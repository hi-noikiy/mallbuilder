<?php

	include_once("config.php");
	
	if($_POST["act"]=='save')
	{
		$wmark_type='0';
		foreach($_POST as $pname=>$pvalue)
		{
			if ($pname!="act")
			{
				
				$sql="select * from ".CONFIG." where `index`='$pname'";
				$db->query($sql);
				if($db->num_rows())
				{
				   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname'";
				}
				else
				{
				   $sql1="insert into ".CONFIG." (`index`,value) values ('$pname','$pvalue')";
				}
				$db->query($sql1);
				$configs[$pname]=$pvalue;
			}
		}
		$name="wechat_config";
		/****更新缓存文件*********/
		$write_config_con_str=serialize($configs);//将数组序列化后生成字符串
		$write_config_con_str='<?php $'.$name.' = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
		$fp=fopen('../config/'.$name.'.php','w');
		fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
		fclose($fp);
		/*********************/
		admin_msg("wechat_config.php",'更新成功');
	}
	@include_once("../config/wechat_config.php");
	$tpl->assign("wechat_config",$wechat_config);
	$tpl->display("wechat_config.htm");
?>