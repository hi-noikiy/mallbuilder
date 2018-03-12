<?php
include_once("includes/global.php");
include_once("includes/admin_global.php");
include_once("includes/admin_class.php");
include_once("includes/insert_function.php");
//===============================================
$action=isset($_GET['action'])?$_GET['action']:"main";
$submit=isset($_POST['submit'])?$_POST['submit']:NULL;
$deid=isset($_GET['deid'])?$_GET['deid']:NULL;
$admin = new admin();
//---------------------清缓存
if(!empty($_POST)||!empty($_GET['deid'])||!empty($_GET['rec']))
	$admin->clear_user_shop_cache();
//---------------------登录检查,个人或企业会员
$admin->is_login($action);
$is_company=$admin->check_myshop();

/*--邮箱验证--*/
include_once("module/member/includes/plugin_member_class.php");
$member = new member();
$flag=$member->email_reg();
if($flag=='false'){
	header("Location:index.php?m=member&s=new_email_reg_two");
}

if(empty($_SESSION['USER_TYPE']))
	$_SESSION['USER_TYPE']=$is_company;
if($_GET['cg_u_type'])
	$_SESSION['USER_TYPE']=$_GET['cg_u_type']*1;
$tpl->assign("cg_u_type",$_SESSION['USER_TYPE']);


include_once("module/shop/includes/plugin_shop_class.php");

//-------店铺信息不存在，但是却进入的是卖家的后台，需要申请开店
if($is_company==1&&$_SESSION['USER_TYPE']==2&&$_GET['s']!='admin_step'&&$_GET['action']!='msg')
	header("Location:main.php?m=shop&s=admin_step");
//--------------------更换语言包
include("lang/cn/user_admin.php");
//-----------------------用户菜单加载

if($_SESSION['USER_TYPE']==1)
	include("lang/cn/admin_menu.inc_p.php");
else
	include("lang/cn/admin_menu.inc.php");

//店铺是否开启
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();	
$shop_statu=$shop->GetShopStatus($buid);

switch ($action){
	case "logout":
	{
		global $config;
		include_once("$config[webroot]/config/reg_config.php");
		$config = array_merge($config,$reg_config);
		bsetcookie("USERID",NULL,time(),"/",$config['baseurl']);
		setcookie("USER",NULL,time(),"/",$config['baseurl']);
		bsetcookie("PMEMBERID",NULL,NULL,"/",$config['baseurl']);
		//=====================
		if($config['openbbs']==2)
		{
			include_once("$config[webroot]/uc_client/client.php");
			echo uc_user_synlogout();
		}
		$_SESSION['USER_TYPE']=NULL;
		header("Location: ".$config['weburl']);
		break;
	}
	case "msg":
	{
		$tpl->assign("lang",$lang);
		$tpl->assign("config",$config);
		include_once("footer.php");
		$output=tplfetch("user_admin/admin_msg.htm",$flag,true);
		break;
	}
	default:
	{
		if(!empty($_GET['m']))
		{
			$s=$_GET['s'];
			if(!$shop_statu and ($s=="admin_product" || $s=="admin_product_list" || $s=="admin_product_storage" || $s=="admin_product_batch"))
			{
				$admin->msg('main.php','shop_statu','failure');die;
			}
			else
			{
				if(file_exists($config['webroot'].'/config/module_'.$_GET['m'].'_config.php'))
				{
					@include($config['webroot'].'/config/module_'.$_GET['m'].'_config.php');
					$mcon='module_'.$_GET['m'].'_config';
					@$config = array_merge($config,$$mcon);
				}
				if(file_exists("$config[webroot]/module/".$_GET['m']."/lang/cn.php"))
				include("$config[webroot]/module/".$_GET['m']."/lang/cn.php");//#调用模块语言包
				
				$tpl->template_dir=$config['webroot']."/templates/".$config['temp']."/user_admin/";
				include("module/".$_GET['m']."/".$_GET['s'].".php");
			}
			break;
		}
		else
		{
			include_once("module/shop/includes/plugin_shop_class.php");
			$shop=new shop();
			//-------------------------------------------
			if($config['temp']=='wap')
			{
				$cominfo=$shop->get_shop_info($buid);
				$admin->tpl->assign("cominfo",$cominfo);
				$count=$shop->get_all_count(ORDER,array('1','2','3','4'));
				$admin->tpl->assign("shop_count",$count);
				$page="user_admin/admin_main_p.htm";
			}
			else
			{
				if($_SESSION['USER_TYPE']==1)
				{	
					$flag=$member->is_qd($buid);
					$tpl->assign("is_qd",$flag);
					
					$cominfo=$shop->get_shop_info($buid);
					$admin->tpl->assign("cominfo",$cominfo);
					
					$tpl->assign("count",$member->get_count($buid));
					$page="user_admin/admin_main_p.htm";
				}
				else
				{
					$cominfo=$shop->get_shop_info($buid);
					$admin->tpl->assign("cominfo",$cominfo);
					
					$time=time();
					$date=array($time-24*60*60,$time-24*60*60*2,$time-24*60*60*3,$time-24*60*60*4,$time-24*60*60*5,$time-24*60*60*6,$time-24*60*60*7);
					$admin->tpl->assign("date",$date);
					$count=$shop->GetViews($date);
					$admin->tpl->assign("count",$count);
					//---------------------------------
					//获取当前用户店铺动态评分
					$admin->tpl->assign("shop_comment",$shop->get_shop_comment());
					//获取当前用户产品 评论 订单 数量
					$count['prdouct']=$shop->get_all_count(PRO,array('-1','-2','1','2'));
					$count['pro_comment']=$shop->get_all_count(PCOMMENT,'');
					$count['order']=$shop->get_all_count(ORDER,array('all','1','2','3','5','4'));
					$admin->tpl->assign("shop_count",$count);
					$page="user_admin/admin_main.htm";
				}
			}
			//------------------------------------------------
			break;
		}
	}
}
$tpl->assign("lang",$lang);
include_once("footer.php");
if(!empty($nohead))
	$tpl->display($page);
else
{
	if(!empty($output))
		$tpl->assign("output",$output);
	else
		$tpl->assign("output",$admin->tpl->fetch($page));
	
	if($config['temp']=='wap')
	{
		$page="admin_inc_p.htm";
	}
	else
	{
		if($_SESSION['USER_TYPE']==1)
		{
			$count['order']=$shop->get_all_count(ORDER,array('1','3','4'),'2');
			$admin->tpl->assign("shop_count",$count);//全局变量
			$page="admin_inc_p.htm";
		}
		else
		{
			$tpl->assign("shop_statu",$shop_statu);//要全局变量，商铺中全部要用
			$page="admin_inc.htm";
		}
	}
	$tpl->template_dir=$config['webroot']."/templates/".$config['temp']."/user_admin/";
	$tpl->display($page);
}
?>
