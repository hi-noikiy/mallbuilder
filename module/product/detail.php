<?php
//------------单项物流价格----------------
function get_log_price($lgid,$area)
{
	global $db;
	if(strlen($area)>6)
		$city=substr($area,6,strlen($area)-6);
	else
		$city=$area;
	
	$city=$city?','.$city:$city;

	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='mail'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='mail'";
		
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str=$re['default_price']?"平邮:$re[default_price]元 ":"";

$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='express'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='express'";
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str.=$re['default_price']?"快递:$re[default_price]元 ":"";
	
	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='ems'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='ems'";
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str.=$re['default_price']?"EMS:$re[default_price]元 ":"";
	return $str;
}
//------------产品详情--------------------
include_once($config['webroot']."/module/product/includes/plugin_pro_class.php");
$id=$_GET["id"]*1;
$prodetail=new pro();
$prode=$prodetail->detail($id);


if(!is_file($config['webroot']."/uploadfile/product/".$prode["userid"]."/".$prode['id'].".jpg")) 
{	
	include "lib/phpqrcode/phpqrcode.php";
	$value=$config['weburl']."?m=product&s=detail&id=".$prode['id'];
	$errorCorrectionLevel = 'L';
	$matrixPointSize = 2;
	QRcode::png($value,$config['webroot']."/uploadfile/product/".$prode["userid"]."/".$prode['id'].".jpg", $errorCorrectionLevel, $matrixPointSize);
}
//-----------------------------------咨询分类
$sql="select * from ".CONSULTCAT." order by id";
$db->query($sql);
$consultcat=$db->getRows();
$tpl->assign("consultcat",$consultcat);
//-----------------------------------------
$sql="select isbuy,ext_table from ".PCAT." where catid='$prode[catid]'";
$db->query($sql);
$current_cat=$db->fetchRow();
if($current_cat['isbuy']==1)
	$prode['isbuy']=1;
else
	$prode['isbuy']=0;
//-----------------------------------扩展字段
include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
$addfield = new AddField('product');
$prode['extfiled']=$addfield->addfieldinput($id,$current_cat['ext_table'],true);
//-----------------------------------用户区获取
$prode['user_ip']=convertip(getip());
if($prode['user_ip']=='- LAN')
	$prode['user_ip']='';
$prode['freight_count']=get_log_price($prode['freight'],$prode['user_ip']);//跟据所在地自动算出的运费
//----------------------------------------
if(!empty($prode['userid']))
{
	$_GET['uid']=$prode['userid'];
	$_GET['action']='product_detail';
	include($config['webroot'].'/shop.php');
}
else
{
	msg($config['weburl'].'/404.php');
}
?>