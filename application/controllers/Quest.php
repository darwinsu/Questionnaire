<?php
class QuestController extends Yaf_Controller_Abstract {
	var $db_table;
	public function init() {
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
		$this->_view->assign('title',$this->mTitle);
		$partMdl=new partModel();
		$rights=$partMdl->PartListValidate();
		$this->_view->assign('partMdl',$partMdl);
	}

	public function indexAction()
	{
		$this->_view->assign('pagenos',$_GET['pageno']);
		$this->_view->assign('wjmc',$_GET['wjmc']);
		$this->_view->assign('userid',cookie::get('userid'));
		$this->_view->assign('userlist',explode(',',cookie::get('userlist')));
		$this->_view->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
		$this->display('index');
	}
	
	public function draftAction()
	{
		$this->_view->assign('userid',cookie::get('userid'));
		$this->_view->assign('userlist',explode(',',cookie::get('userlist')));
		$this->_view->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
		$this->display('draft');
	}
	
	public function recycleAction()
	{	
		$this->_view->assign('userid',cookie::get('userid'));
		$this->_view->assign('userlist',explode(',',cookie::get('userlist')));
		$this->_view->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
		$this->display('recycle');
	}
	
	public function styleAction()
	{	
		$target_name='问卷分类';
		$this->_view->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
		$this->_view->assign('target_name',$target_name);
		$this->display('sytle');
	}
	
	public function typeAction()
	{	
		$target_name='卷子类型';
		$this->_view->assign('target_name',$target_name);
		$this->display('type');
	}
	
	public function subTypeAction()
	{	
		$target_name='题目类型';
		$this->_view->assign('target_name',$target_name);
		$this->display('subType');
	}
	
	public function subjectAction()
	{	
		$this->_view->assign('userid',cookie::get('userid'));
		$this->_view->assign('userlist',explode(',',cookie::get('userlist')));
		$this->_view->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
		$target_name='题目设置';
		$this->_view->assign('target_name',$target_name);
		$this->display('subject');
	}
	
	//分页获取____
	public function getDataAction()
	{	 
		$questObj=new questModel();
		$temp=(isset($_POST['t']))?$_POST:$_GET;
		$conditions_url="";
		unset($fields);
		switch($temp['t'])
		{
			case 1:
				$t='t_quest';
				$fields=" id,`fk_sytle_id`,`fk_type_id`,`q_title`,`q_top_desc`,`q_foot_desc`,`q_start`,`q_end`,`duration`,`status`,`switch`,`pass`,`q_repeat`,`q_anonymous`,`q_login`,`q_all`,`c_userid`,`c_time`,'".strtotime("now")."' as nowtime,(select username from t_members where t_members.id=t_quest.c_userid) as c_user,(select count(1) from t_dj a where a.wjid=t_quest.id and a.status=1) dj_num ,concat('".SITE_ROOT."Dj/start/wjid/',id) as djurl";
				$orders=$temp['orders']?$temp['orders']:'id desc';
				$arrWhere['q_title']=$temp['q_title'];
				$arrWhere['fk_sytle_id']=$temp['sytle_id'];
				$arrWhere['fk_type_id']=$temp['type_id'];
				$arrWhere['status']=$temp['status'];
				$conditions_url.="&orders=".$orders;
			break;
			case 2:
				$t='t_quest_subject';
				$orders=array(' order by s_order');
				$arrWhere['fk_quest_id']=$_POST['fk_quest_id'];
				$arrWhere['s_title']=$_POST['s_title'];
			break;
			case 3:
				$t='t_subjects';
				$orders=array(' order by s_order');
				$arrWhere['fk_subject_id']=$_POST['fk_subject_id'];
				$arrWhere['s_answer']=$_POST['s_answer'];
				$arrWhere['s_value']=$_POST['s_value'];
			break;
		}
		
		$conditions="1 and unitid='".cookie::get('unitid')."'";

		$val_arr=array();
		$item_arr=array();
		foreach($temp as $key=>$val)
		{
			if($key!='t'&&$key!='pageno'&&$key!='orders'&&$key!='v'&&$key!='state'&&$key!='perpages')
			{
				if($key=='q_title'){
					$conditions.=" and ".$key." like '%".$val."%'";
				}else{
					$conditions.=" and ".$key." = '".$val."'";	
				}
				$conditions_url.="&".$key."=".$val;
				array_push($val_arr,$val);
				array_push($item_arr,$key);
			}
		}
		 if($_POST['state']=='1')$conditions.=" and q_end>='".strtotime("now")."'";
		 if($_POST['state']=='2')$conditions.=" and q_end<'".strtotime("now")."'";
		 /*if($arrWhere['fk_type_id'])$conditions.=" and fk_type_id='".$arrWhere['fk_type_id']."'"; 
		 if($arrWhere['fk_quest_id'])$conditions.=" and fk_quest_id='".$arrWhere['fk_quest_id']."'"; 
		 if($arrWhere['status']!='')$conditions.=" and status='".$arrWhere['status']."'";*/
		 
		$rt=$questObj->getSytleCount($t,$conditions);
		$pmCount=$rt;
		$perpage=($temp['perpages'])?$temp['perpages']:50;
		$pageno=(isset($_POST['pageno']))?$_POST['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		$tmparr = $questObj->get_data(array('start'=>$start,'limit'=>$perpage,'fields'=>$fields,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		
		foreach($tmparr as $k => $v)
		{
			func::hdl_switch($v['q_end'], $tmparr[$k]['switch']);
		}
		
		$menu=func::get_page($pageno,$perpage,$pmCount,Yaf_Registry::get("config")->common->get('webroot').'Quest/getData/?t='.$temp['t'].$conditions_url,'1',$state?'del':'');
		if($rt){
			if($val_arr){
				echo json_encode(array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno,'conditions_item'=>$item_arr,'conditions_val'=>$val_arr));
			}else{
				echo json_encode(array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno));
			}
		}else{
			echo json_encode(array('result'=>false,'data'=>'','menu'=>$menu));
		}
	}
	
	/*
	 | 问卷停止
	 +-----------------------
	 */
	function stop()
	{
		global $_G;
		$questObj=new questModel();
		
		
	}
	
	//获取最大排序、题号
	public function getOrderAction(){	 
		$questObj=new questModel();
		$fields=" max((case WHEN (title_id REGEXP '^[0-9]+[0-9]*$')>0 then title_id else 0 end)+0)+1 as m_title,floor((max(s_order)+10)/10)*10 as m_order ";
		$conditions="1 and fk_quest_id=".$_POST['fk_quest_id'];
		$result=$questObj->get_data(array('start'=>'0','limit'=>'9999999','table'=>'t_quest_subject','fields'=>$fields,'conditions'=>$conditions,'orders'=>'id'));
		if($result){
			echo json_encode(array('result'=>true,'data'=>$result));
		}else{
			echo json_encode(array('result'=>false));
		}
	}
	//分页获取_____用户信息
	public function getSytleDataAction(){	 
		$questObj=new questModel();
		$temp=(isset($_POST['t']))?$_POST:$_GET;
		$arrWhere['name']=$_POST['name'];
		$conditions="1=1";
		$conditions_url="";
		switch($temp['t']){
			case 1:
				$t='t_quest_sytle';
				$orders='id';
				$conditions.=" and unitid='".cookie::get('unitid')."'";
			break;
			case 2:
				$t='t_quest_type';
				$orders='id';
			break;
			case 3:
				$t='t_subject_type';
				$orders=array('0'=>'order by id');
			break;
		}
		//$conditions="1 and unitid='".cookie::get('unitid')."'";
		
		$val_arr=array();
		$item_arr=array();
		foreach($temp as $key=>$val){
			if($key!='t'&&$key!='pageno'&&$key!='v'&&$key!='state'&&$key!='perpages'){
				$conditions.=" and ".$key." like '%".$val."%'";
				$conditions_url.="&".$key."=".$val;
				array_push($val_arr,$val);
				array_push($item_arr,$key);
			}
		}
		// if($arrWhere['name'])$conditions.=" and name='".$arrWhere['name']."'";
		$rt=$questObj->getSytleCount($t,$conditions);
		$pmCount=$rt;
		$perpage=($temp['perpages'])?$temp['perpages']:50;
		$pageno=(isset($_POST['pageno']))?$_POST['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		$tmparr = $questObj->get_data(array('start'=>$start,'limit'=>$perpage,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		$menu=func::get_page($pageno,$perpage,$pmCount,Yaf_Registry::get("config")->common->get('webroot').'Quest/getSytleData/?t='.$temp['t'].$conditions_url,'1',$state?'del':'');
		if($tmparr){
			foreach($tmparr as $k=>$v){
				foreach($v as $key=>$va){
					$tmparray[$k][$key]=$va;
				}
			}
		}
		if($rt){
			if($val_arr){
				echo json_encode(array('result'=>true,'data'=>$tmparray,'menu'=>$menu,'pageno'=>$pageno,'conditions_item'=>$item_arr,'conditions_val'=>$val_arr));
			}else{
				echo json_encode(array('result'=>true,'data'=>$tmparray,'menu'=>$menu,'pageno'=>$pageno));
			}
		}else{
			echo json_encode(array('result'=>false,'data'=>'','menu'=>$menu));
		}
	}
	
	//获取单数据
	public function getOneDataAction(){
		$questObj=new questModel();
		if($_POST['id'])$conditions=" id=".$_POST['id'];
		if($_POST['fk_subject_id'])$conditions=" fk_subject_id=".$_POST['fk_subject_id'];
		switch($_POST['t']){
			case 1:
				$t='t_quest';
			break;
			case 2:
				$t='t_quest_subject';
			break;
			case 3:
				$t='t_subjects';
			break;
		}
		$result=$questObj->get_data(array('table'=>$t,'conditions'=>$conditions,'orders'=>'id'));
		if($result){
			echo json_encode(array('result'=>true,'data'=>$result));
		}else{
			echo json_encode(array('result'=>false));
		}
	}	
	
	//获取单数据
	public function getOneSytleDataAction(){
		$questObj=new questModel();
		$conditions=" id=".$_POST['id'];
		switch($_POST['t']){
			case 1:
				$t='t_quest_sytle';
			break;
			case 2:
				$t='t_quest_type';
			break;
			case 3:
				$t='t_subject_type';
			break;
		}
		$result=$questObj->get_data(array('table'=>$t,'conditions'=>$conditions,'orders'=>'id'));
		if($result){
			echo json_encode(array('result'=>true,'data'=>$result));
		}else{
			echo json_encode(array('result'=>false));
		}
	}	
	//复制
	public function copyAction(){
		$questObj=new  questModel();
		$t='t_quest';
		if(isset($_POST['new_title'])&&$_POST['copy_id']){
			 $quest_sql="insert into t_quest(fk_sytle_id,fk_type_id,q_title,q_top_desc,q_foot_desc,q_start,q_end,duration,status,pass,q_repeat,q_anonymous,q_login,q_all,c_userid,c_time,pass_type,unitid) select fk_sytle_id,fk_type_id,'".$_POST['new_title']."',q_top_desc,q_foot_desc,q_start,q_end,duration,status,pass,q_repeat,q_anonymous,q_login,q_all,'".cookie::get('userid')."','".strtotime("now")."',pass_type,unitid from t_quest where id='".$_POST['copy_id']."'";
			 $results=$questObj->exeSql($quest_sql);
			 $questId=$questObj->inID();
			if($results&&$questId){
				$conditions="fk_quest_id='".$_POST['copy_id']."' and unitid='".cookie::get('unitid')."'";
				$result=$questObj->get_data(array('table'=>'t_quest_subject','conditions'=>$conditions,'orders'=>'id'));
				$coun=count($result);
				for($i=0;$i<$coun;$i++){
					$quest_sub_sql="insert into t_quest_subject(fk_quest_id,s_title,fk_type_id,s_url,s_replenish,s_type,s_order,title_id,q_remark,s_len,s_value,unitid) select '".$questId."',s_title,fk_type_id,s_url,s_replenish,s_type,s_order,title_id,q_remark,s_len,s_value,unitid from t_quest_subject where id='".$result[$i]['id']."'";
					$results_sub=$questObj->exeSql($quest_sub_sql);
					$quest_subId=$questObj->inID();
					if($results_sub){
						$conditions="fk_subject_id='".$result[$i]['id']."' and unitid='".cookie::get('unitid')."'";
						$result_sub=$questObj->get_data(array('table'=>'t_subjects','conditions'=>$conditions,'orders'=>'id'));
						$count_sub=count($result_sub);
						for($j=0;$j<$count_sub;$j++){
							$sub_sql="insert into t_subjects(fk_subject_id,s_answer,s_value,s_order,s_url,s_replenish,unitid) select '".$quest_subId."',s_answer,s_value,s_order,s_url,s_replenish,unitid from t_subjects where id='".$result_sub[$j]['id']."'";
							$res_sub=$questObj->exeSql($sub_sql);
						}
					}
				}
			}
		}
		else
		{
			echo json_encode(array('result'=>false,'msg'=>'保存失败'));
		}
	}
	
	 //分类添加
	public function addSytleAction(){
		$questObj=new  questModel();
		switch($_POST['t']){
			case 1:
				$t='t_quest_sytle';
			break;
			case 2:
				$t='t_quest_type';
			break;
			case 3:
				$t='t_subject_type';
			break;
		}
		if(isset($_POST['name'])){
			 $questObj->add($t,array('name'=>$_POST['name'],'unitid'=>cookie::get('unitid')));
		}else{
			echo json_encode(array('result'=>false,'msg'=>'保存失败'));
		}
	}
	//问卷添加
	public function addAction()
	{
		$questObj=new  questModel();	
		switch($_POST['t'])
		{
			case 1:	
				if(isset($_POST['q_title']))
				{
					$_POST['participants'] = trim($_POST['participants']);
					$lmt = empty($_POST['participants']) ? 0 : 1;
					$data = array(
						'q_title'=>$_POST['q_title'],
						'q_start'=>$_POST['q_start'],
						'q_end'=>$_POST['q_end'],
						'fk_sytle_id'=>$_POST['fk_sytle_id'],
						'fk_type_id'=>$_POST['fk_type_id'],
						'pass_type'=>$_POST['pass_type'],
						'pass'=>$_POST['pass'],
						'q_login'=>$_POST['q_login'],
						'duration'=>$_POST['duration'],
						'q_anonymous'=>$_POST['q_anonymous'],
						'c_time'=>strtotime("now"),
						'status'=>$_POST['status'],
						'q_repeat'=>$_POST['q_repeat'],
						'unitid'=>cookie::get('unitid'),
						'c_userid'=>cookie::get('userid'),
						'lmt'=>$lmt
					);
					$quest_id = $questObj->add('t_quest', $data);
					
					if($quest_id)
					{
						$ptct = new participantsModel();
						
						if($lmt)
						{
							$participants = explode(',', $_POST['participants']);
							$ptct->add($quest_id, $participants);
						}
					}
				}
				else
				{
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));
				}
				break;
			case 2:
				$t='t_quest_subject';	
				if(isset($_POST['s_title']))
				{
					$questObj->add($t,array('s_title'=>$_POST['s_title'],'title_id'=>$_POST['title_id'],'s_type'=>$_POST['s_type'],'fk_type_id'=>$_POST['fk_type_id'],'s_url'=>$_POST['s_url'],'s_order'=>$_POST['s_order'],'q_remark'=>$_POST['q_remark'],'fk_quest_id'=>$_POST['fk_quest_id'],'chk_limit'=>$_POST['chk_limit'],'unitid'=>cookie::get('unitid')));
					 $subject_id=$questObj->inID();
					 //简答题 自动添加 唯一选项
					 if($_POST['fk_type_id']=='4'){
						$questObj->add('t_subjects',array('s_answer'=>$_POST['s_title'],'s_order'=>$_POST['s_order'],'s_value'=>$_POST['s_value'],'s_url'=>$_POST['s_url'],'s_replenish'=>$_POST['s_replenish'],'fk_subject_id'=>$subject_id,'unitid'=>cookie::get('unitid')));
					 }
				}else{
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));
				}
				break;
			case 3:
				$t='t_subjects';	
				if(isset($_POST['s_answer'])){
					$questObj->add($t,array('s_answer'=>$_POST['s_answer'],'s_order'=>$_POST['s_order'],'s_value'=>$_POST['s_value'],'s_url'=>$_POST['s_url'],'s_replenish'=>$_POST['s_replenish'],'fk_subject_id'=>$_POST['fk_subject_id'],'unitid'=>cookie::get('unitid')));
				}else{
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));
				}
				break;
		}
	}
	//问卷数据修改
	public function updataAction(){
		$questObj = new questModel();
		switch($_POST['t']){
			case 1:
				$t='t_quest';
				if(isset($_POST['q_title']))
				{
					$_POST['participants'] = trim($_POST['participants']);echo $_POST['participants'];
					$lmt = empty($_POST['participants']) ? 0 : 1;
					$data = array(
						'q_title'=>$_POST['q_title'],
						'q_start'=>$_POST['q_start'],
						'q_end'=>$_POST['q_end'],
						'fk_sytle_id'=>$_POST['fk_sytle_id'],
						'fk_type_id'=>$_POST['fk_type_id'],
						'pass_type'=>$_POST['pass_type'],
						'pass'=>$_POST['pass'],
						'q_login'=>$_POST['q_login'],
						'duration'=>$_POST['duration'],
						'q_anonymous'=>$_POST['q_anonymous'],
						'q_repeat'=>$_POST['q_repeat'],
						'lmt'=>$lmt
					);
					$questObj->update($t, $data, 'id='.$_POST['quest_id']);
					
					$ptct = new participantsModel();
					
					$ptct->del($_POST['quest_id']);
					if($lmt)
					{
						$participants = explode(',', $_POST['participants']);print_r($participants);
						$ptct->add($_POST['quest_id'], $participants);
					}
				}
				else
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));
				break;
			case 2:
				$t='t_quest_subject';
				if(isset($_POST['s_title'])){		
					$questObj->update($t,array('s_title'=>$_POST['s_title'],'title_id'=>$_POST['title_id'],'s_type'=>$_POST['s_type'],'fk_type_id'=>$_POST['fk_type_id'],'s_url'=>$_POST['s_url'],'s_order'=>$_POST['s_order'],'q_remark'=>$_POST['q_remark'],'chk_limit'=>$_POST['chk_limit']),'id='.$_POST['q_subjesct_id']);
				}
				else
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));	
				break;
			case 3:
				$t='t_subjects';
				if(isset($_POST['s_answer'])){		
					$questObj->update($t,array('s_answer'=>$_POST['s_answer'],'s_order'=>$_POST['s_order'],'s_value'=>$_POST['s_value'],'s_url'=>$_POST['s_url'],'s_replenish'=>$_POST['s_replenish']),'id='.$_POST['subjesct_id']);
				}
				else
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));	
				break;
			case 4:
				$t='t_subjects';
				if(isset($_POST['s_answer'])){
					$questObj->update($t,array('s_answer'=>$_POST['s_answer'],'s_len'=>$_POST['s_len'],'s_type'=>$_POST['s_type'],'s_order'=>$_POST['s_order'],'s_value'=>$_POST['s_value']),'fk_subject_id='.$_POST['fk_subject_id']);
				}
				else
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));	
				break;
			case 5:
				$t='t_quest';
				if(isset($_POST['q_foot_desc'])||isset($_POST['q_top_desc'])){
					$questObj->update($t,array('q_foot_desc'=>$_POST['q_foot_desc'],'q_top_desc'=>$_POST['q_top_desc']),'id='.$_POST['quest_id']);
				}
				else
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));
				break;
			case 6:
				$t='t_quest';
				if(isset($_POST['ids'])){
					$questObj->exeSql("update ".$t." set status='".$_POST['status']."' where id in(".$_POST['ids'].")");
				}
				else
					echo json_encode(array('result'=>false,'msg'=>'保存失败'));
				break;
		}	
	}	
	//数据修改
	public function sytle_updataAction(){
		$questObj=new  questModel();
		switch($_POST['t']){
			case 1:
				$t='t_quest_sytle';
			break;
			case 2:
				$t='t_quest_type';
			break;
			case 3:
				$t='t_subject_type';
			break;
		}
		if(isset($_POST['name'])){
			  $questObj->update($t,array('name'=>$_POST['name']),'id='.$_POST['qid']);
		}else
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
	}	
	
	//批量删除
	public function delallAction(){
		$questObj=new  questModel();
		switch($_POST['t']){
			case 1:
				$t='t_quest';
				if(isset($_POST['ids'])){
			  	$questObj->exeSql("delete from ".$t." where id in(".$_POST['ids'].")");
				}else
				echo json_encode(array('result'=>false,'msg'=>'保存失败'));	
			break;	
			case 2:
				$t='t_quest_subject';
				if(isset($_POST['ids'])){
			  	$questObj->exeSql("delete from ".$t." where id in(".$_POST['ids'].")");
				}else
				echo json_encode(array('result'=>false,'msg'=>'保存失败'));
			break;	
			case 3:
				$t='t_subjects';
				if(isset($_POST['ids'])){
			  	$questObj->exeSql("delete from ".$t." where id in(".$_POST['ids'].")");
				}else
				echo json_encode(array('result'=>false,'msg'=>'保存失败'));
			break;		
		}
	}
	//删除信息
	public function delSytleAction(){
		$temp=$_POST;
		switch($temp['t']){
			case 1:
				$questObj=new questModel();
				$action='t_quest_sytle';
				$conditions='id="'.$_POST['id'].'"';//获取联系条件
			break;
			case 2:
				$questObj=new questModel();
				$action='t_quest_type';
				$conditions='id="'.$_POST['id'].'"';//获取联系条件
			break;
			case 3:
				$questObj=new questModel();
				$action='t_subject_type';
				$conditions='id="'.$_POST['id'].'"';//获取联系条件
			break;
		}
		//删除
		$questObj->del($action,$conditions);
	}
	//删除设计
	public function delAction(){
		$temp=$_POST;
		$questObj=new questModel();
		switch($temp['t']){
			case 1:
				//删除题目表
				
				$tmparr = $questObj->get_data(array('table'=>'t_quest_subject','conditions'=>'fk_quest_id='.$temp[''],'orders'=>$orders));
				$count_t=count($tmparr);
				unset($delstr);
				for($i=0;$i<count_t;$i++)
				{
					//删除题目项
					$action='t_subjects';
					$conditions='fk_subject_id="'.$tmparr[$i]['id'].'"';//获取联系条件
					$questObj->del($action,$conditions);
				}
				
				$action='t_quest_subject';
				$conditions='fk_quest_id="'.$_POST['id'].'"';//获取联系条件
				$questObj->del($action,$conditions);
				//删除设计
				$action='t_quest';
				$conditions='id="'.$_POST['id'].'"';//获取联系条件
				//删除
				$questObj->del($action,$conditions);
				break;
			//题目删除	
			case 2:
				//选项删除
				$action='t_subjects';
				$conditions='fk_subject_id="'.$_POST['id'].'"';//获取联系条件
				$questObj->del($action,$conditions);	
				$action='t_quest_subject';
				$conditions='id="'.$_POST['id'].'"';//获取联系条件
				//删除
				$questObj->del($action,$conditions);
				break;
			case 3:
				//选项删除
				$action='t_subjects';
				$conditions='id="'.$_POST['id'].'"';//获取联系条件
				$questObj->del($action,$conditions);	
				break;
		}	
	}
}
