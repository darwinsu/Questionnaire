<?php
define('UAP_DEADED', '抱歉，UAP未知错误！');

class uapModel
{
	/*
	 | uap 通讯入口
	 +------------------------
	 | $para input 接口名称
	 */
	function io($method = 'login', $input)
	{
		$uapConf = Yaf_Application::app()->getConfig()->uap;
		$baseUrl = $uapConf->get('baseUrl');
		
		$sid = $this->getSid();
		$chkQuery = $this->chkQuery($method);
		
		if($sid)
		{
            if($chkQuery)
			{
                $method .= '&sid='.$sid;
            }
			else
			{
                $method .= '?sid='.$sid;
            }
        }
		
		if (strpos($method, 'login') === FALSE)
		{
			$appKey = $uapConf->get('appkey');
			$chkQuery = $this->chkQuery($method);
			$method .= ($chkQuery) ? "&apikey=".$appKey :"?apikey=".$appKey;
		}
		
		$url = $baseUrl . $method;
		
		$rs = io::instance()->action($url, $input);
		
		return $rs;
	}
	
	/*
	 | 获取在线的用户SID
	 +---------------------
	 */
	function getSid()
	{
		global $_G;
		
		static $sid = FALSE;
		if($sid == FALSE)
		{
			if(!empty($_G['v']) && isset($_G['v']['uap_sid']))
			{
				$sid = $_G['v']['uap_sid'];
			}
		}
		
		return $sid;
	}
	
	function chkQuery($method)
	{
		$rs = false;
		if(strstr($method, '?'))
		{
			$rs = true;
		}
		return $rs;
	}
	
	/*
	 | UAP 用户登录
	 +---------------------------
	 | @para username
	 | @para password
	 */
	function login(array $input)
	{
		$para = array(
			'appid'=>Yaf_Application::app()->getConfig()->uap->get('appid'),
			'username'=>$input['username'],//uapsmuser
			'password'=>$input['password'],//uapsmuser
			'blowfish'=>'flasjdlfasdlfldsfoweruoweurowero'
		);
		
		$rs = $this->io('login?flag=1', $para);
		
		return $rs;
	}
	
	/*
	 | UAP 通行证Cookie登录
	 +---------------------------------------------------------------------
	 | @para cookie	91通行证登录后获取到的 Cookie 值为 NDUserCenterLogin 的内容
	 | @para appid	应用ID，可选，用于登录统计及暂停应用服务
	 */
	function loginByCookie(array $input)
	{
		$para = array(
			'appid'=>Yaf_Application::app()->getConfig()->uap->get('appid'),
			'cookie'=>$input['cookie']
		);
		
		$rs = $this->io('login/cookie', $para);
		
		return $rs;
	}
	
	/**
	 * 验证session是否有效
	 *
	 * @return 验证结果
	 */
	function checksession()
	{
		$rs = $this->io('checksession', '');
		if($rs['httpCode']=='200')
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * 延长session会话时间
	 *
	 */
	function checksessionActive()
	{
		global $_G;
		$rs = $this->io('checksession/active', array('uid'=>$_G['cookie']['uap_uid']));
		if($rs['httpCode']=='200')
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	/*
	 | 获取游客的登录信息
	 +-------------------------
	 */
	function getGuestSid()
	{
		$para = array(
			'appid'=>Yaf_Application::app()->getConfig()->uap->get('appid'),
			'username'=>'uapsmuser',//uapsmuser
			'password'=>'uapsmuser',//uapsmuser
			'blowfish'=>'flasjdlfasdlfldsfoweruoweurowero'
		);
		
		$rs = $this->io('login', $para);
		return $rs;
	}
	
	/*
	 | UAP 查询手机号是否被绑定
	 +--------------------------
	 | @para phone	手机号码
	 */
	function mblIsbind()
	{
		$method = 'wlphone/isbind';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
			case 401:	//没有请求的权限（需要使用apikey）
			case 404:	//手机未绑定91帐号/获取信息失败
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | 下发短信验证码
	 +-------------------------—
	 | @para uin	91账号ID
	 | @para phone	手机号码
	 | 
	 | @para flag	选填，默认0
	 | 0：下发绑定的短信验证码，1：下发解绑的短信验证码 
	 */
	function mblSendcode(array $input = array(), $flag = 0)
	{
		$method = 'wlphone/sendcode?flag='.$flag;
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
			case 401:	//没有请求的权限（需要使用apikey）
			case 404:	//手机未绑定91帐号/获取信息失败
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	
	/*
	 | 绑定手机号码
	 +-------------------------------
	 | @para uin			91账号ID
	 | @para phone			手机号
	 | @para verifycode		手机验证码
	 */
	function mblBind(array $input = array())
	{
		$method = 'wlphone/bind';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
			case 401:	//没有请求的权限（需要使用apikey）
			case 404:	//手机未绑定91帐号/获取信息失败
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | 解绑手机号码
	 +--------------------------------
	 | @para uin			91账号ID
	 | @para phone			手机号
	 | @para verifycode		手机验证码
	 */
	function mblUnbind(array $input = array())
	{
		$method = 'wlphone/unbind';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
			case 401:	//没有请求的权限（需要使用apikey）
			case 404:	//手机未绑定91帐号/获取信息失败
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | 绑定手机号码（无需验证码）
	 +--------------------------
	 | @para uin	91账号ID
	 | @para phone	手机号
	 */
	function mblBindnc(array $input = array())
	{
		$method = 'wlphone/bindnc';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
			case 401:	//没有请求的权限（需要使用apikey）
			case 404:	//手机未绑定91帐号/获取信息失败
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | 解绑手机号码（无需验证码）
	 +--------------------------
	 | @para uin	91账号ID
	 | @para phone	手机号
	 */
	function mblUnbindnc(array $input = array())
	{
		$method = 'wlphone/unbindnc';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
			case 401:	//没有请求的权限（需要使用apikey）
			case 404:	//手机未绑定91帐号/获取信息失败
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | UAP 查询手机号码绑定的91帐号
	 +------------------------------
	 | @para phone	手机号
	 +------------------------------
	 | @return uin		91账号ID
	 | @return uname	91账号
	 */
	function mblGetUserByPhone(array $input = array())
	{
		$method = 'wlphone/bindinfo';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
				break;
			case 404:	//手机未绑定91帐号/获取信息失败
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | UAP 通过uap uid 查询绑定的手机号
	 +------------------------------
	 | @para uin	91账号ID
	 */
	function getUserName($uid)
	{
		$method = 'user/'.$uid;
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
				$username = $rs['data']['username'];
				break;
			case 404:	//获取失败，未绑定
				$username = '不存在';//$rs['data']['msg'];
				break;
			default :
				$username = '未知';//isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $username;
	}
	
	/*
	 | UAP 通过uap uid 查询绑定的手机号
	 +------------------------------
	 | @para uin	91账号ID
	 */
	function mblGetPhoneByUid(array $input = array())
	{
		$method = 'wlphone/phoneinfo';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 200:	//获取成功
				break;
			case 404:	//获取失败，未绑定
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | UAP 通过手机号注册
	 +------------------------------
	 | @para appid		来源应用ID，必
	 | @para phone		手机号码，必
	 | @para password	密码，7-12字符串，必
	 | @para nickname	昵称，1-20个字符，必
	 | @para verifycode	手机短信验证码，必
	 */
	function mblRegphone(array $input = array())
	{
		$method = 'ndsoap/regphone';
		
		$rs = $this->io($method, $input);
		
		switch($rs['httpCode'])
		{
			case 201:	//注册成功
				break;
			case 206:	//手机已绑定
			case 404:	//应用ID为空/应用ID不存在
			case 406:	//自动注册91通行证失败 
			case 407:	//绑定91通行证失败
			case 409:	//注册成功, 但是绑定密保手机失败
				$rs['data'] = $rs['data']['msg'];
				break;
			case 204:
				$rs['data'] = '注册信息不完整或不合法！';//手机号码为空/昵称为空/密码为空/短信验证码为空/短信验证码不正确 
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
				break;
		}
		
		return $rs;
	}
	
	/*
	 | UAP 通过手机号码注册，发送手机短信验证码
	 +-----------------------------------
	 | @para phone	手机号码，必
	 | @para type	验证码类型，4：发送手机及注册验证码，必
	 */
	function mblRegSendcode(array $input = array())
	{
		$method = 'ndsoap/sendcode';
		$rs = $this->io($method, $input);
		switch($rs['httpCode'])
		{
			case 200:
				break;
			case 204:
				$rs['data'] = '手机号不合法！';	//短信验证码类型为空或不合法/手机号码为空
				break;
			case 206:
			case 406:
			case 407:
				$rs['data'] = $rs['data']['msg'];
				break;
			default :
				$rs['data'] = isset($rs['data']['msg']) ? $rs['data']['msg'] : UAP_DEADED;
		}
		return $rs;
	}
	
	/**
	 * 获取消息列表
	 *
	 * @param array $input=array(
	 *                         'uid'=>'用户ID',
	 *                         'start'=>'可选，起始记录，默认为0',
	 *                         'pos'=>'可选，偏移量,即所取记录数，默认为100，最大为100',
	 *                         'folder'=>'可选，短消息类型, inbox=收件箱, outbox=已发件箱, autobox=系统短消息 默认为inbox',
	 *                         'type'=>'可选，folder=inbox和type=global时，为包括公共短消息；folder=inbox和type=auto时，为包括系统短消息',
	 *                         'stranger'=>'选填，是否显示陌生人发的消息. folder=inbox时该选项有效. 默认=0，不显示；=1显示，需要APIKEY'
	 * )
	 */
	public function getPmList(array $input)
	{
		$method='pm/'.$input['uid'];
		
		$param=array();
		if($input['start']!='')
		{
			$param[]=$input['start'];
		}
		
		if($input['pos']!='')
		{
			$param[]=$input['pos'];
		}
		
		if($input['folder']!='')
		{
			$param[]=$input['folder'];
		}
		
		if($input['type']!='')
		{
			$param[]=$input['type'];
		}
		
		if($input['stranger']!='')
		{
			$param[]=$input['stranger'];
		}
		
		if(is_array($param) && count($param)>0)
		{
			$method.="?".implode('&',$param);
		}
		$rs = $this->io($method, '');
		return $rs;
	}
}
?>