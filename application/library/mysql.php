<?php

class mysql
{
	var $use_set_names;
	var $hostname;
	var $port = 3306;
	var $username;
	var $password;
	var $database;
	var $charset;
	var $conn_id = NULL;
	
	function __construct()
	{
		$conf = Yaf_Application::app()->getConfig();
		$conf = $conf->database->get('mysql');
		foreach($conf as $k=>$v)
		{
			$this->$k = $v;
		}
		
		$this->conn_id = $this->db_connect();
		
		$this->db_select();
	}
	
	static function &instance()
	{
		static $mdl = NULL;
		if($mdl == NULL)
		{
			$mdl = new mysql();
			
			mysql_query("SET NAMES ".$mdl->charset."", $mdl->conn_id);
		}
		return $mdl;
	}
	
	/*
	 | 创建数据库连接
	 +-----------------------
	 */
	function db_connect()
	{
		if ($this->port != '')
		{
			$this->hostname .= ':'.$this->port;
		}
		
		return mysql_connect($this->hostname, $this->username, $this->password);
	}
	
	/*
	 | 创建数据库持久连接
	 +-----------------------
	 */
	function db_pconnect()
	{
		if ($this->port != '')
		{
			$this->hostname .= ':'.$this->port;
		}

		return @mysql_pconnect($this->hostname, $this->username, $this->password);
	}
	
	/*
	 | 重连MySQL服务器
	 +-----------------------
	 */
	function reconnect()
	{
		if (mysql_ping($this->conn_id) === FALSE)
		{
			$this->conn_id = FALSE;
		}
	}
	
	/*
	 | 选择数据库
	 +-----------------------
	 */
	function db_select()
	{
		return mysql_select_db($this->database, $this->conn_id);
	}
	
	/*
	 | 设置环境字符集
	 +-----------------------
	 */
	function db_set_charset()
	{
		if ( ! isset($this->use_set_names))
		{
			$this->use_set_names = (version_compare(PHP_VERSION, '5.2.3', '>=') && version_compare(mysql_get_server_info(), '5.0.7', '>=')) ? FALSE : TRUE;
		}

		if ($this->use_set_names === TRUE)
		{
			return @mysql_query("SET NAMES '".$this->escape_str($charset)."' COLLATE '".$this->escape_str($collation)."'", $this->conn_id);
		}
		else
		{
			return @mysql_set_charset($charset, $this->conn_id);
		}
	}
	
	function version()
	{
		return "SELECT version() AS ver";
	}
	
	function fetch_array($sql)
	{
		$query = $this->execute($sql);
		
		$rs = array();
		$i = 0;
		while($row = mysql_fetch_array($query, MYSQL_ASSOC))
		{
			$rs[$i++] = $row;
		}
		
		return $rs;
	}
	
	/*
	 | 执行查询语句
	 +----------------------
	 */
	function execute($sql)
	{
		$sql = $this->_prep_query($sql);
		$rs = mysql_query($sql, $this->conn_id);
		return $rs;
	}
	
	function _prep_query($sql)
	{
		return $sql;
	}
	function insertID(){
	return mysql_insert_id();
	}
	/*
	 | 数据过滤
	 +-------------------------------------
	 */
	function escape_str($str, $like = FALSE)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
	   		{
				$str[$key] = $this->escape_str($val, $like);
	   		}

	   		return $str;
	   	}

		if (function_exists('mysql_real_escape_string') AND is_resource($this->conn_id))
		{
			$str = mysql_real_escape_string($str, $this->conn_id);
		}
		elseif (function_exists('mysql_escape_string'))
		{
			$str = mysql_escape_string($str);
		}
		else
		{
			$str = addslashes($str);
		}

		// escape LIKE condition wildcards
		if ($like === TRUE)
		{
			$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
		}

		return $str;
	}
	
	/*
	 | 关闭查询连接
	 +-----------------------
	 */
	function close()
	{
		@mysql_close($this->conn_id);
	}
}
