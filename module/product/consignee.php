<?php
	
	include_once("$config[webroot]/module/member/includes/plugin_orderadder_class.php");
	$orderadder=new orderadder();
	$tpl->assign("consignee",$re=$orderadder->get_consignee());
	$tpl->assign("consignee_list",$orderadder->get_orderadderlist());
	$tpl->assign("prov",GetDistrict());
	$out=tplfetch("consignee.htm",$flag,true);	
	
?>
               