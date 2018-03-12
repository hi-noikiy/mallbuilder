<?php
	
	include_once("config.php");
	
	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['s']);
			unset($_GET['m']);
			unset($_GET['operation']);
			$_POST['con_group']*=1;
			if($_POST["act"]=='save')
			{
				$sql="insert into ".WEBCON." (con_no,con_statu,con_title,con_group,con_linkaddr,lang,con_desc,template,type,title,keywords,description) values ('255','$_POST[con_statu]','$_POST[con_title]','$_POST[con_group]','$_POST[con_linkaddr]','$config[language]','$_POST[con_desc]','$_POST[template]','0','$_POST[title]','$_POST[keywords]','$_POST[description]')";
				$db->query($sql);
			}
		
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql=" update ".WEBCON." SET  title='$_POST[title]',keywords='$_POST[keywords]',description='$_POST[description]',con_title='$_POST[con_title]',con_linkaddr='$_POST[con_linkaddr]',con_desc='$_POST[con_desc]',template='$_POST[template]',con_group='$_POST[con_group]',con_statu='$_POST[con_statu]',msg_online='0' where con_id='$_POST[id]'";	
				$db->query($sql);
			}
			$getstr=implode('&',convert($_GET));
			msg("aboutus.php");
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
	else
	{
		if($_GET['delid'])
		{
			$sql="delete from ".WEBCON."  where con_id='$_GET[delid]'";
			$db->query($sql);
			$getstr=implode('&',convert($_GET));
			msg("aboutus.php");
		}
		if($_POST['act']=='op')
		{
			if($_POST['displayorder'])
			{
				foreach($_POST['displayorder'] as $key=>$list)
				{
					$db->query("update ".WEBCON." set con_no = '$list' where con_id = '$key'");		
				}
				msg("aboutus.php");
			}
		}		
		$sql="select * from ".WEBCON." where type=0 and lang='$config[language]' order by con_no";
		$db->query($sql);
		$de['list'] =$db->getRows();
	}
	
	$tpl->assign("de",$de);
	$tpl->display("admin_aboutus.htm");

?>