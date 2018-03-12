var face_array = new Array();
face_array[1] = 
[	
 	['默认表情'],
	[
		['微笑','weixiao.gif'],
		['撇嘴','pizui.gif'],
		['色','se.gif'],
		['发呆','fadai.gif'],
		['得意','deyi.gif'],
		['流泪','liulei.gif'],
		['害羞','haixiu.gif'],
		['闭嘴','bizui.gif'],
		['睡觉','shuijiao.gif'],
		['大哭','daku.gif'],
		['尴尬','gangga.gif'],
		['大怒','danu.gif'],
		['调皮','tiaopi.gif'],
		['呲牙','ciya.gif'],
		['惊讶','jingya.gif'],
		['难过','nanguo.gif'],
		['酷','ku.gif'],
		['冷汗','lenghan.gif'],
		['抓狂','zhuakuang.gif'],
		['吐','tu.gif'],
		['偷笑','touxiao.gif'],
		['可爱','keai.gif'],
		['白眼','baiyan.gif'],
		['傲慢','aoman.gif'],
		['饥饿','er.gif'],
		['困','kun.gif'],
		['惊恐','jingkong.gif'],
		['流汗','liuhan.gif'],
		['憨笑','haha.gif'],
		['大兵','dabing.gif'],
		['奋斗','fendou.gif'],
		['咒骂','ma.gif'],
		['疑问','wen.gif'],
		['嘘','xu.gif'],
		['晕','yun.gif'],
		['折磨','zhemo.gif'],
		['衰','shuai.gif'],
		['骷髅','kulou.gif'],
		['敲打','da.gif'],
		['再见','zaijian.gif'],
		['擦汗','cahan.gif'],
		['抠鼻','wabi.gif'],
		['鼓掌','guzhang.gif'],
		['糗大了','qioudale.gif'],
		['坏笑','huaixiao.gif'],
		['左哼哼','zuohengheng.gif'],
		['右哼哼','youhengheng.gif'],
		['哈欠','haqian.gif'],
		['鄙视','bishi.gif'],
		['委屈','weiqu.gif'],
		['哭了','ku.gif'],
		['快哭了','kuaikule.gif'],
		['阴险','yinxian.gif'],
		['亲亲','qinqin.gif'],
		['亲吻','kiss.gif'],
		['吓','xia.gif'],
		['可怜','kelian.gif'],
		['敬礼','jingli.gif'],
		['扮鬼脸','banguilian.gif'],
		['潜水','qianshui.gif'],
	],
	[	
	 	['爆筋','baojin.gif'],
		['菜刀','caidao.gif'],
		['西瓜','xigua.gif'],
		['啤酒','pijiu.gif'],
		['篮球','lanqiu.gif'],
		['乒乓','pingpang.gif'],
		['咖啡','kafei.gif'],
		['饭','fan.gif'],
		['猪头','zhutou.gif'],
		['花','hua.gif'],
		['凋谢','diaoxie.gif'],
		['爱心','love.gif'],
		['心碎','xinsui.gif'],
		['蛋糕','dangao.gif'],
		['闪电','shandian.gif'],
		['炸弹','zhadan.gif'],
		['刀','dao.gif'],
		['足球','qiu.gif'],	
		['虫','chong.gif'],
		['便便','dabian.gif'],
		['月亮','yueliang.gif'],
		['太阳','taiyang.gif'],
		['礼物','liwu.gif'],
		['拥抱','yongbao.gif'],
		['强','qiang.gif'],
		['弱','ruo.gif'],
		['握手','woshou.gif'],
		['胜利','shengli.gif'],
		['佩服','peifu.gif'],
		['勾引','gouyin.gif'],
		['拳头','quantou.gif'],
		['差劲','chajin.gif'],
		['NO','no.gif'],
		['OK','ok.gif'],
		['飞吻','feiwen.gif'],
		['跳','tiao.gif'],
		['发抖','fadou.gif'],
		['怄火','dajiao.gif'],
		['转圈','zhuanquan.gif'],
		['磕头','ketou.gif'],
		['回头','huitou.gif'],
		['跳绳','tiaosheng.gif'],
		['挥手','huishou.gif'],
		['激动','jidong.gif'],
		['街舞','tiaowu.gif'],
		['献吻','xianwen.gif'],
		['左太极','zuotaiji.gif'],
		['右太极','youtaiji.gif']
	]
];
face_array[2] = 
[	
 	['生活表情'],
	[
		['下雨','xiayu.gif'],
		['浮云','fuyun.gif'],
		['灯泡','dengpao.gif'],
		['祝福','zhufu.gif'],
		['给力','geili.gif'],
		['喝彩','hecai.gif'], 
		['闹钟','nzh.gif'] 
	]
];
var STATICURL = 'image/face/';
$("#insertFace").live('click',function(){
	if(!$(".faceWrap")[0]){
		var div = document.createElement("div");
		div.className = "faceWrap";
		if(!face_array) return;
		title=sel="";
		for(i in face_array) {
			 sel=i==1?'class="selected"':'';
			 title+='<li onclick="face_switch(\'mc\', 12, \'' + i + '\', 1);" '+sel+'>'+face_array[i][0]+'</li>';
		}
		div.innerHTML ='<div class="sign"><em>◆</em><span>◆</span></div><div class="mt"><ul>'+title+'</ul><a title="关闭" class="close" href="#">X</a></div><div class="mc"></div>';
		$('.weibo_button').append(div);
		face_switch("mc", 12, 1 ,1);
	}
	else
	{
		if($('.faceWrap').css("display")=='none'){
			$(".faceWrap").show();
		}
		else{
			$(".faceWrap").hide();
		}
	}
})
$(".faceWrap .close").live('click',function(){
	$(".faceWrap").hide();
});
$(".faceWrap .mt li").live('click',function(){
	$(this).addClass("selected").siblings().removeClass("selected");
});

$(".faceWrap td img").live('click',function(){
	insertsmilie(this,$('#content'));
});
function insertsmilie(id,obj){
	var code = $(id).attr('title');
	code=code?"/"+code:"";
	$(obj).insertAtCaret(code);
	$(obj).focus();
	$('.faceWrap').hide();
}
function face_switch(id, count, num ,page) {
	num = num ? num : 1;
	page = page ? page : 1;
	if(!face_array || !face_array[num][page]) return;
	facedata ='<table cellpadding="0" cellspacing="0"><tr>';
	j = k = 0;
	for(i in face_array[num][page]) {
		if(j >= count) {
			facedata += '<tr>';
			j = 0;
		}
		var s = face_array[num][page][i];
		width=s[2]?s[2]:"24";
		height=s[3]?s[3]:"24";
		smilieimg = STATICURL + s[1];
		facedata += s && s[0] ? '<td><img  width="' + width + '" height="' + height + '" src="' + smilieimg + '" alt="' + s[0] + '" title="'+s[0]+'" />' : '</td>';
		j++; k++;
	}
	facedata += '</table>';
	facepage = '';
	if(face_array[num].length > 2) {
		prevpage = ((prevpage = parseInt(page) - 1) < 1) ? face_array[num].length  - 1 : prevpage;
		nextpage = ((nextpage = parseInt(page) + 1) == face_array[num].length) ? 1 : nextpage;
		facepage += '<div class="page"><a href="javascript:void(0)" onclick="face_switch(\'' + id + '\', \'' + count + '\', \'' + num + '\', ' + prevpage + ');">上页</a>' 
		facepage +='<a href="javascript:void(0)" onclick="face_switch(\'' + id + '\', \'' + count + '\', \'' + num + '\', ' + nextpage + ' );">下页</a></div>';
	} 
	$('.'+ id).html(facedata+facepage);
}

