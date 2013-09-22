<?php
include_once(APP_PATH . '/application/models/dao.class.php');
include_once(APP_PATH . '/application/models/role/dao/roleAuthDAO.class.php');
include_once(APP_PATH . '/application/models/role/domain/role.class.php');
class roleDAO extends dao
{
    private $_roleAuthDAO;

    public function __construct()
    {
        $this->_roleAuthDAO = new roleAuthDAO();
        parent::__construct();
    }

    public function getRoleByRID($rid)
    {
        $retRole = $this->crud->R('t_part','id',$rid);
        $retRoleAuth = $this->_roleAuthDAO->getRoleAuthByRID($rid);

        $role = new role();
        $role->setLongRID($retRole['id']);
        $role->setStrRoleName($retRole['pname']);
        $role->setArrAuth($retRoleAuth);

        return $role;
    }
	public function getRoleByAuth($rid)
    {
		unset($rlist);
		$res=$this->_roleAuthDAO->getRoleAuthByRID($rid);
		if($res){
			foreach($res as $k=>$v){
				$rlist[]=$v['auth_code'];			
			}
		}
		return $rlist;
	}
}
?>
