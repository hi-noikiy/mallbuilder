<?php

	$sql="select * from ".POINTSCAT." where parent_id=0 order by displayorder ,id";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$sql="select * from ".POINTSCAT." where parent_id='$v[id]'";
		$db->query($sql);
		$de[$k]['scat']=$db->getRows();	
	}
	$tpl->assign("cat",$de);
	
	
	include_once("$config[webroot]/includes/page_utf_class.php");
	if($_GET['id'] and is_numeric($_GET['id']))
	{
		$sql="select id,catname,parent_id from ".POINTSCAT." where id='$_GET[id]'";
		$db->query($sql);
		$aa=$db->fetchRow();
		$id=$aa['parent_id']?$aa['parent_id']:$_GET['id'];
		$sql="select id,catname from ".POINTSCAT." where parent_id='$id'";
		$db->query($sql);
		$cats=$db->getRows();
		if(!$aa['parent_id'])
		{	
			if($cats)
			{
				foreach($cats as $a)
				{
					$b[]=$a['id'];
				}
				$c=','.implode(',',$b);
			}
		}
		$ss=" and catid in ($_GET[id]$c)";	
	}
	else
	{
		$sql="select id,catname from ".POINTSCAT." where parent_id='0'";
		$db->query($sql);
		$cats=$db->getRows();	
	}
	if($_GET['order']=='1')
	{
		$o="order by points,id desc";
	}
	elseif($_GET['order']=='2')
	{
		$o="order by id desc";
	}
	else
	{
		$o="order by sell_amount desc,id desc";
	}
	$sql="select id,pic,name,points from ".POINTSGOODS." where status>0 $ss $o";
	//=============================
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$pages = $page->prompt();
	//=====================
	$db->query($sql);
	$pl['list']=$db->getRows();
	$pl['page']=$page->prompt();
	
	$sql="select id,pic,name,points from ".POINTSGOODS." where status=2 order by id desc limit 0,5";
	$db->query($sql);
	$re=$db->getRows();	

	$tpl->assign("re",$re);
	$tpl->assign("de",$pl);
	$tpl->assign("cats",$cats);
	$tpl->assign("catname",$aa['catname']?$aa['catname']:"È«²¿");
	
	include_once("footer.php");
	$tpl->assign("current","points");
	$out=tplfetch("points_list.htm",$flag,true);
?>