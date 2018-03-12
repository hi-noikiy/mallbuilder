<?php

class orderadder
{
	var $db;
	function orderadder()
	{
		global $db;	
		$this -> db     = & $db;
	}
	function get_orderadder($id=NULL)
	{
		global $buid;	
		if(empty($id)||!is_numeric($id))
			return false;
		else
		{
			$sql="select * from ".DELIVERYADDR." where id=$id";
			$this -> db->query($sql);
			$re=$this -> db->fetchRow();
					  
			return $re;
		}		
	}
	function get_default_orderadder()
	{
		global $buid;	
		$sql="select * from ".DELIVERYADDR." where  userid='$buid' and `default`='2'";
		$this -> db->query($sql);
		$re=$this -> db->fetchRow();
		return $re;
	}
	function get_orderadderlist()
	{
		global $buid;	
		$sql="select * from ".DELIVERYADDR." where userid=$buid";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		return $re;
	}
	
	function get_consignee()
	{
		global $buid;	
		$sql="select * from ".DELIVERYADDR." where userid=$buid limit 0,1 ";
		$this -> db->query($sql);
		$re=$this -> db->fetchRow();	
		return $re;
	}
	
	function add_orderadder()
	{			  	
		global $buid;
		$default=$_POST['default']?"2":"1";
		$_POST['zip']=$_POST['zip']?$_POST['zip']:NULL;
		if($default=='2')
		{
			$sql="UPDATE ".DELIVERYADDR." SET `default` = '1' WHERE  `userid` ='$buid' ";
			$this -> db->query($sql);	
		}
		$sql="insert into ".DELIVERYADDR."(`userid` ,`name` ,`provinceid` ,`cityid` ,`areaid` ,`area` 
		,`address` ,`zip` ,`tel` ,`mobile`,`default`)values($buid,'$_POST[name]','$_POST[province]','$_POST[city]','$_POST[area]','$_POST[t]','$_POST[address]','$_POST[zip]','$_POST[tel]','$_POST[mobile]','$default')";
		$this -> db->query($sql);	
		$id=$this -> db->lastid(); 
		return $id;
	}
	
	function edit_orderadder($id=NULL)
	{
		global $buid;
		if($id)
		{
			$default=$_POST['default']?"2":"1";
			$_POST['zip']=$_POST['zip']?$_POST['zip']:NULL;
			if($default=='2')
			{
				$sql="UPDATE ".DELIVERYADDR." SET `default` = '1' WHERE  `userid` ='$buid' ";
				$this -> db->query($sql);	
			}
			$sql="UPDATE ".DELIVERYADDR." SET `name` = '$_POST[name]',`provinceid` = '$_POST[province]',`cityid` = '$_POST[city]', `areaid` = '$_POST[area]', `area` = '$_POST[t]', `address` = '$_POST[address]',`zip` = '$_POST[zip]',`tel` = '$_POST[tel]',`mobile` = '$_POST[mobile]',`default` = '$default' WHERE  `id` ='$id' ";
			$this -> db->query($sql);	
			return $id;
		}
	}
	function del_orderadder($id=NULL)
	{
		global $buid;	
		if(is_numeric($id))
		{
			$tel=$_POST['tel1'].'-'.$_POST['tel2'].'-'.$_POST['tel3'];
			$sql="delete from ".DELIVERYADDR."  where id=$id ";
			$flag=$this -> db->query($sql);		 
			return $flag;
		}
	}
}
?>
