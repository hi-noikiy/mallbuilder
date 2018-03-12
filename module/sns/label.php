<?php
function sns_friends($ar)
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
		global $buid;
		
		$limit=$ar['limit'];
		$orderby=$ar['orderby'];

		if($orderby)
		{
			 $o=" order by friend desc";
		}
		else
		{
			 $o=" order by fan desc";
		}
		if($buid)
		{
			$sql="select fuid from ".FRIEND." where uid = '$buid'";
			$db->query($sql);
			$de=$db->getRows();
			foreach($de as $val)
			{
				$ss.=",$val[fuid]";
			}
			$s =" and userid not in ($buid$ss) ";	
		}
		
		$sql="select userid,name,logo from ".MEMBER." a left join ".MEMBERCOUNT." on userid = member_id where name != '' $s $o limit 0,$limit";
		$db->query($sql);
		$re=$db->getRows();
		
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("member",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
function sns($ar)
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
		global $config;
		
		$limit=$ar['limit'];
		
		$sql="select title,member_id,img,member_name,create_time,comment_count,copy_count  from ".SNS." where type!=1 and original_id=0 order by create_time desc limit 0 , $limit";
		$db->query($sql);
		$re=$db->getRows();
		
		include_once($config['webroot']."/module/sns/face.php");
		foreach($face_array as $key=>$val)
		{
			$searcharray[] ="/\/".$key."/";
			$replacearray[] = "<img align='absmiddle' src='image/face/".$val."'>";
		}
		foreach($re as $key=>$val)
		{
		
			$sql="select logo,name from ".MEMBER."  WHERE userid='$val[member_id]'";
			$db->query($sql);
			$a=$db->fetchRow();

			$re[$key]['member_img'] = $a['logo']?$a['logo']:"image/default/user_admin/default_user_portrait.gif";
			$re[$key]['member_name']=$a['name']?$a['name']:$val['member_name'];
			if($val['img'])
			{
				$pic=explode(',',$val['img']);
				$re[$key]['img']=$pic;
			}
		}
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("sns",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>