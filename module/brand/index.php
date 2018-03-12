<?php
	$sql="select * from ".BRANDCAT." where parent_id ='0' order by displayorder";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $k=>$v)
	{
		$sql="SELECT * FROM ".BRAND." WHERE  catid = $v[id]  ORDER BY displayorder asc";
		$db->query($sql);
		$re[$k]['brand']=$db->getRows();
	};
	$tpl->assign("bcat",$re);
	
	$tpl->assign("current","brand");
	include("footer.php");
	$out=tplfetch("brand_index.htm");
?>