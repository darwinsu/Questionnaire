<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php echo $sysname; ?></title>
<link href="<?php echo VIEW_CSS_URL; ?>style.css?v=<?php echo SYS_VERSION;?>" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo VIEW_CSS_URL; ?>superfish.css" media="screen">
</head>
<body>
<?php session_start();
$bindusers=$_SESSION['bindusers'];
$ulist=array();
	if(is_array($bindusers)){
		foreach($bindusers as $k=>$v){
			$ulist[$v->unitid]=$v->unitname;
		}
	}
?>
<form name="form1" method="post" action="<?php echo SITE_ROOT; ?>/Index/choosen/">
 <table align='center' class='table-4'> 
    <caption id='welcome'>请选择一个身份登陆：</caption>
    <tr>
      <td class='rowhead'></td>  
      <td><?php echo html::select('choosen',$ulist,cookie::get('unitid'));?></td>
    </tr>  
	<tr>
	  <td colspan='2' class='a-center'><input type="hidden" name="isa" value="<?php echo $isa;?>" /><input type="hidden" name="username" value="<?php echo $username;?>" /><input type="hidden" name="uid" value="<?php echo $uid;?>"/><?php echo html::submitButton('确定').'   或者 ';?>[ <a href="javascript:top.location.href='<?php echo SITE_ROOT; ?>Index/logout/';"  >注销登陆</a> ]</td>
	</tr>
  </table>
</a> 
</body>
</html>