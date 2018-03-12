<?php

	if(empty($_GET['m'])||empty($_GET['s'])||empty($_GET['id'])||empty($_GET['uid']))
		die('forbiden;');

	
	$sql="select a.rebate,a.buyer_id,a.name,a.pic,b.userid,b.user,b.id,b.price from ".ORPRO." a left join ".PRO." b on a.pid=b.id where order_id='$_GET[id]'";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("pro",$re);
	
	
	if($_POST['submit']=="submit")
	{
		$sql="select user,userid from ".MEMBER." where userid='$_GET[uid]' ";
		$db->query($sql);
		$de=$db->fetchRow();
		foreach($re as $k=>$v)
		{
			if($v['userid'])
			{
				$db->query("update ".MEMBER." set sellerpoints=sellerpoints+". $_POST["g".$k]." where userid='".$v['userid']."'");	
		
				$sql="select id from ".PRO." where id='$v[id]' ";
				$db->query($sql);
				$pro=$db->fetchRow();
				if($pro['id'])
				{
					$db->query("update ".PRO." set goodbad=goodbad+". $_POST["g".$k]." where id='".$v['id']."'");	
				}
				$sql="INSERT ".PCOMMENT."(userid,user,fromid,pid,puid,pname,price,con,uptime,goodbad) VALUES ('$de[userid]','$de[user]','$v[userid]','$v[id]','$v[userid]','$v[name]','$v[price]','".$_POST["comment_text".$k]."','".time()."','". $_POST["g".$k]."')";
				$db->query($sql);
			
				if($v['rebate']>0)
				{
					//--------------写入流水账。
					include("$config[webroot]/module/payment/lang/$config[language].php");
					$post['type']=1;//直接到账
					$post['action']='add';//
					$post['buyer_email']='0';//
					$post['seller_email']=$de['userid'];//
					$post['order_id']='R'.time().$k;//外部订单号
					$post['price']=$v['rebate'];//订单总价，单价元
					$post['mold']='5';
					$post['s1']='订单'.$_GET['id'].'商品'.$v['name'].'返利收入';
					$post['s']='订单'.$_GET['id'].'商品'.$v['name'].'返利支出';
					pay_get_url($post,true);//跳转至订单生成页面
				}

			}
		
		}
		if($_POST['snuma'] and $_POST['snumb'] and $_POST['snumc'] and $_POST['snumd'] )
		{
			$sql="INSERT ".UCOMMENT."(userid,user,byid,item1,item2,item3,item4,uptime) VALUES ('$de[userid]','$de[user]','".$re[0]['userid']."','$_POST[snuma]','$_POST[snumb]','$_POST[snumc]','$_POST[snumd]','".time()."')";
			$db->query($sql);
		}
		$db->query("update ".ORDER." set buyer_comment=1 where order_id='".$_GET['id']."'");	
		
		msg("main.php?m=product&s=admin_buyorder");
	}
	$tpl->assign("current","product");
	include_once("footer.php");
	//=============================================
	$out=tplfetch("comment.htm",NULL);
	
?>