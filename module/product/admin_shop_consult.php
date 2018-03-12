<?php

if($_GET['type']=='add')
{
	if($_POST["act"]=='edit' and is_numeric($_POST['id']))
	{
		$_POST['answer']=htmlspecialchars($_POST["answer"]);
		if($_POST['answer'])
		{
			$user=get_member_field($buid,'user');
			$sql="update ".CONSULT." set answer='$user',answer_id='$buid',answer_time='".time()."',answer='$_POST[answer]',status='2' where id='$_POST[id]'";
			$db->query($sql);
			$admin->msg("main.php?m=product&s=admin_shop_consult");
		}
	}
	$sql="select * from ".CONSULT." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
	$tpl->assign("de",$de);
	
}
else
{
	if($_GET['type']=='to_reply')
	{
		$ss=" and (answer is NULL or answer='')";	
	}
	if($_GET['type']=='replied')
	{
		$ss=" and answer <> ''";	
	}
	
	$sql="select * from ".CONSULT." where product_member_id='$buid' $ss order by question_time desc";	
	/**************************************/
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$db->query($sql);
	$re["page"]=$page->prompt();
	/**************************************/
	$re["list"]=$db->getRows();
	$tpl->assign("re",$re);
}
$tpl->assign("config",$config);
$output=tplfetch("admin_shop_consult.htm");
?>