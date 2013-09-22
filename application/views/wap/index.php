<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>问卷列表</title>
<meta name="keywords" content="" />
<meta name="description" content="" /> 
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL;?>wap_style.css"  media="screen"/>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>xui-2.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/wap_common.js"></script>
<script>
	var scrolli=0;
	var ajax_str='';
	function lists(pageno){
		x$('#loging').removeClass('hidden');
		var idz=document.getElementById("is_zd").value;
		var q_title=document.getElementById("q_title").value;
		if(q_title) q_title=q_title.replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "");
		if(q_title=='搜索问卷') q_title='';
		if(pageno>0){
		var str=do_ajax('<?php echo SITE_ROOT; ?>/Wap/list/?zd='+idz+'&v=<?php echo time();?>','&q_title='+q_title+'&pageno='+pageno,'listdata');
		}
		eval("x$('#show"+pageno+"').html('');");
		//x$('#list1').html(str);
	}
	function listdata(str){
		x$('#list1').html(x$('#list1').html()+str);
		x$('#loging').addClass('hidden');
		x$('#loadingbox').addClass('hidden');
		hiloading();
	}
	function seldata(str){
		x$('#centext').html(str);
		hiloading();
	}
	
window.onscroll=function(){	
var a = document.documentElement.scrollTop==0? document.body.clientHeight : document.documentElement.clientHeight;	
var b = document.documentElement.scrollTop==0? document.body.scrollTop : document.documentElement.scrollTop;	
var c = document.documentElement.scrollTop==0? document.body.scrollHeight : document.documentElement.scrollHeight;	
if(c-b<700){ if(scrolli>0) {lists(scrolli);}
			 scrolli=0;}
}
function showloading(){ 
	x$('#bodyfull').removeClass('hide');
}
function hiloading(){ 
	x$('#bodyfull').addClass('hide');
}
</script>

<style type="text/css">
.fullbg{
background:url(../../images/aimg/loading.gif) center center #969d9f no-repeat;
z-index:3;
position:fixed;
left:0px;
top:0px;
width:100%;
height:100%;
filter:Alpha(Opacity=30);
/* IE */
-moz-opacity:0.4;
/* Moz + FF */
opacity: 0.4;
}
.hide{
	 display: none;			  
}
</style>
</head>
<body><div class='fullbg hide' id='bodyfull'></div>
<div class="full_bg2">
	<div class="logo_bg"><a href="<?php echo SITE_ROOT; ?>Index/logout/" title="后退" class="logo_top1" ></a> <a href="#" class="logo_top2" onfocus="this.blur()" onclick="Table();"><span id="dati">未回答</span></a></div>
	<div class="search">
		<form class="form_1 clearfix" name="form_1" method="post" action="<?php echo SITE_ROOT; ?>/Wap/">
			<a class="button_2" title="搜索" onClick="form_1.submit()">搜索</a>
			<input type="text" class="text_2" name="q_title" id="q_title" value="<?php echo $post['q_title'];?>" onfocus="if(this.value=='搜索问卷'){this.value='';}else{}" onblur="if(this.value==''){this.value='搜索问卷';}" />
			<a href="#" class="xiaochu" onClick="document.getElementById('q_title').value=''"></a>
             <input type="hidden" name="is_zd" id="is_zd" value="<?php echo ($post['is_zd'])?$post['is_zd']:0;?>" />
		</form>
	</div>
    
	<!--cion_1-->
    <div id="centext">
    	<div class="lodw_box" id='loadingbox'>页面奋力加载中......</div>
		<div id="list1"></div>
     </div>   
	<!--/cion_1--> 
	
	<!--/cion_1-->
	<div id="loging" class="hidden"><div class="loding_1"><span class="span_1">加载中......</span></div></div>
</div>
<script type="text/javascript">
lists(1);
if(document.getElementById("is_zd").value==1) document.getElementById("dati").innerHTML=("已回答");
 function Table(){
	 showloading();
	if ( document.getElementById("dati").innerHTML==("未回答")){
		document.getElementById("dati").innerHTML=("已回答");
		document.getElementById("is_zd").value=1;
		var str=do_ajax('<?php echo SITE_ROOT; ?>/Wap/sel/?v=<?php echo time();?>','&zd=1&pageno=1','seldata');
	}
	else {
		document.getElementById("dati").innerHTML=("未回答");
		document.getElementById("is_zd").value=0;
		var str=do_ajax('<?php echo SITE_ROOT; ?>/Wap/sel/?v=<?php echo time();?>','&zd=0&pageno=1','seldata');
	}
}


</script>
</body>
</html>