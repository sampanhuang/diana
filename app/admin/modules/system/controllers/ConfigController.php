<?php
/**
 * 参数配置
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class System_ConfigController extends Admin_Controller_Action
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
        $serviceConfig = new Admin_Service_Config();
        $this->view->rows = $serviceConfig->indexByFather($fatherId);
        $this->view->fatherRow = $serviceConfig->getDetail($fatherId);


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
