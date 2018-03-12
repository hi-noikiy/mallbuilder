<?php
include_once("$config[webroot]/module/sns/includes/plugin_friend_class.php");
$friend=new friend();
//获取好友
$tpl->assign("re",$friend->GetFriendList());
//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_friends.htm");
?>