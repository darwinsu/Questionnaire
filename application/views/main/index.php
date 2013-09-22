<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="<?php echo $charset; ?>" />
    <title><?php echo $sysname; ?></title>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>common.css?v=<?php echo SYS_VERSION;?>"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>main.css?v=<?php echo SYS_VERSION;?>"/>
    <link href="<?php echo VIEW_CSS_URL; ?>style.css?v=<?php echo SYS_VERSION;?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo VIEW_JS_URL;?>uploadify/uploadify.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo VIEW_CSS_URL; ?>asyncbox.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo VIEW_CSS_URL; ?>loading.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo VIEW_CSS_URL;?>zTreeStyle.css" type="text/css">

</head>
<body>
<div class="searchBox">
    <div class="sb clearfix">
        <ul>
            <li class="liMenu"><img src="<?php echo VIEW_PIC_URL; ?>menu.png" alt=""/></li>
            <li style="display:none;">
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
        <ul><?php if(in_array('quest#quest#sel',$rights)){ ?>
            <li class="collapsed">
                <div lang="moduleDivSpan" class="open" id=3 style="text-align:left;margin-left:40px; height:54px; line-height:54px;"><img id='img2' src="<?php echo VIEW_PIC_URL; ?>down_r.png" alt=""/><strong>问卷</strong></div>
                <div class="moduleDivContent" id="2"> <? //print_r($rights);?>
                    <ul><!--<?php if(in_array('quest#style#sel',$rights)){ ?>
                    <li> <a id='ac1' href="<?php echo SITE_ROOT.'Quest/style'; ?>" target="mainFrame"  onClick="aclas('#ac1')"><font>问卷分类</font></a> </li>
					<?php } if(in_array('quest#quest#sel',$rights)){?>
                    <li> <a href="<?php echo SITE_ROOT.'Quest/type'; ?>" target="mainFrame" class="active">问卷类型</a> </li>-->
                        <li> <a id='ac2' href="<?php echo SITE_ROOT.'Quest'; ?>" target="mainFrame" class="on" onClick="aclas('#ac2')"><font>问卷列表</font></a> </li>
                        <?php } if(in_array('quest#subject#sel',$rights)){?>
                        <!--li> <a href="<?php echo SITE_ROOT.'Quest/subType'; ?>" target="mainFrame" class="active">题目类型</a> </li
                        <li> <a id='ac3' href="<?php echo SITE_ROOT.'Quest/subject'; ?>" target="mainFrame" class="active" onClick="aclas('#ac3')"><font>题目设置</font></a> </li>-->
                        <?php } ?>
                        <?php //if(in_array('quest#draft#sel',$rights)){?>
                        <!--<li> <a id='ac4' href="<?php echo SITE_ROOT.'Quest/draft'; ?>" target="mainFrame" class="active" onClick="aclas('#ac4')"><font>草稿</font></a> </li>-->
                        <?php //}
					if(in_array('quest#recycle#sel',$rights)){?>
                        <li> <a id='ac5' href="<?php echo SITE_ROOT.'Quest/recycle'; ?>" target="mainFrame" class="active" onClick="aclas('#ac5')"><font>回收站</font></a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php } ?>
            <?php if(in_array('dj#list#sel',$rights)||in_array('dj#my#sel',$rights)){ ?>
            <li class="collapsed">
                <div lang="moduleDivSpan" class="open" id=4  style="text-align:left; margin-left:40px;"><img id='img5' src="<?php echo VIEW_PIC_URL; ?>down_r.png" alt=""/><strong>答卷</strong></div>
                <div class="moduleDivContent" id="5">
                    <ul>
                        <?php //if(in_array('dj#list#sel',$rights)){ ?>
                        <!--li> <a href="<?php echo SITE_ROOT.'Dj/'; ?>" target="mainFrame" class="active">问卷作答</a> </li-->
                        <?php //} if(in_array('dj#my#sel',$rights)){?>
                        <!--li> <a id='ac6' href="<?php echo SITE_ROOT.'Dj/my'; ?>" target="mainFrame" class="active" onClick="aclas('#ac6')"><font>所有答卷</font></a> </li>-->
                        <?php //} ?>
                        <li> <a id='ac9' href="<?php echo SITE_ROOT.'Dj/undo'; ?>" target="mainFrame" class="active" onClick="aclas('#ac9')"><font>未回答</font></a> </li>
                        <li> <a id='ac10' href="<?php echo SITE_ROOT.'Dj/done'; ?>" target="mainFrame" class="active" onClick="aclas('#ac10')"><font>已回答</font></a> </li>
                    </ul>
                </div>
            </li>
            <?php } ?>
            <?php if(in_array('system#user#edit',$rights)||in_array('system#rights#sel',$rights)){ ?>
            <li class="collapsed">
                <div lang="moduleDivSpan" class="open" id=8  style="text-align:left; margin-left:40px;"><img id='img9' src="<?php echo VIEW_PIC_URL; ?>down_r.png" alt=""/><strong>权限</strong></div>
                <div class="moduleDivContent" id="9">
                    <ul>
                        <?php if(in_array('system#user#edit',$rights)){ ?>
                        <li> <a id='ac7' href="<?php echo SITE_ROOT.'Competence/user'; ?>" target="mainFrame" class="active" onClick="aclas('#ac7')"><font>用户管理</font></a> </li>
                        <?php } if(in_array('system#rights#sel',$rights)){?>
                        <li> <a id='ac8' href="<?php echo SITE_ROOT.'Competence/part'; ?>" target="mainFrame" class="active" onClick="aclas('#ac8')"><font>权限设置</font></a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php } ?>
        </ul>

    </div>
    <div class="right" id="right">
        <?php
if(!in_array('quest#quest#sel',$rights)){
?>
        <iframe id="mainFrame" name="mainFrame" src="<?php echo SITE_ROOT.'Dj/undo';?>" frameborder="0" scrolling="no"  width="100%" height="600"></iframe>
        <?php }else{?>
        <iframe id="mainFrame" name="mainFrame" src="<?php echo SITE_ROOT.'Quest';?>" frameborder="0" scrolling="no"  width="100%" height="600"></iframe>
        <?php }?>
    </div>
</div>

<div class="footer"></div>
<script src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
<script src="<?php echo VIEW_JS_URL;?>dcwj/ZeroClipboard.js "></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>public.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>asyncbox/AsyncBox.v1.4.5.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>uploadify/jquery.uploadify.v2.1.4.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>loading.js"></script>
<script type="text/javascript">
    <?php
    if(!in_array('quest#quest#sel',$rights)){?>
        $('#ac9').addClass('on');
    <?php }?>

    function reinitIframe(){
        var iframe = document.getElementById("mainFrame");
        try{
            var h = iframe.contentWindow.document.body.clientHeight;
            iframe.style.height =  600 > h ? "600px":h+"px";
        }catch (ex){}
    }
    window.setInterval("reinitIframe()", 200);
    function asyncboxPreview(titles,ids,urls){
        asyncbox.open({
            title  : titles,width:1010,height:500,
            id: ids,
            url:urls
        });
    }
    function asyncboxClose(titles,ids,urls){
        $('.asyncbox_close').trigger('click');
    }

    $(document).ready(function(e){
        $(document).ui_loading({
            overlay:false,
            opacity:0,
            supportIframe:true,
            message:'数据加载中，请稍后'
        });

        $('.left a').click(function(e) {
            $('#mainFrame').trigger('beforeload');
        });


        $('#ebodys #q_edit_save').live('click',function(){
            //document.mainFrame.a();
        });
    });

    function getElementsByClassName(n) {
        var classElements = [],allElements = document.getElementsByTagName('*');
        for (var i=0; i< allElements.length; i++ )
        {
            if (allElements[i].className == n ) {
                classElements[classElements.length] = allElements[i];
            }
        }
        return classElements;
    }

    function box_close(){
        var redClassElements = getElementsByClassName('asyncbox_close');
        redClassElements[0].click();
    }

    //明码
    function pass_show(){
        if($('#ebodys #pass').val()){passtopass()};
    }

    function passshow(){
        $('#ebodys #pass_bak').val($('#ebodys #pass').val());
    }

    function passto(){
        if($('#ebodys #pass_bak').val()!='●●●●●●'){
            $('#ebodys #pass').val($('#ebodys #pass_bak').val());
        }
    }

    function passtopass(){
        if($('input:checkbox[name="showpass"]:checked').val()){
            passshow();
        }else{
            if($('#ebodys #pass').val()) $('#ebodys #pass_bak').val('●●●●●●');
        }
    }
    function nov(id){
        if(id=='5'){   //投票时间设置为0
            $('#ebodys #duration').val('0');
            $('#ebodys #duration').attr("readonly","readonly").css('background', '#ddd');
        }else{
            $('#ebodys #duration').removeAttr("readonly").css('background', '');
        }
    }

    //删除问题数据
    function del(t,conditions,pageno){
        //mainFrame.del(t,conditions,pageno);
    }
    function aclas(id){
        $(".left a.on").removeClass("on");
        $(id).addClass("on");

    }

    function wjsel(){
        document.mainFrame.location.href='<?php echo SITE_ROOT.'Quest'; ?>/?wjmc='+document.getElementById('wjmc').value;
    }

    function modPass()
    {
        var str = window.showModalDialog("modPass.php","Dialog","dialogHeight: 150px; dialogWidth: 380px; center: yes; help: no; resizable:no; status:no");
    }

    function upfile(tdid,s_url_name,s_url,uid){
        $(tdid).empty().html('<div id="fileQueue"></div> <input id="s_url_name" name="s_url_name" type="file">&nbsp;<a href="javascript:if($(\'#fileQueue\').formhtml()){$(\''+uid+'\').uploadifyUpload()}else{alert(\'请先浏览选择上传图片\')}">上传</a>|<a href="javascript:$(\''+uid+'\').uploadifyClearQueue();"> 取消</a>');
        $(s_url_name).uploadify({
            'uploader': '<?php echo VIEW_JS_URL;?>uploadify/uploadify.swf',
            'script': '<?php echo VIEW_JS_URL;?>uploadify/uploadify.php',
            'sizeLimit': 1000*1024,
            'fileExt': '*.jpg;*.jpeg;*.png;*.gif',
            'fileDesc': '图片(jpg,jpeg,png,gif)',
            'cancelImg': '<?php echo VIEW_JS_URL;?>uploadify/cancel.png',
            'folder': '',
            'queueID': 'fileQueue',
            'buttonImg': '<?php echo VIEW_PIC_URL;?>upfiles.jpg',
            'auto': false,
            'onComplete': function(event, queueID, fileObj, response, data){
                var value = response;
                $(s_url).val(value);
                window.opener.unlockui(value);
            },
            'multi': false,
            'sizeLimit': 10737418240
        });
    }
    (function($){
        var oldHTML = $.fn.html;
        $.fn.formhtml = function() {
            if (arguments.length) return oldHTML.apply(this,arguments);
            $("input,button", this).each(function(){
                this.setAttribute('value',this.value);
            });

            $("textarea", this).each(function(){
                this.innerHTML=this.value;
            });
            $(":radio,:checkbox", this).each(function(){
                if (this.checked)
                    this.setAttribute('checked', 'checked');
                else
                    this.removeAttribute('checked');
            });

            $("option", this).each(function(){
                if(this.selected) this.setAttribute('selected', 'selected');
                else this.removeAttribute('selected');
            });
            return oldHTML.apply(this);

        };
    })(jQuery);
</script>
</body>
</html>