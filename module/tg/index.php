<?php

//=================================================================
useCahe();
$flag=md5($dpid.$dcid.$config["temp"]);
if(!$tpl->is_cached("tg_index.htm",$flag))
{
	$date=time();
	$_GET['catid']*=1;
	if($_GET['catid'])
		$catid=" and catid='$_GET[catid]'";
	$sql="select * from ".TG." where status>=1 $catid order by displayorder desc  ";
		
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("tg",$re);
	
	$id=!empty($_GET["catid"])?$_GET["catid"]*1:NULL;
	$key=!empty($_GET["keys"])?trim($_GET["keys"]):NULL;
	$orderby=!empty($_GET["orderby"])?$_GET['orderby']*1:NULL;
	
	if($id and is_numeric($id))
		$scl=" and catid='$id'";
	if(!empty($key))
		$scl.=" and name like '%$key%'";
	
	if($orderby==1)
		$scl.=" order by sell_amount desc,virtual_quantity desc,id desc";	
	elseif($orderby==2)
		$scl.=" order by hits desc,id desc";
	elseif($orderby==3)
		$scl.=" order by create_time desc";
	elseif($orderby==4)
		$scl.=" order by price asc,id desc";
	elseif($orderby==5)
		$scl.=" order by price desc,id desc";
	else
		$scl.=" order by displayorder, id desc ";
	
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->url=$config['weburl'].'/';
	$page->listRows=9;
	
	$sql="select * from ".TG." where status>=1 $scl";
	
	if(!$page->__get('totalRows'))
	{
		$db->query($sql);
		$page->totalRows =$db->num_rows();
	}
	$sql.=" limit ".$page->firstRow.",".$page->listRows;
	//--------------------------------------------------
	$db->query($sql);
	$prol=$db->getRows();
	$prolist['list']=$prol;
	$prolist['page']=$page->prompt();
	$prolist['count']=$page->totalRows;
	if($page->nowPage==1)
	{
		$pre="<a class='disable'>上一页</a>";
	}
	else
	{
		$pre="<a class='prePage' href='$page->url?firstRow=".($nowPage-2) * ($listRows)."&totalRows=$page->totalRows$page->parameter'>上一页</a>";
	}
	if($page->nowPage==$page->totalPages)
	{
		$next="<a class='disable'>下一页</a>";
	}else
	{
		$next="<a class='nextPage' href='$page->url?firstRow=".($page->nowPage) * ($page->listRows)."&totalRows=$page->totalRows$page->parameter'>下一页</a>";
	}
	$prolist['pages']="<span><i>$page->nowPage</i> / $page->totalPages</span>".$pre.$next;
		
	$tpl->assign("tg",$prolist);
	
	
	//-----------------------商品分类
	$sql="select * from ".TGCAT." where parent_id=0 order by displayorder desc";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("tgcat",$re);
}
$url=implode('&',convert($_GET));
$tpl->assign("url",$url);
//========================================================================
include_once("footer.php");
$tpl->assign("current","tg");
$out=tplfetch("tg_index.htm");

?>