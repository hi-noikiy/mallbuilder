<?php
	
	include_once("config.php");

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['s']);
			unset($_GET['m']);
			unset($_GET['operation']);
			
			if($_POST["act"]=='save')
			{
				$sql="insert into ".WEBCON." (con_no,con_statu,con_title,con_group,con_linkaddr,lang,con_desc,template,type,title,keywords,description) values ('255','$_POST[con_statu]','$_POST[con_title]','$_POST[con_group]','$_POST[con_linkaddr]','$config[language]','$_POST[con_desc]','$_POST[template]','1','$_POST[title]','$_POST[keywords]','$_POST[description]')";
				$db->query($sql);
			}
		
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql=" update ".WEBCON." SET  title='$_POST[title]',keywords='$_POST[keywords]',description='$_POST[description]',con_title='$_POST[con_title]',con_linkaddr='$_POST[con_linkaddr]',con_desc='$_POST[con_desc]',template='$_POST[template]',con_group='$_POST[con_group]',con_statu='$_POST[con_statu]',msg_online='0' where con_id='$_POST[id]'";	
				$db->query($sql);
			}
			$getstr=implode('&',convert($_GET));
			msg("help.php");
		}
	
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".WEBCON." where con_id ='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		
		$sql="select * from ".WEBCONGROUP." where lang='$config[language]'";
		$db->query($sql);
		$re =$db->getRows();
		$tpl->assign("re",$re);
	}
	elseif($_GET['operation']=="cat")
	{
		if($_GET['delid'])
		{
			$sql="delete from ".WEBCONGROUP."  where id='$_GET[delid]'";
			$db->query($sql);
			msg("help.php?operation=cat");
		}
		if($_POST['act']=='op')
		{
			if($_POST['name'])
			{
				foreach($_POST['name'] as $key=>$list)
				{
					if(!empty($list))
					{
						$displayorder=$_POST['displayorder'][$key];
						$displayorder=$displayorder?$displayorder*1:"255";
						$db->query("update ".WEBCONGROUP." set title='$list',lang='$config[language]',`sort`='$displayorder' where id='$key'");		
					}
				}
			}
			
			if(!empty($_POST['newname']))
			{
				$inserts=array();
				foreach($_POST['newname'] as $key=>$list)
				{
					if(!empty($list))
					{
						$displayorder=$_POST['newdisplayorder'][$key];
						$displayorder=$displayorder?$displayorder*1:"255";
						$inserts[]="('$list','$displayorder','$config[language]')";	
					}
				}
				if(!empty($inserts))
				{
					$sql="insert into ".WEBCONGROUP." (`title`,`sort`,`lang`) values ".implode(",",$inserts);
					$db->query($sql);
				}
			}
			msg("help.php?operation=cat");
		}
		$sql="select * from ".WEBCONGROUP." order by sort ,id ";
		$db->query($sql);
		$de=$db->getRows();
	}
	else
	{
		if($_GET['delid'])
		{
			$sql="delete from ".WEBCON."  where con_id='$_GET[delid]'";
			$db->query($sql);
			$getstr=implode('&',convert($_GET));
			msg("help.php");
		}
		if($_POST['act']=='op')
		{
			if($_POST['displayorder'])
			{
				foreach($_POST['displayorder'] as $key=>$list)
				{
					$db->query("update ".WEBCON." set con_no = '$list' where con_id = '$key'");		
				}
				msg("help.php");
			}
		}		
		$sql="select * from ".WEBCON." a left join ".WEBCONGROUP." b on con_group = b.id where type=1 and a.lang='$config[language]' order by con_no";
		$db->query($sql);
		$de['list'] =$db->getRows();
	}
	
	$tpl->assign("de",$de);
	$tpl->display("admin_help.htm");

?>