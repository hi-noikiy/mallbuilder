<?php
	
	include_once("../includes/page_utf_class.php");
	
	if($_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
			//修改
			if($_POST["act"]=='shipping' and is_numeric($_POST['id']))
			{
				$sql="update ".POINTSORDER." set shipping_name='$_POST[shipping_name]',shipping_address='$_POST[t] $_POST[shipping_address]',shipping_tel='$_POST[shipping_tel]',shipping_company='$_POST[shipping_company]',shipping_code='$_POST[shipping_code]',status='20',shipping_time='$time' where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			if($_POST["act"]=='cancel' and is_numeric($_POST['id']))
			{
				include_once("$config[webroot]/module/member/includes/plugin_member_class.php");
				$member=new member();
		
				$admin_remark=$_POST['other_reason']?$_POST['admin_remark']." ".$_POST['other_reason']:$_POST['admin_remark'];
				$sql="update ".POINTSORDER." set admin_remark='$admin_remark',status='0',finnshed_time='$time' where id='".$_POST['id']."'";
				$db->query($sql);
				
				$flag=$member->add_points($_POST['points'],'3',$_POST['order_id'],$_POST['buid']);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=points&s=points_order.php$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".POINTSORDER." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
			
			$sql="select * from ".FASTMAIL."  order by id";
			$db->query($sql);
			$re=$db->getRows();
			$tpl->assign("re",$re);
			
			$tpl->assign("district",GetDistrict());
			$tpl->assign("config",$config);
		}
	}
	else
	{
		$str=NULL;
		if(!empty($_GET["type"]) and is_numeric($_GET['type']))
		{
			$str=" and status = '$_GET[type]' ";
		}
		$sql="select * from ".POINTSORDER." where 1 $str order by id desc ";
		//=============================
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$pages = $page->prompt();
		//=====================
		$db->query($sql);
		$de['list']=$db->getRows();
		$de['page']=$page->prompt();
	}
	$statu_list =array('0'=>'已取消','10'=>'未发货','20'=>'已发货','30'=>'已完成');
	$tpl->assign("de",$de);
	$tpl->assign("statu_list",$statu_list);
	$tpl->display("points_order.htm");

?>