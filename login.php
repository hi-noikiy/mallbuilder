<?php
if(!empty($_GET["action"]))
	$post=$_GET;
else
	$post=$_POST;

if(!empty($_GET['forward'])&&strpos($_GET['forward'],'script')>0)
	header("Location:login.php");//ȫ

if(!empty($post["action"])&&$post["action"]=="submit")
{
	include_once("includes/global.php");
	include_once("includes/smarty_config.php");
	include_once("config/reg_config.php");
	if(strtolower($_SESSION["auth"])!=strtolower($post["randcode"])&&empty($post['first_index'])&&empty($post['connect_id']))
	{
		header("Location: login.php?erry=-3");//֤
		exit();
	} 
	$config = array_merge($config,$reg_config);
	if($config['openbbs']==2)
	{	//ucenter1.5 login
		$sql="select userid,user,password,email from ".MEMBER." a where user='$post[user]' or email='$post[user]'";
		$db->query($sql);
		$re=$db->fetchRow();//bbûǷ
		if(!empty($re['password']))
		{
			if(substr($re['password'],0,4)=='lock')
				msg("login.php?erry=-4&connect_id=$post[connect_id]");//֮ǰʹһ빦ܣ�?
			if($re['password']!=md5($post['password']))
				msg("login.php?erry=-2&connect_id=$post[connect_id]");//
		}
		include_once('uc_client/client.php');
		list($uid, $username, $password, $email) = uc_user_login($post['user'], $post['password']);//ucǷ
		
		if($uid>0||$re["userid"])
		{	//ucBB֮һ˻ȷִ²
		
			if($uid<=0&&$re["userid"]>0)//UCڣ£´
			{
				$uid = uc_user_register($re['user'], $post['password'], $re['email']);
				if($re['pid'])
					login($re['pid'],$re['user'],$re['userid']);//˺ŵ¼
				else
					login($re['userid'],$re['user']);//˺ŵ¼
			}
			elseif($uid>0&&$re["userid"]<=0)//UCBB
			{
				$dbc=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);
				
				$ip=getip();
				$dbc->query("insert into ".MEMBER." (user,email,password,ip) values 
				('$post[user]','$email','".md5($post['password'])."','$ip')");
				$re['userid']=$dbc->lastid();
				$re['user']=$_POST['user'];
				
				if(empty($config['user_reg']))
					$user_reg=1;
				elseif($config['user_reg']==3)
					$user_reg=1;
				else
					$user_reg=$config['user_reg'];
				login($re['userid'],$re['user']);//վ¼
			}
			else
			{
				if($re['pid'])
					login($re['pid'],$re['user'],$re['userid']);//˺ŵ¼
				else
					login($re['userid'],$re['user']);//˺ŵ¼
			}
			echo uc_user_synlogin($uid);//գͬ¼
			$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
			msg($forward);
		}
		else
		{
			header("Location: login.php?erry=-1&connect_id=".$post['connect_id']);//û
			exit();
		}
	}
	else
	{	
		// no ucenter login
		$sql="select * from ".MEMBER." where user='$post[user]' or email='$post[user]'";
		$db->query($sql);
		$re=$db->fetchRow();
		if($re["userid"])
		{
			if(substr($re['password'],0,4)=='lock')
				msg("login.php?erry=-4&connect_id=$post[connect_id]");//֮ǰʹһ빦ܣ�?
			if($re['password']!=md5($post['password']))
				msg("login.php?erry=-2&connect_id=$post[connect_id]");//
			
			if($re["password"]==md5($post['password']))
			{
				if($re['pid'])
					login($re['pid'],$re['user'],$re['userid']);//˺ŵ¼
				else
					login($re['userid'],$re['user']);
					
				$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
				msg($forward);
			}
		}
		else
			msg('login.php?erry=-1&connect_id='.$post['connect_id']);//û
	}
}
//========================================================
function login($uid,$username,$pid=NULL,$type=NULL)
{
	global $post,$config;
	$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);
	if($uid)
		$sql="select lastLoginTime,userid,user,regtime,statu from ".MEMBER." a where a.userid='$uid'";
	else
		$sql="select lastLoginTime,userid,user,regtime,statu from ".MEMBER." where user='$username'";
	$db->query($sql);
	$re=$db->fetchRow();
	
	if($type)
	{
		$time=time()+3600*24*7;
	}
	else
	{
		$time=NULL;
	}
	bsetcookie("USERID","$uid\t$re[user]\t$pid",$time,"/",$config['baseurl']);
	setcookie("USER",$re['user'],$time,"/",$config['baseurl']);
	
	$_SESSION["STATU"]=$re['statu'];
	
	$sql="update ".MEMBER." set lastLoginTime='".time()."' WHERE userid='$uid'";
	$db->query($sql);
	
	$sql="select member_id from ".MEMBERCOUNT." where member_id='$uid'";
	$db->query($sql);
	$mid=$db->fetchField("member_id");
	if(!$mid)
	{
		$sql="INSERT INTO ".MEMBERCOUNT." (member_id) VALUES ('$uid')";
		$re=$db->query($sql);
	}
	//--------------------------------------qq
	if(!empty($post['connect_id']))
	{
		$sql="update ".USERCOON." set userid='$uid' where id='$post[connect_id]'";
		$db->query($sql);
	}
}
function do_post($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret;
}
function get_url_contents($url)
{
    if(ini_get("allow_url_fopen") == "1")
        return file_get_contents($url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result =  curl_exec($ch);
    curl_close($ch);
    return $result;
}

//==================================================================================
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include_once("config/reg_config.php");
include_once("config/connect_config.php");//connect
$config = array_merge($config,$reg_config);
$config = array_merge($config,$connect_config);
if($config['sina_connect']==1)//sina
{
	define( "WB_AKEY" , $config['sina_app_id'] );
	define( "WB_SKEY" , $config['sina_key'] );
	define( "WB_CALLBACK_URL" , "$config[weburl]/login.php?type=sina" );
	include_once( 'includes/saetv2.ex.class.php' );
	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	//------------------------------------------
	if($_GET['type']=='sina'&&isset($_REQUEST['code']))
	{
		$keys = array();
		$keys['code'] = $_REQUEST['code'];
		$keys['redirect_uri'] = WB_CALLBACK_URL;
		$token = $o->getAccessToken( 'code', $keys ) ;
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $token['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$ar = $c->show_user_by_id( $uid);//IDȡûȻϢ
		//------------
		$sql="select * from ".USERCOON." where type=2 and client_id='$ar[id]'";
		$db->query($sql);
		$cre=$db->fetchRow();
		if(empty($cre['id']))
		{
			$sql="insert into ".USERCOON." 
			(nickname,figureurl,gender,type,access_token,client_id) 
			values 
			('$ar[name]','$ar[profile_image_url]','$ar[gender]',2,'$token[access_token]','$ar[id]')";
			$db->query($sql);
			$cre['id']=$db->lastid();
		}
		if($cre['userid'])
		{//Ѿ󶨣ת¼
			login($cre['userid'],NULL);
			$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
			msg($forward);
		}
		else
		{//а󶨡
			msg("login.php?connect_id=$cre[id]");
		}
	}
	//-------------------------------------------
	$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
	$tpl->assign("sina_login_url",$code_url);
}
if(!empty($_GET['code'])&&$config['qq_connect']==1&&$_GET['type']!='sina')//QQ
{
	//-----------------
	$config['return']=$config['weburl'].'/login.php';
	$url="https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
	."client_id=$config[qq_app_id]"
	."&client_secret=$config[qq_key]"
	."&code=$_GET[code]"
	."&state=$config[company]"
	."&redirect_uri=$config[return]";
	$takenid=get_url_contents($url);
	//----------------
	$url2="https://graph.qq.com/oauth2.0/me?$takenid";
	$con=get_url_contents($url2);
	$lpos = strpos($con, "(");
	$rpos = strrpos($con, ")");
	$con  = substr($con, $lpos + 1, $rpos - $lpos -1);
	$ar2=json_decode($con,true);
	//----------------
	$url3 = "https://graph.qq.com/user/get_user_info?"
	. $takenid
	. "&oauth_consumer_key=" . $config['qq_app_id']
	. "&openid=" . $ar2["openid"]
	. "&format=json";
	$con=get_url_contents($url3);
	$ar=json_decode($con,true);
	//--------------------------
	$sql="select * from ".USERCOON." where type=1 and openid='$ar2[openid]'";
	$db->query($sql);
	$cre=$db->fetchRow();
	if(empty($cre['id']))
	{
		$sql="insert into ".USERCOON." 
		(nickname,figureurl,gender,vip,level,type,access_token,client_id,openid) 
		values 
		('$ar[nickname]','$ar[figureurl]','$ar[gender]','$ar[vip]','$ar[level]',1,'$takenid','$ar2[client_id]','$ar2[openid]')";
		$db->query($sql);
		$cre['id']=$db->lastid();
	}
	if($cre['userid'])
	{//Ѿ󶨣ת¼
		login($cre['userid'],NULL);
		$forward = $post['forward']?$post['forward']:$config["weburl"]."/main.php";
		msg($forward);
	}
	else
	{//а󶨡
		msg("login.php?connect_id=$cre[id]");
	}
	
}
//===========================================
if($buid&&empty($_GET['style']))
{
	header("Location:main.php");
	exit();
}

include_once("footer.php");
$tpl -> assign("lang",$lang);
$tpl -> assign("current","office");

if(!empty($_GET['style']))
	$tpl->display("login_box.htm");
elseif(!empty($_GET['connect_id']))
	$tpl->display("user_connect.htm");
else
	$tpl->display("login.htm");
?>