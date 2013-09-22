<?php
class IndexwapController extends Yaf_Controller_Abstract {
	private $uapMdl;
	private $oapMdl;
	private $bind_users;
	private $func;
	function init() {
		session_start();
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
	}

	function indexAction()
	{	global $_G;
		unset($_COOKIE);
		if(cookie::get('isLogin')=='1' && cookie::get('userid') > 0)
		{
			$this->wapAction();
			$this->getResponse()->setRedirect(SITE_ROOT."wap/");
			exit;
		}
		if($_POST['wjid']){
			$_SESSION['urls']=SITE_ROOT.'Wap/start/?wjid='.$_POST['wjid'];
		}
		$url = SITE_ROOT . urlencode('Indexwap/login/?referer='.$_G['referer']);
		echo '<script language="javascript">location.href="https://reg.uap.91.com/uaplogin/login/auto?appid=401&returnurl='.$url.'";</script>';
		$this->_view->assign('title', '用户登录');
		$this->display('index');
	}
	function userlistWapAction($username,$pass,$sid,$referer = '')
	{	
		$this->_view->assign('username', $username);
		$this->_view->assign('referer', $referer);
		$this->_view->assign('sid', $sid);
		$this->_view->assign('bindusers', $this->bind_users);
		$this->display('userlistWap');
	}
	function loginAction()
	{ 
		global $_G;
		$this->oapMdl=Yaf_Registry::get('oapMdl');
		if(!$this->oapMdl)
		{
			$this->oapMdl=new oapModel();
			Yaf_Registry::set('oapMdl',$this->oapMdl);
		}
		if(!empty($_GET['sid']))
		{
			$this->oapMdl->setsid($_GET['sid']);
			$oapRet=$this->oapMdl->sessioncheck($_GET['sid']);
		}elseif($_COOKIE['uapc']){
			$str_url=base64_decode($_COOKIE['uapc']);
			$str_array=$this->convertUrlQuery($str_url);
			$oapRet=$this->oapMdl->sessioncheck($str_array['sid']);
			$this->oapMdl->setsid($str_array['sid']);
		}else
		{
			$oapRet=$this->oapMdl->login($_POST['username'],$_POST['password'],'','');
		}
		
		$oapRet=(array)$oapRet;
		if(!empty($oapRet['uap_uid'])||$str_array['sid'])
		{
			//登录成功同步用户数据
			if(!empty($_GET['sid']))
			{
				$uapMdl = new uapModel();
				$_G['v']['uap_sid'] = $_GET['sid'];
				$_POST['username'] = $uapMdl->getUserName($oapRet['uap_uid']);
			}
			elseif(!empty($_COOKIE['uapc']))
			{
				$uapMdl = new uapModel();
				$_G['v']['uap_sid'] = $str_array['sid'];
				$_POST['username'] = $uapMdl->getUserName($str_array['uid']);
			}
			
			$userMdl=new userModel();
			
			$usrparem['username']=$_POST['username'];
			$usrparem['uap_uid']=$oapRet['uap_uid'];
			$usrparem['oap_uid']=$oapRet['uid'];
			$usrparem['unitid']=$oapRet['unitid'];
			$usrparem['unitid']=$oapRet['unitid'];
			
			cookie::set('unitid',$oapRet['unitid'],3600 * 12);
			cookie::set('isLogin','1',3600 * 12);
			cookie::set('uap_sid',$oapRet['uap_sid'],3600 * 12);
			cookie::set('uid',$oapRet['uap_uid'],3600 * 12);
			
			$userlist=$this->oapMdl->userlist();
			
			if(empty($userlist->msg) && !empty($userlist->bind_users))
			{
				$this->bind_users = $userlist->bind_users;
			}
			else
			{
				$this->wapAction();
				$this->getResponse()->setRedirect(SITE_ROOT."main/");	//身份未绑定，跳转到绑定身份的页面
            }
			strpos($_G['referer'],"/start/") ? '' : ($_G['referer'] = SITE_ROOT."wap/");
			if(!empty($_GET['sid']))
			{
				$useradmin=$this->oapMdl->currentuser();
				$tmp = $this->oapMdl->userinfo();
				$usrparem['username'] = isset($tmp->username) ? $tmp->username : '未知';
				
				$userMdl->addUser($usrparem,$useradmin->isadmin);
				$userData=$userMdl->getUserById("uid=".$oapRet['uid']);
				$UserPart=$userMdl->getUserPart($userData['id']);
				$UserJSBH=$userMdl->getPartBH($UserPart);
				cookie::set('userlist',$UserPart,3600 * 12);	
				cookie::set('jsbhlist',$UserJSBH,3600 * 12);
				cookie::set('userid',$userData['id'],3600 * 12);	
				cookie::set('isadmin',$useradmin->isadmin,3600 * 12);
				cookie::set('username',$userData['username'],3600 * 12);
				
				$this->wapAction();
				$this->getResponse()->setRedirect($_G['referer']);
			}
			else if(count($this->bind_users) < 2)
			{
				$useradmin=$this->oapMdl->currentuser();
				
				$usrparem['username']=$this->bind_users[0]->username;
				$userMdl->addUser($usrparem,$useradmin->isadmin);
	
				$userData=$userMdl->getUserById(" uid='".$oapRet['uid']."'");
				$UserPart=$userMdl->getUserPart($userData['id']);
				$UserJSBH=$userMdl->getPartBH($UserPart);
				cookie::set('userlist',$UserPart,3600 * 12);	
				cookie::set('jsbhlist',$UserJSBH,3600 * 12);
				cookie::set('userid',$userData['id'],3600 * 12);	
				cookie::set('isadmin',$useradmin->isadmin,3600 * 12);
				cookie::set('username',$userData['username'],3600 * 12);
				$this->wapAction();
				$this->getResponse()->setRedirect($_G['referer']);				
			 }
			 else
			 {
				$this->userlistWapAction($_POST['username'],$_POST['password'],$str_array['sid']);
				
			 }
			 exit;
		}
		
		echo '<script language="javascript">alert("登录失败");top.location.href="https://reg.uap.91.com/uaplogin/login/auto?appid=401&returnurl='.SITE_ROOT.'Index/login/";</script>';
		exit;
	}
	
	//用户选择登录单位
	function choosenAction()
	{
		$this->oapMdl=Yaf_Registry::get('oapMdl');
		if(!$this->oapMdl)
		{
			$this->oapMdl=new oapModel();
			Yaf_Registry::set('oapMdl',$this->oapMdl);
		}
		
		if($_POST['sid']){
			$oapRet=$this->oapMdl->sessioncheck($_POST['sid']);
			$this->oapMdl->setsid($_POST['sid']);
		}
		else
		{
			$oapRet=$this->oapMdl->login($_POST['username'],$_POST['pass'],'','');
		}
		$oapRet=(array)$oapRet;
		
		cookie::set('unitid',$_POST['choosen'],3600 * 12);
		$userlist=$this->oapMdl->userlist();
		//print_r($userlist);exit;
		$bindusers=$userlist->bind_users;
		$ulist=array();
		if($oapRet['uap_uid'] && $_POST['sid'])
		{
			if(is_array($bindusers))
			{
				foreach($bindusers as $k=>$v)
				{
					if($v->unitid==$_POST['choosen'])
					{
						//登录成功同步用户数据
						$userMdl=Yaf_Registry::get('userMdl');
						if(!$userMdl)
						{
							$userMdl=new userModel();
							Yaf_Registry::set('userMdl',$userMdl);
						}
						cookie::set('uid',$v->uid);
						cookie::set('duty',$v->duty);
						cookie::set('workid',$v->workid);
						$this->oapMdl->switchuser($v->unitid,$v->uid);
						
						$usrparem['username']= $v->username;//$_POST['username'];
						$usrparem['unitid']=$v->unitid;
						$usrparem['uap_uid']=$oapRet['uap_uid'];
						$usrparem['oap_uid']=$oapRet['uid'];
						$useradmin=$this->oapMdl->currentuser();
						$usrparem['unitid']=$v->unitid;						
						cookie::set('isLogin','1',3600 * 12);
						cookie::set('isadmin',$useradmin->isadmin,3600 * 12);
						$userMdl->addUser($usrparem,$useradmin->isadmin);
						$userData=$userMdl->getUserById("uid=".$oapRet['uid']);
						$UserPart=$userMdl->getUserPart($userData['id']);
						$UserJSBH=$userMdl->getPartBH($UserPart);
						
						cookie::set('username',$userData['username'],3600 * 12);
						
						cookie::set('userlist',$UserPart,3600 * 12);	
						cookie::set('jsbhlist',$UserJSBH,3600 * 12);	
						cookie::set('userid',$userData['id'],3600 * 12);
					}
				}
			}
		}
		
		if($_POST['choosen'])
		{
			$this->wapAction();
			$this->getResponse()->setRedirect($_POST['referer']);
		}
		else
		{
			$this->userlistWapAction($_POST['username'],$_POST['pass'],$_POST['sid']);
		}
		
	}
	function logoutAction()
	{
		unset($_SESSION['login_info']);
		unset($_COOKIE);
		setcookie("oms_sid", '');
        $life=time()-31536000;
		setcookie("uapc", '',$life,'/','.91.com',0,false);
		cookie::delete("oms_sid");
		cookie::delete('username');
		cookie::delete('utype');
		cookie::delete('isLogin');
		cookie::delete('userlist');
		cookie::delete('unitid');
		cookie::delete('userid');
		cookie::delete('uap_sid');
		cookie::delete('uid');
		cookie::delete('isadmin');
		cookie::delete('jsbhlist');
		cookie::delete('PHPSESSID'); 
		echo '<script language="javascript">top.location.href="https://reg.uap.91.com/uaplogin/login/auto?appid=401&returnurl='.SITE_ROOT.'Index/login/";</script>';
        exit;
	}
	

	function wapAction()
	{	
		if(func::checkmobile())
		{
			$Loaction = SITE_ROOT.'Wap/';
		
			if (!empty($Loaction))
			{
				header("Location: $Loaction");
		
				exit;
			}
		
		}
	}
		function convertUrlQuery($query)
	{ 
		$queryParts = explode('&', $query); 
	 
		$params = array(); 
		foreach ($queryParts as $param) 
		{ 
			$item = explode('=', $param); 
			$params[$item[0]] = $item[1]; 
		} 
	 
		return $params; 
	}
}