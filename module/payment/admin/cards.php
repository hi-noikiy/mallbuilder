<?php
include_once("$config[webroot]/includes/page_utf_class.php");
if($_GET['operation']=="add" or $_GET['operation']=="edit")
{
	if($_POST['act'])
	{	
		unset($_GET['operation']);
		unset($_GET['s']);
		unset($_GET['m']);
		
		//添加公告
		if($_POST["act"]=='save')
		{
			$total_price=$_POST['total_price']*1;
			if($total_price>0)
			{
				$str="abcdefghijklmnopqrstuvwxyz";
				$num=$_POST['num']?$_POST['num']*1:"1";
				for($j=0;$j<$num;$j++)
				{
					$password='';
					for($i=0;$i<8;$i++) $password.=$str[rand(0,25)];
					$card_num=rand(1000000000,9999999999);
					
					$sql="INSERT INTO ".PAYCARD." (`card_num` ,`total_price` ,`password` 
					,`statu` ,`creat_time` ,`pic`,`stime`,`etime`,give_price )VALUES('$card_num','$total_price','$password', '0', '".time()."', '$_POST[pic]','".($_POST['stime']?strtotime($_POST['stime']):0)."','".($_POST['etime']?strtotime($_POST['etime']):0)."','$_POST[give_price]')";
					$db->query($sql);
				}	
				$getstr=implode('&',convert($_GET));
				msg("?m=payment&s=cards.php&$getstr");	
			}
		}
	}
}
else
{	
	//删除公告
	if($_GET['delid'])
	{
		$db->query("delete from ".PAYCARD." where id='$_GET[delid]'");
		unset($_GET['delid']);
		unset($_GET['s']);
		unset($_GET['m']);
		$getstr=implode('&',convert($_GET));
		msg("?m=payment&s=cards.php&$getstr");
	}
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			$sql="delete from ".PAYCARD." where id in ($id)";
			$db->query($sql);
		}
		msg("?m=payment&s=cards.php&$getstr");
	}
	
	$sql="select * from ".PAYCARD." order by statu,id desc";
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
	
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->display("cards.htm");
?>
