// JavaScript Document
var tips_user="<i></i>5-25个字符，一个汉字为两个字符，推荐使用中文会员名。";
var tips_password="<i></i>6-16个字符，请使用字母加数字或符号的组合密码。";
var tips_re_password="<i></i>再输一次密码";
var tips_yzm="";
var tips_ckyzwt="";
var tips_email="<i></i>请输入您常用的电子邮箱，以方便日后找回密码。";
var error="<i></i>不能为空";
var right="<i></i>";
var error_user="<i></i>该会员名已被使用";
var error_user1="<i></i>5-25个字符";
var error_user2="<i></i>包含非法字符";
var error_user3="<i></i>会员名首位和末位不能是下划线'_'";
var error_password1="<i></i>长度应为6-16个字符";
var error_password2="<i></i>不能全为字母";
var error_password3="<i></i>不能全为数字";;
var error_re_password="<i></i>两次密码输入不一致";
var error_yzm="<i></i>验证码错误";
var error_ckyzwt="<i></i>验证问题错误";
var error_email="<i></i>该邮箱已注册，请更换其他邮箱";
var error_email1="<i></i>格式错误";
var flag;
var flag1;
var flag2;
$(function(){
	$("form").find(".text").each(function(){
		$(this).focus(function(){ 
			var val=$(this).val();
			var name=$(this).attr("name");
			var div=$(this).parent().next().children("div");
			if(name)
			{
				if(!val){
					div.attr('class','tips').html(eval('tips_'+name));
				}
			}
		});	
		$(this).blur(function(){ 
			var name=$(this).attr("name");
			eval("check_"+name+"($(this))");
		});
	});
	$(".submit").click(function(){
		var arr_flag = new Array();
		$("form").find(".text").each(function(){
			var name=$(this).attr("name");
			if(name)
			{
				arr_flag.push(eval("check_"+name+"($(this))"));
			}
		});
		if(arr_flag.in_array(false)){
			return false;	
		}
		else{
			$("form").submit();		
		}
	});
	$("form .read em").click(function(){
		if($(".agreement").css("display")=='block')
			$(".agreement").hide();
		else
			$(".agreement").show();
	});
});
function check_email(obj)
{
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var patrn = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(!patrn.test(val)){  
		obj.addClass('red');
		div.attr('class','error').html(error_email1);return false;
	}
	else{
		var url = 'ajax_back_end.php';
		var sj = new Date();
		var pars = 'shuiji=' + sj+'&check_email=1&email='+val; 
		$.post(url, pars, function (originalRequest)
		{
			if(originalRequest=="true"){
				obj.removeClass('red');
				div.attr('class','true').html(right);
				flag=true;
			}
			else{				
				obj.addClass('red');
				div.attr('class','error').html(error_email);
				flag=false;
			}
			return flag;
		});
		return flag;
	}
}
function check_user(obj)
{
	var val=obj.val();
	var div=obj.parent().next().children("div");
	
	var patrn = new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5]+$");
	var patrn1 = new RegExp("^(?!_)(?!.*?_$)");
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(getByteLen(val) < 5 || getByteLen(val) > 25){
		obj.addClass('red');
		div.attr('class','error').html(error_user1);return false;
	}
	else if(!patrn.test(val)){  
		obj.addClass('red');
		div.attr('class','error').html(error_user2);return false;
	}
	else if(!patrn1.test(val)){  
		obj.addClass('red');
		div.attr('class','error').html(error_user3);return false;
	}
	else{
		var url = 'ajax_back_end.php';
		var sj = new Date();
		var pars = 'shuiji=' + sj+'&user='+val; 

		var ajax=$.get(url, pars,function (originalRequest){
		
			if(originalRequest!="true"){
				obj.addClass('red');
				div.attr('class','error').html(error_user);
				flag=false;
			}
			else{
				obj.removeClass('red');
				div.attr('class','true').html(right+"一旦注册成功不能修改");
				flag=true;
			}
			return flag;
		});
		return flag;
	}
}
function check_password(obj){
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var patrn1 = new RegExp("^[A-Za-z]+$");
	var patrn2 = new RegExp("^[0-9]*$");
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(getByteLen(val) < 6 || getByteLen(val) > 16){
		obj.addClass('red');
		div.attr('class','error').html(error_password1);return false;
	}
	else if(patrn1.test(val)){  
		obj.addClass('red');
		div.attr('class','error').html(error_password2);return false;
	}
	else if(patrn2.test(val)){  
		obj.addClass('red');
		div.attr('class','error').html(error_password3);return false;
	}
	else{
		obj.removeClass('red');
		div.attr('class','true').html(right);return true;
	}
}
function check_re_password(obj){
	var val=obj.val();
	var div=obj.parent().next().children("div");
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(val!=$("#password").val()){
		obj.addClass('red');
		div.attr('class','error').html(error_re_password);return false;
	}
	else{
		obj.removeClass('red');
		div.attr('class','true').html(right);return true;
	}
}
function check_yzm(obj){
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&yzm='+val; 
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else 
	{
		$.get(url, pars, function(originalRequest){
			if(originalRequest>0)
			{	
				obj.addClass('red');
				div.attr('class','error').html(error_yzm);
				flag1=false;
			}
			else
			{
				obj.removeClass('red');
				div.attr('class','true').html(right);
				flag1=true;
			}
			return flag1;
		});
		return flag1;
	}
}
function check_ckyzwt(obj)
{
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&ckyzwt='+$('#ckyzwt').val(); 
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else 
	{
		$.post(url, pars,function (originalRequest)
		{
			if(originalRequest=='true')
			{	
				obj.removeClass('red');
				div.attr('class','true').html(right);
				flag2=true;
			}
			else
			{
				obj.addClass('red');
				obj.val('');
				div.attr('class','error').html(error_ckyzwt);
				flag2=false;
			}
			return flag2;
		});
		return flag2;
	}
}
function getByteLen(val){
	var len = 0;
	for (var i = 0; i < val.length; i++){
		var a = val.charAt(i);
		if (a.match(/[^\x00-\xff]/ig) != null){
			len += 2;
		}
		else{
			len += 1;
		}
	}
	return len;
}
function show_yzwt()
{
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&wtyz=1'; 
	$.post(url, pars,function (originalRequest){
		if(originalRequest)
			$('#yzwt').html(originalRequest);
	});
}
Array.prototype.in_array = function(e)
{
	
    for(i=0;i<this.length;i++)
    {
		if(this[i] == e)
		{
			return true;
		}
    }
    return false;
}