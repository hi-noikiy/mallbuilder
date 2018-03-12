<?php
include_once("config/reg_config.php");
$config = array_merge($config,$reg_config);
if(!$buid){
	header("Location: main.php");die;
}
$sql="select user,email,email_verify from ".MEMBER." where userid='$buid'";
$db->query($sql);
$re=$db->fetchRow();
if($re['email_verify']==1){
	header("Location: ".$config['weburl']."?m=member&s=new_email_reg_four");die;
}
if(!$re['email']){
	header("Location: ".$config['weburl']."?m=member&s=new_email_reg_two");die;
}
$tpl->assign("email",$re['email']);
$tpl->assign("config",$config);
include_once("footer.php");
$output=tplfetch("new_email_reg_three.htm",$flag,true);
?>