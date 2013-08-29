<?php
/**
 * 功能菜单
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class System_ManagerController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function selectAction(){
        $this->view->page = $page = $this->getRequest()->getUserParam('page',0);
        $serviceManager = new Admin_Service_Manager();
        $this->view->rowsManager = $serviceManager->pageByCondition($page,1000);
        $serviceManagerRole = new Admin_Service_ManagerRole();
        if($rowsRole = $serviceManagerRole->pageByCondition($page,1000)){
            $optionsRole = array();
            foreach($rowsRole as $rowRole){
                $optionsRole[$rowRole['role_id']] = $rowsRole['role_name'];
            }
            $this->view->optionsRole = $optionsRole;
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

    function detailAction()
    {

    }

    function logLoginAction()
    {

    }
}
