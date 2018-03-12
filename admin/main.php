<?php
include_once("config.php");
include_once("menu_config.php");
if(!is_file($config['webroot']."/image/phpqrcode.jpg")) 
{	
	include "lib/phpqrcode/phpqrcode.php";
	$value=$config['weburl'];
	$errorCorrectionLevel = 'L';
	$matrixPointSize = 5;
	QRcode::png($value,$config['webroot']."/image/phpqrcode.jpg", $errorCorrectionLevel, $matrixPointSize,'1');
}
//====================================
if(empty($_SESSION["ADMIN_USER"]))
	msg("index.php");

function list_sub($sv,$sub=NULL)
{
	global $perm,$lang,$bnums,$j;
	$u_v=explode(",",$sv);
	if($u_v[1]==1&&(in_array(md5($u_v[0]),$perm)||$_SESSION["ADMIN_TYPE"]=='1'))
	{
		$str.='<li>';
		if(!empty($u_v[2]))
			$str.='<a href="module.php?m='.$u_v[2].'&s='.$u_v[0].'"  hidefocus="true">';
		else
			$str.="<a href='$u_v[0]' hidefocus='true' >";
		
		$sar=explode('?',$u_v[0]);
		$u_v[0]=$sar[0];
		$scrp_name=substr($u_v[0],0,-4);
		if(!empty($u_v[3]))
			$str.=$u_v[3];
		else
			$str.=$lang[$scrp_name];
		$str.='<em> </em></a></li>';
	}
	return $str;
}
if(empty($perm)) $perm=array();
foreach($mem as $key=>$v)
{
	if(in_array(md5($key),$perm)|| $_SESSION["ADMIN_TYPE"]=='1')
	{
		$arr=@explode(",",$v[1][0][1][0]);
		if($arr[2])
		{ 
			$to_url="module.php?m=$arr[2]&s=$arr[0]";
			if($perm){if(!in_array(md5($arr[0]),$perm)) $to_url="";}
		}
		else	
		{
			$to_url=$arr[0];
			if($perm){if(!in_array(md5($to_url),$perm)) $to_url="";}
		}
	}
	$nav.='<li><a id="header_'.$key.'" hidefocus="true"  onclick="toggleMenu(\''.$key.'\',\''.$to_url.'\'); return false;">'.$v[0].'</a></li>';
}

foreach($mem as $key=>$v)
{
	$con=NULL;
	if($v[1])
	{	
		foreach($v[1] as $skey=>$sv)
		{	
			$left_con=NULL;
			foreach($sv[1] as $sub_sv)
			{
				$left_con.=list_sub($sub_sv);
			}
			if(!empty($sv[0]))
			{
				$con.="<li class='s'><div class='minus'><div onclick='opendiv(this.parentNode)'>$sv[0]</div><ol>$left_con</ol></div></li>";
			}
			else
			{
				$con.=$left_con;
			}
		}
	}
	$item.="<ul id='menu_$key' style='display:none'>$con</ul>";
}
$tpl->assign("rand",rand(10,100));
$tpl->assign("nav",$nav);
$tpl->assign("item",$item);
$tpl->assign("config",$config);
$tpl->display("main.htm");
?>

