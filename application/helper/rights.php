<?php
/*
系统权限配置文件
修改请慎重！
"|"和"#"下划线不可用命名
*/
/*******************基本信息******************/
$rights['quest']['name'] = '问卷设计';
$rights['quest']['action']['style']['name'] = '问卷分类';
$rights['quest']['action']['style']['rights'] = array('sel'=>'浏览',
															'add'=>'新增',
															'edit'=>'修改',
															'del'=>'删除');
/*$rights['quest']['action']['type']['name'] = '问卷类型';
$rights['quest']['action']['type']['rights'] = array('sel'=>'浏览',
															'add'=>'新增',
															'edit'=>'修改',
															'del'=>'删除');*/
$rights['quest']['action']['quest']['name'] = '问卷列表';
$rights['quest']['action']['quest']['rights'] = array('sel'=>'浏览',
															'add'=>'新增',
															'edit'=>'修改',
															'del'=>'删除');
/*$rights['quest']['action']['subtype']['name'] = '题目类型';
$rights['quest']['action']['subtype']['rights'] = array('sel'=>'浏览',
															'add'=>'新增',
															'edit'=>'修改',
															'del'=>'删除');*/
$rights['quest']['action']['subject']['name'] = '题目设置';
$rights['quest']['action']['subject']['rights'] = array('sel'=>'浏览',
															'add'=>'新增',
															'edit'=>'修改',
															'del'=>'删除');
$rights['quest']['action']['draft']['name'] = '草稿';
$rights['quest']['action']['draft']['rights'] = array('sel'=>'浏览',
															'add'=>'新增',
															'edit'=>'修改',
															'del'=>'删除',
															'send'=>'发布');	
$rights['quest']['action']['recycle']['name'] = '回收站';
$rights['quest']['action']['recycle']['rights'] = array('sel'=>'浏览',
															'send'=>'恢复',
															'del'=>'删除');																
/*******************项目管理******************/
$rights['dj']['name'] = '答卷管理';

$rights['dj']['action']['my']['name'] = '答卷列表';
$rights['dj']['action']['my']['rights'] = array('sel'=>'浏览');
/*******************评分管理******************/
/*$rights['report']['name'] = '问卷分析';
$rights['report']['action']['convention']['name'] = '常规分析';
$rights['report']['action']['convention']['rights'] = array('sel'=>'浏览',
															'edit'=>'评分',
															'expExcel'=>'导出');
$rights['report']['action']['condition']['name'] = '条件分析';
$rights['report']['action']['condition']['rights'] = array('sel'=>'浏览');	
$rights['report']['action']['cross']['name'] = '交叉分析';
$rights['report']['action']['cross']['rights'] = array('sel'=>'浏览');		*/
																													
											
/*******************系统管理******************/
$rights['system']['name'] = '系统管理';
$rights['system']['action']['user']['name'] = '用户管理';
$rights['system']['action']['user']['rights'] = array('edit'=>'修改用户');
$rights['system']['action']['rights']['name'] = '角色管理';
$rights['system']['action']['rights']['rights'] = array('sel'=>'浏览',
													    'add'=>'新增',
													    'edit'=>'修改',
													    'rights'=>'权限设置');
?> 