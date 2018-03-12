<?php
	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
			
			$_POST['isopen']=$_POST['isopen']?'1':'0';
			$_POST['stime']="'".strtotime($_POST['stime'])."'";
			$_POST['etime']="'".strtotime($_POST['etime'])."'";
			$_POST['catid']*=1;
			if($_POST['ad_type']==3)
			{
				$_POST['pic']="";
			}
			else
			{
				$_POST['con']="";
			}
			//添加
			if($_POST["act"]=='save')
			{
				$sql="insert into ".ADVSCON." (group_id,url,`type`,isopen,con,picName , `stime` , `etime`,name,province,city,area,width,height,catid)
		  values ('$_POST[group_id]','$_POST[url]','1','$_POST[isopen]','$_POST[con]','$_POST[pic]',$_POST[stime],$_POST[etime],'$_POST[name]','$_SESSION[province]','$_SESSION[city]','$_SESSION[area]','$_POST[width]','$_POST[height]','$_POST[catid]')";
				$db->query($sql);
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="UPDATE ".ADVSCON." SET url='$_POST[url]',picName='$_POST[pic]',catid='$_POST[catid]',group_id='$_POST[group_id]',isopen='$_POST[isopen]',stime=$_POST[stime],etime=$_POST[etime],con='$_POST[con]',name='$_POST[name]',width='$_POST[width]',height='$_POST[height]' WHERE ID=$_POST[id]";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=adv&s=adv.php&operation=ads&$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="SELECT a.*,b.ad_type FROM ".ADVSCON." a left join ".ADVS." b on a.group_id=b.id WHERE a.ID='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		$sql="select name,ID,ad_type from ".ADVS." where name <> '' order by id";
		$db->query($sql);
		$re=$db->getRows($sql);
		if(!$re)
		{
			msg("module.php?m=adv&s=adv.php&operation=add_ads",'请添加广告位');
		}
		$tpl->assign("re",$re);
		$tpl->assign("time",time());
		$tpl->assign("time1",time()+24*30*3600);
	}
	elseif($_GET['operation']=="add_ads" or $_GET['operation']=="edit_ads")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
			
			$_POST['isopen']=$_POST['isopen']?'1':'0';
			$_POST['stime']="'".strtotime($_POST['stime'])."'";
			$_POST['etime']="'".strtotime($_POST['etime'])."'";
			$_POST['catid']*=1;
			$date=date("Y-m-d H:i:s");
			
			//添加
			if($_POST["act"]=='save')
			{
		$sql="insert into ".ADVS." (con,ad_type,name,date,width,height,price,unit,`group`) values  ('$_POST[con]','$_POST[ad_type]','$_POST[name]','$date','$_POST[width]','$_POST[height]','$_POST[price]','$_POST[unit]','$_POST[group]')";
				$db->query($sql);
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="UPDATE ".ADVS." SET con='$_POST[con]',name='$_POST[name]',ad_type='$_POST[ad_type]',width='$_POST[width]',height='$_POST[height]',total='$_POST[total]',price='$_POST[price]',unit='$_POST[unit]',`group`='$_POST[group]' WHERE ID='$_POST[id]'";
			
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=adv&s=adv.php&$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="SELECT * FROM ".ADVS." WHERE ID='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
	}
	elseif($_GET['operation']=="ads")
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".ADVSCON."  where id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=adv&s=adv.php&operation=ads&$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['chk'])
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".ADVSCON." where ID in ($id)";
				$db->query($sql);
				
				$getstr=implode('&',convert($_GET));
				msg("?m=adv&s=adv.php&operation=ads&$getstr");
			}	
		}	
		$sql="select ID,`name` from ".ADVS." order by id ";
		$db->query($sql);
		$re=$db->getRows();
		$tpl->assign("re",$re);
	
		if($_GET['ad_type'] and is_numeric($_GET['ad_type']))
		{
			$ad_type=$_GET['ad_type']*1-1;
			$s.=" and b.ad_type='$ad_type'";
		}
		if($_GET['group'])
		{
			$s.=" and a.`group_id`='$_GET[group]'";
		}
		if($_GET['name'])
		{
			$s.=" and a.name like '%$_GET[name]%' ";
		}
		
		include_once("$config[webroot]/includes/page_utf_class.php");
		$sql="select a.*,b.name as title,b.ad_type from ".ADVSCON." a left join ".ADVS." b on a.group_id=b.id where 1 $s order by id desc";
		
		$page = new Page;
		$page->listRows=20;
		//分页
		if (!$page->__get('totalRows'))
		{
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$db->query($sql);
		$de['list']=$db->getRows();
		$de['page']=$page->prompt();
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".ADVS."  where id='$_GET[delid]'";
			$db->query($sql);
			$sql="delete from ".ADVSCON."  where group_id = '$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=adv&s=adv.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['chk'])
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".ADVS." where ID in ($id)";
				$db->query($sql);
				$sql="delete from ".ADVSCON."  where group_id in ($id)";
				$db->query($sql);
				
				$getstr=implode('&',convert($_GET));
				msg("?m=adv&s=adv.php&$getstr");
			}	
		}	
		$sql="select `group` from ".ADVS." group by `group` order by id ";
		$db->query($sql);
		$re=$db->getRows();
		$tpl->assign("re",$re);
	
		if($_GET['ad_type'] and is_numeric($_GET['ad_type']))
		{
			$ad_type=$_GET['ad_type']*1-1;
			$s.=" and ad_type='$ad_type'";
		}
		if($_GET['group'])
		{
			$s.=" and `group`='$_GET[group]'";
		}
		if($_GET['name'])
		{
			$s.=" and name like '%$_GET[name]%' ";
		}
		
		include_once("$config[webroot]/includes/page_utf_class.php");
		$sql="select * from ".ADVS." where 1 $s order by id";
		$page = new Page;
		$page->listRows=20;
		//分页
		if (!$page->__get('totalRows'))
		{
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$db->query($sql);
		$de['list']=$db->getRows();
		foreach($de['list'] as $key=>$val)
		{
			$sql="select count(*) as num , sum(shownum) as shownum from ".ADVSCON." WHERE group_id='".$val['ID']."'";
			$db->query($sql);
			$sss=$db->fetchRow();
			$de['list'][$key]['num']=$sss['num']?$sss['num']:"0";
			$de['list'][$key]['shownum']=$sss['shownum']?$sss['shownum']:"0";
			
			$sql="select count(*) as num from ".ADVSCON." WHERE isopen=1 and group_id='".$val['ID']."'";
			$db->query($sql);
			$num1=$db->fetchField('num');
			$de['list'][$key]['num1']=$num1?$num1:"0";
		}
		$de['page']=$page->prompt();
	}
	$type=array("图片","幻灯","滚动",'文字');
	$tpl->assign("type",$type);
	$val=$_GET;
	unset($val['m']);
	unset($val['s']);
	unset($val['operation']);
	$tpl->assign("getstr",implode('&',convert($val)));
	$tpl->assign("de",$de);
	$tpl->display("adv.htm");
	
?>