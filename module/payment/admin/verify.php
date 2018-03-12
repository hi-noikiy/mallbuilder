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
$sql="select * from ".PUSER." where identity_pic!='' and identity_verify='false' $psql order by lastLoginTime desc";
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
unset($_GET['s']);
$tpl->assign("url",'&'.implode('&',convert($_GET)));

$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->display("verify.htm");
?>