<?php
class programModel extends baseModel
{
	var $db;

	public function __construct()
	{
		parent::__construct();
	}
	//===============================
	public function getCount(array $param)
	{
		
		$conditions="1=1";
		$rs=$this->getDBCount('t_program',$conditions);
		return $rs;
	}

	public function getList(array $param)
		{
			$conditions="1=1";
			$rs = $this->getDB(
			$param['start'],
			$param['limit'],
			$fields='*',
			$tableViewName='t_program',
			$conditions,
			$orders=$param['order'],
			$distinct='',
			$returnType = 1,
			$groupBy = ''
			);
			
			return $rs;
		}
		//====================================
	public function get_softwareCount(array $param)
	{
		
		$conditions="1=1";
		$rs=$this->getDBCount('t_software',$conditions);
		return $rs;
	}

	public function get_softwareList(array $param)
		{
			$conditions="1=1";
			$rs = $this->getDB(
			$param['start'],
			$param['limit'],
			$fields='*',
			$tableViewName='t_software',
			$conditions,
			$orders=$param['order'],
			$distinct='',
			$returnType = 1,
			$groupBy = ''
			);
			
			return $rs;
		}
		//获取应用软件
	public function get_client_typeCount(array $param)
	{
		
		$conditions="1=1";
		$rs=$this->getDBCount('t_client_type',$conditions);
		return $rs;
	}
	public function get_client_typeList(array $param)
	{
		$conditions="1=1";
		$rs = $this->getDB(
		$param['start'],
		$param['limit'],
		$fields='*',
		$tableViewName='t_client_type',
		$conditions,
		$orders=$param['order'],
		$distinct='',
		$returnType = 1,
		$groupBy = ''
		);
			
			return $rs;
		}
	//=============================
	public function get_provinceCount(array $param)
	{
		
		$conditions="1=1";
		$rs=$this->getDBCount('t_province',$conditions);
		return $rs;
	}
	public function get_provinceList(array $param)
	{
		$conditions="1=1";
		$rs = $this->getDB(
		$param['start'],
		$param['limit'],
		$fields='*',
		$tableViewName='t_province',
		$conditions,
		$orders=$param['order'],
		$distinct='',
		$returnType = 1,
		$groupBy = ''
		);
			
			return $rs;
		}
		//=====================
	//=============================
	public function get_fengblCount(array $param)
	{
		
		$conditions="1=1";
		$rs=$this->getDBCount('t_fengbl',$conditions);
		return $rs;
	}
	public function get_fengblList(array $param)
	{
		$conditions="1=1";
		$rs = $this->getDB(
		$param['start'],
		$param['limit'],
		$fields='*',
		$tableViewName='t_fengbl',
		$conditions,
		$orders=$param['order'],
		$distinct='',
		$returnType = 1,
		$groupBy = ''
		);
			
			return $rs;
		}
	//////////////////////////////////
	//=============================
//=============================
	public function get_filterCount(array $param)
	{
		
		$conditions="1=1";
		$rs=$this->getDBCount('t_filter',$conditions);
		return $rs;
	}
	public function get_filterList(array $param)
	{
		$conditions="1=1";
		$rs = $this->getDB(
		$param['start'],
		$param['limit'],
		$fields='*',
		$tableViewName='t_filter',
		$conditions,
		$orders=$param['order'],
		$distinct='',
		$returnType = 1,
		$groupBy = ''
		);
			
			return $rs;
		}
}
?>