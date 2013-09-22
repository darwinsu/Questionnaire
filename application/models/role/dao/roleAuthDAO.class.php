<?php
include_once(APP_PATH . '/application/models/dao.class.php');
class roleAuthDAO extends dao
{
    public function getRoleAuthByRID($partid)
    {
		$js1="report#convention#sel,report#convention#edit,report#convention#expExcel,report#condition#sel,report#cross#sel,dj#list#sel,dj#list#add,dj#my#sel,system#user#edit,system#rights#sel,system#rights#add,system#rights#edit,system#rights#rights,quest#style#sel,quest#style#add,quest#style#edit,quest#style#del,quest#quest#sel,quest#quest#add,quest#quest#edit,quest#quest#del,quest#subject#sel,quest#subject#add,quest#subject#edit,quest#subject#del,quest#draft#sel,quest#draft#add,quest#draft#edit,quest#draft#del,quest#draft#send,quest#recycle#sel,quest#recycle#del,quest#recycle#send";
		$js2="dj#list#add,dj#my#sel,quest#style#del,quest#quest#sel,quest#quest#add,quest#quest#edit,quest#quest#del,quest#subject#sel,quest#subject#add,quest#subject#edit,quest#subject#del,quest#draft#sel,quest#draft#add,quest#draft#edit,quest#draft#del,quest#draft#send,quest#recycle#sel,quest#recycle#send,quest#recycle#del,dj#list#sel";
		$js3="quest#quest#sel,dj#list#sel,dj#my#sel,report#convention#sel,report#condition#sel,report#cross#sel";
		$js4="dj#list#sel,dj#list#add,dj#my#sel";
		switch($partid)
		{
			case 1:
				$auth = $js1;
				break;
			case 2:
				$auth = $js2;
				break;
			case 3:
				$auth = $js4;
				break;
		}
		$auth = explode(',', $auth);
		foreach($auth as $k => $v)
		{
			$auth[$k] = array('auth_code'=>$v);
		}
		
        return $auth;
    }
}
