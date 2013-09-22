<?php

class WapController extends Yaf_Controller_Abstract {
	private $mDate;
	private $mI;
	public function init() {
        include_once ('application/library/func.php');
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
	}

	public function indexAction()
	{
		if(cookie::get('isLogin')!='1')
		{
			$this->getResponse()->setRedirect(SITE_ROOT."IndexWap/");
			exit;
		}
		$tmp['t']=1;
		$tmp['status']=1;
		if($_POST['is_zd'])$tmp['zd']=$_POST['is_zd'];else $tmp['zd']=0;//未做答
		if($_POST['q_title']&&$_POST['q_title']!='搜索问卷') $tmp['q_title'] = $_POST['q_title'];else $_POST['q_title']='搜索问卷';
		/*$quest_res=$this->getDataAction($tmp);
		//列表HTML
		$tr='';
		if($quest_res['result']){
			foreach($quest_res['data'] as $k=>$v){
			$tr.=$this->divtime($v['q_start']);
			$tr.=''.$this->li($v).'';
			//$tr.=$this->li($v);
			}
		} else{
			$tr.= '<div><span>抱歉，没有找到相关问卷.....</span></div>';
		}
		
		if($quest_res['zyy']>1) $tr.= '<div id="list1"><span id="show2" onClick="lists(2)">更多......</span></div>';
		$this->_view->assign('tr', $tr);
		*/
		$this->_view->assign('post', $_POST);
		$this->display('index');
	}
	public function selAction()
	{
		if(cookie::get('uap_islogin')=='1' && cookie::get('ly_islogin')=='1' )
		{
			$this->getResponse()->setRedirect(SITE_ROOT."IndexWap/");
			exit;
		}
		$tmp=$_GET;
		$tmp['t']=1;
		$tmp['status']=1;
		$quest_res=$this->getDataAction($tmp);
		//列表HTML
		$tr='<div id="list1">';
		if($quest_res['result']){
			foreach($quest_res['data'] as $k=>$v){
			$tr.=$this->divtime($v['c_time']);
			$tr.=''.$this->li($v).'';
			//$tr.=$this->li($v);
			}
		}else{
			$tr.= '<div><span>抱歉，没有找到相关问卷.....</span></div>';
		}
		$tr.="</div>";
		if($quest_res['zyy']>$tmp['pageno'])echo $tr.'<script>scrolli='.($tmp['pageno']+1).';</script>';else echo $tr.'<script>scrolli=0;</script>';
	}
	public function thanksAction()
	{
		$this->display('thanks');
	}

	function divtime($time){
		$sdate='';
		 if(date("Y-m-d",$time)!=$this->mDate){
			 if($this->mI){
			$sdate='
	</ul></div><div class="cion_1"><div class="top_time">'.date("Y-m-d",$time).'</div><ul  class="cion_ul">';
			 }else{
			 $sdate='<div class="cion_1"><div class="top_time">'.date("Y-m-d",$time).'</div><ul  class="cion_ul">';
			 }
			$this->mI=1;
			$this->mDate=date("Y-m-d",$time);
		}
		return  $sdate;
	}
	function li($arr){
		$func=new func();
		//是否答卷
		if($arr['dj_num']){
		$dj='<span class="span_2">已回答</span>';
		}else{
		$dj='<span class="span_2">未回答</span>';
		}
		if($arr['dj_num']){$url=SITE_ROOT.'/Wap/info/djid/'.$arr['djid'].'/';}else{ $url=SITE_ROOT.'/Wap/start/?wjid='.$arr['id'];}
		return $str='<li><a href="'.$url.'" class="clearfix" onfocus="this.blur()">
				<p class="cion_p1"> <span class="span_3">'.$arr['fk_type_name'].'</span> <span class="span_1">'.$arr['q_title'].'</span></p>'.$dj.'
				<p class="cion_p2">
					起止时间:
					<time pubdate="true">'.date("Y-m-d",$arr['q_start']).'至'.date("Y-m-d",$arr['q_end']).'</time>
				</p></a>
			</li>';
	}
	
//###########################答卷######################
	public function startAction()
	{	
        include_once(APP_PATH . '/application/models/dj/service/djService.class.php');
        $this->djService = new djService();
		$uid = cookie::get('userid');
        $wjid = $_GET['wjid'];
        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($wjid);
		//判断是否重复答卷
		if(!$_GET['dj_no']){
		$wj_all=$wj->getAlldata();
		if($wj_all[0]['status']!='1'||strtotime("now")>($wj_all[0]['q_end']+(3600*24))){
			echo '<script language="javascript">alert("该问卷已关闭作答");location.href="'.SITE_ROOT.'Wap";</script>';
			exit;
		}
		if($wj_all[0]['q_start']>strtotime("now")){
			echo '<script language="javascript">alert("该问卷未开始");location.href="'.SITE_ROOT.'Wap";</script>';
			exit;
		}
		if(!$wj_all[0]['q_repeat']&&$uid>0){
			if($this->djService->isDj($wjid,$uid)){
			echo '<script language="javascript">alert("您已经回答过该问卷");location.href="'.SITE_ROOT.'Wap";</script>';
			exit;
			}
		}
		if($wj_all[0]['q_login']&&!$wj_all[0]['q_anonymous']){
			if(cookie::get('isLogin')!=1){
					$url = SITE_ROOT.$_SERVER['REQUEST_URI'];
					echo '<script language="javascript">alert("需登录作答");location.href="'.SITE_ROOT.'Indexwap/index?referer='.$url.'";</script>';
					exit;
				}
		}
		
		
		if($wj_all[0]['pass_type']){
			$post = $this->getRequest()->getPost();
			if(!$post['passdj']){			
			$this->_view->assign('passMsg',$passMsg);
			$this->_view->assign('wjid',$wjid);
			$this->display('pass');exit;
			}
			else{
				if($post['passdj']!=$wj_all[0]['pass']){
				echo $passMsg='<script language="javascript">alert("密码错误");location.href="'.SITE_ROOT.'Wap";</script>';	
				exit;			
				}
			}
		}
		}
        if($djid == -1)
        {
            $this->redirect(SITE_ROOT.'Wap/');
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
		$this->_view->assign('dj_no',$_GET['dj_no']);
		$this->_view->assign('wjid',$wjid);
		$this->_view->assign('passMsg',$passMsg);
        $this->_view->assign('wj',$wj);
        $this->_view->assign('djid',$djid);
        $this->_view->assign('subjects',$subjects);
		$this->display('start');
	}
//###################答卷数据提交#####################	
	public function submitAction()
    {
		include_once(APP_PATH . '/application/models/dj/service/djService.class.php');
        $this->djService = new djService();
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
		//$this->redirect(SITE_ROOT.'Wap/thanks/');
		header("location:".SITE_ROOT."Wap/thanks/");
    }
//###################数据获取#####################
	public function listAction(){
		$tmp=$_GET;
		if($tmp['q_title']&&$tmp['q_title']!='搜索问卷') {}else {unset($tmp['q_title']);}
		$tmp['t']=1;
		$tmp['status']=1;	
		$quest_res=$this->getDataAction($tmp);
		//列表HTML
		$tr='';
		if($quest_res['result']){
			foreach($quest_res['data'] as $k=>$v){
			$tr.=$this->divtime($v['c_time']);
			$tr.=''.$this->li($v).'';
			}
		}else{
			$tr.= '<div><span>抱歉，没有找到相关问卷.....</span></div>';
		}
		//if($quest_res['zyy']>$tmp['pageno'])echo $tr.'<span id="show'.($tmp['pageno']+1).'" onClick="lists('.($tmp['pageno']+1).')">更多......</span>';else echo $tr;
		if($quest_res['zyy']>$tmp['pageno'])echo $tr.'<script>scrolli='.($tmp['pageno']+1).';</script>';else echo $tr.'<script>scrolli=0;</script>';
		
	}
	public function getDataAction($temp){	 
		$questObj=new questModel();
		$conditions_url="";
		unset($fields);
		switch($temp['t']){
			case 1:
				$t='t_quest';
				$fields=" id,`fk_sytle_id`,`fk_type_id`,(select name from t_quest_type b where b.id=t_quest.fk_type_id) as fk_type_name,`q_title`,`q_top_desc`,`q_foot_desc`,`q_start`,`q_end`,`duration`,`status`,`pass`,`q_repeat`,`q_anonymous`,`q_login`,`q_all`,`c_userid`,`c_time`,'".strtotime("now")."' as nowtime,(select username from t_members where t_members.id=t_quest.c_userid) as c_user,(select count(1) from t_dj a where a.wjid=t_quest.id and a.uid='".cookie::get('userid')."') dj_num,(select djid from t_dj a where a.wjid=t_quest.id and a.uid='".cookie::get('userid')."' limit 0,1) djid,concat('".SITE_ROOT."Dj/start/wjid/',id) as djurl";
				//$orders=$temp['orders']?$temp['orders']:'q_start desc';
			break;
			case 2:
				$t='t_quest_subject';
				$orders=array(' order by s_order');
			break;
			case 3:
				$t='t_subjects';
				$orders=array(' order by s_order');
			break;
		}
		$conditions="1 and unitid='".cookie::get('unitid')."'";
		$val_arr=array();
		$item_arr=array();
		foreach($temp as $key=>$val){
			if($key!='t'&&$key!='pageno'&&$key!='orders'&&$key!='v'&&$key!='state'&&$key!='perpages'){
				if($key=='q_title'){
				$conditions.=" and ".$key." like '%".$val."%'";
				}elseif($key=='zd'){
					if($val==1){
					$conditions.=" and exists(select 1 from t_dj a where a.wjid=t_quest.id and a.uid='".cookie::get('userid')."')";
					}else{
					$conditions.=" and q_end>='".strtotime("now")."'";	
					$conditions.=" and  not exists (select 1 from t_dj a where a.wjid=t_quest.id and a.uid='".cookie::get('userid')."')";
					}
				}else{
				$conditions.=" and ".$key." = '".$val."'";	
				}
				$conditions_url.="&".$key."=".$val;
				array_push($val_arr,$val);
				array_push($item_arr,$key);
			}
		}
		  
		$rt=$questObj->getSytleCount($t,$conditions);
		$pmCount=$rt;
		$perpage=($temp['perpages'])?$temp['perpages']:10;
		$pageno=(isset($temp['pageno']))?$temp['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		$yy=ceil($rt/$perpage);
		$tmparr = $questObj->get_data(array('start'=>$start,'limit'=>$perpage,'fields'=>$fields,'table'=>$t,'conditions'=>$conditions,'orders'=>$orders));
		$menu=func::get_page($pageno,$perpage,$pmCount,Yaf_Registry::get("config")->common->get('webroot').'Quest/getData/?t='.$temp['t'].$conditions_url,'1',$state?'del':'');
		if($rt){
			if($val_arr){
				return (array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno,'conditions_item'=>$conditions,'conditions_val'=>$val_arr,'zyy'=>$yy));
		}else{
				return (array('result'=>true,'data'=>$tmparr,'menu'=>$menu,'pageno'=>$pageno,'zyy'=>$yy));
		}
		}else{
				return (array('result'=>false,'data'=>'','menu'=>$menu));
		}
	}
##############################答卷查询######################################
	public function infoAction()
    {	
		
		$this->getView()->assign('userlist',explode(',',cookie::get('userlist')));
		$this->getView()->assign('jsbhlist',explode(',',cookie::get('jsbhlist')));
		include_once(APP_PATH . '/application/models/dj/service/djService.class.php');
        $djid = $this->getRequest()->getParam('djid');
		$this->djService = new djService();
		$objDj = $this->djService->getDj($djid);
//print_r($objDj);
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
		$this->display('info');
    }	
}
