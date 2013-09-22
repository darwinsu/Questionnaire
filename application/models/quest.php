<?php
include('Iwj.class.php');
class questModel implements Iwj
{
	var $db;
	var $db_table;
	var $q_db;
	var $wj_Id;
	var $wj_Title;
	var $wj_TopDesc;
	var $wj_FootDesc;
	var $wj_WjSubjects;
	var $wj_assert;
	var $wj_Repeat;
	var $wj_duration;
	var $wj_anonymous;
	var $sub_user;    //制作人
	var $Alldata;
	public function questModel($id='')
	{
		$this->q_db=new baseModel();
		$this->ini($id);
	}
	public function ini($id){
		$this->setWjId($id);
		if($id){	
			$res=$this->wj_data('id='.$id,1);
			$this->setAlldata($res);
			$this->setTitle($res[0]['q_title']);
			$this->setTopDesc($res[0]['q_top_desc']);
			$this->setFootDesc($res[0]['q_foot_desc']);	
			$this->setRepeat($res[0]['q_repeat']);
			$this->setCuser($res[0]['username']);
			$this->wj_duration=$res[0]['duration'];
			$this->wj_anonymous=$res[0]['q_anonymous'];
			$res=$this->wj_data('fk_quest_id='.$id,2);
			$con=count($res);
			for($i=0;$i<$con;$i++){
				$sub_array[$res[$i]['id']]=$res[$i];
			}				
			$this->setWjSubjects($sub_array);
		}
	}
	public function getAlldata()
	{
		return $this->Alldata;
	}
	
	public function setAlldata($res)
	{
		$this->Alldata=$res;
	}
	public function getCuser()
	{
		return $this->sub_user;
	}
	
	public function setCuser($name)
	{
		$this->sub_user=$name;
	}
	public function getWjId()
	{
		return $this->wj_Id;
	}
	
	public function setWjId($id)
	{
		$this->wj_Id=$id;
	}
	
	public function getTitle()
	{
		return $this->wj_Title;
	}
	
	public function setTitle($str)
	{
		$this->wj_Title=$str;
	}
	
	public function getTopDesc()
	{
		return $this->wj_TopDesc;
	}
	
	public function setTopDesc($str)
	{
		$this->wj_TopDesc=$str;
	}
	
	public function getFootDesc()
	{
		return $this->wj_FootDesc;
	}
	
	public function setFootDesc($str)
	{
		$this->wj_FootDesc=$str;
	}
	
	public function getWjSubjects()
	{
		return $this->wj_WjSubjects;
	}
	
	public function setWjSubjects($arr)
	{
		$this->wj_WjSubjects=$arr;
	}
	public function getRepeat()
	{
		return $this->wj_Repeat;
	}
	
	public function setRepeat($boot)
	{
		if($boot)
		$this->wj_Repeat=true;
		else
		$this->wj_Repeat=false;
	}
	public function assert($subjectid,$itemid,$additional=null,$answer)
	{
		$subject=$this->wj_WjSubjects[$subjectid];
		if($subject['s_value']){		
		return	$subject['s_value'];
		}else{
			if(is_array($itemid)){			
			$con=count($itemid);
			$where='';
				for($i=0;$i<$con;$i++){
					$where.=",".$itemid[$i];
				}
			$res=$this->wj_data('id in ('.substr($where,1).')',3);
			$con=count($res);
				$v=0;
				for($j=0;$j<$con;$j++){
					$v+=$res[$j]['s_value'];
				}
			return	 $v;
			}else{
			$res=$this->wj_data('id ='.$itemid,3);
			return $res[0]['s_value'];
			}
		}
	}
	
	public function setValue($v)
	{
		$this->wj_assert=$v;
	}
	
	public function wj_data($conditions,$t)
	{
		switch($t){
			case 1:
				$tab='t_quest';
				$fields='(select username from t_members where t_members.id=t_quest.c_userid) as username,t_quest.*';
				$orders='id';
			break;
			case 2:
				$tab='t_quest_subject';
				$orders='s_order asc';
			break;
			case 3:
				$tab='t_subjects';
				$orders='id';
			break;
		}
		if(cookie::get('unitid')){
			if($conditions)	$conditions.=" and unitid='".cookie::get('unitid')."'";else $conditions="unitid='".cookie::get('unitid')."'";
		}
		//echo $conditions;
		return $this->get_data(array('table'=>$tab,'fields'=>$fields,'conditions'=>$conditions,'orders'=>$orders));
	}
	/**
	 *û问卷列表
	 */
	public function getWjAll()
	{
		$cookieIsLogin=cookie::get('isLogin');
		$conditions="q_start<='".time()."' and q_end>='".time()."'";
		$conditions="1=1";
		if($cookieIsLogin!=1)
		{
			$conditions.=" and q_login='0'";
		}
		$conditions.=" and unitid='".cookie::get('unitid')."'";
		return $this->get_data(array('table'=>'t_quest','orders'=>'id','conditions'=>$conditions));
		//return $this->get_data(array('table'=>'t_quest','orders'=>'id'));
	}
	/**
	 *ûϢб
	 *
	 *
	 *  'offset'=>$offset,'limit'=>$limit
	 */
	public function get_data(array $param){
	$rs = $this->q_db->getDB(
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
	//
	public function add($t_table,array $param)
	{
		$rs = $this->q_db->replaceIntoDB($t_table,$param);
		if($rs)
		{
			$rs = $this->q_db->inId();
		}
		
		return $rs;
	}
	//޸
	public function update($t_table,array $param, $whereStr)
	{
		return $this->q_db->updateDB($param,$t_table,$whereStr);
	}
	
	public function getSytleCount($t_table,$conditions)
	{
		/*$conditions="1=1";
		if($param['name']!='') $conditions.=" and name like '%".$param['name']."%'";
		if($param['q_title'])$conditions.=" and q_title='".$param['q_title']."'";
		if($param['fk_sytle_id'])$conditions.=" and fk_sytle_id='".$param['fk_sytle_id']."'";
		if($param['fk_type_id'])$conditions.=" and fk_type_id='".$param['fk_type_id']."'"; 
		if($param['fk_quest_id'])$conditions.=" and fk_quest_id='".$param['fk_quest_id']."'"; 
		*/
		$rs=$this->q_db->getDBCount($t_table,$conditions);
		return $rs;
	}
	
	public function getList(array $param)
	{	
		$conditions="1=1";
		if($param['name']!='')
		{
			$conditions.=" and name like '%".$param['name']."%'";
		}		 
		$rs = $this->q_db->getDB(
		$param['start'],
		$param['limit'],
		$fields='*',
		$tableViewName=$this->q_db->db_table,
		$conditions,
		$orders=$param['order'],
		$distinct='',
		$returnType = 1,
		$groupBy = ''
		);
		
		return $rs;
	}

		public function del($db_table,$conditions)
	{
		$rs=$this->q_db->delDB(
		$db_table,
		$conditions
		);
		return $rs;
	}
		public function exeSql($sql)
	{
		$rs=$this->q_db->exeDB($sql);
		return $rs;
	}
		public function inID()
	{	
		return $this->q_db->inId();
	}
}
?>