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
    function indexAction(){
        $dataGet = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'manager-user-datagrid');
        //获取角色数据
        $serviceManagerRole = new Admin_Service_ManagerRole();
        if (!$optionsRole = $serviceManagerRole->makeOptions()) {
            $this->setMsgs('你需要先创建角色才能够进行当前操作');
            return false;
        }
        $serviceManager = new Admin_Service_Manager();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceManager->filterFormSearch($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->optionsRole = $optionsRole;
        $this->view->dataget = $dataGet;
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if (empty($dataget['rows'])) {
            $this->view->pagesize = DIANA_DATAGRID_PAGESIZE_ADMIN;
        }
        if ($dataGet['data_ajax'] == 'manager-user-datagrid') {
            $grid = $serviceManager->makeDataGrid($dataGet['page'],$dataGet['rows'],$dataGet);
            echo json_encode($grid);
        }elseif ($dataGet['data_ajax'] == 'manager-user-delete-lock-unlock') {
            $json = array(
                'stat' => 0,
                'msgs' => '',
                'item' => array(),
            );
            echo json_encode($json);
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
            $grid = $serviceManagerLog->makeDataGrid($dataGet['page'],$dataGet['rows'],$dataGet);
            echo json_encode($grid);
        }elseif($dataGet['data_ajax'] == 'log-type-combobox'){
            $logTypeComboBox = $serviceManagerLog->makeLogTypeCombobox();
            echo json_encode($logTypeComboBox);
        }
    }
}
