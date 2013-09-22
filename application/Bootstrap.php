<?php

class Bootstrap extends Yaf_Bootstrap_Abstract
{
	/**
	 * 把配置存到注册表
	 */
	function _initConfig(Yaf_Dispatcher $dispatcher)
	{
		$dispatcher->disableView();
		$config = Yaf_Application::app()->getConfig();
		Yaf_Registry::set("config",  $config);
	}

	/**
	 * 注册一个插件
	 */
	public function _initPlugin(Yaf_Dispatcher $dispatcher)
	{
		$Cntxt = new ContextPlugin();
		
		$dispatcher->registerPlugin($Cntxt);
	}
}

/*
 | 生成 GUID
 +-------------------------
 */
function getGUID()
{
	if(function_exists('com_create_guid') === true)
	{
		$rs = trim(com_create_guid(), '{}');
	}
	else
	{
		$rs = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(16384, 20479),
			mt_rand(32768, 49151),
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(0, 65535)
		);
	}
	
	return $rs;
}