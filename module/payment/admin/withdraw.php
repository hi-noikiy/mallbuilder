<?php
if(is_array($_POST['chk']))
{
	$id=implode(",",$_POST['chk']);
	if($_POST['act']=='pass')
	{
		$sql="update ".PUSER." set identity_verify ='true' where pay_id in ($id)";
		$db->query($sql);
		msg("?m=payment&s=verify.php");
	}
	if($_POST['act']=='no')
	{
		$sql="update ".PUSER." set identity_pic='' where pay_id in ($id)";
		$db->query($sql);
		msg("?m=payment&s=verify.php");
	}
}
if($_GET['operation']=='edit')
{
	
	$sql="SELECT a.*,b.*,c.name as supportTimeName FROM ".CASHPICKUP." a left join ".PUSER." b on a.pay_uid=b.pay_id left join ".FEE." c on a.supportTime=c.id where a.id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
	
	if($_POST['act']=='edit')
	{
		$add_time=time();
		if($_POST['result']==10)
		{
			$sql = "update ".CASHPICKUP." set is_succeed='10',censor='$_SESSION[ADMIN_USER]',con='$_POST[con]' where id='$_POST[id]'";
			$db->query($sql);
		}
		if($_POST['result']==20)
		{
			$sql = "update ".CASHPICKUP." set is_succeed='20',bankflow='$_POST[bankflow]',con='$_POST[con]',check_time='$add_time', censor='$_SESSION[ADMIN_USER]' 	where id='$_POST[id]'";
			$db->query($sql);
			
			$sql="update ".CASHFLOW." set statu=4 where order_id='$_POST[id]' and pay_uid='$_POST[userid]'";
		$db->query($sql);
			
		}
		if($_POST['result']==50)
		{
			$sql = "update ".CASHPICKUP." set is_succeed='50', check_time='$add_time', censor='$_SESSION[ADMIN_USER]',con='$_POST[con]' where id=$_POST[id]";
			$db->query($sql);
			
			$m=$de['amount']+$de['fee'];
			//----------------------增加可用资金
			$sql = "update ".PUSER." set cash=cash+$m where pay_id=$_POST[userid]";
			$db->query($sql);
			//----------------------更新流水状态为0
			$sql="update ".CASHFLOW." set statu=0 where order_id='$_POST[id]' and pay_uid='$_POST[userid]'";
			$db->query($sql);
		}	
		msg("?m=payment&s=withdraw.php");
	}
}
else
{
	if($_GET['name'])
	{
		$_GET['name']=trim($_GET['name']);
		$psql.=" and real_name like '%$_GET[name]%'";	
	}
	if($_GET['email'])
	{
		$_GET['email']=trim($_GET['email']);
		$psql.=" and pay_email = '$_GET[email]'";	
	}
	$sql="SELECT a.*,b.*,c.name as supportTimeName FROM ".CASHPICKUP." a left join ".PUSER." b on a.pay_uid=b.pay_id left join ".FEE." c on a.supportTime=c.id where 1 $psql order by a.add_time desc";
	//========================================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$de['count']=$page->totalRows;
	$sql.= "  limit ".$page->firstRow.",".$page->listRows;
	$de['page'] = $page->prompt();
	//=========================================
	$db->query($sql);
	$de['list']=$db->getRows();
	foreach($de['list'] as $key=>$val)
	{
		$de['list'][$key]['logo']=$val['logo']?$val['logo']:"../image/default/avatar.png";	
	}
	unset($_GET['s']);
	$tpl->assign("url",'&'.implode('&',convert($_GET)));
}
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->display("withdraw.htm");
?>