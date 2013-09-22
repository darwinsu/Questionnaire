<?php
class baseControl extends Yaf_Controller_Abstract {
	public function init()
	{
		$this->_view->assign('controller', $this->getRequest()->controller);
		$this->_view->assign('action', $this->getRequest()->action);
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
		
		if($this->getRequest()->controller!='Index')
		{
			if($_COOKIE['sm_uap_islogin']!='1' || $_COOKIE['sm_ly_islogin']!='1')
			{
				header("Location:".Yaf_Registry::get("config")->common->get('webroot')."Index/");
				exit;
			}
		}
	}
}
?>