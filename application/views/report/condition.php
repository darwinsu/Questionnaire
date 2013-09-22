<?php 
//*定义数据
include_once(TPL_DIR.'common/header.php');
?>

<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>

<span class="blank"></span>
    <div class="Menu">
        <div class="analytical_menu clearfix">
            <a href="<?php echo SITE_ROOT.'report/index/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >常规分析</a>
            <a  class="active" href="<?php echo SITE_ROOT.'report/condition/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >条件分析</a>
            <a href="<?php echo SITE_ROOT.'report/cross/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >交叉分析</a>
        </div>
	</div>

<div style="text-align:center;border:1px solid #8c8c8c;border-right:1px solid #8c8c8c;border-bottom:none;border-top:none;width:100%;clear:both;height:40px;line-height:40px;background:#c6e1fd;"><?php echo $wj->getTitle(); $wjall=$wj->getAlldata();?></div>
<table class="tableClass">
	<tr>
        <td width="30%">创建日期</td>
		<td width="20%"><?php echo date("Y-m-d",$wjall[0]['q_start']); ?></td>
		<td width="30%">结束时间</td>
		<td width="20%"><?php echo date("Y-m-d",$wjall[0]['q_end']); ?></td>
    </tr>
	<tr>
        <td width="30%">调查时间</td>
		<td width="20%"><?php echo ($wjall[0]['q_end']-$wjall[0]['q_start'])/(3600*24) + 1; ?>天</td>
		<td width="30%">已进行时间</td>
		<td width="20%"><?php if(time()>$wjall[0]['q_end']) echo '已结束'; else echo ceil((time()-$wjall[0]['q_start'])/(3600*24))."天"; ?></td>
    <tr>
        <td colspan="6"><?php echo $wj->getTopDesc(); ?></td>
    </tr>
</table>

<form onSubmit="if(!validate()) return false;" name="subject_<?php echo $item['fk_subject_id'];?>" action="../../../docondition/" method="POST">
<?php
if(!empty($subjects))
{
    foreach($subjects as $subject)  { ?>
        <?php $items = $subject->getSubjectItems();?>
        <?php if(count($items) > 1) { ?>
<table class="tableClass" width="75%">
    <tr>
        <td style="width: 40%"><?php echo $subject->getSubjectTitle();?></td>
        <td style="width: 60%">
		<div><input type='checkbox' name='subcheck[]' id='subcheck' value="<?php echo $subject->getSubjectid();?>"><input type="button" name="buts<?php echo $subject->getSubjectid();?>" onClick="subshow('#subs<?php echo $subject->getSubjectid();?>')" value="设置条件"></div>
		<div id='subs<?php echo $subject->getSubjectid();?>' style="display:none">
		 等值关系：<input type="radio" name="_sub[<?php echo $subject->getSubjectid();?>][condition]" value="isEquals" checked="true"/>等于
            <input type="radio" name="_sub[<?php echo $subject->getSubjectid();?>][condition]" value="isUnequals"/>不等于<br>
            连接关系：<input type="radio" name="_sub[<?php echo $subject->getSubjectid();?>][condition2]" value="isOr" checked="true"/>或者
            <input type="radio" name="_sub[<?php echo $subject->getSubjectid();?>][condition2]" value="isAnd"/>并且
            <input type="hidden" name="_sub[<?php echo $subject->getSubjectid();?>][subjectid]" value="<?php echo $subject->getSubjectid();?>"><input type="hidden" name="<?php echo $subject->getSubjectid();?>" id="<?php echo $subject->getSubjectid();?>" value="<?php echo $subject->getSubjectTitle();?>"> <br>
		值：
                    <select name='_sub[<?php echo $subject->getSubjectid();?>][itemsid][]' id='_subs<?php echo $subject->getSubjectid();?>' MULTIPLE>
                        <?php foreach ($items as $it) { ?>
                        <option value="<?php echo $it['id'];?>"><?php echo $it['s_answer']; ?></option>
                        <?php } ?>
                    </select>
			<br>
			按住ctrl 可以多选
            
           
            <input type="hidden" name="_sub[<?php echo $subject->getSubjectid();?>][wjid]" value="<?php echo $wj->getWjId(); ?>">
        </div></td>
    </tr>
</table>
            <?php } ?>
        <?php
    }
}
    ?>
	
<div style="text-align: center">

    结果项：
	<select name='result' id='result'>
		<?php foreach($subjects as $subject)  {
         $items = $subject->getSubjectItems(); if(count($items) > 1) { ?>
        <option value="<?php echo $subject->getSubjectid();?>"><?php echo $subject->getSubjectTitle();?></option>
        <?php
        }
        } ?>
    </select>&nbsp;&nbsp;&nbsp;<input type="submit" value="分析">&nbsp;&nbsp;&nbsp;<input id="short_edit_back" class="isearcher_submit_button" onClick="javascript:location.href='<?php echo SITE_ROOT; ?>Quest?pageno=<?php echo $_GET['pageno'];?>'" type="button" value="返 回"/>
</div>
</form>
<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js?v=<?php echo SYS_VERSION;?>"></script>
<script language="javascript">
function subshow(id){
	if($(id).css("display")=='none'){$(id).show();}else{$(id).hide();}
}
function validate(){
var obj=$('input[id="subcheck"]');
	if(inputValue(obj)){
		for(var i=0; i<obj.length; i++){  
					if(obj[i].checked) {
						if($('#result').val()==obj[i].value){alert('条件项与结果项不能相同');return false;}
						eval("var subid='#_subs"+obj[i].value+" option:selected'");
						eval("var subtitle='#"+obj[i].value+"'");
						var fruit = "";
						$(subid).each(function() {
						fruit += $(this).text()+',';
						});
						if(fruit){
						//return true;
						}else{
						alert('题目:'+$(subtitle).val()+' ,请选择对应答案匹配条件');
						return false;
						}
											
				  }
		
		}
		return true;
	}else{
	alert('请选择题目匹配条件');
	return false;
	}
}
</script>
</body>
</html>