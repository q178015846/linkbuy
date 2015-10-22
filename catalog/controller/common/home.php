<?php
class ControllerCommonHome extends Controller {
	public function index() {

		//看是否从回调地址跳转过来的
		//正式环境去掉该注释
		$this->load->library('wxapi');
		$this->wx = new Wxapi();
		/*$openid = $this->wx->getOpenid($this->url->link('common/home', '', 'SSL'),$this->request->get['code']);
		if(isset($openid) && $openid != null){
			//验证是否已经登录
			if(!$this->doLogin($openid)){
				$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

				$this->response->redirect($this->url->link('account/login', '', 'SSL'));
			}
		}*/
		
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		//加载微信JS-SDK
		$signPackage = $this->wx->GetSignPackage();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['signPackage'] = $signPackage;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}



	//登录并注册
	protected function doLogin($openid,$password = "123456")
	{
		$this->event->trigger('pre.customer.login');
		if ($this->customer->isLogged()) {
			return true;
		}
		//$email = $openid_data->openid."@beyankee.com";
		$email = $openid."@beyankee.com";

		// Check if customer has been approved.
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomerByEmail($email);

		if (!$customer_info) {
			//自动注册
			$this->load->library('wxapi');
			$this->wx = new Wxapi();
			//$userinfo = $this->wx->getUserInfo($openid_data->access_token,$openid_data->openid);
			$data['customer_group_id'] = 1;
			$data['telephone'] = "13602416028";
			//$data['fullname'] = $userinfo->nickname;
			$data['fullname'] = "user";
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
				//'name'        => $userinfo->nickname
				'name'        => "用户".$customer_id
			);

			$this->model_account_activity->addActivity('register', $activity_data);

			//$this->response->redirect($this->url->link('account/success'));
		}

		

		if (!$this->error) {
			if (!$this->customer->login($email, $password)) {
				return false;
				//$this->error['warning'] = $this->language->get('error_login');
			} else {
				$this->event->trigger('post.customer.login');
				return true;
			}
		}

		//return !$this->error;
		return false;

	}

	//检测accesstoken的有效性
	private function checkAccessToken($email,$code = null)
	{
		$this->load->library('wxapi');
		$this->wx = new Wxapi();
		$customer_info = $this->model_account_customer->getCustomerByEmail($email);
		if(!$customer_info || $customer_info['access_token'] == "" || !isset($customer_info['access_token'])){
			$user_access_token = $this->wx->getUserAccessToken($code);
			return $user_access_token;
		}

		$access_token_json = json_decode($customer_info['access_token']);
		if($access_token_json->expire_time < time()){
			return false;
		}
	}

}