<?php
class ContextPlugin extends Yaf_Plugin_Abstract
{
	public static $memcache;
	
	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
	{
		global $_G;
		 date_default_timezone_set('PRC');
		define('TIMESTAMP', time());
		defined('PRE_KEY') || define('PRE_KEY','t_');
		define('MAGIC_QUOTES_GPC', function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());
		$conf = Yaf_Application::app()->getConfig();
		define('VIEW_PIC_URL', 'http://'.$_SERVER['HTTP_HOST'].$conf->common->get('webroot').'public/images/');
		define('VIEW_JS_URL', 'http://'.$_SERVER['HTTP_HOST'].$conf->common->get('webroot').'public/js/');
		define('VIEW_CSS_URL', 'http://'.$_SERVER['HTTP_HOST'].$conf->common->get('webroot').'public/css/');
		define('SITE_ROOT','http://'.$_SERVER['HTTP_HOST'].$conf->common->get('webroot'));
		define('TPL_DIR',$_SERVER['DOCUMENT_ROOT'].$conf->common->get('webroot').'application/views/');
		//定义缓存
		if(!isset(self::$memcache)){
			self::$memcache=new Memcache();
			self::$memcache->addServer ("10.1.240.166",11211)or die ("Could not connect memcache");
		}
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$_G = array(
			'timestamp' => TIMESTAMP,
			'starttime' => microtime(true),
			'clientip' => $this->_get_client_ip(),
			'charset' => 'UTF-8',
			
			'PHP_SELF' => '',
			'siteurl' => '',
			'siteroot' => '',
			'siteport' => '',
			'referer' => '',
			
			'v' => array(),		//当前在线用户，为空表示游客
			'o' => array(),		//当前测算的用户
		);

		$_G['PHP_SELF'] = htmlspecialchars($this->_get_script_url());
		$_G['basefilename'] = basename($_G['PHP_SELF']);
		$sitepath = substr($_G['PHP_SELF'], 0, strrpos($_G['PHP_SELF'], '/'));

		$_G['siteurl'] = htmlspecialchars('http://'.$_SERVER['HTTP_HOST'].$sitepath.'/');

		$url = parse_url($_G['siteurl']);
		$_G['siteroot'] = isset($url['path']) ? $url['path'] : '';
		$_G['siteport'] = empty($_SERVER['SERVER_PORT']) || $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
		$_G['referer'] = $this->_dreferer();
		$this->_init_input();
		$this->_init_user();
	}

	function _dreferer()
	{
		global $_G;
		@$_G['referer'] = !empty($_GET['referer']) ? $_GET['referer'] : $_SERVER['HTTP_REFERER'];
		$_G['referer'] = substr($_G['referer'], -1) == '?' ? substr($_G['referer'], 0, -1) : $_G['referer'];

		$_G['referer'] = htmlspecialchars($_G['referer'], ENT_QUOTES);
		$_G['referer'] = str_replace('&amp;', '&', $_G['referer']);
		$reurl = parse_url($_G['referer']);

		if(empty($reurl['host']))
		{
			$_G['referer'] = $_G['siteurl'].$_G['referer'];
		}

		return strip_tags($_G['referer']);
	}

	private function _init_input()
	{
		global $_G;
		if (isset($_GET['GLOBALS']) ||isset($_POST['GLOBALS']) ||  isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS']))
		{
			exit('request_tainting');
		}

		if(MAGIC_QUOTES_GPC)
		{
			$_GET = array_walk_recursive($_GET, 'stripslashes');
			$_POST = array_walk_recursive($_POST, 'stripslashes');
			$_COOKIE = array_walk_recursive($_COOKIE, 'stripslashes');
		}

		$conf = Yaf_Application::app()->getConfig();
		$cookiepre = $conf->cookie->get('pre');

		$prelength = strlen($cookiepre);
		foreach($_COOKIE as $key => $val)
		{
			if(substr($key, 0, $prelength) == $cookiepre)
			{
				$_G['cookie'][substr($key, $prelength)] = $val;
			}
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST))
		{
			$_GET = array_merge($_GET, $_POST);
		}

		if(isset($_GET['page']))
		{
			$_GET['page'] = rawurlencode($_GET['page']);
		}

		foreach($_GET as $k => $v)
		{
			if(is_array($v))
			{
				$_G['gp_'.$k] = @array_walk_recursive($v, 'addslashes');
			}
			else
			{
				$_G['gp_'.$k] = addslashes($v);
			}
		}
	}

	private function _init_user()
	{
		global $_G;

		//如果不是首页的脚本，进行cookie认证
		$cookieIsLogin=cookie::get('isLogin');
		
		if($cookieIsLogin!='1')
		{
			if(strpos($_SERVER['REQUEST_URI'],'Index/index') == false
				&& strpos($_SERVER['REQUEST_URI'],"login")<1 && !strstr($_SERVER['REQUEST_URI'],"Dj")&& !strstr($_SERVER['REQUEST_URI'],"start"))
			{
				echo "<script language='javascript'>top.location.href='".SITE_ROOT."Index/index';</script>";exit;
			}
		}
	}

	private function _init_userlist()
	{
		global $_G;
	}

	private function _get_client_ip()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
		{
			foreach ($matches[0] AS $xip)
			{
				if (!preg_match('/^(10|172\.16|192\.168)\./', $xip))
				{
					$ip = $xip;
					break;
				}
			}
		}
		return $ip;
	}

	private function _get_script_url()
	{
		global $_G;
		if(!isset($_G['PHP_SELF']))
		{
			$scriptName = basename($_SERVER['SCRIPT_FILENAME']);
			if(basename($_SERVER['SCRIPT_NAME']) === $scriptName)
			{
				$_G['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
			}
			else if(basename($_SERVER['PHP_SELF']) === $scriptName)
			{
				$_G['PHP_SELF'] = $_SERVER['PHP_SELF'];
			}
			else if(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName)
			{
				$_G['PHP_SELF'] = $_SERVER['ORIG_SCRIPT_NAME'];
			}
			else if(($pos = strpos($_SERVER['PHP_SELF'],'/'.$scriptName)) !== false)
			{
				$_G['PHP_SELF'] = substr($_SERVER['SCRIPT_NAME'],0,$pos).'/'.$scriptName;
			}
			else if(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT']) === 0)
			{
				$_G['PHP_SELF'] = str_replace('\\','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']));
				$_G['PHP_SELF'][0] != '/' && $_G['PHP_SELF'] = '/'.$_G['PHP_SELF'];
			}
			else
			{
				exit('request_tainting');
			}
		}
		return $_G['PHP_SELF'];
	}
}