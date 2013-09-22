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
<style type="text/css">
.fullbg{
z-index:3;
position:fixed;
left:0px;
top:100px;
width:100%;
height:100%;
filter:Alpha(Opacity=30);
/* IE */
-moz-opacity:0.4;
/* Moz + FF */
opacity: 0.4;
}
</style>
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
		#sky{
		  width: 240px;
		  height:35px;
		  text-align:left;
		  padding-left:5px;
		}
		.hide{
		  display: none;			  
		}
	</style> 
</head>
<body>
	<!--header!-->
	<div class="full_bg2">
	<div class="logo_bg"><a href="javascript:history.go(-1)" title="后退" class="logo_top3"></a></div>
	<div class="last_time"><div class='fullbg' id='bodyfull'></div>
		<!--main-->
		<div class="main">
	  <p class="title_p"><?php echo $wj->getTitle(); $wjall=$wj->getAlldata();?></p>
			<p class="title_p2">&nbsp;&nbsp;开始时间：<?php echo date('Y年m月d日',$wjall[0]['q_start'])?></p>
            <p class="title_p2">&nbsp;&nbsp;截止时间：<?php echo date('Y年m月d日',$wjall[0]['q_end'])?></p>
            <p class="title_p2">&nbsp;&nbsp;问卷制作人：<?php echo $wj->getCuser(); ?></p>
            <p class="title_p2"><?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getTopDesc()); ?></p>
	  <section class="exames">
     
	   <form name="dj" id="dj" action="<?php echo SITE_ROOT; ?>/Wap/submit/" method="POST">
 
                <?php  
             if(!empty($subjects))
                    { unset($djzdfs,$zffs);
                        foreach($subjects as $subject)  { ?>
                           <div class="cion_2">
					<p><?php $allsub=$subject->getSubjectAll(); echo $allsub[0]['title_id'];?>&nbsp;<?php echo $subject->getSubjectTitle();?></p><p><?php if($subject->getImageURL()!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$subject->getImageURL())))){ ?><img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$subject->getImageURL()))?>"></p><?php } ?>
							<?php if($allsub[0]['q_remark']) echo "说明：".$allsub[0]['q_remark']."<br>";?>

                                    <?php $items = $subject->getSubjectItems();?>

                                    <?php if($subject->sub_type_id==4 && count($items)==1){ ?>
                                    <div class="cion_4">
                                    <textarea class="text_3" name="_<?php echo $items[0]['fk_subject_id'];?>_textarea_<?php echo $items[0]['id'];?>_" id="_<?php echo $items[0]['fk_subject_id'];?>_text_<?php echo $items[0]['id'];?>_" rows="6" cols="80"><?php  echo $arrVal[$items[0]['fk_subject_id']][$items[0]['id']]['dj_answer']; ?></textarea>
                             </div><?php if($objDj->dj_zf!=-1){ ?>&nbsp;&nbsp;<?php } ?><div id="show_input_df[<?php echo $subject->sub_Id; ?>]" style="display:none;">&nbsp;
									<input type="hidden" name="max_arrPostDf[<?php echo $subject->sub_Id; ?>]" value="<?php echo $subject->sub_zf; ?>" />
									</div>
                                    <?php continue;} ?>

                                    <?php if($subject->sub_type_id!=4 && is_array($items)) { ?>
                                    <?php foreach ($items as $item) { ?>
                                    <p class="xuan_p">
                                        <?php if($subject->isCheck()) { ?>
                                        
                                            <input type="checkbox" name="_<?php echo $item['fk_subject_id'];?>_checkbox_[]" value="<?php echo $item['id'];?>" <?php if($arrVal[$item['fk_subject_id']][$item['id']]['checked']=='1'){ echo "checked"; } ?> /><?php echo $item['s_answer'];echo "\r\n"; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="_<?php echo $item['fk_subject_id'];?>_radio_[]" id="_<?php echo $item['fk_subject_id'].'_'.$item['id'];?>_radio" value="<?php echo $item['id'];?>" <?php if($arrVal[$item['fk_subject_id']][$item['id']]['checked']=='1'){ echo "checked"; } ?> /><?php echo '<label name="_'.$item['fk_subject_id'].'_'.$item['id'].'_radio" id="_'.$item['fk_subject_id'].'_radio" for="_'.$item['fk_subject_id'].'_'.$item['id'].'_radio"'; if($arrVal[$item['fk_subject_id']][$item['id']]['checked']=='1'){ echo 'class="checked"'; } ?> > <?php echo $item['s_answer'];echo "</label>"; ?>
											
                                            <?php } ?>
											<?php if($item['s_url']!="" && file_exists("./public/".str_replace("./","",str_replace("../","",$item['s_url'])))){ ?><img src="<?php echo SITE_ROOT."public/".str_replace("./","",str_replace("../","",$item['s_url']))?>"></p><?php } ?>
										<?php if($arrVal[$items[0]['fk_subject_id']][$item['id']]['dj_additional']){?>	
										补充：	
                                        <?php echo $arrVal[$items[0]['fk_subject_id']][$item['id']]['dj_additional'];}?><?php } ?>
                                        </p>
										<input type="hidden" name="arrPostDf[<?php echo $subject->sub_Id; ?>]" value="<?php echo $arrDf[$subject->sub_Id]['df']; ?>" />
                                    <?php } ?>
                                </td>
                            </tr>

                            <?php
                        }
                    }
                    ?>

                </table>
				
                <div style="text-align: center"><?php echo str_replace("public/js/xheditor",VIEW_JS_URL."xheditor",$wj->getFootDesc()); ?></div>
				<br>
				 
		</form>
	  </section>
	</article>
    </div> 
</body>
</html>