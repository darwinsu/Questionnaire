<?php 
$target_name='交叉分析';
include_once(TPL_DIR.'common/header.php');
?>
<link href="<?php echo VIEW_JS_URL;?>uploadify/uploadify.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />
<!--内容-->
<div class="outer">
<span class="blank"></span>
 <div class="Menu">
 <div class="analytical_menu clearfix">
    <a href="<?php echo SITE_ROOT.'report/index/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >常规分析</a>
    <a href="<?php echo SITE_ROOT.'report/condition/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >条件分析</a>
    <a class="active" href="<?php echo SITE_ROOT.'report/cross/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >交叉分析</a>
 </div>
</div>

<div style="text-align:center;border:1px solid #8c8c8c;border-right:1px solid #8c8c8c;border-bottom:none;border-top:none;width:100%;clear:both;height:40px;line-height:40px;background:#c6e1fd;"><?php echo $wj->getTitle(); ?></div>
    <table class="tableClass">     	
		  <thead>
			<tr class="tr_nav" id="tthead" >
				<td>交叉行：</td>
                <td><select id="cross_col" name="cross_col" class="isearcher_select_list"></select></td>
             </tr>
			<tr class="tr_nav" id="tthead" >
				<td>交叉列：</td>
                <td><select id="cross_row" name="cross_row" class="isearcher_select_list"></select></td>
             </tr> 
			 <tr class="tr_nav" id="tthead" >
				<td>&nbsp;</td>
                <td> <input id="report_btn" class="isearcher_submit_button" type="button" value="分 析"/>&nbsp;&nbsp;&nbsp;<input id="short_edit_back" class="isearcher_submit_button" onClick="javascript:location.href='<?php echo SITE_ROOT; ?>Quest?pageno=<?php echo $_GET['pageno'];?>'" type="button" value="返 回"/></td>
             </tr> 
		  </thead> 
      </table>
	  
	  <table class="tableClass">    
		  <tbody id="tbody">
		  </tbody>
          <tfoot id="tfoot">
          </tfoot>
      </table>
</div>

<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/quest.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>uploadify/jquery.uploadify.v2.1.4.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>uploadify/swfobject.js"></script>
<script type="text/javascript">
var actionPhp="<?php echo SITE_ROOT.'/Report/';?>";
var page='cross';//定义当前页面
var ss_type_array=new Array();//类型

function sel_show(id){ 
var temp=do_ajax('getData','t=2&fk_quest_id='+id); 
	show_select(temp,'');
}
function subject_show(){	
	var vals=do_ajax('getData','t=3&fk_subject_id='+$("#fk_subject_id").val());
		subjectShow(vals);
}
function show_select(sytle_temp){
	var opt='';
	$("#cross_col").empty();
	$("#cross_row").empty();	
	for(var i=0;i<sytle_temp.data.length;i++){
		opt = $("<option value='"+sytle_temp.data[i]['id']+"'>"+sytle_temp.data[i]['s_title']+"</option>");
		$("#cross_col").append(opt);				
	 }
	 for(var i=0;i<sytle_temp.data.length;i++){
		opt = $("<option value='"+sytle_temp.data[i]['id']+"'>"+sytle_temp.data[i]['s_title']+"</option>");	
		$("#cross_row").append(opt);				
	 }
}
$(document).ready(function(e) { 
	var sytle_temp=do_ajax('getData','t=1');	//更新数据
	var opt='';
	var vselectd='';
	//题行
	var temp=do_ajax('getData','t=2&fk_quest_id=<?php echo $wj->getWjId();?>'); 
		show_select(temp);		
		//修改
	
	//#######分析######
	$('#report_btn').live('click',function(){	
			if($('#cross_col').val()==$('#cross_row').val()){
			alert('两个分析条件不可相同');
			}else{
		 report_Set($('#cross_col').val(),$('#cross_row').val());
			}
		 
	});
});
function report_Set(colid,rowid){
	var tbody='';
	var tfoot='';
	var coltemp=do_ajax('getData','t=3&fk_subject_id='+colid); 
	var rowtemp=do_ajax('getData','t=3&fk_subject_id='+rowid);
	
	tbody='<tr class="tr_nav"><td>&nbsp;</td>';
	for(var i=0;i<coltemp.data.length;i++){
	tbody+='<th>'+coltemp.data[i]['s_answer']+'</th>';
	}
	tbody+='</tr>';
	for(var j=0;j<rowtemp.data.length;j++){
	tbody+='<tr class="tr_nav"><th>'+rowtemp.data[j]['s_answer']+'</th>';
		for(var i=0;i<coltemp.data.length;i++){
		var djtemp=do_ajax('getDjData','t=1&col_title_id='+colid+'&row_title_id='+rowid+'&col_id='+coltemp.data[i]['id']+'&row_id='+rowtemp.data[j]['id']);
		tbody+='<td>'+djtemp.data+'</td>';
		}
	tbody+='</tr>';	
	}
	$("#tbody").empty().formhtml(tbody);	
}
</script>
<?php
include_once(TPL_DIR.'common/footer.php');
?>

