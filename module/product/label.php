<?php
function product($ar)
{
	global $cache,$cachetime,$dpid,$dcid,$daid,$config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	$ar['temp']=empty($ar['temp'])?'pro_default':$ar['temp'];
	
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{
		$limit=$ar['limit'];
		$rec=$ar['rec'];
		$comp=$ar['comid'];
		$catid=$ar['catid'];
		$ptype=$ar['ptype'];
		$proid=$ar['proid'];
		$o=$ar['o'];
		$userid=$ar['uid'];
		
		/*---分站---*/
		if($dpid)
			$scl.=" and b.provinceid='".getdistrictid($dpid)."'";
		if($dcid)
			$scl.=" and b.cityid='".getdistrictid($dcid)."'";
		if($daid)
			$scl.=" and b.areaid='".getdistrictid($daid)."'";
			
		if(is_numeric($rec))
			$scl.=" and a.statu='$rec'";
		else
			$scl.=" and a.statu>0";
		if(!empty($comp))
			$scl.=" and a.userid=$comp ";
		if(!empty($catid))
			$scl.=" and LOCATE($catid,a.catid)=1";
		if(!empty($ptype))
			$scl.=" and a.ptype=$ptype ";
		if(!empty($proid))
			$scl.=" and a.id in($proid)";
		if(!empty($userid))
			$scl.=" and a.userid = '$userid' ";


		if($o=='1')
			$or.=" ORDER BY sell_amount DESC ,id desc ";
		elseif($o=='2')
			$or.=" order by price asc,id desc";
		elseif($o==3)
			$or.=" order by price desc,id desc";
		elseif($o=='4')
			$or.=" order by read_nums desc,id desc";
		elseif($o=='5')
			$or.=" order by goodbad desc,id desc";
		else
			$or=" ORDER BY uptime DESC,id desc";
			
		$sql="SELECT a.id,a.pname,a.sell_amount,a.market_price,a.userid,a.pic,a.price,a.uptime,b.user,b.company FROM ".PRO." a left join ".SHOP." b on b.shop_statu=1 and a.userid=b.userid WHERE 1 $scl $or LIMIT 0,$limit";
		$db->query($sql);
		$re=$db->getRows();
		
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("pro",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
function product_consult($ar)
{
	global $cache,$cachetime,$dpid,$dcid,$daid,$config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
		
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{
		$limit=$ar['limit'];
		$product_id=$ar['product_id'];  //产品ID
		$catid=$ar['catid'];			//咨询分类ID
	
		if(!empty($catid))
			$scl.=" and catid='$catid'";
		if(!empty($product_id))
			$scl.=" and product_id='$product_id'";
			
		$sql="select * from ".CONSULT." where 1 $scl order by question_time desc limit 0 , $limit";
		$db->query($sql);
		$re=$db->getRows();
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("consult",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>