<?php
include_once(APP_PATH . '/application/models/role/dao/roleDAO.class.php');
class roleService
{
    public $roleDAO;

    public function __construct()
    {
        $this->roleDAO = new roleDAO();

    }

    public function getRoleByRID($rid)
    {
        return $this->roleDAO->getRoleByRID($rid);
    }

	public function getRoleByAuth($rid)
	{
        return $this->roleDAO->getRoleByAuth($rid);
    }
}
?>