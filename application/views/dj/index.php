<?php 
$target_name='问卷设计';
include_once(TPL_DIR.'common/header.php');
?>
<link href="<?php echo VIEW_CSS_URL;?>lab.cssv=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />
<style>
#info,#sort{ display:none;background:#FFFFFF;}
#info ol li,#sort ol li{ height:25px;line-height:25px; text-align:center; width:100px; cursor:pointer;}
#info ol li:hover,#sort ol li:hover{ background:#E8E8E8;}
.examine {float: right;}
.examine li {float: left;}

/*问卷列表样式*/
.quest_title{
	background:#C6E1FD;
	border: 2px solid #ddd;
	border-bottom:none;
	height:36px;
	line-height:36px;
	width:946px;
}
.quest_control{
	border: 2px solid #ddd;
	border-top:none;
	height:40px;
	line-height:40px;
	width:946px;
	font-size:12px;
}
.quest_control .left, .quest_title .left{
	display:inline-block;
	width:503px;
	float:left;
	text-align:left;
	padding-left:10px;
}
.quest_control .right, .quest_title .right{
	display:inline-block;
	width:423px;
	float:right;
	text-align:right;
	padding-right:10px;
}
.quest_title .left{
	font-size:14px;
	font-weight:600;
}
.quest_title .right{
	font-size:12px;
	color:#666;
}
.quest_title .right .status{
	font-size:12px;
	color:#000;
	font-weight:600;
    display: inline-block;
    text-align: left;
    width: 42px;
}

.quest_title .right .creator{
    text-align: left;
    display: inline-block;
    width: 128px;
}

.quest_title .right .num{
    display: inline-block;
    text-align: left;
    width: 95px;
}

.quest_title .right .time{
    display: inline-block;
    text-align: left;
    width: 150px;
}
#tbody td{
	height:88px;
}
#tfoot td{
	height:46px;
}
</style>
<!--内容-->
<div class="outer" id='out_context'>
	<!--导航-->
	<div id="featurebar"></div>
    <!--end-->
    <!--搜索栏-->
    <div id="querybox" class="isearcher">
	<ul class="operation">
	<li><a href="#" class="opaddbg" id="sytle_add">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;新增</a></li>
	<li><a href="#" class="opdelbg" id="send_del">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></li>
	</ul><table width="800" border="0" cellspacing="0" cellpadding="0" style="float:right; height:35px; line-height:35px;clear:right;"><tr><td><input id="search_item_input"  class="isearcher_input_words" type="text" style="border:1px solid #97bce6;height:22px;line-height:22px;width:200px;"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/></td>
        <td align="right"><script>function sel_cks(){
        	$('#sel_ck').html('查看:'+$("#selid").find("option:selected").text());
		}</script>
        <div>
          <ul class="examine" style="position:relative;" >
           
           <li><a  title="" target="_blank" onclick="fun();return false;" id='sel_ck'>查看:所有</a></li>
           <li><a title="" onclick="fun2();return false;">排序方式</a></li>
           <select id="selid" name='selid' class="isearcher_select_list" style="display:none;" onchange="sel_cks()">
                <option value="0">所有</option>
                <option value="1">我的</option>
                <option value="2">运行中</option>
                <option value="3">已结束</option>
           </select>
          <select id="my_orders" name='my_orders' class="isearcher_select_list" style="display:none;">
			<option value="id desc">--全部--</option>
            <option value="q_start">开始时间</option>
            <option value="q_end">结束时间</option>
         </select>
         <input type="hidden" id="userid" name="userid" value="<?php echo $userid?>" />
		<div id="info" style="width:100px;border:1px solid #9CA1A6;height:100px;position:absolute;left:0px;top:30px;">
            <ol>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ol>
		</div>
        <div id="sort" style="width:100px;border:1px solid #9CA1A6;height:100px;position:absolute;left:60px;top:30px;">
            <ol>
                <li></li>
                <li></li>
                <li></li>
            </ol>
        </div>
        </ul>
        </div>
        
		</td></tr></table>
        
    </div>
    <!--end-->
    <!--内容展示区-->
    <table>
        <tbody id="tbody">
        	
        </tbody>
        <tfoot id="tfoot">
        	
        </tfoot>
      </table>
</div>

<!--end-->

<div id="edit" style="display:none">
<style type="text/css">
table.add_quest{width:741px;margin:0px 20px 5px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest #save_tag{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;}table.add_quest td.tr_nav{text-align:center;padding:0px;}table.add_quest td.m_left_20{padding-left:20px;}table textarea#q_title{width:96%;height:32px;margin-top:5px;}table.add_quest input{border:1px solid #97BDE7;height:24px; vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px; border:none;}table.add_quest .isearcher_submit_button#q_back{ background-color:#939393;}
</style>
	<table align="center" cellpadding="4" cellspacing="1" class="tableborder add_quest">
        <tbody id="ebody">
        	<tr>
            	<th class="pop_title" colspan="3">
                	<div id="pop_title">添加问卷</div>
                </th>
            </tr>
            <tr>
                <td rowspan="3"  class="tr_nav">常规设置</td>
                <td class="m_left_20">问卷标题<span class="fontred">*</span></td>
                <td style="line-height:24px;"><textarea name="q_title" id="q_title"></textarea><br><font color="#b2b2b2">(限制100字)</font></td>
            </tr>
            <tr>
                <td class="m_left_20">起止日期<span class="fontred">*</span></td>
                <td>
                    <input id="q_start" type="text" name="q_start" value="" onClick="WdatePicker()" readonly/>--
                    <input id="q_end" type="text" name="q_end" value="" onClick="WdatePicker()" readonly/>
                </td>
            </tr>
            <!--tr>
                <td class="m_left_20">问卷分类<span class="fontred">*</span></td>
                <td><select id="fk_sytle_id" name="fk_sytle_id" class="isearcher_select_list"></select></td>
            </tr>-->
            <tr>
                <td class="m_left_20">问卷类型<span class="fontred">*</span></td>
                <td id='type_id'>&nbsp;</td>
            </tr>
            <tr>
                <td rowspan="6"  class="tr_nav">条件设置</td>
                <td class="m_left_20">是否需要密码</td>
                <td style="color:#666666">
                    <input type="radio" name="pass_type" value="1" > 是 
                    <input id="pass_bak" type="text" name="pass_bak" value=""  onkeyup="passto()" onblur="passtopass()" onfocus="passshow()"/>
                    <input id="pass" type="hidden" name="pass" value="" />
                    <input id="showpass" type="checkbox" name="showpass" value="1" onclick="passtopass()"/> 密码明文显示 
                    <input type="radio" name="pass_type" value="0" checked="checked"> 否
                </td>
            </tr>
            <tr>
                <td class="m_left_20">是否需要登录</td>
                <td style="color:#666666"><input type="radio" name="q_login" value="1"> 是 <input type="radio" name="q_login" value="0" checked="checked"> 否 </td>
            </tr>
            <tr>
                <td class="m_left_20">选择参与人员</td>
                <td style="color:#666666"><a href="#">选择</a></td>
            </tr>
            <tr>
                <td class="m_left_20">是否允许匿名</td>
                <td style="color:#666666"><input type="radio" name="q_anonymous" value="1" checked="checked"> 是 <input type="radio" name="q_anonymous" value="0" > 否 </td>
            </tr>
            <tr>
                <td class="m_left_20">是否允许重复作答</td>
                <td style="color:#666666"><input type="radio" name="q_repeat" value="1" > 是 <input type="radio" name="q_repeat" value="0" checked="checked"> 否 </td>
            </tr>
            <tr>
                <td class="m_left_20">答卷限制时间</td>
                <td style="color:#666666">
                	<input id="duration" type="text" size="2" name="duration" value="30" /> (分钟)“考试”类型有效，如果填0则不限制。
                    <input id="quest_id" type="hidden"  name="quest_id" value="" />
                </td>
            </tr>
            <tr>
                <td colspan="3" id="save_tag">
                    <input id="q_add_save" onclick="mainFrame.q_add_save();" class="isearcher_submit_button" type="button" value="增 加"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input id="q_back" class="isearcher_submit_button" type="button" value="取 消"/>
                </td>
            </tr>
        </tbody>
        <tfoot id="efoot">
        </tfoot>
	</table>
    </div>
	<div id="sedit" style="display:none">
<style type="text/css">
table.add_quest{clear:right;width:400px;margin:0px 20px 5px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest .ctl_btn{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;overflow:hidden; white-space:nowrap; text-overflow:ellipsis;}table.add_quest input{border:1px solid #97BDE7;height:24px; vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px; border:none;}table.add_quest .isearcher_submit_button#q_back{ background-color:#939393;}
</style>
        <table class="tableClass add_quest" >
            <thead id="sbody">
            </thead>
        </table> 
	</div>
</div>

<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/quest.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>/My97DatePicker/WdatePicker.js "></script>
<script type="text/javascript">
var actionPhp="<?php echo SITE_ROOT.'Quest/';?>";
var page='index';//定义当前页面
var sytle_array=new Array();//分类
var type_array=new Array();//类型
var wjmc='<?php echo $wjmc;?>';
var pageno='<?php echo $pagenos;?>';
var rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>';
var userID='<?php echo cookie::get('userid');?>';

function sytle_show( ){
	var u_id= $("#selid").val();
	var orders= $("#my_orders").val();
	var key='t=1&status=1&orders='+orders;

	if(u_id=='0'){
	<?php if(in_array('1',$jsbhlist)||in_array('2',$jsbhlist)){}else{?>
		//key=key+'&c_userid='+u_id;
	<?php } ?>
	}
	if(u_id=='1') key+='&c_userid='+userID;
	if(u_id=='2') key+='&state=1';
	if(u_id=='3') key+='&state=2';
	if(wjmc!='') key+='&q_title='+wjmc;
	if(pageno) key+='&pageno='+pageno;
	var temp=do_ajax('getData',key);
	showInfo(temp,'');
	wjmc='';
}

function copy_save(){
	<?php $right=$partMdl->Partvalidate('quest#quest#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	if(getdom().find('#sbodys #new_title').val().replace(/(^\s*)|(\s*$)/g, "")){}else{alert('问卷标题未填写');return false;}
	if(strlen(getdom().find('#sbodys #new_title').val())>100){alert('问卷标题大于100个字符');return false;}
	var temp_val="t=1&new_title="+getdom().find('#sbodys #new_title').val()+"&copy_id="+getdom().find('#sbodys #copy_id').val();
	do_ajax('copy',temp_val);	//更新数据
	temp_val='';
	sytle_show();	
	parent.box_close();
}

function short_copy(){
	var d=getdom().find("#dj_url").val();
	window.clipboardData.setData('text', d); 
}

//添加	 
function q_add_save(){	
	<?php $right=$partMdl->Partvalidate('quest#quest#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	var t_start=unixtime(getdom().find('#ebodys #q_start').val());
	var t_end=unixtime(getdom().find('#ebodys #q_end').val());
	if(getdom().find('#ebodys #q_title').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "")){}else	{alert('问卷标题未填写');return false;}
	if(strlen(getdom().find('#ebodys #q_title').val().replace(/\&/g,"%24"))>100){alert('问卷标题大于100个字符');return false;}
	if(getdom().find('input:radio[name="fk_type_id"]:checked').val()){}else	{alert('问卷类型未选择');return false;}
	if(!getdom().find('#ebodys #q_start').val()||!getdom().find('#ebodys #q_end').val())	{alert('起止时间未选择');return false;}
	if(getdom().find('#ebodys #q_start').val()>getdom().find('#ebodys #q_end').val())	{alert('开始时间大于结束时间');return false;}
	var temp_val="t=1&q_title="+getdom().find('#ebodys #q_title').val().replace(/\&/g,"%24")+"&q_start="+t_start+"&q_end="+t_end+"&fk_sytle_id="+getdom().find('#ebodys #fk_sytle_id').val()+"&fk_type_id="+getdom().find('input:radio[name="fk_type_id"]:checked').val()+"&pass_type="+getdom().find('input:radio[name="pass_type"]:checked').val()+"&pass="+getdom().find('#ebodys #pass').val()+"&status=1&q_login="+getdom().find('input:radio[name="q_login"]:checked').val()+"&duration="+getdom().find('#ebodys #duration').val()+"&q_anonymous="+getdom().find('input:radio[name="q_anonymous"]:checked').val()+"&q_repeat="+getdom().find('input:radio[name="q_repeat"]:checked').val();
	do_ajax('add',temp_val);	//更新数据
	temp_val='';
	sytle_show();			
	parent.box_close();
}
	
$(document).ready(function(e){
	//问卷类型
	type_temp=do_ajax('getSytleData','t=2&perpages=999999');	//更新数据
	opt='';
	for(var i=0;i<type_temp.data.length;i++){
		if(i==1){
			opt += '<input type="radio" checked="checked" name="fk_type_id" value="'+type_temp.data[i]['id']+'" onclick="nov(this.value)">'+type_temp.data[i]['name'];
		}else{
			opt += '<input type="radio" name="fk_type_id" value="'+type_temp.data[i]['id']+'" onclick="nov(this.value)">'+type_temp.data[i]['name'];
		}
		eval("type_array["+type_temp.data[i]['id']+"]=type_temp.data[i]['name'];")
	}
	$("#type_id").empty().formhtml(opt);	
	sytle_show();		
	//修改
	  
	$(window.parent.document).find('#desc_add_save').live('click',function(){	
		<?php $right=$partMdl->Partvalidate('quest#quest#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		var temp_val="t=5&q_top_desc="+getdom().find('#desc_edits #q_top_desc').val()+"&q_foot_desc="+getdom().find('#desc_edits #q_foot_desc').val()+"&quest_id="+getdom().find('#desc_edits #quest_id').val();
		do_ajax('updata',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		getdom().find('#desc_edits').hide();
		getdom().find('#out_context').show();
	});
	
	getdom().find('#q_edit_save').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#quest#edit'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>	
		var t_start=unixtime(getdom().find('#ebodys #q_start').val());
		var t_end=unixtime(getdom().find('#ebodys #q_end').val());	
		if(getdom().find('#ebodys #q_title').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "")){}else	{alert('问卷标题未填写');return false;}
		if(strlen(getdom().find('#ebodys #q_title').val().replace(/\&/g,"%24"))>100){alert('问卷标题大于100个字符');return false;}
		//if(getdom().find('#ebodys #fk_sytle_id').val()){}else	{alert('问卷分类未选择');return false;}				
		if(getdom().find('input:radio[name="fk_type_id"]:checked').val()){}else	{alert('问卷类型未选择');return false;}
		if(!getdom().find('#ebodys #q_start').val()||!getdom().find('#ebodys #q_end').val())	{alert('起止时间未选择');return false;}				
		if(getdom().find('#ebodys #q_start').val()>getdom().find('#ebodys #q_end').val())	{alert('开始时间大于结束时间');return false;}
		var temp_val="t=1&q_title="+getdom().find('#ebodys #q_title').val().replace(/\&/g,"%24")+"&q_start="+t_start+"&q_end="+t_end+"&fk_sytle_id="+getdom().find('#ebodys #fk_sytle_id').val()+"&fk_type_id="+getdom().find('input:radio[name="fk_type_id"]:checked').val()+"&pass_type="+getdom().find('input:radio[name="pass_type"]:checked').val()+"&pass="+getdom().find('#ebodys #pass').val()+"&q_login="+getdom().find('input:radio[name="q_login"]:checked').val()+"&duration="+getdom().find('#ebodys #duration').val()+"&q_anonymous="+getdom().find('input:radio[name="q_anonymous"]:checked').val()+"&q_repeat="+getdom().find('input:radio[name="q_repeat"]:checked').val()+"&quest_id="+getdom().find('#ebodys #quest_id').val();
		do_ajax('updata',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		//getdom().find('.asyncbox_close').trigger('click');
		parent.box_close();
	});
	
	//end
	$('#sytle_add').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#quest#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		$('#edit #pop_title').html('添加问卷');
		$('#edit #q_title').val('');
		$('#edit #q_start').val('');
		$('#edit #q_end').val('');
		$('#edit #duration').val('30');
		$('#edit #pass').val(''); 	 
		$('#edit #quest_id').val('');
		getdom().find("#save_tag").empty().formhtml('<input id="q_add_save" onclick="mainFrame.q_add_save();" class="isearcher_submit_button" type="button" value="增 加"/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="q_back" class="isearcher_submit_button" type="button" value="返 回"/>');
		parent.asyncbox.open({
		title  : '问卷添加',
		id: 'ebodys',
		html:$("#edit").formhtml()
		});				 
	});
	
	getdom().find('#q_back').live('click',function(){
		parent.box_close();
	});
	
	$('#q_back_out').live('click',function(){	
		$('#desc_edits').hide();
		$('#out_context').show();
	});
	//删除到回收站
	$('#send_del').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#quest#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		var v=inputValue($('input[id="Sid"]')); 
		if(v){
		if(confirm('确定删除数据到回收站吗？')){
		 var temp_val="t=6&status=0&ids="+v;
		 do_ajax('updata',temp_val);	//更新数据
		 sytle_show();
		 }
		}else{alert('请选择要删除的项目');}
	});
	
	$('#desc_edits #descs').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#quest#edit'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>	
		if($('input:radio[name="descs"]:checked').val()=='1'){
			$('#desc_edits #desc_top').show();
			$('#desc_edits #desc_foot').hide();
		}else{
			$('#desc_edits #desc_top').hide();
			$('#desc_edits #desc_foot').show();
		}
	});
	
	//查询
	$('#search_item_btn').live('click',function(){
		 var temp=do_ajax('getData','t=1&status=1&q_title='+$('#search_item_input').val()); 
		 showInfo(temp,'');	
	});
});

//删除问题数据
function del(t,id,pageno){
	if(confirm('确定删除数据到回收站吗？')){
		<?php $right=$partMdl->Partvalidate('quest#quest#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		var temp_val="t=6&status=0&ids="+id;
		do_ajax('updata',temp_val);	//更新数据
		sytle_show();
	}			
}

//预览功能
function preview(id){
	parent.asyncboxPreview('问卷预览','preview','<?php echo SITE_ROOT;?>Dj/start/wjid/'+id+'?dj_no=1');	
}
//明码
function pass_show(){
	if(getdom().find('#ebodys #pass').val()){passtopass()};	
}
function passshow(){
	getdom().find('#ebodys #pass_bak').val(getdom().find('#ebodys #pass').val());	
}
function passto(){
	if(getdom().find('#ebodys #pass_bak').val()!='●●●●●●'){
		getdom().find('#ebodys #pass').val(getdom().find('#ebodys #pass_bak').val());
	}				 
}
function passtopass(){
	if(getdom().find('input:checkbox[name="showpass"]:checked').val()){
		passshow();
	}else{
		if(getdom().find('#ebodys #pass').val()) getdom().find('#ebodys #pass_bak').val('●●●●●●');
	}			 
}		
function nov(id){
	if(id=='5'){   //投票时间设置为0
		getdom().find('#ebodys #duration').val('0');
		getdom().find('#ebodys #duration').attr("readonly","readonly").css('background', '#ddd');
	}else{
		getdom().find('#ebodys #duration').removeAttr("readonly").css('background', '');
	}
}

var selId = document.getElementById("selid");
var hidden = document.getElementById("my_orders");
option_elems = selId.getElementsByTagName('option');
option_elems2 = hidden.getElementsByTagName('option');
var img_1 = document.getElementById("");
var img_2 = document.getElementById("img_2");
var info = document.getElementById("info");
var sortinfo = document.getElementById("sort");
var infoli = info.getElementsByTagName("li");
var sortli = sortinfo.getElementsByTagName("li");
for( var i=0;i< option_elems.length; i++ ){
	infoli[ i ].innerHTML = option_elems[ i ].innerHTML;
	infoli[ i ].setAttribute( 'val', option_elems[ i ].value );
	infoli[ i ]["c_value"] = option_elems[ i ].value;
}

for( var i=0;i< option_elems2.length; i++ ){
	sortli[ i ].innerHTML = option_elems2[ i ].innerHTML;
	sortli[ i ].setAttribute( 'val', option_elems2[ i ].value );
	sortli[ i ]["c_value"] = option_elems2[ i ].value;
}

for( var i = 0; i < sortli.length; i++ ){
	sortli[ i ].index = i;
	sortli[ i ].onclick = function( i ){
		option_elems2[ this.index ].selected = true;
		sytle_show();
		sortinfo.style.display = "none";
	}
}

for( var i = 0; i < infoli.length; i++ ){
	infoli[ i ].index = i;
	infoli[ i ].onclick = function( i ){
		$('#sel_ck').html('查看:'+option_elems[ this.index ].text);
		option_elems[ this.index ].selected=true;
		sytle_show();
		info.style.display = "none";
	}
}

function fun(){
	info.style.display = "block";
	sortinfo.style.display = "none";
	setTimeout("funinfo()",3000);
}

function fun2(){
	sortinfo.style.display = "block";
	info.style.display = "none";
	setTimeout("funsortinfo()",3000);
}
function funsortinfo(){
	sortinfo.style.display = "none";
}
function funinfo(){
	info.style.display = "none";
}

</script>
<?php
include_once(TPL_DIR.'common/footer.php');
?>










<ul id="treeDemo"></ul>






<script type="text/javascript" src="jquery.ztree.core-3.5.js" />
<script type="text/javascript" src="jquery.ztree.excheck-3.5.js" />
<script type="text/javascript" src="jquery.ztree.exedit-3.5.js" />
<script type="text/javascript" src="jquery.ztree.exhide-3.5.js" />
<script>
    var setting = {
        async: {
            enable: true,
            url: getUrl
        },
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        view: {
            expandSpeed: ""
        },
        callback: {
            beforeExpand: beforeExpand,
            onAsyncSuccess: onAsyncSuccess,
            onAsyncError: onAsyncError
        }
    };

    var zNodes =[
        {name:"500个节点", id:"1", count:500, times:1, isParent:true},
        {name:"1000个节点", id:"2", count:1000, times:1, isParent:true},
        {name:"2000个节点", id:"3", count:2000, times:1, isParent:true}
    ];

    var log, className = "dark",
            startTime = 0, endTime = 0, perCount = 100, perTime = 100;
    function getUrl(treeId, treeNode) {
        var curCount = (treeNode.children) ? treeNode.children.length : 0;
        var getCount = (curCount + perCount) > treeNode.count ? (treeNode.count - curCount) : perCount;
        var param = "id="+treeNode.id+"_"+(treeNode.times++) +"&count="+getCount;
        return "../asyncData/getNodesForBigData.php?" + param;
    }
    function beforeExpand(treeId, treeNode) {
        if (!treeNode.isAjaxing) {
            startTime = new Date();
            treeNode.times = 1;
            ajaxGetNodes(treeNode, "refresh");
            return true;
        } else {
            alert("zTree 正在下载数据中，请稍后展开节点。。。");
            return false;
        }
    }
    function onAsyncSuccess(event, treeId, treeNode, msg) {
        if (!msg || msg.length == 0) {
            return;
        }
        var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
                totalCount = treeNode.count;
        if (treeNode.children.length < totalCount) {
            setTimeout(function() {ajaxGetNodes(treeNode);}, perTime);
        } else {
            treeNode.icon = "";
            zTree.updateNode(treeNode);
            zTree.selectNode(treeNode.children[0]);
            endTime = new Date();
            var usedTime = (endTime.getTime() - startTime.getTime())/1000;
            className = (className === "dark" ? "":"dark");
            showLog("[ "+getTime()+" ]&nbsp;&nbsp;treeNode:" + treeNode.name );
            showLog("加载完毕，共进行 "+ (treeNode.times-1) +" 次异步加载, 耗时："+ usedTime + " 秒");
        }
    }
    function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        alert("异步获取数据出现异常。");
        treeNode.icon = "";
        zTree.updateNode(treeNode);
    }
    function ajaxGetNodes(treeNode, reloadType) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        if (reloadType == "refresh") {
            treeNode.icon = "../../../css/zTreeStyle/img/loading.gif";
            zTree.updateNode(treeNode);
        }
        zTree.reAsyncChildNodes(treeNode, reloadType, true);
    }
    function showLog(str) {
        if (!log) log = $("#log");
        log.append("<li class='"+className+"'>"+str+"</li>");
        if(log.children("li").length > 4) {
            log.get(0).removeChild(log.children("li")[0]);
        }
    }
    function getTime() {
        var now= new Date(),
                h=now.getHours(),
                m=now.getMinutes(),
                s=now.getSeconds(),
                ms=now.getMilliseconds();
        return (h+":"+m+":"+s+ " " +ms);
    }

    $(function(){
        $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    });
</script>













