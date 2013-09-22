<?php

class cookie
{

	/*
	 | 写COOKIE
	 +-------------------------------------------
	 | @para name		COOKIE 键名或包含设置参数的数组
	 | @para value		COOKIE 键值
	 | @para expire		COOKIE 有效期
	 | @para path		COOKIE 路径
	 | @para domain		COOKIE 域名
	 | @para secure		COOKIE 安全
	 | @para httponly	COOKIE是否只HTTP有效(requires PHP 5.2 or higher)
	 | @return  boolean
	 */
	public static function set($name, $value = NULL, $expire = '', $path = NULL, $domain = NULL, $secure = NULL, $httponly = NULL)
	{
		if(headers_sent())
		{
			return FALSE;
		}
		
		is_array($name) and extract($name, EXTR_OVERWRITE);

		// 获取配置信息
		$conf = Yaf_Application::app()->getConfig();
		$conf = $conf->get('cookie');
		$pre = '';
		foreach (array('pre', 'expire', 'domain', 'path', 'secure', 'httponly') as $item)
		{
			if (isset($conf[$item]))
			{
				$$item = $conf[$item];
			}
		}
		
		$expire = ($expire == 0) ? 0 : time() + (int) $expire;

		return setcookie($pre.$name, $value, $expire, $path, $domain, $secure, $httponly);
	}

	/*
	 | 写COOKIE
	 +-------------------------------------------
	 | @para name		COOKIE 键名或包含设置参数的数组
	 | @para default	COOKIE 键值
	 | @para xss_clean	COOKIE 有效期
	 */
	public static function get($name, $default = NULL, $xss_clean = FALSE)
	{
		$conf = Yaf_Application::app()->getConfig();
		$conf = $conf->get('cookie');
		$pre = $conf['pre'];
		if ( !isset($_COOKIE[$pre.$name]))
		{
			return FALSE;
		}
		
		return $_COOKIE[$pre.$name];
	}

	/*
	 | 写COOKIE
	 +-------------------------------------------
	 | @para name		COOKIE 键名或包含设置参数的数组
	 | @para path		COOKIE 路径
	 | @para domain		COOKIE 域名
	 | @return  boolean
	 */
	public static function delete($name, $path = NULL, $domain = NULL)
	{
		$conf = Yaf_Application::app()->getConfig();
		$conf = $conf->get('cookie');
		$pre = $conf['pre'];
		
		if ( !isset($_COOKIE[$pre.$name]))
		{
			return FALSE;
		}
		
		unset($_COOKIE[$pre.$name]);
		
		return cookie::set($pre.$name, '', -86400, $path, $domain, FALSE, FALSE);
	}
}