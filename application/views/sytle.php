<?php

//*定义数据
$target_name='问卷分类';
include_once(TPL_DIR.'common/header.php');
?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo time();?> "></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/quest.js?v=<?php echo time();?> "></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo time();?> "></script>
<link href="<?php echo VIEW_CSS_URL;?>lab.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
		var actionPhp="<?php echo SITE_ROOT.'/Quest/';?>";
		var page='sytle';//定义当前页面
		var rights='<?php if(in_array('1',$jsbhlist)){}elseif(in_array('2',$jsbhlist)){echo "2";}elseif(in_array('3',$jsbhlist)){echo "3";}else{echo "1";}?>';
		function sytle_show(){
		 var temp=do_ajax('getSytleData','t=1');
		 showInfo(temp,'');
		}
		function getdom(){
			
			return $(window.parent.document);
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
				getdom().find('.asyncbox_close').trigger('click');
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
			$('#q_add_save').live('click',function(){
				<?php $right=$partMdl->Partvalidate('quest#style#add'); if(!$right['state']){ echo "errorMSG('".$right['msg']."');return false;";}?>		if($('#ebodys #ebody #name').val().replace(/\+/g,"%2B").replace(/(^\s*)|(\s*$)/g, "") ){}else	{alert('分类名称未填写');return false;}
				var temp_val="t=1&name="+$('#ebodys #ebody #name').val().replace(/\&/g,"%24");
				do_ajax('addSytle',temp_val);	//更新数据
				temp_val='';
				sytle_show();
				$('.asyncbox_close').trigger('click');
				});
			//end
			
    });
	//删除数据
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
<body>

<!--内容-->
<div class="outer">
	<!--导航-->
	<div id="featurebar"></div>
    <!--end-->
    <!--搜索栏-->
    <div id="querybox" class="isearcher" >
    条件搜索：
    	<input id="search_item_input"  class="isearcher_input_words" type="text"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索"/>
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
				<th>编号</th>
                <th>名称</th>
				<th>操作</th>
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

<!--end-->
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
<!--end-->
</body>

<?php
include_once(TPL_DIR.'common/footer.php');
?>

