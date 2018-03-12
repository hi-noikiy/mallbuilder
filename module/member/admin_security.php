<?php
include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
$member=new member();
//===================================================================
$tpl->assign("de",$de=$member->get_member_detail($_GET['editid']));
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_security.htm");
?>