<?php
class sns
{
	var $db;
	var $tpl;
	var $page;
	function sns()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	function add_sns($type='')
	{
		global $buid,$config;
		
		$sql="select user from ".MEMBER."  WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		
		$content_str = '';
		
		$original_id = '0';
		$original_member_id = '0';
		
		$member_id = $buid;
		$member_name = $re['user'];
	
		$create_time  = time();
		$t  = '1';
		$img = '';
		$url = '';
		$status  = '0';
		$privacy = 0;
		$comment_count  = 0;
		$copy_count  = 0;
	
		if($type=='shareshop')
		{
			$sql="select userid,logo,company from ".SHOP." WHERE userid='$_POST[choosestoreid]'";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
			$img = $de['logo'];
			$url = $config['weburl']."/shop.php?uid=".$de['userid'];
			$title = $_POST['comment']?$_POST['comment']:"这家店【".$de['company']."】不错，分享给你！希望你也喜欢哦~";
			$t  = '3';
			
		}
		elseif($type=='sharegoods')
		{
			$sql="select id as pid,pic_more,pname from ".PRO."  WHERE id='$_POST[choosestoreid]'";
			$this->db->query($sql);
			$de=$this->db->fetchRow();

			$img = $de['pic_more'];
			$url = $config['weburl']."/?m=product&s=detail&id=".$de['pid'];
			$title = $_POST['comment']?$_POST['comment']:"这个宝贝真不错我很喜欢【".$de['pname']."】";
			$this->db->query("update ".SPRO." set isshare='1' where pid='".$_POST['choosestoreid']."'");	
			$t  = '2';
		}
		elseif($type=='forward')
		{
			$sql="select content,member_id,member_name,title,original_id,original_member_id from ".SNS." WHERE id='$_POST[forwardid]'";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
			$original_id = $de['original_id']?$de['original_id']:$_POST['forwardid'];
			$original_member_id = $de['original_member_id']?$de['original_member_id']:$de['member_id'];
			$title = $_POST['forwardcontent']?$_POST['forwardcontent']:"";
			$this->db->query("update ".SNS." set copy_count=copy_count+1 where id='".$_POST['forwardid']."'");
		}
		else
		{
			$title = $_POST['content'];
		}
		if($img&&$t==2)
		{
			$pic=explode(',',$val['img']);
			foreach($pic as $p)
			{
				$pic=str_replace($config['weburl'],$config['webroot'],$pic);
				if(file_exists($pic))
				{
					$path=$config['webroot'].'/uploadfile/sns/'.$buid.'/'; 
					if(!file_exists($path))
					{
						mkdirs($path);
					}
					$time=time();
					copy($pic,$path.$time.".jpg");
					
					if(file_exists($pic."_120X120.jpg"))
					{
						copy($pic."_120X120.jpg",$path.$time.".jpg_120X120.jpg");
					}
					else
					{
						makethumb($pic,$path.$time.".jpg_120X120.jpg","120","120",false);
					}
					$pic=$path.$time.".jpg";
					$pic.=str_replace($config['webroot'],$config['weburl'],$pic);
				}
			}
		}
		$title=htmlspecialchars($title);
		$title.=$url?" <a target=\'_blank\' href=\'$url\'>$config[weburl]</a>":"";
		$sql="insert into ".SNS." (original_id,original_member_id,original_status,member_id,member_name,title,content,img,type,create_time,status,privacy,comment_count,copy_count) VALUES ('$original_id','$original_member_id','0','$member_id','$member_name','$title','$content','$img','$t','$create_time','$status ','$privacy','$comment_count','$copy_count')";
		$this->db->query($sql);
		
		$this->db->query("update ".MEMBERCOUNT." set blog=blog+1 where member_id='$member_id'");	
		die;
	}
	function add_sns_comment()
	{
		global $buid,$config;
		
		$sql="select logo,user from ".MEMBER."  WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		
		$member_id = $buid;
		$member_name = $re['user'];
		$original_id = $_POST['commentid'];
		$create_time  = time();
		$content = $_POST['commentcontent']?$_POST['commentcontent']:"";
		if($content)
		{
			$sql="insert into ".SNSCOMMENT." (original_id,member_id,member_name,content,addtime) VALUES ('$original_id','$member_id','$member_name','$content','$create_time')";
			$this->db->query($sql);
			$this->db->query("update ".SNS." set comment_count=comment_count+1 where id='$original_id'");
		}
	}

	function del_sns($id)
	{
		global $buid;
		$this->db->query("update ".MEMBERCOUNT." set blog=blog-1 where member_id='$buid'");
		$this->db->query("delete from ".SNS." where id='$id'");
		$this->db->query("delete from ".SNSCOMMENT." where original_id='$id'");
		$this->db->query("update ".SNS." set original_status=1 where original_id='$id'");
	}
	
	
	function del_sns_comment($id)
	{
		$this->db->query("delete from ".SNSCOMMENT." where id='$id'");
		$this->db->query("update ".SNS." set comment_count=comment_count-1 where id='$id'");
	}
	
	function get_sns()
	{
		global $buid,$config;
		
		$sql="select fuid from ".FRIEND." where uid=$buid order by addtime desc";
		$this->db->query($sql);
		$re=$this->db->getRows();
		
		$myfriend=$buid;
		
		foreach($re as $val)
		{
			$myfriend.=','.$val['fuid'];
		}
		
		$sql="select a.* , b.member_id as ouid , b.member_name as ouser , b.title as otitle, b.create_time as ocreate_time, b.content as ocontent,b.img as oimg,b.type as otype from ".SNS." a left join ".SNS." b on a.original_id= b.id where a.member_id in ($myfriend) order by a.create_time desc";
		$this->db->query($sql);
		$re=$this->db->getRows();
		if(!$re)
		{
			$sql="select a.* , b.member_id as ouid , b.member_name as ouser , b.title as otitle, b.create_time as ocreate_time, b.content as ocontent,b.img as oimg,b.type as otype from ".SNS." a left join ".SNS." b on a.original_id= b.id order by rand() , a.create_time desc";
		}
		$str="";
		include_once($config['webroot']."/includes/page_utf_class.php");
		include_once($config['webroot']."/module/sns/face.php");
	
		$page = new Page;
		$page->listRows=10;
		
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
		$p=$_GET['page']-1>0?$_GET['page']-1:"0";
		$page->firstRow=$p*$page->listRows;
        $sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$this->db->query($sql);
		$re=$this->db->getRows();
		
		foreach($face_array as $key=>$val)
		{
			$searcharray[] ="/\/".$key."/";
			$replacearray[] = "<img align='absmiddle' src='image/face/".$val."'>";
		}
		
		foreach($re as $val)
		{
			$comment=$con=$del=$a=$img="";
			$sql="select logo,name from ".MEMBER." WHERE userid='$val[member_id]'";
			$this->db->query($sql);
			$a=$this->db->fetchRow();

			$val['member_img'] = $a['logo']?$a['logo']:"image/default/user_admin/default_user_portrait.gif";
			$val['member_name']=$a['name']?$a['name']:$val['member_name'];
			
			$sql="select * from ".SNSCOMMENT."  WHERE original_id='$val[id]' order by id desc";
			$this->db->query($sql);
			$ss=$this->db->getRows();
			$val['title']=preg_replace($searcharray,$replacearray,$val['title']);
			if($ss)
			{
				$comment="<div class='commnet'>";
				foreach($ss as $list)
				{
					$comment.="<div class='commnet_list'><dl><dt><a target=\"_blank\" href=\"home.php?uid=".$list['member_id']."\">$list[member_name]</a> $list[content]</dt><dd>".date('Y-m-d',$list['addtime'])."</dd></div>";
				}
				$comment.="</div>";
			}
			if($val['original_id'])
			{
				$con="<div class=\"quote-wrap\">";
				if($val['original_status']==1)
				{
					$con.="原文已删除";
				}
				else
				{
					$val['otitle']=preg_replace($searcharray,$replacearray,$val['otitle']);
					if($val['oimg'])
					{
						$oimg="<div class=\"sns-img clearfix\"><ul>";
						$opic=explode(',',$val['oimg']);
						foreach($opic as $op)
						{
							if($val['otype']=='2')
							{
								$oimg.="<li><img class=\"small\" src=\"".$op."_120X120.jpg\"></li>";	
							}
							else
							{
								$oimg.="<li><img src=\"".$op."\"></li>";
							}
						}
						$oimg.="</ul></div>";
					}
					$con.="<p><a target=\"_blank\" href=\"home.php?uid=".$val['ouid']."\">".$val['ouser']."</a></p><div class=\"sns-text\"><div class=\"sns-title\"><span>".$val['otitle']."</span>".$oimg."</div></div><div class=\"sns-extra\"><a class=\"sns-time\" title=\"".date('Y年m月d日 H:i',$val['ocreate_time'])."\" href=\"#\">".date('m月d日 H:i',$val['ocreate_time'])."</a></div>";
					
					
				}
				$con.="</div>";
			}
			$fd_forward="<span><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" genre=\"sns_forward\" href=\"javascript:void(0);\">转发</a></span><span><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" genre=\"sns_comment\" href=\"javascript:void(0);\">评论</a></span>";
			$del="";
			if($val['member_id']==$buid)
			{
				$del="<div class=\"more-action\"><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" data_type=\"fd_del\" href=\"javascript:void(0);\"></a></div>";
			}
			if($val['img'])
			{
				$img="<div class=\"sns-img clearfix\"><ul>";
				$pic=explode(',',$val['img']);
				foreach($pic as $p)
				{
					if($val['type']=='2')
					{
						$img.="<li><img class=\"small\" src=\"".$p."_120X120.jpg\"></li>";	
					}
					else
					{
						$img.="<li><img src=\"".$p."\"></li>";
					}
				}
				$img.="</ul></div>";
			}
			$str.="<div class=\"sns-item\"><div class=\"sns-avatar\"><a target=\"_blank\" href=\"shop.php?uid=".$val['member_id']."\"><img width=\"60\" height=\"60\" src=\"".$val['member_img']."\" ></a></div>".$del."<div class=\"sns-wrap\"><p class=\"clearfix\"><a target=\"_blank\" href=\"home.php?uid=".$val['member_id']."\"><b>".$val['member_name']."</b></a></p><div class=\"sns-text\"><div class=\"sns-title\">".$val['title']."</div>".$img."</div>".$con."<div class=\"sns-extra\"><a class=\"sns-time\" title=\"".date('Y年m月d日 H:i',$val['create_time'])."\" href=\"#\">".date('m月d日 H:i',$val['create_time'])."</a><span class=\"sns-action\">".$fd_forward."</span></div></div><div class='clear'></div>".$comment."</div>";
		}
		if(($_GET['page']+1)<= ceil($page->totalRows/$page->listRows))
		{
			$str.="<div id=more></div>";
		}
		return $str;
	}
	
	
	function get_sns_list()
	{
		global $buid,$config;
		
		$str="";
		if($_GET['key'])
		{
			$sql=" and title like '%$_GET[key]%' ";	
		}
		$sql="select * from ".SNS." where type!=1 and original_id=0 $sql order by create_time desc";
		include_once($config['webroot']."/includes/page_utf_class.php");
		include_once($config['webroot']."/module/sns/face.php");
		$page = new Page;
		$page->listRows=30;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
		$p=$_GET['page']-1>0?$_GET['page']-1:"0";
		$page->firstRow=$p*$page->listRows;
        $sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$this->db->query($sql);
		$re=$this->db->getRows();
		foreach($face_array as $key=>$val)
		{
			$searcharray[] ="/\/".$key."/";
			$replacearray[] = "<img align='absmiddle' src='image/face/".$val."'>";
		}
		foreach($re as $val)
		{
			$comment=$con=$del=$a=$img="";
			
			$sql="select logo,name from ".MEMBER."  WHERE userid='$val[member_id]'";
			$this->db->query($sql);
			$a=$this->db->fetchRow();

			$val['member_img'] = $a['logo']?$a['logo']:"image/default/user_admin/default_user_portrait.gif";
			$val['member_name']=$a['name']?$a['name']:$val['member_name'];
			if($val['img'])
			{
				$img="<div class=\"blog_pic clearfix\">";
				$pic=explode(',',$val['img']);
				foreach($pic as $p)
				{
					$img.="<center><img src=\"".$p."\"></center>";
				}
				$img.="</div>";
			}
			$val['title']=preg_replace($searcharray,$replacearray,$val['title']);
			$str.="<div class=\"blog_item\">".$img."<div class=\"blog_info\"><p>".$val["title"]."</p></div><div class=\"blog_user clearfix\"><div class=\"fl\"><a target=\"_blank\" href=\"home.php?uid=".$val["member_id"]."\"><img width=\"35\" height=\"35\" src=\"".$val["member_img"]."\"</a></div><div class=\"fr\"><p><a target=\"_blank\" href=\"home.php?uid=".$val["member_id"]."\">".$val["member_name"]."</a><a class=\"attention\" data-param=\"{&quot;uid&quot;:&quot;".$val["member_id"]."&quot;}\"  href=\"javascript:void(0);\">关注</a></p><p><a data-param=\"{&quot;bid&quot;:&quot;".$val["id"]."&quot;}\" genre=\"sns_forward\" href=\"javascript:void(0);\">转播</a><span>|</span><a data-param=\"{&quot;bid&quot;:&quot;".$val["id"]."&quot;}\" genre=\"sns_comment\" href=\"javascript:void(0);\">评论</a></p></div></div></div>";
		}
		return $str;
	}
}
?>
