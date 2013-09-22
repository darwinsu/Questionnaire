<?php
$target_name='草稿';
include(TPL_DIR.'common/header.php');
?>
<link href="<?php echo VIEW_CSS_URL;?>lab.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />

<style>
/*回收站样式*/
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

.quest_control .right .copydj,.deletedj {
    color: #005590;
    cursor:hand;
    cursor:pointer;
}

.quest_control .left .pz, .wjkk, .wjyl{
    color: #005590;
    cursor:hand;
    cursor:pointer;
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
    <div id="querybox" class="isearcher" style="padding-top:17px; padding-bottom:5px;">
    <!--条件搜索：
    	问卷标题<input id="search_item_input"  class="isearcher_input_words" type="text"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/>-->
		<input id="send_add" class="isearcher_submit_button" type="button" value="恢复"/>
        <input id="send_del" class="isearcher_submit_button" type="button" value="删除"/>
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
<!--修改名称-->
    <table align="center"  cellpadding="4" cellspacing="1" class="tableborder" style="width:777px; clear:right;">
		  <tbody id="ebody"> <tr>
				<td rowspan="4"  class="tr_nav">常规设置</td>
                <td>问卷标题<span class="fontred">*</span></td>
				<td><textarea name="q_title" id="q_title" cols="60" rows="5"> </textarea><br>(限制100字)</td>
             </tr> <tr>
                <td>起止日期<span class="fontred">*</span></td>
				<td><input id="q_start" type="text" name="q_start" value="" onClick="WdatePicker()" readonly/>--<input id="q_end" type="text" name="q_end" value="" onClick="WdatePicker()" readonly/></td>
             </tr> <tr>
                <td>问卷分类<span class="fontred">*</span></td>
				<td><select id="fk_sytle_id" name="fk_sytle_id" class="isearcher_select_list">
        		</select></td>
             </tr> <tr>
                <td>问卷类型</td>
				<td id='type_id'>&nbsp;</td>
             </tr> <tr>
				<td rowspan="5"  class="tr_nav">条件设置</td>
                <td>是否需要密码</td>
				<td><input type="radio" name="pass_type" value="1" >是 <input id="pass" type="password" name="pass" value="" /><input id="showpass" type="checkbox" name="showpass" value="1" />密码明文显示<input type="radio" name="pass_type" value="0" >否</td>
             </tr> <tr>
                <td>是否需要登录</td>
				<td><input type="radio" name="q_login" value="1" >是<input type="radio" name="q_login" value="0" >否</td>
             </tr><tr>
                <td>选择参与人员</td>
                <td><a href="#">选择</a></td>
             </tr><tr>
                <td>是否匿名</td>
				<td><input type="radio" name="q_anonymous" value="1" >是<input type="radio" name="q_anonymous" value="0" >否</td>
             </tr> <tr>
                <td>是否重复作答</td>
				<td><input type="radio" name="q_repeat" value="1" >是<input type="radio" name="q_repeat" value="0" >否</td>
             </tr> <tr>
                <td>答卷限制时间</td>
				<td><input id="duration" type="text" size="3" name="duration" value="" />(分钟)“考试”类型有效，如果填0则不限制。<input id="quest_id" type="hidden"  name="quest_id" value="" /></td>	
			</tr> <tr>
				<td colspan="3" id="save_tag"><input id="q_add_save" class="isearcher_submit_button" type="button" value="增 加"/>&nbsp;&nbsp;<input id="q_back" class="isearcher_submit_button" type="button" value="返 回"/> </td>
			</tr>		  	  
		  </tbody>
		  </tbody>
          <tfoot id="efoot">
          </tfoot>
      </table>
    </div>
	<div id="sedit" style="display:none">
		<table class="tableClass" >
          <thead id="sbody">
		  </thead>
		 </table> 
	</div>
	<div id="desc_edits" style="display:none">
		<table class="tableClass" >
          <thead id="dbody">
		  	<tr>
                <td colspan="2"><input type="radio" id="descs" name="descs" value="1" >编辑顶部描述 <input type="radio"  id="descs" name="descs" value="2" >编辑结束标语</td>
             </tr> 
		  	 <tr id='desc_top'>
                <td>编辑顶部描述</td>
				<td><textarea name="q_top_desc" id="q_top_desc"  class="xheditor" cols="100" rows="10"> </textarea><br></td>
             </tr> 
			 <tr  id='desc_foot' style="display:none">
                <td>编辑结束标语</td>
				<td><textarea name="q_foot_desc" id="q_foot_desc"  class="xheditor" cols="100" rows="10"> </textarea><br></td>
             </tr> 
			 <tr>
				<td colspan="2" align="center"><input id="quest_id" type="hidden"  name="quest_id" value="" /><input id="desc_add_save" class="isearcher_submit_button" type="button" value="修 改"/>&nbsp;&nbsp;<input id="q_back_out" class="isearcher_submit_button" type="button" value="返 回"/> <br /><span class="fontred">*</span>修改后记得保存</td>
			</tr>	
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
var page='recycle';//定义当前页面
var sytle_array=new Array();//分类
var type_array=new Array();//类型		
var userID='<?php echo cookie::get('userid');?>';
var rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>';
function sytle_show(){
	var temp=do_ajax('getData','t=1&status=0'); 
	showInfo(temp,'');		
}
$(document).ready(function(e) {
	//问卷类型
	type_temp=do_ajax('getSytleData','t=2');	//更新数据
	opt='';
	 for(var i=0;i<type_temp.data.length;i++){
		opt += '<input type="radio" name="fk_type_id" value="'+type_temp.data[i]['id']+'" >'+type_temp.data[i]['name'];
		eval("type_array["+type_temp.data[i]['id']+"]=type_temp.data[i]['name'];")
	 }
	 $("#type_id").empty().formhtml(opt);	
	 
		sytle_show();		
	  //修改

	getdom().find('#q_back').live('click',function(){	
		parent.box_close();	
	});
	$('#q_back_out').live('click',function(){	
		$('#desc_edits').hide();
		$('#out_context').show();
	});

	//查询
	$('#search_item_btn').live('click',function(){
		 var temp=do_ajax('getData','t=1&status=0&q_title='+$('#search_item_input').val()); 
		 showInfo(temp,'');	
	});
	//恢复草稿
	$('#send_add').live('click',function(){
	<?php $right=$partMdl->Partvalidate('quest#recycle#send'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		 var v=inputValue($('input[id="Sid"]')); 
		 if(v){
		 var temp_val="t=6&status=1&ids="+v;
		 do_ajax('updata',temp_val);	//更新数据
		 var temp=do_ajax('getData','t=1&status=0&q_title='+$('#search_item_input').val()); 
		 showInfo(temp,'');	
		 }else{alert('请选择要恢复的问卷');}
	});


	//彻底批量删除
	$('#send_del').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#recycle#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		var v=inputValue($('input[id="Sid"]')); 
		if(v){
		if(confirm('确定永久删除数据吗？')){
		 var temp_val="t=1&ids="+v;
		 do_ajax('delall',temp_val);	//更新数据
		 sytle_show();
		 }
		}else{alert('请选择要删除的项目');}
	});	
});
//删除问题数据
function del(t,conditions,pageno){
	if(confirm('确定永久删除数据吗？')){
	<?php $right=$partMdl->Partvalidate('quest#recycle#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		var val=t+'&'+conditions+'&'+pageno;
		do_ajax('del',val);
		sytle_show();
	}
}		
</script>
<?php include(TPL_DIR.'common/footer.php');?>

