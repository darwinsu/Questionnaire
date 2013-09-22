<?php

class RolemanagementController extends Yaf_Controller_Abstract
{
	public function init()
	{
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
	}

	public function indexAction()
	{
		$base_fn = new basefnModel();
		$base_fn->check_power(3);
		$this->display('index');
	}
	
	//获取对应角色模块的数据内容
	public function getDataAction()
	{
		$base_fn = new basefnModel();
		$base_fn->check_power(3);
		//$dept_info=$base_fn->get_depts_data();//获取所有部门的信息
		$dept_info=$base_fn->get_depts_data();//获取所有部门的信
		$members=$base_fn->getHRoles();//获取所有成员数据
		
		if($dept_info)
		{
			$arr_1=array();//管理员
			$arr_2=array();//行政职员
			$arr_3=array();//普通职员
			
			$arr_1['username']=array();
			$arr_1['uid']=array();
			$arr_1['deptid']=array();
			
			$arr_2['username']=array();
			$arr_2['uid']=array();
			$arr_2['deptid']=array();

			$arr_3['username']=array();
			$arr_3['uid']=array();
			$arr_3['deptid']=array();
			
			//dept_id=0
			$arr_4['username']=array();
			$arr_4['uid']=array();
			
			$member_arr=$this->split_m_data($members,&$arr_1,&$arr_2,&$arr_3,&$arr_4);
			$dept_1=array();//部门id
			$dept_2=array();//对应的parent_id
			$dept_3=array();//对应的部门名称
			$dept_data=$this->split_dept_data(&$dept_1,&$dept_2,&$dept_3,$dept_info);
			//$arr_4['uid'] = array_diff($arr_4['uid'], $arr_1['uid'], $arr_2['uid']);
			//$arr_4['username'] = array_diff($arr_4['username'], $arr_1['username'], $arr_2['username']);
			$arr_4['uid'] = array_values($arr_4['uid']);
			$arr_4['username'] = array_values($arr_4['username']);
			echo json_encode(array('result'=>true,'member'=>$member_arr[0],'member_dept'=>$member_arr[1],
				'arr_1'=>$arr_1,'arr_2'=>$arr_2,'arr_3'=>$arr_3,'arr_4'=>$arr_4,'dept_1'=>$dept_1,'dept_2'=>$dept_2,'dept_3'=>$dept_3));
		}
		else
		{
			echo json_encode(array('result'=>false));
		}
	}
	
	/*
	 | 获取指定部门成员
	 +----------------------------
	 */
	function getDeptMemAction()
	{
		$base_fn = new basefnModel();
		$members = $base_fn->getDeptMem($_REQUEST['deptid']);
		$rs = array('rs'=>false);
		if($members)
		{
			$rs = array('rs'=>true, 'data'=>$members);
		}
		
		echo json_encode($rs);
	}
	
	//角色权限管理
	public function setDataAction(){
		if(isset($_POST)){
			$base_fn = new basefnModel();
			$base_fn->check_power(3);
			foreach($_POST as $key=>$val){
				if($val){
					$this->saveConfig($key,$val,$base_fn);
				}
			}
			echo json_encode(array('result'=>true));
		}else{
			echo json_encode(array('result'=>false,'msg'=>'此xxx真损'));
		}
	}
	
	//保存数据
	private function saveConfig($key,$val,$base_fn){
		$base_fn = new basefnModel();
		switch($key){
			case 'm_1'://管理员
				$type=2;
			break;
			case 'm_2'://行政职员
				$type=1;
			break;
			case 'm_3'://普通成员
				$type=0;
			break;
		}
		$arr_1=explode(';',$val);
		foreach($arr_1 as $key=>$v){
			$up_mac_arr['value']=array('account_type'=>$type);
			$up_mac_arr['conditions']='uid='.$v;
			$result=$base_fn->set_fn('5','update',$up_mac_arr);
		}
		//定义缓存
		$memcache=ContextPlugin::$memcache;
		//判断今天是否存在新的打卡
		$memcache_key=PRE_KEY.'unitid_get_HRoles_'.cookie::get('unitid');
		$memcache->delete($memcache_key,0);
	}
	
	//分离部门
	private function split_dept_data($dept_1,$dept_2,$dept_3,$val){
		foreach($val['deptid'] as $k=>$v){
			$dept_1[]=$v;
			$dept_2[]=$val['parentid'][$k];
			$dept_3[]=$val['deptname'][$k];
		}
	}
	//筛选数据
	private function split_m_data($val,$arr_1,$arr_2,$arr_3,$arr_4){
		
		$arr_dept_index=array();//部门数组
		
		$arr=array();
		$arr2=array();
		
		foreach($val['deptid'] as $k=>$v){
			if($_SESSION['uid']==$val['uid'][$k]){
				continue;
			}
			if($v==0||$v=='0'){
				$arr_4['uid'][]=$val['uid'][$k];
				$arr_4['username'][]=$val['username'][$k];
			}else{
				$arr2['uid'][]=$val['uid'][$k];
				$arr2['username'][]=$val['username'][$k];
				$arr2['deptid'][]=$v;
			}
				$arr['uid'][]=$val['uid'][$k];
				$arr['username'][]=$val['username'][$k];
				$arr['deptid'][]=$v;
				
			
			switch($val['account_type'][$k]){
				case '0'://普通职员
					$arr_3['uid'][]=$val['uid'][$k];
					$arr_3['username'][]=$val['username'][$k];
					$arr_3['deptid'][]=$v;
				break;
				case '1'://行政人员
					$arr_2['uid'][]=$val['uid'][$k];
					$arr_2['username'][]=$val['username'][$k];
					$arr_2['deptid'][]=$v;
				break;
				case '2'://管理员
					$arr_1['uid'][]=$val['uid'][$k];
					$arr_1['username'][]=$val['username'][$k];
					$arr_1['deptid'][]=$v;
				break;
			}
		}
		return array($arr,$arr2);
	}
	
}
