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
     * 菜单索引
     */
    function indexAction(){
        $this->view->requestPaams = $requestPaams = $this->getRequest()->getParams();
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if(($requestPaams['menu_detail'] == 'yes')&&(!empty($requestPaams['menu_id']))){
            if(!$this->view->detail = $detail = $serviceManagerMenu->getDetailById($requestPaams['menu_id'])){
                $this->setMsgs($serviceManagerMenu->getMsgs());
            }
        }
        if ($requestPaams['data_ajax'] == 'treegrid_data') { // 获取数据
            if($tree = $serviceManagerMenu->makeTree()){
                echo json_encode($serviceManagerMenu->makeTreeGrid($tree));
            }
        }
    }

    /**
     * 添加菜单
     */
    function insertAction()
    {
        $dataGet = $this->getRequest()->getParams();
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            if($dataPost['handle'] == 'insert'){
                if($serviceManagerMenu->insert($dataPost)){
                    unset($dataPost);
                    $this->setMsgs('添加菜单成功');
                }else{
                    $this->setMsgs('添加菜单失败');
                    $this->setMsgs($serviceManagerMenu->getMsgs());
                }
            }
            $this->view->detail = $dataPost;
        }

        if($dataGet['data_ajax'] == 'make-tree-menu-father'){
            if($tree = $serviceManagerMenu->makeTree()){
                echo json_encode($serviceManagerMenu->makeComboTree($tree));
            }
        }
    }

    /**
     * 更新菜单
     */
    function updateAction()
    {
        $dataGet = $this->getRequest()->getParams();
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            if($dataPost['handle'] == 'update'){
                if($serviceManagerMenu->update($dataPost,$dataPost['menu_id'])){
                    $this->setMsgs('更新菜单成功');
                }else{
                    $this->setMsgs('更新菜单失败');
                    $this->setMsgs($serviceManagerMenu->getMsgs());
                }
            }
        }
        if($dataGet['data_ajax'] == 'make-tree-menu-father'){//上级树装输入框
            if($tree = $serviceManagerMenu->makeTree()){
                echo json_encode($serviceManagerMenu->makeComboTree($tree));
            }
        }else{//获取
            if($detail = $serviceManagerMenu->getDetailById($dataGet['menu_id'])){
                $this->view->detail = $detail;
            }else{
                $this->setMsgs($serviceManagerMenu->getMsgs());
            }
        }
    }

    /**
     * 菜单处理
     */
    function handleAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if($request['data_ajax'] == 'delete'){
            if($rowsAffected = $serviceManagerMenu->deleteById($request['menu_id'])){
                $dataAjax = array(
                    'stat' => 1,
                    'msgs' => '成功删除'.$rowsAffected.'条菜单！',
                );
            }else{
                $dataAjax = array(
                    'stat' => 0,
                    'msgs' => '删除失败！'.implode(';',$serviceManagerMenu->getMsgs()),
                );
            }
            echo json_encode($dataAjax);
        }
    }
}
