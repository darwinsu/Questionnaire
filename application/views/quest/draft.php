<?php

//*定义数据
$target_name='草稿';
include_once(TPL_DIR.'common/header.php');
?>

<link href="<?php echo VIEW_CSS_URL;?>lab.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />
<style>
/*草稿样式*/
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
    <!--搜索栏-->
    <div id="querybox" class="isearcher" style="padding-top:14px; padding-bottom:5px;">
   <!-- 条件搜索：
    	问卷标题<input id="search_item_input"  class="isearcher_input_words" type="text"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/>-->
		<input id="sytle_add" class="isearcher_submit_button" type="button" value="添 加"/>
		<input id="send_add" class="isearcher_submit_button" type="button" value="发 布"/>
		<input id="send_del" class="isearcher_submit_button" type="button" value="移回收站"/>
        <div id="iscp_iresult" class="isearcher_instant_result">
        <ul id="iscp_iresult_list"> </ul>
        </div>
         
    </div>
    <!--内容展示区-->
        <table>
            <tbody id="tbody">

            </tbody>
            <tfoot id="tfoot">

            </tfoot>
          </table>
</div>


<div id="edit" style="display:none">
<style type="text/css">
table.add_quest{width:741px;margin:10px 30px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest #save_tag{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;}table.add_quest td.tr_nav{text-align:center;padding:0px;}table.add_quest td.m_left_20{padding-left:20px;}table textarea#q_title{width:96%;height:32px;margin-top:5px;}table.add_quest input{border:1px solid #97BDE7;height:24px; vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px; border:none;}table.add_quest .isearcher_submit_button#q_back{ background-color:#939393;}
</style>
    <table align="center"  cellpadding="4" cellspacing="1" class="tableborder add_quest">
		  <tbody id="ebody">
          	 <tr>
            	<th class="pop_title" colspan="3">
                	<div id="pop_title">添加问卷</div>
                </th>
            </tr>
             <tr>
				<td rowspan="3"  class="tr_nav">常规设置</td>
                <td class="m_left_20">问卷标题<span class="fontred">*</span></td>
				<td><textarea name="q_title" id="q_title" cols="60" rows="5"> </textarea><br><font color="#b2b2b2">(限制100字)</font></td>
             </tr> <tr>
                <td class="m_left_20">起止日期<span class="fontred">*</span></td>
				<td><input id="q_start" type="text" name="q_start" value="" onClick="WdatePicker()" readonly/>--<input id="q_end" type="text" name="q_end" value="" onClick="WdatePicker()" readonly/></td>
             </tr>
             </tr> <tr>
                <td class="m_left_20">问卷类型</td>
				<td id='type_id'>&nbsp;</td>
             </tr> <tr>
				<td rowspan="6"  class="tr_nav">条件设置</td>
                <td class="m_left_20">是否需要密码</td>
				<td style="color:#666666"><input type="radio" name="pass_type" value="1" >是 <input id="pass_bak" type="text" name="pass_bak" value=""  onkeyup="passto()" onblur="passtopass()" onfocus="passshow()"/><input id="pass" type="hidden" name="pass" value="" /><input id="showpass" type="checkbox" name="showpass" value="1" onclick="passtopass()"/>密码明文显示<input type="radio" name="pass_type" value="0" >否</td>
             </tr> <tr>
                <td class="m_left_20">是否需要登录</td>
				<td style="color:#666666"><input type="radio" name="q_login" value="1" >是<input type="radio" name="q_login" value="0" >否</td>
             </tr> <tr>
                <td class="m_left_20">选择参与人员</td>
                <td style="color:#666666"><a href="#">选择</a></td>
             </tr><tr>
                <td class="m_left_20">是否允许匿名</td>
				<td style="color:#666666"><input type="radio" name="q_anonymous" value="1" >是<input type="radio" name="q_anonymous" value="0" >否</td>
             </tr> <tr>
                <td class="m_left_20">是否允许重复作答</td>
				<td style="color:#666666"><input type="radio" name="q_repeat" value="1" >是<input type="radio" name="q_repeat" value="0" >否</td>
             </tr> <tr>
                <td class="m_left_20">答卷限制时间</td>
                <td style="color:#666666">
                <input id="duration" type="text" size="2" name="duration" value="30" /> (分钟)“考试”类型有效，如果填0则不限制。
                <input id="quest_id" type="hidden"  name="quest_id" value="" />
                </td>
             </tr> <tr>
				<td colspan="3" id="save_tag">
                	<input id="q_add_save" class="isearcher_submit_button" type="button" value="增 加"/>&nbsp;&nbsp;&nbsp;&nbsp;
                	<input id="q_back" class="isearcher_submit_button" type="button" value="取 消"/>
                </td>
			</tr>		  	  
		  </tbody>
		  </tbody>
          <tfoot id="efoot">
          </tfoot>
      </table>
    </div>
	<div id="sedit" style="display:none">
<style type="text/css">
table.add_quest{clear:right;width:400px;margin:0px 20px 5px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest .ctl_btn{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;}table.add_quest input{border:1px solid #97BDE7;height:24px; vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px; border:none;}table.add_quest .isearcher_submit_button#q_back{ background-color:#939393;}
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
var actionPhp="<?php echo SITE_ROOT.'/Quest/';?>";
var page='draft';//定义当前页面
var sytle_array=new Array();//分类
var type_array=new Array();//类型
var rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>';
var userID='<?php echo cookie::get('userid');?>';
function sytle_show(){
	var temp=do_ajax('getData','t=1&status=2');
	showInfo(temp,'');
}

function short_copy(){
	var d=getdom().find("#dj_url").val();
	window.clipboardData.setData('text', d);
}

function copy_save(){
	<?php $right=$partMdl->Partvalidate('quest#draft#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	if(getdom().find('#sbodys #new_title').val()){}else	{alert('问卷标题未填写');return false;}
	var temp_val="t=1&new_title="+getdom().find('#sbodys #new_title').val()+"&copy_id="+getdom().find('#sbodys #copy_id').val();
	do_ajax('copy',temp_val);	//更新数据
	temp_val='';
	sytle_show();	
	parent.box_close();
};

$(document).ready(function(e){
	//问卷类型
	type_temp=do_ajax('getSytleData','t=2');	//更新数据
	opt='';
	for(var i=0;i<type_temp.data.length;i++){
		opt += '<input type="radio" name="fk_type_id" value="'+type_temp.data[i]['id']+'"  onclick="nov(this.value)">'+type_temp.data[i]['name'];
		eval("type_array["+type_temp.data[i]['id']+"]=type_temp.data[i]['name'];")
	}
	$("#type_id").empty().formhtml(opt);	
	sytle_show();
	
	//修改
	getdom().find('#desc_add_save').live('click',function(){
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
		if(getdom().find('#ebodys #fk_sytle_id').val()){}else	{alert('问卷分类未选择');return false;}				
		if(getdom().find('input:radio[name="fk_type_id"]:checked').val()){}else	{alert('问卷类型未选择');return false;}
		if(!getdom().find('#ebodys #q_start').val()||!getdom().find('#ebodys #q_end').val())	{alert('起止时间未选择');return false;}
		var temp_val="t=1&q_title="+getdom().find('#ebodys #q_title').val().replace(/\&/g,"%24")+"&q_start="+t_start+"&q_end="+t_end+"&fk_sytle_id="+getdom().find('#ebodys #fk_sytle_id').val()+"&fk_type_id="+getdom().find('input:radio[name="fk_type_id"]:checked').val()+"&pass_type="+getdom().find('input:radio[name="pass_type"]:checked').val()+"&pass="+getdom().find('#ebodys #pass').val()+"&q_login="+getdom().find('input:radio[name="q_login"]:checked').val()+"&duration="+getdom().find('#ebodys #duration').val()+"&q_anonymous="+getdom().find('input:radio[name="q_anonymous"]:checked').val()+"&q_repeat="+getdom().find('input:radio[name="q_repeat"]:checked').val()+"&quest_id="+getdom().find('#ebodys #quest_id').val();
		do_ajax('updata',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		parent.box_close();
	});	
	
	$('#sytle_add').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#quest#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		$('#edit #pop_title').html('添加问卷');
		$('#edit #q_title').val('');
		$('#edit #q_start').val('');
		$('#edit #q_end').val('');
		$('#edit #duration').val('30');
		$('#edit #pass').val(''); 	 
		$('#edit #quest_id').val('');
		getdom().find("#save_tag").empty().formhtml('<input id="q_add_save" class="isearcher_submit_button" type="button" value="增 加"/><input id="q_back" class="isearcher_submit_button" type="button" value="取 消"/>');
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
	
	$('#desc_edits #descs').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#draft#edit'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>	
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
		var temp=do_ajax('getData','t=1&status=2&q_title='+$('#search_item_input').val()); 
		showInfo(temp,'');	
	});
	$('#send_add').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#draft#send'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>	
		var v=inputValue($('input[id="Sid"]')); 
		var temp_val="t=6&status=1&ids="+v;
		do_ajax('updata',temp_val);	//更新数据
		sytle_show();
	});
	
	//删除到回收站
	$('#send_del').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#draft#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>		
		var v=inputValue($('input[id="Sid"]')); 
		if(v){
			if(confirm('确定删除数据到回收站吗？')){				
				var temp_val="t=6&status=0&ids="+v;
				do_ajax('updata',temp_val);	//更新数据
				sytle_show();
			}
		}else{alert('请选择要删除的项目');}
	});
	
	//添加	 
	getdom().find('#q_add_save').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#draft#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		var t_start=unixtime(getdom().find('#ebodys #q_start').val());
		var t_end=unixtime(getdom().find('#ebodys #q_end').val());
		if(getdom().find('#ebodys #q_title').val()){}else{alert('问卷标题未填写');return false;}
		//if(getdom().find('#ebodys #fk_sytle_id').val()){}else{alert('问卷分类未选择');return false;}				
		if(getdom().find('input:radio[name="fk_type_id"]:checked').val()){}else	{alert('问卷类型未选择');return false;}
		if(!getdom().find('#ebodys #q_start').val()||!getdom().find('#ebodys #q_end').val())	{alert('起止时间未选择');return false;}
		var temp_val="t=1&q_title="+getdom().find('#ebodys #q_title').val()+"&q_start="+t_start+"&q_end="+t_end+"&fk_sytle_id="+getdom().find('#ebodys #fk_sytle_id').val()+"&fk_type_id="+getdom().find('input:radio[name="fk_type_id"]:checked').val()+"&status=2&pass_type="+getdom().find('input:radio[name="pass_type"]:checked').val()+"&pass="+getdom().find('#ebodys #pass').val()+"&q_login="+getdom().find('input:radio[name="q_login"]:checked').val()+"&duration="+getdom().find('#ebodys #duration').val()+"&q_anonymous="+getdom().find('input:radio[name="q_anonymous"]:checked').val()+"&q_repeat="+getdom().find('input:radio[name="q_repeat"]:checked').val();
		do_ajax('add',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		parent.box_close();
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
	if($('#ebodys #pass').val()){passtopass()};	
}

function passshow(){
	$('#ebodys #pass_bak').val($('#ebodys #pass').val());	
}

function passto(){
	if($('#ebodys #pass_bak').val()!='●●●●●●'){
		$('#ebodys #pass').val($('#ebodys #pass_bak').val());
	}
}

function passtopass(){
	if($('input:checkbox[name="showpass"]:checked').val()){
		passshow();
	}else{
		if($('#ebodys #pass').val()) $('#ebodys #pass_bak').val('●●●●●●');
	}
}

function nov(id){
	if(id=='5'){   //投票时间设置为0
		$('#ebodys #duration').val('0');
		$('#ebodys #duration').attr("readonly","readonly").css('background', '#ddd');
	}else{
		$('#ebodys #duration').removeAttr("readonly").css('background', '');
	}
}
</script>
<?php
include_once(TPL_DIR.'common/footer.php');
?>

