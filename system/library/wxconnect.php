<?php

define("TOKEN", "yaoyao20150805");

$wechatObj = new Wxconnect();
//$wechatObj->valid();
$wechatObj->responseMsg();
class Wxconnect
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }


    //关注后答复
    public function responseMsg()
    {
        //get post data, May be due to the different environments  
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
          //extract post data  
        if (!empty ($postStr)) {
            $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;  
            $toUsername = $postObj->ToUserName;  
            $keyword = trim($postObj->Content);  
            $Event = trim($postObj->Event); 
            $EventKey = urlencode(trim($postObj->EventKey));
            $time = time();  
            $textTpl = "<xml>  
                        <ToUserName><![CDATA[%s]]></ToUserName>  
                        <FromUserName><![CDATA[%s]]></FromUserName>  
                        <CreateTime>%s</CreateTime>  
                        <MsgType><![CDATA[%s]]></MsgType>  
                        <Content><![CDATA[%s]]></Content>  
                        <FuncFlag>0</FuncFlag>  
                        </xml>"; 

             //测试事件推送  
            if($Event=="subscribe"){  
                $contentStr = "欢迎关注YANKEE CANDLE香薰体验馆公众平台!";  
                $msgType = "text";  
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                echo $resultStr;  
            }else if($Event == "VIEW"){ //菜单事件推送
                echo "你好";
            }


            //回复内容
            if(strstr($keyword, "你好") || strstr($keyword, "您好") || strstr(strtolower($keyword), "hellow")){
                $contentStr = "您好，点击链接尽情体验<a href=\"http://120.24.157.131/yankee/\">Yankee Candle</a>的极致享受吧！";  
                $msgType = "text";  
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                echo $resultStr;  
            }else if(strstr(haystack, needle)){

            }

            if (!empty ($keyword)) {  
  
                $contentStr = "欢迎来到Yankee Candle世界！";  
  
                $msgType = "text";  
  
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                echo $resultStr;  
            } else {  
                echo "请尝试换个姿势再输入吧，亲。";  
            }  
        } else {  
            echo "";  
            exit;  
        }  
    }

    private function checkSignature()
    {
       // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
?>