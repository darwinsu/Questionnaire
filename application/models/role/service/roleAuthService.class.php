<?php

class roleAuthService
{
    public $roleAuthDAO;

    public function __construct()
    {
        include_once(APP_PATH . '/application/models/role/dao/roleAuthDAO.class.php');
        $this->roleAuthDAO = new roleAuthDAO();
    }

}
