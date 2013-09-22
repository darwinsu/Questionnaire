<?php
include_once(APP_PATH . '/application/models/dj/dao/djItemDAO.class.php');
class djItemService
{
    public $djItemDAO;
    public $pageInfo;

    public function __construct()
    {
        $this->djItemDAO = new djItemDAO();
    }

    public function setDjItemScore($djitemid,$score)
    {
        return $this->djItemDAO->setDjItemScore($djitemid,$score);
    }

    public function delDjItemByDjid($djid)
    {
        return $this->djItemDAO->delDjItemByDjid($djid);
    }
}
?>