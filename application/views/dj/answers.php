<?php 
//*定义数据
include_once(TPL_DIR.'common/header.php');
?>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>lab.css?v=<?php echo SYS_VERSION;?>"/>
<div id="querybox" class="isearcher">
    <table width="100%" border="0" style="height:43px;line-height:43px">
        <tr>
            <td>条件搜索：
                <input id="search_item_input"  size="15"   type="text" class="isearcher_input_words" style="vertical-align:middle"/>
                <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索" style="vertical-align:middle"/>
            </td>
        </tr>
    </table>
</div>

<table class="tableClass">
    <thead>
    <tr>
        <th width="35%" align="center">问卷标题</th>
        <th width="10%" align="center">答卷人</th>
        <th width="20%" align="center">答题时间</th>
        <th width="10%" align="center">答题用时(秒)</th>
        <th width="10%" align="center">得分</th>
        <th width="10%" align="center">排名</th>
        <?php if(in_array('1',$jsbhlist)||in_array('3',$jsbhlist)){?>
        <th width="5%" align="center">操作</th>
        <?php }?>
	</tr>
	</thead>
	<tbody id="tbody">
    <?php
    if(!empty($djlist))
    {
    foreach ($djlist as $dj) { ?>
    <tr>
        <td><a href="<?php echo SITE_ROOT; ?>Dj/djinfo/djid/<?php echo $dj->getDjid() ?>/"><?php echo $dj->getObjWj()->getTitle();?></a></td>
        <td><?php echo $dj->getDjStartTime();?></td>
        <td><?php echo $dj->getDjStartTime();?></td>
        <td><?php echo $dj->getDjOverTime();?></td>
        <td><?php echo $dj->getDjTimeConsuming();?></td>
    </tr>
    <?php }
    } ?>
    </tbody>
    <tfoot id="tfoot">
    </tfoot>
</table>
<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/dj.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript">
var actionPhp="<?php echo SITE_ROOT.'/Dj/';?>",
page='my',//定义当前页面
userID='<?php echo cookie::get('userid');?>',
urk='<?php echo SITE_ROOT; ?>',
rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>',
wjid = '<?php echo $wjid;?>';
$(document).ready(function(e) {
	//查询
	$('#search_item_btn').live('click',function(){	
		sytle_show();
	});
	sytle_show();	
});

function sytle_show(){
	var wj_title=$('#search_item_input').val();
	var u_id=$('#selid').val();
	
	var key='t=1&status=1&wjid='+wjid;
	if(u_id=='0'){
		<?php if(in_array('1',$jsbhlist)||in_array('2',$jsbhlist)){}else{?>	
		key=key+'&uid='+userID;
		<?php } ?>	 
	}
	if(rights=='3') key=key+'&wjids='+userID;
	if(u_id=='1') key=key+'&uid='+userID;
	if(u_id=='2') key=key+'&state=1';
	if(u_id=='3') key=key+'&state=2';
	if(wj_title) key=key+'&q_title='+wj_title;
	var temp=do_ajax('getData',key);  
	showInfo(temp,'');		
}

//删除问题数据
function del(t,conditions,pageno){
	if(confirm('确定删除数据吗？')){
		var val=t+'&'+conditions+'&'+pageno;
		do_ajax('del',val);
		sytle_show();
	}	
}

var img_1 = document.getElementById("img_1");
var img_2 = document.getElementById("img_2");
</script>
</body>
</html>