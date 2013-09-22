<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>客户端-身份选择页</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?php echo VIEW_CSS_URL; ?>wap_style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="full_bg1">
<?php
$ulist=array();
	if(is_array($bindusers)){
		foreach($bindusers as $k=>$v){
			$ulist[$v->unitid]=$v->unitname;
		}
	}
?>
<form name="form1" method="post" action="<?php echo SITE_ROOT; ?>/IndexWap/choosen/">
	<div class="logo_bg"></div>
	<div class="signin">
		<div class="signin_top">请选择一个身份登录</div>
		<div class="text_box">
			<?php echo html::select('choosen',$ulist,cookie::get('unitid'));?>
		</div>
	</div>
	<div class="signin_1"><input type="hidden" name="isa" value="<?php echo $isa;?>" /><input type="hidden" name="username" value="<?php echo $username;?>" /><input type="hidden" name="pass" value="<?php echo $pass;?>"/> <a href="#" title="用户登录" class="button_1" onClick="form1.submit()">用户登录</a> 或者 <a href="javascript:top.location.href='<?php echo SITE_ROOT; ?>IndexWap/logout/" title="退出" class="a_1" onfocus="this.blur()">退出</a> </div>
	<div class="bg_box"></div>
</body>
</html>