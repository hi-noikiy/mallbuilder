<?php
	if($_POST["act"]=="submit")
	{
			$sql="select * from ".TGMEMBER." where user='$_POST[user]'";
			$db->query($sql);
			$re=$db->fetchRow();
			if($re["id"])
			{
				if($re["password"]==md5($_POST['password']))
				{
					setcookie("TGUSER",$re['id'],NULL,"/",$config['baseurl']);
					msg('?m=tg&s=login');
				}
				else
				{
					msg('?m=tg&s=login','密码错误');
				}
			}
			else
				msg('?m=tg&s=login','用户不存在');
		
	}
	if($_COOKIE['TGUSER']&&$_GET['code'])
	{
		$sql="select order_id,code from ".TGORDER." where company_id='$_COOKIE[TGUSER]' and code='$_GET[code]'";
		$db->query($sql);
		$re=$db->fetchRow();
		$tpl->assign("re",$re);
	}
	
	include_once("footer.php");
	$tpl->assign("current","tg");	
	$page=$_COOKIE['TGUSER']?"tg_select.htm":"tg_login.htm";
	$out=tplfetch($page,$flag,true);
?>