<?php
if(!$buid){
	header("Location: main.php");die;
}
$sql="select email,email_verify,user from ".MEMBER." where userid='$buid'";
$db->query($sql);
$re=$db->fetchRow();

if($_SESSION['RAND']==$_GET['k']&&$re['email_verify']!=1){
	$sql="update ".MEMBER." set email_verify='1' where userid='$buid'";
	$db->query($sql);
	$_SESSION['RAND']="";
	unset($_SESSION['RAND']);
}
elseif($re['email_verify']!=1&&$_SESSION['RAND']&&$_GET['k']){
	$tpl->assign("error",error);
}
elseif($re['email_verify']!=1){
	header("Location: ?m=member&s=new_email_reg_two");die;
}
else{
	header("Location: main.php");die;
}

$tpl->assign("re",$re);
$tpl->assign("config",$config);
include_once("footer.php");
$output=tplfetch("new_email_reg_four.htm",$flag,true);
?>