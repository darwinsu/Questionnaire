<?php
class RoleController extends Yaf_Controller_Abstract
{

    public $roleService;

    public function init()
    {
        include_once(APP_PATH . '/application/models/role/service/roleService.class.php');
        $this->roleService = new roleService();
    }

    public function indexAction()
    {
        $role = $this->roleService->getRoleByRID(15);
        var_export($role->isAuth('report#cross#sel'));

        $this->getView()->assign("content", "Hello");
		$this->display('index');
    }

}
?>