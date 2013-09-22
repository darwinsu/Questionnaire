<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>91云办公|考试问卷</title>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>common.css?v=<?php echo SYS_VERSION;?>"/>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>main.css?v=<?php echo SYS_VERSION;?>"/>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>style.css?v=<?php echo SYS_VERSION;?>" type="text/css" />

<style type="text/css">
.mydiv{position: absolute;border: 1px solid silver;background-color: #EFEFEF;line-height:35px;font-size:12px;z-index:1000;bottom:0;right:0;}
#sky{width: 240px;height:35px;text-align:left;padding-left:5px;}
label{display:-moz-inline-block;display:inline-block;cursor:pointer;margin:5px 0;padding-left:20px;line-height:15px;background:url(<?php echo VIEW_PIC_URL?>no.png) no-repeat left top;}
label.checked{background:url(<?php echo VIEW_PIC_URL?>yes.png) no-repeat left top;}
</style>
<script language="javascript" type="text/javascript">
var isnot="";
var isnotmsg="";
</script>
</head>
<body <?php if(!$dj_no){?>onbeforeunload="if(bOut==0){ return '退出后将放弃本次答题。' }"<? } ?>>

<div class="main clearfix" style="width:950px;">
    <div class="right">
        <div class="content">
		<?php if(!$dj_no){?>
			<div id="sky" class="mydiv" ><div id="show_duration" style="display:inline;text-align:right;color:red;">--</div></div>
		<? } ?>
            <form name="dj" id="dj" action="../../../submit/" method="POST">
            <table class="tableClass"  cellpadding="0" cellspacing="0" width='100%'>
				<tr>
                	<th align="center" colspan="2" style="background:none; border:none;">
                        <div style="float:right;">
                        	问卷制作人：<?php echo $wj->getCuser(); ?>
                        </div>
                    </th>
                </tr>
				<tr>
                	<th colspan="2" style="height:40px;">
                    	<div><div style="width:100%; text-align:center;"><?php echo $wj->getTitle(); ?></div></div>
            			<div class="add" style="display:none;"><?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getTopDesc()); ?></div>
                    </th>
                </tr>
<?php
			if(cookie::get('isLogin')==1 && $wj->wj_anonymous=='1')
			{
?>
            	<!--匿名部分-->
                <tr style="display:none;">
                	<td colspan="2">是否匿名答卷：
                        <select name="is_anonymous">
                        	<option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </td>
                </tr>
                <!--匿名部分-->
<?php
			}
			else
			{
				if(cookie::get('isLogin')!=1)
				{
?>
                <input type="hidden" name="is_anonymous" value="1" />
<?php
				}
			}
            if(!empty($subjects))
            {	$pages=count($subjects);
				$pi=0;
                foreach($subjects as $subject){ if($pi!=0){ $sty="style='display:none'";} if($pi%10==0) echo "<tbody id='dj".(ceil($pi/10)+1)."' $sty>"; $pi++;?>
                <tr>
                	<td width="6%" align="center" style="padding-left:0px;"><?php $allsub=$subject->getSubjectAll(); echo $allsub[0]['title_id'];?></td>
                    <td class="cr" width="94%">
                        <p data="<?php echo intval($allsub[0]['chk_limit']);?>">
							<?php echo $subject->getSubjectTitle();?><br>
							<?php if($subject->getImageURL()!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$subject->getImageURL())))){ ?>
                            <img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$subject->getImageURL()))?>">
                        </p>
						<?php } ?>
							<?php if($allsub[0]['q_remark']) echo "说明：".$allsub[0]['q_remark']."<br>";?>
                            <?php $items = $subject->getSubjectItems();?>
                            
<?php
						if($subject->sub_type_id==4 )
						{?>
                            <textarea name="_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $items[0]['id'];?>_"
                            	id="_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $items[0]['id'];?>_" rows="4" style="width:860px;"
                                <?php if($items[0]['s_len']){ ?>maxlength=<?php echo '"'.$items[0]['s_len'].'" onkeyup="return isMaxLen(this,\''.$items[0]['s_type'].'\')"'; }?>
                                onBlur="<?php echo 'isExp(this,\''.$items[0]['s_type'].'\')';?>"></textarea>
							<?php if($allsub[0]['s_type']){
								echo "<script>isnot+=\"if($('#_".$items[0]['fk_subject_id']."_textarea_".$items[0]['id']."_').val()){}else{isnotmsg+='".$subject->getSubjectTitle()
								." ';}\"</script>";}//必填?>
                            <?php echo "</td></tr>";if($pi%10==0||$pi==$pages) echo "</ebody>";continue;
							}
							if(is_array($items))
							{
								$ji=0;
								foreach($items as $item)
								{
									if($subject->sub_type_id==3)
									{ //复选 ?>
                                    <input type="checkbox" onBlur="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')" onClick="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')" name="_<?php echo $item['fk_subject_id'];?>_checkbox_[]" value="<?php echo $item['id'];?>" />
									<?php echo $item['s_answer']; echo "<br>"; ?>
									<?php if($allsub[0]['s_type']&&$ji==0){echo "<script>isnot+=\"var s".$items[0]['id']."=$('input:checkbox[name=\\\"_".$item['fk_subject_id']."_checkbox_[]\\\"]:checked').val();if(s".$items[0]['id']."){";
									if($item['s_replenish']) echo "if($('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').val()||$('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').length==0){}else{/*isnotmsg+='".$subject->getSubjectTitle()."补充说明';*/}";
									echo "}else{isnotmsg+='".$subject->getSubjectTitle()." ';}\"</script>";} //必填?>
                                <?php }else if($subject->sub_type_id==2) { //单选
								echo '<style type="text/css">#_'.$items[0]['id'].$ji.'_radio{display:none}</style>';?>
                                	<input type="radio" onChange="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')"  onClick="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>_radio')" name="_<?php echo $item['fk_subject_id'];?>_radio_[]" value="<?php echo $item['id'];?>" id="<?php echo '_'.$items[0]['id'].$ji;?>_radio"/> <?php echo '<label  name="_'.$items[0]['id'].$ji.'_radio" data="_'.$item['fk_subject_id'].'_radio" for="_'.$items[0]['id'].$ji.'_radio">'; echo $item['s_answer'];echo "</label><br />"; ?>
									<?php if($allsub[0]['s_type']&&$ji==0){echo "<script>isnot+=\"var s".$items[0]['id']."=$('input:radio[name=\\\"_".$item['fk_subject_id']."_radio_[]\\\"]:checked').val();if(s".$items[0]['id']."){";
									if($item['s_replenish']) echo "if($('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').val()||$('#additional_".$items[0]['fk_subject_id']."_textarea_'+s".$items[0]['id']."+'_').length==0){}else{/*isnotmsg+='".$subject->getSubjectTitle()."补充说明';*/}";
									echo "}else{isnotmsg+='".$subject->getSubjectTitle()." ';}\"</script>";} //必填?>
									 
                                <?php }else if($subject->sub_type_id==4) { } else { ?>
                                    <input type="radio" onBlur="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>')"  onClick="replenish(this.checked,'#replenish_<?php echo $items[0]['id'].$ji;?>')" name="_<?php echo $item['fk_subject_id'];?>_radio_[]" value="<?php echo $item['id'];?>" /><?php echo $item['s_answer'];echo "<br />"; ?>
									<?php if($allsub[0]['s_type']&&$ji==0){echo "<script>isnot+=\"if($('input:radio[name=\\\"_".$item['fk_subject_id']."_radio_[]\\\"]:checked').val()){}else{isnotmsg+='".$subject->getSubjectTitle()." ';}\"</script>";} //必填?>
                                <?php } 
								if($item['s_replenish']){?>
								<span id='replenish_<?php echo $items[0]['id'].$ji;?>_radio' style="display:none">补充说明<textarea name="_<?php echo $items[0]['fk_subject_id'];?>_add_<?php echo $items[0]['id'];?>_[]" id="additional_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $item['id'];?>_" rows="2" cols="30"></textarea><br /></span>
								<?php } if($item['s_url']!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$item['s_url'])))){ ?><img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$item['s_url']))?>"></p><?php } ?>
                               <?php $ji++;} ?>
                            <?php } ?>
							
                    </td>
                </tr>

                    <?php  
			   if($pi%10==0||$pi==$pages) echo "</tbody>";
			   }
            }
                ?>
</span>
            </table>
            <div style="text-align: center"><?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getFootDesc()); ?></div>
            <br>
			<div class="page" align="center"><!--<span style="cursor:pointer" onClick='javascript:getDJ("1","<?php echo ceil($pages/10);?>");'><font class="page_first">首页</font></span>
			<?php for($m=1;$m<=ceil($pages/10);$m++){ if($m==1) $page_current="class='page_current'";else $page_current="";?>
			<span <?php echo $page_current;?> id="pagea_<?php echo $m;?>" style="cursor:pointer" onClick='getDJ("<?php echo $m;?>","<?php echo ceil($pages/10);?>");'><font id="pageno_0"><?php echo $m;?></font></span>
			<?php } ?>
			<span style="cursor:pointer" onClick='javascript:getDJ("<?php echo ceil($pages/10);?>","<?php echo ceil($pages/10);?>");'><font class="page_last">尾页</font></span><font style='color: #808080;margin-left: 10px;'>&nbsp;共<span id='page_count'><?php echo $pages;?></span>条,分<?php echo ceil($pages/10);?>页,每页10条</font>--></div>
			<?php if(!$dj_no){?>
                <div style="text-align: center">
                    <input type="hidden" name="wjid" id="wjid" value="<?php echo $wj->getWjId(); ?>">
                    <input type="hidden" name="djid" id="djid" value="<?php echo $djid; ?>">
					<input type="hidden" name="ttime" id="ttime" value="0">
					<input type="hidden" name="startTime" value="<?php echo time(); ?>">
                    <input type="button" value="提交答卷" class="inputBig fr" onClick="submitOK();" />
                </div>
			<?php }?>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js?v=<?php echo SYS_VERSION;?>"></script>
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
	try{
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
	
		document.getElementById('show_duration').innerHTML="答卷时间还剩："+hour+":"+minute+":"+second;
	
		if(t/1 == 0)
		{
			bOut=1;
			document.dj.submit();
			return;
		}
	
		t = t - 1;
		document.getElementById('ttime').value=Number(document.getElementById('ttime').value)+1;
		flag1 = setTimeout("studyTime()", 1000);
	}catch(e){}
}

$(document).ready(function(e){
    scall();
	studyTime();
    
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

//翻页
function getDJ(id,con){
	for(var i=1;i<=con;i++){
		eval("$('#dj"+i+"').hide();");
		eval("document.getElementById('pagea_"+i+"').className='';");
	}
	eval("$('#dj"+id+"').show();");
	eval("document.getElementById('pagea_"+id+"').className='page_current';");
}


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
		$(id).show();
	}else{
		$(id).hide();
	}
}
function replenish_area(id,num){
	if(num=1){
		if($('input:radio[name="'+id+'"]:checked').val()){
			$(id).show();
		}else{
			$(id).hide();
		}
	}else{
		if($('input:checkbox[name="'+id+'"]:checked').val()){
			$(id).show();
		}else{
			$(id).hide();
		}
	}
}

function submitOK(){
	eval(isnot);
	if(isnotmsg){
		alert(isnotmsg+'未填写');
		isnotmsg="";
		return false;
	}else{
		bOut=1;
		document.dj.submit();
	}
}

function sc5(){
	try{
	document.getElementById("sky").style.top=((document.documentElement.scrollTop+document.body.scrollTop+document.getElementById("sky").offsetHeight)/1)+"px";
	document.getElementById("sky").style.left=(document.documentElement.scrollLeft+document.body.scrollLeft+document.documentElement.clientWidth-document.getElementById("sky").offsetWidth)+"px";
	}catch(e){};
}
function closeDiv()
{
	document.getElementById('sky').style.visibility='hidden';
	if(objTimer) window.clearInterval(objTimer)
}

function scall(){
	sc5();
}
window.onscroll=scall;
window.onresize=scall;

var form = document.getElementById("dj");
var labelList = form.getElementsByTagName("label");

for( var i=0;i< labelList.length; i++ ){
	labelList[i].onclick = function( i ){
        delClass($(this).attr('data'));
		addClass( this );
		try{
			document.getElementById( this.name ).checked = true;
			replenish(true,'#replenish'+this.name);
		}catch( e ){}
	}
}

function addClass( obj ){
    obj.className = "checked";
}

function delClass( objlist )
{
    $("label[data='"+objlist+"']").removeClass('checked');
    
	eval("var ad=document.getElementsByName('"+objlist+"_[]')");
	for( var k=0;k< ad.length; k++ ){
		replenish(false,'#replenish'+ad[k].id);
	}
}
</script>
</body>
</html>