<?php

class Controller extends Yaf_Controller_Abstract
{
	protected $responseCode = 200;
	protected $responseData = '';
	protected $responseMsg = '';
	protected $responseMsgData = '';

	protected $paramArray;

    //父类初始化
    public function init()
	{
		$content = file_get_contents('php://input');
        $this->paramArray = json_decode($content, true);
	}

    //设置返回数据
	public function setResponseCode($code)
	{
		$this->responseCode = $code;
	}

	public function setResponseData($data)
	{
		$this->responseData = $data;
	}

	public function setResponseMsg($msg)
	{
		$this->responseMsg = $msg;
	}

	public function setResponseMsgData($responseMsgData)
	{
		$this->responseMsgData = $responseMsgData;
	}

	//输出给客户端
	public function output2Client()
	{
		CommonFunc::sendResponse($this->responseCode, $this->responseData, 'application/json', $this->responseMsg, $this->responseMsgData);
	}
	
	protected function grabParam($key, $is_get)	
	{
		$value = "";
		if ($is_get)
		{
			if(array_key_exists($key, $_GET))
			{
			    $value = $_GET[$key];
			}
		}
		else if(array_key_exists($key, $this->paramArray))
		{
			$value = $this->paramArray[$key];
		}
		return trim($value);
	}
}
?>