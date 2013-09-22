<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>客户端-身份选择页</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?php echo VIEW_CSS_URL; ?>wap_style.css" rel="stylesheet" type="text/css" media="screen" />
<script>
function choosens(id){
	document.getElementById('choosen').value=id;
	document.forms["form1"].submit();
}
</script>
</head>
<body class="full_bg1">
<?php session_start();
$ulist=array();
	
?>
<form name="form1" id="form1" method="post" action="<?php echo SITE_ROOT; ?>Index/choosen/">
	<div class="logo_bg"></div>
	<div class="signin">
		<div class="signin_top">请选择一个身份登录</div>
		<div class="text_box">
			
		</div>
        <ul class="signin_ul">
        <?php
        	if(is_array($bindusers)){
				foreach($bindusers as $k=>$v){
					$ulist[$v->unitid]=$v->unitname;
					echo '<li><a href="#" title="'.$v->unitname.'" onClick=choosens("'.$v->unitid.'")>'.$v->unitname.'</a></li>';
				}
			}
		?>
		</ul>
	</div>
	<div class="signin_1"><input type="hidden" name="isa" value="<?php echo $isa;?>" /><input type="hidden" name="username" value="<?php echo $username;?>" /><input type="hidden" name="pass" value="<?php echo $pass;?>"/><input type="hidden" name="choosen" id="choosen" value="<?php echo $choosen;?>" /> <a href="<?php echo SITE_ROOT; ?>Index/logout/" title="退出" class="a_1" onfocus="this.blur()">退出</a> </div>
	<div class="bg_box"></div>
</body>
</html>