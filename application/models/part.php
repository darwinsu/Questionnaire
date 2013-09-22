<?php
class partModel extends baseModel
{
	var $db;
	var $db_table;
	var $db_auth_table;
	var $db_up_table;
	var $rights_array;
	public function __construct()
	{
		include_once(APP_PATH.'/application/helper/rights.php');
		$this->rights_array=$rights;
		$this->db_table='t_part';
		$this->db_auth_table='t_auth';
		$this->db_up_table='t_user_part';
		parent::__construct();
	}
	
	/**
	 * 权限认证
	 * @返回数组
	 */
	public function PartListValidate()
	{
		include_once(APP_PATH . '/application/models/role/service/roleService.class.php');
		$this->roleService = new roleService();
		$part_array=explode(",",cookie::get('userlist'));
		
		$Auth_aray=array();
		if($part_array){
			foreach($part_array as $k=>$v){
				$res_array=$this->roleService->getRoleByAuth($v);
				if(is_array($res_array))$Auth_aray = $Auth_aray+$res_array;
			}
			return $Auth_aray;
		}
		return false;
	}
	/**
	 * 权限认证
	 * @Mcode 模块代码
	 */
	public function Partvalidate($Mcode)
	{
		$data=array();
		$Auth_aray=$this->PartListValidate();
		
		if($Auth_aray){
			if(in_array($Mcode,$Auth_aray)){
				$data['state']=true;
			}else{
				$arr=explode("#",$Mcode);
				$data['state']=false;
				$data['name']=$this->rights_array[$arr[0]]['action'][$arr[1]]['name'];
				$data['rights']=$this->rights_array[$arr[0]]['action'][$arr[1]]['rights'][$arr[2]];
				$data['msg']=$data['name'].'-'.$data['rights'];
			}
		}
		return $data;
	}
	/**
	 * 根据条件获取角色信息
	 */
	public function getPartById($whereStr)
	{
		$rs = $this->getDB(
			0,
			0,
			$fields='*',
			$tableViewName=$this->db_table,
			$conditions=$whereStr,
			$orders='id',
			$distinct='',
			$returnType = 1,
			$groupBy = ''
		);
		if($rs)
		{
			return $rs[0];
		}
		else 
		{
			return null;
		}
	}
	/**
	 *用户信息列表
	 *
	 *
	 *  'offset'=>$offset,'limit'=>$limit
	 */
	public function get_data(array $param){
	$rs = $this->getDB(
		$start=($param['start'])?$param['start']:0,
		$limi=($param['limit'])?$param['limit']:0,
		$fields=($param['fields'])?$param['fields']:'*',
		$tableViewName=$param['table'],
		$conditions=($param['conditions'])?$param['conditions']:'',
		$orders=($param['orders'])?($param['orders']):'id',
		$distinct='',
		$returnType = 1,
		$groupBy = ''
		);
		return $rs;
	}
	//插入用户数据
	public function addPart(array $param)
	{
		return $this->replaceIntoDB($this->db_table,$param);
		
	}
	//插入权限数据
	public function updataRight(array $param)
	{
		return $this->replaceIntoDB($this->db_auth_table,$param);
		
	}
	//插入权限数据
	public function updataUP(array $param)
	{
		return $this->replaceIntoDB($this->db_up_table,$param);
		
	}
	//修改用户数据
	public function updatePart(array $param, $whereStr)
	{
		return $this->updateDB($param,$this->db_table,$whereStr);
	}
	
	public function getCount(array $param)
	{
		$conditions="1 and unitid='".cookie::get('unitid')."'";
		if($param['pname']!='')
		{
			$conditions.=" and pname like '%".$param['pname']."%'";
		}
		
		$rs=$this->getDBCount($this->db_table,$conditions);
		return $rs;
	}
	
	public function getList(array $param)
	{	
		$conditions="1 and unitid='".cookie::get('unitid')."'";
		if($param['pname']!='')
		{
			$conditions.=" and pname like '%".$param['pname']."%'";
		}
		
		 
		$rs = $this->getDB(
		$param['start'],
		$param['limit'],
		$fields='*',
		$tableViewName=$this->db_table,
		$conditions,
		$orders=$param['order'],
		$distinct='',
		$returnType = 1,
		$groupBy = ''
		);
		
		return $rs;
	}

		public function del($conditions,$t)
	{
		$rs=$this->delDB(
		$t,
		$conditions
		);
		return $rs;
	}

}
?>