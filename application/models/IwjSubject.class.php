<?php
interface IwjSubject
{
    public function getSubjectid();//返回题目id

    public function getOrder();//返回题目序号
	
	public function getCuser();//返回返回制作人

    public function getSubjectTitle();//返回题目标题

    public function getImageURL();//返回题目图片url，无则为null
	
	public function getSubjectAll();//返回所有数据

    public function isCheck();//是否多选，false为单选

    public function getSubjectItems();//返回可选答题信息数组。例：array(0=>array('id','item_title'))
}
?>