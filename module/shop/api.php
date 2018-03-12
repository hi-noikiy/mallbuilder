<?php

if(!$shop)
include("$config[webroot]/module/shop/includes/plugin_shop_class.php");

class shop_api extends shop
{
	function shop_api()
	{
		parent::shop();
	}
	
	function update_uid($array)
	{	
	}
	
	function delete_by_uid($array)
	{
		$userid=$array['uid'];
		$this->db->query("delete from ".SHOP." where userid='$userid'");
		$this->db->query("delete from ".SSET." where shop_id='$userid'");
		$this->db->query("delete from ".CUSTOM_CAT." where userid='$userid'");
		$this->db->query("delete from ".UDOMIN." where shop_id='$userid'");
		$this->db->query("delete from ".FEED." where userid='$userid'");
		$this->db->query("delete from ".FEEDBACK." where touserid='$userid' or fromuserid='$userid'");
		$this->db->query("delete from ".READREC." where userid='$userid'");
		$this->db->query("delete from ".SHOPEARNEST." where shop_id='$userid'");
	}
}
?>