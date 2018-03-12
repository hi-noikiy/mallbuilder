<?php
include_once("config/reg_config.php");
$config = array_merge($config,$reg_config);
include_once("config/seo_config.php");
$config = array_merge($config,$config);
include("config/nav_menu.php");

if(!$buid){
	header("Location: main.php");die;
}

$sql="select userid,user,email,password,email_verify from ".MEMBER." where userid='$buid'";
$db->query($sql);
$re=$db->fetchRow();
if($re['email_verify']==1){
	header("Location: ".$config['weburl']."?m=member&s=new_email_reg_four");die;
}

if($_POST['email'])
{
	$email=$_POST['email'];
	$sql="select * from ".MEMBER." where email='$email'";
    $db->query($sql);
    if($db->num_rows()){
		header("Location: ".$config['weburl']."/?m=member&s=new_email_reg_two");
	}
	else{
		
		$sql="update ".MEMBER." set email='$email' where userid='$buid'";
		$db->query($sql);
		
		$auth=md5($config['authkey']);
		$url=$config['weburl']."/pay/api/member.php?userid=$re[userid]&email=$email&auth=$auth";
		get_url_content($url);
		
		$rand=create_password("8");
		$_SESSION['RAND']=$rand;
		$time=md5(create_password("5"));
		$link=$config['weburl']."/?m=member&s=new_email_reg_four&u=$time&k=$rand";
	
		$mail_temp=get_mail_template('active');
		$con=$mail_temp['message'];
		$title=$mail_temp['title'];
		$user=$re['user'];
		$time=date("Y-m-d H:i:s");
		$logo='<img height="24" src="'.$config['weburl'].'/image/logo.png"  style="border:none;margin:0;">';
	
		if($nav_menu)
		{
			foreach($nav_menu as $val)
			{
				if($val['link_addr']=='http')
				{
					$url=$val['link_addr'];
				}
				else
				{
					$url=$config['weburl'].'/'.$val['link_addr'];
				}
				if(!$str)
				$str.="<a target='_blank' style='padding:0 5px;color:#FFF;text-decoration:none;' href='".$url."'>".$val['menu_name']."</a>";
				else
				$str.="<font>|</font><a target='_blank' style='padding:0 5px;color:#FFF;text-decoration:none;' href='".$url."'>".$val['menu_name']."</a>";
			}
		}
		$ar1=array('[menu]','[time]','[logo]','[member_name]','[weburl_name]','[link]','[weburl_email]','[weburl_tel]','[weburl_url]','[weburl_desc]','[weburl_desc]');
		$ar2=array($str,$time,$logo,$user,$config['company'],$link,$config['email'],$config['owntel'],$config['weburl'],$config['description']);
		$con=str_replace($ar1,$ar2,$con);
	
		$ar3=array('[member_name]','[weburl_name]');
		$ar4=array($user,$config['company']);
		$title=str_replace($ar3,$ar4,$title);
		send_mail($email,$user,$title,$con);
		unset($con);unset($title);
		//提醒查收邮件。
		if($config['openregemail'])
		{	//注册欢迎邮件发送
			$mail_temp=get_mail_template('register');
			$con=$mail_temp['message'];
			$title=$mail_temp['title'];
			
			$ar1=array('[menu]','[logo]','[member_name]','[member_email]','[weburl_name]','[weburl_email]','[weburl_tel]','[weburl_url]','[weburl_desc]','[weburl_desc]');
			$ar2=array($str,$logo,$user,$email,$config['company'],$config['email'],$config['owntel'],$config['weburl'],$config['description']);
		
			$con=str_replace($ar1,$ar2,$con);
			$ar3=array('[member_name]','[weburl_name]','[weburl_url]');
			$ar4=array($user,$config['company'],$config['weburl_url']);
			$title=str_replace($ar3,$ar4,$title);
			send_mail($email,$user,$title,$con);
			unset($con);unset($title);
		}
		header("Location: ".$config['weburl']."/?m=member&s=new_email_reg_three");
	}
}
$tpl->assign("config",$config);
include_once("footer.php");
$output=tplfetch("new_email_reg_two.htm",$flag,true);
?>