<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<title><?php echo $sysname; ?></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url(<?php echo VIEW_PIC_URL; ?>login/login_01.gif);
	overflow:hidden;
}
.STYLE3 {font-size: 12px; color: #FFFFFF; }
.STYLE4 {
	color: #FFFFFF;
	font-family: "方正大黑简体";
	font-size: 50px;
}
-->
</style>
<script language="javascript">
function checkForm(form)
{
    if(form.username.value==""){
            alert('请输入用户名！');
			document.form1.username.focus();
            return false;
    }

    if(form.password.value==""){
        alert("请输入密码！");
		document.form1.password.focus();
        return false;
    }

    return true;
}
</script>
</head>

<body onLoad="document.form1.username.focus();">

<form name="form1" method="post" action="<?php echo SITE_ROOT; ?>/Index/login/">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="<?php echo VIEW_PIC_URL; ?>login/login_03.gif">&nbsp;</td>
        <td width="876"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="299" valign="top" background="<?php echo VIEW_PIC_URL; ?>login/login_01.jpg">&nbsp;</td>
          </tr>
          <tr>
            <td height="54"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="394" height="69" background="<?php echo VIEW_PIC_URL; ?>login/login_02.jpg">&nbsp;</td>
                <td width="199" background="<?php echo VIEW_PIC_URL; ?>login/login_03.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="22%" height="22"><div align="center"><span class="STYLE3">用户名</span></div></td>
                    <td width="51%" height="22"><input name="username" type="text" size="12" value="54894128@qq.com" style="height:20px;background-color:#032e49; color:#88b5d1; border:solid 1px #88b5d1;" tabindex="1"  onKeyUp="document.form1.password.value=''"/></td>
                    <td width="27%" rowspan="2"><div align="right"><input type="image" src="<?php echo VIEW_PIC_URL; ?>login/d1l.gif" width="42" height="51" tabindex="3" onClick="if(checkForm(form)){ document.form1.submit(); }return false;" /></div></td>
                  </tr>
                  <tr>
                    <td height="22" valign="middle"><div align="center"><span class="STYLE3">密&nbsp; 码</span></div></td>
                    <td height="22" valign="bottom"><input name="password" type="password" size="12" tabindex="2" value="54894128" style="height:20px;background-color:#032e49; color:#88b5d1; border:solid 1px #88b5d1;" /></td>
                    </tr>
                  
                </table></td>
                <td width="283" background="<?php echo VIEW_PIC_URL; ?>login/login_04.jpg">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="225" background="<?php echo VIEW_PIC_URL; ?>login/login_05.jpg">&nbsp;</td>
          </tr>
        </table></td>
        <td background="<?php echo VIEW_PIC_URL; ?>login/login_03.gif">&nbsp;</td>
      </tr>
      <tr>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>
