<?php
	$sql="select name,id from ".DISTRICT." where pid=0 order by sorting,id";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$sql="select name,id from ".DISTRICT." where pid=$v[id] order by sorting,id ";
		$db->query($sql);
		$de[$k]['city']=$db->getRows();	
	}
	$tpl->assign("pv",$de);
		
	$out=tplfetch("district.htm",$flag,true);
?>