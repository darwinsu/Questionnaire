<?php
class report
{
    public $reports;
    public $reportCount;

    public function setReports($reports)
    {
        $this->reports = $reports;
    }

    public function getReports()
    {
        return $this->reports;
    }

    public function getReportItemCountByItemid($itemid)
    {
        if(!empty($this->reports))
        {
            foreach ($this->reports as $rep) {
                if($rep['wj_subject_item_id'] == $itemid)
                {
                    return $rep['wj_subject_item_count'];
                }
            }

        }
        return 0;
    }

    public function setReportCount($reportCount)
    {
        $this->reportCount = $reportCount;
    }

    public function getReportCount()
    {
        return $this->reportCount;
    }

}
?>
