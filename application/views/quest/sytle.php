<?php
$target_name='问卷分类';
include_once(TPL_DIR.'common/header.php');
?>
<link href="<?php echo VIEW_CSS_URL;?>lab.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />
<body>
<!--内容-->
<div class="outer">
	<!--导航-->
	<div id="featurebar"></div>
    <!--end-->
    <!--搜索栏-->
    <div id="querybox" class="isearcher" style="padding-top:14px; padding-bottom:5px;">
    <!--条件搜索：
    	<input id="search_item_input"  class="isearcher_input_words" type="text"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/>-->
		<?php $partMdl->Partvalidate('quest#style#add'); if($right['state']){?>
		<input id="sytle_add" class="isearcher_submit_button" type="button" value="添 加"/>
		<?php } ?>
        <div id="iscp_iresult" class="isearcher_instant_result">
        	<ul id="iscp_iresult_list"> </ul>
        </div>

    </div>
	<!--end-->
    <!--内容展示-->
    <!-- 引入样式-->
   
    <table class="tableClass" >
          <thead>
			<tr id="tthead" >
				<th width="10%">编号</th>
                <th width="80%">名称</th>
				<th width="10%">操作</th>
             </tr>
		  </thead>
           <!--thead end-->
		  <!--tbody start-->
		  <tbody id="tbody">
		  </tbody>
          <tfoot id="tfoot">
          </tfoot>
      </table>
    <!--end-->

</div>

<div id="edit" style="display:none">
<style type="text/css">
table.add_quest{clear:right;width:400px;margin:0px 20px 5px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest .ctl_btn{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;}table.add_quest input{border:1px solid #97BDE7;height:24px; vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px; border:none;}table.add_quest .isearcher_submit_button#q_back{ background-color:#939393;}
</style>
    <table cellpadding="4" cellspacing="1" class="tableborder add_quest">
        <tbody id="ebody">
        </tbody>
        <tfoot id="efoot">
        </tfoot>
    </table>
</div>

<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/quest.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript">
var actionPhp="<?php echo SITE_ROOT.'Quest/';?>";
var page='sytle';//定义当前页面
var rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>';

function sytle_show(){
	var temp=do_ajax('getSytleData','t=1');
	showInfo(temp,'');
}

$(document).ready(function(e) {
	sytle_show();
	//修改
	$(window.parent.document).find('#q_edit_save').live('click',function(){

	<?php $right=$partMdl->Partvalidate('quest#style#edit'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		if(getdom().find('#ebodys #ebody #name').val().replace(/(^\s*)|(\s*$)/g, "")){}else	{alert('分类名称未填写');return false;}
		var temp_val="t=1&name="+getdom().find('#ebodys #ebody #name').val().replace(/\&/g,"%24")+"&qid="+getdom().find('#qid').val();
		do_ajax('sytle_updata',temp_val);	//更新数据
		temp_val='';
		sytle_show();
		parent.box_close();
		});
	//end
	$('#sytle_add').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#style#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
		add();
	});
	//查询
	$('#search_item_btn').live('click',function(){
		 var temp=do_ajax('getSytleData','t=1&name='+$('#search_item_input').val());
		 showInfo(temp,'');
	});
	//添加
	getdom().find('#q_add_save').live('click',function(){
		<?php $right=$partMdl->Partvalidate('quest#style#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>		if(getdom().find('#ebodys #ebody #name').val().replace(/\+/g,"%2B").replace(/(^\s*)|(\s*$)/g, "") ){}else	{alert('分类名称未填写');return false;}
		var temp_val="t=1&name="+getdom().find('#ebodys #ebody #name').val().replace(/\&/g,"%24");
		do_ajax('addSytle',temp_val);	//更新数据
		temp_val='';
		sytle_show();
		parent.box_close();
		});
	
	getdom().find('#q_back').live('click',function(){
		parent.box_close();
	});
	
});
//删除数据
function delSytle(t,conditions,pageno){
	if(confirm('确定永久删除数据吗？')){
<?php $right=$partMdl->Partvalidate('quest#style#del'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>
	var temp=do_ajax('getData','t=1&fk_sytle_'+conditions); 
	if(temp.data.length>0){alert('该类型问卷已存在不允许删除'); return false;}
	var val=t+'&'+conditions+'&'+pageno;
	do_ajax('delSytle',val);
	sytle_show();
	}
}
</script>
<?php
include_once(TPL_DIR.'common/footer.php');
?>

