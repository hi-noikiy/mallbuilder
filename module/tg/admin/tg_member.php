<?php

	include_once("$config[webroot]/includes/page_utf_class.php");

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$_POST['password']=md5($_POST['password']);
			
			if($_POST["act"]=='save')
			{
				$sql="select * from ".TGMEMBER." where user='$_POST[user]'";
				$db->query($sql);
				$re=$db->fetchRow();
				if(!$re)
				{
					$sql="insert into ".TGMEMBER." (`user`,`password`,`name`) values ('$_POST[user]','$_POST[password]','$_POST[name]')";
					$db->query($sql);
				}
				else
				{
					msg("?m=tg&s=tg_member.php&$getstr","用户名已存在");	
				}
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				if($_POST['password'])
				{
					$s=",password='$_POST[password]'";	
				}
				$sql="update ".TGMEMBER." set name='$_POST[name]' $s where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=tg&s=tg_member.php&$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".TGMEMBER." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".TGMEMBER."  where id='$_GET[delid]'";
			$db->query($sql);
			
			$sql="update ".TG." set member_id='' where member_id='$_GET[delid]'";
			$db->query($sql);
				
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			msg("?m=tg&s=tg_member.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".TGMEMBER." where id in ($id)";
				$db->query($sql);
				
				$sql="update ".TG." set member_id='' where member_id in ($id)";
				$db->query($sql);
				msg("?m=tg&s=tg_member.php&$getstr");
			}
		}	
		//获取
		$sql="select* from ".TGMEMBER;
		//=============================
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",20";
		$pages = $page->prompt();
		//=====================
		$db->query($sql);
		$de['list']=$db->getRows();
		$de['page']=$page->prompt();
	}
	$tpl->assign("de",$de);
	$tpl->display("tg_member.htm");

?>