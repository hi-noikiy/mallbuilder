<?php
@set_time_limit(0);
include_once("includes/global.php");
//关键字
if(!empty($_POST['search_flag'])&&!empty($_POST['key']))
{	
	include_once("includes/global.php");
	$sql="select * from ".SWORD." where keyword like '$_POST[key]%' or char_index like '$_POST[key]%' order by nums desc limit 0,10";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		echo "<a onclick=\"select_word('$v[keyword]')\" href='#'>$v[keyword]</a>";
	}
	die;
}
if($_POST['qd']==1&&is_numeric($_POST['id']))
{
	$buid=$_POST['id'];
	include_once("module/member/includes/plugin_member_class.php");
	$member = new member();
	$num=$member->is_qd($buid);
	if($num==0)
	{
		$num=rand(1,5);
		$flag=$member->add_points($num,4,'',$buid);
	}
	die;
}
//验证问题是否正确
if(isset($_POST["wtyz"])&&$_POST["wtyz"]=='1')
{	
	include_once("includes/global.php");
	$sql="select * from ".REGVERFCODE." order by rand() limit 0,1";
	$db->query($sql);
	$re=$db->fetchRow();
	echo $re['question'];
	$_SESSION['YZWT']=$re['answer'];
}
//验证问题是否正确
if(isset($_POST["ckyzwt"]))
{	
	if($_POST["ckyzwt"]==$_SESSION['YZWT'])
		echo "true";
	else
		echo "false";
}
//验证码是否正确
if(isset($_GET["yzm"]))
{
	if(strtolower($_GET["yzm"])!=strtolower($_SESSION["auth"]))
	{
		echo 1;
	}
}
if(isset($_POST["yzm"]))
{
	if(strtolower($_POST["yzm"])!=strtolower($_SESSION["auth"]))
		echo "false";
	else
		echo "true";
	die;
}
//邮箱是否重复
if(isset($_POST["email"]))
{	
	include_once("includes/global.php");
	$sql="select * from ".MEMBER." where email='$_POST[email]'";
    $db->query($sql);
    if($db->num_rows())
		echo "false";
	else
		echo "true";
	die;
}

if(isset($_POST["remail"]))
{	
	include_once("config/seo_config.php");
	$config = array_merge($config,$config);
	include("config/nav_menu.php");
	
	$email=$_POST["remail"];
	$rand=create_password("8");
	$_SESSION['RAND']=$rand;
	$time=md5(create_password("5"));
	$link=$config['weburl']."/?m=member&s=new_email_reg_four&u=$time&k=$rand";

	$mail_temp=get_mail_template('active');
	$con=$mail_temp['message'];
	$title=$mail_temp['title'];
	$user=$re['user'];
	$time=date("Y-m-d H:i:s");
	
	$sql="select userid,user from ".MEMBER." where email='$email'";
	$db->query($sql);
	$re=$db->fetchRow();

	$logo='<img height="24" src="'.$config['weburl'].'/image/logo.png"  style="border:none;margin:0;">';
	if($nav_menu)
	{
		foreach($nav_menu as $val)
		{
			if($val['link_addr']=='http')
			{
				$url=$val['link_addr'];
			}
			else
			{
				$url=$config['weburl'].'/'.$val['link_addr'];
			}
			if(!$str)
			$str.="<a target='_blank' style='padding:0 5px;color:#FFF;text-decoration:none;' href='".$url."'>".$val['menu_name']."</a>";
			else
			$str.="<font>|</font><a target='_blank' style='padding:0 5px;color:#FFF;text-decoration:none;' href='".$url."'>".$val['menu_name']."</a>";
		}
	}

	$ar1=array('[menu]','[time]','[logo]','[member_name]','[weburl_name]','[link]','[weburl_email]','[weburl_tel]','[weburl_url]','[weburl_desc]','[weburl_desc]');
	$ar2=array($str,$time,$logo,$user,$config['company'],$link,$config['email'],$config['owntel'],$config['weburl'],$config['description']);
	$con=str_replace($ar1,$ar2,$con);
		
	$ar3=array('[member_name]','[weburl_name]');
	$ar4=array($user,$config['company']);
	$con=str_replace($ar1,$ar2,$con);
	$title=str_replace($ar3,$ar4,$title);
	send_mail($email,$user,$title,$con);
	unset($con);unset($title);
	echo "true";
	die;
}

//会员是否重复
if(isset($_GET['user']))
{
	include_once("includes/global.php");
	include_once("config/reg_config.php");
	$config = array_merge($config,$reg_config);
	$un=trim($_GET['user']);
	$sql="select * from ".RESERVE_USERNAME." where username='$un'"; 
	$db->query($sql);
	if($db->num_rows()>0)
	{
		echo "false";
	}
	else
	{
		$sql="select * from ".MEMBER." where user='$un'";
		$db->query($sql);
		$bbnum=$db->num_rows();
	    if(!empty($config['openbbs'])&&$config['openbbs']==2)
	    {
			include_once('uc_client/client.php');
		    if(uc_user_checkname($un)==1&&!$bbnum)
			    echo "true";//成功
		    else
			    echo "false";//失败
	    }
	    else
	    {
		    if($bbnum>0)
			   echo "false";//失败
		    else
			   echo "true";//成功
	    }
	}
}
//地区联动
if(isset($_POST["d_id"]))
{	
	include_once("includes/global.php");
	$sql="select id,name from ".DISTRICT." where pid='$_POST[d_id]' order by sorting";
	$db->query($sql);
	$num=$db->num_rows();
	$str="";
	if($num!=0)
	{
		$str="{";
		$i=0;
		while($k=$db->fetchRow())
		{
			$i++;
			$id=$k["id"];
			$name=$k["name"];
			if($i<$num)
				$str.="'$i':{'0':'$id','1':'$name'},";
			else
				$str.="'$i':{'0':'$id','1':'$name'}";
		}
		//------------------------------------------------------------
		$str.="};";
	}
	echo $str;
}

//产品商铺分类联动
if(!empty($_POST["pcatid"]))
{
	include_once("includes/global.php");
	
	$s=$_POST["pcatid"]."00";$b=$_POST["pcatid"]."99";
	if(!empty($_POST['cattype'])&&$_POST['cattype']=='pro')
		$db->query("SELECT * FROM ".PCAT." WHERE catid>'$s' and catid<'$b' ORDER BY nums ASC");
	
	if(!empty($_POST['cattype'])&&$_POST['cattype']=='com')
		$db->query("SELECT * FROM ".SHOPCAT." WHERE parent_id='$_POST[pcatid]' ORDER BY displayorder");
	
	$num=$db->num_rows();
	$str="{";
	$i=0;
	while($k=$db->fetchRow())
	{
		$i++;
		if($_POST['cattype']=='com')
		{
			$city_id=$k["id"];
			$cat=str_replace("\r",'',$k['name']);
		}
		else
		{
			$city_id=$k["catid"];
			$cat=str_replace("\r",'',$k['cat']);
		}
		if($i<$num)
			$str.="\"$i\":{\"0\":\"$city_id\",\"1\":\"$cat\"},";
		else
			$str.="\"$i\":{\"0\":\"$city_id\",\"1\":\"$cat\"}";
	}
	$str.="};";
	echo $str;
}

?>