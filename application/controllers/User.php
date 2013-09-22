<?php
class UserController extends Yaf_Controller_Abstract {
	public $mTitle='用户管理';
	public function init() {
		$this->_view->assign(Yaf_Registry::get("config")->common->toArray());
		$this->_view->assign('title',$this->mTitle);
	}

	public function indexAction()
	{
		$this->getData();
		$this->display('index');
	}
	
	
	
	public function getData()
	{
		$userObj=new userModel();
		//$arrWhere['username']=$_POST['where_array']['username'];
		//$arrWhere['realname']=$_POST['where_array']['realname'];
		$arrWhere['utype']=$_POST['where_array']['utype'];
		$arrWhere['unitid']="'".cookie::get('unitid')."'";
		$count=$userObj->getCount($arrWhere);
		$perpage=50;
		$pageno=(isset($_POST['pageno']))?$_POST['pageno']:($_GET['pageno']!=''?$_GET['pageno']:'1');
		$start=($pageno-1)*$perpage;
		$mutil=func::getPage($count,Yaf_Registry::get("config")->common->get('perpage'),$start,$limit);
		$arrWhere['start']=$start;
		$arrWhere['limit']=$limit;
		$datalist=$userObj->getList($arrWhere);
		$this->_view->assign('datalist',$datalist);
		$this->_view->assign('mutil',$mutil);
	}
	
	public function getListAction()
	{	
		$userObj=new userModel();
		$arrWhere['username']=$_POST['where_array']['username'];
		$count=$userObj->getCount($arrWhere);	
		func::getPage($count,Yaf_Registry::get("config")->common->get('perpage'),$start,$limit);
		$this->display('list');
	}
}
