<?php

class oapModel {

    var $webservice = 'http://oap.91.com/';//http://192.168.94.21/oap21/
    var $_responsecode;
    var $hd;
    private $_sid = '';
	private $_uid = '';
	private $_cookie = '';
	
    function __construct() {
        //parent::__construct();
		
    }

    //登录
    function login($user='test889', $pwd='1', $blowfish='', $forcelogin=FALSE) {

        if ($forcelogin) {
            //如果是强制登陆，则清除之前的所有session以及cookie
            $this->sessionid = NULL;
            $this->uid = NULL;
            $this->setuid(NULL);
            $this->setsid(NULL);
            setcookie("oms_sid", '');
            unset($_SESSION['login_info']);
        }
//print_r($_SESSION['login_info']);exit;
        if (!empty($_SESSION['login_info']) && is_object($_SESSION['login_info']))
            return $_SESSION['login_info']; //会话缓存还未过期，直接返回

        $user = strtolower($user);

        $req = array('account' => $user, 'password' => $pwd, 'blowfish' => $blowfish);
        $reqdata = json_encode($req);
        $result = $this->request('passport/login', 'POST', $reqdata, '');
        $obj = json_decode($result);
        //
        if (isset($obj->msg)) {
            return $obj;
        }

        //如果包含有ticket，则转到票据登陆
        if (!empty($obj->ticket)) {
            $obj = $this->loginticket($blowfish, $obj->ticket);
            return $obj;
        }

        setcookie('oms_sid', $obj->uap_sid, time() + 3600 * 12, '');  /* expire in 1 hour */
        //setcookie('oap_ticket', $obj->ticket, time() + 3600 * 24 * 31, '/');

        $this->sessionid = $obj->uap_sid;
        $this->uid = $obj->uap_uid;
        $this->u_id = $obj->uid;
        $this->unitid = $obj->unitid;

        $this->setsid($obj->uap_sid);
        $this->setuid($obj->uap_uid);

        $_SESSION['login_info'] = $obj;

        return $obj;
    }

    //票据登陆
    function loginticket($blowfish, $ticket) {
        $data = array("blowfish" => $blowfish, "ticket" => $ticket);
        $reqdata = json_encode($data);
        $result = $this->request('passport/loginticket', 'POST', $reqdata, '');
        $obj = json_decode($result);

        setcookie('oms_sid', $obj->uap_sid, time() + 3600 * 12, '/');  /* expire in 0.5 hour */
        setcookie('oap_ticket', $ticket.'|'.$blowfish, time() + 3600 * 24 * 30, '');

        $this->sessionid = $obj->uap_sid;
        $this->uid = $obj->uap_uid;
        $this->u_id = $obj->uid;
        $this->unitid = $obj->unitid;

        $this->setsid($obj->uap_sid);
        $this->setuid($obj->uap_uid);

        $_SESSION['login_info'] = $obj;

        return $obj;
    }

    //退出登录
    function logout($data='') {
        if (!$this->getsid())
            return FALSE;
        if(!$data)$data = array("sid" => $this->getsid(), "uid" => $this->uid);
		//print_r($data);exit;
        $reqdata = json_encode($data);
        $result = $this->request('logout', 'POST', $reqdata, '');
		//清除之前的所有session以及cookie
            $this->sessionid = NULL;
            $this->uid = NULL;
            $this->setuid(NULL);
            $this->setsid(NULL);
            setcookie("oms_sid", '');
            unset($_SESSION['login_info']);
        $this->setsid(NULL);
        $this->setuid(NULL);
        unset($GLOBALS);
    }

    //注册新用户 未测试
    function newuser($pre='TestCase') {


        $username = strtolower($pre . time() . rand(1, 100));

        $data = array('username' => $username, 'password' => 'testssssss', 'realname' => '测试');
        $reqdata = json_encode($data, JSON_FORCE_OBJECT);

        $result = $this->request('user', 'POST', $reqdata, 'http://192.168.94.19/uaps/');
        $obj = json_decode($result);



        return array('uid' => $obj->uid, 'username' => $username, 'password' => 'testssssss', 'sid' => $obj->sid);
    }

    //当前身份切换
    function switchuser($unitid='', $uid='') {

        $data = array("unitid" => $unitid, "uid" => $uid);
        $reqdata = json_encode($data);

        $result = $this->request('passport/changeuser', 'POST', $reqdata, '');
        $obj = json_decode($result);

        return $obj;
    }

    //会话验证
    /*
     * 请求对象:uap_sid 会话ID
     * 成功返回：{"uap_uid":123,"unitid":123, "uid":123}
     * 失败返回：{"msg":"提示信息"}
     */
    function sessioncheck($sid) {
        $data = array("uap_sid" => $sid);
        $reqdata = json_encode($data);
        $result = $this->request('passport/check', 'POST', $reqdata, '');
        $obj = json_decode($result);
        return $obj;
    }

    //会话验证
    /*
     * 请求对象:uap_sid 会话ID
     * 成功返回：bool true
     * 失败返回：bool false
     */
    function sessionvalidcheck($sid) {
        $data = array("uap_sid" => $sid);
        $reqdata = json_encode($data);
        $result = $this->request('passport/check', 'POST', $reqdata, '');
        $obj = json_decode($result);
        if(!empty($obj->msg))
        {
	    	if($_COOKIE['oap_ticket'] && empty($_COOKIE['sid']))
	    	{
		        $vars = explode('|', $_COOKIE['oap_ticket']);
		        $ticket_info = $this->loginticket($vars[1], $vars[0]);//$blowfish , $ticket
		        
                //只有一个绑定身份
                $unitid = $_COOKIE['lastPmsUintid'] ? $_COOKIE['lastPmsUintid'] :$this->unitid;
                //将用户所在的单位成员列表拉取回来以备使用
                $dept = $this->unitdeptusers($unitid, '', '', 100);
                $dept_users = $dept->users;
                if (($dept->total) > 100) {
                    for ($i = 1; $i < ceil(($dept->total) / 100); $i++) {
                        $dept = $this->unitdeptusers($unitid, '', 100 * $i, 100);
                        $dept_users = array_merge((array) $dept_users, (array) $dept->users);
                    }
                }
                $_SESSION['deptusers'] = $dept_users;
				
                //$checkresult = $this->login->oapbindcheck($bind_users[0], $account);
                //$this->session->set('checkresult', $checkresult);
                unset($dept_users);//unset($checkresult);
                

	    	}
        	return false;
        }
        else if(isset($obj->uid))
        	return true;
        else
        	return false;
    }
    
    /* 查询用户基本资料，单个 */

    function userinfo($id) {
        $uid = !empty($id) ? $uid : $this->u_id;
        $data = array("uid" => $uid);
        $reqdata = json_encode($data);

        $result = $this->request('user/info', 'GET', $reqdata, '');
        $obj = json_decode($result);

        return $obj;
    }

    /* 查询用户基本资料，批量 */

    function userlistinfo($uids) {

        $data = array("uid" => $uids);
        $reqdata = json_encode($data);
        $result = $this->request('user/listinfo', 'GET', $reqdata, '');
        $obj = json_decode($result);
        return $obj;
    }

    //获取当前登录91通行证所在机构列表
    function orglist() {

        $result = $this->request('org/list', 'GET', $reqdata, '');
        $obj = json_decode($result);

        return array('uap_uid' => $obj->uap_uid, 'orgs' => $obj->orgs);
    }

    //获取此91通行证单位绑定的所有身份
    function userlist() {

        $result = $this->request('user/list', 'GET');
        $obj = json_decode($result);

        return $obj;
    }
    //查询当前会话所在的当前登录uap_uid,当前单位编号unitid,当前身份uid
    function currentuser() {
        $data = array("getadmin" => '1');
        $reqdata = json_encode($data);
        $result = $this->request('passport/currentuser?getadmin=1', 'GET', $reqdata);
        $obj = json_decode($result);

        return $obj;
    }

    /* 获取部门用户列表 */

    function unitdeptusers($unitid='', $deptid='', $pos=0, $size=20, $isgender='', $istel='', $ismob='', $isemail='') {
        !empty($this->unitid) ? $unitid = $this->unitid : $unitid;
        $data = array("unitid" => $unitid, "pos" => $pos, "size" => $size, "isgender" => $isgender, "istel" => $istel, "ismob" => $ismob, "isemail" => $isemail);

        $reqdata = json_encode($data);
        $result = $this->request('unit/deptusers', 'GET', $reqdata, '');
        $obj = json_decode($result);
        return $obj;
    }

    /* get ids */

    function setResponseCode($code=200) {
        $this->_responsecode = $code;
    }

    function getResponseCode() {
        return $this->_responsecode;
    }

    function getuid() {
        return $this->_uid;
    }

    function getsid() {
        //return $this->_sid;
        if (!empty($_REQUEST['s']) || !empty($_COOKIE['oms_sid'])) {
            $sessionid = isset ($_REQUEST['s']) ? $_REQUEST['s'] : (isset ($_COOKIE['oms_sid']) ? $_COOKIE['oms_sid'] : '');
            return $sessionid;
            } elseif (!empty ($_SESSION['login_info']->uap_sid)) {
            return $_SESSION['login_info']->uap_sid;
            }elseif(!empty ($_SESSION['oaplogin']->uap_sid)) {
            return $_SESSION['oaplogin']->uap_sid;
        }else{
		return $this->_sid;
		}
    }

    function setuid($uid) {
        $this->_uid = $uid;
    }

    function setsid($sid) {
        $this->_sid = $sid;
    }

    function setblowfish($blowfish) {
        $GLOBALS['tc_blowfish'] = $blowfish;
    }

    function setticket($ticket) {
        $GLOBALS['tc_ticket'] = $ticket;
    }

    function request($service, $method='GET', $data='', $webservice='') {

		
		
        $cookie = $sid = '';

        $url = ($webservice ? $webservice : $this->webservice) . $service;

        if ($method == 'GET' && $data != '') {
            $data = json_decode($data);
            $data = (array) $data;
            $url = $url . '?' . http_build_query($data);
        }

        if ($this->getsid()) {

            $sid = "sid=" . $this->getsid();
            $cookie = "PHPSESSID=" . $this->getsid();
        } else {
            //if (strpos($service, 'login') === FALSE) $sid = "apikey=";
        }
        if (strpos($url, '?'))
            $url = $url . '&' . $sid;
        else
            $url = $url . '?' . $sid;

        //echo "\r\n".$method.':'.$url."\r\n";

        $result = $this->_uc_fopen($url, 0, $data, $method, $cookie);
        //error_log($url.chr(13).print_r($data,true).chr(13).$method.chr(13).$cookie."\n\n\n",3,'D:\Program Files\APMServ5.2.6\www\new_zentaopms\www\test.log');

        preg_match('|HTTP/1\.1\s+(\d{3})|', $result['header'], $m);
        $this->setResponseCode($m[1]);

        //echo "\r\n$method - ".$url."\r\n";

        return $result['data'];
    }

    function _uc_fopen($url, $limit = 0, $post = '', $type='GET', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
        $return = '';
        $hd = '';
        $matches = parse_url($url);
        !isset($matches['host']) && $matches['host'] = '';
        !isset($matches['path']) && $matches['path'] = '';
        !isset($matches['query']) && $matches['query'] = '';
        !isset($matches['port']) && $matches['port'] = '';
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;
        if ($type != 'GET') {
            $out = "$type $path HTTP/1.1\r\n";
            $out .= "Accept: application/json\r\n";
            $out .= "Content-Type: 	application/x-www-form-urlencoded; charset=UTF-8\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: ' . strlen((string) $post) . "\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.1\r\n";
            $out .= "Accept: application/json\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }

        //echo $out."\r\n";

        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return ''; //note $errstr : $errno \r\n
        } else {
            stream_set_blocking($fp, $block);
            stream_set_timeout($fp, $timeout);
            @fwrite($fp, $out);
            $status = stream_get_meta_data($fp);

            if (!$status['timed_out']) {
                while (!feof($fp)) {
                    if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                        break;
                    }
                    $hd .= $header;
                }
                $stop = false;
                while (!feof($fp) && !$stop) {
                    $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                    $return .= $data;
                    if ($limit) {
                        $limit -= strlen($data);
                        $stop = $limit <= 0;
                    }
                }
            }
            @fclose($fp);


            return array('header' => $hd, 'data' => $return);
        }
    }

}

?>
