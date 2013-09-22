<?php
interface Iwj
{
    public function getWjId();//返回问卷id
	
	public function getWjAll();//返回问卷列表
	
	public function getCuser();//返回返回制作人

    public function getTitle();//返回问卷标题

    public function getTopDesc();//返回问卷头部html

    public function getFootDesc();//返回问卷底部html
	
	public function getAlldata();//返回问卷所有数据

    public function getWjSubjects();//返回所有题目对象数组
	
	public function assert($subjectid,$itemid,$additional=null,$answer);//根据题目id，答题ID，补充，简答内容判定得分
}
?>
