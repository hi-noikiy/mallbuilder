<?php
	
$id=!empty($_GET["id"])?$_GET["id"]*1:NULL;
$key=!empty($_GET["key"])?trim($_GET["key"]):NULL;
$firstRow=!empty($_GET["firstRow"])?$_GET["firstRow"]:NULL;
$totalRows=!empty($_GET["totalRows"])?$_GET["totalRows"]:NULL;
$sprice=!empty($_GET["sprice"])?$_GET["sprice"]*1:NULL;
$eprice=!empty($_GET["eprice"])?$_GET["eprice"]*1:NULL;
$province=!empty($_GET["province"])?$_GET['province']:NULL;
$orderby=!empty($_GET["orderby"])?$_GET['orderby']*1:NULL;

//===================================分类
if(is_numeric($id))
{
	if(strlen($id)>8)
		$catname[]=substr($id,0,-6);
	if(strlen($id)>6)
		$catname[]=substr($id,0,-4);
	if(strlen($id)>4)
		$catname[]=substr($id,0,-2);
	$catname[]=$id;
	$tpl->assign("catname",$catname);
	
	$cat=readCat($id);
	//-----------------------------分类关连的品牌
	if(!empty($cat['brand']))
	{
		$sql="select * from ".BRAND." where id in ( $cat[brand] ) order by displayorder asc ";
		$db->query($sql);
		$re=$db->getRows();
		$tpl->assign("brand",$re);
	}
	//-----------------------------子类
	$sql="select cat,catid from ".PCAT." where catid < ".$id."99 and catid > ".$id."00 order by nums asc ";
	$db->query($sql);
	$de=$db->getRows();
	$tpl->assign("cat",$de);	

	//-----------------------------获取分类下自定义字段搜索项
	$sql="select display_type,field,field_name,item from ".PROPERTYVALUE." where is_search=1 and display_type in (3,4,5) and property_id='$cat[ext_field_cat]'";
	$db->query($sql);
	$catfile=$db->getRows();
	foreach($catfile as $fkey=>$v)
	{
		$catField=explode(',',$v['item']);
		foreach($catField as $skey=>$sv)
		{
			$catField[$skey]=explode('|',$sv);
		}
		$catfile[$fkey]['catItem']=$catField;
		//------组合皖搜索
		if(!empty($_GET[$v['field']]))
			$ext_sql.=' and b.'.$v['field'].'=\''.$_GET[$v['field']].'\'';
	}

	$tpl->assign("catfile",$catfile);
	//---------------------------------按分类搜索
	$scl.=" and LOCATE('".intval(trim($_GET['id']))."',a.catid)=1 ";//按类别搜索
}

//------------------------------------------------------
include_once("config/module_product_config.php");
$tpl->assign("ptype",$ptype=explode('|',$module_product_config['ptype']));
//------------------------------------------------------

if(!empty($key))
	$scl.=" and ( a.keywords like '%$key%' or a.pname like '%$key%' )";
if(!empty($province))
	$scl.=" and a.province='$province' ";
if(!empty($_GET['brand']))
	$scl.=" and a.brand='".$_GET['brand']."' ";

if($dpid)
	$scl.=" and c.provinceid='".getdistrictid($dpid)."'";
if($dcid)
	$scl.=" and c.cityid='".getdistrictid($dcid)."'";
if($daid)
	$scl.=" and c.areaid='".getdistrictid($daid)."'";
		
if(!empty($_GET['ptype']) and $_GET['ptype']>=0 and $_GET['ptype']<count($ptype))
	$scl.=" and a.ptype='$_GET[ptype]' ";	
if($orderby==1)
	$scl.=" order by a.sell_amount desc";	
elseif($orderby==2)
	$scl.=" order by a.read_nums desc";
elseif($orderby==3)
	$scl.=" order by a.goodbad desc";
elseif($orderby==4)
	$scl.=" order by a.uptime desc";
elseif($orderby==5)
	$scl.=" order by a.price asc";
elseif($orderby==6)
	$scl.=" order by a.price desc";
else
	$scl.=" order by a.rank desc,a.uptime desc";

//--------------------------------------------------
include_once("includes/page_utf_class.php");
$page = new Page;
$page->url=$config['weburl'].'/';
$page->listRows=16;
if(empty($cat['ext_field_cat']))
	$sql="SELECT a.* FROM ".PRO." a left join ".SHOP." c on a.userid=c.userid WHERE c.shop_statu=1 and a.statu>0 $ext_sql $scl";
else
	$sql="SELECT a.* FROM ".PRO." a left join ".$cat['ext_table']." b on a.id=b.info_id and b.info_type='product' left join ".SHOP." c on a.userid=c.userid WHERE c.shop_statu=1 and a.statu>0 $ext_sql $scl";

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
	
$tpl->assign("info",$prolist);
unset($prolist);

//------------------------------------------------------
$url=implode('&',convert($_GET));
$tpl->assign("url",$url);
//----------------------------SEO
$config['title']=str_replace('[catname]',$cat['cat'],$config['title2']);
$config['keyword']=str_replace('[catname]',$cat['cat'],$config['keyword2']);
$config['description']=str_replace('[catname]',$cat['cat'],$config['description2']);
//=====================================================
$tpl->assign("current","product");
include_once("footer.php");
$out=tplfetch("product_list.htm");
?>