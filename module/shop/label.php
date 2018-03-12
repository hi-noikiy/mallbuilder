<?php
function shop($ar)
{
	global $cache,$cachetime,$dpid,$dcid,$config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$ar['temp']=empty($ar['temp'])?'user_default':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		$limit=$ar['limit'];
		$rec=$ar['rec'];
		$spointer=empty($ar['start_pointer'])?0:$ar['start_pointer'];
		$o=$ar['o'];

		if($rec)
			$scl.=" and statu='$rec'";
			
		if($dpid)
			$scl.=" and a.provinceid='".getdistrictid($dpid)."'";
		if($dcid)
			$scl.=" and a.cityid='".getdistrictid($dcid)."'";
		if($daid)
			$scl.=" and a.areaid='".getdistrictid($daid)."'";
		
		if($o=='1')
			$or=" ORDER BY view_times DESC";
		else
		   $or="  ORDER BY uptime DESC";
			
		$sql="SELECT a.*,sellerpoints,name from ".SHOP." a left join  ".MEMBER." b on a.userid=b.userid  WHERE company!='' and shop_statu='1' $scl $or LIMIT $spointer,$limit";
		$db->query($sql);
		$shop=$db->getRows();
		foreach($shop as $key=>$val)
		{
			$sql="select * from ".POINTS." order by id";
			$db->query($sql);
			$de=$db->getRows();
			foreach($de as $k=>$v)
			{
				if($v['points'])
					$arr = explode('|',$v['points']);
				
				if($val['sellerpoints']<=$arr[1] and $val['sellerpoints']>=$arr[0])
				{
					$shop[$key]["sellerpointsimg"]=$v['img'];
				}
			}
			
			$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id where b.userid='".$val['userid']."' and a.userid <> '".$val['userid']."'";
			$db->query($sql);
			$count=$db->fetchField('count');
			if($count!=0)
			{
				$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id where b.userid='".$val['userid']."' and a.userid <> '".$val['userid']."' and a.goodbad=1";
				$db->query($sql);
				$shop[$key]["favorablerate"]=($db->fetchField('count')/$count)*100;
			}else{
				$shop[$key]["favorablerate"]="0";
			}
		}
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("shop",$shop);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>