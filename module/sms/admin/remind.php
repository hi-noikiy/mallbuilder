<?php
	//删除
	if($_GET['delid'])
	{
		unset($_GET['s']);
		unset($_GET['m']);
		$sql="delete from ".REMIND."  where id='$_GET[delid]'";
		$db->query($sql);
		unset($_GET['delid']);
		$getstr=implode('&',convert($_GET));
		msg("?m=sms&s=remind.php&$getstr");
	}
	if($_POST['act']=='op')
	{
		if($_POST['name'])
		{
			foreach($_POST['name'] as $key=>$list)
			{
				if(!empty($list))
				{
					$catid=$_POST['catid'][$key];
					$mail_template=$_POST['mail_template'][$key];
					$message_template=$_POST['message_template'][$key];
					$mobile_template=$_POST['mobile_template'][$key];
					
					$mail=$mail_template?"1":"0";
					$message=$message_template?"1":"0";
					$mobile=$mobile_template?"1":"0";
					
					$db->query("update ".REMIND." set name='$list',catid='$catid',mail='$mail',mail_template='$mail_template',message='$message',message_template='$message_template',mobile='$mobile',mobile_template='$mobile_template' where id='$key'");		
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
					$catid=$_POST['newcatid'][$key];
					$inserts[]="('$list','$catid','0','','0','','0','')";	
				}
			}
			if(!empty($inserts))
			{
				$sql="insert into ".REMIND." (`name`,`catid`,`mail`,`mail_template`,`message`,`message_template`,`mobile`,`mobile_template`) values ".implode(",",$inserts);
				$db->query($sql);
			}
		}
		unset($_GET['s']);
		unset($_GET['m']);
		$getstr=implode('&',convert($_GET));
		msg("?m=sms&s=remind.php&$getstr");
	}
	if($_GET['catid'] and is_numeric($_GET['catid']))
	{
		$sql="select id from ".REMINDCAT." where parent_id='$_GET[catid]'";
		$db->query($sql);
		$cat=$db->getRows();	
		foreach($cat as $v)
		{
			$cats[]=$v['id'];
		}
		if($cats)
		{
			$cats=implode(',',$cats);
			$ss="  and catid in ($cats) ";	
			$a=" and parent_id='$_GET[catid]'";	
		}
	}
	else
	{
		$a=" and parent_id='0'";	
	}
	$sql="select * from ".REMIND." where 1 $ss order by id ";
	$db->query($sql);
	$de=$db->getRows();
	
	$sql="select * from ".REMINDCAT." where 1 $a order by displayorder ,id ";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $k=>$v)
	{
		$sql="select * from ".REMINDCAT." where parent_id='$v[id]'";
		$db->query($sql);
		$re[$k]['scat']=$db->getRows();	
	}
	
	$sql="select * from ".REMINDCAT." where parent_id='0' order by displayorder ,id ";
	$db->query($sql);
	$cat=$db->getRows();
	
	$tpl->assign("cat",$cat);
	$tpl->assign("de",$de);
	$tpl->assign("re",$re);
	$tpl->display("remind.htm");
?>