<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="<?php echo $charset; ?>">
</style>	
    <title><?php echo $sysname; ?></title>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>common.css"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>main.css"/>
    <script type="text/javascript" src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
    <script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js"></script>
	<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>public.js"></script>
	<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>AsyncBox.v1.4.js"></script>
	<script type="text/javascript">
		function asyncboxPreview(titles,ids,urls){
			 	asyncbox.open({
				title  : titles,width:800,height:500,
				id: ids,
				url:urls
				});
		}
		function asyncboxClose(titles,ids,urls){
			 	$('.asyncbox_close').trigger('click');
		}
		$(document).ready(function(e) {
		$('#ebodys #q_edit_save').live('click',function(){
			 document.mainFrame.a();
				});
		});		
</script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
	<script>
		function wjsel(){ 
			document.mainFrame.location.href='<?php echo SITE_ROOT.'Quest'; ?>/?wjmc='+document.getElementById('wjmc').value;
		}
        function myshow(){
            alert('shit');
        }
	</script>
<link href="<?php echo VIEW_CSS_URL; ?>asyncbox.css" type="text/css" rel="stylesheet" />
</head>
<body>
<!-- head!-->
<div class="top">
    <div class="head">
        <a href="" title="logo" class="logo">
            <img src="<?php echo VIEW_PIC_URL; ?>logo.gif" alt="logo" width="140" height="50"/>
        </a>
        <ul class="menu">
            <li class="active">首页</li>
            <li>应用介绍</li>
            <li>下载中心</li>
            <li>帮助中心</li>
            <li>论坛</li>
            <li>开放平台</li>
            <li>意见提交</li>
        </ul>
        <ol class="login">
			<a href=""><?php echo $username; ?></a>
            <a href="javascript:top.location.href='<?php echo SITE_ROOT; ?>Index/logout/';" >注销</a>
            <a href="" class="username"><img src="<?php echo VIEW_PIC_URL; ?>username.png" alt="<?php echo $username; ?>"/></a>
        </ol>
    </div>
</div>
<div class="searchBox">
    <div class="sb clearfix">
        <ul>
            <li class="liMenu"><img src="<?php echo VIEW_PIC_URL; ?>menu.png" alt=""/></li>
            <li>

                <div class="inputBox">
                    <form action="" method="">
                        <input type="text" class="inputText" name="wjmc" id="wjmc"  value="问卷调查" autocomplete="off"/>
                        <span></span>
                        <input  type="button" class="searchBtn" onClick="wjsel()">
                    </form>
                </div>
            </li>
        </ul>
         
    </div>


</div>

<div class="main clearfix">
    <div class="left">
        <ul><?php if(in_array('quest#quest#sel',$rights)||in_array('quest#style#sel',$rights)||in_array('quest#subject#sel',$rights)||in_array('quest#draft#sel',$rights)||in_array('quest#recycle#sel',$rights)){ ?>
            <li class="collapsed">
        <div lang="moduleDivSpan" class="open" id=3 onClick="changeDiv(this,'2');"><img id='img2' src="<?php echo VIEW_PIC_URL; ?>down_r.png" alt=""/><strong>问卷</strong></div>
              <div class="moduleDivContent" id="2"> <? //print_r($rights);?>
                <ul><?php if(in_array('quest#style#sel',$rights)){ ?>
                    <li> <a href="<?php echo SITE_ROOT.'Quest/style'; ?>" target="mainFrame" class="active">问卷分类</a> </li>
					<?php } if(in_array('quest#quest#sel',$rights)){?>
                    <!--li> <a href="<?php echo SITE_ROOT.'Quest/type'; ?>" target="mainFrame" class="active">问卷类型</a> </li-->
                    <li> <a href="<?php echo SITE_ROOT.'Quest'; ?>" target="mainFrame" class="active">问卷列表</a> </li>
					<?php } if(in_array('quest#subject#sel',$rights)){?>
                    <!--li> <a href="<?php echo SITE_ROOT.'Quest/subType'; ?>" target="mainFrame" class="active">题目类型</a> </li-->
                    <li> <a href="<?php echo SITE_ROOT.'Quest/subject'; ?>" target="mainFrame" class="active">题目设置</a> </li>
					<?php } ?>
					<?php if(in_array('quest#draft#sel',$rights)){?>
                    <li> <a href="<?php echo SITE_ROOT.'Quest/draft'; ?>" target="mainFrame" class="active">草稿</a> </li>
					<?php } if(in_array('quest#recycle#sel',$rights)){?>
					<li> <a href="<?php echo SITE_ROOT.'Quest/recycle'; ?>" target="mainFrame" class="active">回收站</a> </li>
					<?php } ?>
                </ul>
			  </div>	
            </li>
			<?php } ?>
			<?php if(in_array('dj#list#sel',$rights)||in_array('dj#my#sel',$rights)){ ?>
            <li class="collapsed">
				<div lang="moduleDivSpan" class="open" id=4 onClick="changeDiv(this,'5');"><img id='img5' src="<?php echo VIEW_PIC_URL; ?>down_r.png" alt=""/><strong>答卷</strong></div>
              <div class="moduleDivContent" id="5" style="display:none;"> 
                <ul>
				<?php if(in_array('dj#list#sel',$rights)){ ?>
                    <!--li> <a href="<?php echo SITE_ROOT.'Dj/'; ?>" target="mainFrame" class="active">问卷作答</a> </li-->
				<?php } if(in_array('dj#my#sel',$rights)){?>
                    <li> <a href="<?php echo SITE_ROOT.'Dj/my'; ?>" target="mainFrame" class="active">答卷列表</a> </li>
				<?php } ?>	
                </ul>
			  </div>	
            </li>
			<?php } ?>
			<?php if(in_array('system#user#edit',$rights)||in_array('system#rights#sel',$rights)){ ?>
            <li class="collapsed">
			<div lang="moduleDivSpan" class="open" id=8 onClick="changeDiv(this,'9');"><img id='img9' src="<?php echo VIEW_PIC_URL; ?>down_r.png" alt=""/><strong>权限管理</strong></div>
              <div class="moduleDivContent" id="9" style="display:none;"> 
                <ul>
				<?php if(in_array('system#user#edit',$rights)){ ?>
                    <li> <a href="<?php echo SITE_ROOT.'Competence/user'; ?>" target="mainFrame" class="active">用户管理</a> </li>
				<?php } if(in_array('system#rights#sel',$rights)){?>	
                    <li> <a href="<?php echo SITE_ROOT.'Competence/part'; ?>" target="mainFrame" class="active">角色管理</a> </li>
				<?php } ?>		
                </ul>
			</div>		
            </li>
			<?php } ?>
        </ul>

    </div>
    <div class="right" id="right">

        <iframe id="mainFrame" name="mainFrame" src="<?php echo SITE_ROOT.'Quest';?>" frameborder="0" scrolling="no"  width="100%" height="600"></iframe>

    </div>
</div>


<div class="footer"></div>
<!-- footer!-->
<script type="text/javascript">



</script>
</body>
</html>


<link href="<?php echo VIEW_CSS_URL; ?>style.css" type="text/css" rel="stylesheet" />
<!--导航菜单开始-->
<link rel="stylesheet" type="text/css" href="<?php echo VIEW_CSS_URL; ?>superfish.css" media="screen">
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>superfish.js"></script>





<script type="text/javascript">

// initialise plugins
jQuery(function(){
	jQuery('ul.sf-menu').superfish();

});

function modPass()
{
	var str = window.showModalDialog("modPass.php","Dialog","dialogHeight: 150px; dialogWidth: 380px; center: yes; help: no; resizable:no; status:no");
}

window.onload=function()
{
	try
    {
        //if(navigator.userAgent.indexOf("MSIE 6.0")==-1)
        //{
            var obj=document.getElementById('mainFrame');
            if(obj)
            {
               obj.style.height=(document.documentElement.clientHeight-70)+"px";
            }
       // }
    }
    catch(e)
    {}
}
</script>
<script>
function reinitIframe(){

		var iframe = document.getElementById("mainFrame");
		
		try{
		var h = iframe.contentWindow.document.body.clientHeight;
		
		
		iframe.style.height =  600 > h ? "600px":h+"px";
		
		}catch (ex){}
		
		}

window.setInterval("reinitIframe()", 200);


</script>
<!--导航菜单结束-->
</head>

<body>


<!--底部信息-->
<div id="foot"> </div>
</body>
</html>