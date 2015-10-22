<?php
class ControllerCommonWxapi extends Controller {
	
	public function index(){
		
		$this->load->library('wxapi');
		$this->wx = new Wxapi();
		

		var_dump($this->wx->createMenu());

		/*//加载微信JS-SDK
		$signPackage = $this->wx->GetSignPackage();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['signPackage'] = $signPackage;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/wxapi.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/wxapi.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/wxapi.tpl', $data));
		}*/

	}

	//生成菜单
	public function createMenu()
	{
		$this->load->library('wxapi');
		$this->wx = new Wxapi();

		var_dump($this->wx->createMenu());
	}

	//分享订单
	public function addCustomerService()
	{
		$this->load->library('wxapi');
		$this->wx = new Wxapi();
		$data = '{
	     "kf_account" : "001@gh_86afcd2673bf",
	     "nickname" : "黄睿智",
	     "kf_nick" : "rock",
	     "kf_id" : "001",
	     "password" : "'.md5("i7jhcev21t").'"
		}';
		var_dump($this->wx->addCustomerService($data));
	}

	public function listCustomerService()
	{
		$this->load->library('wxapi');
		$this->wx = new Wxapi();
		$data = "";
		var_dump($this->wx->listCustomerService());
	}

	public function getBaseAuthorize()
	{
		$this->load->library('wxapi');
		$this->wx = new Wxapi();

		$this->wx->getBaseAuthorize();
	}

	public function getCustomer()
	{
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomerByEmail("178015846@qq.com");
		var_dump($customer_info);
	}

	public function queryEx()
	{
		$this->load->library('wxapi');
		$this->wx = new Wxapi();

		$this->wx->queryExpress("210941926049","百世汇通");
	}

	//检测accesstoken的有效性
	private function checkAccessToken($email,$code = null)
	{	
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomerByEmail($email);
		$this->load->library('wxapi');
		$this->wx = new Wxapi();
		if (!$customer_info) {
			//自动注册
			$data['customer_group_id'] = 1;
			$data['fullname'] = "rocktest";
			$data['email'] = $email;
			$data['password'] = $password;
			$data['newsletter'] = 0;
			$customer_id = $this->model_account_customer->addCustomer($data);

			$customer_info = $this->model_account_customer->getCustomer($customer_id);
		}
		if($customer_info['access_token'] == "" || !isset($customer_info['access_token'])){
			if($code == null){
				$this->wx->getUserinfoAuthorize();
			}
			$user_access_token = $this->wx->getUserAccessToken($code);
			//存进数据库
			$customer_info['access_token'] = $user_access_token;
			$customer_info['expires_time'] = time() + 7000;
			$this->model_account_customer->editCustomer($customer_info);
			$user_access_data = json_decode($user_access_token);
			$result['access_token'] = $user_access_data->access_token;
			$result['openid'] = $user_access_data->openid;
			return $result;
		}

		$access_token_json = json_decode($customer_info['access_token']);
		if($customer_info['expires_time'] < time()){
			if(isset($access_token_json->unionid)){
				$refresh_access_token = $this->wx->getUserRefreshToken($access_token_json->refresh_token);
				$customer_info['expires_time'] = time() + 7000;
			}else{
				if($code == null){
					$this->wx->getAuthorize();
				}
				$refresh_access_token = $this->wx->getUserAccessToken($code);
				$customer_info['expires_time'] = time() + 7000;
			}
			//存进数据库
			$customer_info['access_token'] = $refresh_access_token;
			$this->model_account_customer->editCustomer($customer_info);
			$user_access_data = json_decode($user_access_token);
			$result['access_token'] = $user_access_data->access_token;
			$result['openid'] = $user_access_data->openid;
			return $result;
		}
		return null;
	}

	//获取accesstoken
	/*protected function getAccessToken()
	{
		$this->load->library('wxapi');
		if(isset($this->session->data['access_token'])&&isset($this->session->data['expires_time'])){
			$wx_config['access_token'] = $this->session->data['access_token'];
			$wx_config['expires_time'] = $this->session->data['expires_time'];
		}else{
			unset($this->session->data['access_token']);
			unset($this->session->data['expires_time']);
			$wx_config['access_token'] = "";
			$wx_config['expires_time'] = 0;
		}
		return $wx_config;
	}*/

	//登录并注册
	protected function doLogin($email,$password)
	{
		$this->event->trigger('pre.customer.login');

		// Check if customer has been approved.
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomerByEmail($email);

		if (!$customer_info) {
			//自动注册
			$data['customer_group_id'] = 1;
			$data['fullname'] = "rocktest";
			$data['email'] = $email;
			$data['password'] = $password;
			$data['newsletter'] = 0;
			$customer_id = $this->model_account_customer->addCustomer($data);

			$this->customer->login($email, $password);

			unset($this->session->data['guest']);

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $customer_id,
				'name'        => $this->request->post['fullname']
			);

			$this->model_account_activity->addActivity('register', $activity_data);

			$this->response->redirect($this->url->link('account/success'));
		}

		if (!$this->error) {
			if (!$this->customer->login($email, $password)) {
				$this->error['warning'] = $this->language->get('error_login');
			} else {
				$this->event->trigger('post.customer.login');
			}
		}

		return !$this->error;

	}
}