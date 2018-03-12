<?php
include_once("config.php");
@include_once("../config/home_config.php");
//==============================================
$type='home';
$reg_config=read_config($type);
if(!$reg_config)
{
	@include('config/'.$type.'_config.php');
	$con=$type.'_config';
	$reg_config=$$con;
}

if($_GET['operation']=='cat')
{
	if($_POST["act"]=='save')
	{
		unset($_POST['act']);
		if($_POST['index_catid'])
		{
			foreach($_POST['index_catid'] as $k=>$v)
			{
				$displayorder=$_POST['displayorder'][$v]?$_POST['displayorder'][$v]:"";
				$name=$_POST['name'][$v]?$_POST['name'][$v]:"";
				$color=$_POST['color'][$v]?$_POST['color'][$v]:"";
				$temp=$_POST['temp'][$v]?$_POST['temp'][$v]:"";
				$tab=$_POST['tab'][$v]?$_POST['tab'][$v]:"";
				
				$new_array[$k]['catid']=$v;
				$new_array[$k]['displayorder']=$displayorder;
				$new_array[$k]['name']=$name;
				$new_array[$k]['color']=$color;
				$new_array[$k]['temp']=$temp;
				$new_array[$k]['tab']=$tab;
			}
		}
		if($new_array) uasort($new_array,"my_sort");
		$sql="select * from ".CONFIG." where `index`='index_catid' and type='$type'";
		$db->query($sql);
		$pvalue = serialize($new_array);
		if($db->num_rows())
		   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='index_catid' and type='$type'";
		else
		   $sql1="insert into ".CONFIG." (`index`,value,type) values ('index_catid','$pvalue','$type')";
		$db->query($sql1);
		write_config($type);
		admin_msg("home_config.php?operation=cat","设置成功");
		exit;
	}
	$index_catid=unserialize($reg_config['index_catid']);
	$sql="select cat,catid from ".PCAT." where catid<9999 order by nums,catid";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$displayorder=$index_catid[$v['catid']]['displayorder'];
		$de[$k]['displayorder']=$displayorder?$displayorder:"255";
		$sql="select cat,catid from ".PCAT." where catid<='$v[catid]99' and catid>='$v[catid]00' order by nums,catid";
		$db->query($sql);
		$de[$k]['con']=$db->getRows();	
	}
	uasort($de,"my_sort");
	$tpl->assign("de",$de);
	$tpl->assign("index_catid",$index_catid);
}
else
{
	if($_POST["act"]=='save')
	{
		unset($_POST['act']);
		foreach($_POST as $pname=>$pvalue)
		{
			$sql="select * from ".CONFIG." where `index`='$pname' and type='$type'";
			$db->query($sql);
			$pvalue = trim(is_array($pvalue)?implode(',',$pvalue):"",',');	
			if($db->num_rows())
			   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='$type'";
			else
			   $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','$type')";
			$db->query($sql1);
		}
		write_config($type);
		admin_msg("home_config.php","设置成功");
		exit;
	}
	if($_GET['key']&&is_numeric($_GET['id']))
	{
		$sql="select value from ".CONFIG." where `index`='$_GET[key]' and type='$type'";
		$db->query($sql);
		$value=$db->fetchField('value');
		if($value)
		{
			$pvalue=trim(str_replace(','.$_GET['id'].',',',',','.$value.','),',');
			$sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$_GET[key]' and type='$type'";
			$db->query($sql1);
			write_config($type);
			msg("home_config.php");
		}
	}
	foreach($reg_config as $key=>$val)
	{
		if($key!='index_catid')
		{
			if($val)
			{
				$sql="SELECT id,pname,pic,price FROM ".PRO." WHERE id in ($val) order by uptime desc";
				$db->query($sql);
				$reg_config[$key."_list"]=$db->getRows();
			}
		}
	}
	$tpl->assign("reg_config",$reg_config);
}
function my_sort($array1, $array2)
{
	$a=$array1['displayorder'];
	$b=$array2['displayorder'];
	$c=$array1['catid'];
	$d=$array2['catid'];
	if ($a == $b){
		return ($c < $d) ? -1 : 1;
	}
	return ($a < $b) ? -1 : 1;
}
function write_config($type)
{
	/****更新缓存文件*********/
	$write_config_con_array=read_config($type);//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	$write_config_con_str='<?php $'.$type.'_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/'.$type.'_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);	
}
//===读库函数，生成config形式的数组====
function read_config($type)
{
	global $db;
	$sql="select * from ".CONFIG." where type='$type'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		$index=$v['index'];
		$value=$v['value'];
		$configs[$index]=$value;
	}
	return $configs;
}
$tpl->assign("config",$config);
$tpl->display("home_config.htm");
?>
