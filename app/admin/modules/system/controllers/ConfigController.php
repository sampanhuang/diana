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
        $dataget = $this->getRequest()->getParams();
        if ($dataget['show_data'] == 'treegrid_data') {
            $serviceConfig = new Admin_Service_Config();
            if($datatreegrid = $serviceConfig->makeTreegrid()){
                echo json_encode($datatreegrid);
            }
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
