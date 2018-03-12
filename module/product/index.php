<?php
/***
 *Powered by b2bbuilder
 *Copyright http://www.b2b-builder.com
 *Auther:Brad zhang
 *Date:2008-11-7
 *Des:产品类别页面，已做缓存处理
 */
//=============================================
if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');
//------------------------------
if(!empty($config['index_catid']))
	$cat_pro=unserialize($config['index_catid']);
else
	$cat_pro=array();
if($cat_pro)
{
	foreach($cat_pro as $key=>$v)
	{
		//--------类别名--------------
		$sql="select brand,cat,catid,pic from ".PCAT." where catid='$v[catid]'";
		$db->query($sql);
		$cata=$db->fetchRow();
		$cat_pro[$key]['name'] = $v['name']?$v['name']:$cata['cat'];
		$cat_pro[$key]['pic'] = $cata['pic'];
			//--------类别下面的子分类------
		$s=$v['catid']."00";
		$b=$v['catid']."99";
		$limit=$config['temp']!='wap'?"16":"7";
		
		$sql="select cat,catid from ".PCAT." where catid>$s and catid<$b order by nums asc,char_index asc limit 0,$limit";
		$db->query($sql);
		$cat_pro[$key]['sub_cat']=$db->getRows();
		
		if($v['tab'])
		{
			$array=implode(',',$v['tab']);
			$sql="select cat,catid from ".PCAT." where catid in ($array) order by nums asc,char_index asc limit 0,6";
			$db->query($sql);
			$cat_pro[$key]['scat']=$db->getRows();
		}
		//------------------------------
		if($config['temp']!='wap')
		{
			//--------类别下面的品牌-------
			if(!empty($cata['brand']))
			{
				$brand_id=$cata['brand'];
				$sql="select id,name,logo from ".BRAND." where 1 and id in($brand_id) limit 0,10";
				$db->query($sql);
				$cat_pro[$key]['brand']=$db->getRows();
			}
		}
		else
		{
			$count=count($cat_pro[$key]['sub_cat'])-1;
			$cat_pro[$key]['rand'][]=rand(0,$count);
			$cat_pro[$key]['rand'][]=rand(0,$count);
		}
		
	}
	$tpl->assign("categorys",$cat_pro);
}
if($config['temp']!='wap')
{
	//------团购推荐----
	$sql="select * from ".TG." where status=2 order by displayorder limit 0,1";
	$db->query($sql);
	$re = $db->getRows(); 
	$tpl->assign("tj",$re);
}
//----------------------------
include_once("config/connect_config.php");//connect
$config = array_merge($config,$connect_config);
if($config['sina_connect']==1)//sina
{
	define( "WB_AKEY" , $config['sina_app_id'] );
	define( "WB_SKEY" , $config['sina_key'] );
	define( "WB_CALLBACK_URL" , "$config[weburl]/login.php?type=sina" );
	include_once( 'includes/saetv2.ex.class.php' );
	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
	$tpl->assign("sina_login_url",$code_url);
}
//------------------------------
$tpl->assign("cat_pro",$cat_pro);
$tpl->assign("current","index");
include_once("footer.php");
//=============================================
$out=tplfetch("product_index.htm",NULL);
?>