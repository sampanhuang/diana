<?php
/**
 * 功能菜单
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class System_MenuController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function indexAction(){
        $this->view->fatherId = $fatherId = $this->getRequest()->getUserParam('father_id',0);
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        $this->view->rows = $serviceManagerMenu->selectByFather($fatherId);
        if(!empty($fatherId)){
            $this->view->fatherRow = $serviceManagerMenu->selectById($fatherId);
        }

    }

    function insertAction()
    {

    }

    function updateAction()
    {

    }

    function deleteAction()
    {

    }
}
