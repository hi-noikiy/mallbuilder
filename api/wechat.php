<?php
include_once("../includes/global.php");
@include_once("../config/wechat_config.php");
$wechat=$wechat_config['wechat']?$wechat_config['wechat']:"";
define("TOKEN", $wechat);
$wechatObj = new wechatCallbackapiTest();
if($_GET["echostr"]&&$_GET["signature"]&&$_GET["timestamp"]&&$_GET["nonce"])
{
	$wechatObj->valid();	
}
else
{
	$wechatObj->responseMsg();
}
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){

			echo $echoStr;
        	exit;
        }
    }
    public function responseMsg()
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		global $db,$config;
		if (!empty($postStr)){
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
				$RX_TYPE = trim($postObj->MsgType);
				$num=0;
				$str="";
				if($RX_TYPE=='text')
				{
					if(!empty($keyword))
					{
						$sql="select pname,id,pic from ".PRO." where pname like '%$keyword%' order by id desc limit 0,4";
						$db->query($sql);
						$re=$db->getRows();
						foreach($re as $val)
						{
							$str.="<item>
								 <Title><![CDATA[".$val['pname']."]]></Title> 
								 <Description><![CDATA[]]></Description>
								 <PicUrl><![CDATA[".$val['pic']."]]></PicUrl>
								 <Url><![CDATA[".$config['weburl']."?m=product&s=detail&id=".$val['id']."]]></Url>
							</item>";
							$num++;
						}
						if($num>0)
						{
							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>".$num."</ArticleCount>
							<Articles>".$str."</Articles>
							<FuncFlag>1</FuncFlag>
							</xml>"; 
							$msgType = "text";
							$contentStr = "";
							$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
							echo $resultStr;
						}
					}
					else
						echo "Input something...";
				}
				else{
                	echo "Input something...";
                }
        }else{
        	echo "";
        	exit;
        }
    }
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
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