// JavaScript Document

/**
 * 判断是否是空
 * @param value
 */
function isEmpty(value){
	if(value == null || value == "" || value == "undefined" || value == undefined || value == "null"){
		return true;
	}
	else{
		value = value.replace(/\s/g,"");
		if(value == ""){
			return true;
		}
		return false;
	}
}

/**
 * 判断是否是数字
 */
function isNumber(value){
	if(isNaN(value)){
		return false;
	}
	else{
		return true;
	}
}

/**
 * 只包含中文和英文
 * @param cs
 * @returns {Boolean}
 */
function isGbOrEn(value){
    var regu = "^[a-zA-Z\u4e00-\u9fa5]+$";
    var re = new RegExp(regu);
    if (value.search(re) != -1){
      return true;
    } else {
      return false;
    }
}



/**
 * 检查手机号码
 * @param mobile
 * @returns {Boolean}
 */
function check_mobile(mobile){
  var regu = /^\d{11}$/;
  var re = new RegExp(regu);
  if(!re.test(mobile)){
	 return  false;
  }
  return true;
}

//正则
function trimTxt(txt){
 return txt.replace(/(^\s*)|(\s*$)/g, "");
}
/**
 * 检查是否含有非法字符
 * @param temp_str
 * @returns {Boolean}
 */
function is_forbid(temp_str){
    temp_str=trimTxt(temp_str);
	temp_str = temp_str.replace('*',"@");
	temp_str = temp_str.replace('--',"@");
	temp_str = temp_str.replace('/',"@");
	temp_str = temp_str.replace('+',"@");
	temp_str = temp_str.replace('\'',"@");
	temp_str = temp_str.replace('\\',"@");
	temp_str = temp_str.replace('$',"@");
	temp_str = temp_str.replace('^',"@");
	temp_str = temp_str.replace('.',"@");
	temp_str = temp_str.replace(';',"@");
	temp_str = temp_str.replace('<',"@");
	temp_str = temp_str.replace('>',"@");
	temp_str = temp_str.replace('"',"@");
	temp_str = temp_str.replace('=',"@");
	temp_str = temp_str.replace('{',"@");
	temp_str = temp_str.replace('}',"@");
	var forbid_str=new String('@,%,~,&');
	var forbid_array=new Array();
	forbid_array=forbid_str.split(',');
	for(i=0;i<forbid_array.length;i++){
		if(temp_str.search(new RegExp(forbid_array[i])) != -1)
		return false;
	}
	return true;
}

/**
 * 打开蒙版
 * 
 * @param id
 */
function openExpose(id) {
	$.jExpose($(id), {
		exposeClass: "step-current",
		onLoad: function(){
			$("order_buttons").jSticky("destory");
		},
		onClose: function(){
			$("#order_buttons").jSticky("refresh");
		}
	});
}

/**
 * 关闭蒙版
 * 
 * @param id
 */
function closeExpose(id) {
	$.jExpose.close();
}

/**
 * 设置高亮选中
 * 
 * @param step
 */
function step_Openlight(step) {
	if (step == 'consignee') {
		$("#step-1").addClass("step-current");
		openExpose("#step-1");
	} 
}

/**
 * 关闭高亮选中
 * 
 * @param step
 */
function set_CloseLight(step) {
	if (step == 'consignee') {
		$("#step-1").removeClass("step-current");
		closeExpose("#step-1");
	}
}

function open_Module(name) {

	$("#"+name+"_edit_action a").hide();
	step_Openlight(name);
	
	var url = "?m=product&s=confirm_order";
	var pars = 'act='+name;
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{		
		if(originalRequest)
		{
			$("#"+name).html(originalRequest);
		}
	}
}

/**
 * 选择常用收货人地址
 * 
 * @param id
 */
function chose_consignee(id) {
	$("#consignee_form").hide();
	$("#use_new_address").removeClass("item-selected");
	$("#consignee_radio_" + id).attr("checked", "checked");
	var parentDiv = $("#consignee_radio_" + id).parent();
	parentDiv.addClass("item-selected").siblings().removeClass("item-selected");
	$("#hidden_consignee_id").val(id);
}

/**
 * 使用新收获人地址
 */
function NewConsignee() {
	$("#consignee_form").show();
	
	$("#name").val("");
	$("#id_1").val("");
	$("#id_2").val("");
	$("#id_3").val("");
	$("#t").val("");
	$("#select_1").val("");
	$("#select_2").val("");
	$("#select_3").val("");
	$("#address").val("");
	$("#tel").val("");
	$("#mobile").val("");
	$("#email").val("");
	
	removeConsingeeMessage();
	
	$("#consignee_radio_new").attr("checked", "checked");
	$("#use_new_address").attr("class", "item item-selected");
	var consigneeList = $(".consignee_list");
	consigneeList.find(".item").each(function() {
		$(this).attr("class", "item");
	});
}

/**
 * 删除收货人验证提示信息
 */
function removeConsingeeMessage() {
	$("#name_error").html("");
	$("#area_error").html("");
	$("#address_error").html("");
	$("#call_error").html("");
	$("#email_error").html("");
}

/**
 * 验证收货地址消息提示
 * 
 * @param divId
 * @param value
 */
function check_Consignee(divId) {
	var errorFlag = false;
	var errorMessage = null;
	var value = null;
	
	// 验证收货人名称
	if (divId == "name") {
		value = $("#name").val();
		if (isEmpty(value)) {
			errorFlag = true;
			errorMessage = "请您填写收货人姓名";
		}
		if (value.length > 25) {
			errorFlag = true;
			errorMessage = "收货人姓名不能大于25位";
		}
		if (!is_forbid(value)) {
			errorFlag = true;
			errorMessage = "收货人姓名中含有非法字符";
		}
	}
	// 验证邮箱格式
	else if (divId == "email") {
		value = $("#email").val();
		if (!isEmpty(value)) {
			if (!check_email(value)) {
				errorFlag = true;
				errorMessage = "邮箱格式不正确";
			}
		} else {
			if (value.length > 50) {
				errorFlag = true;
				errorMessage = "邮箱长度不能大于50位";
			}
		}
	}
	// 验证地区是否完整
	else if (divId == "area") {
		var provinceId = $("#select_1").find("option:selected").val();
		var cityId = $("#select_2").find("option:selected").val();
		var townId = $("#select_3").find("option:selected").val();

		// 验证地区是否正确
		if (isEmpty(provinceId) || isEmpty(cityId) || isEmpty(townId))
		{
			errorFlag = true;
			errorMessage = "请您填写完整的地区信息";
		}
	}
	// 验证收货人地址
	else if (divId == "address") {
		value = $("#address").val();
		if (isEmpty(value)) {
			errorFlag = true;
			errorMessage = "请您填写收货人详细地址";
		}
		if (!is_forbid(value)) {
			errorFlag = true;
			errorMessage = "收货人详细地址中含有非法字符";
		}
		if (value.length>50) {
			errorFlag = true;
			errorMessage = "收货人详细地址过长";
		}
	}
	// 验证手机号码
	else if (divId == "mobile") {
		value = $("#mobile").val();
		divId = "call";
		if (isEmpty(value)) {
			errorFlag = true;
			errorMessage = "请您填写收货人手机号码";
		} else {
			if (!check_mobile(value)) {
				errorFlag = true;
				errorMessage = "手机号码格式不正确";
			}
		}
		if (!errorFlag) {
			value = $("#tel").val();
			if (!isEmpty(value)) {
				if (!isNumber(value) || value.length > 20) {
					errorFlag = true;
					errorMessage = "固定电话号码格式不正确";
				}
			}
		}
	}
	// 验证电话号码
	else if (divId == "tel") {
		value = $("#tel").val();
		divId = "call";
		if (!isEmpty(value)) {
			if (!isNumber(value) || value.length > 20) {
				errorFlag = true;
				errorMessage = "固定电话号码格式不正确";
			}
		}
		if (true) {
			value = $("#mobile").val();
			if (isEmpty(value)) {
				errorFlag = true;
				errorMessage = "请您填写收货人手机号码";
			} else {
				if (!check_mobile(value)) {
					errorFlag = true;
					errorMessage = "手机号码格式不正确";
				}
			}
		}
	}
	if (errorFlag) {
		$("#" + divId + "_error").html(errorMessage);
		return false;
	} else {
		$("#" + divId + "_error").html("");
	}
	return true;
}

/**
 * 保存收货地址（包含保存常用人收货地址，根据id区分）
 */
function save_consignee() {

	var isHidden = $("#consignee_form").is(":hidden");// 是否隐藏
	var id = $("input[name='consignee_radio']:checked").val();// 获取收货人id
	
	if(id==undefined){
		alert("请选择收货人地址!");
		return;
	}
	if (!isHidden) {
		var checkConsignee = true;

		// 验证收货人信息是否正确
		if (!check_Consignee("name")) {
			checkConsignee = false;
		}
		// 验证地区是否正确
		if (!check_Consignee("area")) {
			checkConsignee = false;
		}
		// 验证收货人地址是否正确
		if (!check_Consignee("address")) {
			checkConsignee = false;
		}
		// 验证手机号码是否正确
		if (!check_Consignee("mobile")) {
			checkConsignee = false;
		}
		// 验证电话是否正确
		if (!check_Consignee("tel")) {
			checkConsignee = false;
		}
		if (!checkConsignee) {
			return;
		}
		name=$("#name").val();
		mobile=$("#mobile").val();
		tel=$("#tel").val();
		t=$("#t").val();
		province=$("#id_1").val();
		city=$("#id_2").val();
		area=$("#id_3").val();
		address=$("#address").val();
		zip=$("#zip").val();
	}	
	var url = "?m=product&s=confirm_order";
	if (!isHidden) {
		var pars = 'name='+name+'&mobile='+mobile+'&tel='+tel+'&t='+t+'&address='+address+'&province='+province+'&city='+city+'&area='+area+'&zip='+zip+'&act=add_consignee';
	}
	else{
		var pars = 'id='+id+'&act=select_consignee';
	}
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		if(originalRequest)
		{
			var tempStr = 'var MyMe = ' + originalRequest;
			eval(tempStr);
			html=MyMe['two'];
			if (!isHidden) {
				id=MyMe['id'];
				$("#hidden_consignee_id").val(id);
			}
			else
			{
				id=MyMe['one']['id'];
				name=MyMe['one']['name'];
				mobile=MyMe['one']['mobile'];
				tel=MyMe['one']['tel'];
				t=MyMe['one']['t'];
				address=MyMe['one']['address'];
				$("#hidden_consignee_id").html(id);
			}
			$("#consignee_edit_action a").show();
			str="<p>"+name+"&nbsp;&nbsp;"+mobile+"&nbsp;&nbsp;"+tel+"</p><p>"+t+"&nbsp;"+address+"</p>";
			$("#consignee").html(str);
			$('.order_product').children('tbody').html(html);
			count_all_price();
			set_CloseLight("consignee");
		}
	}
}
