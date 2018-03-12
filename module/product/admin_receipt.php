<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//=======================================
if($_POST['act']=='act' && is_numeric($_POST['id']))
{
	$id=$_POST['id'];
	if(empty($_POST['password']))
	{
		$admin->msg("main.php?m=product&s=admin_receipt&id=$id","请填写支付密码！",'failure');	
	}
	
	include_once("module/payment/includes/payment_class.php");
	$payment=new payment();
	$re=$payment->payment_base();
	
	if($re['pay_pass']==md5(trim($_POST['password'])))
	{
		$order->set_order_statu($id,4);//确认收货
		$admin->msg("main.php?m=product&s=admin_buyorder");	
	}
	else
	{
		$admin->msg("main.php?m=product&s=admin_receipt&id=$id","支付密码错误！",'failure');	
	}
}
$tpl->assign("de",$order->orderdetail($_GET['id']));
//========================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_receipt.htm");
?>
