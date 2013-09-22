<?php

class ParticipantsController extends Yaf_Controller_Abstract
{
	public function init()
	{
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
	}
	
	//获取对应角色模块的数据内容
	public function getDataAction()
	{
		$base_fn = new participantsModel();
		$base_fn->check_power(3);
		
		$dept_info = $base_fn->get_depts_data();//获取所有部门的信息
		$arr_1 = $base_fn->getHRoles();//获取单位根节点成员
		$_REQUEST['qid'] = (int)$_REQUEST['qid'];
		$arr_2 = $base_fn->getParticipants($_REQUEST['qid']);;
		
		if($dept_info)
		{
			$dept_1=array();//部门id
			$dept_2=array();//对应的parent_id
			$dept_3=array();//对应的部门名称
			$dept_data=$this->split_dept_data(&$dept_1,&$dept_2,&$dept_3,$dept_info);
			
			$rs = array(
				'result'=>true,
				'arr_1'=>$arr_1,//单位根节点成员
				'arr_2'=>$arr_2,//当前参与人员
				'dept_1'=>$dept_1,
				'dept_2'=>$dept_2,
				'dept_3'=>$dept_3
			);
			echo json_encode($rs);
		}
		else
		{
			echo json_encode(array('result'=>false));
		}
	}
	
	//分离部门
	private function split_dept_data($dept_1,$dept_2,$dept_3,$val)
	{
		foreach($val['deptid'] as $k=>$v)
		{
			$dept_1[]=$v;
			$dept_2[]=$val['parentid'][$k];
			$dept_3[]=$val['deptname'][$k];
		}
	}
}
