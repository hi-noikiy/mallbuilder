<?php 
include_once("includes/global.php");

@include_once("config/remote_config.php");
@include_once("config/image_config.php");

$path="";
if($remote_config['image_remote_storage']==1 and !empty($remote_config['space_name']) and !empty($remote_config['ftp_password']) and !empty($remote_config['ftp_name']))
{
	if(!empty($remote_config['remote_directory']))
	{
		$path='/'.$remote_config['remote_directory']."/";
	}
}
else
{
	$path=$config['webroot'].'/uploadfile/'; 
}
$m=$_GET['m']?$_GET['m']:"";
if(!empty($m))
{	
	$path.=$m.'/';	
	if($m=='member' or $m=='shop' or $m=='product' or $m=='product/property' or $m=='album')
	{
		$b2bbuilder_auth=bgetcookie("USERID");
		$buid=$b2bbuilder_auth['0'];
		if($buid)
			$path.=$buid.'/';	
	}
	}
else
{
	$path.='all/';
}
$ist=$image_config['image_storage_type']?$image_config['image_storage_type']:"1";
switch ($ist){
	case "1":
	{	
		$path.=date('Y').'/'.date('m').'/'.date('d').'/';
		break;
	}
	case "2":
	{	
		$path.=date('Y').'/'.date('m').'/';
		break;
	}
	case "3":
	{	
		$path.=date('Y').'/';
		break;
	}
	default:
	{
		break;
	}
}
$size=array('60','120','220');
//==============================================

if($_SESSION['USER_LANG'])
	$config['language']=$_SESSION['USER_LANG'];
	
if(is_uploaded_file($_FILES['pic']['tmp_name']))
{
	$file_name = $_FILES['pic']['name'];
	$file_size = $_FILES['pic']['size'];
	$max_size = is_numeric($image_config['image_size'])?$image_config['image_size']*1024:1024*1024;
	
	$ext_arr = explode(',',$image_config['image_extension']);
	if ($file_size > $max_size) {
		echo "<script>alert('上传文件大小超过限制。');window.parent.close_win();</script>";die;
	}
	
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);

	//检查扩展名
	if (in_array($file_ext, $ext_arr) === false) {
		echo "<script>alert('上传文件扩展名是不允许的扩展名。');window.parent.close_win();</script>";die;
	}
	
	if($remote_config['image_remote_storage']==1 and !empty($remote_config['space_name']) and !empty($remote_config['ftp_password']) and !empty($remote_config['ftp_name']))
	{
		
		require_once('lib/php-sdk-master/upyun.class.php');
		
		$upyun = new UpYun("$remote_config[space_name]","$remote_config[ftp_name]","$remote_config[ftp_password]");
		try
		{
			$fh = fopen($_FILES['pic']['tmp_name'], 'rb');
			$pn=time().".jpg";
			$rsp = $upyun->writeFile($path.$pn, $fh, True);   // 上传图片，自动创建目录
			fclose($fh);
			if($_GET['m']=='product')
			{	
				$fh = fopen($_FILES['pic']['tmp_name'], 'rb');
				foreach($size as $key=>$val)
				{
					$opts.$key = array(
						UpYun::X_GMKERL_TYPE    => 'square', // 缩略图类型
						UpYun::X_GMKERL_VALUE   => $val, // 缩略图大小
						UpYun::X_GMKERL_QUALITY => 95, // 缩略图压缩质量
						UpYun::X_GMKERL_UNSHARP => True // 是否进行锐化处理
					);
					$rsp = $upyun->writeFile($path.$pn."_".$val."X".$val.".jpg", $fh, True, $opts.$key);   // 上传图片，自动创建目录
				}
				fclose($fh);
				$pn=$remote_config['remote_access_url'].$path.$pn;
				echo "<script>window.parent.load_pic('".$pn."');</script>";
			}
			else
			{
				$pn=$remote_config['remote_access_url'].$path.$pn;
				$str="window.parent.document.getElementById('$_GET[obj]').value='$pn';";
				$str.="if(window.parent.document.getElementById('$_GET[obj]_img')){window.parent.document.getElementById('$_GET[obj]_img').src='$pn';}";
				echo "<script>$str;window.parent.close_win();</script>";
			}
		}
		catch(Exception $e) {
			echo $e->getCode();
			echo $e->getMessage();
		}
	}
	else
	{
		
		if(!empty($_GET['watermark']))
			$watermark=false;
		else
			$watermark=true;
			
		$pn=time().".jpg";
		$pw=$_POST['pw'];
		$ph=$_POST['ph'];
		
		if(!file_exists($path))
		{
			mkdirs($path);
		}
		
		if($_GET['m']=='product')
		{
			foreach($size as $key=>$val)
			{
				makethumb($_FILES['pic']['tmp_name'], $path.$pn."_".$val."X".$val.".jpg",$val,$val,false);
			}
			$info = @getimagesize($_FILES['pic']['tmp_name']);
			makethumb($_FILES['pic']['tmp_name'], $path.$pn,$info[0],$info[1]);
			$str="window.parent.load_pic();";
		}
		else
		{
			makethumb($_FILES['pic']['tmp_name'],$path.$pn,$pw,$ph,$watermark);
		}
		
		$pn=str_replace($config['webroot'],$config['weburl'],$path).$pn;
		
		$str.="window.parent.document.getElementById('$_GET[obj]').value='$pn';";
		$str.="if(window.parent.document.getElementById('$_GET[obj]_img')){window.parent.document.getElementById('$_GET[obj]_img').src='$pn';}";
		echo "<script>$str;window.parent.close_win();</script>";
	}
	die;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>图片上传</title>
</head>
<style>
*{font-family:Arial, Helvetica, sans-serif;}
td{font-size:12px; padding:3px;}
#preview{width:380px; height:260px; overflow:scroll; text-align:center;}
#preview img{border:1px solid #CCCCCC}
</style>
<body>
<?php if(empty($_GET['pv'])){ ?>
<form action="" method="post" enctype="multipart/form-data">
<?php if($config['language']=='cn'){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input name="pic" type="file" id="pic" /><br /><font style="color:#666666">(支持格式:Jpeg,Jpg Gif,Png 小于1MB)</font></td>
  </tr>
  <?php 
		if($_GET['m']!='product'){ ?>
  <tr>
    <td>
	  宽度<input name="pw" type="text" id="pw" value="<?php echo $_GET['pw'];?>" size="3" />px 
	  高度<input name="ph" type="text" id="ph" value="<?php echo $_GET['ph'];?>" size="3" />px
	  </td>
  </tr>
  <?php } ?>
  <tr>
    <td>
      <input type="submit" name="Submit" value="提交" />
      <input type="reset" onclick="window.parent.close_win();" name="Submit2" value="取消" />
    </td>
  </tr>
</table>
<?php } else{ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input name="pic" type="file" id="pic" /><br /><font style="color:#666666">(Supported formats: Jpeg, Jpg, Gif, or Png Size: Max 1MB)</font></td>
  </tr>
  <?php if(empty($_GET['re_pic'])){ ?>
  <tr>
    <td>
	  Width<input name="pw" type="text" id="pw" value="<?php echo $_GET['pw'];?>" size="3" />px 
	  Height<input name="ph" type="text" id="ph" value="<?php echo $_GET['ph'];?>" size="3" />px
	  </td>
  </tr>
  <?php } ?>
  <tr>
    <td>
      <input type="submit" name="Submit" value="Submit" />
      <input type="reset" onclick="window.parent.close_win();" name="Submit2" value="Cancel" />
    </td>
  </tr>
</table>
<?php } ?>
</form>
<?php
}
else
{
?>
<div id="preview"></div>
<script>
str=window.parent.document.getElementById('<?php echo $_GET['obj'];?>').value;
if(str=='')
	str='图片地址为空，无法预览';
else
	str='<img src='+str+'>';
document.getElementById('preview').innerHTML=str;
</script>
<?php } ?>
</body>
</html>
