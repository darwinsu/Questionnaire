<script type="text/javascript" src="<?php echo VIEW_JS_URL;?>jquery-1.7.2.min.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript" src="<?php echo VIEW_JS_URL; ?>common.js?v=<?php echo SYS_VERSION;?>"></script>
<script type="text/javascript">
	function errorMSG(msg){
		var mbody='<tr >'
			+'<td style="padding-left:15px;"><br>对不起，您无 '+msg+' 操作权限<br></td>'
			+'</tr>';
		$('#mbody').empty().html(mbody);
		$("#mbodys #mbody").empty().html(mbody);				
		parent.asyncbox.open({
			title  : '提示信息',
			id: 'mbodys',
			width:400,
			height:120,
			html:$('#errormsg').html()
		});						
	}
</script>