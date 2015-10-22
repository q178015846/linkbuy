<?php
class ControllerAccountShare extends Controller {
	public function index()
	{
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		//加载微信JS-SDK
		$this->load->library('wxapi');
		$this->wx = new Wxapi();
		$signPackage = $this->wx->GetSignPackage();

		$data['signPackage'] = $signPackage;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/share.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/share.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/share.tpl', $data));
		}
	}
}