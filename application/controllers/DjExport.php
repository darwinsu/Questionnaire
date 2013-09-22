<?php
class DjExportController extends Yaf_Controller_Abstract
{

    public $djService;

    public function init()
    {
        include_once(APP_PATH . '/application/models/dj/service/djService.class.php');
        $this->djService = new djService();
    }

    public function indexAction()
    {
        $djid = $this->getRequest()->getParam('djid');

        $objDj = $this->djService->getDj($djid);

        include_once(APP_PATH . '/application/models/quest.php');
        $wj = new questModel($objDj->getWjid());

        $subjects = $wj->getWjSubjects();
        if(!empty($subjects))
        {
            include_once(APP_PATH . '/application/models/subject.php');
            foreach ($subjects as $key => $subject)
            {
                $items = new subjectModel($subject['id']);
                $subjects[$key]['items'] = $items->getSubjectItems();
            }

        }

        $this->getView()->assign('objDj',$objDj);
        $this->getView()->assign('wj',$wj);
        $this->getView()->assign('subjects',$subjects);
    }

}
?>