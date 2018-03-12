<?php

	include_once("$config[webroot]/includes/page_utf_class.php");

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
			$_POST['sell_amount']=$_POST['sell_amount']?$_POST['sell_amount']:"0";
			//添加
			if($_POST["act"]=='save')
			{
				$sql="insert into ".POINTSGOODS." (`catid`,`name`,`content`,`pic`,`points`,`price`,`hits`,`sell_amount`,`status`,`create_time`,`stock`,`sku`) values 
('$_POST[catid]','$_POST[name]','$_POST[content]','$_POST[pic]','$_POST[points]','$_POST[price]','$_POST[hits]','$_POST[sell_amount]','$_POST[status]','$time','$_POST[stock]','$_POST[sku]')";
				$db->query($sql);
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".POINTSGOODS." set name='$_POST[name]',catid='$_POST[catid]',content='$_POST[content]',pic='$_POST[pic]',points='$_POST[points]',price='$_POST[price]',sell_amount='$_POST[sell_amount]',stock='$_POST[stock]',status='$_POST[status]',hits='$_POST[hits]',sku='$_POST[sku]' where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=points&s=points.php$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".POINTSGOODS." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		//获取分类
		$sql="select id,catname from ".POINTSCAT." where parent_id=0 order by displayorder";
		$db->query($sql);
		$cat=$db->getRows();
		foreach($cat as $key=>$val)
		{
			$sql="select * from ".POINTSCAT." where parent_id='$val[id]' order by displayorder";
			$db->query($sql);
			$cat[$key]['scat']=$db->getRows();	
		}
		$tpl->assign("cat",$cat);
		$tpl->assign("district",GetDistrict());
		$tpl->assign("config",$config);
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".POINTSGOODS."  where id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			msg("?m=points&s=points.php$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					$sql="delete from ".POINTSGOODS." where id in ($id)";
					$db->query($sql);
				}
			}
			else
			{
				if(is_array($_POST['chk']))
				{
					foreach($_POST['chk'] as $val)
					{
						if($_POST['submit']==$lang['rc'])
						{
							$db->query("update ".POINTSGOODS." set status='2' where id='$val'");
						}
						elseif($_POST['submit']==$lang['btn_open'])
						{
							$db->query("update ".POINTSGOODS." set status='1' where id='$val'");
						}
						elseif($_POST['submit']==$lang['btn_close'])
						{
							$db->query("update ".POINTSGOODS." set status='0' where id='$val'");
						}
					}
				}
			}
			msg("?m=points&s=points.php$getstr");
		}	
		//获取
		$sql="select b.*,c.catname from ".POINTSGOODS." b left join ".POINTSCAT." c on c.id=b.catid order by id desc";
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
	$tpl->assign("config",$config);
	$tpl->display("points.htm");

?>