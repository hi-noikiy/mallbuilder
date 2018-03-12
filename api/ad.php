<?php
/**
 * JS的广告调用
 * powered by b2bbuilder
 * Auther:brad
 * date:2008-12-18
 */
header("Content-Encoding:none");
include_once("../includes/arrcache_class.php");
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
//=====================
$cache = new ArrCache('../cache/ad/');
$config["weburl"]=str_replace('api','',$config["weburl"]);
$id   = $_GET['id']*1;
$_GET['catid']*=1;
if(!$cache->begin(array($id,'','',$_GET['catid']),3600*0))
{
	$sub_sql=$tsql=NULL;$nt=time();
	
	if(!empty($dpid))
		$sub_sql=" and province='$dpid'";
	else
		$sub_sql=" and (province='' or province is NULL) ";
	if(!empty($dcid))
		$sub_sql.=" and city='$dcid'";
	else
		$sub_sql.=" and (city='' or city is NULL)";
	if(!empty($acid))
		$sub_sql.=" and area='$acid'";
	else
		$sub_sql.=" and (area='' or area is NULL)";
	
	if(!empty($_GET['catid']))
		$tsql.=" and catid='$_GET[catid]'";
	if(!empty($_GET['name']))
		$tsql.=" and name='$_GET[name]'";
		
	$db->query("update ".ADVSCON." set shownum=shownum+1 where group_id='$id'");//广告展示次数加
	$db->query("select ad_type,width,height,id from ".ADVS." where ID='$id'");
	$ad_re=$db->fetchRow();
	if(empty($ad_re['id']))
	{
		$sql="insert into ".ADVS." (id,ad_type,width,height) values ('$id','1','100','100')";
		//$db->query($sql);
	}
	
	$sql="select * from ".ADVSCON." where group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' $sub_sql $tsql order by sort_num asc ";
	$db->query($sql);
	$re=$db->getRows();
	if(count($re)<=0)
	{
		$sql="select * from ".ADVSCON." where 
		group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' $sub_sql and (catid='' or catid is NULL) order by sort_num asc";
		$db->query($sql);
		$re=$db->getRows();
	}

	if($ad_re['ad_type']==1)
	{	
		//幻灯
		echo $str=jside($re,$ad_re,'1');
	}
	elseif($ad_re['ad_type']==2)
	{
		//滚动
		echo $str=jside($re,$ad_re,'2');
	}
	else
	{
		foreach($re as $v)
		{
			echo $str=get_add($v,$ad_re);
		}	
	}
}
$cache->end();

//============================================================
function get_add($re,$ar)
{
	global $config;
	$str=NULL;
	$width  = $ar['width']; //宽度
	$height = $ar['height'];//高度
	$type   = $ar['ad_type'];
	
	if($type ==3)
	{
		$re['con']=nl2br($re['con']);
		$re['con']=str_replace("\t",'',$re['con']);
		$re['con']=str_replace("\r\n",'',$re['con']);
		$re['con']=str_replace("\r",'',$re['con']);
		$re['con']=str_replace("\n",'',$re['con']);
		if($re["url"])
			$str="<a href=".$re["url"]." target=_blank>".$re["con"]."</a>";
		else
			$str=$re["con"];
		return "document.write('".$str."');";
	}
	else
	{
		if($re["url"])
			$str="<a href=".$re['url']." target=_blank><img src=".$re['picName']." border=0></a>";
		else
			$str="<img src=".$re['picName'].">";
			
		return "document.write('".$str."');";
	}
	
}
function jside($re,$ar,$type)
{
	global $db,$config;
	$width  = $ar['width']; //宽度
	$height = $ar['height'];//高度
	$catid = '';
	$ssp[]='';
	if(!empty($re))
	{
		foreach($re as $k=>$v)
		{
			$ssp[]="<li><a style=\"height:".$height."px;width:".$width."px;\" target=\"_blank\" href=\"".$v['url']."\"><img src=\"".$v['picName']."\"></a></li>";
			$catid='_'.$v['catid'];
		}
	}
	if($type=='1')
		$js="$(window).load(function(){\$(\"#slider_".$ar['id']."\").flexslider({directionNav:false})});";
	else
		$js="$(window).load(function(){\$(\"#slider_".$ar['id']."\").flexslider({controlNav:false,slideshowSpeed:9000})});";
	$str="document.write('<div class=\"slide\" id=\"slider_".$ar['id']."\"><ul class=\"slides\">".implode("",$ssp)."</ul></div>');".$js;
	return $str;
}

?>
