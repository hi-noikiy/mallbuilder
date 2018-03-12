<?php
class friend
{
	var $db;
	function friend()
	{
		global $db;	
		$this -> db     = & $db;
	}
	
	//添加 好友 
	function AddFriend()
	{			  	
		global $buid;
		$user=GetMember($buid);
		$friend=GetMember($_GET['id']);
		$sql="insert into ".FRIEND." (uid,uname,uimg,fuid,funame,fuimg,addtime,state) values ($buid,'$user[user]','$user[logo]','$friend[userid]','$friend[user]','$friend[logo]','".time()."','0')";
		$this -> db->query($sql);	
		$id=$this -> db->lastid(); 
		return $id;
	}
	
	function GetMember($uid)
	{			 
		$sql="select userid,user,logo from ".MEMBER." where userid=$uid ";
		$this -> db->query($sql);
		$re=$this -> db->fetchRow();	
		return $re;
	}
	
	function GetMembers()
	{
		$sql="select userid as fuid, name as funame,logo as fuimg,lastLoginTime as addtime from ".MEMBER." where name != '' order by lastLoginTime desc limit 0,10";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	
	function GetFriendList()
	{
		global $config,$buid;	
		
		if($_GET['type']=="fan")
		{
			$str=" and fuid=$buid";
		}
		else
		{
			$str=" and uid=$buid";
		}
		$sql="select id,uid,uname,uimg,fuid,funame,fuimg,state,area,buyerpoints,sex  from ".FRIEND." a left join ".MEMBER." b on a.fuid = b.userid where 1 $str order by addtime desc";
		
		include_once($config['webroot']."/includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=10;
		
		if (!$page->__get('totalRows')){
			$this->db->query("select count(id) as num from ".FRIEND." where uid=$buid ");
			$num=$this->db->fetchRow();
			$page->totalRows = $num['num'];
		}
		
        $sql .= "  limit ".$page->firstRow.",10";
		$this->db->query($sql);
		$re["list"]=$this->db->getRows();
		
		$sql="select * from ".POINTS." order by id";
		$this ->db ->query($sql);
		$de=$this ->db->getRows();
		foreach($re["list"] as $key=>$val)
		{
			foreach($de as $k=>$v)
			{
				$ar=explode('|',$v['points']);
				if($val['buyerpoints']<=$ar[1] and $val['buyerpoints']>=$ar[0])
				{
					$re["list"][$key]["buyerpointsimg"]=$v['img'];
				}
			}
		}
		$re["page"]=$page->prompt();
		return $re;
	}
}

?>