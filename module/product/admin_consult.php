<?php


	if($_GET['type']=='to_reply')
	{
		$ss=" and (answer is NULL or answer='')";	
	}
	if($_GET['type']=='replied')
	{
		$ss=" and answer <> ''";	
	}
	
	$sql="select * from ".CONSULT." where member_id='$buid' $ss order by question_time desc";	
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
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_consult.htm");
?>