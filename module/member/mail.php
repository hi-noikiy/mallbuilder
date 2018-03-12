<?php
include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
$member=new member();
//===================================================================
$de=$member->get_member_detail('');
if($de['email_verify']==0&&!empty($de['email'])){
	$page="verifyMail.htm";
}
else{
	$page="verifyMail.htm";
}

if($_POST['submit']=='verify')
{	
	$flag=$member->verifymail($buid);
	if($flag=='1')
		$admin->msg("main.php?m=member&s=mail",'验证码错误！','failure');
	elseif($flag=='2')
		$admin->msg("main.php?m=member&s=mail",'密码错误！','failure');
	else
		$page='verifyMail_1.htm';
		
}if($_POST['submit']=='edit')
{	
	$flag=$member->editmail($buid);
	$page='verifyMail_1.htm';
	if($flag=='1')
		$admin->msg("main.php?m=member&s=mail",'验证码错误！','failure');
	elseif($flag=='2')
		$admin->msg("main.php?m=member&s=mail",'该邮箱已被使用，请更换其它邮箱！','failure');
	else
		$page='verifyMail_2.htm';
}
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch($page);
?>