<?php
	
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			foreach($_POST["chk"] as $val)
			{
				$sql="select * from ".PCOMMENT." where id=$val";
				$db->query($sql);
				$re=$db->fetchRow();
				$db->query("update ".SETMEAL." set goodbad=goodbad-$re[goodbad] where id='$re[pid]'");	
				$db->query($sql);	
			}
			$sql="delete from ".PCOMMENT." where id in ($id)";
			$db->query($sql);
			msg("?m=product&s=product_comment.php");
		}
	}
	if($_GET['name'])
	{
		$psql=" and pname like '%$_GET[name]%' ";	
	}
	if($_GET['id'] and is_numeric($_GET['id']))
	{
		$psql.=" and pid = '$_GET[id]' ";	
	}
	if($_GET['goodbad'] and is_numeric($_GET['goodbad']))
	{
		$goodbad=$_GET['goodbad']*1-2;
		$psql.=" and goodbad = '$goodbad' ";	
	}
	$sql="select * from ".PCOMMENT." where 1 $psql order by uptime desc";
	//====================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=10;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	//=====================
	$db->query($sql);
	$de['list']=$db->getRows();
	foreach($de['list'] as $k=>$v)
	{
		$c[$v['pid']]['name']=$v['pname'];
		$c[$v['pid']]['id']=$v['pid'];
		$c[$v['pid']]['comment'][]=$v;
	}
	$de['list']=$c;
	$de['page'] = $page->prompt();
	$tpl->assign("url",'&'.implode('&',convert($_GET)));
	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$tpl->display("product_comment.htm");
?>