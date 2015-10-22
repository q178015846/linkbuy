<?php
// Heading
$_['heading_title']      	= '微信支付';

$_['text_wxpay']	= '<img src="view/image/payment/wxpay.png" alt="微信支付接口" title="微信支付接口" style="border: 1px solid #EEEEEE;" />';

// Text 
$_['text_seller_appid'] 	= 'APPID';
$_['text_security_code'] 	= '商户支付密钥(Key)';
$_['text_partner'] 			= '商户号(MCHID)';
$_['text_payment']       	= '支付方式';
$_['text_success']       	= '成功: 已修改微信支付接口！';
$_['text_edit']          	= '编辑微信支付接口';

// Entry
$_['entry_wxpay_seller_appid'] 		= '绑定支付的APPID';
$_['entry_wxpay_security_code'] 	= '商户支付密钥(Key)';
$_['entry_wxpay_partner'] 			= '商户号(MCHID)';
$_['entry_wxpay_status'] 		    = '状态';
$_['entry_wxpay_sort_order']   		= '排序';
$_['entry_total']					 		= '最低金额';

$_['entry_trade_finished_status']       	= '交易完成';
$_['entry_trade_success_status']       		= '交易成功';

$_['entry_geo_zone']				 		= '区域群组';
$_['entry_log']		 						= '调试日志';

// Help
$_['help_seller_appid']		    			= '绑定支付的APPID（必须配置，开户邮件中可查看）';
$_['help_total']					 		= '为使该支付方式生效，订单所需达到的最低金额';

$_['help_trade_finished']			 		= '该种交易状态只在两种情况下出现：1、开通了普通即时到账，买家付款成功后。2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。';
$_['help_trade_success']			 		= '该种交易状态只在一种情况下出现：开通了高级即时到账，买家付款成功后。';
$_['help_log']			 					= '如果出现异常，通过日志记录相关过程，便于分析处理。';



// Tab
$_['tab_general']					 		= '常规';
$_['tab_order_status']       		 		= '订单状态';

// Error
$_['error_permission']   					= '警告: 无权修改微信支付接口！';
$_['error_wxpay_seller_appid']      = '请填写APPID！'; 
$_['error_wxpay_partner']        	= '请填写商户号(MCHID)！'; 
$_['error_wxpay_security_code']     = '请填写商户支付密钥(Key)！'; 
