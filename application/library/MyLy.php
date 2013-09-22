<?php
include_once(dirname(__FILE__)."/MyHttpClient.class.php");
 
class MyLy
{
	// http交互句柄
	var $mReq;
	
	// UAP地址
	var $mUapAddr = "api.ly.rj.91.com";
	
	//APPID
	var $appid = 104;
	
	var $apikey = '';
	
	// 错误信息
	var $mErrMsg;
	
	// 登录的session
	var $mSess;
	
	function MyLy()
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
	function login($uid, $sid,$imei="", $lngtd="0", $lttd="0")
	{
		if($uid == "")
		{
			$this->mErrMsg = "UID不能为空";
			return -1;
		}
		
		if($sid == "")
		{
			$this->mErrMsg = "SID不能为空";
			return -1;
		}
		$data['uid'] = $uid;
		$data['sid'] = $sid;
		$data['imei'] = $imei;
		$data['lngtd'] = $lngtd;
		$data['lttd'] = $lttd;
		 
		$postData = json_encode($data);
		$bRet = $this->mReq->put("/login", $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['405'] = 'uid或sid为空';
					$t['406'] = '更新登录信息失败';
					$t['407'] = '插入登录信息失败';
					$this->mErrMsg = $t[$this->mReq->status];
					return -1;
				}
				else
				{
					$result = json_decode($reqContent);
					$this->mSess = $result->uap_sid;
					cookie::set('ly_playerid',$result->playerid);
					cookie::set('ly_sid',$result->sid);
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
	 * 获取个人信息
	 *
	 * @param string $sid龙易服务器的SID
	 */
	public function fatehome($sid)
	{
		if($sid == "")
		{
			$this->mErrMsg = "SID不能为空";
			return -1;
		}
		
		$bRet = $this->mReq->get("/fatehome?sid=".$sid, $postData);
		if($bRet)
		{
			$reqContent = $this->mReq->getContent();
			if($reqContent != "")
			{
				if($this->mReq->status != 200)
				{
					$t['404'] = '用户信息丢失';
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
}
?>