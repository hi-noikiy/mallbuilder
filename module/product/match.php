<?php

	include_once("includes/global.php");
	//相关产品
	$sql="SELECT pic,id,pic,pname,price,market_price,amount FROM ".PRO." where userid='$buid' and id!='$_GET[id]' order by id desc";
	//====================
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=10;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$de['page'] = $page->prompt();
	//=====================
	$db->query($sql);
	$de['list']=$db->getRows();
	
	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$output=tplfetch("match.htm",$flag,true);
	
?>