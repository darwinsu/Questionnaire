<?php
include_once(APP_PATH . '/application/models/dao.class.php');
include_once(APP_PATH . '/application/models/dj/domain/dj.class.php');
class djDAO extends dao
{

    public function getDjListPage($page=1,$pageSize=10)
    {
        $sql = 'select * from t_dj order by dj_start_time desc';
        $arrRetDj = $this->crud->LP($sql,null ,$page,$pageSize);
        if(!empty($arrRetDj))
        {
            $djObj = new dj();
            foreach ($arrRetDj as $dj)
            {
                $djObj->setDjid($dj['djid']);
                $djObj->setUid($dj['uid']);
                $djObj->setWjid($dj['wjid']);
                $djObj->setDjStartTime($dj['dj_start_time']);
                $djObj->setDjOverTime($dj['dj_over_time']);
                $djObj->setDjTimeConsuming($dj['dj_time_consuming']);

                $arrDjObj[] = $djObj;
            }
            return $arrDjObj;
        }
        return null;
    }

    public function getMyDjListPage($uid,$page=1,$pageSize=10)
    {
        $sql = 'select * from t_dj where uid = ? order by dj_start_time desc';
        $arrRetDj = $this->crud->LP($sql,array($uid) ,$page,$pageSize);
        if(!empty($arrRetDj))
        {
            foreach ($arrRetDj as $dj)
            {
                $djObj = new dj();
                $djObj->setDjid($dj['djid']);
                $djObj->setUid($dj['uid']);
                $djObj->setWjid($dj['wjid']);
                $djObj->setDjStartTime($dj['dj_start_time']);
                $djObj->setDjOverTime($dj['dj_over_time']);
                $djObj->setDjTimeConsuming($dj['dj_time_consuming']);

                $arrDjObj[] = $djObj;
            }
            return $arrDjObj;
        }
        return null;
    }

    public function delDj($djid)
    {
        return $this->crud->D('t_dj','djid',$djid);
    }

    public function getDjByWjidUid($wjid,$uid)
    {
        $sql = 'select * from t_dj where wjid=? and uid=? and status=1';
        $arrDj = $this->crud->L($sql,array($wjid,$uid));
        if(!empty($arrDj))
        {
            foreach($arrDj as $dj)
            {
                $djObj = new dj();
                $djObj->setDjid($dj['djid']);
                $djObj->setUid($dj['uid']);
                $djObj->setWjid($dj['wjid']);
                $djObj->setDjStartTime($dj['dj_start_time']);
                $djObj->setDjOverTime($dj['dj_over_time']);
                $djObj->setDjTimeConsuming($dj['dj_time_consuming']);

                $arrDjObj[] = $djObj;
            }
            return $arrDjObj;
        }
        return null;
    }

    public function createDj($wjid,$uid)
    {
        return $this->crud->C('t_dj',array('wjid'=>$wjid,'uid'=>$uid,'unitid'=>cookie::get('unitid')));
    }

    public function setDjOverTime($djid,$ttime=0)
    {
		$dj_start_time=date('Y-m-d H:i:s', $_POST['startTime']);
		if($ttime){
		$dj_over_time=date('Y-m-d H:i:s', $_POST['startTime']+$ttime);
		$dj_time_consuming=$ttime;
		}else{
		$dj_over_time=date('Y-m-d H:i:s');
		$dj_time_consuming=time()-($_POST['startTime']/1);
        
		}
		$sql = 'UPDATE
                  t_dj
                SET
				  dj_start_time = ?,
                  dj_over_time = ?,
                  dj_time_consuming = ?
                WHERE
                  djid = ?';
        return $this->crud->E($sql,array($dj_start_time,$dj_over_time,$dj_time_consuming,$djid));
    }
	
	public function setDjAnonymous($param)
	{
		$sql="UPDATE
                  t_dj
                SET
                  is_anonymous = ?
                WHERE
                  djid = ?";
		return $this->crud->E($sql,array($param['is_anonymous'],$param['djid']));
	}

    public function saveDjItem($wjid,$djid,$arrDjItems)
    {
        if(!empty($arrDjItems))
        {
            foreach($arrDjItems as $key => $item)
            {
                $item['djid'] = $djid;
                $item['wjid'] = $wjid;
                if(is_array($item['wj_title_item_id']))
                {
                    $it = $item;
                    for ($i=0;$i<count($item['wj_title_item_id']);$i++)
                    {
                        //$it['wj_title_item_id'] = $itm;
						$tmparr=$it;
						$tmparr['dj_additional'] = $item['dj_additional'][$i];
						$tmparr['wj_title_item_id'] = $item['wj_title_item_id'][$i];
						
                        $djitemids[$key] = $this->crud->C('t_dj_item',$tmparr);
                    }
                }
                else
                {
                    $djitemids[$key] = $this->crud->C('t_dj_item',$item);
                }
            }
            return $djitemids;
        }
        return -1;
    }

    public function djItemAssert($djitem,$score)
    {
        return $this->crud->U('t_dj_item',array('dj_score'=>$score),'djitemid',$djitem);
    }

    public function getDj($djid)
    {
        $dj = $this->crud->R('t_dj','djid',$djid);
        $objDj = new Dj();
        $objDj->setDjid($dj['djid']);
        $objDj->setUid($dj['uid']);
		$uname = $this->crud->R('t_members','id',$dj['uid']);
		$objDj->setDjName($uname['username']);
        $objDj->setWjid($dj['wjid']);
        $objDj->setDjStartTime($dj['dj_start_time']);
        $objDj->setDjOverTime($dj['dj_over_time']);
        $objDj->setDjTimeConsuming($dj['dj_time_consuming']);
		$objDj->dj_anonymous=$dj['is_anonymous'];
		$objDj->dj_zf=$dj['dj_zf'];

        $djItemDao = new djItemDAO();
        $objDj->setArrDjItemObject($djItemDao->getObjDjItems($djid));

        return $objDj;
    }

	/**
	* 更新答卷的分数
	* @param $param=array('wjid'=>'问卷ID','djid'=>'答卷ID','it_df'=>array('题目ID'=>'得分'))
	*/
	public function updatePf($param)
	{
		$zf=0;
		if(is_array($param['it_df']))
		{
			foreach($param['it_df'] as $k=>$v)
			{
				
				$sql="update t_dj_item set df=? where djid=? and wj_title_id=?";
				$this->crud->E($sql,array($v,$param['djid'],$k));
				$zf+=$v/1;
			}
		}

		//排名处理
		//首先获取比该比分大的最大排名
		$sql="select max(dj_pm) as max_pm from t_dj where wjid=? and dj_zf>=? and djid!=?";
		$tmparr = $this->crud->L($sql,array($param['wjid'],$zf,$param['djid']));
		$max_pm=($tmparr[0]['max_pm']/1)+1;

		//$sql="update t_dj set dj_zf=".$zf.",dj_pm=".$max_pm." where djid=".$param['djid'];
		$sql="update t_dj set dj_zf=?,dj_pm=? where djid=?";
		$this->crud->E($sql,array($zf,$max_pm,$param['djid']));


		$sql="select djid from t_dj where wjid=? and dj_zf<? and djid!=? and dj_zf!=-1 order by dj_zf desc,djid asc";
		$tmparr = $this->crud->L($sql,array($param['wjid'],$zf,$param['djid']));
		$n=count($tmparr);
		for($i=0;$i<$n;$i++)
		{
			$sql="update t_dj set dj_pm=? where djid=?";
			$this->crud->E($sql,array(($max_pm+$i+1),$tmparr[$i]['djid']));
		}
		
	}
}
?>