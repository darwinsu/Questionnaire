<?php
class reportController extends Yaf_Controller_Abstract
{

    public $reportService;

    public function init()
    {
        session_start();
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
		$this->_view->assign('title',$this->mTitle);
        include_once(APP_PATH . '/application/models/report/service/reportService.class.php');
        $this->reportService = new reportService();
    }

    public function indexAction()
    {
        $uid = cookie::get('userid');
        $wjid = $this->getRequest()->getParam('wjid');
        if(!$this->reportService->isReport($wjid))
        {
            $this->reportService->report($wjid,$uid);
        }
        $report = $this->reportService->getReportByWjid($wjid);
        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($wjid);
        $subs = $wj->getWjSubjects();//print_r($subs);
        if(!empty($subs))
        {
            include_once(APP_PATH . '/application/models/subject.php');
            foreach ($subs as $sub)
            {	if($sub['fk_type_id']!=4){	//不统计简答
                $subjects[] = new subjectModel($sub['id']);
				}
            }
        }
        $this->getView()->assign('report',$report);
        $this->getView()->assign('wj',$wj);
        $this->getView()->assign('subjects',$subjects);
		$this->display('index');
    }

    public function conditionAction()
    {
        $wjid = $this->getRequest()->getParam('wjid');
        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($wjid);

        $subs = $wj->getWjSubjects();
        if(!empty($subs))
        {
            include_once(APP_PATH . '/application/models/subject.php');
            foreach ($subs as $sub)
            {
                $subjects[] = new subjectModel($sub['id']);
            }
        }
        $this->getView()->assign('wj',$wj);
        $this->getView()->assign('subjects',$subjects);
		$this->display('condition');
    }

    public function doconditionAction()
    {
        $_sub = $this->getRequest()->getPost();
        include_once(APP_PATH . '/application/models/subject.php');

        foreach($_sub['_sub'] as $key=>$post)
        {
		 if(in_array($key,$_POST['subcheck'])){
            $action = $post['condition'];
            $action2 = $post['condition2'];	
            $doreport[$key] = $this->reportService->$action($post['wjid'],$post['subjectid'],$post['itemsid'],$action2);
			if($action2=='isAnd'){
					for($i=0;$i<count($doreport[$key]);$i++){
						if($doreport[$key][$i]!=$doreport[$key][$i-1]&&$i>0){
							unset($doreport[$key]);//不符合 并且 状态清空
						}
					}
			}
            $subjects[$key] = new subjectModel($post['subjectid']);
			}	
        }
		//结果项题目
		$result = new subjectModel($_POST['result']);
		$result_item=$result->getSubjectItems();
		$res_item=array();
		if($result_item){
			foreach($result_item as $k=>$v){
				$res_item[]=$v['id'];
			}
		}
		//print_r($doreport);
		//echo "<br>";
		//echo $action;
		//获取项的所有答卷情况
		$doreport_res = $this->reportService->$action($_POST['_sub'][$_POST['result']]['wjid'],$_POST['result'],$res_item,'isOr');
		$doreport_res_cou=count($doreport_res);
		//print_r($doreport_res);
		//统计
		if($result_item){
			foreach($result_item as $k=>$v){
				for($i=0;$i<$doreport_res_cou;$i++){	
					if($doreport){
						foreach($doreport as $key=>$value){
							foreach($value as $k=>$va){ 
							if($va['djid']==$doreport_res[$i]['djid']&&$doreport_res[$i]['wj_title_item_id']==$v['id']) {$report_item[$v['id']][$va['djid']]=$doreport_res[$i];	$dj_array[$doreport_res[$i]['djid']]=$result->wj_data('djid='.$doreport_res[$i]['djid'],4);}
							}
						}
					}else{					//if($doreport_res[$i]['wj_title_item_id']==$v['id']){$report_item[$v['id']][]=$doreport_res[$i];$dj_array[$doreport_res[$i]['djid']]=$result->wj_data('djid='.$doreport_res[$i]['djid'],4);}
					}
				}
			
			}
		}
		//print_r($report_item);
        $report = $this->reportService->getReportByWjid($post['wjid']);
		
        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($post['wjid']);
        $this->getView()->assign('wj',$wj);
        //$this->getView()->assign('subjects',$subjects);
		$this->getView()->assign('subject',$result);
        $this->getView()->assign('report',$report);
        $this->getView()->assign('doreport_res',$doreport_res);
		$this->getView()->assign('report_item',$report_item);
		$this->getView()->assign('dj_array',$dj_array);
        $this->display('docondition');
    }
	
	public function crossAction(){	
		$target_name='';
		$wjid = $this->getRequest()->getParam('wjid');
		include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($wjid);
		$this->_view->assign('wj',$wj);
		$this->_view->assign('target_name',$target_name);
		$this->display('cross');
	}
	
	//分页获取____
	public function getDataAction(){	 
		$questObj=new questModel();
		$temp=(isset($_POST['t']))?$_POST:$_GET;
		
		$conditions="1 and unitid='".cookie::get('unitid')."'";
		switch($temp['t']){
			case 1:
				$t='t_quest';
				$orders='id';
				$arrWhere['q_title']=$_POST['q_title'];
				$arrWhere['fk_sytle_id']=$_POST['sytle_id'];
				$arrWhere['fk_type_id']=$_POST['type_id'];
			break;
			case 2:
				$t='t_quest_subject';
				$orders='s_order';
				$arrWhere['fk_quest_id']=$_POST['fk_quest_id'];
				$arrWhere['s_title']=$_POST['s_title'];
				$conditions.=" and fk_type_id!=4 ";
			break;
			case 3:
				$t='t_subjects';
				$orders='s_order';
				$arrWhere['fk_subject_id']=$_POST['fk_subject_id'];
				$arrWhere['s_answer']=$_POST['s_answer'];
				$arrWhere['s_value']=$_POST['s_value'];
			break;
		}
		$val_arr=array();
		$item_arr=array();
		foreach($temp as $key=>$val){
			if($key!='t'&&$key!='pageno'&&$key!='v'&&$key!='state'){
				$conditions.=" and ".$key." = '".$val."'";
				array_push($val_arr,$val);
				array_push($item_arr,$key);
			}
		}
		
		 //if($arrWhere['q_title'])$conditions.=" and q_title='".$arrWhere['q_title']."'";
		 //if($arrWhere['fk_sytle_id'])$conditions.=" and fk_sytle_id='".$arrWhere['fk_sytle_id']."'";
		 //if($arrWhere['fk_type_id'])$conditions.=" and fk_type_id='".$arrWhere['fk_type_id']."'"; 
		 //if($arrWhere['fk_quest_id'])$conditions.=" and fk_quest_id='".$arrWhere['fk_quest_id']."'"; 
		 //if($arrWhere['fk_subject_id'])$conditions.=" and fk_subject_id='".$arrWhere['fk_subject_id']."'"; 
		 
		$rt=$questObj->getSytleCount($t,$conditions);
		$pmCount=$rt;
		$perpage=999999;
		$pageno=(isset($_POST['pageno']))?$_POST['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		$tmparr = $questObj->get_data(array('start'=>$start,'limit'=>$perpage,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		
		$menu=func::get_page($pageno,$perpage,$pmCount,Yaf_Registry::get("config")->common->get('webroot').'Quest/getData/','1',$state?'del':'');
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
	
	public function getDjDataAction(){	 
		$questObj=new questModel();
				$t='t_dj_item';
				$orders='djitemid';
				$conditions=" 1";
				$arrWhere['col_title_id']=$_POST['col_title_id'];
				$arrWhere['row_title_id']=$_POST['row_title_id'];
				$arrWhere['col_id']=$_POST['col_id'];
				$arrWhere['row_id']=$_POST['row_id'];
				if($arrWhere['col_title_id'])$conditions.=" and wj_title_id='".$arrWhere['col_title_id']."'"; 
		 		if($arrWhere['col_id'])$conditions.=" and wj_title_item_id='".$arrWhere['col_id']."'"; 
		//行数据		
		$tmparr_col = $questObj->get_data(array('table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		$count_c=count($tmparr_col);
		$col_jdid=array();
				for($i=0;$i<$count_c;$i++){
					$col_jdid[$tmparr_col[$i]['djid']]=1;
				}
		//列数据	
				$conditions=" 1 ";	
				if($arrWhere['row_title_id'])$conditions.=" and wj_title_id='".$arrWhere['row_title_id']."'"; 
		 		if($arrWhere['row_id'])$conditions.=" and wj_title_item_id='".$arrWhere['row_id']."'"; 
		$tmparr_row = $questObj->get_data(array('table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		$count_r=count($tmparr_row);
		$row_jdid=array();
				for($i=0;$i<$count_r;$i++){
					$row_jdid[]=$tmparr_row[$i]['djid'];
				}						
		$num=0;
				for($j=0;$j<count($row_jdid);$j++){	
					$num+=$col_jdid[$row_jdid[$j]];
				}
				
				echo json_encode(array('result'=>true,'data'=>$num,'menu'=>$menu,'pageno'=>$pageno,'conditions_item'=>$item_arr,'conditions_val'=>$val_arr));
		 
			
	}
}
?>