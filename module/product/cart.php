<?php
//------------------------------ajax加入购物车
if(!empty($_GET['add_cart']))
{
	if(!empty($buid))
	{
		include_once("$config[webroot]/module/product/includes/plugin_cart_class.php");
		$cart=new cart();
		$cart->add_cart($_GET['id'],$_GET['nums'],$_GET['sid'],$_GET['sku']); //加入购物车

		$sql="select * from ".CART." where userid='$buid'";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
			$anums+=$v['num'];
			$aprice+=$v['num']*$v['price'];
		}
		$tpl->assign("nums",$anums);
		$tpl->assign("price",$aprice);
		include_once("footer.php");
		echo $out=tplfetch("cart_ajax.htm",$flag,false);
		die;
	}
}
//===================================
if(empty($buid)&&empty($_GET['type']))
{	
	if(!empty($_GET['add_cart']))
	{
		$pid=$_GET['id']*1;
		$num=$_GET['nums']*1;
		$price=$_GET['price']*1;
		$sid=$_GET['sid']*1;
		$sku=$_GET['sku']*1;
	}
	else
	{
		$pid=$_POST['id']*1;
		$num=$_POST['nums']*1;
		$price="0";
		$sid=$_POST['sid']*1;	
		$sku=$_POST['sku']*1;
	}
	if(empty($_COOKIE['cartnumt']))
	{	
		$cart=$cart."|$pid,$num,$price,$sid,$sku";
		setcookie("cartnumt",$cart,NULL,"/",$config['baseurl']);
	}
	$cart=$_COOKIE['cartnumt']?$_COOKIE['cartnumt']:$cart;
	
		//----------------------------填加
	$ar=explode('|',$cart);unset($ar[0]);
	foreach($ar as $key=>$v)
	{
		$ar[$key]=explode(',',$v);
		$proid[]=$ar[$key][0];
		$code[$ar[$key][0]][]=$ar[$key][4];
	}
	
	if(is_array($proid)&&(!in_array($pid,$proid) || !in_array($sku,$code[$pid])))
	{
		$cart=$cart."|$pid,$num,$price,$sid,$sku";
		setcookie("cartnumt",$cart,NULL,"/",$config['baseurl']);
	}
	
	if(!empty($_GET['add_cart']))
	{
		//-----------------------------计算
		$ar=explode('|',$cart);unset($ar[0]);
	
		foreach($ar as $key=>$v)
		{	
			$ar[$key]=explode(',',$v);
			$anums[]=$ar[$key][1];
			$aprice[]=$ar[$key][1]*$ar[$key][2];
		}
		$tpl->assign("nums",array_sum($anums));
		$tpl->assign("price",array_sum($aprice));
		include_once("footer.php");
		echo $out=tplfetch("cart_ajax.htm",$flag,false);
		die;
	}
	
	msg($config['weburl']."/login.php?forward=".urlencode("$config[weburl]/?m=product&s=cart")); //如果没有登录
}
else
{
	if(!empty($buid))
	{
		include_once("$config[webroot]/module/product/includes/plugin_cart_class.php");
		$cart=new cart();
		$_GET['id']*=1;
		$_GET['nums']*=1;
		$_GET['deid']*=1;
		$_GET['cgnum']*=1;
		
		if($_POST['act']=='match')
		{
			$chk=$_POST['chk'];
			if($chk)
			{
				foreach($chk as $k=>$v)
				{
					$sku=$_POST[skus][$v];
					$tid=$_POST[tids][$v];
					if($v)
						$cart->add_cart($v,'1',$tid,$sku); 
				}	
			}
			$chk1=$_POST['chk1'];
			if($chk1)
			{
				foreach($chk1 as $key=>$val)
				{
					foreach($val as $k=>$v)
					{
						$sku=$_POST["skus".$v];
						if($v)
							$cart->add_cart($key,'1',$v,$sku); 
					}
				}	
			}
		}
		//---------------------------清空购物车
		if(!empty($_GET['clear'])&&empty($_GET['type']))
		{
			$cart->clear_cart();
			setcookie("cartnumt",'',NULL,"/",$config['baseurl']);//清空COOKIE
			msg($config['weburl']."/?m=product&s=cart&type=clear");//购物车已经清空
		}
		//---------------------------修改数量
		if(!empty($_GET['cgnum'])&&!empty($_GET['id'])&&empty($_GET['type']))
		{  
			$flag=$cart->edit_cart($_GET['id'],$_GET['cgnum']);
			if($flag=='error')
				msg($config['weburl']."/?m=product&s=cart&type=numf");//库存数量不够
		}
		//-----------------------通过cookie纪录向购物车添加商品信息
		
		if(!empty($_COOKIE['cartnumt']))
		{
			$ar=explode('|',$_COOKIE['cartnumt']);unset($ar[0]);
			foreach($ar as $key=>$v)
			{
				$pd=explode(',',$v);
				if(!empty($pd[0])&&!empty($pd[1]))
				$cart->add_cart($pd[0],$pd[1],$pd[3],$pd[4]); //将COOKIE记录存入购物车
			}
			setcookie("cartnumt",'',NULL,"/",$config['baseurl']);//清空COOKIE
		}
		
		if(!empty($_POST['id'])&&!empty($_POST['nums'])&&empty($_GET['type']))
		{
			$flag=$cart->add_cart($_POST['id'],$_POST['nums'],$_POST['sid'],$_POST['sku']); 
			if($flag=='error')
				msg($config['weburl']."/?m=product&s=cart&type=pronull");//商品不存在或已经销完
			elseif($flag=='error1')
				msg($config['weburl']."/?m=product&s=cart&type=numf");//库存数量不够
			else
				msg($config['weburl']."/?m=product&s=cart");//库存数量不够
		}
		//---------------------------删除商品信息	
		if(!empty($_GET['deid'])&&!empty($buid)&&empty($_GET['type']))   
		{
			$flag=$cart->del_cart($_GET['deid']);
			if(!isset($flag))
				msg($config['weburl']."/?m=product&s=cart&type=del");//商品已经删除
		
		}
		if($_POST['act']=='del'&&!empty($buid))   
		{
			$flag=$cart->del_cart($_POST['checkbox']);
			msg($config['weburl']."/?m=product&s=cart");//产品已经删除
		}
		//--------------------------读出购物车的数据
		
		$re=$cart->get_listcart('');
		//print_r($re);
		$tpl->assign("cart",$re);
		
	}
}
//================================================
$tpl->assign("current","product");
include_once("footer.php");
$out=tplfetch("cart.htm",$flag,true);
?>