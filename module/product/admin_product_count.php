<?php

	$time=time();
	$day=$_GET['type']==1?"7":"30";
	for($i=$day;$i>0;$i--)
	{
		$date[]=$time-24*60*60*$i;
	}
	
	if($_GET['id'])
	{
		foreach($date as $key=>$val)
		{
			$min=$val-24*60*60;
			$sql="select count(*) as count from ".ORPRO." a left join ".ORDER." b on a.order_id=b.order_id where status>='2' and a.pid='$_GET[id]' and (b.creat_time<=$val and b.creat_time>$min) ";
			$db->query($sql);
			$count[]=$db->fetchField('count');
		}
	}		
	$tpl->assign("count",$count);
	$tpl->assign("date",$date);
	include_once("footer.php");
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_product_count.htm");
?>