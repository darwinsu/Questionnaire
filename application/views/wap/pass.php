<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<title>请输入密码</title>
	<link rel="stylesheet" href="<?php echo VIEW_CSS_URL;?>wap_style.css"/>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>xui-2.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/wap_common.js"></script>
<script language="javascript">
		var imports='请输入密码:';
		var passerror='密码错误';
		function passok(){
			if(document.getElementById("passdj").value=="") 
			{ 
				alert('请输入密码');
			}else{
			    dj.submit();
			}
			
		}
	</script>	
	<?php echo $passMsg;?>
</head>
<body>
	<!--header!-->
	<div class="full_bg2">
	<div class="logo_bg"><a href="<?php echo SITE_ROOT; ?>Wap" title="后退" class="logo_top3" ></a></div>
	<div class="last_time">
	<div class="main">
	  <div class="list" align="center">	 
       <form name="dj" action="<?php echo SITE_ROOT; ?>Wap/start/?wjid=<?php echo $wjid?>" method="POST">
			<ul>
			<li>
				 请输入密码:<input id="passdj"  size="20"   type="password" name="passdj"   class="isearcher_input_words"/>
			</li>
            <div class="btn">
				<input type="button" onClick="passok()" class="button_3" value="提交"/>
		   </div>
		 </ul>
         </form>
	  </div>
	  </div>
      </div>
  
</body>
</html>