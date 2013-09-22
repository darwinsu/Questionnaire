<?php
include_once(APP_PATH . '/application/models/dj/dao/djDAO.class.php');
include_once(APP_PATH . '/application/models/dj/service/djItemService.class.php');
class djService
{
    public $djDAO;
    public $pageInfo;

    public function __construct()
    {
        $this->djDAO = new djDAO();
    }

    public function getPageInfo()
    {
        return $this->pageInfo;
    }

    public function getDjListPage($page=1,$pageSize=10)
    {
        $arrDjObj = $this->djDAO->getDjListPage($page,$pageSize);
        $this->pageInfo = $this->djDAO->pdoe->GetPageInfo();
        return $arrDjObj;
    }

    public function getMyDjListPage($uid,$page=1,$pageSize=10)
    {
        $arrDjObj = $this->djDAO->getMyDjListPage($uid,$page,$pageSize);
        $this->pageInfo = $this->djDAO->pdoe->GetPageInfo();
        return $arrDjObj;
    }

    public function delDj($djid)
    {
        $djItemService = new djItemService();
        $djItemService->delDjItemByDjid($djid);

        return $this->djDAO->delDj($djid);
    }
	public function isDj($wjid,$uid)
    {
         
            $arrobjdj = $this->djDAO->getDjByWjidUid($wjid,$uid);
            if(!empty($arrobjdj))
            {
                return 1;
            }else{
				return 0;
			}
    }
    public function startDj($wjid,$uid,$isRepeat)
    {
        if(!$isRepeat)
        {
            $arrobjdj = $this->djDAO->getDjByWjidUid($wjid,$uid);
            if(!empty($arrobjdj))
            {
                return -1;
            }
        }
        return $this->djDAO->createDj($wjid,$uid);
    }

    public function submitDj($wjid,$djid,$arrDjItems)
    {
        //记录提交时间
        $this->djDAO->setDjOverTime($djid,$arrDjItems['ttime']);unset($arrDjItems['ttime']);
		$this->djDAO->setDjAnonymous(array('is_anonymous'=>$_POST['is_anonymous']
										  ,'djid'=>$djid));
        //保存答卷记录
        $djitems = $this->djDAO->saveDjItem($wjid,$djid,$arrDjItems);

        //调用问卷方法
        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($wjid);
        if(!empty($arrDjItems))
        {
            foreach($arrDjItems as $key => $item)
            {
				if($item['wj_title_id']&&$item['wj_title_item_id']){
                //取得问卷分数
                $score = $wj->assert($item['wj_title_id'],$item['wj_title_item_id'],$item['dj_additional'],$item['dj_answer']);
                //保存得分
                $this->djDAO->djItemAssert($djitems[$key],$score);
				}
            }
        }
        return true;

    }

    public function getDj($djid)
    {
        return $this->djDAO->getDj($djid);
    }

}
?>