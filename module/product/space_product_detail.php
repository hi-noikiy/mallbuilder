<?php

//===========================购买记录
$sql="select  a.name,b.user,b.buyerpoints,a.price,a.num,a.time,c.setmeal 
from ".ORPRO." a left join ".MEMBER." b on a.buyer_id=b.userid left join ".SETMEAL." c on a.setmeal=c.id where a.pid='$_GET[id]'";
//---------------
include_once("includes/page_utf_class.php");
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$buy_rec["page"]=$page->prompt();
//---------------
$buy_rec["list"]=$db->getRows();
foreach($buy_rec["list"] as $key=>$val)
{
	$sql="select * from ".POINTS." order by id";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$buy_rec["list"][$key]["user"]=namereplace($val['user']);
		$ar=explode('|',$v['points']);
		if($val['buyerpoints']<=$ar[1] and $val['buyerpoints']>=$ar[0])
		{
			$buy_rec["list"][$key]["buyerpointsimg"]=$v['img'];
			$buy_rec["list"][$key]["buyerpointsdesc"]=str_replace('|','-',$v['points']);
		}
	}
}
function namereplace($name, $charset = 'UTF8') {
	$strlen = mb_strlen($name, $charset);
	if($strlen>2) {
		return mb_substr($name, 0, 1, $charset).str_repeat('*',$strlen-2).mb_substr($name, -1, 1, $charset);
	} elseif($strlen>0) {
		return mb_substr($name, 0, 1, $charset).str_repeat('*',$strlen-1);
	}
}
$tpl->assign("buy_rec",$buy_rec);

//=============================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid ,c.logo
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id left join ".MEMBER." c on a.userid=c.userid 
where a.pid='$_GET[id]' and b.userid='$prode[userid]' and a.userid <> '$prode[userid]'";
//---------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review["page"]=$page->prompt();
//--------------
$review["list"]=$db->getRows();
$tpl->assign("review",$review);

//================================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid ,c.logo
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id left join ".MEMBER." c on a.userid=c.userid 
where a.pid='$_GET[id]' and b.userid='$prode[userid]' and a.userid <> '$prode[userid]' and a.goodbad='1'";
//-----------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review2["page"]=$page->prompt();
//----------------
$review2["list"]=$db->getRows();
$tpl->assign("review2",$review2);

//===============================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid ,c.logo
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id left join ".MEMBER." c on a.userid=c.userid 
where a.pid='$_GET[id]' and b.userid='$prode[userid]' and a.userid <> '$prode[userid]' and a.goodbad='0'";
//----------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review3["page"]=$page->prompt();
//----------------
$review3["list"]=$db->getRows();
$tpl->assign("review3",$review3);

//================================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid ,c.logo
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id left join ".MEMBER." c on a.userid=c.userid 
where a.pid='$_GET[id]' and b.userid='$prode[userid]' and a.userid <> '$prode[userid]' and a.goodbad='-1'";
//------------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review4["page"]=$page->prompt();
//-----------------
$review4["list"]=$db->getRows();
$tpl->assign("review4",$review4);

//=====================================
$sql="select avg(item1) as a from ".UCOMMENT." where byid = '$_GET[uid]'";
$db->query($sql);
$u=$db->fetchRow();
$u['aw']=$u['a']/5*100;
$tpl->assign("u",$u);	
//====================================产品详情

if($prode['validTime']<6)
{
	$time=time();
	$uptime=strtotime($prode['uptime']);
	switch ($prode['validTime'])
	{
		case "0":
		{	
			$uptime=$uptime+(7*3600*24);
			break;
		}
		case "1":
		{	
			$uptime=$uptime+(15*3600*24);
			break;
		}
		case "2":
		{	
			$uptime=$uptime+(30*3600*24);
			break;
		}
		case "3":
		{	
			$uptime=$uptime+(90*3600*24);
			break;
		}
		case "4":
		{	
			$uptime=$uptime+(120*3600*24);
			break;
		}
		case "5":
		{	
			$uptime=$uptime+(365*3600*24);
			break;
		}
		default:break;
	}
	if($uptime-$time>=0)
	{
		$tpl->assign("validTime",1);	
	}
	else
	{
		$tpl->assign("validTime",0);	
	}
}
else if($prode['validTime']==6)
{
	$tpl->assign("validTime",1);	
}
$tpl->assign("de",$prode);

if($prode['match'])
{
	$sql="SELECT a.id,a.pic,a.pname,a.price,a.market_price,a.amount,a.code,b.id as tid,b.price as money,b.stock,b.sku,b.setmeal FROM ".PRO." a left join ".SETMEAL." b on a.id=b.pid  where a.id in ($prode[match])  order by id desc";
	$db->query($sql);
	$match=$db->getRows();	
}
$tpl->assign("match",$match);

$stime=1;
if($prode['stime_type']=='2'&&$prode['stime']&&$prode['stime']>time())
{	
	$stime=0;	
}
$tpl->assign("stime",$stime);

//====================================SEOconfig
$shopconfig["hometitle"]=$prode['pname'].'-'.$shopconfig["hometitle"];
$shopconfig["homedes"]=$prode['keywords'].','.$shopconfig["homedes"];
$shopconfig["homekeyword"]=$prode['keywords'].','.$shopconfig["homekeyword"];
//---------------------------------------
$tpl->assign("lang",$lang);
$tpl->assign("config",$config);
$tpl->assign("com",$company);
$output=tplfetch("space_product_detail.htm",$flag);
?>