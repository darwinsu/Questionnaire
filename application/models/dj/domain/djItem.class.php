<?php
class djItem
{

    public $djitemid;
    public $djid;
    public $wj_title_id;
    public $wj_title_item_id;
    public $dj_answer;
    public $dj_score;
    public $dj_additional;
	public $df;

    public function setDjAdditional($dj_additional)
    {
        $this->dj_additional = $dj_additional;
    }

    public function getDjAdditional()
    {
        return $this->dj_additional;
    }

    public function setDjAnswer($dj_answer)
    {
        $this->dj_answer = $dj_answer;
    }

    public function getDjAnswer()
    {
        return $this->dj_answer;
    }

    public function setDjScore($dj_score)
    {
        $this->dj_score = $dj_score;
    }

    public function getDjScore()
    {
        return $this->dj_score;
    }

    public function setDjid($djid)
    {
        $this->djid = $djid;
    }

    public function getDjid()
    {
        return $this->djid;
    }

    public function setDjitemid($djitemid)
    {
        $this->djitemid = $djitemid;
    }

    public function getDjitemid()
    {
        return $this->djitemid;
    }

    public function setWjTitleId($wj_title_id)
    {
        $this->wj_title_id = $wj_title_id;
    }

    public function getWjTitleId()
    {
        return $this->wj_title_id;
    }

    public function setWjTitleItemId($wj_title_item_id)
    {
        $this->wj_title_item_id = $wj_title_item_id;
    }

    public function getWjTitleItemId()
    {
        return $this->wj_title_item_id;
    }

}
?>