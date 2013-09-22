<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0">
	<title>手机问卷登录页面</title>
	<link rel="stylesheet" href="<?php echo VIEW_CSS_URL;?>wap_style.css"/>
</head>
<body>
	<!--header!-->
	<header>
		<a class="headerl" style="visibility: hidden;">
			<img src="<?php echo VIEW_PIC_URL;?>returnbtn.png" alt=""/>
		</a>
		<div class="headerm">
			<img src="<?php echo VIEW_PIC_URL;?>logo.png" alt=""/>
		</div>
		<a class="headerr" style="visibility: hidden;">
			<span>已回答</span>
		</a>
	</header>
	<!-- header end!-->
	
	<article>
	  <section class="content"> 
      <form name="form1" method="post" action="<?php echo SITE_ROOT; ?>index.php" >
		  <div class="info"> <h1><?php echo $q_title;?></h1></div>
		   <article>
			 <section class="content">
				 <div class="actionInfo">
					 <p class="actionContent">
						<?php echo $topdesc;?>
					 </p>
			</div>
			 </section>
			 <section class="login">
				 <p>该问卷需要登录才能作答请<a class="lo" href="#" onClick="dj.submit();">登录</a></p>
			 </section>
		   </article>
		  
	        <div class="btn">
            <input type="hidden" name="wjid" value="<?php echo $wjid;?>" />
				<input type="submit" class="submitButton" value="提交"/>
		   </div>
		  </form>
	  </section>
	</article>
	
</body>
</html>