<?php
$sql="select * from ".REMINDCAT." where parent_id='0' order by displayorder ,id ";
$db->query($sql);
$re=$db->getRows();
foreach($re as $k=>$v)
{
	$sql="select * from ".REMINDCAT." where parent_id='$v[id]'";
	$db->query($sql);
	$cat=$db->getRows();
	
	foreach($cat as $key=>$val)
	{
		$sql="select * from ".REMIND." where catid = '$val[id]' order by id ";
		$db->query($sql);
		$de=$db->getRows();
		$cat[$key]['remind']=$de;
	}
	$re[$k]['scat']=$cat;
}
$tpl->assign("re",$re);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_sms.htm");
?>