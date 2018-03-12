<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
if($config['temp']=='wap')
{
	$id=$_GET['id']?$_GET['id']*1:"0";
	$type=$_GET['type']?$_GET['type']*1:"0";
	if(!empty($id) and is_numeric($id))
	{
		$sql="select con_desc,template,con_title,title,keywords,description,msg_online from ".WEBCON." WHERE  con_id='$id'";
		$db->query($sql);
		$de=$db->fetchRow();
	}
	else if(!empty($type) and is_numeric($type))
	{
		$sql="select con_id as id,con_title as title from ".WEBCON." where con_statu=1 and type='1' and con_group='$type' and lang='$config[language]' order by con_no asc";
		$db->query($sql);
		$de = $db->getRows();
	}
	else
	{
		$sql="select * from ".WEBCONGROUP." where lang='$config[language]'";
		$db->query($sql);
		$de = $db->getRows();
	}
    $tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$page='help.htm';
}
else
{
$sql="select * from ".WEBCONGROUP." where lang='$config[language]'";
$db->query($sql);
$con_groups = $db->getRows();
$tpl->assign("con_groups",$con_groups);

$sql="select * from ".WEBCON." where con_statu=1 and type=1 and lang='$config[language]' order by con_no asc";
$db->query($sql);
$all_web = $db->getRows();
$tpl->assign("all_web",$all_web);

//--------------------------------------------------------------------
$type=empty($_GET['type'])?$all_web[0]['con_id']:$_GET['type'];
$sql="select con_desc,template,con_title,title,keywords,description,msg_online from ".WEBCON." WHERE  con_id='$type'";
$db->query($sql);
$de=$db->fetchRow();
$tpl->assign("de",$de);

$config['title']=$de['title'];
$config['keyword']=$de['keywords'];
$config['description']=$de['description'];
//======================================================================
include_once("footer.php");

if(!empty($de['template'])&&file_exists($tpl->template_dir.'/'.$de['template']))
	$page=$de['template'];
else
	$page='help.htm';
}
$tpl->display($page);
?>