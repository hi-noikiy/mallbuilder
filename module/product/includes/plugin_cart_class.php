<?php
function get_price($pde,$lgid,$city,$type,$num)
{
	global $db;
	
	$sql="select price_type from ".LGSTEMP." where id='$lgid'";
	$db->query($sql);
	$unit=$db->fetchField('price_type');
			
	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%$city%' and logistics_type='$type'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='$type'";
		$db->query($sql);
		$re=$db->fetchRow();
	}
	if($re)
	{
		if($num<=$re['default_num'])
			$price=$re['default_price'];
		else
		{
			if($unit=='件')
				$price=$re['default_price']+ceil(($num-$re['default_num'])/$re['add_num'])*$re['add_price'];
			if($unit=='kg')
				$price=$re['default_price']+ceil(($num*$pde['weight']-$re['default_num'])/$re['add_num'])*$re['add_price'];
			if($unit=='m³')
				$price=$re['default_price']+ceil(($num*$pde['cubage']-$re['default_num'])/$re['add_num'])*$re['add_price'];
		}
	}
	return $price?$price:'0';
}
function get_log_price($area,$pde,$num)
{
	if(empty($pde['freight_type']))
	{	
		//卖家承担运费
		$mail=0;
		$ems=0;
		$express=0;
	}
	else
	{
		if(empty($pde['freight']))
		{	
			//非运费模板
			$mail=$pde['post_price']*$num;
			$ems=$pde['ems_price']*$num?$pde['ems_price']*$num:"0";
			$express=$pde['express_price']*$num?$pde['express_price']*$num:"0";
		}
		else
		{	
			//运费模板
			$city=$area?','.$area:$area;
			$lgid=$pde['freight'];
			$mail=get_price($pde,$lgid,$city,'mail',$num);
			$ems=get_price($pde,$lgid,$city,'ems',$num);
			$express=get_price($pde,$lgid,$city,'express',$num);
		}
	}
	$re['mail']=$mail;
	$re['ems']=$ems;
	$re['express']=$express;
	return $re;
}
//================================================
class cart
{
	var $db;
	
	function cart()
	{
		global $db;	
		$this -> db     = & $db;
	
	}
	//获取购物车单个商品信息
	function get_cart_detail($id=NULL)
	{
		global $buid;
		$sql="select * from ".CART." where id=$id";
		$this->db->query($sql);
		$re=$this->db->fetchRow();	
		return $re;
	}
	//获取一个产品的物流价格

	//获取一个卖家的已放入购物车的商品信息
	function get_prolist($sell_userid=NULL,$area)
	{
		global $buid;
		
		$sql="select 
		a.*,a.price*a.num as sumprice,a.price,a.num,
		b.amount,b.catid,b.pname,b.pic,b.id as pid,b.freight,b.freight_type,b.rebate,b.post_price,b.ems_price,b.express_price,b.point,
		c.setmeal as setmealname,c.stock from 
		".CART." a left join 
		".PRO." b on  a.pid=b.id left join 
		".SETMEAL." c on a.setmeal=c.id 
		where a.sell_userid=$sell_userid and a.userid=$buid";
		$this->db->query($sql);	 	
		$re=$this->db->getRows();	
	
		foreach($re as $key=>$val)
		{   
			if(empty($val['amount']))
				$re[$key]['amount']=$val['stock'];//产品库存数量,用套餐的替换
						
			$sumprice+=$val['sumprice'];//单店总价
			$fprice=get_log_price($area,$val,$val['num']);
			$list['mail']+=$fprice['mail'];
			$list['ems']+=$fprice['ems'];
			$list['express']+=$fprice['express'];
		}
		$list['sumprice']=$sumprice;//单个卖家的商品总价
		$list['prolist']=$re;//单个店铺的产品列表
		return $list;
		
	}
	//获取购物车卖家列表及卖家商品列表信息及总商品价格
	function get_listcart($area)
	{
		global $buid;  
		$sumprice=0;			
		$sql="select a.id,a.sell_userid,a.setmeal,b.company,b.logo,b.tel  from ".CART." a left join    
		".SHOP." b on a.sell_userid=b.userid where a.userid=$buid  group by sell_userid";
		
		$this->db->query($sql);
		$re=$this->db->getRows();
		foreach($re as $key=>$v)
		{	
		
		     //保存单个店铺商品总价 //平邮 //快点 //EMS 总邮费
			$pro=$this->get_prolist($v['sell_userid'],$area);
			$re[$key]['sumprice']=$pro['sumprice'];
			$re[$key]['mail']=$pro['mail'];
			$re[$key]['ems']=$pro['ems'];
			$re[$key]['express']=$pro['express'];
			
			$re[$key]['prolist']=$pro['prolist'];
			$sumprice+=$pro['sumprice'];	
			
		}
		$res['cart']=$re;
		$res['sumprice']=$sumprice;
		return $res;
	}
	//增加商品
	function add_cart($prid=NULL,$num=1,$sid=0)
	{
		global $buid;  
		$num*=1;
		$str="";
		$sql="select userid,price,amount,code from ".PRO." where id=$prid";
		$this->db->query($sql);
		$pro=$this->db->fetchRow(); 
		if(!empty($sid))
		{
			$sql="select price,stock,sku from ".SETMEAL." where id=$sid";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
			$str=" and setmeal=$sid ";	
		}
		$sql="select id,num from ".CART." where pid=$prid and userid=$buid $str limit 1";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		
		$rnum=$re['num']?$re['num']:0;
		$stock=$sid?$de['stock']:$pro['amount'];
		$price=$sid?$de['price']:($pro['price']?$pro['price']:"0");
		$code=$sid?$de['sku']:$pro['code'];
		$pro['userid']=$pro['userid']?$pro['userid']:'0';
		$sid*=1;
		if($rnum+$num>$stock)
		{
			return "1";	
		}
		else
		{
			if(!empty($re['id']))
			{
				$sql="update ".CART." set num=num+$num where id='$re[id]'";	
				$this->db->query($sql);	
			}
			else
			{
				
				$sql="insert into ".CART."(`userid`,`pid`,`sell_userid`,`price`,`num`,`time` ,`setmeal`,`code`) VALUES ('$buid','$prid','$pro[userid]','$price','$num',".time().",'$sid','$code')";
				$this->db->query($sql);	
			}
		}
		
	}
	//删除购物车内容
	function del_cart($id=NULL)
	{
		if(is_array($id))
		{
			$id=implode(',',$id);
			$sql="delete from ".CART." where id in ($id)";
		}
		else
		{
			$id*=1;
			$sql="delete from ".CART." where id='$id'";
		}
		$flag=$this->db->query($sql);	   
		return $flag;
	}
	//清空购物车内容
	function clear_cart(){
		global $buid;  
		$sql="delete from ".CART." where userid='$buid'";
		$flag=$this->db->query($sql);	   
		return $flag;
	}
	
	//编辑购物车数量
	function edit_cart($cartid=NULL,$num=NULL)
	{
		global $buid;  
		if($num<1&&!empty($cartid))//如果数量小于1就删除
			$this->del_cart($cartid);
		$sql="select b.amount,a.num,c.stock,a.setmeal from ".CART." a left join 
			".PRO." b on a.pid=b.id left join ".SETMEAL." c on a.setmeal=c.id where a.id=$cartid and a.userid=$buid";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if($re['setmeal'])
		{
			if($num>$re['stock']&&isset($re['stock']))
			{
				$sql="update ".CART." set num='$re[stock]' where id=$cartid";	
				$this->db->query($sql);			
				return 'error';//库存不够	
			}
			else
			{  
				$sql="update ".CART." set num='$num' where id='$cartid'";
				$flag=$this->db->query($sql);	   
				return false;
			}
		}
		else
		{
			if($num>$re['amount']&&isset($re['amount']))
			{
				$sql="update ".CART." set num='$re[amount]' where id=$cartid";	
				$this->db->query($sql);			
				return 'error';//库存不够	
			}
			else
			{  
				$sql="update ".CART." set num='$num' where id='$cartid'";
				$flag=$this->db->query($sql);	   
				return false;
			}
		}
	}
	function get_html($array)
	{
		 global $config;  
		 foreach($array['cart'] as $k=>$list)
		 {
			$str.="<tr><th colspan='4' class='shop'>店铺:&nbsp;<a target='_blank' href='shop.php?uid=$list[sell_userid]'>$list[company]</a></th></tr>";
			$price=number_format($list['sumprice']*1+$list['express']*1,2);
			
			if($list['mail']>0 or $list['express']>0 or $list['ems']>0 )
			{
				if($list['express']>0)
				{
					$op.="<option value='快递,$list[express]'>快递 $config[money]".number_format($list['express'])."</option>";
				}
				if($list['mail']>0)
				{
					$op.="<option value='平邮,$list[mail]'>平邮 $config[money]".number_format($list['mail'])."</option>";
				}
				if($list['ems']>0)
				{
					$op.="<option value='EMS,$list[ems]'>EMS $config[money]".number_format($list['ems'])."</option>";	
				}
				$log="<select id='cem_$list[sell_userid]' onchange='count_price(\'$list[sell_userid]\');'>$op</select>";
				$log.="<b>$config[money] <span id='show_cem_$list[sell_userid]'>".number_format($list['express'],2)."</span></b>";
			}
			else
			{
				$log="卖家承担";
			}
			if($list['prolist'])
			{
				foreach($list['prolist'] as $pro)
				{
					$str.="<tr><td class='product'><div class='fl'><a href='$config[weburl]/?m=product&s=detail&id=$pro[pid]'><img height='80' alt='$pro[pname]' src='$pro[pic]'></a></div><div class='fr'><a href='$config.weburl/?m=product&s=detail&id=$pro[pid]'>$pro[pname]</a><p>产品编号:$pro[code]</p></div></td><td class='ac'>$config[money] ".number_format($pro['price'])."</td><td class='ac'>$pro[num]</td><td class='ac'><b class='price'>$config[money] ".number_format($pro['sumprice'],2)."</b></td></tr>";
				}
			}
			$str.="<tr><td colspan='5'></td></tr><tr><td class='msg'><div><div class='fl'>给卖家留言: </div><textarea class='textarea' name='msg_$list[sell_userid]'></textarea><input name='logistics_type_$list[sell_userid]' id='logistics_type_$list[sell_userid]' type='hidden' value='快递'/><input name='logistics_price_$list[sell_userid]' id='logistics_price_$list[sell_userid]' type='hidden' value='$list[express]' /></div></td><td colspan='3' class='ar'><div>运费方式：$log</div><div>店铺合计(含运费)：<b>$config[money] <span class='shop_total_price' id='s_csumprice_$list[sell_userid]'>$price</span></b></div></td></tr>";
		}
		return $str;
	}
}
?>
