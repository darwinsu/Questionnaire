<?php
include_once(APP_PATH . '/application/models/dao.class.php');
include_once(APP_PATH . '/application/models/report/domain/report.class.php');
class reportDAO extends dao
{
    public function queryReport($wjid,$uid)
    {
        $sql = 'select wjid,
            wj_title_id wj_subject_id,
            wj_title_item_id wj_subject_item_id,
            count(wj_title_item_id) wj_subject_item_count
             from t_dj_item where wjid = ?
              group by wj_title_item_id';

        return $this->crud->L($sql,array($wjid));
    }

    public function saveReport($report)
    {	$rep_array=array();
		if($report)
		foreach($report as $k=>$v){
			if($v)$rep_array[$k]=$v;
		}
			return $this->crud->C('t_report',$rep_array);
    }

    public function getReportByWjid($wjid)
    {
        $report = new report();

        $sql = 'select * from t_report where wjid=?';
        $arrReport = $this->crud->L($sql,array($wjid));
        $report->setReports($arrReport);

        $sql = 'select count(djid) djid from t_dj where wjid=? and status=1';
        $reportcount = $this->crud->L($sql,array($wjid));
        if(!empty($reportcount))
        {
            $report->setReportCount($reportcount[0][djid]);
        }
        else
        {
            $report->setReportCount(0);
        }
        return $report;
    }

    public function delReportByWjid($wjid)
    {
        $sql = 'DELETE FROM t_report WHERE wjid = ?';
        return $this->crud->E($sql,array($wjid));
    }

    public function reportOrEquals($wjid,$subjectid,$arrItemsid)
    {
        $sql = 'SELECT * FROM t_dj_item WHERE wjid = ? AND wj_title_id = ? AND wj_title_item_id REGEXP \''.implode('|',$arrItemsid).'\' ';//group by djid
        return $this->crud->L($sql,array($wjid,$subjectid));
    }

    public function reportAndEquals($wjid,$subjectid,$arrItemsid)
    {
        $sql = 'SELECT * FROM t_dj_item WHERE wjid = ? AND wj_title_id = ? AND wj_title_item_id IN ('.implode(',',$arrItemsid).')';// group by djid
        return $this->crud->L($sql,array($wjid,$subjectid));
    }

    public function reportOrUnequals($wjid,$subjectid,$arrItemsid)
    {
        $sql = 'SELECT * FROM t_dj_item WHERE wjid = ? AND wj_title_id = ? AND wj_title_item_id REGEXP \'[^('.implode('|',$arrItemsid).')]\'';// group by djid
        return $this->crud->L($sql,array($wjid,$subjectid));
    }

    public function reportAndUnequals($wjid,$subjectid,$arrItemsid)
    {
        $sql = 'SELECT * FROM t_dj_item WHERE wjid = ? AND wj_title_id = ? AND wj_title_item_id NOT IN ('.implode(',',$arrItemsid).') ';
        //echo $sql;exit;
        return $this->crud->L($sql,array($wjid,$subjectid));
    }
}
