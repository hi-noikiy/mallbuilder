<?php
//=============================================

include_once("$config[webroot]/includes/page_utf_class.php");
include_once("$config[webroot]/module/payment/lang/$config[language].php");
if($_GET['operation']=="add")
{
	//给会员充值
	if($_POST["act"]=='add'&&!empty($_POST["cash"])&&is_numeric($_POST["cash"])&&!empty($_POST['email']))
	{
		$add_time = time();
		$_POST['email']=trim($_POST['email']);
		$_POST['cash']=$_POST['cash']*1;
		
		$sql="select pay_id from ".PUSER." where pay_email='$_POST[email]'";
		$db->query($sql);
		$v=$db->fetchRow();
		
		//-------是否开通支付账号
		if($v['pay_id'])
		{
			//-----充值记录
			$desc=$_POST["note"]?htmlspecialchars($_POST["note"]):$note[8];
			$price=$_POST['type'].$_POST['cash'];
		
			$flow_id=date("Ymdhis").rand(0,9);
			
			$sql = "insert into ".CASHFLOW." (pay_uid,price,time,note,censor,flow_id,statu,type,mold) values
('$v[pay_id]',$price,'$add_time','$desc','$_SESSION[ADMIN_USER]','$flow_id','4','1','1')";
			$db->query($sql);
			//---修改金额
			$sql="update ".PUSER." set cash=cash+$price where pay_email='$_POST[email]'";
			$db->query($sql);
			
			admin_msg('module.php?m=payment&s=cashflow.php','操作成功');
		}
		else
		{
			admin_msg('module.php?m=payment&s=cashflow.php','用户还没有开通支付账户');
		}
	}
}
else
{	
	if(!empty($_GET['name']))
		$sql=" and c.user='$_GET[name]'";
	if(!empty($_GET['stime']))
	{
		$stime=strtotime($_GET['stime']);
		$sql.=" and a.time>'$stime' ";
	}
	if(!empty($_GET['etime']))
	{
		$etime=strtotime($_GET['etime']);
		$sql.=" and a.time<'$etime' ";
	}
	if(!empty($_GET['type']))
	{
		$type=$_GET['type']-1;
		$sql.=" and a.mold='$type'";
	}
	$sqlg="select a.*,b.userid,c.user from ".CASHFLOW." a left join ".PUSER." b on a.pay_uid=b.pay_id left join ".MEMBER." c on b.userid=c.userid where 1 $sql order by a.time desc";
	
	//-----------------------------------
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows'))
	{
		$db->query($sqlg);
		$page->totalRows = $db->num_rows();
	}
	$count=$page->totalRows;
	$sqlg .= "  limit ".$page->firstRow.",".$page->listRows;
	$pages = $page->prompt();
	$db->query($sqlg);
	$de['page']=$page->prompt();
	$de['list']=$db->getRows();
	$statu=array('取消','待处理','已付款','发货中','成功','退货中','退货成功');
	$tpl->assign("payment_statu",$statu);
	$tpl->assign("payment_type",$payment_type);
	$tpl->assign("count",$count);
}
	
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->display("cashflow.htm");
?>