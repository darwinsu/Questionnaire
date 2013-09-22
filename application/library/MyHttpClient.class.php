<?php
include_once(dirname(__FILE__)."/driver/httpclient.php");

class MyHttpClient extends HttpClient 
{
	function quickGet($url)
	{
		return  parent::quickGet($url);
	}
	
	function quickPost($url, $data)
	{
		return parent::quickPost($url, $data);
	}
}
?>