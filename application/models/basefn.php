<?php
class basefnModel extends baseModel
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function set_fn ($target,$fn_type,$val,$file='*')
	{
		switch($target)
		{
			case '5'://物理地址,角色管理
				$table=PRE_KEY.'members';
			break;
		}
		
		switch($fn_type)
		{
			case 'update'://更新
				return $this->update_fn($table,$val);
			break;
		}
	}
	
	public function update_fn($table,$val)
	{
		if(is_array($val))
		{
			$value=$val['value']; $conditions='unitid='.cookie::get('unitid').' and '.$val['conditions'];
			$result=$this->updateDB($value,$table,$conditions);
			return $result?array('result'=>true,'msg'=>'更新成功'):array('result'=>false,'msg'=>'更新失败');
		}
		else
		{
			return array('result'=>false,'msg'=>'您的更新数据格式不正确');
		}
	}
	
	//获取成员数据
	public function get_connect_member($table,$conditions,$file){
		$rt=$this-> getDB('', '',$file,$table,'unitid='.cookie::get('unitid').' and '.$conditions.' and statue=1');
		return $rt;
	}

	//获取部门成员
	function getDeptMem($deptid)
	{
		$conditions='unitid='.cookie::get('unitid').' AND deptid='.$deptid;
		$member=$this->getDB('', '', 'uap_uid,username,id,deptid,depts,uid',PRE_KEY.'members', $conditions,'id');
		return $member;
	}
	
	/*
	 | 获取指定单位管理员&行政人员
	 +----------------------
	 */
	function getHRoles()
	{
		//定义缓存
		$memcache=ContextPlugin::$memcache;
		//判断今天是否存在新的打卡
		$memcache_key=PRE_KEY.'unitid_get_HRoles_'.cookie::get('unitid');
		$m_data=$memcache->get($memcache_key);
		if($m_data){
			return $m_data;
		}
		$conditions='unitid='.cookie::get('unitid').' AND ( account_type>0 OR deptid=0)';
		$count=$this->getDBCount(PRE_KEY.'members',$conditions);
		$temp_member=$this->getDB('', '', 'uap_uid,username,id,deptid,account_type,depts,uid',PRE_KEY.'members', $conditions,'id');
		$members=array();
		if($temp_member){
			foreach($temp_member as $key=>$val){
				$members['uap_uid'][]=$val['uap_uid'];
				$members['uid'][]=$val['uid'];
				$members['username'][]=$val['username'];
				$members['depts'][]=$val['depts'];
				$members['deptid'][]=$val['deptid'];
				$members['account_type'][]=$val['account_type'];
			}
			$members['count']=$count;
		}
		//保存缓存
		$memcache->set($memcache_key,$members);
		//var_export($members);
		return $members;
	}
	
	/*
	 | 获取单位全部部门
	 +----------------------------------------
	 */
	public function get_depts_data(){
		$depts=array();
		$depts['deptid']=array();
		$depts['parentid']=array();
		$depts['deptname']=array();
		
		//定义缓存
		$memcache=ContextPlugin::$memcache;
		$memcache_key=PRE_KEY.'unitid_get_depts_'.cookie::get('unitid');
		$memcache->delete($memcache_key,0);
		$d_data=$memcache->get($memcache_key);
		if($d_data){
			return $d_data;
		}

		$temp_depts=$this->get_all_dept_fn();
		if($temp_depts){
			foreach($temp_depts as $key=>$val){
				$depts['parentid'][]=$val['parentid'];
				$depts['deptid'][]=$val['deptid'];
				$depts['deptname'][]=$val['deptname'];
			}
		}
		//保存缓存
		$memcache->set($memcache_key,$depts);
		return $depts;
	}
	
	//获取所有的部门dept
	private function get_all_dept_fn(){
		//定义缓存
		$temp_depts=$this->getDB('', '','deptid,parentid,deptname',PRE_KEY.'depts', ' unitid='.cookie::get('unitid'));
		return $temp_depts;
	}
	
	/*
	 | 获取单位全部部门
	 +----------------------------------------
	 */
	function get_depts_tree(){
		
		//定义缓存
		$memcache=ContextPlugin::$memcache;
		$memcache_key=PRE_KEY.'unitid_get_tree_'.cookie::get('unitid');
	
	    $d_data=$memcache->get($memcache_key);
		if($d_data){
			return $d_data;
		}

		//$memcache->delete($memcache_key,0);
		$temp_depts=$this->get_all_dept_fn();
		
		$depts = $root = $node = array();
		if($temp_depts){
			foreach($temp_depts as $key=>$val){
				$node['parentid']  = $val['parentid'];
				$node['deptid']    = $val['deptid'];
				$node['deptname']  = $val['deptname'];
				
				$root[$node['deptid']] = $node;
			}
		}
		
		//保存缓存
		$memcache->set($memcache_key,$root);
		return $root;
	}
	
	//用户权限判断与限制
	public function check_power($power){
		$isadmin = cookie::get('isadmin');
		if(!$isadmin)
		{
			header("Content-type:text/html; charset=utf-8");
			echo '<script language="javascript">alert("非法操作");window.location.href="'.SITE_ROOT.'";</script>';
			exit;
		}
	}
	
	//判断是否为utf8
	function set_utf8($word)
	{
		if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true
			|| preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true
			|| preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)    
		{
			return $word;    
		}
		else
		{
			return iconv("GB2312", "UTF-8",$word);
		}
	}
}
?>