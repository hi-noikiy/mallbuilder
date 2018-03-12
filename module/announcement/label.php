<?php
function announcement($ar)
{
	global $config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$ar['temp']=empty($ar['temp'])?'notice_default':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		$limit=$ar['limit'];
		
		$sql="SELECT * from ".ANNOUNCEMENT." where status>0  order by displayorder,id desc limit 0,".$limit;
		$db->query($sql);
		$re=$db->getRows();
		//==================================================	
		$tpl->assign("config",$config);
		$tpl->assign("notice",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>