<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<title>开始答题</title>
	<link rel="stylesheet" href="<?php echo VIEW_CSS_URL;?>wap_style.css"/>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>xui-2.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/wap_common.js"></script>
<script language="javascript">
		var imports='请输入密码:';
		var passerror='密码错误';
	</script>	
    <?php echo $passMsg;?>
<script language="javascript">
	var _duration="<?php echo $wj->wj_duration; ?>";
	//显示学习计时器
	var hour = 0, minute = 0, second = 0;
	var t = _duration*60;
	var flag1;
	var bOut=0;
	function studyTime()
	{
		if(_duration/1==0){
			document.getElementById('sky').style.display='none';
			document.getElementById('show_duration').innerHTML='不限';
			return;
		}
		hour=parseInt(t/60/60);
		minute=parseInt(t/60%60);
		second=parseInt(t%60);
		
		if(hour<10){
			hour='0'+hour;
		}

		if(minute<10){
			minute='0'+minute;
		}

		if(second<10){
			second='0'+second;
		}

		document.getElementById('show_duration').innerHTML=" "+hour+":"+minute+":"+second;

		if(t/1 == 0)
		{
			bOut=1;
			document.dj.submit();
			return;
		}

		t = t - 1;
		document.getElementById('ttime').value=Number(document.getElementById('ttime').value)+1;
		flag1 = setTimeout("studyTime()", 1000);
	}
	  
	//必填
	var isnot="";
	var isnotmsg="";
	</script>
	<script type="text/javascript">  
	function isExp(o,s){ 
	if(s=='2'){
	 var reg = new RegExp("^[0-9]*$");
	  if(!reg.test(o.value)){
			alert("请输入数字!");
			o.value='';
		}
	 }
	if(s=='3'){
	 var result1 = o.value.match(/((^((1[8-9]\d{2})|([2-9]\d{3}))(-)(10|12|0?[13578])(-)(3[01]|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(11|0?[469])(-)(30|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(0?2)(-)(2[0-8]|1[0-9]|0?[1-9])$)|(^([2468][048]00)(-)(0?2)(-)(29)$)|(^([3579][26]00)(-)(0?2)(-)(29)$)|(^([1][89][0][48])(-)(0?2)(-)(29)$)|(^([2-9][0-9][0][48])(-)(0?2)(-)(29)$)|(^([1][89][2468][048])(-)(0?2)(-)(29)$)|(^([2-9][0-9][2468][048])(-)(0?2)(-)(29)$)|(^([1][89][13579][26])(-)(0?2)(-)(29)$)|(^([2-9][0-9][13579][26])(-)(0?2)(-)(29)$))/);
	   if(result1==null)
        {
            alert("请输入正确的日期格式！,例如：2009-01-01。");o.value='';
        }
	 } 
	}
	
	function isMaxLen(o,s){  
	
	 var nMaxLen=o.getAttribute? parseInt(o.getAttribute("maxlength")):"";  
	 if(o.getAttribute && o.value.length>nMaxLen){  
	 o.value=o.value.substring(0,nMaxLen)  
	 }  
	}  
	
	function replenish(sel,id){
		if(sel){
			x$(id).removeClass('hide');
		}else{
			x$(id).addClass('hide');
		}
	}
	function replenish_area(id,num){
		if(num=1){
			if(radioChecks(id)){
			x$(id).removeClass('hide');
			}else{
			x$(id).addClass('hide');
			}
		}else{
			if(radioChecks(id)){
			x$(id).removeClass('hide');
			}else{
			x$(id).addClass('hide');
			}
		}
	}
	function nono(id){
	 document.getElementById(id).click(); 
	}
	window.onload=function(){
		studyTime();
	}
</script>   
<script language="javascript"> 
<!-- 
javascript:window.history.forward(1); 
//--> 
</script> 
<style>
		.mydiv{
		  position: absolute;
		  border: 1px solid silver;
		  background-color: #EFEFEF;
		  line-height:35px;
		  font-size:12px;
		  z-index:1000;
		  bottom:0;
		  right:0;
		}
		 
		.hide{
		  display: none;			  
		}
	</style> 
</head>
<body>
	<!--header!-->
	<div class="full_bg2">
	<div class="logo_bg"><a href="<?php echo SITE_ROOT; ?>/Wap" title="后退" class="logo_top3"></a></div>
	<div class="last_time">
		<div class="box_1" id="sky">剩余时间： <span id="show_duration"></span></div>
		<!--main-->
		<div class="main">
	  <p class="title_p"><?php echo $wj->getTitle(); $wjall=$wj->getAlldata();?></p>
			<p class="title_p2">&nbsp;&nbsp;开始时间：<?php echo date('Y年m月d日',$wjall[0]['q_start'])?></p>
            <p class="title_p2">&nbsp;&nbsp;截止时间：<?php echo date('Y年m月d日',$wjall[0]['q_end'])?></p>
            <p class="title_p2">&nbsp;&nbsp;问卷制作人：<?php echo $wj->getCuser(); ?></p>
            <p class="title_p2"><?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getTopDesc()); ?></p>
	  <section class="exames">
     
	   <form name="dj" id="dj" action="<?php echo SITE_ROOT; ?>Wap/submit/" method="POST">
 
            	<?php if(cookie::get('isLogin')==1 && $wj->wj_anonymous=='1'){ ?>
            	<!--匿名部分-->
                <div class="cion_2">
                    	是否匿名答卷：<select name="is_anonymous">
                        	<option value="0">否</option>
                            <option value="1">是</option>
                        </select>
				</div>
                <!--匿名部分-->
                <?php }else{ ?>
                <?php if(cookie::get('isLogin')!=1){ ?>
                <input type="hidden" name="is_anonymous" value="1" />
                <?php } ?>
                <?php } ?>
            <?php
            if(!empty($subjects))
            { $pages=count($subjects);
				$pi=0;
                foreach($subjects as $subject)  { if($pi%10==0) echo "<tbody id='dj".(ceil($pi/10)+1)."' $sty>";               $pi++;?>
                <div class="cion_2">
					<p data="<?php echo intval($allsub[0]['chk_limit']);?>"><?php $allsub=$subject->getSubjectAll(); echo $allsub[0]['title_id'];?>&nbsp;<?php echo $subject->getSubjectTitle();?></p><p><?php if($subject->getImageURL()!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$subject->getImageURL())))){ ?><img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$subject->getImageURL()))?>"></p><?php } ?>
							<?php if($allsub[0]['q_remark']) echo "说明：".$allsub[0]['q_remark']."<br>";?>
                            <?php $items = $subject->getSubjectItems();?>
					
                            <?php if($subject->sub_type_id==4 ){?>
                          <div class="cion_4">
                            <textarea class="text_3" name="_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $items[0]['id'];?>_" id="_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $items[0]['id'];?>_" rows="6" cols="80" <?php if($items[0]['s_len']){ ?>maxlength=<?php echo '"'.$items[0]['s_len'].'" onkeyup="return isMaxLen(this,\''.$items[0]['s_type'].'\')"'; }?> onBlur="<?php echo 'isExp(this,\''.$items[0]['s_type'].'\')';?>"  placeholder="此处填写答案"></textarea>
                             </div>
							<?php if($allsub[0]['s_type']){echo "<script>isnot+=\"if(document.getElementById('_".$items[0]['fk_subject_id']."_textarea_".$items[0]['id']."_').value){}else{isnotmsg+='".$subject->getSubjectTitle()." ';}\"</script>";} //必填?>
                            <?php echo "</td></tr>";if($pi%10==0||$pi==$pages) echo "</ebody>";continue;} ?>

                            <?php if(is_array($items)) { $ji=0;?>
                            <?php foreach ($items as $item) { ?>		
                                <p class="xuan_p">
                                <?php if($subject->sub_type_id==3) { //复选 ?>
                                    <input type="checkbox" onBlur="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')" onClick="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')" name="_<?php echo $item['fk_subject_id'];?>_checkbox_[]" id="_<?php echo $item['fk_subject_id'].$ji;?>_checkbox" value="<?php echo $item['id'];?>" /><font onClick="nono('_<?php echo $item['fk_subject_id'].$ji;?>_checkbox');"><?php echo $item['s_answer'];echo "\r\n"; ?></font>
                                 
									<?php if($allsub[0]['s_type']&&$ji==0){echo "<script>isnot+=\"var s".$items[0]['id']."=CheckboxChecks(document.all.dj.elements[\'_".$item['fk_subject_id']."_checkbox_[]\']);if(s".$items[0]['id']."){";
									if($item['s_replenish']) echo "if(x$('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').attr('value')||x$('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').length==0){}else{isnotmsg+='".$subject->getSubjectTitle()."补充说明';}";
									echo "}else{isnotmsg+='".$subject->getSubjectTitle()." ';}\"</script>";} //必填?>
                                <?php }else if($subject->sub_type_id==2) { //单选?>
                                	<input type="radio" onBlur="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')"  onClick="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')" name="_<?php echo $item['fk_subject_id'];?>_radio_[]" id="_<?php echo $item['fk_subject_id'].$ji;?>_radio" value="<?php echo $item['id'];?>" /><font onClick="nono('_<?php echo $item['fk_subject_id'].$ji;?>_radio');"><?php echo $item['s_answer'];echo "\r\n"; ?></font>
									<?php if($allsub[0]['s_type']&&$ji==0){echo "<script>isnot+=\"var s".$items[0]['id']."=radioChecks(\\\"_".$item['fk_subject_id']."_radio_[]\\\");if(s".$items[0]['id']."){";
									if($item['s_replenish']) echo "if(x$('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').attr('value')||x$('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').length==0){}else{isnotmsg+='".$subject->getSubjectTitle()."补充说明';}";
									echo "}else{isnotmsg+='".$subject->getSubjectTitle()." ';}\"</script>";} //必填?>
									 
                                <?php }else if($subject->sub_type_id==4) { } else { ?>
                                    <input type="radio" onBlur="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')"  onClick="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')" name="_<?php echo $item['fk_subject_id'];?>_radio_[]" id="_<?php echo $item['fk_subject_id'].$ji;?>_radio" value="<?php echo $item['id'];?>" /><font onClick="nono('_<?php echo $item['fk_subject_id'].$ji;?>_radio');"><?php echo $item['s_answer'];echo "\r\n"; ?><font>
									<?php if($allsub[0]['s_type']&&$ji==0){echo "<script>isnot+=\"if(radioChecks(\\\"_".$item['fk_subject_id']."_radio_[]\\\"){}else{isnotmsg+='".$subject->getSubjectTitle()." ';}\"</script>";} //必填?>
                                <?php } 
								if($item['s_replenish']){?>
								<span id='replenish_<?php echo $items[0]['id'].$ji;?>_radio' class='hide'>补充说明<textarea name="_<?php echo $items[0]['fk_subject_id'];?>_add_<?php echo $items[0]['id'];?>_[]" id="additional_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $item['id'];?>_" rows="2" cols="30"  placeholder="此处填写答案"></textarea></span>
								<?php } if($item['s_url']!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$item['s_url'])))){ ?><img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$item['s_url']))?>"></p><?php } ?>
                               <?php $ji++;} ?>
                               </p>
                            <?php } ?>
                    

                    <?php  
			   if($pi%10==0||$pi==$pages) echo "</tbody>";
			   }
            }
                ?>
</span> 
			                <div style="text-align: center"><?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getFootDesc()); ?></div>
							<br>
			
			<?php if(!$dj_no){?>
                <div style="text-align: center">
                    <input type="hidden" name="wjid" id="wjid" value="<?php echo $wj->getWjId(); ?>">
                    <input type="hidden" name="djid" id="djid" value="<?php echo $djid; ?>">
					<input type="hidden" name="ttime" id="ttime" value="0">
					<input type="hidden" name="startTime" value="<?php echo time(); ?>">
                </div>
                 <div class="btn" align="center">
                    <input type="button" value=" 提 交 " class="button_3" onClick="submitOK();" />
                </div>    
                <div class="footer">
					
				</div>
			<?php }?>

           
		</form>
	  </section>
	</article>
    </div>
</div>    
<script language="javascript">
function submitOK(){
	eval(isnot);
	if(isnotmsg){
		alert(isnotmsg+'未填写');
		 isnotmsg="";
		return false;
	}else{
		bOut=1;
		ondonw();
		//document.dj.submit();
	}
}
</script>    
<!--TC-->
<div class="zhezhao" id="zhezhao"></div>
<div class="tc_box" id="tc_box">
	<div class="tc_cen">
		<p>提交问卷后将无法修改</p>
	</div>
	<div class="tc_btm"> <p><a href="#" title="提交" class="button_4" onclick="document.dj.submit()">提交</a> <a href="#" title="取消" class="button_5" onclick="upclick();">取消</a></p></div>
</div>
<!--/TC-->
<script type="text/javascript">

$(document).ready(function(e){  
    $('input[type=checkbox]').click(function(e){
        var cnt = $(this).parent().attr('data');
		if(cnt > 0)
		{
			var curname = $(this).attr('name');
			$("input[name='"+curname+"']").attr('disabled',true)
			if($("input[name='"+curname+"']:checked").length >= cnt){
				$("input[name='"+curname+"']:checked").attr('disabled', false);
			}
			else
			{
				$("input[name='"+curname+"']").attr('disabled', false);
			}
		}
    })
})
function ondonw(){
	document.getElementById("zhezhao").style.display="block";
	document.getElementById("tc_box").style.display="block";
};
function upclick(){
	document.getElementById("zhezhao").style.display="none";
	document.getElementById("tc_box").style.display="none";
}

</script>
</body>
</html>