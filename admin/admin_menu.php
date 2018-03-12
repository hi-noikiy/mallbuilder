<?php
	include_once("config.php");
	//删除
	if($_GET['delid'])
	{
		$sql="delete from ".ADMINMENU."  where id='$_GET[delid]'";
		$db->query($sql);
		msg("admin_menu.php");
	}
	if($_POST['act']=='op')
	{
		$uid=$_SESSION['ADMIN_USER_ID'];
		if($_POST['name']&&!empty($_POST['url']))
		{
			foreach($_POST['name'] as $key=>$list)
			{
				if(!empty($list))
				{
					$url=$_POST['url'][$key];
					$displayorder=$_POST['displayorder'][$key];
					$displayorder=$displayorder?$displayorder*1:"255";
					$db->query("update ".ADMINMENU." set name='$list',url='$url',displayorder='$displayorder' where uid='$uid' and id='$key'");		
				}
			}
		}
		
		if(!empty($_POST['newname'])&&!empty($_POST['newurl']))
		{
			$inserts=array();
			foreach($_POST['newname'] as $key=>$list)
			{
				if(!empty($list))
				{		 
					$url=$_POST['newurl'][$key];
					$displayorder=$_POST['newdisplayorder'][$key];
					$displayorder=$displayorder?$displayorder*1:"255";
					$inserts[]="('$list','$url','$displayorder','$uid')";	
				}
			}
			if(!empty($inserts))
			{
				$sql="insert into ".ADMINMENU." (`name`,`url`,`displayorder`,`uid`) values ".implode(",",$inserts);
				$db->query($sql);
			}
		}
		msg("admin_menu.php");
	}
	$sql="select * from ".ADMINMENU." where uid='$_SESSION[ADMIN_USER_ID]' order by displayorder,id ";
	$db->query($sql);
	$de=$db->getRows();
	$tpl->assign("de",$de);
	$tpl->display("admin_menu.htm");
?>