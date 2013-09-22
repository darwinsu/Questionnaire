<?php

class CompetenceController extends Yaf_Controller_Abstract {
	public function init() {
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
	}

	public function indexAction()
	{
		$this->display('index');
	}
	public function userAction(){

		$target_name='用户管理';
		$this->_view->assign('target_name',$target_name);
		$this->display('user');
		
	}
	public function partAction(){
	
		$target_name='角色管理';
		$this->_view->assign('target_name',$target_name);
		$this->display('part');
		
	}
	//获取单用户数据
	public function getOneDataAction(){
		$userObj=new userModel();
		$t='t_members';
		if($_POST['id']) $conditions=" id=".$_POST['id'];
		$result=$userObj->get_data(array('table'=>$t,'conditions'=>$conditions,'orders'=>'id'));
		if($result){
			echo json_encode(array('result'=>true,'data'=>$result));
		}else{
			echo json_encode(array('result'=>false));
		}
	}
	
	//分页获取____
	public function getDataAction()
	{
		$userObj=new userModel();
		$temp=(isset($_POST['t']))?$_POST:$_GET;
		$arrWhere['username']=$_POST['username'];
		
		switch($temp['t']){
			case 1:
				$t='t_members';
				$orders='id';
			break;
			case 2:
				$t='t_auth';
			break;
			case 3:
				$t='t_part';
			break;
			case 4:
				$t='t_user_part';
			break;
		}
		
		
		$conditions="unitid='".cookie::get('unitid')."'";
		if($_POST['part_id']) $conditions.=" and part_id=".$_POST['part_id'];
		if($_POST['user_id']) $conditions.=" and user_id=".$_POST['user_id'];
		$val_arr=array();
		$item_arr=array();
		foreach($temp as $key=>$val){
			if($key!='t'&&$key!='pageno'&&$key!='v'&&$key!='state'&&$key!='page_num'){
				$conditions.=" and ".$key." like '%".$val."%'";
				array_push($val_arr,$val);
				array_push($item_arr,$key);
			}
		}
		
		$arrWhere['unitid']=cookie::get('unitid');
		$rt=$userObj->getCount($arrWhere);
		$pmCount=$rt;
		$perpage=($_POST['page_num']>0)?$_POST['page_num']:50;
		$pageno=(isset($_POST['pageno']))?$_POST['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		//echo $conditions.$t;
		$tmparr = $userObj->get_data(array('start'=>$start,'limit'=>$perpage,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		if($tmparr)
		{
			foreach($tmparr as $k => $v)
			{
				$tmparr[$k] = array(
					'id'=>$v['id'],
					'uid'=>$v['uid'],
					'username'=>$v['username'],
					'mobilephone'=>$v['mobilephone'],
					'w1Val'=>$v['w1Val'],
					'w2Val'=>$v['w2Val'],
					'w3Val'=>$v['w3Val']
				);
			}
		}
		
		$weight = $userObj->getWeightName();
		empty($weight) && $weight = array('w1Name'=>'', 'w2Name'=>'', 'w3Name'=>'');
		$menu=func::get_page($pageno,$perpage,$pmCount,Yaf_Registry::get("config")->common->get('webroot').'Competence/getData/?t='.$temp['t'],'1',$state?'del':'');
		if($rt){
			if($val_arr){
				echo json_encode(array('result'=>true,'data'=>$tmparr,'weight'=>$weight,'menu'=>$menu,'pageno'=>$pageno,'conditions_item'=>$item_arr,'conditions_val'=>$val_arr));
		}else{
				echo json_encode(array('result'=>true,'data'=>$tmparr,'weight'=>$weight,'menu'=>$menu,'pageno'=>$pageno));
		}
		}else{
				echo json_encode(array('result'=>false,'data'=>'无对应的数据','weight'=>$weight,'menu'=>$menu));
		}
	}
	
	function setWeightNameAction()
	{
		$w1Name = trim($_POST['w1Name']);
		$w1Name = $w1Name ? $w1Name : '';
		$w2Name = trim($_POST['w2Name']);
		$w2Name = $w2Name ? $w2Name : '';
		$w3Name = trim($_POST['w3Name']);
		$w3Name = $w3Name ? $w3Name : '';
		$userObj = new userModel();
		$rs = $userObj->setWeightName($w1Name, $w2Name, $w3Name);
		
		if($rs)
		{
			echo json_encode(array('result'=>true));
		}
		else
		{
			echo json_encode(array('result'=>false));
		}
	}
	
	function setWeightValAction()
	{
		$uid = trim($_POST['uid']);
		isset($_POST['w1Val']) && $value['w1Val'] = (int)trim($_POST['w1Val']);
		isset($_POST['w2Val']) && $value['w2Val'] = (int)trim($_POST['w2Val']);
		isset($_POST['w3Val']) && $value['w3Val'] = (int)trim($_POST['w3Val']);
		
		$userObj = new userModel();
		$rs = $userObj->setWeightVal($uid, $value);
		
		if($rs)
		{
			echo json_encode(array('result'=>true));
		}
		else
		{
			echo json_encode(array('result'=>false));
		}
	}
	 
	//账号管理修改
	public function m_updataAction(){
		$userObj=new  userModel();
		if(isset($_POST['username'])&&isset($_POST['sex'])&&$_POST['userid']!=''){
			 if($userObj->updateUser(array('username'=>$_POST['username'],'sex'=>$_POST['sex']),'id='.$_POST['userid'])){			
			//echo json_encode(array('result'=>true,'msg'=>'保存成功'));
			}
		}else
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
	}
 
	 
	//end
	//删除信息
	public function delAction(){
		$temp=$_POST;
		switch($temp['t']){
			case 1:
				$delObj=new partModel();
				$action='t_part';//所需更新的表单
				$orders='id';
				$conditions='id="'.$_POST['id'].'"';//获取联系条件
			break;
			case 2:
				$delObj=new partModel();
				$action='t_auth';
				$orders='id';
				$conditions='part_id="'.$_POST['id'].'"';//获取联系条件
			break;
		}
		//删除product_account中的联系
		$delObj->del($conditions,$action);
		 
		
	}
//------------------------------角色信息------------------------------------///
	//分页获取_____用户信息
	public function getPartDataAction(){	 
		$partObj=new partModel();
		$temp=(isset($_POST['t']))?$_POST:$_GET;
		$arrWhere['pname']=$_POST['pname'];
		
		switch($temp['t']){
			case 1:
				$t='t_part';
				$orders='id';
			break;
			case 2:
				$t='t_auth';
				$orders='id';
			break;
		}
		$conditions="1 and unitid='".cookie::get('unitid')."'";
		$val_arr=array();
		$item_arr=array();
		foreach($temp as $key=>$val){
			if($key!='t'&&$key!='pageno'&&$key!='v'&&$key!='state'){
				$conditions.=" and ".$key." like '%".$val."%'";
				array_push($val_arr,$val);
				array_push($item_arr,$key);
			}
		}
		// if($arrWhere['pname'])$conditions.=" and pname='".$arrWhere['pname']."'";
		$rt=$partObj->getCount($arrWhere);
		$pmCount=$rt;
		$perpage=50;
		$pageno=(isset($_POST['pageno']))?$_POST['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		$tmparr = $partObj->get_data(array('start'=>$start,'limit'=>$perpage,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		$menu=func::get_page($pageno,$perpage,$pmCount,Yaf_Registry::get("config")->common->get('webroot').'Competence/getPartData/?t='.$temp['t'],'1',$state?'del':'');
		if($rt){
			if($val_arr){
				echo json_encode(array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno,'conditions_item'=>$item_arr,'conditions_val'=>$val_arr));
		}else{
				echo json_encode(array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno));
		}
		}else{
				echo json_encode(array('result'=>false,'data'=>'无对应的数据','menu'=>$menu));
		}
	}	
//获取单橘色数据
	public function getOnePartDataAction(){
		$partObj=new partModel();
		$conditions=" id=".$_POST['id'];
		$result=$partObj->get_data(array('table'=>'t_part','conditions'=>$conditions,'orders'=>'id'));
		if($result){
			echo json_encode(array('result'=>true,'data'=>$result));
		}else{
			echo json_encode(array('result'=>false));
		}
	}	
//角色添加
	public function addPartAction(){
	$partObj=new  partModel();
		if(isset($_POST['pname'])){
			 $partObj->addPart(array('pname'=>$_POST['pname'],'jsbh'=>$_POST['jsbh'],'remark'=>$_POST['remark'],'unitid'=>cookie::get('unitid')));
		}else{
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
		}
	}
//权限分配
	public function updataRightAction(){
	$partObj=new  partModel();
		if(isset($_POST['right_id'])){
			//删除之前权限
			$action='t_auth';
			$conditions='part_id="'.$_POST['part_id'].'"';
			$partObj->del($conditions,$action);
			$arr=explode("|",$_POST['right_id']);
			for($i=0;$i<count($arr);$i++){
			if($arr[$i])
			 $partObj->updataRight(array('auth_code'=>$arr[$i],'part_id'=>$_POST['part_id'],'unitid'=>cookie::get('unitid')));
			 }
		}else{
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
		}
	}	
//角色分配
	public function updataAuthAction(){
	$partObj=new  partModel();
		if(isset($_POST['parts'])){
			//删除之前权限
			$action='t_user_part';
			$conditions='user_id='.$_POST['user_id'];
			$partObj->del($conditions,$action);
			$arr=explode(",",$_POST['parts']);
			for($i=0;$i<count($arr);$i++){
			if($arr[$i])
			 $partObj->updataUP(array('part_id'=>$arr[$i],'user_id'=>$_POST['user_id'],'unitid'=>cookie::get('unitid')));
			 }
		}else{
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
		}
	}	
//角色数据修改
	public function part_updataAction(){
		$partObj=new  partModel();
		if(isset($_POST['pname'])){
			 if($partObj->updatePart(array('pname'=>$_POST['pname'],'jsbh'=>$_POST['jsbh'],'remark'=>$_POST['remark']),'id='.$_POST['partid'])){			
			//echo json_encode(array('result'=>true,'msg'=>'保存成功'));
			 
			}
		}else
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
	}	 
}
