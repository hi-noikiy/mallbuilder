<?php

class member
{
	var $db;
	function member()
	{
		global $db;	
		$this -> db     = & $db;
	}
	
	/**
	 * 邮箱是否验证
	 * @param $member_id 会员ID 默认值：NULL
	 * return 结果字符串
	 */
	function email_reg($member_id=NULL)
	{
		global $buid;
		$buid=$uid?$uid:$buid;
		$sql="SELECT email_verify FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$email_verify=$this->db->fetchField('email_verify');
		return $email_verify==1?"true":"false";
	}
	/**
	 * 获取会员好友,粉丝,微博数量
	 * @param $member_id 会员ID 默认值：NULL
	 * return 结果数组
	 */
	function get_count($member_id)
	{
		if(empty($member_id)) return NULL;
		$sql="select * from ".MEMBERCOUNT." WHERE member_id='$member_id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	function get_member_detail($id)
	{
		global $buid,$config;
		if(empty($id))
			$id=$buid;
		$sql="select * from ".MEMBER." WHERE userid='$id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	
	function update_member($uid)
	{
		global $config,$buid;$logo=NULL;$ssql=NULL;
		if(empty($uid))
			$uid=$buid;
		
		$_POST['sex']=empty($_POST['sex'])?1:$_POST['sex'];
		$_POST['province']*=1;
		$_POST['city']*=1;
		$_POST['area']*=1;
		
		$sql="UPDATE ".MEMBER." SET		name='$_POST[name]',qq='$_POST[qq]',provinceid='$_POST[province]',cityid='$_POST[city]',areaid='$_POST[area]',area='$_POST[t]',sex='$_POST[sex]',msn='$_POST[msn]',skype='$_POST[skype]',logo='$_POST[logo]'
		WHERE userid='$uid'";
		
		$re=$this->db->query($sql);
		return $re;
	}
	
	
	function resetpass($buid)
	{
		$sql="SELECT password FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if(empty($_POST["oldpass"]) || empty($_POST["newpass"]) || empty($_POST["renewpass"]))
		{
			return '0';	die;
		}
		if($re['password']!=md5($_POST["oldpass"]))
		{
			return '1';	die;
		}
		if($_POST["newpass"]!=$_POST["renewpass"])
		{
			return '2';	die;
		}
		$sql="UPDATE ".MEMBER." SET password='".md5($_POST['newpass'])."' WHERE userid='$buid'";
		$re=$this->db->query($sql);
		return '3';
		
	}
	function resetemail($buid)
	{
		$sql="SELECT password FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if(empty($_POST["pass"]) || empty($_POST["mail"]))
		{
			return '0';	die;
		}
		if($re['password']!=md5($_POST["pass"]))
		{
			return '1';	die;
		}
		$sql="UPDATE ".MEMBER." SET email='".$_POST['mail']."' WHERE userid='$buid'";
		$re=$this->db->query($sql);
		return '2';
	}
	
	function verifymail($buid)
	{
		if(strtolower($_POST['yzm'])==strtolower($_SESSION['auth']))
		{
			$pass=md5($_POST["password"]);
			$sql="SELECT userid FROM ".MEMBER." WHERE userid='$buid' and password='$pass'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			if(!$re)
			{
				return '2';die;
			}
		}
		else
		{
			return '1';die;
		}
	}
	function editmail($buid)
	{
		if(strtolower($_POST['yzm'])==strtolower($_SESSION['auth']))
		{
			$sql="select userid from ".MEMBER." where email='$_POST[email]' or user='$_POST[email]'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			if($re)
			{
				return '2';die;
			}
			else
			{
				$sql="UPDATE ".MEMBER." SET email='".$_POST['email']."' WHERE userid='$buid'";
				$re=$this->db->query($sql);
			}
		}
		else
		{
			return '1';die;
		}
	}
	
	function get_points()
	{
		global $buid;
		$sql="select points from ".MEMBERCOUNT." WHERE member_id='$buid'";
		$this->db->query($sql);
		$points=$this->db->fetchField('points');
		return $points;
	}
	
	function is_qd($uid='')
	{
		global $buid;
		$buid=$uid?$uid:$buid;
		$min= strtotime(date("Y-m-d"));   
		$max= $min+24*60*60;      
		$sql="select * from ".POINTSLOG." WHERE create_time>='$min' and create_time<'$max 'and type='4' and member_id='$buid'";
		$this->db->query($sql);
		return 	$this->db->num_rows();
	}
	
	function add_points($sum,$type,$order_id,$uid='')
	{
		global $buid;
		$sum=$sum?round($sum):0;
		$num=$sum<0?$sum*(-1):$sum;
		$points=$this->get_points();
		if($num>$points and $sum<0)
		{
			return '1';
		}
		$buid=$uid?$uid:$buid;
		$statu=1;
		$sql="UPDATE ".MEMBERCOUNT." SET `points` = points + $sum  WHERE `member_id` = '$buid'";
		$this -> db->query($sql);	
		if($type==5)
		{
			$desc="注册会员赠送";	
		}
		elseif($type==2)
		{
			$desc="兑换礼品".$order_id."消耗积分";	
		}
		elseif($type==3)
		{
			$desc="取消购物订单".$order_id;	
		}
		elseif($type==4)
		{
			$desc="每日签到";	
		}
		else
		{
			$desc="订单".$order_id."购物消费";	
		}
		
		$sql="select user from ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$user=$this->db->fetchField('user');

		$sql = "INSERT INTO ".POINTSLOG." (member_id,member_name,points,type,create_time,`desc`) VALUES ('$buid','$user','$sum','$type','".time()."','$desc')"; 
		$this -> db->query($sql);
	}
}
?>
