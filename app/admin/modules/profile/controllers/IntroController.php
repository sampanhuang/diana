<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 下午11:35
 * To change this template use File | Settings | File Templates.
 */
class Profile_IntroController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 用户资料
     */
    function indexAction()
    {

    }

    /**
     * 日志查询
     */
    function logAction()
    {
        $dataGet = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'manager-log-datagrid');
        $serviceManagerLog = new Admin_Service_ManagerLog();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceManagerLog->filterFormSearch($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->dataget = $dataGet;
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if (empty($dataGet['rows'])) {
            $this->view->pagesize = DIANA_DATAGRID_PAGESIZE_ADMIN;
        }
        if(($dataGet['log_detail'] == 'yes')&&(!empty($dataGet['log_id']))){
            if(!$detailManagerLog = $serviceManagerLog->getDetailById($dataGet['log_id'])){
                $this->setMsgs($serviceManagerLog->getMsgs());
            }
            $this->view->detailManagerLog = $detailManagerLog;
        }
        if ($dataGet['data_ajax'] == 'manager-log-datagrid') {
            $dataGet['log_managerId'] = $this->currentManagerId;
            $grid = $serviceManagerLog->makeDataGrid($dataGet['page'],$dataGet['rows'],$dataGet);
            echo json_encode($grid);
        }elseif($dataGet['data_ajax'] == 'log-type-combobox'){
            $logTypeComboBox = $serviceManagerLog->makeLogTypeCombobox();
            echo json_encode($logTypeComboBox);
        }
    }

}