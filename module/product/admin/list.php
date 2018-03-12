<?php


	if($_GET['operation']=="property")
	{
		//扩展属性
		
	}
	elseif($_GET['operation']=="product")
	{
		//收费服务 产品绑定
		if($_GET['id'])
		{
			$id=$_GET['id']*1;
			$sql="select * from ".PROPERTYVALUETEMPATE." where id ='$id' order by id desc";
			$db->query($sql);
			$de=$db->fetchRow();
			$str=explode(',',$de['item']);
			foreach($str as $k=>$v)
			{
				$arr[]=explode('|',$v);
			}	
			$de['item']=$arr;
			$tpl->assign("de",$de);
		}
	}
	else
	{
		//规格 免费服务 收费服务
		if($_GET['type'])
		{
			$str=" where type='$_GET[type]'";	
		}
		
		$sql="select * from ".PROPERTYVALUETEMPATE.$str." order by id desc";
		$db->query($sql);
		$de=$db->getRows();
		if($de)
		{
			foreach($de as $key=>$val)
			{
				$str=explode(',',$val['item']);
				$arr=array();
				foreach($str as $v)
				{
					$str1=explode('|',$v);
					$arr[]=$str1[1];
				}
				$de[$key]['items']=$arr;
			}
		}
		$tpl->assign("de",$de);
	}
	$tpl->assign("config",$config);
	$tpl->display("list.htm");
	
?>