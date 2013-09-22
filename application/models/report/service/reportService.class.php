<?php
include_once(APP_PATH . '/application/models/report/dao/reportDAO.class.php');
class reportService
{
    public $reportDAO;

    public function __construct()
    {
        $this->reportDAO = new reportDAO();
    }

    public function report($wjid,$uid)
    {
        $retReport = $this->reportDAO->queryReport($wjid,$uid);

        if(!empty($retReport))
        {
            foreach ($retReport as $report)
            {
                $report['uid'] = $uid;
				$report['unitid'] =cookie::get('unitid'); //print_r($report);exit;
                $this->reportDAO->saveReport($report);
            }
            return true;
        }
        return null;
    }

    public function isReport($wjid)
    {
        $ret = $this->reportDAO->getReportByWjid($wjid);
        $ret = $ret->getReports();
        if(empty($ret))
        {
            return false;
        }
        return true;
    }

    public function delReportByWjid($wjid)
    {
        return $this->reportDAO->delReportByWjid($wjid);
    }

    public function resetReport($wjid,$uid)
    {
        $this->delReportByWjid($wjid);
        return $this->report($wjid,$uid);
    }

    public function getReportByWjid($wjid)
    {
        return $this->reportDAO->getReportByWjid($wjid);
    }

    public function isEquals($wjid,$subjectid,$arrItemsid,$condition2='isOr')
    {
        if($condition2=='isOr')
        {
            return $this->reportDAO->reportOrEquals($wjid,$subjectid,$arrItemsid);
        }
        return $this->reportDAO->reportAndEquals($wjid,$subjectid,$arrItemsid);
    }

    public function isUnequals($wjid,$subjectid,$arrItemsid,$condition2='isOr')
    {
        if($condition2=='isOr')
        {
            return $this->reportDAO->reportOrUnequals($wjid,$subjectid,$arrItemsid);
        }
        return $this->reportDAO->reportAndUnequals($wjid,$subjectid,$arrItemsid);
    }
}
?>