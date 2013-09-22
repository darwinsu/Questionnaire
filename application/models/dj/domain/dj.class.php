<?php
class dj
{

    public $djid;
    public $uid;
    public $wjid;
    public $dj_start_time;
    public $dj_over_time;
    public $dj_time_consuming;
	public $dj_anonymous;
	public $dj_zf;        //´ð¾í×Ü·Ö
	public $dj_name;        //´ð¾íÈË

    private $arrDjItemObject;
    private $objWj;
	public function setDjName($name)
    {
        $this->dj_name = $name;
    }

    public function getDjName()
    {
        return $this->dj_name;
    }
    public function setObjWj($objWj)
    {
        $this->objWj = $objWj;
    }

    public function getObjWj()
    {
        return $this->objWj;
    }

    public function setDjOverTime($dj_over_time)
    {
        $this->dj_over_time = $dj_over_time;
    }

    public function getDjOverTime()
    {
        return $this->dj_over_time;
    }

    public function setDjStartTime($dj_start_time)
    {
        $this->dj_start_time = $dj_start_time;
    }

    public function getDjStartTime()
    {
        return $this->dj_start_time;
    }

    public function setDjTimeConsuming($dj_time_consuming)
    {
        $this->dj_time_consuming = $dj_time_consuming;
    }

    public function getDjTimeConsuming()
    {
        return $this->dj_time_consuming;
    }

    public function setDjid($djid)
    {
        $this->djid = $djid;
    }

    public function getDjid()
    {
        return $this->djid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setWjid($wjid)
    {
        $this->wjid = $wjid;
    }

    public function getWjid()
    {
        return $this->wjid;
    }

    public function setArrDjItemObject($arrDjItemObject)
    {
        $this->arrDjItemObject = $arrDjItemObject;
    }

    public function getArrDjItemObject()
    {
        return $this->arrDjItemObject;
    }



}
?>