<?php 
/**    
    * ******************************************    
    * 系统公共函数和变量类    
    *     
    * @author     
    * @date 2009-08-12 10:10:48    
    * @copyright www.nd.com    
    * @version 1.0    
    * ******************************************    
*/     
 
class CommonFunc {

	/**
     * 统一输出
     */
	public static function sendResponse($status = 200, $body_data = '', $content_type = 'application/json' , $response_msg = '', $response_data = '')
    {
        ob_start();
		if(is_array($body_data))
            $body = json_encode($body_data);
		else
			$body = $body_data ;
        $status_header = 'HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status);
        header($status_header);
        header('Content-Type: ' . $content_type . ';charset=UTF-8;');

        if($body != '') {
			echo $body;
        } else if($response_msg != '') {
            echo json_encode(array('msg' => $response_msg));
        }
		else {
			echo json_encode($response_data);
		}

        header('Content-Length: ' . ob_get_length());
        ob_end_flush();
        exit();
    }

	 /**
     * 获取状态码对应内容
     * @param int $status HTTP状态码
     * @return string 状态码对应内容
     */
    public static function getStatusCodeMessage($status)
    {
        $codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }

}
 
?>