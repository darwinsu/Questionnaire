<?php
//*定义数据
$target_name='用户管理';
include(TPL_DIR.'common/header.php');
?>
<link href="<?php echo VIEW_CSS_URL;?>lab.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />
 <style>
 /*新增权重弹出样式*/
.white_content {
    display: none;
    position: absolute;
    left:40%; top:10%;
    border: 1px solid #b6b6b6;
    background:#ffffff;
    width: 306px;
    height: 129px;
    overflow: auto;
}

 .shoe_title {
     width: 306px;
     height: 33px;
     background-color:#518FD3;
}

 .shoe_title_left{
     text-decoration: none;
     text-align: center;
     position: absolute;
     margin-left: 120px;
     margin-top: 5px;
     background: none repeat scroll 0 0;
     font: 16px '微软雅黑',Arial,Helvetica,sans-serif;
}
  .show_button{
      text-decoration: none;
      text-align: center;
 }
  .show_content{
      background: none repeat scroll 0 0;
      font: 15px '微软雅黑',Arial,Helvetica,sans-serif;
}
  .show_button .buttonone{
     background: #0063A2;
     color: #FFFFFF;
     cursor: pointer;
     font-weight: 700;border:none; cursor:pointer;
     height:24px;
     width: 55px;
 }
 .show_button .buttontwo{
     background: #939393;
     color: #FFFFFF;
     cursor: pointer;
     font-weight: 700;border:none; cursor:pointer;
     height:24px;
     width: 55px;
 }

    #thead td{
        background: none repeat scroll 0 0 #C6E1FD;
        border: 1px solid #8C8C8C;
        height: 40px;
        line-height: 40px;
        text-align: center;
    }
 </style>

<!--内容-->
<div class="outer">
	<!--导航-->
	<div id="featurebar"></div>
    <!--搜索栏-->
    <div id="querybox" class="isearcher" style="padding-top:18px; padding-bottom:7px;">
    	<select id="search_item" class="isearcher_select_list" style="height:24px; margin-top:0px;vertical-align:''">
            <option value="username">账户</option>
            <option value="realname">姓名</option>
        </select>
        <input id="search_item_input"  class="isearcher_input_words" type="text" style="vertical-align:''"/>
        <input id="search_item_btn" class="isearcher_submit_button" type="button" value="搜 索" style="vertical-align:''; margin:0px;"/>
        <div id="iscp_iresult" class="isearcher_instant_result">
        <ul id="iscp_iresult_list"> </ul>
        </div>
         
    </div>
    <!--内容展示-->


    <table class="tableClass">
        <thead id="thead">
        </thead>
        <tbody id="tbody">
        </tbody>
        <tfoot id="tfoot">
        </tfoot>
    </table>
      <!--  <table class="tableClass" >
          <thead >
			<tr id="tthead" >
			    <th width="10%">编号</th>
                <th width="15%">姓名</th>
                <th width="20%" >联系方式</th>
				<th width="10%" id="forplus"> <a style="cursor:pointer;"  onclick="ShowDiv('MyDiv','fade')" > <img src="<?php echo $_SERVER['DOCUMENT_ROOT'];?>/public/images/plus.png" alt="+ "></a>
                                </th>
             </tr>
		  </thead>
		  <tbody id="tbody">
		  </tbody>
          <tfoot id="tfoot">
          </tfoot>
      </table>
      -->
      <!--弹出层时背景层DIV-->
      <div id="MyDiv" class="white_content" style="display:none;">
          <div class="shoe_title">
              <span class="shoe_title_left" >新增权重</span>
              <span  class="shoe_title_right" style="position: absolute;margin-left: 250px;cursor: pointer; margin-top: 5px;cursor:hand;cursor:pointer;" id="closeDiv">关闭</span>
          </div><br/>
               权重名称: <input type="text" id="wname" /> <br/><br/>
          <div class="show_button">
               <input type="button" class="buttonone" value="确定" id="addWname"/>
               <input type="button" class="buttontwo" value="取消"  id="closeDiv">
          </div>
      </div>
</div>
<div id="edit" style="display:none;">
<style type="text/css">
table.add_quest{width:400px;margin:0px 20px 5px;border-collapse:collapse;border-spacing:0;color:#535353;}table.add_quest #save_tag{text-align:right;border:none;}table.add_quest .pop_title{height:40px;line-height:40px;border:1px solid #b6b6b6;background:none repeat scroll 0 0 #C6E1FD;}table.add_quest #pop_title{width:100%;text-align:center;}table.add_quest td{padding-left:10px;height:40px;line-height:40px;border:1px solid #b6b6b6;}table.add_quest td.tr_nav{text-align:center;padding:0px;}table.add_quest td.m_left_20{padding-left:20px;}table textarea#q_title{width:96%;height:32px;margin-top:5px;}table.add_quest input{border:1px solid #97BDE7;height:24px; vertical-align:middle;}table.add_quest .isearcher_submit_button{height:27px;width:87px; border:none;}table.add_quest .isearcher_submit_button#q_back{ background-color:#939393;}
</style>
    <table cellpadding="0" cellspacing="0" class="add_quest">
        <tbody id="ebody"></tbody>
        <tfoot id="efoot"></tfoot>
    </table>
</div>
</div>
<?php include(TPL_DIR.'common/common_js.php');?>
<script src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/competence.js?v=1.0.0.2013051708"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script >
var actionPhp="<?php echo SITE_ROOT.'/Competence/';?>";
var page='user';//定义当前页面
$(document).ready(function(e) {
    //显示之前判断

	show_info('t=1');		
	//修改
	getdom().find('#user_save').live('click',function(){
		var temp_val="&realname="+getdom().find('#ebodys #realname').val()+"&sex="+getdom().find('#ebodys #sex').val()+"&userid="+getdom().find('#ebodys #userid').val();
		m_updata(temp_val);//更新数据
		temp_val='';
		show_info('t=1');
		parent.box_close();
	});

	getdom().find('#part_save').live('click',function(){
		var obj=getdom().find('#ebodys input[name="parids"]'); 
		var s='';  
		for(var i=0; i<obj.length; i++){  
			if(obj[i].checked) s+=obj[i].value+',';  //如果选中，将value添加到变量s中     
		}    

		var temp_val="parts="+s+"&user_id="+getdom().find('#ebodys #user_id').val();
		do_ajax('updataAuth',temp_val);	//更新数据
		parent.box_close();
	});
	$('#search_item_btn').live('click',function(){
		show_info('t=1&'+$('#search_item').val()+'='+$('#search_item_input').val());
	});

    //弹出隐藏层
    $('#forplus').live('click',function(){
         document.getElementById('MyDiv').style.display='block';
    });

    //关闭弹出层
    $('#closeDiv').live('click',function(){
             document.getElementById('MyDiv').style.display='none';
        });

});

</script>
<?php include(TPL_DIR.'common/footer.php');?>

