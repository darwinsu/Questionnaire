<?php

$GLOBALS['file'] = dirname(__FILE__).'/log/'.date('Y-m-d').'.txt';  //日志文件名
$GLOBALS['debug']=0;  //调试标志, 等于1时打印接口数据

/*
 | OAP 增量信息同步
 +--------------------------
 | 同步单位、部门、用户信息
 */
class sync {
	private $server = 'http://oaps.91.com/';
	private $id = '131';
	private $key = 'b3d2f7cf-00a6-4ff0-a1ce-efa9ad509739';
	private $sid = '';
	private $pre = 't_'; // 表前缀，根据实际情况编写
	private $mysql;
	private $unitid;
	private $updatetime;
	private $currentnitid;
	private $unitname;
	private static $memcache;

	function __construct() {				
		$GLOBALS['file'] = 'log/' ."MYSQL_". time ( 'Y-m-d' ) . '.txt';
		$this->mysql = new mysql ();
		$this->init ();
	}
	private function init($n=3) {
		$rs = $this->auth_applogin ();
		switch ($rs ['httpCode']) {
			case '200' :
				$this->sid = $rs ['data'] ['sid'];
				break;
			default :
				$error = array (
					'httpCode' => $rs ['httpCode'],
					'error' => '应用登录失败！退出。' 
				);
				$this->log ( $error );
				exit;
		}
	}
	
	/*
	 * 同步总处理函数
	 */
	function sync_all($n=3) {
		$input = "<br>开始同步....\r\n"; 
		$this->log ( $input );
		$this->unitid=0;
		$this->mysql->setUnitid($this->unitid);
		$sql = "select * from  " . $this->pre . "sync";
		$result = $this->mysql->fetch_array ( $sql );
		if (!empty($result)) 
		{
			$para = array ();
			$para ['updatetime'] = $result[0]['unit_dt'];
			
			$para ['start'] = 0;
			$para ['size'] = 50;
			while ( 1 )
			 {
				$units_result = $this->sync_units ( $para );
				$total = $units_result ['total'];
				
				//-----测试2013.5.2----------------------
				$input = "<br>新获得单位记录:".$total."\r\n";  
				$this->log ( $input );
				
				$this->logic_units_change($units_result['units']);//更新新的单位，以及删除取消授权的单位
				$max = $para['start']+ $para['size'];
				if($total){//如果存在数据
					//更新同步时间
					$this->up_date_sync_time();
				}
				if($total<=$max)
				{
					 if($this->updatetime===null)
					 {
						 $input = "没有需要更新的数据....";
						 $this->log ( $input );
						 echo $input;
					 }
				     break;
				}
				else
				{
					$para ['start'] = $max;
					sleep(1);
				}
			}
			$this->logic_unit_updates($result[0]['unit_dt']);
		}
		else 
		{
			$para = array ();
			$para ['updatetime'] = 0;
			$para ['start'] = 0;
			$para ['size'] = 50;

			while ( 1) 
			{
				$units_result = $this->sync_units ( $para );
				$total = $units_result ['total'];//返回的数据都要先加个data（因为本程序获取接口设计如此）
				
				//-----测试2013.5.2----------------------
				$input = "<br>新获得单位记录:".$total."\r\n";  
				$this->log ( $input );
				
				$this->logic_units_change($units_result['units']);//更新新的单位，以及删除取消授权的单位
 			    $max = $para['start']+ $para['size'];
				if($total){//更新同步时间
					$this->up_date_sync_time();
				}
				
 			    if($total<=$max)
 			    {
						//-----测试2013.5.2----------------------
						$input = "更改同步表:".$sql2;  
						$this->log ( $input );
 				        break;
 			    }
				else
 			    {
 			           $para ['start'] = $max;
 			           sleep(1);
 			    }	
		   }
	   }
	   $this->mysql->close();
	   $this->log ( "complete" );
	   //end
	}

	//初始化创建数据库
    private  function logic_unit_updates($updatetime,$n=3)//单位更新（包括更新单位资料，职员增加，信息修改，删除）
    {
		$para_updates = array ();
		$para_updates['updatetime'] = $updatetime;
		$para_updates['start'] = 0;
		$para_updates['size'] = 10;
		while(1)
		{
			$update_result=$this->unit_updates($para_updates);
			$update_total = $update_result['total'];
			foreach ( $update_result['data'] as $value )
			{
				$this->unitid = $this->currentnitid = $value['unit_id'];
				$this->mysql->setUnitid($this->unitid);
				$this->updatetime=$value['updatetime'];
				
				if($updatetime<$value['unit'])//更新单位资料
				{
					$update_unit_result=$this->unit_info($value['unit_id']);
					
					if($value['unit_id']!=$update_unit_result['unitid'])
					{
						$input = "在更新单位资料中出错，两个单位ID不相等，请检查OAP接口是否有问题,退出。";
						$this->log ( $input );
						exit ();
					}
					$sql="select count(*) as countt from ".$this->pre.'unitid'." where unitid=".$update_unit_result['unitid']." and unitname='".$update_unit_result['name']."'";
					$result_temp=$this->mysql->fetch_first($sql);
					if($result_temp==false)
					{
						$input = "查询语句".$sql.",（在sync.php logic_unit_updates函数 140行）出错，请检查程序或者数据库是否有问题";
						$this->log ( $input );
						//exit ();
						continue;
					}
					else if($result_temp['countt']!=1)
					{
						$sql="update " . $this->pre . 'unitid' . " set unitname='" .$update_unit_result['name']."' where unitid=".$update_unit_result['unitid'];
						$result_temp1=$this->mysql->execute($sql);
						if($result_temp1==false)
						{
							$input = "更新语句".$sql.",（在sync.php logic_unit_updates函数 150行）出错，请检查程序或者数据库是否有问题,退出。";
							$this->log ( $input );
							exit ();
						}
						$this->unitid =0;
						$this->mysql->setUnitid($this->unitid);
						$sql="update " . $this->pre . 'unitid' . " set unitname='" .$update_unit_result['name']."' where unitid=".$update_unit_result['unitid'];
						$result_temp2=$this->mysql->execute($sql);
						if($result_temp2==false)
						{
							$input = "更新语句".$sql.",（在sync.php logic_unit_updates函数 160行）出错，请检查程序或者数据库是否有问题,退出。";

							$this->unitid=$this->currentnitid;
							$this->mysql->setUnitid($this->unitid);
							$this->log ( $input );
							exit ();
						}
						$this->unitid=$this->currentnitid;
						$this->mysql->setUnitid($this->unitid);
					}
				} 
				
				if ($updatetime<$value['user']) //更新职员
				{
					$para_updates_user = array ();
					$para_updates_user['updatetime'] = $updatetime;
					$para_updates_user['start'] = 0;
					$para_updates_user['size'] = 50;
					$para_updates_user['unitid'] = $value['unit_id'];
					
					while(1)
					{
						$deptusers=$this->unit_deptusers($para_updates_user);
						$update_total_users = $deptusers['total'];
						if($update_total_users!=0)
						{
							$this->members_unit($deptusers['users']);
						}
						$max_users= $para_updates_user['start']+ $para_updates_user['size'];
						if($update_total_users<=$max_users)
						{
							break;
						}
						else
						{
							$para_updates_user['start'] = $max_users;
						}
					} 
				}
				if ($updatetime<$value['userrm']) //删除职员
				{
					$para_updates_userrm = array ();
					$para_updates_userrm['updatetime'] = $updatetime;
					$para_updates_userrm['start'] = 0;
					$para_updates_userrm['size'] = 50;
					$para_updates_userrm['unitid'] = $value['unit_id'];
					 
					while(1)
					{
						$delete_deptusers=$this->sync_userrm($para_updates_userrm);
						$update_total_userrm = $delete_deptusers['total'];
						if($update_total_userrm!=0)
						{
							$this->members_delete($delete_deptusers['data']);
						}
						$max_users= $para_updates_userrm['start']+ $para_updates_userrm['size'];
						if($update_total_userrm<=$max_users)
						{
							break;
						}
						else
						{
							$para_updates_userrm['start'] = $max_users;
						}
					}
				}
				if($updatetime<$value['dept'])//更新部门
				{
					$para_updates_dept = array ();
					$para_updates_dept['updatetime'] = $updatetime;
					$para_updates_dept['start'] = 0;
					$para_updates_dept['size'] = 50;
					$para_updates_dept['unitid'] = $value['unit_id'];
					
					while(1)
					{
						$depts=$this->sync_depts($para_updates_dept);
						$update_total_depts = $depts['total'];
						if($update_total_depts!=0){
							$this->depts_unit($depts['depts']);
						}
						
						$max_depts= $para_updates_dept['start']+ $para_updates_dept['size'];
						if($update_total_depts<=$max_depts)
						{
							break;
						}
						else
						{
							$para_updates_dept['start'] = $max_depts;
						}
					}
				}
				if($updatetime<$value['deptrm'])//删除部门
				{
					$para_updates_dept = array ();
					$para_updates_dept['updatetime'] = $updatetime;
					$para_updates_dept['start'] = 0;
					$para_updates_dept['size'] = 50;
					$para_updates_dept['unitid'] = $value['unit_id'];
					 
					while(1)
					{
						$delete_depts=$this->sync_deptrm($para_updates_dept);
						
						$update_total_deptrm = $delete_depts['total'];
						if($update_total_deptrm!=0)
						{
							$this->depts_delete($delete_depts['depts']);
						}
						$max_depts= $para_updates_dept['start']+ $para_updates_dept['size'];
						if($update_total_deptrm<=$max_depts)
						{
							break;
						}
						else
						{
							$para_updates_deptrm['start'] = $max_depts;
						}
					}
				
				}
			}
			$max_update= $para_updates['start']+ $para_updates['size'];
			if($update_total<=$max_update)
			{
				$this->unitid =0;
				$this->mysql->setUnitid($this->unitid);
				
				if($this->updatetime===null) break;  //没有更新数据，退出
				
				$sql2 = "update " . $this->pre . 'sync' . " set data_dt=" .$this->updatetime;  //更新时间戳
				$result_temp = $this->mysql->execute($sql2);
				if ($result_temp==false) 
				{
					$input = "更新语句".$sql2."（在sync.PHP 在第232行 ）出错，请检查程序或者数据库是否有问题,退出。";

					$this->unitid = $this->currentnitid;
					$this->mysql->setUnitid($this->unitid);
					$this->log ( $input );
					exit ();
				}
				
				$this->unitid = $this->currentnitid;
				$this->mysql->setUnitid($this->unitid);
				break;
			}
			else
			{
				$para_updates['start'] = $max_update;
			}
		}
    } 
	
		
	private function updatetime($n=3)//第一次同步更新时间
	{
		$sql = "update " . $this->pre . "unitid set statue=1,time=" . $this->updatetime." where unitid=".$this->currentnitid;
		$result_temp=$this->mysql->execute ( $sql );
		if ($result_temp==false) 
		{
			$input = "更新语句".$sql."（在sync.PHP 函数updatetime 第一个更新语句）出错，请检查程序或者数据库是否有问题,退出。";
			$this->log ( $input );
			exit ();
		}
		
		$this->unitid = 0; // 为了方便超级管理员管理，把所有机构统一到一个数据库的一个表中
		$this->mysql->setUnitid($this->unitid);
		$unitid = $this->currentnitid;
		$result = $this->mysql->fetch_first ( "SELECT count(*) as count FROM " . $this->pre . 'unitid' . " WHERE unitid='$unitid'" );
		if ($result ['count'] == 0) 
		{
			$data = array ();
			$data ['unitid'] = mysql_real_escape_string($this->currentnitid);
			$data ['statue'] = 1;
			$data ['unitname'] = mysql_real_escape_string($this->unitname);
			$data ['time'] = $this->updatetime;
				
			$sql = "REPLACE INTO ".$this->pre."unitid (`unitid`,`unitname`,`statue`,`time`) VALUES(".$data['unitid'].",'".$data['unitname']."',1,".$data['time'].")";
			$result_temp = $this->mysql->execute ( $sql );
			if ($result_temp==false) 
			{
				$input = "插入语句".$sql."（在sync.PHP 函数updatetime 第一个插入语句）出错，请检查程序或者数据库是否有问题,退出。";
				$this->log ( $input );
				$this->unitid = $this->currentnitid;
				$this->mysql->setUnitid($this->unitid);
				$sql = "update  " . $this->pre . "unitid set  statue=0,unitid=" . $this->unitid;
				$this->mysql->execute ( $sql );	
				exit ();
			}
		}
		$this->unitid = $this->currentnitid;
		$this->mysql->setUnitid($this->unitid);
	}
	
	private function logic_units_change($units,$n=3)//单位同步（增加，或者删除单位）
	{
		$all_total_dept=0;
		$all_total_member=0;
		foreach ( $units as $value )
		{
			if($value['status']==0)
			{
				$this->unitid = $this->currentnitid = $value['unitid'];
				$this->mysql->setUnitid($this->unitid);
				$this->unitname = $value['unitname'];
				$this->updatetime = $value['updatetime'];
				$sql = "select * from  " . $this->pre . "unitid where unitid=" . $value['unitid'];
				$result = $this->mysql->fetch_array ( $sql );
					
				if ($result === false) {
					$input = "查询语句" . $sql . "出错，请检查程序或者数据库是否有问题,退出。";
					$this->log ( $input );
					exit ();
				}
				$time_s = time ();
				if (empty ( $result )) 
				{
					//new add area_code
					$sql = "REPLACE INTO " . $this->pre . "unitid (`unitid`,`unitname`,`statue`,`time`,`area_code`) VALUES(".mysql_real_escape_string($value['unitid']).",'".mysql_real_escape_string($value['unitname'])."',0,'".mysql_real_escape_string($time_s)."','".mysql_real_escape_string($value['area_code'])."')";
					$result_temp = $this->mysql->execute ( $sql );
					if ($result_temp === false) 
					{
						$input = "单位同步，插入语句" . $sql . "出错，请检查程序或者数据库是否有问题";
						exit ();
					}
					$sync_unit=true;
				}
				else if ($result[0]['statue'] == 0) 
				{
					$time = $this->hours_min ( $result[0]['time'], time () );
					if ($time ['min'] >= 30) 						// 超过30分钟系统将会重新初始化
					{
						$sql = "REPLACE INTO ".$this->pre."unitid (`unitid`,`unitname`,`statue`,`time`) VALUES({$value['unitid']},'".mysql_real_escape_string($value['unitname'])."',0,$time_s)";
						$result_temp = $this->mysql->execute ( $sql );
						if ($result_temp === false) 
						{
							$input = "插入语句" . $sql . "出错，请检查程序或者数据库是否有问题";
							$this->log ( $input );
							exit ();
						}
						$sync_unit=true;
					}
					else 
					{
						$input = "存在正在初始化的单位，且初始化时间小于30分钟，该单位unitid=" . $value['unitid'] . "---单位名称是" . $value['unitname'];
						$this->log ( $input );
					}
				}
					
				$para_deptusers = array ();
				$para_deptusers ['updatetime'] = 0;
				$para_deptusers ['start'] = 0;
				$para_deptusers ['size'] = 10;
				$para_deptusers ['unitid'] = $value['unitid'];
				
				$total_depts = 0;
				$total_users = 0;
				while($sync_unit)
				{
					 //new add  部门同步
					 $depts=$this->sync_depts($para_deptusers);
					 $total_depts = $depts['total'];
					 if($total_depts!=0){
						$this->depts_unit($depts['depts']);
					 }
					 
					 //end
					 $deptusers=$this->unit_deptusers($para_deptusers);
					 $total_users = $deptusers['total'];
					 if($total_users!=0)
					 {
					      $this->members_unit($deptusers['users']);
					 }
					 
					 $max_users= $para_deptusers ['start']+ $para_deptusers ['size'];
					 if($total_users<=$max_users)
					 {
						   break;
					 }
					 else
					 {
						 $para_deptusers ['start'] = $max_users;
					 }		
				}				
			}
			else if($value['status']==1)
			{
				//删除单位
				$this->unitid = $this->currentnitid = $value ['unitid'];
				$this->mysql->setUnitid($this->unitid);
				$this->updatetime = $value['updatetime'];
				$sql = "select * from  " . $this->pre . "unitid where unitid=" . $value ['unitid'];
				$result_1 = $this->mysql->fetch_array ( $sql );
				if ($result_1 === false)
				{ 
					$input = "查询语句(sync.php 第140行)" . $sql . "出错，请检查程序或者数据库是否有问题";
					$this->log ( $input );
					continue;
				}
				if (!empty ( $result_1 ))
				{
					$result_2 = $this->mysql->fetch_array("SHOW TABLES",'MYSQL_NUM');
					 
					$temp_array=array
					(
						'0'=>$this->pre.'common_district',
						'1'=>$this->pre.'role_action',
						'2'=>$this->pre.'role_module',
						'3'=>$this->pre.'sadmin',
						'4'=>$this->pre.'sync'
					);
					
					foreach ($result_2  as $temp)
					{
						if(!in_array($temp[0], $temp_array))
						{ 
							//$sql="alter table ".$row[0]." delete from ".$row[0]."where unitid=51064";
							$sql="delete from ".$temp[0]." where unitid=".$value['unitid'];
							$result_temp = $this->mysql->execute($sql);
							if(false==$result_temp)
							{
								$input = "删除语句(sync.php 第160行)".$sql."出错，请检查程序或者数据库是否有问题";
								$this->log ( $input );
								continue;
							}	
						}	 
					}
					 
					if($result_1[0]['statue']==1)
					{
						$this->unitid=0;
						$this->mysql->setUnitid($this->unitid);
						$sql="delete from ".$this->pre."unitid where unitid=".$value['unitid'];
						$result_temp = $this->mysql->execute($sql);
						if(false==$result_temp)
						{ 
							$input = "删除语句(sync.php 第176行)".$sql."出错，请检查程序或者数据库是否有问题";
							$this->log ( $input );
							continue;
						}
						$this->unitid=$this->currentnitid;
						$this->mysql->setUnitid($this->unitid);
					}
				}
			}	
		}	
	}
	
	private function depts_unit($depts,$n=3) //初始化及更新部门
	{
		foreach ( $depts as $value_depts )
		{
			$sql="select count(*) as countt from ". $this->pre . "depts where  deptid=". $value_depts['deptid']." and unitid=".$this->unitid;
			$result_temp=$this->mysql->fetch_first($sql);
			
			if($result_temp==false)
			{
				$input = "查询语句(sync.php 函数members_unit第一个查询语句)" . $sql . "出错，请检查程序或者数据库是否有问题";
				$this->log ( $input );
				exit ();
			}
			else if($result_temp['countt']==0)
			{
				$sql1 = 'replace into ' .$this->pre . 'depts' . ' set deptid="' . $value_depts['deptid'] . '",
						parentid="' . $value_depts['parentid'] . '",
						subid="'.$value_depts['subid'].'",
						issub="'.$value_depts['issub'].'",
						deptname="'.mysql_real_escape_string($value_depts['deptname']).'",
						shortname="' . mysql_real_escape_string($value_depts['shortname']) .'",
						manager_uid="'.$value_depts['manager_uid'].'",
						seq="'.$value_depts['seq'].'",
						updatetime="'.$value_depts['updatetime'].'",
						updatetime2="' . $value_depts['updatetime2'] . '",
						updatetime3="' . $value_depts['updatetime3'] . '",
						allow_group="'.$value_depts['allow_group'].'",
						allow_batch="'.$value_depts['allow_batch'].'",
						unitid="' . $this->unitid.'"';
						
				$panduan = $this->mysql->execute ( $sql1 );
				if ($panduan == false)
				{
					$input = "插入语句(sync.php 函数depts_unit第一个插入语句)" . $sql1 . "出错，请检查程序或者数据库是否有问题";
					$this->log ( $input );
					exit ();
				}
			}
			else if($result_temp['countt']==1)
			{
					$sql2 = "update " . $this->pre . 'depts' . '  set parentid="' . $value_depts['parentid'] . '",
						subid="'.$value_depts['subid'].'",
						issub="'.$value_depts['issub'].'",
						deptname="'.mysql_real_escape_string($value_depts['deptname']).'",
						shortname="' .mysql_real_escape_string( $value_depts['shortname']) . '",
						manager_uid="'.$value_depts['manager_uid'].'",
						seq="'.$value_depts['seq'].'",
						updatetime="'.$value_depts['updatetime'].'",
						updatetime2="' . $value_depts['updatetime2'] . '",
						updatetime3="' . $value_depts['updatetime3'] .'",
						allow_group="'.$value_depts['allow_group'].'",
						allow_batch="'.$value_depts['allow_batch'].'"
						where  deptid="' . $value_depts['deptid'] . '" and unitid="' . $this->unitid.'"';
					$panduan = $this->mysql->execute ( $sql2 );
					
					if ($panduan == false)
					{	
						$input = "更新语句(sync.php 函数depts_unit第一个更新语句)" . $sql2 . "出错，请检查程序或者数据库是否有问题";
						$this->log ( $input );
						exit ();
					}
			}
		}
	}
	//end
	
	
	private  function  members_unit($deptusers_users,$n=3)//初始化及更新员工
	{
		foreach ( $deptusers_users as $value_users )
		{
			$sql="select count(*) as countt from ". $this->pre . "members where  uid='". $value_users['uid']."' and unitid='".$this->unitid."'";
			$result_temp=$this->mysql->fetch_first($sql);
			
			$sync_admins=$this->sync_admins($this->unitid);
			
			$value_users['gender']=$value_users['gender']?$value_users['gender']:0;
			if($result_temp==false)
			{
				$input = "查询语句(sync.php 函数members_unit第一个查询语句)" . $sql . "出错，请检查程序或者数据库是否有问题";
				$this->log ( $input );
				exit ();
				
			}
			else if($result_temp['countt']==0)
			{
				$d_m=0;
				$a_t=0;
				if(is_array($sync_admins['uids'])&&in_array($value_users['uid'],$sync_admins['uids'])){
					$d_m.='1';
					$a_t.='2';
				}		
				$sql1 = "insert into " . $this->pre . 'members' . ' 
				(
						uid,
						uap_uid,
						username,
						spell1,
						spell2,
						nickname,
						workid,
						signature,
						joindate,
						gender,
						telephone,
						mobilephone,
						email,
						sysavatar,
						updatetime,
						isactive,
						seat,
						type,
						status,
						deptid,
						seq,
						depts,
						unitid,
						default_account_type,
						account_type
						
				)
				values
				(
						"'.$value_users['uid'].'",
						"'.$value_users['uap_uid'] . '",
						"'.mysql_real_escape_string($value_users['username']).'",
						"'.mysql_real_escape_string($value_users['spell1']).'",
						"'.mysql_real_escape_string($value_users['spell2']).'",
						"'.mysql_real_escape_string($value_users['nickname']).'",
						"'.$value_users['workid'].'",
						"'.$value_users['signature'].'",
						"'.$value_users['joindate'].'",
						"'.$value_users['gender'].'",
						"'.$value_users['telephone'].'",
						"'.$value_users['mobilephone'].'",
						"'.$value_users['email'].'",
						"'.$value_users['sysavatar'].'",
						"'.$value_users['updatetime'].'",
						"'.$value_users['isactive'].'",
						"'.$value_users['seat'].'",
						"'.$value_users['type'].'",
						"'.$value_users['status'].'",
						"'.$value_users['deptid'].'",
						"'.$value_users['seq'].'",
						"'.implode(',',array_values($value_users["depts"])).'",
						"'.$this->unitid.'",
						"'.$d_m.'",
						"'.$a_t.'"
						)';
				
				$panduan = $this->mysql->execute ( $sql1 );
				if ($panduan == false)
				{
					$input = "插入语句(sync.php 函数members_unit第一个插入语句)" . $sql1 . "出错，请检查程序或者数据库是否有问题";
					$this->log ( $input );
					exit ();
				}
					
			}
			else if($result_temp['countt']==1)
			{
				$sql2 = "update " . $this->pre . 'members' . '  set uid="' . $value_users['uid'] . '",
					uap_uid="' . $value_users['uap_uid'] . '",
					username="'.mysql_real_escape_string($value_users['username']).'",
					spell1="'.mysql_real_escape_string($value_users['spell1']).'",
					spell2="'.mysql_real_escape_string($value_users['spell2']).'",
					nickname="' . mysql_real_escape_string($value_users['nickname'] ). '",
					workid="'.$value_users['workid'].'",
					signature="'.$value_users['signature'].'",
					joindate="'.$value_users['joindate'].'",
					gender="' . $value_users['gender'] . '",
					telephone="' . $value_users['telephone'] . '",
					mobilephone="'.$value_users['mobilephone'].'",
					email="'.$value_users['email'].'",
					sysavatar="' . $value_users['sysavatar'] . '",
					updatetime="' . $value_users['updatetime'] . '",
					isactive="'.$value_users['isactive'].'",
					seat="'.$value_users['seat'].'",
					type="'.$value_users['type'].'",
					status="' . $value_users['status'] . '",
					deptid="' . $value_users['deptid'] . '",
					seq="'.$value_users['seq'].'",
					depts="'.implode(',',array_values($value_users['depts'])).'"
					where unitid="'.$this->unitid.'"  and uid='.$value_users['uid'];
				$panduan = $this->mysql->execute ( $sql2 );
				if ($panduan == false)
				{	
					$input = "更新语句(sync.php 函数depts_unit第一个更新语句)" . $sql2 . "出错，请检查程序或者数据库是否有问题";
					$this->log ( $input );
					exit ();
				}
			}
		}
	}
	//同步数据时间更新
	private function up_date_sync_time(){
		
		$sql="select count(*) as countt from ". $this->pre . "sync ";
		$result_temp=$this->mysql->fetch_first($sql);
		if($result_temp==false)
		{
			$input = "查询语句(sync.php 函数up_date_sync_time第一个查询语句)" . $sql . "出错，请检查程序或者数据库是否有问题";
			$this->log ( $input );
			exit;
		}
		else if($result_temp['countt']==1){
			$sql2 = "update " . $this->pre . 'sync' . " set unit_dt=" .$this->updatetime.",data_dt=".$this->updatetime;
			$result_temp = $this->mysql->execute($sql2);
			if($result_temp==false)
			{
				$input = "查询语句(sync.php 函数up_date_sync_time第二语句)" . $sql2 . "出错，请检查程序或者数据库是否有问题";
				$this->log ( $input );
				exit;
			}
			
		}else{
			$sql2 = "insert into " . $this->pre . 'sync' . " set unit_dt=" .$this->updatetime.",data_dt=".$this->updatetime;
			$result_temp = $this->mysql->execute($sql2);
			if($result_temp==false)
			{
				$input = "查询语句(sync.php 函数up_date_sync_time第三语句)" . $sql . "出错，请检查程序或者数据库是否有问题";
				$this->log ( $input );
				exit;
			}
		}
	}
	private function depts_delete($delete_depts){//删除部门
		
		foreach($delete_depts as $value_delete)
		{
			$sql="select count(*) as countt from ". $this->pre . "depts where  deptid=". $value_delete['deptid']." and unitid=".$this->unitid;
			$result_temp=$this->mysql->fetch_first($sql);
			if($result_temp==false)
			{
				$input = "查询语句(sync.php 函数depts_delete第一个查询语句)" . $sql . "出错，请检查程序或者数据库是否有问题";
				$this->log ( $input );
				return;
			}
			else if($result_temp['countt']==1)
			{
				$result_2 = $this->mysql->fetch_array("SHOW TABLES",'MYSQL_NUM');
				
				$temp_array=array
				(
						'0'=>$this->pre.'common_district',
						'1'=>$this->pre.'role_action',
						'2'=>$this->pre.'role_module',
						'3'=>$this->pre.'sadmin',
						'4'=>$this->pre.'sync',
						'5'=>$this->pre.'unitid',
				);
				
				foreach ($result_2  as $temp)
				{
					if(!in_array($temp[0], $temp_array))
					{
						$sql="delete from ".$temp[0]." where deptid=".$value_delete['deptid'].' and unitid='.$this->unitid;
						$result_temp = $this->mysql->execute($sql);
					}
						
				}
			}
		}
	}
	private  function  members_delete($delete_users,$n=3)//删除员工
	{
		foreach($delete_users as $value_delete)
		{
			$sql="select count(*) as countt from ". $this->pre . "members where  uid=". $value_delete['uid']." and unitid=".$this->unitid;
			$result_temp=$this->mysql->fetch_first($sql);
			if($result_temp==false)
			{
				$input = "查询语句(sync.php 函数members_delete第一个查询语句)" . $sql . "出错，请检查程序或者数据库是否有问题";
				$this->log ( $input );
				return;
			}
			else if($result_temp['countt']==1)
			{
				$result_2 = $this->mysql->fetch_array("SHOW TABLES",'MYSQL_NUM');
				$temp_array=array
				(
						'0'=>$this->pre.'common_district',
						'1'=>$this->pre.'role_action',
						'2'=>$this->pre.'role_module',
						'3'=>$this->pre.'sadmin',
						'4'=>$this->pre.'sync',
						'5'=>$this->pre.'unitid',
				);
				
				foreach ($result_2  as $temp)
				{
					if(!in_array($temp[0], $temp_array))
					{
						$sql="delete from ".$temp[0]." where uid=".$value_delete['uid'];
						$result_temp = $this->mysql->execute($sql);
					}
						
				}
			}
		}
	}
	
	// 时间戳相减获取到的分数及小时
	private function hours_min($start_time, $end_time) {
		$sec = $end_time - $start_time;
		$min = $sec = round ( $sec / 60 );
		$time = array ();
		$time ['min'] = $min;
		return $time;
	}
	
	/*
	 * | 应用登录 +-------------------------------- | METHOD	POST +-----------------
	 * | @para id | @para key +----------------- {
	 * "sid":"r62vluemi5m50cebshsaepu863"//session id }
	 */
	private function auth_applogin() {
		$method = 'auth/applogin';
		$input = array (
				'id' => $this->id,
				'key' => $this->key 
		);
		
		$rs = $this->io ( $method, $input );
		return $rs;
	}
	
	
	//new add  单位管理员同步--------------------------------------------------------------------------
	
	
	
	public function sync_admins($unitid,$n=3) {
		/*
		 * o	200 同步成功 o	401 未登录或会话过期 o	403 单位未对应用授权，无权限
		 */
		$method = "unit/admins?unitid={$unitid}";
		$cookie = "PHPSESSID=" . $this->sid;
		$rs = $this->io ( $method, '', $cookie );
		if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{
			$this->oap_error_log($rs,"获取使用应用的单位列表sync_admins失败");
			exit;
		}
	}
	
	//end----------------------------------------------------------------------------------
	
	/*
	 * | 增量方式，获取使用应用的单位列表 +--------------------------------- | METHOD	GET
	 * +---------------- | @para updatetime 选填，客户端存储的更新时间，为0时，获取全量 | @para start
	 * 选填，开始位置，默认为0 | @para size 选填，最多获取的数量，默认为50，最大值100 +---------------- {
	 * "total":, 单位个数(11位整数) "units":[ { "unitid":, 单位id,(11位整型) "unitname":,
	 * 单位名称,(20位字符) "status":, 状态标识，=0 为订阅，=1为取消订阅（2位整型） "updatetime": ,
	 * 修改时间戳（11位整型） "area_code": 所在地区（12个字符）(新) }, ... ] }
	 */
	public function sync_units(array $para,$n=3)
	{
		/*
		 * o	200 同步成功 o	401 未登录或会话过期 o	403 单位未对应用授权，无权限
		 */
		$method = 'sync/units?updatetime=' . $para ['updatetime'] . '&start=' . $para ['start'] . '&size=' . $para ['size'];
		$cookie = "PHPSESSID=" . $this->sid;
		$rs = $this->io ( $method, '', $cookie );
		
		if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{
			$this->oap_error_log($rs,"获取使用应用的单位列表sync/units失败");
			exit();
		}
	}
	
	/*
	 | 增量方式，获取应用有授权的单位的更新部门列表
	 +---------------------------------------
	 | METHOD	GET
	 +----------------
	 | @para updatetime 必填，客户端存储的更新时间，为0时，获取全量
	 | @para unitid 必填，返回指定单位内的部门更新记录，不支持多个单位
	 | @para start 选填，开始记录序号，默认=0
	 | @para size 选填，返回最大记录数，默认=50，最大100，超过报错
	 +----------------
	 { "total":123,
	  "depts":[{ "unitid":123, //请求部门所在单位编号（11位整数） "deptid":123, //部门编号（8位整数）
	  "parentid":123, //父部门编号，顶级部门返回空(11位整数) "subid":123, //分支机构编号（11位整数）
	  "issub":1, //是否子单位（1位整数） "deptname":"2000级计算机1班", //部门名称（20位字符）
	  "shortname":"计1班", //部门简称（20位字符） "manager_uid":123, //部门负责人编号（11位整数）
	  "manager_username":"小珊珊", //部门负责人姓名（20位字符） "seq":1, //排序（8位整型）
	  "updatetime":123, //部门最后更新时间，时间戳（11位整型） "updatetime2":123,
	  //下级部门被删除（不包括子部门）最后时间，时间戳（11位整型） "updatetime3":123
	  //当前部门成员被删除（不包括子部门）更新时间，时间戳（11位整型） "allow_group":0, //允许部门群 0=不允许
	  1=允许（5位整数） "allow_batch":0 //允许批量消息 0=不允许 1=允许（5位整数） } .. ] }
	 */
	public function sync_depts(array $para,$n=3) {
		/*
		 * o	200 同步成功 o	401 未登录或会话过期 o	403 单位未对应用授权，无权限
		 */
		$method = 'sync/depts?updatetime=' . $para ['updatetime'] . '&unitid=' . $para ['unitid'] . '&start=' . $para ['start'] . '&size=' . $para ['size'];
		$cookie = "PHPSESSID=" . $this->sid;
		$rs = $this->io ( $method, '', $cookie );
		
		if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{
			$this->oap_error_log($rs,"获取部门信息同步unit/depts接口失败");
			exit();
		}
	}
	
	/*
	 * | 增量方式，职员信息同步 +--------------------------------------- | METHOD	GET
	 * +---------------- | @para updatetime 选填，人员修改时间 | @para unitid
	 * 必填，单位ID（8位整数） | @para start 选填，当前开始位置，默认0（11位整数） | @para size
	 * 选填，每页记录数，默认20最大100（11位整数） +---------------- { "total":123, //人员个数（11位整数）
	 * "users":[ { "uid":, //用户编号（11位整数） "uap_uid":,
	 * //此身份绑定用户通行证为null或0代表未绑定用户通行证（11位整型） "username":, //用户姓名 （40位字符）
	 * "spell1":, //用户姓名全拼 （128位字符），多音字返回，如：王长 （wangzhang,wangchang）
	 * "spell2":"zss", //用户姓名简拼 （40位字符），多音字返回，如：王长 （wz,wc） "nickname":"小姗姗",
	 * //用户昵称 （40位字符） "workid":"4511212", //工号 （15位字符） "signature":"今天阳光灿烂",
	 * //个性签名（64位字符） "joindate":"2010-01-12", //入职时间（12位字符） "gender":1, //性别
	 * 0=保密 1=男 2=女（3位整型） "telephone":"059187860988", //计费电话（20位字符）
	 * "mobilephone":"13897854568", //常用手机（20位字符） "email":"test@test.com",
	 * //电子邮件（32位字符） "sysavatar":1, //默认为1，0标识为自定义头像，系统默认头像图片id（4位整型）
	 * "updatetime":123, //记录最后更新时间 "isactive":1, //标识用户账户是否已激活，1为激活，0未激活 （1位整型）
	 * "seat":"DM3-001", //办公座位标识 （20位字符） "type":1, //人员分类 0,1=职员 2=学生
	 * 3=家长（5位整型） "status":0, //状态(0-正常、1-禁用、2-离职)(5位整型) "deptid":123,
	 * //主要部门ID（11位整型） "seq":1, //排序（8位整型） "depts":[123,123] //所在部门ID（11位整型） },
	 * ... ] }
	 */
	public function unit_deptusers(array $para,$n=3)
	{
		$method = 'unit/deptusers?updatetime='.$para['updatetime'].'&unitid='.$para['unitid'].'&start='.$para['start'].'&size='.$para['size'];
		$cookie = "PHPSESSID=" . $this->sid;
		$rs = $this->io ( $method, '', $cookie );
		
		if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{
			$this->oap_error_log($rs,"获取职员信息同步unit/deptusers接口失败");
			exit();
		}
		
	}
	/* 获取授权单位更新时间戳
	功能说明：获取授权单位更新时间戳
	请求方式：GET
	请求资源：/sync/unitupdates{?updatetime=123}[&start=0][&size=50]
	updatetime 必填，客户端存储的更新时间
	start 选填，开始记录序号，默认=0
	size 选填，返回最大记录数，默认=50，最大100，超过报错
	*/
	private function unit_updates(array $para,$n=3) {
		$method = 'sync/unitupdates?updatetime=' . $para['updatetime'] . '&start=' . $para['start'] . '&size=' . $para['size'];
		$cookie = "PHPSESSID=" . $this->sid;
		$rs = $this->io ($method, '', $cookie );
		
		if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{ 
			$this->oap_error_log($rs,"获取授权单位更新时间戳unit/deptusers接口失败");
			exit();
		}
	}
	
	
	
	/*
	 * | 获取单位的详细资料 +--------------------------------------- | METHOD	GET
	 * +---------------- | @para unitid 必填，单位ID（8位整数） +---------------- {
	 * "unitid":123, //单位编号(8位整型) "parentid":123, //父单位编号，顶级单位返回空(8位整型)（作废）
	 * "unitcode":"123", //单位代码(3~16个字符) "name":"福州大学", //单位名称(64个字符)
	 * "shortname":"福大", //单位简称(20个字符) "unittype":0, //单位类型 0=普通单位，1=学校(3位整型)
	 * "master":"张三", //负责人(20个字符) "contact":"李四", //联系人(20个字符)
	 * "telephone":"0591-89896989", //联系电话(20个字符) "area_code":"123",
	 * //所在地区（12个字符） "addr":"福州市杨桥路234号", //联系地址(128个字符) "postcode":"350003",
	 * //邮政编码(10个字符) "fax":"0591-88978534", //传真(20个字符)
	 * "site":"http://www.fzu.edu.cn", //网址(64个字符) "seq":1, //排序（8位整型）
	 * "updatetime":123, //单位基本资料最后更新时间，时间戳（11位整型） "updatetime2":123,
	 * //一级部门被删除最后时间，时间戳（11位整型） "updatetime3":123, //未设置部门的成员被删除更新时间，时间戳（11位整型）
	 * "admin": { "uid":123, //单位管理员uid（11位整型） "uap_account":"test"
	 * //单位管理员91通行证账号 } }
	 */
	private function unit_info($unitid,$n=3) {
		/*
		 * 失败：{"msg":"错误信息"} o	200 成功 o	401 未登录或会话过期 o	403 单位未对应用授权，无权限
		 */
		$method = 'unit/info?unitid=' . $unitid;
		$cookie = "PHPSESSID=" . $this->sid;
		$rs = $this->io ( $method, '', $cookie );
	    if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{
			$this->oap_error_log($rs,"获取单位的详细资料unit/info接口失败");
			exit();
		}
	}
	
	/*
	 * | 增量方式，获取部门删除历史记录 +--------------------------------------- | METHOD	GET
	 * +---------------- | @para updatetime 必填，客户端存储的更新时间 | @para unitid
	 * 必填，返回指定单位内的部门更新记录，不支持多个单位 | @para start 选填，开始记录序号，默认=0 | @para size
	 * 选填，返回最大记录数，默认=50 +---------------- { "total":, //记录总个数(11位整数) "data":[ {
	 * "unitid":, //单位ID,(11位整型) "deptid":, //部门ID，（11位整型） "removetime":
	 * //删除时间戳（11位整型） }, ... ] }
	 */
	private function sync_deptrm(array $para) {
		/*
		 * 失败：{"msg":"错误信息"} o	200 同步成功 o	401 未登录或会话过期 o	403 单位未对应用授权，无权限 o	405
		 * 请求参数错误
		 */
		$method = 'sync/depts?updatetime=' . $para ['updatetime'] . '&unitid=' . $para ['unitid'] . '&start=' . $para ['start'] . '&size=' . $para ['size'];
		$cookie = "PHPSESSID=" . $this->sid;
		$rs=$this->io ( $method, '', $cookie );
		if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{
			$this->oap_error_log($rs,"获取职员删除历史记录unit/info接口失败");
			exit();
		}
	}
	
	/*
	 * | 增量方式，获取职员删除历史记录 +--------------------------------------- | METHOD	GET
	 * +---------------- | @para updatetime 必填，客户端存储的更新时间 | @para unitid
	 * 必填，单位ID（8位整数） | @para start 选填，开始位置，默认为0 | @para size 选填，最多获取的数量，默认为50
	 * +---------------- { "total":123, //记录总个数(11位整数) "data":[ { "uid":123,
	 * //职员ID（11位整型） "removetime": 123 //删除时间戳（11位整型） }, ... ] }
	 */
	private function sync_userrm($para,$n=3) {
		/*
		 * 失败：{"msg":"错误信息"} o	200 同步成功 o	401 未登录或会话过期 o	403 单位未对应用授权，无权限 o	405
		 * 请求参数错误
		 */
		$method = 'sync/userrm?updatetime=' . $para['updatetime'] . '&unitid=' . $para['unitid'] . '&start=' . $para['start'] . '&size=' . $para['size'];
		$cookie = "PHPSESSID=" . $this->sid;
	    $rs = $this->io ( $method, '', $cookie );
	    if($rs['httpCode']==200)
		{
			return $rs['data'];
		}
		else
		{
			$this->oap_error_log($rs,"获取职员删除历史记录unit/info接口失败");
			exit();
		}
	}
	private function log($input) {
		$file = $GLOBALS['file'];
		error_log ( date ( 'Y-m-d H:i:s', time () ) . print_r ( $input, 1 ), 3, $file );
	}
	private function oap_error_log($input,$msg) {
		//$file = $_SERVER ['DOCUMENT_ROOT'] . '/log/oap_error_log' . date('Y-m-d',time()) . '.txt';
		//$file = 'log/' ."MYSQL_". time ( 'Y-m-d' ) . '.txt';
		$file = $GLOBALS['file'];
		error_log ( date('Y-m-d H:i:s',time()).$msg.print_r ( $input, 1 ), 3, $file );
	}
	
	private function io($method = 'auth/applogin', $input = '', $cookie = '') {
		$url = $this->server . $method;
		
		for ($num=0;$num<3;$num++) 
		{
			$rs = io::instance ()->action ( $url, $input, $cookie );
			if($rs['httpCode']==200)
			{
				return $rs;
			}
			else 
			{
				$file = $GLOBALS['file'];
				$errorlog = "<br>http失败.url=".$url." data:".print_r ( $input, 1 )."<br>".print_r ($rs,1);
				error_log ( date('Y-m-d H:i:s',time()).$errorlog, 3, $file );
				echo $errorlog;
				//sleep(2);
			}
		}
		
		return $rs;
	}
	
	
}

class io {
	
	/*
	 * | IO 实例化方法 +-------------------------
	 */
	static function &instance() {
		static $obj = NULL;
		if ($obj == NULL) {
			$obj = new io ();
		}
		
		return $obj;
	}
	private function __construct() {
	}
	
	/*
	 * | 通讯方法 +----------------------------------------- | @para url	请求的资源地址 |
	 * @para data	请求的参数，不为空POST，否则GET
	 */
	function action($url, $data = '', $cookie = '') 
	{
		$rs = $this->_send ( $url, 0, empty ( $data ) ? '' : json_encode ( $data ), $cookie );
		
		if ( $GLOBALS['debug'] == 1 ) 
		{
			echo "<br>";
			echo "url:".$url;echo "<br>";
			if ( empty ( $data ) ) {
				echo "data:";
			}
			else { 
				echo "data:";
				echo json_encode ( $data );
			}
			echo "<br>";
			echo "cookie:".$cookie;echo "<br>";
			echo "return:";
			print_r($rs);
			echo "<br>";
		}
		return $rs;
	}
	
	private function _send($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = TRUE) {
		$hd = $return = '';
		$matches = parse_url ( $url );
		! isset ( $matches ['host'] ) && $matches ['host'] = '';
		! isset ( $matches ['path'] ) && $matches ['path'] = '';
		! isset ( $matches ['query'] ) && $matches ['query'] = '';
		! isset ( $matches ['port'] ) && $matches ['port'] = '';
		$host = $matches ['host'];
		$path = $matches ['path'] ? $matches ['path'] . ($matches ['query'] ? '?' . $matches ['query'] : '') : '/';
		$port = ! empty ( $matches ['port'] ) ? $matches ['port'] : 80;
		if ($post) {
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			@$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: ' . strlen ( $post ) . "\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
			
		} else {
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			@$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		
		if (function_exists ( 'fsockopen' )) {
			$fp = @fsockopen ( ($ip ? $ip : $host), $port, $errno, $errstr, $timeout );
		} elseif (function_exists ( 'pfsockopen' )) {
			$fp = @pfsockopen ( ($ip ? $ip : $host), $port, $errno, $errstr, $timeout );
		} else {
			$fp = false;
		}
		if (! $fp) {
			$rs = array (
					'httpCode' => 800,
					'data' => array (
							'msg' => '当前项目环境，PHP不支持套接字连接！' 
					) 
			);
		} else {
			stream_set_blocking ( $fp, $block );
			stream_set_timeout ( $fp, $timeout );
			@fwrite ( $fp, $out );
			$status = stream_get_meta_data ( $fp );
			if (! $status ['timed_out']) {
				while ( ! feof ( $fp ) ) {
					if (($header = @fgets ( $fp )) && ($header == "\r\n" || $header == "\n")) {
						break;
					}
					$hd .= $header;
				}
				$stop = false;
				while ( ! feof ( $fp ) && ! $stop ) {
					$data = fread ( $fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit) );
					$return .= $data;
					if ($limit) {
						$limit -= strlen ( $data );
						$stop = $limit <= 0;
					}
				}
			}
			@fclose ( $fp );
			preg_match ( '|HTTP/1\.1\s+(\d{3})|', $hd, $m );
			$rs = array (
					'httpCode' => $m [1],
					'data' => json_decode ( $return, true ) 
			);
		}
		return $rs;
	}
}



class mysql {
	var $use_set_names;
	var $hostname = array(//服务器IP地址
						'localhost',
						'localhost',
						'localhost',
						'localhost',
						'localhost',
						'localhost',
						'localhost',
						'localhost',
						'localhost',
						'localhost'
						); 
	var $port = 3306;
	var $username = 'root';  //数据库账号
	var $password = 'anjin84';//数据库密码
	var $database;//数据库真实名称（数据可能存在多个数据库中。这个是表明现在存的是哪个数据库）
	var $charset; 
	var $conn_id = array();
	var $unitid = null;//组织ID
	var $dbCnt = 10;      //存放数据库的个数
	var $dbname='wj_new';//数据库前面统一的名称 
	var $index=null;//判断当前是哪个库
	function __construct() {
		
	}
	static function &instance() {
		static $mdl = NULL;
		if ($mdl == NULL) {
			// $mdl = new __CLASS__;
		}
		return $mdl;
	}
	
	function setUnitid($unitid){
		$this->unitid = $unitid;
		
	}
	
	function checkConnection() {
		if($this->unitid === null )
		{
			$input="当前数据库类的unitid为空，请检查程序或者数据库出错";
			echo $input;
			$this->log($input);
			exit();
		}
		$unitid=$this->unitid;
		$dbCnt=$this->dbCnt;
		$tmp=abs($unitid%$dbCnt);
		$this->index =$tmp ;
		$this->conn_id=array();
		if (empty($this->conn_id[$this->index])) {
			
			$this->conn_id[$this->index] = $this->db_connect ();
			
			// 连接失败
			if (! $this->conn_id[$this->index]) {
				Log::instance ()->error ( "[mysql checkConnection] 数据库连接失败 失败原因:" . $this->get_mysql_error () . " --错误码:" . mysql_errno () );
				CommonFunc::sendResponse ( 500, '', 'application/json', "数据库连接失败" );
			}
			$this->db_select();
			$this->db_set_charset ();
		} 
	}
	
	/*
	 * | 创建数据库连接 +-----------------------
	 */
	function db_connect() {
		$index=($this->unitid%$this->dbCnt);
		$tmp_arr=$this->hostname; 
		$hostname=$tmp_arr[$index];
		if ($this->port != '') {
			$hostname.= ':' . $this->port;
		}
		return mysql_connect ( $hostname, $this->username, $this->password );
	}
	
	/*
	 * | 创建数据库持久连接 +-----------------------
	 */
	function db_pconnect() {
		$index=($this->unitid%$this->dbCnt);
		$tmp_arr=$this->hostname; 
		$hostname=$tmp_arr[$index];
		if ($this->port != '') {
			$hostname.= ':' . $this->port;
		}
		return @mysql_pconnect ( $hostname, $this->username, $this->password );
	}
	
	/*
	 * | 重连MySQL服务器 +-----------------------
	 */
	function reconnect() {
		if (mysql_ping ( $this->conn_id[$this->index] ) === FALSE) {
			$this->conn_id[$this->index] = FALSE;
		}
	}
	
	/*
	 * | 选择数据 +-----------------------
	 */
	function db_select() {
		/*$tmp_index=$this->index;//编号从1始,如第一个库为ydkq_1,第二个为ydkq_2....
		$tmp_index+=1;
		$tmp_db=$this->dbname;
		$this->database=$tmp_index;
		$tmp_db.=$tmp_index;*/
		mysql_select_db ($this->dbname, $this->conn_id[$this->index] ) or die('mysql_select_db  error');
	}
	
	/*
	 * | 设置环境字符集 +-----------------------
	 */
	function db_set_charset() {
		if (! isset ( $this->use_set_names )) {
			$this->use_set_names = (version_compare ( PHP_VERSION, '5.2.3', '>=' ) && version_compare ( mysql_get_server_info (), '5.0.7', '>=' )) ? FALSE : TRUE;
		}
		
		$this->use_set_names = TRUE;
		$this->charset = "utf8";
		if ($this->use_set_names === TRUE) {
			return @mysql_query ( "SET character_set_connection='utf8', character_set_results='utf8', character_set_client=binary", $this->conn_id[$this->index] );
		} else {
			return @mysql_set_charset ( $this->charset, $this->conn_id[$this->index] );
		}
	}
	function version() {
		return "SELECT version() AS ver";
	}
	//默认是返回关联数组
	function fetch_array($sql,$type='MYSQL_ASSOC') {
		$query = $this->execute ( $sql );
		if ($query == false) {
			return false;
		}
		$rs = array ();
		$i = 0;
		if($type=='MYSQL_ASSOC')
		{
		    while ( $row = mysql_fetch_array ( $query, MYSQL_ASSOC ) ) {
			    $rs [$i ++] = $row;
		    }
		}else if($type=='MYSQL_NUM')
		{
			while ( $row = mysql_fetch_array ( $query, MYSQL_NUM ) ) {
				$rs [$i ++] = $row;
			}
		}
		
		return $rs;
	}
	function fetch_first($sql) {
		$query = $this->execute ($sql);
		if ($query == false) {
			return false;
		}
		$rs = array ();
		if($rs = mysql_fetch_array ( $query, MYSQL_ASSOC ))
		{
			return $rs;
		}
		return $rs;
	}
	
	// 执行update或insert，返回true表示执行成功，false执行失败
	function update_data($sql) {
		return $this->execute ( $sql );
	}
	function insert_id() {
		return mysql_insert_id ();
	}
	function get_mysql_error() {
		return mysql_error ();
	}
	function get_affected_rows() {
		return mysql_affected_rows ();
	}
	
	/*
	 * | 执行查询语句 +----------------------
	 */
	function execute($sql,$returnAutoId=false,$test=false) {
		$result=$this->checkConnection();
		if($test){
			var_export($sql);
		}
		$sql = $this->_prep_query( $sql );
		$rs = mysql_query ( $sql, $this->conn_id[$this->index]);
		if($returnAutoId){
			 return mysql_insert_id();
		}
		return $rs;
	}
	function _prep_query($sql) {
		return $sql;
	}
	
	/*
	 * | 数据过滤 +-------------------------------------
	 */
	function escape_str($str, $like = FALSE) {
		if (is_array ( $str )) {
			foreach ( $str as $key => $val ) {
				$str [$key] = $this->escape_str ( $val, $like );
			}
			
			return $str;
		}
		
		if (function_exists ( 'mysql_real_escape_string' ) and is_resource ( $this->conn_id[$this->index] )) {
			$str = mysql_real_escape_string ( $str, $this->conn_id[$this->index] );
		} elseif (function_exists ( 'mysql_escape_string' )) {
			$str = mysql_escape_string ( $str );
		} else {
			$str = addslashes ( $str );
		}
		
		// escape LIKE condition wildcards
		if ($like === TRUE) {
			$str = str_replace ( array (
					'%',
					'_' 
			), array (
					'\\%',
					'\\_' 
			), $str );
		}
		
		return $str;
	}
	//日志
	private function log($input) {
	
		//$file = $_SERVER ['DOCUMENT_ROOT'] . '/log/' ."MYSQL_". time ( 'Y-m-d' ) . '.txt';
		//$file = 'log/' ."MYSQL_". time ( 'Y-m-d' ) . '.txt';
		$file = $GLOBALS['file'];
		error_log ( print_r ( $input, 1 ), 3, $file );
	}
	/*
	 * | 关闭查询连接 +----------------------- 
	 */
	function close() {
		if(is_array($this->conn_id))
		{
			foreach($this->conn_id as $k => $v)
			{
				@mysql_close ( $v );
			}
		}
	}
	function __destruct() {
		$this->close ();
	}
	//创建数据库
	function build_DB($index){
		$index+=1;
		$conn=$this->db_connect()or die(mysql_error);
		$query=" CREATE DATABASE  IF NOT EXISTS ".$this->dbname.$index." DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		mysql_query($query,$conn) or die("创建数据库失败");
	}
	
}