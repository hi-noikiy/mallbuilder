<?php

	include_once("$config[webroot]/includes/page_utf_class.php");
	

	if($_POST['act']=='save' or $_POST['act']=='edit')
	{	
		unset($_GET['s']);
		unset($_GET['m']);
		unset($_GET['operation']);
		//添加
		if($_POST["act"]=='save')
		{
			$item="";
			foreach($_POST['name'] as $key=>$val)
			{
				$displayorder=$_POST['displayorder'][$key];
				$displayorder=$displayorder?$displayorder*1:rand(1,99999999);
				if($val and $displayorder)
				{
					$arr[]=$displayorder."|".$val;
				}
			}
			$item=implode(',',$arr);
			
			
			$sql = "insert into ".PROPERTYVALUETEMPATE." (`field`,field_name,field_desc,display_type,item,is_search,statu,type) values ('field','$_POST[field_name]','$_POST[field_desc]','5','$item','0','1','2')";
			$db->query($sql);
			$id=$db->lastid();
			
			$sql = "update ".PROPERTYVALUETEMPATE." set `field`='g".$id."' where id='$id'";
			$db->query($sql);
			
		}
		//修改
		if($_POST["act"]=='edit' and is_numeric($_POST['id']))
		{
			
			$item="";
			foreach($_POST['name'] as $key=>$val)
			{
				$displayorder=$_POST['displayorder'][$key];
				$displayorder=$displayorder?$displayorder*1:rand(1,99999999);
				if($val and $displayorder)
				{
					$arr[]=$displayorder."|".$val;
				}
			}
			if($arr)
				$item=implode(',',$arr);
			
			$sql = "update ".PROPERTYVALUETEMPATE." set `field_name`='$_POST[field_name]',`field_desc`='$_POST[field_desc]',`item`='$item' where id='$_POST[id]'";
			$db->query($sql);
			
			$sql = "update ".PROPERTYVALUE." set `field_name`='$_POST[field_name]',`field_desc`='$_POST[field_desc]',`item`='$item' where template_id='$_POST[id]'";
			$db->query($sql);
			
			unset($_GET['editid']);
		}
		$getstr=implode('&',convert($_GET));
		msg("?m=product&s=spec.php&$getstr");
	}
	//信息
	if($_GET['editid'] and is_numeric($_GET['editid']))
	{
		$sql="select * from ".PROPERTYVALUETEMPATE." where id='$_GET[editid]'";
		$db->query($sql);
		$de=$db->fetchRow();
		$str=explode(',',$de['item']);
		foreach($str as $v)
		{
			$str1[]=explode('|',$v);
		}
		$de['items']=$str1;
	}
	
	else
	{		
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				foreach($_POST["chk"] as $v)
				{
					$sql="delete from ".PROPERTYVALUETEMPATE." where id=$v";
					$db->query($sql);
				}
				msg("?m=product&s=spec.php");
			}						
		}

		if(!empty($_GET['key']))
		{
			$str=" and field_name like '%$_GET[key]%' ";
		}
		$sql="select * from ".PROPERTYVALUETEMPATE." where type='2' $str";
		$page = new Page;
		$page->listRows=12;
		
		//分页
		if (!$page->__get('totalRows'))
		{
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$count=$page->totalRows;
		$tpl->assign("count",$count);
		$sql .= " order by id desc  limit ".$page->firstRow.",".$page->listRows;
		$db->query($sql);
		$de['list']=$db->getRows();
		if($de['list'])
		{
			foreach($de['list'] as $key=>$val)
			{
				$str=explode(',',$val['item']);
				$arr=array();
				foreach($str as $v)
				{
					$str1=explode('|',$v);
					$arr[]=$str1[1];
				}
				$de['list'][$key]['items']=implode(",",$arr);
			}
		}
		$de['page']=$page->prompt();
	}
	
	$tpl->assign("config",$config);
	$tpl->assign("de",$de);
	$tpl->display("spec.htm");
	

?>
