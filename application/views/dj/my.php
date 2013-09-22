<?php 
//*定义数据
include_once(TPL_DIR.'common/header.php');
?>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>lab.css?v=<?php echo SYS_VERSION;?>"/>
<style type="text/css">#info, #sort {display: none;background: #FFFFFF;}#info ol li, #sort ol li {height: 25px;line-height: 25px;text-align: center;width: 100px;cursor: pointer;}#info ol li:hover, #sort ol li:hover background: #E8E8E8;}.examine {float: right;}.examine li {float: left;}</style>
<div id="querybox" class="isearcher">
    <table width="100%" border="0" style="height:42px;line-height:30px">
        <tr>
            <td>条件搜索：
                <input id="search_item_input"  size="15"   type="text" class="isearcher_input_words"/>
                <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索" />
            </td>
            <td align="right">
                <div>
                    <ul class="examine" style="position:relative;">
                    	<li><a  title="" target="_blank" onclick="fun();return false;">查看</a></li>
                    	<li><a title="" onclick="fun2();return false;">排序方式</a></li>
                        <select id="selid" name='selid' class="isearcher_select_list" style="display:none;">
                            <option value="0">所有</option>
                            <option value="1">我的</option>
                        </select>
                        <select id="my_orders" name='my_orders' class="isearcher_select_list" style="display:none;">
                            <option value="dj_start_time">答卷时间</option>
                            <option value="dj_zf">得分</option>
                            <option value="dj_pm">排名</option>
                        </select>
                        <input type="hidden" id="userid" name="userid" value="<?php echo $userid?>" />
                        <div id="info" style="width:100px;border:1px solid #9CA1A6;height:50px;position:absolute;left:0px;top:30px;">
                            <ol>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ol>
                        </div>
                        <div id="sort" style="width:100px;border:1px solid #9CA1A6;height:75px;position:absolute;left:60px;top:30px;">
                            <ol>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ol>
                        </div>
                    </ul>
                </div>
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
var actionPhp="<?php echo SITE_ROOT.'/Dj/';?>";
var page='my';//定义当前页面
var userID='<?php echo cookie::get('userid');?>';
var urk='<?php echo SITE_ROOT; ?>';
var rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>';

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
	var orders=$('#my_orders').val();
	var key='t=1&status=1&orders='+orders;
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

var selId = document.getElementById("selid");
var hidden = document.getElementById("my_orders");
option_elems = selId.getElementsByTagName('option');
option_elems2 = hidden.getElementsByTagName('option');
var img_1 = document.getElementById("img_1");
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
		option_elems[ this.index ].selected=true;
		sytle_show();
		info.style.display = "none";
	}
}

function fun(){
	info.style.display = "block";
	sortinfo.style.display = "none";
}
function fun2(){
	sortinfo.style.display = "block";
	info.style.display = "none";
}
</script>
</body>
</html>