<?php
//*定义数据
$target_name='产品管理';
include_once(TPL_DIR.'common/header.php');
?>
<div class="xbtn" >
    </div>
<!--快速添加-->
<div id="search" class="search_l">
    <div class="top_item">快速添加</div>
    <div class="main_item">
        <span class="item">新产品名称：</span>
        <span class="space"><input  type="text" maxlength="30" class="n_p input" /></span>
        <div class="item"><span class="left" style="padding-top:3px;">负责人：</span>
        	<div class="left" style="width:110px; position:relative;">
        		<input class="input" id="search_text" style="width:100px" />
                <span class="icon icon_triangle-1-s search_btn"></span>
            </div>
        </div>
        <div class="m_list" >
        	<ul>
            </ul>
        </div>
         <div style="padding-left:45px;"><div class="m_add">添加</div></div>

    </div>
</div>
<!--内容-->
<div class="outer">
	<!--导航-->
	<div id="featurebar"></div>
    <!--搜索栏-->
    <div id="querybox" class="isearcher">
    条件搜索：
    	<select id="search_item" class="isearcher_select_list">
            <option value="product_name">请输入相关产品名称</option>
            <option value="app_id">产品ID</option>
            <option value="account_info">开放权限账号</option>
        </select>
        <input id="search_item_input" class="isearcher_input_words" type="text"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/>
        <div id="iscp_iresult" class="isearcher_instant_result">
        <ul id="iscp_iresult_list"> </ul>
        </div>
        <font class="separator">|</font>
         查看已删除数据：
         <button id="clear_list" class="btn btn-success" style="margin-left: 0px;">查看</button>
    </div>
    <!--内容展示-->
    <style type="text/css">
.tableborder { 
border: 0px none currentColor !important;
border-collapse: separate !important;
empty-cells: show;
margin-bottom: 10px;
background: url("http://192.168.6.126/control/lztg/templates/admin/images/main-left.gif") 0% 0% repeat-y #FFFFFF;
padding-left:5px;
}
.tr_nav { 
background: #c7e0fe;
border-bottom: 1px solid #DDE9F5;
color: #008800;
}
.tableborder td { 
border-bottom: 1px solid #DDE9F5;
border-right: 1px solid #F5FCFF;
line-height: 2em;
padding: 5px 10px;
}
.isearcher { 
border-bottom: 1px solid #DDE9F5;
border-left: medium none currentColor;
border-right: medium none currentColor;
border-top: 1px solid #DDE9F5;
padding: 3px 6px;
}
.isearcher .isearcher_input_words { 
width: 200px;
}
.isearcher .isearcher_submit_button { 
background: #336699;
color: #FFFFFF;
font-weight: 700;
padding: 2px 3px;
}
.isearcher .isearcher_instant_result { 
background: #FFFFFF;
border: 1px solid #CCCCCC;
display: none;
position: absolute;
width: 206px;
}
.isearcher .separator { 
background: #CCCCCC;
color: #CCCCCC;
margin: 0px 5px;
padding: 3px;
}
	</style>
    <table align="center"  cellpadding="4" cellspacing="1" class="tableborder" style="width:950px; clear:right">
          <thead>
			<tr class="tr_nav">
				<td>产品ID</td>
                <td>产品名称</td>
                <td>开放权限账号</td>
                <td>修改</td>
                <td>删除</td>
             </tr>
		  </thead>
		  <tbody id="tbody">
		  </tbody>
          <tfoot id="tfoot">
          </tfoot>
      </table>
</div>
    <div id="test" style="display:none;">
    
                    <div  style=" width:98%; padding: 20px 25px 5px;">
                        <div id="selected" class="left">
                            <span class="item">新增负责人：</span>
                            <div class="m_list" >
                                <ul></ul>
                            </div>
                        </div>
                        <div id="list" class="left">
                            <span class="item">负责人列表：</span>
                            <div class="m_list">
                                <span> <input type="text" class="search_input" maxlength="30" /></span>
                                <ul>
                                
                                </ul>
                            
                            </div>
                        </div>
                        <div class="left" style="width:130px; padding-top:100px;">
                                <button id="m_selected" class="btn btn-info">选定</button>
                                <button id="add_m" class="btn btn-success" style="margin-top: 15px;">新增负责人</button>
                            </div>
                    </div>
                    <div class="noty_bar"  style="background:#E7E7E7;margin: 8px 0px;padding: 10px; text-align:left; display:none;clear:both">
                            <label for="name" style="">姓名：</label><input  type="text" maxlength="30" name="name" id="add_name" style=" margin:5px;width:120px; height:20px;"/>
                            <label for="user_id">账号：</label><input type="text" maxlength="30" name="user_id" id="add_uid" style="margin:5px;width:120px; height:20px;"/>
                            <button id="add_m" class="btn btn-success" style="margin-left: 0px;">新增负责人</button>
                            <button id="cancle_m" class="btn btn-danger" style="margin-left: 5px;">取消</button>
                    </div>
    </div>
<!--编辑-->
   <div id="edit" style="display:none;">
                     <div  style="background:#E7E7E7;padding: 10px; text-align:left;clear:both; position:relative;">
                         <span>产品名称：<font id="product_name" style="color:#F33;"></font></span>
                         <input id="p_n_edit_input" type="text" maxlength="32" title="产品名称" style=" width:335px;height:24px; display:none;"  />
                         <div style="position:absolute;right:65px;top:9px;display:none;"><button id="p_n_edit_s" class="btn btn-info" >使用</button></div> <div style="position:absolute;right:40px;top:4px;"><button id="p_n_edit" class="btn btn-info" >编辑产品名称</button></div>
                    </div>
                    <div  style=" width:98%; padding: 20px 25px 5px;">
                        <div id="selected" class="left">
                            <span class="item">开放权限账号：</span>
                            <div class="m_list" >
                                <ul></ul>
                            </div>
                        </div>
                       
                        <div id="list" class="left">
                            <span class="item">负责人列表：</span>
                            <div class="m_list">
                                <span> <input type="text" class="search_input" maxlength="30" /></span>
                                <ul>
                                
                                </ul>
                            
                            </div>
                        </div>
                        <div class="left" style="width:130px; padding-top:100px;">
                                <button id="m_selected" class="btn btn-info">选定</button>
                                <button id="add_m" class="btn btn-success" style="margin-top: 15px;">新增负责人</button>
                        </div>
                    </div>
                    <div class="noty_bar"  style="background:#E7E7E7;margin: 8px 0px;padding: 10px; text-align:left; display:none;clear:both">
                            <label for="name" style="">姓名：</label><input  type="text" maxlength="30" name="name" id="add_name" style=" margin:5px;width:120px; height:20px;"/>
                            <label for="user_id">账号：</label><input type="text" maxlength="30" name="user_id" id="add_uid" style="margin:5px;width:120px; height:20px;"/>
                            <button id="add_m" class="btn btn-success" style="margin-left: 0px;">新增负责人</button>
                            <button id="cancle_m" class="btn btn-danger" style="margin-left: 5px;">取消</button>
                    </div>
    </div>
</div>
<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/competence.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript">
var actionPhp="<?php echo SITE_ROOT.'/Competence/';?>";
var availableTags =new Array();//定义自动完成提示内容
var temp_name=new Array();//定义所有负责人array;
var temp_uid=new Array();//定义所有负责人对应uid
var page='index';//定义当前页面
$(document).ready(function(e) {
	
	show_info('t=1');//显示列表文件信息
	
	var temp_val='';//所选负责人
		var temp='';
	var temp_json=do_ajax('get_availableTags','t=1').data;//获取自动完成内容
	if(temp_json){
		for(var i=0;i<temp_json.length;i++)
		{
			availableTags.push(temp_json[i].account_name+"("+temp_json[i].account_id+")");
			temp_name.push(temp_json[i].account_name);
			temp_uid.push(temp_json[i].account_id);
		}
	}
	//新产品名称ajax判断
	$(".n_p").bind('change',function(){
		if(check_np()){
			$(".n_p").addClass("error");
		}else{
			$(".n_p").removeClass("error");
		}
	});
	
	function check_np(){
		var callback=do_ajax('getByName','n_p='+$(".n_p").val());
		if(callback.result){
			return true;
		}else{
			return false;
		}
	}
	//自动完成	
	//li事件处理
	li_action('#search .m_list ul li','','');//负责人
	//
	//新产品权限添加到数据库-负责人判断
	 auto_complete('#search_text',availableTags,'#search .m_list ul','');
	  // auto_complete('#list .search_input',availableTags,'#list .m_list ul','');

	$('.m_add').bind('click',function(){		
		if($('#search  .m_list li').length==0){
			$('#search  .m_list ul').addClass('error');
		}else{
			$('#search  .m_list ul').removeClass('error');
		}
		
		if($('.error').length>0||$(".n_p").val()=='[暂未使用]'||check_np()){
				$('body').artDialog({
					title:'提示',
				  content:(($('.n_p').val()=='')?"请输入新产品名称!":($('.error').length>1)?'请选择负责人!':'您填写的新产品名称已被使用,请重新填写！'),
				   art_class :'tt'
				});
				return false;
		}
		for(var i=0;i<$('#search .m_list li').length;i++){
			temp_val+=$('#search .m_list li').eq(i).text();
			if($('#search .m_list li').length>1&&i<$('#search .m_list li').length-1){
				temp_val+=',';
			}
		}
		p_add(temp_val);
		temp_val='';
	});	
	
	//弹出框 
	$(".search_btn").live('click',function(){
		
		if($("#search .m_list ul").attr('class')=='error'){//去除错误
			$("#search .m_list ul").removeClass('error');
		}
		$("#test #selected .m_list").formhtml($("#search .m_list").formhtml());//附值
		//进行已有数据判断,并对选择框数据进行删除
		var selected_param=new Array();
		for(var i=0;i<$("#search .m_list ul li").length;i++){
			selected_param.push($("#search .m_list ul li").eq(i).text());
		}
		//end
			//负责人列表
	 m_list(availableTags,selected_param,'#test ');		
			$('body').artDialog({
				title  :'负责人添加/删除',
			   content :'#test',
				append :true,
				mask   :true,
				art_class :'test'//用来限制点击生成artDialo的个数为1！此为class="art_test"
			});
	 
	});
	
	//事件定义
		//查看已删除数据
			$('#clear_list').live('click',function(){
				show_clear_list('t=1&state=del');
			});
		//搜索
			$('#search_item_btn').live('click',function(){
				if($('#search_item_input').val()==''){
					 show_info('t=1');//显示列表文件信息
					return false;
				}
				var conditions='t=1&'+$('#search_item').val()+'='+$('#search_item_input').val()+'';
				var temp_data=do_ajax('getData',conditions);
				if(temp_data.result){
					showInfo(temp_data);
				}else{
					alert(temp_data.data);
				}
			})
		//end
	
		//快速添加
		live_fn('.art_test ');
		//end
		//编辑
		live_fn('.art_edit ');
		
		$('#p_n_edit').live('click',function(){
			$(this).parent().hide();
			$('.art_edit #p_n_edit_s').parent().show();
			$('.art_edit #product_name').hide();
			$('.art_edit #p_n_edit_input').val($('.art_edit #product_name').text()).show();
		});
		$('#p_n_edit_s').live('click',function(){
			var id=$('.art_edit tbody').attr('class');
			var temp_id=id.substr(3);
			var np=$('.art_edit #p_n_edit_input').val();
			var val='id='+temp_id+'&name='+np;
			if($('.art_edit #product_name').text()!=np){//如果有做修改则进行数据库判断
				var callback=do_ajax('getByName','n_p='+np);//判断是否已经存在此命名
				if(callback.result==true){alert('您的产品命名已经存在，请重新填写！');return ;}
				var temp_data=do_ajax('p_updata',val);
				if(!temp_data){
					alert('修改失败，请重试!');
				}
			}
			$(this).parent().hide();
			$('#p_n_edit').parent().show();
			$('.art_edit #p_n_edit_input').hide();
			$('#tbody').find('.'+id).find('td').eq(1).text($('.art_edit #p_n_edit_input').val());
			$('.art_edit #product_name').text($('.art_edit #p_n_edit_input').val()).show();
		});
		
	//end
	function live_fn(target){
		li_action('#list .m_list ul li','selected',target);//弹出框
		li_action('#selected .m_list ul li','back_list',target);//弹出框
		//新增负责人事件
		$(target+"#add_m").live('click',function(){
			if($(target+'.noty_bar').css('display')=='none'){
				$(target+'.noty_bar').slideDown('fast');
			}else{
				add_m(target+'.noty_bar',' #add_name',' #add_uid' );//新增负责人
			}
		});
		$(target+"#add_name").change(function(){
			do_next(target+'#add_name',temp_name);
		});
		$(target+"#add_uid").change(function(){
			do_next(target+'#add_uid',temp_uid);
		});
		//新增负责人取消
		$(target+"#cancle_m").live('click',function(){
			$(target+'.noty_bar').removeClass('focus_now').slideUp('fast').find(target+"#add_name").val('').end().find(target+'#add_uid').val('').end();
		});
		//选定
		$(target+'#m_selected').live('click',function(){
			var target=$(this).closest('#art_dialog');
			var class_val=target.attr('class');
			var temp=class_val.split(" ");
			if($.inArray('art_test',temp)>-1){//新增用户
				$("#search .m_list").formhtml(target.find("#selected .m_list").formhtml());
			}else{//编辑用户
				var li_num=$(".art_edit #selected .m_list ul li").length;
				if(li_num>0){
					var name=$('.art_edit #product_name').text()?'name='+$('.art_edit #product_name').text():'';
					var id='id='+$('.art_edit tbody').attr('class').substr(3);
					var m_data='';
					for(var i=0;i<li_num;i++){
						m_data+=$(".art_edit #selected .m_list ul li").eq(i).text();
						if(li_num>1&&i<li_num-1){
							m_data+=',';
						}
					}
					var temp_data=id+'&'+name+"&managers="+m_data;
					var temp_val=do_ajax('p_updata',temp_data);
					if(temp_val.result){//修改成功
						$('#tbody .'+$('.art_edit tbody').attr('class')).find('td :eq(2)').formhtml(m_data);//修改成功
					}else{
						alert(temp_val.msg);
					}
				}else{
					alert('您至少使用一个开放权限的账号!');
					e.preventDefault();
					return false;
				}
			}
			target.find('.aui_close').trigger('click');
		});
	}
});
</script>
<?php
include_once(TPL_DIR.'common/footer.php');
?>

