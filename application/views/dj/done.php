<?php include(TPL_DIR.'common/header.php');?>
<div class="outer" id='out_context'>
	<div id="featurebar"></div>
    <div id="querybox" class="isearcher">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			<tr>
				<td align="left" style="padding-top: 5px; height: 39px;">
					我的答卷 >> 已回答
                    <!--问卷标题: <input id="search_item_input"  class="isearcher_input_words" type="text"/>
					<input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/>-->
				</td>
			</tr>
		</table>
    </div>
    <!--内容展示区-->
	<table class="tableClass" style="vertical-align: top; padding-top: 5px; height: 39px;">
		<thead>
			<tr id="tthead" >
				<th width="45%">问卷标题</th>
				<th width="15%">答卷时间</th>
				<th width="10%">问卷分类</th>
				<th width="10%">创建者</th>
				<th width="10%">答卷数</th>
                <th width="10%">操作</th>
			</tr>
		</thead>
	<tbody id="tbody"></tbody>
	<tfoot id="tfoot"></tfoot>
	</table>
</div>

<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" language="javascript">
var baseUrl = '<?php echo SITE_ROOT;?>',
actionPhp= baseUrl+'Quest/',
type_array=new Array();
$(document).ready(function(e) {
	var type_data=do_ajax('getSytleData','t=2&perpages=999999');
	for(var i=0;i<type_data.data.length;i++){
		eval("type_array["+type_data.data[i]['id']+"]=type_data.data[i]['name'];")
	}
	
    var url = baseUrl+'Dj/listdata?zd=1';
	getPage(1, url);
});

//刷页面
function getPage(isajax, url, a){
	if(isajax == '1'){
		var now = new Date().getTime(),
		keyword = $.trim($('#search_item_input').val()),
		conditions = keyword ? '&q_title='+keyword : '';
		url += "?v="+now+conditions;
		
		$.get(url, function(data){
			data =$.parseJSON(data);
			showInfo(data);
		});
	}else{
		window.location.href = url;
	}
}

function showInfo(val){
	var tbody='';
	var tfoot='';
	
	$("#tthead").show();
	if(val.result==false){
		$("#tbody").empty().formhtml('<tr><td colspan="6">暂无数据</td></tr>');
		$("#tfoot").empty().formhtml('<td colspan="6">'+val.menu+'</td>');
		return false;
	}
	
	for(var i=0;i<val.data.length;i++){
		tbody+='<tr class="pm_'+val.data[i]['id']+'">';
		tbody+='<td><span class="wj_t">'+val.data[i]['q_title']+'</span></td>';
		
		tbody +=
			'<td>'+getLocalTime(val.data[i]['q_start'])+'</td>'
			+'<td>'+eval("type_array["+val.data[i]['fk_type_id']+"]")+'</td>'
			+'<td>'+val.data[i]['c_userid']+'</td>'
			+'<td>'+val.data[i]['dj_num']+'</td>'
			+'<td><a href="'+baseUrl+'Dj/djinfo/djid/'+val.data[i]['djid']+'/" target="_blank">查阅</a></td>';
		
		tbody+='</tr>';
	}
	
	tfoot += '<tr><td colspan="6">'+val.menu+'</td></tr>';
	$("#tfoot").empty().formhtml(tfoot);
	$("#tbody").empty().formhtml(tbody);
}
</script>
<?php include(TPL_DIR.'common/footer.php');?>

