<?php
include_once(APP_PATH . '/application/models/dao.class.php');
include_once(APP_PATH . '/application/models/dj/domain/djItem.class.php');
class djItemDAO extends dao
{
    public function setDjItemScore($djitemid,$score)
    {
        return $this->crud->U('t_dj_item',array('dj_score'=> $score),'djitemid',$djitemid);
    }

    public function delDjItemByDjid($djid)
    {
        return $this->crud->D('t_dj_item','djid',$djid);
    }

    public function getObjDjItems($djid)
    {
        $sql = 'select * from t_dj_item where djid=?';
        $items = $this->crud->L($sql,array($djid));
        if(!empty($items))
        {
            $arrObjItems = array();
            foreach ($items as $item)
            {
                $objItem = new djItem();
                $objItem->setDjitemid($item['djitemid']);
                $objItem->setDjid($item['djid']);
                $objItem->setWjTitleId($item['wj_title_id']);
                $objItem->setWjTitleItemId($item['wj_title_item_id']);
                $objItem->setDjAnswer($item['dj_answer']);
                $objItem->setDjScore($item['dj_score']);
                $objItem->setDjAdditional($item['dj_additional']);
				$objItem->df=$item['df'];

                $arrObjItems[] = $objItem;
                unset($objItem);
            }
            return $arrObjItems;
        }
        return null;
    }
}
?>