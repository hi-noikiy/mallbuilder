<?php
	function randomkeys($length) {
		global $db;
		$returnStr='';
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
		for($i = 0; $i < $length; $i ++) {
			$returnStr .= $pattern {mt_rand ( 0, 61 )}; //生成php随机数
		}
		$sql="select * from ".TGORDER." where code='$returnStr'";
		$db->query($sql);
		$s=$db->fetchRow();
		if($s)
		{
			randomkeys($length);
		}
		return $returnStr;
	}
	include_once("../includes/page_utf_class.php");
	
	if($_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
			
			//实物发货
			if($_POST["act"]=='shipping' and is_numeric($_POST['id']))
			{
				$sql="update ".TGORDER." set shipping_name='$_POST[shipping_name]',shipping_address='$_POST[t] $_POST[shipping_address]',shipping_tel='$_POST[shipping_tel]',shipping_company='$_POST[shipping_company]',shipping_code='$_POST[shipping_code]',status='40',shipping_time='$time' where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			//虚拟产品发货
			if($_POST["act"]=='send' and is_numeric($_POST['id']))
			{
				$code=$_POST['code']?$_POST['code']:randomkeys(8);
				$sql="update ".TGORDER." set code='$code',status='40',shipping_time='$time' where id='".$_POST['id']."'";
				$db->query($sql);
				
				$title=$_POST['title']?$_POST['title']:"";
				$content=$_POST['content']?$_POST['content']:"";
				if($title&&$content)
				{
					$sql="select id,member_id,member_name,order_id,tg_name from ".TGORDER." where id='$_POST[id]'";
					$db->query($sql);
					$info=$db->fetchRow();
					
					$sql="select name from ".MEMBER." where userid='$info[member_id]'";
					$db->query($sql);
					$name=$db->fetchField('name');
					
					$name=$name?$name:$info['member_name'];
					
					$title=str_replace('{username}',$name,$title);
					$title=str_replace('{sitename}',$config['company'],$title);
					$title=str_replace('{order_sn}',$info['order_id'],$title);
					$title=str_replace('{product_name}',$info['tg_name'],$title);
					
					$content=str_replace('{username}',$name,$content);
					$content=str_replace('{sitename}',$config['company'],$content);
					$content=str_replace('{time}',date("Y-m-d H:i:s",time()),$content);
					$content=str_replace('{adminemail}',$config['email'],$content);
					$content=str_replace('{order_sn}',$info['order_id'],$content);
					$content=str_replace('{order_id}',$info['id'],$content);
					$content=str_replace('{product_name}',$info['tg_name'],$content);
					$content=str_replace('{code}',$code,$content);
					
					$sql="insert into ".FEEDBACK." (touserid,fromInfo,sub,con,date,msgtype) VALUES ('".$info['member_id']."','系统信息','".$title."','".$content."','".date("Y-m-d H:i:s")."','3')";
					$db->query($sql);
				}
				unset($_GET['editid']);
			}
			//取消
			if($_POST["act"]=='cancel' and is_numeric($_POST['id']))
			{
				$admin_remark=$_POST['other_reason']?$_POST['admin_remark']." ".$_POST['other_reason']:$_POST['admin_remark'];
				$sql="update ".TGORDER." set admin_remark='$admin_remark',status='10',finished_time='$time' where id='".$_POST['id']."'";
				$db->query($sql);
				
				$sql="select member_id,order_id from ".TGORDER." where id='$_POST[id]'";
				$db->query($sql);
				$de=$db->fetchRow();
			
				$post['action']='update';
				$post['seller_email']=0;
				$post['buyer_email']=$de['member_id'];//卖家账号
				$post['order_id']=$de['order_id'];//外部订单号
				$post['statu']=0;//取消的订单
				$res=pay_get_url($post,true);
				
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=tg&s=tg_order.php&$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".TGORDER." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
			if($de['is_virtual']=='true')
			{
				$code=randomkeys(8);
				$tpl->assign("code",$code);
			}
			else
			{
				$sql="select * from ".FASTMAIL."  order by id";
				$db->query($sql);
				$re=$db->getRows();
				$tpl->assign("re",$re);
			}
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
		$sql="select * from ".TGORDER." where 1 $str order by id desc ";
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
	
	$statu_list =array('10'=>'已取消','20'=>'未支付','30'=>'未发货','40'=>'已发货','50'=>'已完成');

	$tpl->assign("de",$de);
	$tpl->assign("statu_list",$statu_list);
	$tpl->display("tg_order.htm");

?>