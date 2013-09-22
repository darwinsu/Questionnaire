<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>91云办公|考试问卷</title>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>common.css?v=<?php echo SYS_VERSION;?>"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>main.css?v=<?php echo SYS_VERSION;?>"/>
<style type="text/css">
label{display:-moz-inline-block;display:inline-block;cursor:pointer;margin:5px 0;padding-left:20px;line-height:15px;background:url(<?php echo VIEW_PIC_URL?>no.png) no-repeat left top;}
label.checked {background:url(<?php echo VIEW_PIC_URL?>yes.png) no-repeat left top;}
.main .right .ans{	line-height:65px;	height:54px;	float:left;	width:240px;	text-align:left;}
.main .right .sub_time{	line-height:65px; height:54px;	float:left;	width:280px;	text-align:left;}
.main .right .ans_time{	line-height:65px; height:54px;	float:left;	width:200px;	text-align:left;}
.main .right .pre_next{	line-height:65px; height:54px;	float:left;	width:230px;	text-align:right;}
</style>
</head>
<body>
<div class="main clearfix" style="width:950px;">
	<div class="right">
		<div class="content">
            <form name="dj" id="dj" action="<?php echo SITE_ROOT.'Dj/pfrk/'; ?>" method="POST">
                <input type="hidden" name="djid" value="<?php echo $objDj->djid; ?>">
                <input type="hidden" name="wjid" value="<?php echo $wj->wj_Id; ?>">
                <div class="ans">
                    答卷人:<?php if(!$objDj->dj_anonymous){ echo $objDj->getDjName(); ?><?php echo "[".$objDj->getUid()."]"; }else{echo "匿名[guest]";}?>
                </div>
                <div class="sub_time">
                    答卷提交时间：<?php echo $objDj->getDjOverTime() ?>
                </div>
                <div class="ans_time">
                    答卷所用时间：<?php echo $objDj->getDjTimeConsuming() ?>秒
                </div>
                <div class="pre_next">
                    <?php //if($swj) echo $swj."上一份问卷</a> &nbsp;"; if($xwj) echo $xwj."下一份问卷</a>"; ?>
                </div><div class="clear"></div>
                <table class="tableClass"  cellpadding="0" cellspacing="0" width='100%'>
                    <tr>
                    	<th colspan="2"><?php echo $wj->getTitle();$wjall=$wj->getAlldata(); ?></th>
                    </tr>
                    <tr>
                    	<td colspan="2" align="left" style="padding-top:5px; padding-bottom:5px;">
							<?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getTopDesc()); ?>
                        </td>
                    </tr>
<?php if($wj->wj_anonymous=='1')
{?>
                    <!--匿名部分-->
                    <tr>
                        <td colspan="2">是否匿名答卷：<?php echo ($objDj->dj_anonymous=='1'?'是':'否'); ?></td>
                    </tr>
                    <!--匿名部分-->
<?php
}
if(!empty($subjects))
{
	unset($djzdfs,$zffs);
	foreach($subjects as $subject)
	{
?>
                    <tr>
                    <td width="6%" align="center" style="padding-left:0px;"><?php $allsub=$subject->getSubjectAll(); echo $allsub[0]['title_id'];?></td>
                    <td class="cr" width="94%">
                    <p>
<?php
						echo $subject->getSubjectTitle()/*,'(总分：',$subject->sub_zf,')'*/; $zffs=$zffs+0+$subject->sub_zf;?><br>
<?php
		if($subject->getImageURL()!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$subject->getImageURL()))))
		{?>
						<img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$subject->getImageURL()))?>">
<?php	}?>
                    </p>
<?php
		$items = $subject->getSubjectItems();
		
		if($subject->sub_type_id==4 && count($items)==1)
		{?>
					<textarea style="border: 1px solid #97BDE7;" name="_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $items[0]['id'];?>_" id="_<?php echo $items[0]['fk_subject_id'];?>_text_<?php echo $items[0]['id'];?>_" rows="5" cols="136"><?php  echo $arrVal[$items[0]['fk_subject_id']][$items[0]['id']]['dj_answer']; ?></textarea>
<?php
			if($objDj->dj_zf!=-1)
			{?>
					&nbsp;&nbsp;
					<div id="show_df[<?php echo $subject->sub_Id; ?>]" style="display:inline;">
						(得分：<?php echo $arrVal[$items[0]['fk_subject_id']][$items[0]['id']]['df']; $djzdfs=$djzdfs+0+$arrVal[$items[0]['fk_subject_id']][$items[0]['id']]['df'];?>)
					</div>
<?php
			}?>
					<div id="show_input_df[<?php echo $subject->sub_Id; ?>]" style="display:none;">
						&nbsp;得分：<input type="text" size="5" name="arrPostDf[<?php echo $subject->sub_Id; ?>]" value="<?php if($objDj->dj_zf!=-1){ echo $arrVal[$items[0]['fk_subject_id']][$items[0]['id']]['df']; }else{ echo ""; } ?>" />
						<input type="hidden" name="max_arrPostDf[<?php echo $subject->sub_Id; ?>]" value="<?php echo $subject->sub_zf; ?>" />
                    </div>
<?php
			continue;
		}
?>

<?php
		if($subject->sub_type_id!=4 && is_array($items))
		{?>
<?php
			foreach($items as $item)
			{
				if($subject->isCheck())
				{?>
                    <input type="checkbox" name="_<?php echo $item['fk_subject_id'];?>_checkbox_[]" value="<?php echo $item['id'];?>" <?php if($arrVal[$item['fk_subject_id']][$item['id']]['checked']=='1'){ echo "checked"; } ?> /><?php echo $item['s_answer'];echo "\r\n"; ?>
<?php
				}
				else
				{
					echo '<style type="text/css">#_'.$item['fk_subject_id'].'_'.$item['id'].'_radio{display:none}</style>';?>
                    <input type="radio" name="_<?php echo $item['fk_subject_id'];?>_radio_[]" id="_<?php echo $item['fk_subject_id'].'_'.$item['id'];?>_radio" value="<?php echo $item['id'];?>" <?php if($arrVal[$item['fk_subject_id']][$item['id']]['checked']=='1'){ echo "checked"; } ?> />
					<?php echo '<label name="_'.$item['fk_subject_id'].'_'.$item['id'].'_radio" data="_'.$item['fk_subject_id'].'_radio" for="_'.$item['fk_subject_id'].'_'.$item['id'].'_radio"'; if($arrVal[$item['fk_subject_id']][$item['id']]['checked']=='1'){ echo 'class="checked"'; } ?> >
					<?php echo $item['s_answer'];echo "</label>"; ?>
                    
<?php
				}
				if($item['s_url']!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$item['s_url']))))
				{?>
					<img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$item['s_url']))?>"></p>
<?php
				}
				if($arrVal[$items[0]['fk_subject_id']][$item['id']]['dj_additional'])
				{?>
                    补充：<?php echo $arrVal[$items[0]['fk_subject_id']][$item['id']]['dj_additional'];
				}?>
				(<?php echo $item['s_value'];?>分)<br>
<?php
			}?>
					&nbsp;&nbsp;(得分：<?php echo $arrDf[$subject->sub_Id]['df']; $djzdfs=$djzdfs+0+$arrDf[$subject->sub_Id]['df'];?>)
					<input type="hidden" name="arrPostDf[<?php echo $subject->sub_Id; ?>]" value="<?php echo $arrDf[$subject->sub_Id]['df']; ?>" />
<?php
		}?>
                    </td>
                    </tr>
                    
                    <?php
    }
}?>
                	<tr>
                    	<td align="left" style="padding-top:5px; padding-bottom:5px;" colspan="2">
                        	<?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getFootDesc()); ?>
                        </td>
                    </tr>
                </table>
                <br>
                <table class="tableClass"  cellpadding="0" cellspacing="0" width='100%'>
					<tr>
						<th colspan="2" align="center">分值统计结果</td>
					</tr>
					<tr>
						<td width="40%">问卷总分值：<?php echo $zffs;?>分</td>
						<td >
							问卷总分基数：100分
						</td>
					</tr>
					<tr>
						<td width="40%">答卷的得分值：<?php echo $djzdfs?>分</td>
						<td >基于问卷基数的答卷得分值：<?php if($zffs) echo round($djzdfs/$zffs*100,2); else echo '0';?>分</td>
					</tr>
					<tr>
						<td colspan="2" align="left">换算成标准分：0.0分(智力测试专用)</td>
					</tr>
                </table>
				<br>
                <?php if(in_array('1',$jsbhlist)||(in_array('3',$jsbhlist)&&$wjall[0]['c_userid']==cookie::get('userid'))){ ?>
                <input type="button" value="返 回" class="inputBig fr" style="background:#939393;" onClick="window.location.href='<?php echo $_SERVER['HTTP_REFERER'];?>'" />
                <input type="button" id="btnPfrk" value="评分入库" class="inputBig fr" onClick="clickPfrk()" />
                <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
                <?php }else{?>
                <input type="button" value="关 闭" class="inputBig fr" style="background:#939393;" onClick="window.close();" />
                <?php }?>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js?v=<?php echo SYS_VERSION;?>"></script>
<script language="javascript">
var isEdit=0;
function clickPfrk()
{
	if(isEdit==0)
	{
		var arrDiv=document.getElementsByTagName('div');
		var len=arrDiv.length;
		if(len)
		{
			for(i=0;i<len;i++)
			{
				if(arrDiv[i].id.indexOf('show_input_df')!=-1)
				{
					arrDiv[i].style.display='inline';
				}
				else if(arrDiv[i].id.indexOf('show_df')!=-1)
				{
					arrDiv[i].style.display='none';
				}
			}
		}
		document.getElementById('btnPfrk').value='保 存';
		isEdit = 1;
	}
	else
	{
		//alert('提交');
		//判断简答题评分是否为空
		var arrEl=document.dj.elements;
		var len=arrEl.length;
		if(len)
		{
			for(i=0;i<len;i++)
			{
				if(arrEl[i].name.indexOf('arrPostDf')!=-1 && arrEl[i].type=='text')
				{
					if(arrEl[i].value=="")
					{
						alert('请填写简答题的评分！');
						arrEl[i].focus();
						return;
					}
					else
					{
						if((arrEl[i].value/1) > (document.dj['max_'+arrEl[i].name].value/1))
						{
							alert('评分不能大于总得分！');
							arrEl[i].focus();
							return;	
						}
					}
				}
			}
		}
		document.dj.submit();
	}
}

var form = document.getElementById("dj");
var labelList = form.getElementsByTagName("label");
for( var i=0;i< labelList.length; i++ ){
	labelList[i].onclick = function( i ){
		delClass($(this).attr('data'));
		addClass( this );
		try{
			document.getElementById( this.name ).checked = true;
		}catch( e ){}
	}
}

function addClass( obj ){
	obj.className = "checked";
}

function delClass( objlist ){
	$("label[data='"+objlist+"']").removeClass('checked');
}
</script>
</body>
</html>