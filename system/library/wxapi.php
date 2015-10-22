<?php
require_once 'phpqrcode.php';
require_once "WxPay.Config.php";

class Wxapi {
	private $wx_appid = WxPayConfig::APPID;
	private $wx_appsecret = WxPayConfig::APPSECRET;
	//生成NoneStr的验证串
	private $wx_chars = WxPayConfig::CHARS;
	//appid
	//private $wx_appid = "wxdd144819072bd8ca";
	//appsecret
	//private $wx_appsecret = "4OVTtcggXhHEkU30LRQFhQJdsQZ2-wFdte62IRQ5w41sb8S9YXIqQkjKB_qyd-y7";
	private $access_token;
	//private $baseCgiUrl = "https://qyapi.weixin.qq.com/cgi-bin/";
	private $baseUrl = "https://api.weixin.qq.com/";
	private $baseCgiUrl = "https://api.weixin.qq.com/cgi-bin/";
	//private $webUrl = "http://www.beyankee.com/";
	private $webUrl = "http://www.beyankee.com/";
	
	public function __construct() {
		$this->access_token = $this->getAccessToken();
	}

/*===========================S：基础方法=====================================*/

	//获取access_token
	private function getAccessToken() {
	    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	    $data = json_decode(file_get_contents(DIR_CACHE."access_token.json"));
	    if ($data->expire_time < time()) {
	      // 如果是企业号用以下URL获取access_token
	      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
	      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->wx_appid."&secret=".$this->wx_appsecret;
	      $res = json_decode($this->http_request($url));
	      $access_token = $res->access_token;
	      if ($access_token) {
	        $data->expire_time = time() + 7000;
	        $data->access_token = $access_token;
	        $fp = fopen(DIR_CACHE."access_token.json", "w");
	        fwrite($fp, json_encode($data));
	        fclose($fp);
	      }
	    } else {
	      $access_token = $data->access_token;
	    }
	    return $access_token;
	}

	//获取js票
	private function getJsApiTicket() {
    	// jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	    $data = json_decode(file_get_contents(DIR_CACHE."jsapi_ticket.json"));
	    if ($data->expire_time < time()) {
	      $accessToken = $this->getAccessToken();
	      // 如果是企业号用以下 URL 获取 ticket
	      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
	      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
	      $res = json_decode($this->http_request($url));
	      $ticket = $res->ticket;
	      if ($ticket) {
	        $data->expire_time = time() + 7000;
	        $data->jsapi_ticket = $ticket;
	        $fp = fopen(DIR_CACHE."jsapi_ticket.json", "w");
	        fwrite($fp, json_encode($data));
	        fclose($fp);
	      }
	    } else {
	      $ticket = $data->jsapi_ticket;
	    }

	    return $ticket;
	  }

	 //获取签名包
	public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->wx_appid,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

	//生成noncestr
	private function createNonceStr($length = 16) {
		//$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
		  $str .= substr($this->wx_chars, mt_rand(0, strlen($this->wx_chars) - 1), 1);
		}
		return $str;
	}

	//发送请求
	private function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //cgi-url签名
    private function accessCgiTokenUrl($middleUrl)
    {
    	return $this->baseCgiUrl.$middleUrl."?access_token=".$this->access_token;
    }

    //拼接签名的url
    private function accessTokenUrl($middleUrl)
    {
    	return $this->baseUrl.$middleUrl."?access_token=".$this->access_token;
    }

/*===========================E：基础方法=====================================*/


/*===========================S：接口方法=====================================*/


	//发送文本消息
	public function sendTextMsg($text,$openid)
	{
		//$get_template_url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=".$this->access_token;
		$url = $this->accessCgiTokenUrl("message/custom/send");
		$post_msg = '{
		    "touser":"'.$openid.'",
		    "msgtype":"text",
		    "text":
		    {
		         "content":"'.$text.'"
		    }
		}';
		$result = $this->http_request($url,$post_msg);


		return json_decode($result);
	}

	//发送图片信息-客服接口
	public function setImgMsg($url)
	{
		$url = $this->accessCgiTokenUrl("message/custom/send");
		$post_msg = '{
		   "touser": "oCrCkwElKC4YuFiAYe0EvPKa-OEg,oCrCkwJz4TVqPrm3HX2uIgc5VxIw",
		   "msgtype":"image",
		    "image":
		    {
		      "media_id":"'.$url.'"
		    }
		}';
		$result = $this->http_request($url,$post_msg);


		return json_decode($result);
	}

	//发送图文信息
	public function sendTxtImgMsg($openid,$data)
	{
		$url = $this->accessCgiTokenUrl("message/custom/send");
		$post_msg = '{
		    "touser":"'.$openid.'",
		    "msgtype":"news",
		    "news":{
		        "articles": [
		         {
		             "title":"'.$data["title"].'",
		             "description":"'.$data["description"].'",
		             "url":"'.$data["url"].'",
		             "picurl":"'.$data["picurl"].'"
		         }
		         ]
		    }
		}';
		$result = $this->http_request($url, $post_msg);
		return json_decode($result);
	}

	//发送模板信息
	public function sendModelMsg($data)
	{
		$set_tpl_url = $this->accessCgiTokenUrl("message/template/send");

		/*$post_data_settpl = '  {
           "touser":"oCrCkwElKC4YuFiAYe0EvPKa-OEg",
           "template_id":"yoXVOPzjp4Sdffu2WO3PhWMGl9WFpvUfRl7HYmU0WSM",
           "url":"http://weixin.qq.com/download",
           "topcolor":"#FF0000",
           "data":{
           	"touser":{
           		"value":"oCrCkwJz4TVqPrm3HX2uIgc5VxIw",
           		"color":"#173177"
           	},
           	"prices":{
           		"value":"12.20元",
           		"color":"#173177"
           	},	
           	"status":{
           		"value":"已发货",
           		"color":"#173177"
           	}
           }
       	}';*/

       	$result = $this->http_request($set_tpl_url, $data);

		return json_decode($result);
	}

	//userid转换openid接口
	public function qy2oauth($userid,$agentid = 23)
	{
		$url = $this->accessCgiTokenUrl("user/convert_to_openid");
		$post_data = '{
		   "userid": "'.$userid.'",
		   "agentid": "'.$agentid.'"
		}';

		$result = $this->http_request($url, $post_data);

		return json_decode($result);
	}

	//openid转换成userid接口
	public function oauth2qy($openid)
	{
		$url = $this->accessCgiTokenUrl("user/convert_to_userid");
		$post_data = '{
		   "userid": "'.$userid.'",
		   "agentid": "'.$agentid.'"
		}';

		$result = $this->http_request($url, $post_data);

		return json_decode($result);
	}


	//创建菜单
	public function createMenu()
	{
		$url = $this->accessCgiTokenUrl("menu/create");
		//$go_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->wx_appid."&redirect_uri=".urlencode($this->webUrl."yankee/")."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
		$go_url = $this->webUrl."yankee";
		//$order_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->wx_appid."&redirect_uri=".urlencode($this->webUrl."yankee/index.php?route=account/order")."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
		$order_url = $this->webUrl."yankee/index.php?route=account/order/list_mobile";
		$post_data = ' {
		     "button":[
		     {	
		          "type":"view",
		          "name":"查看订单",
		          "url":"'.$order_url.'"
		      },
		      {
		       	  "type":"view",
		          "name":"挑选香薰",
		          "url":"'.$go_url.'"
		       },{
		           "name":"发现",
		           "sub_button":[
		           {	
		               "type":"view",
		               "name":"优惠券",
		               "url":"'.$go_url.'"
		            },
		            {
		               "type":"view",
		               "name":"Yankee",
		               "url":"'.$this->webUrl.'yankee/index.php?route=common/wxapi"
		            },
		            {
		            	"type":"view",
		            	"name":"抽奖",
		            	"url":"'.$this->webUrl.'yankee/index.php?route=account/lottery"
		            },
		            {
		            	"type":"view",
		            	"name":"关于我们",
		            	"url":"'.$this->webUrl.'site/"
		            },{
		            	"type":"view",
		            	"name":"test",
		            	"url":"'.$this->webUrl.'yankee/index.php?route=payment/wxpay"
		            }]
		       }]
		 }';
		 $this->http_request($url, $post_data);
	}

	//新增临时素材
	public function uploadTmpMedia()
	{
		$url = $this->accessCgiTokenUrl("media/upload");

	}

	//拉取用户普通授权
	public function getBaseAuthorize($redirect_uri = "http://www.beyankee.com/yankee/")
	{
		$go_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->wx_appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
		return $go_url;
	}

	//拉取用户详细授权
	public function getUserinfoAuthorize($redirect_uri = "http://www.beyankee/yankee/")
	{
		$go_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->wx_appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
		return $go_url;
	}

	//通过snsapi_base方式获取openid
	public function getOpenid($redirect_uri = "http://www.beyankee.com/yankee/",$code)
	{
		//code来换取access_token和openid的url
		//通过code获得openid
		if (!isset($code)){
			//触发微信返回code码
			$go_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->wx_appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
			Header("Location: $go_url");
			exit();
		} else {
			//获取code码，以获取openid
		    $get_accesstoken_url = $this->baseUrl."sns/oauth2/access_token?appid=".$this->wx_appid."&secret=".$this->wx_appsecret."&code=".$code."&grant_type=authorization_code";
			$get_accesstoken_json = $this->http_request($get_accesstoken_url);
			$get_accesstoken_data = json_decode($get_accesstoken_json);
			$openid = $get_accesstoken_data->openid;
			return $openid;
		}
	}

	//通过snsapi_userinfo方式获取用户的access_token
	public function getUserAccessToken($code)
	{
		//code来换取access_token和openid的url
		$get_accesstoken_url = $this->baseUrl."sns/oauth2/access_token?appid=".$this->wx_appid."&secret=".$this->wx_appsecret."&code=".$code."&grant_type=authorization_code";
		$get_accesstoken_json = $this->http_request($get_accesstoken_url);
		if(!$get_accesstoken_json){
			return "错误授权";
		}
	/*	$get_accesstoken_data = json_decode($get_accesstoken_json);
		if(isset($get_accesstoken_data->errcode)){
			return $get_accesstoken_data->errmsg;
		}*/
		return $get_accesstoken_json;
	}

   //获取refresh_token
   public function getUserRefreshToken($refresh_token)
   {
   		$url = $this->baseUrl."sns/oauth2/refresh_token?appid=".$this->appid."&grant_type=refresh_token&refresh_token=".$refresh_token;
   		$get_refreshtoken_json = $this->http_request($url);
		if(!$get_refreshtoken_json){
			return "错误授权";
		}
		return $get_refreshtoken_json;
   }

	//拉取用户信息
	public function getUserInfo($access_token,$openid,$lang = "zh_CN")
	{
		$url = $this->baseUrl."sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=".$lang;
		$return_json = $this->http_request($url);
		if(!$return_json){
			return "错误授权";
		}
		$return_data = json_decode($return_json);
		if(isset($return_data->errcode)){
			return $return_data->errmsg;
		}
		return $return_data;
	}

	//生成二维码
	public function generateQRCode($link)
	{
		$errorCorrectionLevel = 'L';//容错级别   
		$matrixPointSize = 6;//生成图片大小 
		$qrcode_path = DIR_UPLOAD."qrcode/";
		$qrcode_name = time();
		//生成二维码图片   
		QRcode::png($link, $qrcode_path.$qrcode_name.'.png', $errorCorrectionLevel, $matrixPointSize, 2);
		/*$logo = 'placeholder.png';//准备好的logo图片   
		$QR = $qrcode_path.$qrcode_name.'.png';//已经生成的原始二维码图   

		if ($logo !== FALSE) {   
		    $QR = imagecreatefromstring(file_get_contents($QR));   
		    $logo = imagecreatefromstring(file_get_contents($logo));   
		    $QR_width = imagesx($QR);//二维码图片宽度   
		    $QR_height = imagesy($QR);//二维码图片高度   
		    $logo_width = imagesx($logo);//logo图片宽度   
		    $logo_height = imagesy($logo);//logo图片高度   
		    $logo_qr_width = $QR_width / 5;   
		    $scale = $logo_width/$logo_qr_width;   
		    $logo_qr_height = $logo_height/$scale;   
		    $from_width = ($QR_width - $logo_qr_width) / 2;   
		    //重新组合图片并调整大小   
		    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
		    $logo_qr_height, $logo_width, $logo_height);   
		}   
		//输出图片   
		imagepng($QR, 'helloweixin.png');   
		echo '<img src="helloweixin.png">';  */
	}

	//从微信获取二维码
	public function getQRCodefromWX()
	{
		//获取ticket的url
		$ticket_url = $this->accessCgiTokenUrl("qrcode/create");

		$ticket_post_data = '{"expire_seconds": 5000, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.time().'}}}';
		$ticket_jason = $this->http_request($ticket_url, $ticket_post_data);
		if($ticket_jason){
			$ticket_data = json_decode($ticket_jason);
			//用ticket换取二维码
			$qrcode_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket_data->ticket);
			return $qrcode_url;
		}
		return "";
	}

	//添加客服
	public function addCustomerService($cs_data)
	{
		$url = $this->accessTokenUrl("customservice/kfaccount/add");

		return json_decode($this->http_request($url, $cs_data));
	}

	//修改客服
	public function updateCustomerService($cs_data)
	{
		$url = $this->accessTokenUrl("customservice/kfaccount/update");

		return json_decode($this->http_request($url, $cs_data));
	}

	//删除客服
	public function delCustomerService($cs_data)
	{
		$url = $this->accessTokenUrl("customservice/kfaccount/del");

		return json_decode($this->http_request($url, $cs_data));
	}

	//设置客服头像
	public function setCustomerServiceHeadIcon($cs_data)
	{
		$url = $this->accessTokenUrl("customservice/kfaccount/del");

		return json_decode($this->http_request($url, $cs_data));
	}

	//获取客服列表
	public function listCustomerService()
	{
		$url = $this->accessCgiTokenUrl("customservice/getkflist");

		return json_decode($this->http_request($url));
	}


	//查询快递接口www.apix.cn
	public function queryExpress($nu,$com)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://a.apix.cn/apixlife/express/delivery?id=".$nu."&logistics=".$com,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "accept: application/json",
		    "apix-key: 852059bd2303484a5d36dff1dc591bcc",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return "cURL Error #:" . $err;
		} else {
		  return json_decode($response);
		}
	}
	

}