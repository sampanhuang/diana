<?php
/**
 * 功能菜单
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class Member_MemberMenuController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 菜单索引
     */
    function indexAction(){
        $dataGet = $this->getRequest()->getParams();
        $serviceMemberMenu = new Admin_Service_MemberMenu();
        if(($dataGet['menu_detail'] == 'yes')&&(!empty($dataGet['menu_id']))){
            if(!$this->view->detail = $detail = $serviceMemberMenu->getDetailById($dataGet['menu_id'])){
                $this->setMsgs($serviceMemberMenu->getMsgs());
            }
        }
        if ($dataGet['data_ajax'] == 'treegrid_data') { // 获取数据
            if($tree = $serviceMemberMenu->makeTree()){
                echo json_encode($serviceMemberMenu->makeTreeGrid($tree));
            }
        }
    }

    /**
     * 添加菜单
     */
    function insertAction()
    {
        $dataGet = $this->getRequest()->getParams();
        $serviceMemberMenu = new Admin_Service_MemberMenu();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            if($dataPost['handle'] == 'insert'){
                if($serviceMemberMenu->insert($dataPost)){
                    unset($dataPost);
                    $this->setMsgs('添加菜单成功');
                }else{
                    $this->setMsgs('添加菜单失败');
                    $this->setMsgs($serviceMemberMenu->getMsgs());
                }
            }
            $this->view->detail = $dataPost;
        }

        if($dataGet['data_ajax'] == 'make-tree-menu-father'){
            echo json_encode($serviceMemberMenu->makeComboTree($serviceMemberMenu->makeTree()));
        }
    }

    /**
     * 更新菜单
     */
    function updateAction()
    {
        $dataGet = $this->getRequest()->getParams();
        $serviceMemberMenu = new Admin_Service_MemberMenu();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            if($dataPost['handle'] == 'update'){
                if($serviceMemberMenu->update($dataPost,$dataPost['menu_id'])){
                    $this->setMsgs('更新菜单成功');
                }else{
                    $this->setMsgs('更新菜单失败');
                    $this->setMsgs($serviceMemberMenu->getMsgs());
                }
            }
        }
        if($dataGet['data_ajax'] == 'make-tree-menu-father'){//上级树装输入框
            if($tree = $serviceMemberMenu->makeTree()){
                echo json_encode($serviceMemberMenu->makeComboTree($tree));
            }
        }else{//获取
            if($detail = $serviceMemberMenu->getDetailById($dataGet['menu_id'])){
                $this->view->detail = $detail;
            }else{
                $this->setMsgs($serviceMemberMenu->getMsgs());
            }
        }
    }

    /**
     * 菜单处理
     */
    function handleAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceMemberMenu = new Admin_Service_MemberMenu();
        if($request['data_ajax'] == 'delete'){
            if($rowsAffected = $serviceMemberMenu->deleteById($request['menu_id'])){
                $dataAjax = array(
                    'stat' => 1,
                    'msgs' => '成功删除'.$rowsAffected.'条菜单！',
                );
            }else{
                $dataAjax = array(
                    'stat' => 0,
                    'msgs' => '删除失败！'.implode(';',$serviceMemberMenu->getMsgs()),
                );
            }
            echo json_encode($dataAjax);
        }
    }
}
