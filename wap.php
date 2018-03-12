<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");

$config['title']=$de['title'];
$config['keyword']=$de['keywords'];
$config['description']=$de['description'];
//======================================================================
include_once("footer.php");
$tpl->display("wap.htm");
?>