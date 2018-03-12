<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include_once("config/reg_config.php");
if($reg_config)
{
	$config = array_merge($config,$reg_config);
}
@include_once("config/module_points_config.php");
if($module_points_config)
{
	@$config = array_merge($config,$module_points_config);
}

if($buid)
{	//已经登录
	msg('main.php');
}
if(is_array($stop_reg))
{
	stop_ip($stop_reg);
	unset($stop_reg);
}
//----------------------------------------------------
if(!empty($_POST['user'])&&strtolower($_POST['yzm'])==strtolower($_SESSION['auth']))
{
	if($config['closetype']==2)
	{	//关闭注册
		die('access dined!');
	}
	if($config['user_reg_verf'])
	{	//验证码不对
		if(trim($_POST['ckyzwt'])!=trim($_SESSION['YZWT']))
			 die("Verification question error...");
	}
	if($config['inhibit_ip']==1)
	{	//ip禁止注册
		$ip=getip();
		if(empty($ip))
			die("Can not get you IP...");
		else
		{	
			$config['exception_ip']=explode("\r\n",$config['exception_ip']);
			if(!in_array($ip,$config['exception_ip']))
			{
				$sql="select ip from ".MEMBER." where ip='$ip'";
				$db->query($sql);
				if($db->num_rows())
					die("Your IP has been registered...");
				unset($sql);
			}
		}
	}
	if($config['openbbs']==2)
	{	//关联UCHENTER
		include_once('uc_client/client.php');
		$user=trim($_POST['user']);
		$pass=trim($_POST['password']);
		$email=trim($_POST['email']);
		$regtime=time();
		$uid = uc_user_register($user, $pass, $email);
		if($uid>0)
		{
			doreg($uid);
		}
	}
	else
		doreg();
}
function doreg($guid=NULL)
{
	global $db,$config,$ip;
	$user=$_POST['user'];
	$pass=$_POST['password'];
	$email=$_POST['email'];
	$ip=getip();$ip=empty($ip)?NULL:$ip;
	$lastLoginTime=time();
	$regtime=date("Y-m-d H:i:s");
	$user_reg=$config['user_reg']==3?"1":"2";
	
	$sql="select * from ".MEMBER." where user='$user'";
    $db->query($sql);
    if($db->num_rows())
		die("User name is have");
	
	$sql="insert into ".MEMBER." (user,password,ip,lastLoginTime,email,regtime,statu) values ('$user','".md5($pass)."','$ip','$lastLoginTime','$email','$regtime','$user_reg')";
	$re=$db->query($sql);
	$userid=$db->lastid();

	$sql="update ".MEMBER." set number='$userid' where userid='$userid'";
	$re=$db->query($sql);	
		
	if($userid)
	{	
		$points=$config['reg_points']?$config['reg_points']:"0";
		$sql="INSERT INTO ".MEMBERCOUNT." (member_id,points) VALUES ('$userid','$points')";
		$re=$db->query($sql);
		if($points>0)
		{
			include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
			$member=new member();
			$flag=$member->add_points($points,'5','',$userid);
		}
		if($re)
		{
			//---------------绑定一键连接
			if(!empty($_POST['connect_id']))
			{
				$sql="update ".USERCOON." set userid='$userid' where id='$_POST[connect_id]'";
				$db->query($sql);
			}
			//---------------设置加密的cookie
			bsetcookie("USERID","$userid\t$user",NULL,"/",$config['baseurl']);
			setcookie("USER",$user,NULL,"/",$config['baseurl']);
			header("Location: ".$config["weburl"]."/?m=member&s=new_email_reg_two");
		}
	 }
	 else
		 die("Can not register...");
}
$template="register.htm";
include_once("footer.php");	
$tpl->display($template);
?>