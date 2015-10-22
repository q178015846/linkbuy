<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
/*require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';*/

class ControllerPaymentWxpay extends Controller {
	public function index() {
		
		//初始化日志
		$this->load->library('wxpaylog');
		$this->logHandler = new CLogFileHandler(DIR_LOGS."wxpaylogs/".date('Y-m-d').'.log');
		
		$log = WxPayLog::Init($this->logHandler, 15);


		$this->language->load('payment/wxpay');

		$this->load->model('checkout/order');

		$order_id = $this->session->data['order_id'];

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$item_name = $this->config->get('config_name');

		$amount = $order_info['total'];

		$currency_value = $this->currency->getValue('CNY');

		$price = $amount * $currency_value;

		$price = number_format($price,2,'.','');

		$notify_url = HTTPS_SERVER.'catalog/controller/payment/wxpay_callback.php';

		//$total_fee = $price;
		$total_fee = "0.01";

		$out_trade_no = $this->session->data['order_id'];

		$subject = $item_name . ' ' . $this->language->get('text_order') .' '. $order_id;

		

		//打印输出数组信息
		function printf_info($data)
		{
		    foreach($data as $key=>$value){
		        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
		    }
		}

		//①、获取用户openid
		$this->load->library('wxpayapipay');
		$this->tools = new WxPayApiPay();

		$openId = $this->tools->GetOpenid();
		
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody($subject);
		$input->SetAttach("YANKEE");
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee($total_fee);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url($notify_url);
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		//$input->SetOpenid("oCrCkwElKC4YuFiAYe0EvPKa-OEg");
		$this->load->library('wxpayapi');
		$this->wxpayapi = new WxPayApi();
		$order = $this->wxpayapi->unifiedOrder($input);
		//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
		//printf_info($order);
		$jsApiParameters = $this->tools->GetJsApiParameters($order);

		//获取共享收货地址js函数参数
		$editAddress = $this->tools->GetEditAddressParameters();

		//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
		/**
		 * 注意：
		 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
		 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
		 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
		 */
				
		
		//$data['html_text'] = $this->alipaydtsubmit->buildRequestForm($parameter,"get", $this->language->get('button_confirm'));
		$data['jsApiParameters'] = isset($jsApiParameters)?$jsApiParameters:"111";
		$data['editAddress'] = isset($editAddress)?$editAddress:"111";
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wxpay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/wxpay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/wxpay.tpl', $data);
		}
		
	}
	
	

	
}