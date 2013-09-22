<?php
class userModel extends baseModel
{
	var $db;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 根据用户名、密码获取指定用户
	 *
	 * @param whereStr查询用户条件
	 * 
	 * @return array该用户列表，用户不存在返回空
	 */
	public function getUserById($whereStr)
	{
		$rs = $this->getDB(
			0,
			0,
			$fields='*',
			$tableViewName='t_members',
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
	 * 根据用户名、密码用户权限
	 *
	 * @param $id查询用户id
	 * 
	 * @return array该用户列表，用户不存在返回空
	 */
	public function getUserPart($id)
	{
		$rs = $this->getDB(
			0,
			0,
			$fields='default_account_type,account_type',
			$tableViewName='t_members',
			$conditions="id='".$id."'",
			$orders='id',
			$distinct='',
			$returnType = 1,
			$groupBy = ''
		);
		
		if($rs)
		{
			switch($rs[0]['account_type'])
			{
				case 0://普通职员
					$partid = 3;
					break;
				case 1://行政职员
					$partid = 2;
					break;
				case 2://管理员
					$partid = 1;
					break;
			}
			
			return $partid;
		}
		else 
		{
			return null;
		}
	}
	/**
	 * 根据获取等级
	 *
	 * @param $id查询用户id
	 * 
	 * @return array该用户列表，用户不存在返回空
	 */
	public function getPartBH($id)
	{
		$rs = $this->getDB(
			0,
			0,
			$fields='*',
			$tableViewName = 't_part',
			$conditions = "id=".$id,
			$orders='id',
			$distinct='',
			$returnType = 1,
			$groupBy = ''
		);
		
		if($rs)
		{
			$jsbh = $rs[0]['jsbh'];
			return $jsbh;
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
	public function get_data(array $param)
	{
		if($param['table']=='t_members')
			$param['fields']='*,(select id from t_user_part where t_user_part.user_id=t_members.id limit 0,1) as zt';
		
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
	
	function getWeightName()
	{
		$conditions.="unitid=".cookie::get('unitid');
		
		$rs = $this->getDB(
			0,
			1,
			$fields='w1Name,w2Name,w3Name',
			$tableViewName='t_weight',
			$conditions,
			'',
			'',
			1,
			''
		);
		if(!empty($rs))
		{
			$rs = $rs[0];
		}
		
		return $rs;
	}
	
	function setWeightName($w1Name, $w2Name, $w3Name)
	{
		$rs = $this->replaceIntoDB('t_weight',array('unitid'=> cookie::get('unitid'),'w1Name'=>$w1Name, 'w2Name'=>$w2Name,'w3Name'=>$w3Name));
		
		return $rs;
	}
	
	function setWeightVal($uid, $value)
	{
		$conditions='unitid='.cookie::get('unitid').' AND uid='.$uid;
		$rs = $this->updateDB($value,'t_members',$conditions);
		
		return $rs;
	}
	
	//插入用户数据
	public function addUser(array $param, $isadmin='0')
	{
		$firstuse = $this->getDB(0, 1, 'firstuse', 't_unitid', 'unitid='.$param['unitid'], '', false);	
		if(empty($firstuse))
		{
			$this->replaceIntoDB('t_initTMPL',array('unitid'=>$param['unitid']));
			$this->initTMPL($param['unitid'], $add_user_id);
		}
		
		if(!$this->getUserById("uid=".$param['oap_uid']))
		{
			$userinfo = array('uid'=>$param['oap_uid'],'uap_uid'=>$param['uap_uid'],'username'=>$param['username'],'unitid'=>$param['unitid']);
			
			$userinfo['default_account_type'] = $isadmin ? 1 : 0; //0:普通职员 1：管理员
			$userinfo['account_type'] = $isadmin ? 2 : 0; //0:普通职员 1：行政人员 2：管理员
			
			$rs=$this->replaceIntoDB('t_members',$userinfo);
			return $rs;
		}
		else
		{
			return true;
		}
	}
	
	function initTMPL($unitid, $userid)
	{
		$questObj=new  questModel();
		$t='t_quest';
		$TMPL = array(
			'174'=>'考试例子',
			'175'=>'投票例子',
			'177'=>'调查例子'
		);
		
		foreach($TMPL as $k=>$v){
			$quest_sql="insert into t_quest(fk_sytle_id,fk_type_id,q_title,q_top_desc,q_foot_desc,q_start,q_end,duration,status,pass,q_repeat,q_anonymous,q_login,q_all,c_userid,c_time,pass_type,unitid) select fk_sytle_id,fk_type_id,'".$v."',q_top_desc,q_foot_desc,q_start,q_end,duration,status,pass,q_repeat,q_anonymous,q_login,q_all,'".$userid."',c_time,pass_type,".$unitid." from t_quest where id='".$k."'";
			$results=$questObj->exeSql($quest_sql);
			$questId=$questObj->inID();
			if($results&&$questId)
			{
				$conditions="fk_quest_id=".$k;
				$result=$questObj->get_data(array('table'=>'t_quest_subject','conditions'=>$conditions,'orders'=>'id'));
				$coun=count($result);
				for($i=0;$i<$coun;$i++)
				{
					$quest_sub_sql="insert into t_quest_subject(fk_quest_id,s_title,fk_type_id,s_url,s_replenish,s_type,s_order,title_id,q_remark,s_len,s_value,unitid) select '".$questId."',s_title,fk_type_id,s_url,s_replenish,s_type,s_order,title_id,q_remark,s_len,s_value,".$unitid." from t_quest_subject where id='".$result[$i]['id']."'";
					$results_sub=$questObj->exeSql($quest_sub_sql);
					$quest_subId=$questObj->inID();
					if($results_sub)
					{
						$conditions="fk_subject_id=".$result[$i]['id'];
						$result_sub=$questObj->get_data(array('table'=>'t_subjects','conditions'=>$conditions,'orders'=>'id'));
						
						$count_sub=count($result_sub);
						for($j=0;$j<$count_sub;$j++){
							$sub_sql="insert into t_subjects(fk_subject_id,s_answer,s_value,s_order,s_url,s_replenish,unitid) select '".$quest_subId."',s_answer,s_value,s_order,s_url,s_replenish,".$unitid." from t_subjects where id='".$result_sub[$j]['id']."'";
							$res_sub=$questObj->exeSql($sub_sql);
						}
					}
				}
			}
		}
	}
	
	//修改用户数据
	public function updateUser(array $param, $whereStr)
	{
		return $this->updateDB($param,'t_members',$whereStr);
	}
	
	public function getCount(array $param)
	{
		$conditions="1=1";
		if($param['username']!='')
		{
			$conditions.=" and username like '%".$param['username']."%'";
		}
		
		if($param['unitid']!='')
		{
			$conditions.=" and unitid = '".$param['unitid']."'";
		}
		$rs=$this->getDBCount('t_members',$conditions);
		return $rs;
	}
	
	public function getList(array $param)
	{	
		$conditions="1=1";
		if($param['username']!='')
		{
			$conditions.=" and username like '%".$param['username']."%'";
		}
		
		$rs = $this->getDB(
			$param['start'],
			$param['limit'],
			$fields='*',
			$tableViewName='t_members',
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