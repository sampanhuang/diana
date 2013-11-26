<?php
/**
 * 功能菜单
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class System_ManagerMenuController extends Admin_Controller_Action
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
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if(($dataGet['menu_detail'] == 'yes')&&(!empty($dataGet['menu_id']))){
            if(!$this->view->detail = $detail = $serviceManagerMenu->getDetailById($dataGet['menu_id'])){
                $this->setMsgs($serviceManagerMenu->getMsgs());
            }
        }
        if ($dataGet['data_ajax'] == 'treegrid_data') { // 获取数据
            if($tree = $serviceManagerMenu->makeTree()){
                echo json_encode($serviceManagerMenu->makeTreeGrid($tree));
            }
        }
    }

    function insertAction()
    {
        $dataGet = $this->getRequest()->getParams();
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if($dataGet['data_ajax'] == 'make-tree-menu-father'){
            if($tree = $serviceManagerMenu->makeTree()){
                echo json_encode($serviceManagerMenu->makeComboTree($tree));
            }
        }
    }

    function updateAction()
    {

    }
}
