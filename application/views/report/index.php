<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>91云办公|考试问卷</title>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>common.css?v=<?php echo SYS_VERSION;?>"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>main.css?v=<?php echo SYS_VERSION;?>"/>
</head>
<body>
<span class="blank"></span>
 <div class="Menu">
 <div class="analytical_menu clearfix">
    <a  class="active" href="<?php echo SITE_ROOT.'report/index/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >常规分析</a>
    <a href="<?php echo SITE_ROOT.'report/condition/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >条件分析</a>
    <a href="<?php echo SITE_ROOT.'report/cross/wjid/'.$wj->getWjId()."/?pageno=".$_GET['pageno']; ?>" >交叉分析</a>
 </div>
</div>

<div style="text-align:center;border:1px solid #8c8c8c;border-right:1px solid #8c8c8c;border-bottom:none;border-top:none;width:100%;clear:both;height:40px;line-height:40px;background:#c6e1fd;"><?php echo $wj->getTitle(); $wjall=$wj->getAlldata();?></div>

<table class="tableClass">
    
	<tr>
        <td width="30%">创建日期</th>
		<td width="20%"><?php echo date("Y-m-d",$wjall[0]['q_start']); ?></td>
		<td width="30%">结束时间</th>
		<td width="20%"><?php echo date("Y-m-d",$wjall[0]['q_end']); ?></td>
    </tr>
	<tr>
        <td width="30%">调查时间</th>
		<td width="20%"><?php echo ($wjall[0]['q_end']-$wjall[0]['q_start'])/(3600*24)+1; ?>天</td>
		<td width="30%">已进行时间</th>
		<td width="20%"><?php if(time()>($wjall[0]['q_end']+(3600*24))) echo '已结束'; elseif(floor((time()-$wjall[0]['q_start'])/(3600*24))<=0) echo '0'; else echo floor((time()-$wjall[0]['q_start'])/(3600*24))."天"; ?></td>
    </tr>
    <tr>
        <td>已收集样本：</td>
        <td colspan="3"><?php echo $report->getReportCount();
            $_SESSION['_rep'] = null;
            $_SESSION['_rep']['djcount']=$report->getReportCount(); ?>
        </td>
    </tr>
</table>
<div id="pic_horizbarex" style="margin:10px auto;width:100%;">
<?php

function FSubstr($title,$start,$len="",$magic=true) 
{
  /**
  *  powered by Smartpig
  *  mailto:d.einstein@263.net
  */

 if($len == "") $len=strlen($title);
 
 if($start != 0)
 {
  $startv = ord(substr($title,$start,1));
  if($startv >= 128)
  {
   if($startv < 192)
   {
    for($i=$start-1;$i>0;$i--)
    {
     $tempv = ord(substr($title,$i,1));
     if($tempv >= 192) break;
    }
    $start = $i;
   }
  }
 }
 
 if(strlen($title)<=$len) return substr($title,$start,$len);
 
 $alen   = 0;
 $blen = 0;
 
 $realnum = 0;
 
 for($i=$start;$i<strlen($title);$i++)
 {
  $ctype = 0;
  $cstep = 0;
  
  $cur = substr($title,$i,1);
  if($cur == "&")
  {
   if(substr($title,$i,4) == "&lt;")
   {
    $cstep = 4;
    $length += 4;
    $i += 3;
    $realnum ++;
    if($magic)
    {
     $alen ++;
    }
   }
   else if(substr($title,$i,4) == "&gt;")
   {
    $cstep = 4;
    $length += 4;
    $i += 3;
    $realnum ++;
    if($magic)
    {
     $alen ++;
    }
   }
   else if(substr($title,$i,5) == "&amp;")
   {
    $cstep = 5;
    $length += 5;
    $i += 4;
    $realnum ++;
    if($magic)
    {
     $alen ++;
    }
   }
   else if(substr($title,$i,6) == "&quot;")
   {
    $cstep = 6;
    $length += 6;
    $i += 5;
    $realnum ++;
    if($magic)
    {
     $alen ++;
    }
   }
   else if(preg_match("/&#(\d+);?/i",substr($title,$i,8),$match))
   {
    $cstep = strlen($match[0]);
    $length += strlen($match[0]);
    $i += strlen($match[0])-1;
    $realnum ++;
    if($magic)
    {
     $blen ++;
     $ctype = 1;
    }
   }
  }else{
   if(ord($cur)>=252)
   {
    $cstep = 6;
    $length += 6;
    $i += 5;
    $realnum ++;
    if($magic)
    {
     $blen ++;
     $ctype = 1;
    }
   }elseif(ord($cur)>=248){
    $cstep = 5;
    $length += 5;
    $i += 4;
    $realnum ++;
    if($magic)
    {
     $ctype = 1;
     $blen ++;
    }
   }elseif(ord($cur)>=240){
    $cstep = 4;
    $length += 4;
    $i += 3;
    $realnum ++;
    if($magic)
    {
     $blen ++;
     $ctype = 1;
    }
   }elseif(ord($cur)>=224){
    $cstep = 3;
    $length += 3;
    $i += 2;
    $realnum ++;
    if($magic)
    {
     $ctype = 1;
     $blen ++;
    }
   }elseif(ord($cur)>=192){
    $cstep = 2;
    $length += 2;
    $i += 1;
    $realnum ++;
    if($magic)
    {
     $blen ++;
     $ctype = 1;
    }
   }elseif(ord($cur)>=128){
    $length += 1;
   }else{
    $cstep = 1;
    $length +=1;
    $realnum ++;
    if($magic)
    {
     if(ord($cur) >= 65 && ord($cur) <= 90)
     {
      $blen++;
     }else{
      $alen++;
     }
    }
   }
  }
  
  if($magic)
  {
   if(($blen*2+$alen) == ($len*2)) break;
   if(($blen*2+$alen) == ($len*2+1))
   {
    if($ctype == 1)
    {
     $length -= $cstep;
     break;
    }else{
     break;
    }
   }
  }else{
   if($realnum == $len) break;
  }
 }
 
 unset($cur);
 unset($alen);
 unset($blen);
 unset($realnum);
 unset($ctype);
 unset($cstep);
 
 return substr($title,$start,$length);
}



    if(!empty($subjects))
    {
        foreach($subjects as $subject)  { ?>
<table class="tableClass">
    <tr>
        <th colspan="3" align="left" style="border-top:none;">
            <?php echo $subject->getSubjectTitle();
            $_SESSION['_rep']['subjects'][$subject->getSubjectid()]['title'] = $subject->getSubjectTitle().' ';?>
        </th>
    </tr>
    <?php $items = $subject->getSubjectItems();?>
    <?php if($items)foreach ($items as $itemkey => $item) { ?>

    <tr>
        <td style="width: 50%"><?php echo $item['s_answer'];echo "(".$item['s_value']."分)";$itemcount = $report->getReportItemCountByItemid($item['id']); ?></td>
        <td style="width: 30%"><?php  if($report->getReportCount()){?><img src="<?php echo SITE_ROOT; ?>chart.php?action=horizbarex&val=<?php echo round($itemcount/$report->getReportCount()*100,0); ?>" /><?php }?>&nbsp;</td>
        <td style="width: 20%"><?php if($report->getReportCount())echo round($itemcount/$report->getReportCount()*100,0); ?>%&nbsp;<?php echo $itemcount; ?>人</td>
    </tr>
    <?php $slen=strlen($item['s_answer']);$s_answer=$item['s_answer'];if($slen>20){$s_answer="\n\r";
		for($x=0;$x<ceil($slen/20);$x++){
		$s_answer.=FSubstr($item['s_answer'],$x*28,14)."\n\r";}} 
	
	$_SESSION['_rep']['subjects'][$subject->getSubjectid()]['items']['s_answer'][$itemkey] = $s_answer; ?>
    <?php $_SESSION['_rep']['subjects'][$subject->getSubjectid()]['items']['itemcount'][$itemkey] = $itemcount+0; ?>
    <?php  if($report->getReportCount())$_SESSION['_rep']['subjects'][$subject->getSubjectid()]['items']['scale'][$itemkey] = round($itemcount/$report->getReportCount()*100,0)+0; ?>
    <?php }  ?>
</table>
            <?php
        }
    } ?>
</div>	
<div id="pic_pie" style="display:none; margin:10px auto;width:100%;">
<?php
if(!empty($subjects))
{
foreach($subjects as $subject)  { if($_SESSION['_rep']['subjects'][$subject->getSubjectid()]['items'] ){?>
<img src="<?php echo SITE_ROOT; ?>chart.php?action=pie&val=<?php echo $subject->getSubjectid();?>" /><br>
<?php } }
}
?>
</div>
<div id="pic_bar" style="display:none;margin:10px auto;width:100%;">
<?php
if(!empty($subjects))
{
    foreach($subjects as $subject)  { if($_SESSION['_rep']['subjects'][$subject->getSubjectid()]['items'] ){?>
    <img src="<?php echo SITE_ROOT; ?>chart.php?action=bar&val=<?php echo $subject->getSubjectid();?>" /><br>
        <?php }}
}
?>
</div>
选择方式<select id="selid" name='selid' class="isearcher_select_list" onChange="pic_show(this.value)">
            <option value="horizbarex">横柱图</option>
            <option value="pie">饼图</option>
			<option value="bar">竖柱图</option>
        </select>&nbsp;&nbsp;&nbsp;<input id="short_edit_back" class="isearcher_submit_button" onClick="javascript:location.href='<?php echo SITE_ROOT; ?>Quest?pageno=<?php echo $_GET['pageno'];?>'" type="button" value="返 回"/>


<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js?v=<?php echo SYS_VERSION;?>"></script>
<script language="javascript">
	function pic_show(v){
		$("#pic_horizbarex").hide();
		$("#pic_pie").hide();
		$("#pic_bar").hide();
		eval('$("#pic_'+v+'").show()');
	}
</script>
</body>
</html>