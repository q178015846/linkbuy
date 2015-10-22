<?php
class ControllerAccountLottery extends Controller {
	public function index() {
		$data['title'] = "抽奖";
		$data['lottery_title'] = "无敌大转盘";
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/lottery.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/lottery.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/lottery.tpl', $data));
		}
	}
}