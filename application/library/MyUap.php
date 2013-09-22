<?php
include_once(dirname(__FILE__)."/MyHttpClient.class.php");
 
class MyUap extends baseModel
{
	// http交互句柄
	var $mReq;
	
	// UAP地址
	var $mUapAddr = "uap.91.com";
	
	//APPID
	var $appid = 104;
	
	var $apikey = '';
	
	// 错误信息
	var $mErrMsg;
	
	// 登录的session
	var $mSess;
	
	function MyUap()
	{
		$this->mReq = new MyHttpClient($this->mUapAddr);
	}
	
	/**
	 * 验证用户登录
	 *
	 * @param string $account
	 * @param string $password
	 * @param string $blowfish
	 * @param string $flag
	 * @param string $unitid
	 * @param string $uid
	 * @return -1:失败；-2:内容为空；-3:POST返回失败；成功:用户登录信息
	 */
	function login($account, $password,$flag="0", $blowfish="", $expire="", $clientip="",$rsa=0)
	{
		if($account == "")
		{
			$this->mErrMsg = "手机号为空";
			return -1;
		}
		
		if($password == "")
		{
			$this->mErrMsg = "密码为空";
			return -1;
		}
		$data['appid'] = $this->appid;
		$data['username'] = $account;
		$data['password'] = $password;
		$data['blowfish'] = $blowfish;
		$data['expire'] = $expire;
		$data['clientip'] = $clientip;
		$data['rsa'] = $rsa;
		 
		$postData = json_encode($data);
		$bRet = $this->mReq->post("/login?flag=".$flag, $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['400'] = '登录错误次数太多,1小时后再登';
					$t['403'] = '认证失败/密码错误';
					$t['404'] = '用户不存在/用户名为空/密码为空/应用ID不存在';
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					$this->mSess = $result->uap_sid;
					cookie::set('uap_uid',$result->uid);
					cookie::set('uap_sid',$result->sid);
					return $result;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 通过身份验证票验证用户并登录：票据登录通常适用于客户端保存密码的情况，因为登录保存时间通常比较久（当前为 1 个月），所以存储票据而不是密码就相对比较安全了
	 *
	 * @param string $blowfish
	 * @param string $ticket
	 * @param string $flag
	 * @param string $unitid
	 * @return -1:失败；-2:内容为空；-3:POST返回失败；成功:票据信息
	 */
	function loginticket($blowfish, $ticket, $flag="1", $unitid="")
	{
		if($blowfish == "")
		{
			$this->mErrMsg = "机器特征码为空";
			return -1;
		}
		
		if($ticket == "")
		{
			$this->mErrMsg = "身份验证票为空";
			return -1;
		}
		
		$data['blowfish'] = $blowfish;
		$data['ticket'] = $ticket;
		$data['flag'] = $flag;
		$data['unitid'] = $unitid;
		
		$postData = json_encode($data);
		$bRet = $this->mReq->post("/passport/loginticket", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['404'] = '票据不存在';
					$t['403'] = '认证失败或票据过期';
					
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					return $result;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 内置通行证登录，验证用户登录
	 *
	 * @param string $unitid
	 * @param string $unitcode
	 * @param string $account
	 * @param string $password
	 * @param string $blowfish
	 * @return -1:失败；-2:内容为空；-3:POST返回失败；成功:用户登录信息
	 */
	function login1($unitid, $unitcode, $account, $password, $blowfish="")
	{
		if($unitid == "" && $unitcode == "")
		{
			$this->mErrMsg = "单位编号和单位代码都为空";
			return -1;
		}
		
		if($account == "")
		{
			$this->mErrMsg = "用户名为空";
			return -1;
		}
		
		if($password == "")
		{
			$this->mErrMsg = "密码为空";
			return -1;
		}
		$data['account'] = $account;
		$data['password'] = $password;
		$data['blowfish'] = $blowfish;
		$data['unitid'] = $unitid;
		$data['unitcode'] = $unitcode;
		
		$postData = json_encode($data);
		$bRet = $this->mReq->post("/passport/login1", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['404'] = '用户名或密码为空';
					$t['403'] = '密码错误';
					$t['410'] = '输入的 blowfish小于16 位';
					$t['423'] = '用户登录限制';
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					$this->mSess = $result->sid;
					return $result;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 通过身份验证票验证用户并登录：票据登录通常适用于客户端保存密码的情况，因为登录保存时间通常比较久（当前为 1 个月），所以存储票据而不是密码就相对比较安全了
	 *
	 * @param string $blowfish
	 * @param string $ticket
	 * @return -1:失败；-2:内容为空；-3:POST返回失败；成功:票据信息
	 */
	function loginticket1($blowfish, $ticket)
	{
		if($blowfish == "")
		{
			$this->mErrMsg = "机器特征码为空";
			return -1;
		}
		
		if($ticket == "")
		{
			$this->mErrMsg = "身份验证票为空";
			return -1;
		}
		
		$data['blowfish'] = $blowfish;
		$data['ticket'] = $ticket;
		
		$postData = json_encode($data);
		$bRet = $this->mReq->post("/passport/loginticket1", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['404'] = '票据不存在';
					$t['403'] = '认证失败或票据过期';
					
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					return $result;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 修改内置通行证密码，修改成功后相关的登录票据失效
	 *
	 * @param string $curr_password
	 * @param string $new_password
	 * @return -1:失败；-2:内容为空；-3:POST返回失败；成功:返回"修改成功"
	 */
	function password($curr_password, $new_password)
	{
		if($curr_password == "")
		{
			$this->mErrMsg = "当前密码为空";
			return -1;
		}
		
		if($new_password == "")
		{
			$this->mErrMsg = "新密码为空";
			return -1;
		}
		
		$data['curr_password'] = $curr_password;
		$data['new_password'] = $new_password;
		
		$postData = json_encode($data);
		$bRet = $this->mReq->post("/passport/password", $postData);
		if($bRet)
		{
			if($this->mReq->status != 200)
			{
				$t['401'] = '未登录或会话过期';
				$t['403'] = '当前密码错误';
				$t['415'] = '输入参数有误';
				$t['500'] = '修改失败';
				
				$this->mErrMsg = $t[$this->mReq->status];
				return -1;
			}
			else
			{
				$result = json_decode($reqContent);
				return "修改成功";	
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 用户登录 后通过此接口退出
	 *
	 * @param string $sid
	 * @return -1:失败；-2:内容为空；-3:POST返回失败；成功:返回"登出成功"
	 */
	function logout()
	{
		
		$this->mReq->setCookies(array('PHPSESSID'=>$_COOKIE['PHPSESSID']));
		$bRet = $this->mReq->post("/passport/logout", "");
		if($bRet)
		{
			if($this->mReq->status != 200)
			{
				$t['403'] = '当前密码错误';
				$t['404'] = '密码为空';
				$t['423'] = '用户登录限制';
				
				$this->mErrMsg = $t[$this->mReq->status];
				return -1;
			}
			else
			{
				$this->mSess = "";
				$result = json_decode($reqContent);
				return "登出成功";
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 通过用户通行证登录后获取的会话编号在 OAP登录验证
	 *
	 * @param string $uap_sid
	 * @param string $insidepassport
	 * @return -1:失败；-2:内容为空；-3:POST返回失败；成功:返回当前用户信息
	 */
	function check($uap_sid, $insidepassport="")
	{
		if($uap_sid == "")
		{
			$this->mErrMsg = "会话编号为空";
			return -1;
		}
		
		$data['uap_sid'] = $uap_sid;
		$data['insidepassport'] = $insidepassport;
		
		$postData = json_encode($data);
		$bRet = $this->mReq->post("/passport/check", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['401'] = '验证失败';
					$t['402'] = '输入参数有误';
					$t['406'] = '激活会话失败';
					
					$result = json_decode($reqContent);
					$this->mErrMsg = $t[$this->mReq->status]." ".$result->msg;
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					return $result;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 当用户存在多个机构时需要调用此接口进行当前身份的切换，大部分的接口操作都是针对当前身份进行的，切换后用户下次登录会默认此身份（即有记住功能）  
	 *
	 * @param string $unitid
	 * @param string $uid
	 * @return 小于0表示失败，1表示成功
	 */
	function changeuser($unitid, $uid="")
	{
		if($unitid == "")
		{
			$this->mErrMsg = "单位编号为空";
			return -1;
		}
		
		$data['unitid'] = $unitid;
		$data['uid'] = $uid;
		
		$postData = json_encode($data);
		$this->mReq->setCookies(array('PHPSESSID'=>$_COOKIE['PHPSESSID']));
		$bRet = $this->mReq->post("/passport/changeuser", $postData);
			
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
		
			if($this->mReq->status != 200)
			{
				$t['401'] = '未登录或会话过期';
				$t['403'] = '操作失败-没有权限';
				$t['404'] = '切换的单位不存在';
				$t['405'] = '请求参数错误';
				$t['500'] = '服务器错误';
				
				$result = json_decode($reqContent);
				$this->mErrMsg = $t[$this->mReq->status]." ".$result->msg;
				return -1;
			}
			else
			{
				return 1;	
			}
		}
		else 
		{
			return -3;
		}
	}
	
	/**
	 * 查询当前会话所在的当前登录 uap_uid,当前单位编号 unitid,当前身份 uid  
	 *
	 * @param string $sid
	 * @param int $getroot默认=0 返回是否管理员=1
	 * @return 当前用户的uap_uid，unitid，uid
	 */
	function currentuser($sid, $getroot=0)
	{
		if($sid=="")
		{
			$this->mErrMsg = "SID为空";
			return -1;
		}
		
		$this->mReq->setCookies(array('PHPSESSID'=>$this->mSess));
		$bRet = $this->mReq->get("/passport/currentuser?getroot=".$getroot);
		if($bRet)
		{
			$status = $this->mReq->getStatus();
			$reqContent = $this->mReq->getContent();
			if($status == 200)
			{
				return json_decode($reqContent);
			}
			else 
			{
				$t['401'] = '未登录或会话过期';
				$t['403'] = '操作失败-没有权限';
				$t['500'] = '服务器错误';
				
				$result = json_decode($reqContent);
				$this->mErrMsg = $t[$this->mReq->status]." ".$result->msg;
				return -1;
			}
		}
		else 
		{
			echo $this->mReq->errormsg;
			return -3;
		}
	}
	
	/**
	 * 根据身份标识获取具体的身份基本资料（同一个单位的所有人员资料完全公开，对所有好友资料完全公开）
	 *
	 * @param int $uid
	 * @return 用户信息
	 */
	function getuserinfo($uid)
	{
		if($uid == "")
		{
			$this->mErrMsg = "UID为空";
			return -1;
		}
		
		$url=  '/user/info?uid='.$uid;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有操作权限',
				   '404' => '用户/资料不存在'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	}
	
	
	/**
	 * 根据身份批量获取用户资料（同一个单位的所有人员资料完全公开，对所有好友资料完全公开）
	 *
	 * @param array $uid   必填 请求的用户身份编号，最大20条，超过则报405错误
	 * @return 用户列表
	 */
	function getuserlistinfo($uids)
	{
		if(!is_array($uids))
		{
			$this->mErrMsg = "UID为空";
			return -1;
		}
		
		$this->mReq->setCookies(array('PHPSESSID'=>$_COOKIE['PHPSESSID']));
		$bRet = $this->mReq->get("/user/listinfo?uid=".$uids);
		if($bRet)
		{
			$status = $this->mReq->getStatus();
			if($status == 200)
			{
				$reqContent = $this->mReq->getContent();
				return json_decode($reqContent);
			}
			else 
			{
				$t['401'] = '未登录或会话过期';
				$t['403'] = '没有操作权限';
				$t['404'] = '用户/资料不存在';
				
				$this->mErrMsg = $t[$this->mReq->status];
				return -1;
			}
		}
		else 
		{
			$this->mErrMsg = $this->mReq->errormsg;
			return -3;
		}
	}
	
	
	/**
	 * 根据身份获取用户扩展详细资料（同一个单位的所有人员资料完全公开，对所有好友资料完全公开）  
	 *
	 * @param int $uid
	 * @return 用户信息
	 */
	function infoext($uid)
	{
		if($uid == "")
		{
			$this->mErrMsg = "UID为空";
			return -1;
		}
		
		$bRet = $this->mReq->get("/user/infoext?uid=".$uid);
		if($bRet)
		{
			$status = $this->mReq->getStatus();
			if($status == 200)
			{
				$reqContent = $this->mReq->getContent();
				return json_decode($reqContent);
			}
			else 
			{
				$t['401'] = '未登录或会话过期';
				$t['404'] = '用户/资料不存在';
				
				$this->mErrMsg = $t[$this->mReq->status];
				return -1;
			}
		}
		else 
		{
			$this->mErrMsg = $this->mReq->errormsg;
			return -3;
		}
	}
	
 
	function modify_user($data,$uid)
	{
		if(!is_array($data)||empty($uid)) return;
		$data['uid'] = $uid;
		$postData = json_encode($data);
		$this->mReq->setCookies(array('PHPSESSID'=>$this->mSess));
		$bRet = $this->mReq->post("/user/modi", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['401'] = '未登录或会话过期';
					$t['404'] = '请求的用户不存在';
					$t['403'] = '没有权限';
					$result = json_decode($reqContent);
					return $result;
				}
				else
				{
					$result = json_decode($reqContent);
					return 1;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			return -3;
		}
	}
	
	
	//申请绑定
	
	function userbindapply($data){
		$postData = json_encode($data);
		$this->mReq->setCookies(array('PHPSESSID'=>$_COOKIE['PHPSESSID']));
		$bRet = $this->mReq->post("/org/userbindapply", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['401'] = '未登录或会话过期';
					$t['403'] = '没有权限或未登录';
					$t['404'] = '授权码不合法';
					$t['404'] = '邀请码校验失败';
					$t['415'] = '通行证绑定的身份已经达到上限';
				
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					return $result;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			return -3;
		}
	
	}
	
	
	
	//绑定身份
	
	function userbind($authcode91){
	
		$data['authcode'] = $authcode91;
		$postData = json_encode($data);
		$this->mReq->setCookies(array('PHPSESSID'=>$_COOKIE['PHPSESSID']));
		$bRet = $this->mReq->post("/org/userbind", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['401'] = '未登录或会话过期';
					$t['403'] = '没有权限或未登录';
					$t['404'] = '授权码不合法';
					$t['404'] = '邀请码校验失败';
					$t['415'] = '通行证绑定的身份已经达到上限';
				
					$result = json_decode($reqContent);
					return $result;
				}
				else
				{
					$result = json_decode($reqContent);
					return $result;	
				}
			}
			else 
			{
				return -2;
			}
		}
		else 
		{
			return -3;
		}
	
	}
	/*
		获取单位列表，只能取到最后一个绑定的列表
		issub 选填是否返回子单位及下级单位，默认1 1=返回子单位及下级单位 0=不返回子单位下级单位（3位整数）
	*/
	
	function getunitlist($issub=1)
	{	
		$url=  '/user/unitlist?issub='.$issub;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户或单位不存在'
				   );
		
		return $this->apiRequest($url,'get',array(),$t);

	}
	
	/*
		获取部门列表，只能取到最后一个绑定的列表
		issub 选填是否返回子部门及下级部门，默认1 1=返回子部门及下级部门 0=不返回子部门下级部门（3位整数）
	*/
	
	function getdeptslist($unitid,$issub=1)
	{
		$url = '/unit/depts?unitid='.$unitid.'&issub='.$issub;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户或单位不存在',
				   '405' => '请求参数错误'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	
	}
	
	
	/*
		获取请求用户能查看的单位班级列表
		unitid 必填，单位ID（8位整数）
		typeid 可选，单位分类ID，为空或0表示所有班级分类（11位整数）
	*/
	
	function getclasslist($unitid,$typeid=0)
	{
		$url = '/unit/classes?unitid='.$unitid.'&typeid='.$typeid;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户或单位不存在',
				   '405' => '请求参数错误'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	
	}
	
	
	/*
		获取指定部门的完整路径，含请求部门，如果用户没有加入请求部门或无权限，则返回403
		deptid 必填，部门ID（8位整数）
	*/
	function getdeptpath($deptid){
		$url = '/unit/deptpath?deptid='.$deptid;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户或部门不存在'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	}
	
	/*
		获取圈子
	*/
	function getcircles(){
		$url = '/user/circles';
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户或部门不存在'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	
	}
	
	
	
	/*
		根据关键字模糊搜索所有单位，调用此接口无需登录
		keyword 必填，搜索关键字（单位名称，单位代号），模糊匹配（64位字符）,支持拼音简写，全拼搜索
		start 选填，开始记录序号，默认=0
		unitid 选填，单位id （搜索指定单位并返回详细信息）
		size 选填，返回最大记录数，默认=20，最大100，超过报错
	*/
	
	
	function searchunitlist($keyword,$start,$size=20,$unitid=0)
	{
		$url = "/unit/search?keyword=$keyword&start=$start&size=$size&unitid=$unitid";
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户不存在'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	
	}
	
	
	/*
		获取组织详细信息
	*/
	function getunitinfo($unitid)
	{
		$url = "/unit/info?unitid=".$unitid;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户不存在',
				   '405' => '请求错误'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	}
	
	
	/*
		获取用户身份列表
	*/
	function getuserlist($unitid=0)
	{
		$url = "/user/list?unitid=".$unitid;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '404' => '用户不存在',
				   '405' => '请求错误'
				   );
		return $this->apiRequest($url,'get',array(),$t);
	}
	
	/*
		获取用户当前身份
		getadmin 默认=0 返回是否管理员=1
	*/
	function getcurrentuser($getadmin=0)
	{
		
		$url = "/passport/currentuser?getadmin=".$getadmin;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '500' => '服务器错误'
				   );
		return $this->apiRequest($url,'get',array(),$t);
		 
	}
	
	
	/*
		单点会话验证
		uap_sid
		insidepassport:0
	*/
	function passportcheck($data)
	{
		
		$url = "/passport/check";
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '500' => '服务器错误'
				   );
		return $this->apiRequest($url,'post',$data,$t);
		 
	}
	
	/*
		获取用户帐号
		uap_uid 
	*/
	function getuserbyuapuid($uap_uid,$sid)
	{
		
		$url = "/user/".$uap_uid."?sid=".$sid;
		$t = array('401' => '未登录或会话过期',
				   '403' => '没有权限访问',
				   '500' => '服务器错误'
				   );
		$this->mReq->host = "uap.91.com";
		return $this->apiRequest($url,'get',$data,$t);
  
	}
	
	
	/*
		检查手机号是否已绑定
	*/
	function checkphoneisbind($phone)
	{
		
		$url = "/wlphone/isbind?apikey=".$this->apikey;
		$data = array('phone'=>$phone);
		
		$t = array('404' => '未绑定'
				   );
		$this->mReq->host = "uap.91.com";
		return $this->apiRequest($url,'post',$data,$t);
  
	}
	
	
	/*
		获取短信验证码
		uap_uid 
	*/
	function sendcode($phone)
	{
		
		$url = "/ndsoap/sendcode?apikey=".$this->apikey;
		$data = array('phone'=>$phone,'type'=>4);
		
		$t = array('204' => '短信验证码类型为空或不合法/手机号码为空',
					'406' => '发送短信验证码失败（根据手机帐号取回密码）',
					'407' => '发送短信验证码失败（手机注册）',
				   );
		$this->mReq->host = "uap.91.com";
		return $this->apiRequest($url,'post',$data,$t);
  
	}
	
	//直接取短信码
	function getcode($phone)
	{
		
		$url = "/ndsoap/getcode?apikey=".$this->apikey;
		$data = array('phone'=>$phone,'type'=>4);
		
		$t = array( 
				   );
		$this->mReq->host = "uap.91.com";
		return $this->apiRequest($url,'post',$data,$t);
  
	}
	//解绑手机
	function unbindphone($uid,$phone)
	{
		
		$url = "/wlphone/unbindnc?apikey=".$this->apikey;
		$data = array('uin'=>$uid,'phone'=>$phone);
		
		$t = array(
				   );
		$this->mReq->host = "uap.91.com";
		return $this->apiRequest($url,'post',$data,$t);
  
	}
	
	//查询手机绑定情况
	function bindinfo($phone)
	{
		
		$url = "/wlphone/bindinfo?apikey=".$this->apikey;
		$data = array('phone'=>$phone);
		
		$t = array('404' => '失败'
				   );
		$this->mReq->host = "uap.91.com";
		return $this->apiRequest($url,'post',$data,$t);
  
	}
	
	/**手机注册
	 * @param array $userD
	 * { 
	 *		"appid":"来源应用ID (用来标示该用户是通过哪个应用注册来的)，必须",
	 *	    "username":"手机号码，必须",
	 *	    "password":"密码，7-12字符串，必须", 
	 *	    "verifycode":"手机短信验证码，必须",
	 *	    "nickname":"昵称，1-20个字符，必须", 
	 * }
	 
	**/
	
	function mobile_register($userData){
		
		$userData['appid'] = $this->appid;
		
		$postData = json_encode($userData);
		$this->mReq->host = "uap.91.com";
		$bRet = $this->mReq->post("/ndsoap/regphone?apikey=".$this->apikey, $postData);
		$this->mReq->host = $this->mUapAddr;
		//echo $this->mReq->status;
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status == 201||$this->mReq->status == 201)
				{
					
					$result = json_decode($reqContent);
					return $result;	
				}else if($this->mReq->status ==409)
				{
					$result = json_decode($reqContent);
					return $result->msg;	
				}
				else
				{
					$t['204'] = '手机号码为空/昵称为空/密码为空/短信验证码为空/短信验证码不正确';
					$t['206'] = '手机已绑定';
					$t['404'] = '应用ID为空/应用ID不存在';
					$t['406'] = '自动注册91通行证失败';
					$t['407'] = '绑定91通行证失败';
					//$t['409'] = '绑定密保手机失败';
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
			}
			else 
			{
				$this->mErrMsg = "响应内容为空";
				return -2;
			}
		}
		else 
		{
			$this->mErrMsg = $this->mReq->errormsg;
			return -3;
		}
	
	}
	
	
	
	/**
	 * 注册用户
	 *
	 * @param array $userD
	 * { 
	 *	    "username":"用户名,4-70字符串,必须", 
	 *	    "password":"密码，7-12字符串，必须", 
	 *	    "nickname":"昵称，1-20个字符，必须", 
	 *	    "realname":"真实姓名，可选", 
	 *	    "appid":"来源应用 ID，可选 (用来标示该用户是通过哪个应用注册来的)", 
	 *	    "idcard":"身份证号码，15或18位，可选", 
	 *	    "mobile":"移动电话,可选 (限11个字符)", 
	 *	    "telephone":"固话，可选 (限20个字符)", 
	 *	    "email":"邮箱 (限32个字符)", 
	 *	    "sex":"性别 0 保密，1男，2女", 
	 *	    "qq":"QQ号码 (限20个字符)", 
	 *	    "msn":"MSN帐号 (限32个字符)", 
	 *	    "homepage":"个人主页 (限100个字符)", 
	 *	    "sign":"个人签名 (限70个字符)", 
	 *	    "birthyear":"出生年份", 
	 *	    "birthmonth":"出生月份", 
	 *	    "birthday":"出生日", 
	 *	    "blood":"血型，限填（A、B、AB、O、保密）", 
	 *	    "marry":"婚否，0=保密,1=单身,2=恋爱中, 3=订婚, 4=已婚, 5=离异, 6=丧偶, 7=未婚", 
	 *	    "astro":"星座，当有填写出生月日时，该值无效，取值 1-12，按顺序值如下（1=魔羯座，2=水瓶座，3=双鱼座，4=牡羊座，5=金牛座，6=双子座，7=巨蟹座，8=狮子座，9=处女座，10=天秤座，11=天蝎座，12=射手座,0=保密）", 
	 *	    "zodiac":"生肖，当有填写出年年时，该值无效，取值 1-12，按顺序值如下（1=鼠，2=牛，3=虎，4=兔，5=龙，6=蛇，7=马，8=羊，9=猴，10=鸡，11=	狗，12=猪,0=保密）", 
	 *	    "work":"职业 整形//游戏里面职业", 
	 *	    "avatar":"头像 整形", 
	 *	    "school":"学校", 
	 *	    //"profession":"所学专业", 
	 *	    "education":"学历 整形,（1=博士，2=MBA，3=硕士，4=本科，5=大专，6=中专/技院/高职，7=高中及以下, 0=保密）", 
	 *	    //"address":"联系地址(150)", 
	 *	    //"introduction":"个人简介(150)", 
	 *	    "birthcountry":"出生国家，国家省份，中国(100000)",
	 *      "birthprovince":"出生省份，省份代码，如 350000代表福建省（身份证的	前6位）", 
	 *	    "birthcity":"出生城市，城市代码，如 350100代码福州市（身份证的前 6	位）", 
	 *	    "residecountry":"居住国家，国家省份，中国(100000)", 
	 *	    "resideprovince":"目前所在省，省份代码，如 350000代表福建省（身份证的前6位）", 
	 *	    "residecity":"所在城市，城市代码，如 350100代码福州市（身份证的前6位）", 
	 *	    //"job":"工作, 整形(1=学生, 2=执行官/经理,3=教授/老师, 4=技术人员/工程师, 5=服务人员, 6=行政干部, 7=销售/市场, 8=艺术家, 9=自由职业者, 10=演员/歌星, 11=失业, 12=离/退休, 12=普通职员, 14=主妇, 0=其他)", 
	 *	   //"industry":"行业,1=保险,2=采矿/能源,3=餐饮/宾馆,4=动物/宠物,5=电讯,6=房地产,7=服务,8=服装,9=公益,10=广告,11=航空航天,12=化学/化工,13=	健康/保健,14=建筑业, 15=教育/培训,16=计算机软件,17=计算机系统,18=计算机硬件,19=金属冶炼,20=警察/消防,21=军人,22=会计,23=美容/形体,24=媒体/出版,25=木材/造纸,26=零售/批发, 27=农业,28=旅游业,29=司法/律师,30=司机,31=体育运动,32=学术科研,33=演绎娱乐,34=医疗服务,35=艺术/设计,36=银行/金融,37=因特网,38=音乐/舞蹈,39=邮政/快递, 40=运输业,41=政府机关,42=制造/机械,43=咨询服务,0=保密", 
	 *	   //"avatarchange":"头像更新时间戳", 
	 *	   //"income":"个人收入,1=1000元以下,2=1001-2000,3=2001-4000,4=4001-8000,5=8001-20000,6=20000 以上,0=保密", 
	 *	   //"interest":"个人兴趣(150)", 
	 *	   "plat":"0下发邮件(默认),1不下发", 
	 *	    "uniquename":"唯一标识，可选" 
	 *	}
	 * @return 注册成功的用户信息
	 */
	function register($userD)
	{
		if($userD['username'] == "")
		{
			$this->mErrMsg = "用户名不能为空";
			return -1;	
		}
		
		if($userD['password'] == "")
		{
			$this->mErrMsg = "密码不能为空";
			return -1;	
		}
		
		if($userD['nickname'] == "")
		{
			$this->mErrMsg = "昵称不能为空";
			return -1;	
		}
		
		$postData = json_encode($userD);
		$this->mReq->host = "uap.91.com";
		$bRet = $this->mReq->post("/user", $postData);
		$this->mReq->host = $this->mUapAddr;
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 201)
				{
					$t['400'] = '用户名或密码为空';
					$t['409'] = '该用户已存在';
					$t['403'] = '身份证号码不合法/邮箱、MSN格式错误/手机号码格式错误/密码长度不合法/唯一标识已经被注册';
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					return $result;	
				}
			}
			else 
			{
				$this->mErrMsg = "响应内容为空";
				return -2;
			}
		}
		else 
		{
			$this->mErrMsg = $this->mReq->errormsg;
			return -3;
		}
	}
	
	
	
	
	function apiRequest($url,$method,$data,$t)
	{
	
		$cookie = empty($_SESSION['oap91']->sid) ?  $_COOKIE['PHPSESSID'] : $_SESSION['oap91']->sid;
		$this->mReq->setCookies(array('PHPSESSID'=>$cookie));
		if($method=='post'){
			$postData = json_encode($data);
			$bRet = $this->mReq->post($url, $postData);
		}else{
			$bRet = $this->mReq->get($url);
		}
		if($bRet)
		{
			$status = $this->mReq->getStatus();
			if($status == 200)
			{
				$reqContent = $this->mReq->getContent();
				return json_decode($reqContent,true);
			}
			else 
			{
				$reqContent = $this->mReq->getContent();
				$this->mErrMsg = $t[$this->mReq->status];
				return -1;
			}
		}
		else 
		{
			$this->mErrMsg = $this->mReq->errormsg;
			return -3;
		}
	
	}
	
	
	
	 
	
}
?>