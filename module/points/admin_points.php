<?php
	include_once("$config[webroot]/includes/page_utf_class.php");
	include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
	$member=new member();
	$sql="select * from ".POINTSLOG." where member_id=$buid order by id desc";
	//=============================
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$pages = $page->prompt();
	//=====================
	$db->query($sql);
	$de['list']=$db->getRows();
	foreach($de['list'] as $key=>$val)
	{
		$points=substr($val['points'],0,1);
		$de['list'][$key]['points']=$points!='-'?"+".$val['points']:$val['points'];	
	}
	$de['page']=$page->prompt();
	$tpl->assign("de",$de);
	$points=$member->get_points();
	$tpl->assign("points",$points);
	$tpl->assign("config",$config);
	$output=tplfetch("admin_points.htm");
?>