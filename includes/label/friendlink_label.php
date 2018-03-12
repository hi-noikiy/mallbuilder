<?php
function friendlink($ar)
{
	global $db,$cache,$cachetime,$config,$buid,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	$ar['temp']=empty($ar['temp'])?'friendlink_default':$ar['temp'];
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{
	
		$limit=$ar['limit']?$ar['limit']:"10";
		$sql="select url,name from ".LINK." a  where statu >= 0 order by orderid asc limit 0 ,$limit";
		$db->query($sql);
		$textlist=$db->getRows();
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("textlist",$textlist);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);

}
?>