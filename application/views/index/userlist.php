<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php echo $sysname; ?></title>
<link href="<?php echo VIEW_CSS_URL; ?>style.css" type="text/css" rel="stylesheet" />
</head>
<body style="color:#999; font-size:12px;">
<?php 
$ulist=array();
	if(is_array($bindusers)){
		foreach($bindusers as $k=>$v){
			$ulist[$v->unitid]=$v->unitname;
		}
	}
?>
<form name="form1" method="post" action="<?php echo SITE_ROOT; ?>/Index/choosen/">
<table align='center' class='table-4' style="margin:0 auto;"> 
    <tr>
	    <td class='rowhead' height="50" colspan="2">&nbsp;  </td>
    </tr>
    <tr>
    	<td class='rowhead' colspan="2" style="font-size:14px;"> 请选择一个身份登陆：</caption></td>
    </tr>
    <tr>
    	<td class='rowhead' colspan="2"> <?php echo html::select('choosen',$ulist,cookie::get('unitid'));?></td>
    </tr>  
    <tr>
        <td colspan='2' class='a-center' valign="middle">
        	<input type="hidden" name="referer" value="<?php echo $referer;?>" />
            <input type="hidden" name="isa" value="<?php echo $isa;?>" />
            <input type="hidden" name="username" value="<?php echo $username;?>" />
            <input type="hidden" name="sid" value="<?php echo $sid;?>"/>
            <input type="submit" class="button-s" value="确定" id="submit" style="background:#336699;font-weight:700;color:#FFF;font-size:14px;padding:4px;margin:4px 0px;border:none;cursor:pointer;"/> 或者 [<a href="javascript:top.location.href='<?php echo SITE_ROOT; ?>Index/logout/';"  >注销登陆</a>]
        </td>
    </tr>
</table>
</form>
</body>
</html>