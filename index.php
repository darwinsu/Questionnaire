<?php
if (phpversion() >= "5.3") {
	define("APPLICATION_PATH", __DIR__);
	define("APP_PATH",  __DIR__);
} else {
	define("APPLICATION_PATH", dirname(__FILE__));
	define("APP_PATH",  dirname(__FILE__));
}
define("APP_PATH",  dirname(__FILE__));
define('SYS_VERSION', '1.0.0.2013051708');
header("Content-type:text/html;charset=utf-8");
//error_reporting(0);
$app = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
$app->bootstrap()->run();
