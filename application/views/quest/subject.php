<?php include(TPL_DIR.'common/header.php');?>
<link href="<?php echo VIEW_JS_URL;?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<link href="<?php echo VIEW_CSS_URL;?>lab.css" rel="stylesheet" type="text/css" />
<!--内容-->
    <div id="querybox"  class="isearcher">
    	<ul class="operation">
            <li><a href="#" class="opaddbg" id="sytle_add">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;新增</a></li>
            <?php $right=$partMdl->Partvalidate('quest#subject#del'); if($right['state']){?>
            <li><a href="#" class="opdelbg" id="all_del">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></li><?php }?>	
        </ul>
        <!--问卷名称--><select id="fk_quest_id" name="fk_quest_id" class="isearcher_select_list" onchange="sel_show(this.value);" style="width:150px; display:none;"></select>
    	<!--条件搜索：问题标题<input id="search_item_input"  size="15"   type="text"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索" />-->
        <input id="sel_item_btn" class="isearcher_submit_button" type="button" value="预 览"/>
        </div>
    </div>
    <table class="tableClass"  >
          <thead>
			<tr id="tthead" >
			<th width="4%"><input type='checkbox' name='Allcheck' id='Allcheck' onClick='allchead(this.checked)' ></th>
				<th width="10%">题号</th>
                <th>问题标题</th>
				<th width="10%">问题类型</th>
				<th width="10%">排序号</th>
				<th width="12%">操作</th>
             </tr>
		  </thead> 
		  <tbody id="tbody">
		  </tbody>
          <tfoot id="tfoot">
          </tfoot>
      </table>

<div id="edit" style="display:none">
<style type="text/css">table.add_quest{ clear:right;width:741px;margin:0px 20px 5px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest #save_tag{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;}table.add_quest td.tr_nav{text-align:center;padding:0px;}table.add_quest td.m_left_20{padding-left:20px;}table textarea{width:96%;height:32px;margin-top:8px;}table.add_quest input{border:1px solid #97BDE7;height:24px; vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px; border:none;}table.add_quest .isearcher_submit_button#q_back{ background-color:#939393;}</style>
    <table align="center"  cellpadding="4" cellspacing="1" class="tableborder add_quest">
        <tbody id="ebody">
        	<tr>
            	<th class="pop_title" colspan="2">
                	<div id="pop_title"></div>
                </th>
            </tr>
            <tr>
                <td>题号</td>
                <td><input type="text" size="3" id="title_id" name="title_id" value="1" onblur="noF($('#ebodys #title_id'))"/></td>
            </tr>
            <tr>
                <td>问题标题<span class="fontred">*</span></td>
                <td style="line-height:24px;color:#666666"><textarea name="s_title" id="s_title"> </textarea><br/>(限制250字)</td>
            </tr>
            <tr>
                <td>问题说明</td>
                <td style="line-height:24px;color:#666666"><textarea name="q_remark" id="q_remark"> </textarea><br/>(限制500字)</td>
            </tr>
            <tr>
                <td>上传图片<input type="hidden" id="s_url" name="s_url" value="" /></td>
                <td id='c_url'> </td>
            </tr>
            <tr>
                <td>问题类型<span class="fontred">*</span></td>
                <td id='type_id' style="color:#666666">&nbsp;</td>
            </tr>
            <tr>
                <td>多选限制<span class="fontred">*</span></td>
                <td style="color:#666666"><input type="text" size="3" id="chk_limit" name="chk_limit" value="0" />&nbsp;<font color="#ccc">多选题，限制选中项数目</font></td>
            </tr>
            <tr>
                <td>填写方式<span class="fontred">*</span></td>
                <td style="color:#666666"><input type="radio" name="s_type" id="s_type" value="1" /> 必填 <input id="s_type" type="radio" name="s_type" value="0" /> 选填 </td>
            </tr>
            <tr>
                <td>排序号<span class="fontred">*</span></td>
                <td style="color:#666666"><input type="text" size="3" id="s_order" name="s_order" value="1" onblur="mainFrame.noZ($('#ebodys #s_order'))"><input id="q_subjesct_id" type="hidden"  name="q_subjesct_id" value="" /> (非负数) 在同一页中号越小越靠前</td>
            </tr> 
            <tr>
            	<td colspan="2" id="save_tag">
                	
                </td>
            </tr>	
        </tbody>
        <tfoot id="efoot">
        </tfoot>
    </table>
</div>

<div id="subject_show" style="display:none">
<style type="text/css">
table.add_quest{width:741px;margin:0px 20px 5px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest #save_tag{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;}table.add_quest td.tr_nav{text-align:center;padding:0px;}table.add_quest td.m_left_20{padding-left:20px;}table textarea#q_title{width:96%;height:32px;margin-top:5px;}table.add_quest input{border:1px solid #97BDE7;height:24px;vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px;border:none;}table.add_quest .isearcher_submit_button#short_edit_back,table.add_quest .isearcher_submit_button#sub_edit_back{background-color:#939393;}table.add_quest .isearcher_submit_button#sub_add_save{background-color:#DC842B}
</style>
 	<div id="querybox_sub" style="width:741px; margin:0 20px;height:32px">
		<input id="subject_add" class="isearcher_submit_button" type="button" value="添 加"/>
    </div>
	<!--选项设置-->
    <table align="center"  cellpadding="4" cellspacing="1" class="tableborder add_quest">
		  <tbody id="sbody">
          	
		  </tbody>
          <tfoot id="sfoot">
          </tfoot>
		  <span id='span_sub'><input id="fk_subject_id" type="hidden"  name="fk_subject_id" value="" /></span>
      </table>
</div>

<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/quest.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>uploadify/jquery.uploadify.v2.1.4.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>uploadify/swfobject.js"></script>
<script type="text/javascript">
var actionPhp="<?php echo SITE_ROOT.'/Quest/';?>";
var page='subject';//定义当前页面
var ss_type_array=new Array();//类型
var rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>';
var userID='<?php echo cookie::get('userid');?>';
function sytle_show(){
	var temp=do_ajax('getData','t=2&perpages=200&fk_quest_id='+$('#querybox #fk_quest_id').val()); 
	showInfo(temp,'');		
}

function sel_show(id){
	var temp=do_ajax('getData','t=2&perpages=200&fk_quest_id='+id); 
	showInfo(temp,'');			
}

function subject_show(){
	var id = $("#span_sub #fk_subject_id").val();
	var vals=do_ajax('getData','t=3&fk_subject_id='+id);
	subjectShow(vals, id);
}

function order_show(){
	var temp=do_ajax('getOrder','fk_quest_id='+$('#querybox #fk_quest_id').val()); 
	return temp;			
}

//保存关闭	
function sub_add_save(){
	<?php $right=$partMdl->Partvalidate('quest#subject#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	if(getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "")){}else	{alert('选项题未填写');return false;}
	if(getdom().find('#sbodys #s_order').val()){}else	{alert('选项排序号未填写');return false;}
	if(getdom().find('#sbodys #s_value').val()){}else	{alert('选项分值未填写');return false;}
	var temp_val="t=3&s_answer="+getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24")+"&s_order="+getdom().find('#sbodys #s_order').val()+"&s_value="+getdom().find('#sbodys #s_value').val()+"&s_url="+getdom().find('#sbodys #s_url').val()+"&s_replenish="+getdom().find('input:checkbox[name="s_replenish"]:checked').val()+"&fk_subject_id="+getdom().find('#sbodys #fk_subject_id').val();
	do_ajax('add',temp_val);	//更新数据
	temp_val='';
	subject_show();
	parent.box_close();
}

//保存继续	
function sub_add_saves(){
	<?php $right=$partMdl->Partvalidate('quest#subject#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	if(getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "")){}else	{alert('选项标题未填写');return false;}
	if(getdom().find('#sbodys #s_order').val()){}else	{alert('选项排序号未填写');return false;}
	if(getdom().find('#sbodys #s_value').val()){}else	{alert('选项分值未填写');return false;}
	var temp_val="t=3&s_answer="+getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24")+"&s_order="+getdom().find('#sbodys #s_order').val()+"&s_value="+getdom().find('#sbodys #s_value').val()+"&s_url="+getdom().find('#sbodys #s_url').val()+"&s_replenish="+getdom().find('input:checkbox[name="s_replenish"]:checked').val()+"&fk_subject_id="+getdom().find('#fk_subject_id').val();
	do_ajax('add',temp_val);	//更新数据
	temp_val='';
	subject_show();
	var s_title = getdom().find("#sbodys #sbody #the_q_title").html();
	edit_Set('', s_title);
}

//编辑保存
function sub_edit_save(){
	<?php $right=$partMdl->Partvalidate('quest#subject#edit'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>	
	if(getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*getdom().find)/g, "")){}else	{alert('选项标题未填写');return false;}
	if(getdom().find('#sbodys #s_order').val()){}else	{alert('选项排序号未填写');return false;}
	if(getdom().find('#sbodys #s_value').val()){}else	{alert('选项分值未填写');return false;}
	var temp_val="t=3&s_answer="+getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24")+"&s_order="+getdom().find('#sbodys #s_order').val()+"&s_value="+getdom().find('#sbodys #s_value').val()+"&s_url="+getdom().find('#sbodys #s_url').val()+"&s_replenish="+getdom().find('input:checkbox[name="s_replenish"]:checked').val()+"&subjesct_id="+getdom().find('#sbodys #subjesct_id').val();
	do_ajax('updata',temp_val);	//更新数据
	temp_val='';
	subject_show();
}

//保存关闭	 
function q_add_save(){
	<?php $right=$partMdl->Partvalidate('quest#subject#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	if(getdom().find('#ebodys #s_title').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "")){}else	{alert('问题标题未填写');return false;}
	if(getdom().find('input:radio[name="fk_type_id"]:checked').val()){}else	{alert('问题类型未选择');return false;}				
	if(getdom().find('input:radio[name="s_type"]:checked').val()){}else	{alert('填写方式未选择');return false;}
	if(getdom().find('#ebodys #s_order').val()){if(isNum(getdom().find('#ebodys #s_order'))){}else{alert('排序号请填写自然数');return false;}}else	{alert('排序号未填写');return false;}
	var temp_val="t=2&chk_limit="+getdom().find('#ebodys #chk_limit').val()+"&s_title="+getdom().find('#ebodys #s_title').val().replace(/\&/g,"%24")+"&title_id="+getdom().find('#ebodys #title_id').val()+"&s_type="+getdom().find('#ebodys input:radio[name="s_type"]:checked').val()+"&fk_type_id="+getdom().find('input:radio[name="fk_type_id"]:checked').val()+"&s_url="+getdom().find('#ebodys #s_url').val()+"&s_order="+getdom().find('#ebodys #s_order').val()+"&fk_quest_id="+$('#querybox #fk_quest_id').val()+"&q_remark="+getdom().find('#ebodys #q_remark').val().replace(/\&/g,"%24");
	do_ajax('add',temp_val);	//更新数据
	temp_val='';
	sytle_show();			
	parent.box_close();
}

$(document).ready(function(e){
	//问卷分类
	var sytle_temp=do_ajax('getData','t=1&perpages=999999');	//更新数据
	var opt='';
	var vopt='<?php if($_GET['questid']) echo $_GET['questid'];?>';
	var vselectd='';
	for(var i=0;i<sytle_temp.data.length;i++){
		if(sytle_temp.data[i]['id']==vopt) {vselectd='selected';}else{vselectd='';}
		if(rights=='3'){
			if(sytle_temp.data[i]['c_userid']==userID){
				opt = $("<option value='"+sytle_temp.data[i]['id']+"' "+vselectd+">"+sytle_temp.data[i]['q_title']+"</option>");
			}
		}else{
			opt = $("<option value='"+sytle_temp.data[i]['id']+"' "+vselectd+">"+sytle_temp.data[i]['q_title']+"</option>");
		}
		$("#querybox #fk_quest_id").append(opt);				
	}
	
	//问题类型
	var type_temp=do_ajax('getSytleData','t=3');	//更新数据
	opt='';
	for(var i=0;i<type_temp.data.length;i++){
		opt += '<input type="radio" name="fk_type_id" value="'+type_temp.data[i]['id']+'" >'+type_temp.data[i]['name'];
		eval("ss_type_array["+type_temp.data[i]['id']+"]=type_temp.data[i]['name'];");
	}
	$("#type_id").empty().formhtml(opt);				 
	sytle_show();		
	
	//修改
	getdom().find('#q_edit_save').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#subject#edit'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		if(getdom().find('#ebodys #s_title').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "")){}else{alert('问题标题未填写');return false;}
		if(getdom().find('input:radio[name="fk_type_id"]:checked').val()){}else	{alert('问题类型未选择');return false;}				
		if(getdom().find('#ebodys input:radio[name="s_type"]:checked').val()){}else	{alert('填写方式未选择');return false;}
		if(getdom().find('#ebodys #s_order').val()){if(isNum(getdom().find('#ebodys #s_order'))){}else{alert('排序号请填写自然数');return false;}}else{alert('排序号未填写');return false;}
	
		var temp_val="t=2&chk_limit="+getdom().find('#ebodys #chk_limit').val()+"&fk_quest_id="+getdom().find('#ebodys #fk_quest_id').val()+"&s_title="+getdom().find('#ebodys #s_title').val().replace(/\&/g,"%24")+"&title_id="+getdom().find('#ebodys #title_id').val()+"&s_type="+getdom().find('input:radio[name="s_type"]:checked').val()+"&fk_type_id="+getdom().find('input:radio[name="fk_type_id"]:checked').val()+"&s_url="+getdom().find('#ebodys #s_url').val()+"&s_order="+getdom().find('#ebodys #s_order').val()+"&q_subjesct_id="+getdom().find('#ebodys #q_subjesct_id').val()+"&q_remark="+getdom().find('#ebodys #q_remark').val().replace(/\&/g,"%24");
		do_ajax('updata',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		parent.box_close();
	});
	
	$('#sytle_add').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#subject#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		var m_v=order_show();		
		if(!m_v.data[0]['m_title']) m_v.data[0]['m_title']=1;
		if(!m_v.data[0]['m_order']) m_v.data[0]['m_order']=1;
		$('#ebody #pop_title').html('新增题目');
		$('#ebody #title_id').val(m_v.data[0]['m_title']);
		$('#ebody #s_title').val('');
		$('#ebody #fk_type_id').val('');
        $('#ebody #chk_limit').val(0);
		$('#ebody #s_order').val(m_v.data[0]['m_order']);
		$('#ebody #s_url').val(''); 	 
		$('input[name=s_type][value=0]').attr("checked",true);
		$('#ebody #q_remark').val(''); 	 
		$('#ebody #q_subjesct_id').val('');
		$("#save_tag").empty().formhtml('<input id="q_add_save" onclick="mainFrame.q_add_save();" class="isearcher_submit_button" type="button" value="保 存"/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="q_back" class="isearcher_submit_button" type="button" value="取 消"/>');
		parent.asyncbox.open({
			title  : '',
			id: 'ebodys',
			html:$("#edit").formhtml()
		});	
		parent.upfile("#ebodys #c_url","#ebodys #s_url_name","#ebodys #s_url","#ebodys #s_url_name");
	});
	
	//查询
	$('#search_item_btn').live('click',function(){	
		var temp=do_ajax('getData','t=2&s_title='+$('#search_item_input').val()+'&fk_quest_id='+$('#querybox #fk_quest_id').val()); 
		showInfo(temp,'');	
	});
	
	getdom().find('#search_subjectbtn').live('click',function(){	
		if(getdom().find('#sbodys #search_subjects_input').val()){
			var temp=do_ajax('getData','t=3&s_answer='+getdom().find('#sbodys #search_subjects_input').val()+'&fk_subject_id='+getdom().find("#span_sub #fk_subject_id").val());
			subjectShow(temp);
		}
	});
	
	//简答保存	
	getdom().find('#sbodys #short_edit_save').live('click',function(){	
		<?php $right=$partMdl->Partvalidate('quest#subject#edit'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		if(getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24").replace(/(^\s*)|(\s*$)/g, "")){}else	{alert('标题未填写');return false;}
		if(getdom().find('#sbodys #s_order').val()){}else	{alert('排序号未填写');return false;}
		if(getdom().find('#sbodys #s_value').val()){}else	{alert('分值未填写');return false;}
		if(getdom().find('#sbodys input:radio[name="s_type"]:checked').val()){}else	{alert('值类型未选择');return false;}				
		var temp_val="t=4&s_answer="+getdom().find('#sbodys #s_answer').val().replace(/\&/g,"%24")+"&s_order="+getdom().find('#sbodys #s_order').val()+"&s_type="+getdom().find('#sbodys input:radio[name="s_type"]:checked').val()+"&s_order="+getdom().find('#sbodys #s_order').val()+"&s_len="+getdom().find('#sbodys #s_len').val()+"&s_value="+getdom().find('#sbodys #s_value').val()+"&fk_subject_id="+getdom().find('#sbodys #fk_subject_id').val();
		do_ajax('updata',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		parent.box_close();
	});
	
	//#######选项添加######
	getdom().find('#subject_add').live('click',function(){
		var s_title = $('#querybox_sub #subject_add').attr('data');
		edit_Set('', s_title);
	});
	
	getdom().find('#short_edit_back').live('click',function(){	
		parent.box_close();	
	});
	
	getdom().find('#up_btn').live('click',function(e){
		do_upload();
		e.preventDefault();
		return false;
	});
	
	$('#sel_item_btn').live('click',function(){	
		preview($('#fk_quest_id').val());
	});
	
	getdom().find('#q_back').live('click',function(){
		parent.box_close();
	});
});

//删除问题数据
function del(t,conditions,pageno){
	<?php $right=$partMdl->Partvalidate('quest#subject#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	if(confirm('确定删除数据吗？')){
		var val=t+'&'+conditions+'&'+pageno;
		do_ajax('del',val);
	}
	subject_show();
}

//删除
$('#all_del').live('click',function(){
	var v=inputValue($('input[id="Sid"]')); 
	<?php $right=$partMdl->Partvalidate('quest#subject#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>	
	if(v){
		if(confirm('确定批量删除数据吗？')){				
			var temp_val="t=2&ids="+v;
			do_ajax('delall',temp_val);	//更新数据
			sytle_show();
		}
	}else{
		alert('请选择要删除的项目');
	}
});

//预览功能
function preview(id){
	parent.asyncboxPreview('问卷预览','preview','<?php echo SITE_ROOT;?>Dj/start/wjid/'+id+'?dj_no=1');	
}
</script>
<?php
include_once(TPL_DIR.'common/footer.php');
?>

