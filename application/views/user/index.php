<?php include(TPL_DIR.'common/header.php'); ?>
<form name="MyForm" method="post">
<input type="hidden" name="selid">
<input type="hidden" name="sys_action">
<input type="hidden" name="order" value="">
<input type="hidden" name="ys" value="<?php echo $_GET['page']; ?>">
<div class="main">
  <!--导航-->
  <div class="mnav"> <span class="b14"><?php echo $title; ?></span> </div>
  <!--按钮-->
  <div class="xbtn">
    <div class="x01">
      <div class="l"></div>
      <a href="#" id="showText" onclick="showHideSearch()" >隐藏查询条件</a>
      <div class="line"></div>
      <a href="#" onClick="myPostForm('delall','','是否删除选择的记录\n如果删除则不能恢复数据');">删除</a>
      <div class="line"></div>
      <a href="#" onClick="myPostForm('add');">新增</a>
      <div class="line"></div>
      <a href="#" onClick="myPostForm('sel');">查询</a>
      <div class="r"></div>
    </div>
  </div>
  <!--查询条件-->
  <div id="searchM" style="display:block;">
    <table width="98%" border="0">
      <tr>
	   <td align="right">用户名：</td>
	   <td><input type="text" name="where_array[username]" size="20" value=""></td>
	   <td align="right">用户类型：</td>
	   <td>
	   <select name="where_array[utype]">
		<option value="">--全部--</option>
		<option value="0">普通用户</option>
		<option value="1">管理员</option>
	   </select>
	   </td>
	  </tr>
    </table>
  </div>
  <!--表格-->
  <div id="div_showlist" class="xtable">
    <?php include(TPL_DIR.'user/list.php'); ?>
  </div>
  </form>
<?php include(TPL_DIR.'common/common_js.php');?>
<?php include(TPL_DIR.'common/footer.php'); ?>