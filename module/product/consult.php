<?php

//-----------------------------------咨询分类
include_once($config['webroot']."/module/product/includes/plugin_consult_class.php");
$consult=new consult();
if($_POST['act']=='add')
{
	$consult->add_question();
	msg("?m=product&s=consult&pid=$_POST[pid]");
}
$id=$_GET["pid"]?$_GET["pid"]*1:NULL;
$catid=$_GET['type']?$_GET['type']*1:NULL;
$key=$_GET['k']?$_GET['k']:NULL;
if(!empty($id) and is_numeric($id))
{
	$tpl->assign("consultcat",$consult->get_consult_cat());
	include_once($config['webroot']."/module/product/includes/plugin_pro_class.php");
	$prodetail=new pro();
	$prode=$prodetail->detail($id);
	$tpl->assign("de",$prode);
	
		include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->url=$config['weburl'].'/';
	$page->listRows= 20;
	if(!empty($catid) and is_numeric($catid))
	{
		$ssql = " and catid = '$catid'";
	}
	if(!empty($key))
	{
		$ssql = " and question like '%$key%'";
	}
	$sql="select * from ".CONSULT." where product_id='$id' $ssql order by question_time desc";
	
	if(!$page->__get('totalRows'))
	{
		$db->query($sql);
		$page->totalRows =$db->num_rows();
	}
	$sql.=" limit ".$page->firstRow.", ".$page->listRows;
	//--------------------------------------------------
	$db->query($sql);
	$re['list']=$db->getRows();
	$re['page']=$page->prompt();
	$tpl->assign("re",$re);
}
else
{
	header("Location: 404.php");	
}
$ar1=array('[catname]','[title]','[keyword]','[brand]');
$ar2=array($pcats['cat'],$prode['pname'],$prode['keywords'],$prode['brand']);
$config['title']=str_replace($ar1,$ar2,$config['title3']);
$config['keyword']=str_replace($ar1,$ar2,$config['keyword3']);
$config['description']=str_replace($ar1,$ar2,$config['description3']);
//======================================
$tpl->assign("current","product");
include_once("footer.php");
$out=tplfetch("consult.htm",$flag);
?>