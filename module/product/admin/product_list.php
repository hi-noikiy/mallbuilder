<?php
@include('../config/home_config.php');
$val=$home_config[$_GET['key']]?$home_config[$_GET['key']]:"";
$psql.= $_GET['name']?" and pname like '%$_GET[name]%'":"";	
$psql.= $val?" and id not in ($val)":"";
$sql="SELECT id,pname,pic,price FROM ".PRO." where 1 $psql order by uptime desc";
//========================================
include_once("../includes/page_utf_class.php");
$page = new Page;
$page->listRows=10;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$de['count']=$page->totalRows;
$sql.= " limit ".$page->firstRow.",".$page->listRows;
//=========================================
$db->query($sql);
$de['list'] = $db->getRows();
$de['page'] = $page->prompt();
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->assign("val",$home_config[$_GET['key']]);
$tpl->display("product_list.htm");
?>