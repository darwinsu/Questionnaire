<?php
	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */
	// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}

	session_start();
	ini_set("html_errors", "0");

	// Check the upload
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		echo "ERROR:invalid upload";
		exit(0);
	}

//FILES參數
  $b_img = $_FILES["Filedata"]["tmp_name"];
  $imgname = substr(md5(time().$_FILES["Filedata"]["name"]),0,16).$_FILES["Filedata"]["name"];
  $path = '../../res/tmp/'.$imgname;
  
//
  if(@move_uploaded_file($b_img, $path))
  {
  $p='http://'.$_SERVER['HTTP_HOST'].'/public/res/tmp/'.$imgname;
 	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}
	$_SESSION["file_info"][$imgname] = $p;
	echo "FILEID:" . $p;
  }
  else
  echo '上传发生错误，无法复制档案。';
  
  
  
  

?>