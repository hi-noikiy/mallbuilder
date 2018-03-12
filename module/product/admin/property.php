<?php

	
	include_once("$config[webroot]/includes/page_utf_class.php");
	
	//=================================================
	function creat_table($id)
	{
		global $db,$config;
		$table_name=$config['table_pre']."defind_".$id;
		$csql="
		CREATE TABLE `".$table_name."` (
		  `id` int(11) NOT NULL auto_increment,
		  `info_id` int(11) unsigned default NULL,
		  `info_type` varchar(30) default NULL,
		  `color_img` text default NULL,
		   PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
		";
		$db->query($csql);
		$sql="ALTER TABLE `$table_name` ADD INDEX ( `info_id`,`info_type` )";
		$db->query($sql);
		
		//----------------------初始默认字段
		//$sql="INSERT INTO `".PROPERTYVALUE."` VALUES ( 0,'color', '颜色', '请选择颜色', 0, '','varchar', '255', 1, 5, '1|红色,2|蓝色', 0, $id, '', 1)";
		//$db->query($sql);
		//----------------------
	}

	if($_POST['act']=="save" || $_POST['act']=="edit")
	{	
		unset($_GET['s']);
		unset($_GET['m']);
		unset($_GET['operation']);
		//添加
		if($_POST["act"]=='save')
		{
		
			$spec=substr($_POST['ids'][0],2,(strlen($_POST['ids'][0])-3));
			$free_service=substr($_POST['ids'][1],2,(strlen($_POST['ids'][1])-3));
			$charge_service=substr($_POST['ids'][2],2,(strlen($_POST['ids'][2])-3));
			
			$sql="insert into ".PROPERTY." (`name`) values ('$_POST[pname]')";
			$db->query($sql);
			$id=$db->lastid();
			
			creat_table($id);
			$a = array();
			if($_POST['name'])
			{
				foreach($_POST['name'] as $key=>$val)
				{
					
					$select=$_POST["select"][$key];
					$item1=$_POST["item"][$key];
					$statu=$_POST["statu"][$key];
					$num=$_POST["displayorder"][$key]?$_POST["displayorder"][$key]:"255";
					$item = "";
					$arr = "";
					if($val)
					{	
						
						if($item1)
						{
							$item2=explode(',',$item1);
							foreach($item2 as $k=>$v)
							{
								$displayorder=$k+1;
								if($val and $displayorder)
								{
									$arr[]=$displayorder."|".$v;
								}
							}
							$item=implode(',',$arr);
						}
						
						$display_type=$select==3?1:3;
						$is_search=$select==2?1:0;
						$statu=$statu?1:0;
						
						$sql = "insert into ".PROPERTYVALUE." (`field`,field_name,field_desc,display_type,item,is_search,property_id,statu,type,displayorder) values ('field','$val','','$display_type','$item','$is_search','$id','$statu','1','$num')";
				
						$db->query($sql);
						$sid=$db->lastid();
						
						$c[]=$sid;
						$sql = "update ".PROPERTYVALUE." set `field`='s".$sid."' where id='$sid'";
						$db->query($sql);
						
					}
				}
			}
			
			if($_POST['ids'])
			{
				foreach($_POST['ids'] as $val)
				{
					$val=substr($val,2,strlen($val)-3);
					$arr=explode(',',$val);	
					foreach($arr as $v)
					{
						if($v)
						{
							$a[]=$v;	
						}
					}
				}
			}
			if($a)
			{
				$str=implode(',',$a);
				$sql="select * from ".PROPERTYVALUETEMPATE." where id in ($str)";
				$db->query($sql);
				$re=$db->getRows();
				$table_name=$config['table_pre']."defind_".$id;
				foreach($re as $list)
				{
					
					
					$sql = "insert into ".PROPERTYVALUE." (`field`,field_name,field_desc,display_type,item,is_search,property_id,statu,type,template_id) values ('$list[field]','$list[field_name]','$list[field_desc]','$list[display_type]','$list[item]','$list[is_search]','$id','$list[statu]','$list[type]','$list[id]')";
					$db->query($sql);
					if($list['type']!=3 && $list['type']!=4)
					{
						$sql="ALTER TABLE `".$table_name."` ADD `$list[field]` VARCHAR(255) NULL";
						$db->query($sql);
					}
				}
			}
			if($c)
			{
				$str=implode(',',$c);
				$sql="select field from ".PROPERTYVALUE." where id in ($str)";
				$db->query($sql);
				$re=$db->getRows();
				$table_name=$config['table_pre']."defind_".$id;
				foreach($re as $list)
				{
					$sql="ALTER TABLE `".$table_name."` ADD `$list[field]` VARCHAR(255) NULL";
					$db->query($sql);
				}
			}
			if(!empty($_POST['is_search']))
			{	
				//$sql2="ALTER TABLE `".$table_name."` ADD INDEX ( `$_POST[field]` )";
				//$db->query($sql2);
			}
		}
		//修改
		if($_POST["act"]=='edit' and is_numeric($_POST['id']))
		{
			
			$id = $_POST['id']*1;
			
			$spec=substr($_POST['ids'][0],2,(strlen($_POST['ids'][0])-3));
			$free_service=substr($_POST['ids'][1],2,(strlen($_POST['ids'][1])-3));
			$charge_service=substr($_POST['ids'][2],2,(strlen($_POST['ids'][2])-3));
		
			$sql="update ".PROPERTY." set name='$_POST[pname]' where id='$id'";
			$db->query($sql);
					
			$table_name=$config['table_pre']."defind_".$id;
			
			$a = array();
			$b = array();
			if($_POST['name'])
			{
				foreach($_POST['name'] as $key=>$val)
				{
					
					$select=$_POST["select"][$key];
					$item1=$_POST["item"][$key];
					$statu=$_POST["statu"][$key];
					$num=$_POST["displayorder"][$key]?$_POST["displayorder"][$key]:"255";
					$item = "";
					$arr = "";
					if($val)
					{	
						
						if($item1)
						{
							$item2=explode(',',$item1);
							foreach($item2 as $k=>$v)
							{
								$displayorder=$k+1;
								if($val and $displayorder)
								{
									$arr[]=$displayorder."|".$v;
								}
							}
							$item=implode(',',$arr);
						}
						
						$display_type=$select==3?1:3;
						$is_search=$select==2?1:0;
						$statu=$statu?1:0;

						$sql = "insert into ".PROPERTYVALUE." (`field`,field_name,field_desc,display_type,item,is_search,property_id,statu,type,displayorder) values ('field','$val','','$display_type','$item','$is_search','$id','$statu','1','$num')";
						
						$db->query($sql);
						$sid=$db->lastid();
						
						$c[]=$sid;
						$sql = "update ".PROPERTYVALUE." set `field`='p".$sid."' where id='$sid'";
						$db->query($sql);
					}
				}
			}
			
			if($_POST['pid'])
			{
				foreach($_POST['pid'] as $key=>$val)
				{
					$dd[]=$val;
					$name=$_POST["names"][$key];
					$select=$_POST["selects"][$key];
				
					$item1=$_POST["items"][$key];
					$statu=$_POST["status"][$key];
					$num=$_POST["displayorders"][$key]?$_POST["displayorders"][$key]:"255";
					
					$item = "";
					$arr = "";
					if($name)
					{	
						if($item1)
						{
							$item2=explode(',',$item1);
							foreach($item2 as $k=>$v)
							{
								$displayorder=$k+1;
								if($val and $displayorder)
								{
									$arr[]=$displayorder."|".$v;
								}
							}
							$item=implode(',',$arr);
						}
			
						$display_type=$select==3?1:3;
						$is_search=$select==2?1:0;
						$statu=$statu?1:0;
						
						$sql = "update ".PROPERTYVALUE." set `field_name`='$name',`display_type`='$display_type',`item`='$item',`is_search`='$is_search',`statu`='$statu' ,`displayorder`='$num' where id='$val'";
						$db->query($sql);
						
					}
					else
					{
						$d[]=$val;	
					}
	
				}				
				if($_POST['old_property'])
				{
					$old_property=explode(',',$_POST['old_property']);	
					foreach($old_property as $l)
					{
						if(!in_array($l,$dd))
						{
							$d[]=$l;
						}
					}
				}
			}
			
			if($_POST['ids'])
			{
				foreach($_POST['ids'] as $key=>$val)
				{
					
					$val=substr($val,2,strlen($val)-3);
					$arr=explode(',',$val);	
					
					if($_POST['old_ids'][$key])
					{
						$old=$_POST['old_ids'][$key];
						$old_arr=explode(',',$old);	
						foreach($old_arr as $v)
						{
							if($v)
							{	
								if(!in_array($v,$arr))
								{
									$b[]=$v;	
								}
							}
						}
					}
					foreach($arr as $v)
					{
						if($v)
						{	
							if($_POST['old_ids'][$key])
							{
								if(!in_array($v,$old_arr))
								{
									$a[]=$v;	
								}
							}
							else
							{
								$a[]=$v;	
							}
						}
					}
				}
			}
		
			if($a)
			{
				$str=implode(',',$a);
				$sql="select * from ".PROPERTYVALUETEMPATE." where id in ($str)";
				$db->query($sql);
				$re=$db->getRows();
				
				foreach($re as $list)
				{
					
					$displayorder=$_POST['displayorder'.$list['id'].'']?$_POST['displayorder'.$list['id'].'']:"255";
					$pro=$pro?$pro:"";
					
					$sql = "insert into ".PROPERTYVALUE." (`field`,field_name,field_desc,display_type,item,is_search,property_id,statu,type,template_id,displayorder) values ('$list[field]','$list[field_name]','$list[field_desc]','$list[display_type]','$list[item]','$list[is_search]','$id','$list[statu]','$list[type]','$list[id]','$displayorder')";
					$db->query($sql);
					if($list['type']!=3 && $list['type']!=4)
					{
						$sql="ALTER TABLE `".$table_name."` ADD `$list[field]` VARCHAR(255) NULL";
						$db->query($sql);
					}
				}
			}
			if($b)
			{
				$str=implode(',',$b);
				$sql="select id,field,type from ".PROPERTYVALUE." where template_id in ($str)";
				$db->query($sql);
				$re=$db->getRows();
				
				foreach($re as $list)
				{
					$sql="select id,field,type from ".PROPERTYVALUE." where field = '$list[field]' ";
					$db->query($sql);
					$s=$db->fetchRow();
				
					if($s['type']!=3 && $s['type']!=4)
					{
						$db->query("ALTER TABLE `$table_name` DROP `$s[field]`");
					}
					$sql="delete from ".PROPERTYVALUE." where id='$s[id]'";
					$db->query($sql);
				}
			}
			if($c)
			{
				$str=implode(',',$c);
				$sql="select field from ".PROPERTYVALUE." where id in ($str)";
				$db->query($sql);
				$re=$db->getRows();
				foreach($re as $list)
				{
					$sql="ALTER TABLE `".$table_name."` ADD `$list[field]` VARCHAR(255) NULL";
					$db->query($sql);
				}
			}
			if($d)
			{
				$str=implode(',',$d);
				$sql="select id,field from ".PROPERTYVALUE." where id in ($str)";
				$db->query($sql);
				$re=$db->getRows();
				foreach($re as $list)
				{
					$db->query("ALTER TABLE `$table_name` DROP `$list[field]`");
					$sql="delete from ".PROPERTYVALUE." where id='$list[id]'";
					$db->query($sql);
				}
			}
			unset($_GET['editid']);
		}
		$getstr=implode('&',convert($_GET));
		echo "<script>window.parent.main.document.location.reload();window.parent.DialogManager.close('property');</script>";
		msg("?m=product&s=property.php&operation=list&$getstr");
	}
	//信息
	if($_GET['editid'] and is_numeric($_GET['editid']))
	{
		
		$sql="select * from ".PROPERTY." where id='$_GET[editid]'";
		$db->query($sql);
		$de=$db->fetchRow();
		
		$sql="select * from ".PROPERTYVALUE." where property_id='$_GET[editid]' order by displayorder";
		$db->query($sql);
		$re=$db->getRows();
	
		if($re)
		{	
		
			$cc=$dd=$ee=$ff=array();
			foreach($re as $key=>$val)
			{
				if($val['type']==1)
				{
					$str=explode(',',$val['item']);
					$arr=array();
					$cc[]=$val['id'];
					foreach($str as $v)
					{
						$str1=explode('|',$v);
						$arr[]=$str1[1];
					}	
					$de['value'][$key]=$val;
					$de['value'][$key]['items']=implode(",",$arr);
					
				}
				else if($val['type']==2)
				{
					
					$de['spec_list'][$key]=$val;
					$dd[]=$de['spec_list'][$key]['template_id'];
					$de['spec_list'][$key]['id']=$de['spec_list'][$key]['template_id'];
					
				}
				else if($val['type']==3)
				{
				
					$de['free_service_list'][$key]=$val;
					$ee[]=$de['free_service_list'][$key]['template_id'];
					$de['free_service_list'][$key]['id']=$de['free_service_list'][$key]['template_id'];
		
				}
				else if($val['type']==4)
				{
					$de['charge_service_list'][$key]=$val;
					
					$de['charge_service_list'][$key]['id1']=$de['charge_service_list'][$key]['id'];
					$ff[]=$de['charge_service_list'][$key]['id']=$de['charge_service_list'][$key]['template_id'];
					
				}
			}
			$de['property']=$cc?implode(",",$cc):"";
			$de['spec']=$dd?implode(",",$dd):"";
			$de['free_service']=$ee?implode(",",$ee):"";
			$de['charge_service']=$ff?implode(",",$ff):"";
		}
	}
	else
	{	
		
		if($_POST['act']=='op')
		{			
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".PROPERTY." where id in ($id)";
				$db->query($sql);
				$sql="delete from ".PROPERTYVALUE." where property_id in ($id)";
				$db->query($sql);
				foreach($_POST["chk"] as $val)
				{
					$table_name=$config['table_pre']."defind_".$val;
					$db->query("DROP TABLE `$table_name`");//删除生成的表
				}
				msg("?m=product&s=property.php");
			}									
		}
		
		//===============================================
		if(!empty($_GET['key']))
		{
			$str=" and name like '%$_GET[key]%' ";
		}
		
		$sql="select * from ".PROPERTY." where 1 $str order by id desc";
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
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$db->query($sql);
		$de['list']=$db->getRows();
		$de['page']=$page->prompt();
		
	}
	
	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$tpl->display("property.htm");

?>
