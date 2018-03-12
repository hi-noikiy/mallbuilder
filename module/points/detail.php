<?php

	if($_POST['act']=='save')
	{
		include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
		$member=new member();
		$points=$member->get_points();
		
		include_once("$config[webroot]/module/member/includes/plugin_orderadder_class.php");
		$orderadder=new orderadder();
		$re=$orderadder->get_orderadder($_POST['addr']); 
		
		$sql="select * from ".POINTSGOODS." where id='$_POST[id]'";
		$db->query($sql);
		$de=$db->fetchRow();
		
		if($points>=$de[points])
		{
			$tm=date('YmdHis',time());
			$order_id=$tm.rand(0,9);
			
			$sql = "INSERT INTO ".POINTSORDER." ( `order_id`,`buyer_id`,`buyer_name`,`contact`,`address`,`tel`,`product_name`,`product_id`,`pic`,`allpoint`,`status`,`create_time`) VALUES ($order_id,'$buid','$b2bbuilder_auth[1]','$re[name]','$re[area] $re[address]','$re[mobile]','$de[name]','$de[id]','$de[pic]','$de[points]',10,'".time()."')"; 
			$re=$db->query($sql);
			
			$flag=$member->add_points(-$de['points'],'2',$order_id);
			msg("main.php?m=points&s=admin_points_order");
		}
		else
		{
			msg("?m=points&s=detail&id=$_POST[id]","积分不足");
		}
	}
	$sql="select * from ".POINTSCAT." where parent_id=0 order by displayorder ,id";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$sql="select * from ".POINTSCAT." where parent_id='$v[id]'";
		$db->query($sql);
		$de[$k]['scat']=$db->getRows();	
	}
	$tpl->assign("cat",$de);

	if($_GET['id'] and is_numeric($_GET['id']))
	{
		$sql="select * from ".POINTSGOODS." where id='$_GET[id]'";
		$db->query($sql);
		$de=$db->fetchRow();
		if(!$de) header("Location: 404.php");
		$tpl->assign("de",$de);
	}
	else
	{
		header("Location: 404.php");
	}

	$sql="select id,pic,name,points from ".POINTSGOODS." where status=2 order by id desc limit 0,5";
	$db->query($sql);
	$re=$db->getRows();	
	$tpl->assign("re",$re);
	
	include_once("footer.php");
	$tpl->assign("current","points");
	$out=tplfetch("points_detail.htm",$flag,true);
?>