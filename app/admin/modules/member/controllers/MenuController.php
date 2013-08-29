<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-9
 * Time: 上午12:21
 * To change this template use File | Settings | File Templates.
 */
class Member_MenuController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    function indexAction()
    {
        $this->view->fatherId = $fatherId = $this->getRequest()->getUserParam('father_id',0);
        $serviceManagerMenu = new Diana_Service_MemberMenu();
        $this->view->rows = $serviceManagerMenu->selectByFather($fatherId);
        if(!empty($fatherId)){
            $this->view->fatherRow = $serviceManagerMenu->selectById($fatherId);
        }
    }
}
