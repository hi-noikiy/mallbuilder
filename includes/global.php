<?php
error_reporting(E_ERROR|E_WARNING|E_PARSE|E_USER_ERROR|E_USER_WARNING);//6143
header('Content-Type: text/html; charset=utf-8');
if(@function_exists('date_default_timezone_set'))
	@date_default_timezone_set('Asia/Shanghai');
	
$config['version']='MallBuilder_v5.8';
$config['webroot']=substr(dirname(__FILE__), 0, -9);
ini_set('include_path',$config['webroot'].'/');

include_once($config['webroot']."/config/config.inc.php");
include_once($config['webroot']."/config/web_config.php"); 
include_once($config['webroot']."/config/table_config.php");
include_once($config['webroot']."/includes/convertip.php");
include_once($config['webroot']."/includes/function.php");
include_once($config['webroot']."/config/uc_config.php");
include_once($config['webroot']."/includes/function.php");
include_once($config['webroot']."/includes/db_class.php"); 
$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);
include_once($config['webroot']."/includes/session.php"); 
if(is_mobile())
{
	if(!empty($_GET["temp"]))
	{
		$_SESSION['temp']=$_GET['temp'];
		$config['temp']=$_SESSION['temp'];
	}
	if(!empty($_SESSION['temp']))
	{
		$config['temp']=$_SESSION['temp'];
	}
	else
	{
		$config['temp']="wap";	
	}
}
else
{
	if(!empty($_GET["temp"]))
	{
		$_SESSION['temp']=$_GET['temp'];
		$config['temp']=$_SESSION['temp'];
	}
	if(!empty($_SESSION['temp']))
		$config['temp']=$_SESSION['temp'];
}
magic();//魔术调用
?>