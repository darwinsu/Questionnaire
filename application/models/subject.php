<?php
include('IwjSubject.class.php');
class subjectModel implements IwjSubject
{
	var $db;
	var $db_table;
	var $q_db;
	var $sub_Id;
	var $sub_Order;
	var $sub_Title;
	var $sub_ImageURL;
	var $sub_Check;
	var $sub_Items;
	var $sub_type_id;
	var $sub_len;
	var $sub_value;
	var $sub_zf;    //总分
	var $sub_user;    //制作人
	var $sub_all;
	public function subjectModel($id)
	{
		$this->q_db=new baseModel();
		$this->ini($id);
	}
	
	public function ini($id){
		$this->setSubjectid($id); //获取问卷题目
					if($id){	
					$res=$this->wj_data('id='.$id,2);//问卷数据
					$this->setSubjectAll($res);
					$this->setOrder($res[0]['s_order']);
					$this->setSubjectTitle($res[0]['s_title']);
					$this->setImageURL($res[0]['s_url']);	
					$this->sub_type_id=$res[0]['fk_type_id'];
					$this->sub_len=$res[0]['s_len'];
					$this->sub_value=$res[0]['s_value'];
					if($res[0]['fk_type_id']==3){
						$this->setisCheck(true);
					}else{
						$this->setisCheck(false);
					}
					$this->setSubjectItems($this->wj_data('fk_subject_id='.$id,3));
					$this->setZf();
					
		}
	}
	
	public function getSubjectAll()
	{
		return $this->sub_all;
	}
	
	public function setSubjectAll($res)
	{
		$this->sub_all=$res;
	}
	public function getCuser()
	{
		return $this->sub_user;
	}
	
	public function setCuser($name)
	{
		$this->sub_user=$name;
	}
	public function getSubjectid()
	{
		return $this->sub_Id;
	}
	
	public function setSubjectid($id)
	{
		$this->sub_Id=$id;
	}
	
	public function getOrder()
	{
		return $this->sub_Order;
	}
	
	public function setOrder($str)
	{
		$this->sub_Order=$str;
	}
	
	public function getSubjectTitle()
	{
		return $this->sub_Title;
	}
	
	public function setSubjectTitle($str)
	{
		$this->sub_Title=$str;
	}
	
	public function getImageURL()
	{
		return $this->sub_ImageURL;
	}
	
	public function setImageURL($str)
	{
		$this->sub_ImageURL=$str;
	}
	
	public function isCheck()
	{
		return $this->sub_Check;
	}
	
	public function setisCheck($bo)
	{
		$this->sub_Check=$bo;
	}
	
	public function getSubjectItems()
	{
		 return $this->sub_Items;
	}
	
	public function setSubjectItems($v)
	{
		$this->sub_Items=$v;
	}

	public function setZf()
	{
		$this->sub_zf=0;
		$count=count($this->sub_Items);
		for($i=0;$i<$count;$i++)
		{
			$this->sub_zf+=$this->sub_Items[$i]['s_value'];
		}
	}
	
	public function wj_data($conditions,$t)
	{
		switch($t){
			case 1:
				$tab='t_quest';
				$orders='id';
			break;
			case 2:
				$tab='t_quest_subject';
				$orders='id';
			break;
			case 3:
				$tab='t_subjects';
				$orders='id';
			break;
			case 4:
				$tab='t_dj';				
				$conditions.=" and status=1";
				$fields='djid,(select username from t_members where t_members.id=t_dj.uid) as username';
				$orders='djid';
			break;
		}
		
		return $this->get_data(array('table'=>$tab,'fields'=>$fields,'conditions'=>$conditions,'orders'=>$orders));
	} 
	/**
	 *用户信息列表
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

}
?>