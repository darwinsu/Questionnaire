<?php
class io
{
	
	/*
	 | IO 实例化方法
	 +-------------------------
	 */
	static function &instance()
	{
		static $obj = NULL;
		if($obj == NULL)
		{
			$obj = new io();
		}
		
		return $obj;
	}
	
	private function __construct(){}
	
	/*
	 | 通讯方法
	 +-----------------------------------------
	 | @para url	请求的资源地址
	 | @para data	请求的参数，不为空POST，否则GET
	 */
	function action($url, $data = '')
	{
		if($data!='')
		{
			$postData=json_encode($data);
		}
		$rs = $this->_send($url, 0, $postData);
		
		return $rs;
	}
	
	function actionput($url, $data = '')
	{
		if($data!='')
		{
			$postData=json_encode($data);
		}
		$rs = $this->_sendput($url, 0, $postData);
		
		return $rs;
	}
	
	private function _sendput($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = TRUE)
	{
		$hd = $return = '';
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if($post)
		{
			$out = "PUT $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			@$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		}
		else
		{
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			@$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		
// 		if(strpos($url, 'fatesearch')){
// 			file_put_contents(dirname(__FILE__).'/a.txt', $out);
// 		}
		
		if(function_exists('fsockopen'))
		{
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		}
		elseif (function_exists('pfsockopen'))
		{
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		}
		else
		{
			$fp = false;
		}
	
		if(!$fp)
		{
			$rs = array('httpCode'=>800, 'data'=>array('msg'=>'当前项目环境，PHP不支持套接字连接！'));
		}
		else
		{
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out'])
			{
				while (!feof($fp))
				{
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))
					{
						break;
					}
					$hd .= $header;
				}
				
				$stop = false;
				while(!feof($fp) && !$stop)
				{
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit)
					{
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			preg_match('|HTTP/1\.1\s+(\d{3})|', $hd, $m);
			
			$rs = array('httpCode'=>$m[1], 'data'=>json_decode($return, true));
		}
		
		return $rs;
	}
	
	private function _send($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = TRUE)
	{
		$hd = $return = '';
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if($post)
		{
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			@$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		}
		else
		{
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			@$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		
		if(function_exists('fsockopen'))
		{
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		}
		elseif (function_exists('pfsockopen'))
		{
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		}
		else
		{
			$fp = false;
		}
	
		if(!$fp)
		{
			$rs = array('httpCode'=>800, 'data'=>array('msg'=>'当前项目环境，PHP不支持套接字连接！'));
		}
		else
		{
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out'])
			{
				while (!feof($fp))
				{
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))
					{
						break;
					}
					$hd .= $header;
				}
				
				$stop = false;
				while(!feof($fp) && !$stop)
				{
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit)
					{
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			preg_match('|HTTP/1\.1\s+(\d{3})|', $hd, $m);
			
			$rs = array('httpCode'=>$m[1], 'data'=>json_decode($return, true));
		}
		
		return $rs;
	}
}

?> 
