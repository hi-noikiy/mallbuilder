<?php
class consult
{
	var $db;
	var $tpl;
	var $page;
	
	function consult()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
		if(!empty($_POST['con']))
		{
			include_once($config['webroot'].'/includes/filter_class.php');
			$word=new Text_Filter();
			$_POST['con']=$word->wordsFilter($_POST['con'], $matche_row);
		}
	}
	function get_consult_cat()
	{
		$sql="select * from ".CONSULTCAT." order by id";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		return $re;
	}
	
	function add_question()
	{
		global $buid;
		$con=htmlspecialchars($_POST["con"]);
		$user=get_member_field($buid,'user');
		$sql="insert into ".CONSULT." (`catid`,product_member_id,product_id,product_name,member_id,member_name,question,answer,answer_id,question_time,answer_time,status) values ('$_POST[catid]','$_POST[uid]','$_POST[pid]','$_POST[pname]','$buid','$user','$con','','0','".time()."','0','1')";
		$this -> db->query($sql);
	}
}
?>