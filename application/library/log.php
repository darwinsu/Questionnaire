<?php

class Log
{
	protected static $ERROR_LOG_FILE;
	protected static $DEBUG_LOG_FILE;

	private static $logObj;

	public static function instance()
	{
		if($logObj == null)
		{
			$logObj = new Log();
		}
		return $logObj;
	}


	private function __construct()
	{
		$date_str = date("Y-m-d");
		$dirPath = APPLICATION_PATH.'/application/log';
		//判断文件目录
        if (!is_dir($dirPath)) 
		{
			$a = umask(0);
            mkdir($dirPath, 0777);
			umask($a);
        }

		self::$ERROR_LOG_FILE = $dirPath.'/error-'.$date_str.'.log';
		self::$DEBUG_LOG_FILE = $dirPath.'/debug-'.$date_str.'.log';

		if(!file_exists ( self::$ERROR_LOG_FILE ))
		{
			$fp=fopen(self::$ERROR_LOG_FILE, "w+"); //打开文件指针，创建文件
            fclose($fp);  //关闭指针
		}

		if(!file_exists ( self::$DEBUG_LOG_FILE ))
		{
			$fp=fopen(self::$DEBUG_LOG_FILE, "w+"); //打开文件指针，创建文件
            fclose($fp);  //关闭指针
		}
	}

	/*
	 Log trace info...
	*/
	public function trace($msg)
	{
		$date = date('h:i:s');
		$log_msg = "$date ----- [TRACE] $msg\n";
		error_log($log_msg, 3, self::$DEBUG_LOG_FILE);
	}
	/*
	 Log debug info...
	 */
	public function debug($msg)
	{
		$date = date('h:i:s');
		$log_msg = "$date ----- [DEBUG] $msg\n";
		error_log($log_msg, 3, self::$DEBUG_LOG_FILE);
	}
	/*
	 Log error info...
	 */
	public function error($msg)
	{
		$date = date('h:i:s');
		$log_msg = "$date ----- [ERROR] $msg\n";
		error_log($log_msg, 3, self::$ERROR_LOG_FILE);
	}
}

?>
