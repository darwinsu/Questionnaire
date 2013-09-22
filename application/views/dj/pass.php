<?php 
include_once(TPL_DIR.'common/header.php');
?>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>head.css?v=<?php echo SYS_VERSION;?>"/>
<link rel="stylesheet" href="<?php echo VIEW_CSS_URL; ?>lab.css?v=<?php echo SYS_VERSION;?>"/>
<body style="width:45%">
<div style="height:200px; margin:0px auto;">&nbsp;</div>
    <form name="dj" action="../../../start/wjid/<?php echo $wjid?>/" method="POST">
    <table class="tableClass">
        <thead>
            <tr>
                <th align="center" colspan="2">密码验证</th>
            </tr>
        </thead>
        <tbody id="tbody">	
            <tr>
                <th>请输入密码:</td>
                <td><input id="search_item_input"  size="20"   type="password" name="passdj" class="isearcher_input_words"/></td>
            </tr>
            <tr>
                <th align="right"><input type="hidden" id="userid" name="userid" value="<?php echo $userid?>" />&nbsp;</td>
                <td>
                	<input id="search_item_btn" class="isearcher_submit_button" type="submit" value="确认提交" />&nbsp;
                    <input id="search_item_btn" class="isearcher_submit_button" type="button" onClick="location.href='<?php echo SITE_ROOT.'main/';?>'" value="返 回" />
                </td>
            </tr>
        </tbody>
        
        <tfoot id="tfoot">
        </tfoot>	
    </table>
	</form>
<?php include(TPL_DIR.'common/common_js.php');?>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/dj.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>dcwj/popup.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>jquery-common.js?v=<?php echo SYS_VERSION;?>"></script>
<script language="javascript">
	var imports='请输入密码:';
	var passerror='密码错误';
</script>
<?php echo $passMsg;?>
</body>
</html>