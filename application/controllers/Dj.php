<?php
class DjController extends Yaf_Controller_Abstract
{

    var $djService;

    function init()
    {
        session_start();
        include_once(APP_PATH . '/application/models/dj/service/djService.class.php');
        $this->djService = new djService();
    }

    function indexAction()
    {
        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel();
        $this->getView()->assign('arrwj',$wj->getWjAll());
		$this->display('index');
    }
    function startAction()
    {
        $uid = cookie::get('userid');
        $wjid = $this->getRequest()->getParam('wjid');
		$this->wapAction($wjid);
		$this->getView()->assign('dj_no',$_GET['dj_no']);
		$this->getView()->assign('wjid',$wjid);
        include_once(APP_PATH . '/application/models/quest.php');
		$conf = Yaf_Application::app()->getConfig();
		$conf = $conf->get('cookie');
		$_COOKIE[$conf['pre'].'unitid'] = false;
        $wj = new questModel($wjid);
        //$djid = $this->djService->startDj($wjid,$uid,$wj->getRepeat());
		//判断是否重复答卷
		if(!$_GET['dj_no']){
			$wj_all=$wj->getAlldata();
			if($wj_all[0]['status']!='1'||strtotime("now")>($wj_all[0]['q_end']+(3600*24))){
					echo '<script language="javascript">alert("该问卷已关闭作答");location.href="'.SITE_ROOT.'main";</script>';
					exit;
			}
			if($wj_all[0]['q_start']>strtotime("now")){
					echo '<script language="javascript">alert("该问卷未开始");location.href="'.SITE_ROOT.'main";</script>';
					exit;
			}
			if(!$wj_all[0]['q_repeat']&&$uid>0){
				if($this->djService->isDj($wjid,$uid)){
					echo '<script language="javascript">alert("您已经回答过该问卷");location.href="'.SITE_ROOT.'main";</script>';
					exit;
				}
			}
			if($wj_all[0]['q_login']&&!$wj_all[0]['q_anonymous']){
				if(cookie::get('isLogin')!=1){
					$url = SITE_ROOT.$_SERVER['REQUEST_URI'];
					echo '<script language="javascript">alert("需登录作答");location.href="'.SITE_ROOT.'Index/index?referer='.$url.'";</script>';
					exit;
				}
			}
			if($wj_all[0]['pass_type']){
				$post = $this->getRequest()->getPost();
				if(!$post['passdj']){
					$this->getView()->assign('passMsg',$passMsg);
					$this->getView()->assign('djid',$djid);
					$this->display('pass');exit;
				}
				else{
					if($post['passdj']!=$wj_all[0]['pass']){
					echo $passMsg='<script language="javascript">alert("密码错误");location.href="'.SITE_ROOT.'main";</script>';
					exit;
					}
					
				}
			}
		}
		
        if($djid == -1)
        {
            $this->redirect('../../../my/');
        }

        $subs = $wj->getWjSubjects();
        if(!empty($subs))
        {
            include_once(APP_PATH . '/application/models/subject.php');
            foreach ($subs as $sub)
            {
                $subjects[] = new subjectModel($sub['id']);
            }
        }
		$this->getView()->assign('passMsg',$passMsg);
        $this->getView()->assign('wj',$wj);
        $this->getView()->assign('djid',$djid);
        $this->getView()->assign('subjects',$subjects);
		$this->display('start');
    }
    function submitAction()
    {
        $post = $this->getRequest()->getPost();
        $arrDjItems = array();
		//var_export($post);exit;
        foreach ($post as $key => $ps)
        {
            $match = array();
            preg_match('/^_(\d+)_(checkbox|radio)_$/',$key,$match);			
         
            if(!empty($match))
            {
				if(is_array($arrDjItems[$match[1]])){
                $arrDjItems[$match[1]] += array('wj_title_id'=> $match[1],'wj_title_item_id'=>$ps);
				}else{
				$arrDjItems[$match[1]] = array('wj_title_id'=> $match[1],'wj_title_item_id'=>$ps);
				}
                continue;
            }
            preg_match('/^_(\d+)_textarea_(\d+)_$/',$key,$match);
            if(!empty($match))
            {
				if(is_array($arrDjItems[$match[1]])){
                $arrDjItems[$match[1]] += array('wj_title_id'=> $match[1],'wj_title_item_id'=>$match[2],'dj_answer'=>$ps);
				}else{
				$arrDjItems[$match[1]] = array('wj_title_id'=> $match[1],'wj_title_item_id'=>$match[2],'dj_answer'=>$ps);
				}
                continue;
            }
			preg_match('/^_(\d+)_add_(\d+)_$/',$key,$match);
            if(!empty($match))
            {   if(is_array($arrDjItems[$match[1]])){
                $arrDjItems[$match[1]]= $arrDjItems[$match[1]]+array('dj_additional'=>$ps);
                }else{
				$arrDjItems[$match[1]]=array('dj_additional'=>$ps);
				}
				continue;
            }
            
        }
		//print_r($arrDjItems);exit;
		$arrDjItems['ttime']=$post['ttime'];		
		$uid = (cookie::get('userid')?cookie::get('userid'):0);
		//var_export($arrDjItems);exit;
        $wjid = $post['wjid']?$post['wjid']:0;
        include_once(APP_PATH . '/application/models/quest.php');
		$wj = new questModel($wjid);
       // $djid = $post['djid'];
		$djid = $this->djService->startDj($wjid,$uid,$wj->getRepeat());

//        $arrDjItems = array(
//            0 => array('wj_title_id'=> 1,'wj_title_item_id'=>3,'dj_answer'=>null,'dj_additional'=>null),
//            1 => array('wj_title_id'=> 2,'wj_title_item_id'=>4,'dj_answer'=>null,'dj_additional'=>null)
//        );
        $this->djService->submitDj($wjid,$djid,$arrDjItems);
        //$this->redirect('../my/');
		if($uid<1){
			$this->display('thanks');exit;
		}
		$this->redirect(SITE_ROOT.'Dj/my/');
    }

    function myAction()
    {
        $uid = cookie::get('userid');
        /*$djList = $this->djService->getMyDjListPage($uid,$page=1,$pageSize=10);
        if(!empty($djList))
        {
            include_once(APP_PATH . '/application/models/quest.php');
            foreach ($djList as $dj)
            {
                $wj = new questModel($dj->getWjid());
                $dj->setObjWj($wj);
            }
        }*/
		$this->getView()->assign('userlist',explode(',',cookie::get('userlist')));
		$this->getView()->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
        $this->getView()->assign('djlist',$djList);
		$this->display('my');
    }
	
	//指定问卷的所有答卷
	function answersAction()
    {
		$wjid = $this->getRequest()->getParam('wjid');
		
		$this->getView()->assign('userlist',explode(',',cookie::get('userlist')));
		$this->getView()->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
		$this->getView()->assign('djlist',$djList);
		$this->getView()->assign('wjid',$wjid);
		$this->display('answers');
    }
	
	// 我未回答的问卷
	function undoAction()
	{
		$this->display('undo');
	}
	
	//我已回答的问卷
	function doneAction()
	{
		$this->display('done');
	}
	
	//已回答 or 未回答列表数据
	function listdataAction()
	{
		$userid = cookie::get('userid');
		$usrMdl = new userModel();
		$usrInfo = $usrMdl->getUserById('id='.$userid);
		$uid = (int)$usrInfo['uid'];
		$questObj = new questModel();
		$conditions_url = "";
		$fields = "id,`fk_type_id`,`q_title`,`q_start`,`q_end`,(select username from t_members where t_members.id=t_quest.c_userid) as c_user,'".strtotime("now")."' as nowtime,(select djid from t_dj a where a.wjid=t_quest.id and a.uid='".$userid."') djid,(select count(1) from t_dj a where a.wjid=t_quest.id and a.uid='".$userid."') dj_num";
		$conditions = "unitid='".cookie::get('unitid')."' AND status=1 ";//status = 0：回收站 1：在用 2：草稿
		$val_arr = array();
		$item_arr = array();
		
		switch($_REQUEST['zd'])
		{
			case 1: //已回答
				$conditions.=" AND EXISTS (SELECT 1 FROM t_dj a WHERE a.wjid=t_quest.id AND a.uid='".$userid."')";
				break;
			case 2: //未回答
				$conditions.=" AND (t_quest.q_end=0 OR t_quest.q_end>".time().") AND NOT EXISTS (SELECT 1 FROM t_dj a WHERE a.wjid=t_quest.id AND a.uid='".$userid."')";
		}
		
		$conditions = ' (lmt=0 AND ('.$conditions.')) OR (lmt=1 AND ('.$conditions.') AND EXISTS(SELECT 1 FROM t_participants p WHERE p.fk_quest_id=t_quest.id AND p.uid='.$uid.'))';
		
		$rs = $questObj->getSytleCount('t_quest', $conditions);
		
		$total = $rs;
		$perpage = max($_REQUEST['perpage'], 50);
		$page = max($_REQUEST['pageno'], 1);
		$start = ($page - 1) * $perpage;
		$pages = ceil($rs / $perpage);
		$list = $questObj->get_data(array('start' => $start, 'limit' => $perpage, 'fields' => $fields, 'table' => 't_quest', 'conditions' => $conditions));
		$menu=func::get_page($page, $perpage, $rs, Yaf_Registry::get("config")->common->get('webroot').'Dj/listdata/?zd='.$_REQUEST['zd'],'1',$state?'del':'');
		
		if($rs)
		{
			foreach($list as $k => $v)
			{
				$uids .=','.$v['c_userid'];
			}
			$uids = trim($uids, ',');
			$baseMdl = new baseModel();
			$tmps = $baseMdl->getDB(0,0,'id,useranme', 't_members', 'id IN('.$uids.')');
			
			foreach($tmps as $v)
			{
				$users[$v['id']] = $v['useranme'];
			}
			
			foreach($list as $k => $v)
			{
				$v['c_userid'] = $users[$v['c_userid']];
				$list[$k] = $v;
			}
			
			$out = json_encode(array('result'=>true, 'data'=>$list, 'menu'=>$menu, 'page'=>$page, 'pages'=>$pages));
		}
		else
		{
			$out = json_encode(array('result'=>false,'data'=>'','menu'=>$menu));
		}
		echo $out;
	}

    function djinfoAction()
    {	
		
		$this->getView()->assign('userlist',explode(',',cookie::get('userlist')));
		$this->getView()->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
        $djid = $this->getRequest()->getParam('djid');
		$swjid=$this->getSDJ($djid);						//上一问卷
		if($swjid) $swj="<a href='".SITE_ROOT."Dj/djinfo/djid/".$swjid."/'>";
		$xwjid=$this->getXDJ($djid);						//下一问卷
		if($xwjid) $xwj="<a href='".SITE_ROOT."Dj/djinfo/djid/".$xwjid."/'>";
        $objDj = $this->djService->getDj($djid);
		
        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($objDj->getWjid());

        $subs = $wj->getWjSubjects();
        if(!empty($subs))
        {
            include_once(APP_PATH . '/application/models/subject.php');
            foreach ($subs as $sub)
            {
                $subjects[] = new subjectModel($sub['id']);
            }

        }
		
		$arrVal=array();
		if(is_array($objDj->getArrDjItemObject()))
		{
			foreach($objDj->getArrDjItemObject() as $key=>$val)
			{
				$arrVal[$val->wj_title_id][$val->wj_title_item_id]['dj_answer']=$val->dj_answer;
				$arrVal[$val->wj_title_id][$val->wj_title_item_id]['checked']='1';
				$arrVal[$val->wj_title_id][$val->wj_title_item_id]['df']=$val->df;
				$arrVal[$val->wj_title_id][$val->wj_title_item_id]['dj_additional']=$val->dj_additional;
			}
		}

		//计算得分
		$arrDf=array();
		if(is_array($subjects))
		{
			foreach($subjects as $k=>$sub)
			{
				$tmpDf=0; //本题目得分
				if($sub->sub_type_id=='3') //多选
				{
					if(is_array($sub->sub_Items))
					{
						foreach($sub->sub_Items as $k2=>$it)
						{
							if($it['s_value']=='0') //分值等于0，表示不用选择
							{
								if($arrVal[$sub->sub_Id][$it['id']]['checked']=='1') //如果选择了该选项，直接本题目分数为0
								{
									$tmpDf=0;
									break;
								}
							}
							else //分值不等于0，表示需要选择
							{
								if($arrVal[$sub->sub_Id][$it['id']]['checked']=='1') //如果选择了该选项，直接本题目分数加多分值
								{
									$tmpDf+=($it['s_value']/1);
								}
								else //如果应该选的没有选，直接本题目分数为0
								{
									$tmpDf=0;
									break;
								}
							}
						}
					}
					$arrDf[$sub->sub_Id]['df']=$tmpDf;
				}
				else if($sub->sub_type_id=='2') //单选
				{
					if(is_array($sub->sub_Items))
					{
						foreach($sub->sub_Items as $k2=>$it)
						{
							if($arrVal[$sub->sub_Id][$it['id']]['checked']=='1')
							{
								$tmpDf+=($it['s_value']/1);
							}
						}
					}
					$arrDf[$sub->sub_Id]['df']=$tmpDf;
				}
				else if($sub->sub_type_id=='4') //简答
				{
					if(is_array($sub->sub_Items))
					{
						foreach($sub->sub_Items as $k2=>$it)
						{
							$tmpDf+=($it['s_value']/1);
						}
					}
					//$arrDf[$sub->sub_Id]['df']=$arrVal[$sub->sub_Id][$it['id']]['df'];
					$arrDf[$sub->sub_Id]['df']=$tmpDf;
				}
			}
		}
		
		$this->getView()->assign('swj',$swj);
        $this->getView()->assign('xwj',$xwj);
		$this->getView()->assign('arrVal',$arrVal);
        $this->getView()->assign('objDj',$objDj);
        $this->getView()->assign('wj',$wj);
        $this->getView()->assign('subjects',$subjects);
		$this->getView()->assign('arrDf',$arrDf);
		$this->display('djinfo');
    }
	
	//获取__上一答卷ID__
	function getSDJ($id){	 
		$questObj=new questModel();	
				$t='t_dj';
				$fields="djid";
				$orders=array('order by djid desc');				
		$conditions="1 and status ='1' and unitid='".cookie::get('unitid')."' and djid<'".$id."'";		
		$jsbhlist=explode(',',cookie::get('jsbhlist'));
		if(in_array('1',$jsbhlist)||in_array('2',$jsbhlist)||in_array('3',$jsbhlist)){
		}else{
		$conditions.=" and ( uid=".cookie::get('userid').")";
		}
		$tmparr = $questObj->get_data(array('start'=>0,'limit'=>1,'fields'=>$fields,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		return $tmparr[0]['djid'];
	}
	
	//获取__下一答卷ID__
	function getXDJ($id){	 
		$questObj=new questModel();	
				$t='t_dj';
				$fields="djid";
				$orders='djid';				
		$conditions="1 and status ='1' and unitid='".cookie::get('unitid')."' and djid>'".$id."'";
		$jsbhlist=explode(',',cookie::get('jsbhlist'));
		if(in_array('1',$jsbhlist)||in_array('2',$jsbhlist)||in_array('3',$jsbhlist)){
		}else{
		$conditions.=" and ( uid=".cookie::get('userid').")";
		}
		$tmparr = $questObj->get_data(array('start'=>0,'limit'=>1,'fields'=>$fields,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		return $tmparr[0]['djid'];
	}
	
	//分页获取
	function getDataAction()
	{
		$questObj=new questModel();
		$temp=(isset($_POST['t']))?$_POST:$_GET;
		$conditions = $conditions_url="";
		switch($temp['t'])
		{
			case 1:
				$t='t_dj';
				$fields="(select username from t_members where t_members.id=t_dj.uid) as c_user,(select q_title from t_quest where t_dj.wjid=t_quest.id) as q_title,t_dj.*,(select c_userid from t_quest where t_quest.id=wjid) as wuid";
				$orders=$temp['orders']?$temp['orders']:'djid';
				$conditions_url.="&orders=".$orders;
			break;
		}
		
		$conditions .= " 1 AND (unitid='".cookie::get('unitid')."' or t_dj.wjid in (select t_quest.id from t_quest where unitid='".cookie::get('unitid')."'))";
		$val_arr=array();
		$item_arr=array();
		foreach($temp as $key=>$val)
		{
			if($key!='t'&&$key!='pageno'&&$key!='orders'&&$key!='v'&&$key!='state'&&$key!='wjids')
			{
				if($key=='q_title')
				{
					$conditions.=" AND t_dj.wjid in (select t_quest.id from t_quest where q_title like '%".$temp['q_title']."%' and unitid='".cookie::get('unitid')."')"; 
				}
				else
				{				
					$conditions.=" and ".$key." ='".$val."'";
				}
				$conditions_url.="&".$key."=".$val;
				array_push($val_arr,$val);
				array_push($item_arr,$key);
			}
		}
		
		$jsbhlist=explode(',',cookie::get('jsbhlist'));
		if(in_array('1',$jsbhlist)||in_array('2',$jsbhlist)||in_array('3',$jsbhlist))
		{
			
		}
		else
		{
			$conditions.=" and ( uid=".cookie::get('userid').")";
		}
		
		if($temp['wjid'])
		{
			$conditions .=' AND wjid='.$temp['wjid'];
			$conditions_url .="&wjid=".$temp['wjid'];
		}
		
		if($temp['wjids'])
		{
			$conditions .=" or wjid in (select id from t_quest where c_userid='".$temp['wjids']."')";
			$conditions_url .="&wjids=".$_POST['wjids'];
		}
		if($temp['state']=='1')$conditions.=" and q_end>='".strtotime("now")."'";
		if($temp['state']=='2')$conditions.=" and q_end<'".strtotime("now")."'";
		
		$rt=$questObj->getSytleCount($t,$conditions);
		$pmCount=$rt;
		$perpage=50;
		$pageno=(isset($_POST['pageno']))?$_POST['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		$tmparr = $questObj->get_data(array('start'=>$start,'limit'=>$perpage,'fields'=>$fields,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		$menu=func::get_page($pageno,$perpage,$pmCount,Yaf_Registry::get("config")->common->get('webroot').'Dj/getData/?t='.$temp['t'].$conditions_url,'1',$state?'del':'');
		if($rt)
		{
			if($val_arr)
			{
				echo json_encode(array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno,'conditions_item'=>$item_arr,'conditions_val'=>$val_arr));
			}
			else
			{
				echo json_encode(array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno));
			}
		}
		else
		{
			echo json_encode(array('result'=>false,'data'=>'','menu'=>$menu));
		}
	}
	//问卷数据修改
	function updataAction(){
		$questObj=new  questModel();
		switch($_POST['t']){
			case 1:
				$t='t_dj';
		if(isset($_POST['id'])){
			  $questObj->update($t,array('status'=>$_POST['status']),'djid='.$_POST['id']);
		}else
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
		break;
		}
			 
	}
//问卷数据修改
	function delAction(){
		$questObj=new  questModel();
		switch($_POST['t']){
			case 1:
				$t='t_dj';
		if(isset($_POST['id'])){
			  $questObj->del('t_dj_item','djid='.$_POST['id']);
			  $questObj->del($t,'djid='.$_POST['id']);
		}else
		echo json_encode(array('result'=>false,'msg'=>'保存失败'));
		break;
		}
			 
	}
	function pfrkAction()
	{
		include_once(APP_PATH.'/application/models/dj/dao/djDAO.class.php');
		$dao = new djDAO();
		$dao->updatePf(array(
							'wjid'=>$_POST['wjid'],
							'djid'=>$_POST['djid'],
							'it_df'=>$_POST['arrPostDf']
						));
		$this->redirect($_POST['referer']);
	}
	public function wapAction($wjid)
	{	
		if(func::checkmobile())
		{
			$Loaction = SITE_ROOT.'Wap/start/?wjid='.$wjid;
		
			if (!empty($Loaction))
			{
				header("Location: $Loaction");
		
				exit;
			}
		
		}
	}
}
?>