<?php
include_once("includes/global.php");
//=================================
function showUser()
{
	global $config,$db,$buid;
	$new=$inum=0;
	include($config["webroot"]."/lang/".$config['language']."/front.php");
	
	if(!empty($_COOKIE['USER']))
	{
		if($config['temp']=='wap')
		{
			$new='<a href=\'main.php\'>'.$_COOKIE['USER']."</a> &nbsp;<a href='$config[weburl]/main.php?action=logout'>".$lang["logout"]."</a>";
		}
		else
		{
			$new="欢迎来".$config['company'].'！<a class=\'name\' href=\'main.php\'>'.$_COOKIE['USER']."</a> &nbsp;<a href='$config[weburl]/main.php?action=logout'>".$lang["logout"]."</a>";
		}
	
	}
	else
	{	
		if($config['temp']=='wap')
		{
			$new="<a href='".$config["weburl"]."/login.php'>".$lang["login"]."</a> <a href='".$config["weburl"]."/$config[regname]'>".$lang["sigin"]."</a>";	
		}
		else
		{
			$new=$lang["tourist"]." [<a href='".$config["weburl"]."/$config[regname]'>".$lang["sigin"]."</a>] [<a href='".$config["weburl"]."/login.php'>".$lang["login"]."</a>]";
		}
	}
	return $new;
}
echo "document.write(\"".showUser()."\");";
?>