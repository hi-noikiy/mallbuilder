<?php
if(is_array($_POST['chk']))
{
	$id=implode(",",$_POST['chk']);
	if($_POST['act']=='del')
	{
		$sql="delete from ".PUSER." where pay_id in ($id)";
		$db->query($sql);
		msg("?m=payment&s=member.php");
	}
}
if($_GET['operation']=='edit')
{
	if($_POST['act']=='edit')
	{
		if($_POST['pay_pass'])
		{
			$_POST['pay_pass']=md5(trim($_POST['pay_pass']));
			$s=",pay_pass='$_POST[pay_pass]'";	
		}
		if($_POST['login_pass'])
		{
			$_POST['login_pass']=md5(trim($_POST['login_pass']));
			$s.=",login_pass='$_POST[login_pass]'";	
		}
		if($_POST['question']&&$_POST['answer'])
		{
			$_POST['question']=trim($_POST['question']);
			$_POST['answer']=trim($_POST['answer']);
			$s.=",question='$_POST[question]',answer='$_POST[answer]'";	
		}
		$sql="update ".PUSER." set email_verify='false' $s where pay_id='$_POST[id]'";
		$db->query($sql);
		msg("?m=payment&s=member.php");
	}
	$sql = "select * from ".PUSER." where pay_id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
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
	$sql="select * from ".PUSER." where 1 $psql order by lastLoginTime desc";
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
$tpl->display("member.htm");
?>