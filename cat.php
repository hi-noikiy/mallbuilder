<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");

if($_GET['id'] and is_numeric($_GET['id']))
{
	$max=$_GET['id']."99";
	$min=$_GET['id']."00";
	$s="catid<=$max and catid>=$min ";
}
else
{
	$s="catid<9999";
}
$sql="select catid,cat from ".PCAT." WHERE  $s order by nums asc,char_index asc";

$db->query($sql);
$re=$db->getRows();
foreach($re as $k=>$val)
{

	$maxs=$val['catid']."99";
	$mins=$val['catid']."00";
	$ss="catid<=$maxs and catid>=$mins ";
	$sql="select count(*) as c from ".PCAT." WHERE  $ss order by nums asc,char_index asc";
	$db->query($sql);
	$re[$k]['count']=$db->fetchField('c');	
}
$tpl->assign("cat",$re);
$config['title']=$de['title'];
$config['keyword']=$de['keywords'];
$config['description']=$de['description'];
//======================================================================
include_once("footer.php");
$tpl->display("cat.htm");
?>