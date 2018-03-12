<?php
//删除
	if($_GET['delid'])
	{
		$sql="delete from ".BRAND."  where id='$_GET[delid]'";
		$db->query($sql);
		msg("?m=payment&s=brand.php");
	}
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			$sql="delete from ".BRAND." where id in ($id)";
			$db->query($sql);
		}
		if($_POST['name'])
		{
			foreach($_POST['name'] as $key=>$list)
			{
				$fee_rates=$_POST['fee_rates'][$key]*1;
				if(!empty($list)&&!empty($fee_rates))
				{
					$fee_min=$_POST['fee_min'][$key]?$_POST['fee_min'][$key]*1:"2";
					$fee_max=$_POST['fee_max'][$key]?$_POST['fee_max'][$key]*1:"25";
					$db->query("update ".BRAND." set name='$list',fee_rates='$fee_rates',fee_min='$fee_min',fee_max='$fee_max' where id='$key'");		
				}
			}
		}
		if(!empty($_POST['newname']))
		{
			$inserts=array();
			foreach($_POST['newname'] as $key=>$list)
			{
				$fee_rates=$_POST['newfee_rates'][$key];
				if(!empty($list)&&!empty($fee_rates))
				{
					$fee_min=$_POST['newfee_min'][$key]?$_POST['newfee_min'][$key]*1:"2";
					$fee_max=$_POST['newfee_max'][$key]?$_POST['newfee_max'][$key]*1:"25";
					$inserts[]="('$list','$fee_rates','$fee_min','$fee_max')";	
				}
			}
			if(!empty($inserts))
			{
				$sql="insert into ".BRAND." (`name`,`fee_rates`,`fee_min`,`fee_max`) values ".implode(",",$inserts);
				$db->query($sql);
			}
		}
		msg("?m=payment&s=brand.php");
	}
	$sql="SELECT * FROM ".BRAND;
	//====================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$de['page'] = $page->prompt();
	//=====================
	$db->query($sql);
	$de['list']=$db->getRows();
	$tpl->assign("de",$de);
	$tpl->display("brand.htm");
?>