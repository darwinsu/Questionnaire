<?php

class MainController extends Yaf_Controller_Abstract {
	public function init() {
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
	}

	public function indexAction()
	{
		$this->_view->assign('username',cookie::get('username'));
		$this->_view->assign('password',cookie::get('password'));
		$this->_view->assign('utype',cookie::get('utype'));
		$partMdl=new partModel();
		$rights=$partMdl->PartListValidate();
		$this->_view->assign('rights',$rights);
		$this->display('index');
	}
	public function userlistAction()
	{
		$this->_view->assign('username',cookie::get('username'));
		$this->_view->assign('uid',cookie::get('uid'));
		$this->_view->assign('isa',cookie::get('isadmin'));
		$this->display('userlist');
	}
}
