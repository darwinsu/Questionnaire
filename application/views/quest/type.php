<?php
$target_name='卷子类型';
include_once(TPL_DIR.'common/header.php');
?>
<link href="<?php echo VIEW_CSS_URL;?>lab.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />
<div class="xbtn" > </div>
<!--内容-->
<div class="outer">
	<!--导航-->
	<div id="featurebar"></div>
    <!--搜索栏-->
    <div id="querybox" class="isearcher">
    条件搜索：
    	名称<input id="search_item_input"  class="isearcher_input_words" type="text"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/>
		<input id="sytle_add" class="isearcher_submit_button" type="button" value="添 加"/>
        <div id="iscp_iresult" class="isearcher_instant_result">
        <ul id="iscp_iresult_list"> </ul>
        </div>
         
    </div>
    <!--内容展示-->
    
    <table align="center"  cellpadding="4" cellspacing="1" class="tableborder" style="width:777px; clear:right;">
          <thead>
			<tr class="tr_nav" id="tthead" >
				<td>编号</td>
                <td>名称</td>
				<td>操作</td>
             </tr>
		  </thead>
		  <tbody id="tbody">
		  </tbody>
          <tfoot id="tfoot">
          </tfoot>
      </table>
</div>

<div id="edit" style="display:none">
<!--修改名称-->
    <table align="center"  cellpadding="4" cellspacing="1" class="tableborder" style="width:777px; clear:right;">
		  <!--tbody start-->
		  <tbody id="ebody">
		  </tbody>
          <tfoot id="efoot">
          </tfoot>
      </table>
    </div>
</div>
<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/quest.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript">
var actionPhp="<?php echo SITE_ROOT.'/Quest/';?>";
var page='type';//定义当前页面
function sytle_show(){
	var temp=do_ajax('getSytleData','t=2'); 
	showInfo(temp,'');		
}
$(document).ready(function(e) {
		sytle_show();		
	  //修改
	$('#q_edit_save').live('click',function(){		
		var temp_val="t=2&name="+$('#ebodys #ebody #name').val()+"&qid="+$('#qid').val();
		do_ajax('sytle_updata',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		$('.asyncbox_close').trigger('click');	
		});	
	//end
	$('#sytle_add').live('click',function(){	
		add();
	});
	//查询
	$('#search_item_btn').live('click',function(){	
		 var temp=do_ajax('getSytleData','t=2&name='+$('#search_item_input').val()); 
		 showInfo(temp,'');	
	});
	//添加	 
	$('#q_add_save').live('click',function(){		
		var temp_val="t=2&&name="+$('#ebodys #ebody #name').val();
		do_ajax('addSytle',temp_val);	//更新数据
		temp_val='';
		sytle_show();			
		$('.asyncbox_close').trigger('click');	
		});
});
//删除数据
	function delSytle(t,conditions,pageno){
		var val=t+'&'+conditions+'&'+pageno;
		do_ajax('delSytle',val);
	}	
</script>
<?php
include_once(TPL_DIR.'common/footer.php');
?>

