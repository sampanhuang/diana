<?php
/**
 * 功能菜单
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class System_ManagerRoleController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function indexAction(){
        $dataGet = $this->view->dataget = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'datagrid_role');
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $serviceManagerRole = new Admin_Service_ManagerRole();
            $queryGridPost = $serviceManagerRole->filterFormSearch($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_role') {
            $serviceManagerRole = new Admin_Service_ManagerRole();
            if($dataGrid = $serviceManagerRole->makeDataGrid($dataGet['page'],$dataGet['rows'],$dataGet)){
                echo json_encode($dataGrid);
            }
        }


    }

    /**
     * 插入新角色
     */
    function insertAction()
    {
        $dataGet = $this->getRequest()->getParams();
        //有无提交post，如果提交了，就保存
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $dataPost['msg_source'] = $this->currentManagerId;
            $serviceManagerRole = new Admin_Service_ManagerRole();
            if ($detailManagerRole = $serviceManagerRole->create($dataPost)) {
                $this->setMsgs('添加成功，点击<a href="javascript:addTabWithParent(\'系统管理/权限角色/明细详情\',\'/system/manager-role/detail/role_id/'.$detailManagerRole['role_id'].'\',true)" >这里</a>查看新添加的数据');
            }else{
                $this->setMsgs($serviceManagerRole->getMsgs());
                $this->view->detail = $dataPost;
            }
        }
        //获取所有资源
        $serviceManagerResource = new Admin_Service_ManagerMenu();
        if ($treeMenu = $serviceManagerResource->makeTree()) {
            $this->view->treeMenu = $treeMenu;
        }
        //ajax判断
        if ($dataGet['data_ajax'] == 'isexistwithname') {//判断这个label是否能用
            $json = array(
                'stat' => 0,//状态
                'msgs' => '',//提示消息
                'item' => array(),//项目
            );
            if (empty($dataGet['role_name'])) {
                $json['msgs'] = '参数不能为空';
            }else{
                $serviceManagerRole = new Admin_Service_ManagerRole();
                if (!$serviceManagerRole->isExistsWithName($dataGet['role_name'])) {
                    $json['msgs'] = '请重新命名，'.$dataGet['role_name'].'已经被使用';
                }else{
                    $json['stat'] = 1;
                }
            }
            echo json_encode($json);
        }
    }

    /**
     * 更新旧角色
     * @return bool
     */
    function updateAction()
    {
        $this->showBreadcrumb = true;
        $dataGet = $this->getRequest()->getParams();
        $this->view->dataGet = $dataGet;
        $roleId = $dataGet['role_id'];
        if (empty($roleId)) {
            $this->setMsgs('参数role_id不能为空');
            return false;
        }
        $detailManagerRole = array();
        //有无提交post，如果提交了，就保存
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $serviceManagerRole = new Admin_Service_ManagerRole();
            if ($detailManagerRole = $serviceManagerRole->modify($dataPost,$roleId)) {
                $this->setMsgs('保存成功');
            }else{
                $this->setMsgs($serviceManagerRole->getMsgs());
            }
        }
        //如果之前没有$detailManagerRole就要获取
        if (empty($detailManagerRole)) {
            //获取角色信息
            $serviceManager = new Admin_Service_Manager();
            $detailManagerRole = $serviceManager->getRoleById($roleId);
        }
        if (empty($detailManagerRole)) {
            $this->setMsgs('无法获取详细的角色数据');
            return false;
        }
        $this->view->detail = $detailManagerRole;
        //获取所有资源
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if ($treeMenu = $serviceManagerMenu->makeTree()) {
            $this->view->treeMenu = $treeMenu;
        }
        //ajax判断
        if ($dataGet['data_ajax'] == 'isexistwithname') {//判断这个label是否能用
            $json = array('stat' => 0);
            if (empty($dataGet['role_id']) || empty($dataGet['role_name'])) {
                $json['msgs'] = '参数不能为空';
            }else{
                $serviceManagerRole = new Admin_Service_ManagerRole();
                if (!$serviceManagerRole->isExistsWithName($dataGet['role_name'],$dataGet['role_id'])) {
                    $json['msgs'] = '请重新命名，'.$dataGet['role_name'].'已经被使用';
                }else{
                    $json['stat'] = 1;
                }
            }
            echo json_encode($json);
        }
    }

    /**
     * 删除权限组
     *
     */
    function deleteAction()
    {
        $dataget = $this->getRequest()->getParams();
        if ($dataget['data_ajax'] == 'manager-role-delete-lock-unlock') {//判断这个label是否能用
            $json = array('stat' => 0 );
            $serviceManagerRole = new Admin_Service_ManagerRole();
            if ($serviceManagerRole->deleteById($dataget['role_id'])) {
                $json['stat'] = 1;
                $json['msgs'] = '删除成功!';
            }else{
                $json['msgs'] = implode(';',$serviceManagerRole->getMsgs());
            }
            echo json_encode($json);
        }
    }

    /**
     * 锁定角色
     *
     */
    function lockAction()
    {
        $dataget = $this->getRequest()->getParams();
        if ($dataget['data_ajax'] == 'manager-role-delete-lock-unlock') {//判断这个label是否能用
            $json = array('stat' => 0 );
            $serviceManagerRole = new Admin_Service_ManagerRole();
            if ($serviceManagerRole->lockById($dataget['role_id'],$dataget['role_lock_time'])) {
                $json['stat'] = 1;
                $json['msgs'] = '锁定成功!';
            }else{
                $json['msgs'] = implode(';',$serviceManagerRole->getMsgs());
            }
            echo json_encode($json);
        }
    }

    /**
     * 解锁角色
     *
     */
    function unlockAction()
    {
        $dataget = $this->getRequest()->getParams();
        if ($dataget['data_ajax'] == 'manager-role-delete-lock-unlock') {//判断这个label是否能用
            $json = array('stat' => 0 );
            $serviceManagerRole = new Admin_Service_ManagerRole();
            if ($serviceManagerRole->lockById($dataget['role_id'])) {
                $json['stat'] = 1;
                $json['msgs'] = '解锁成功!';
            }else{
                $json['msgs'] = implode(';',$serviceManagerRole->getMsgs());
            }
            echo json_encode($json);
        }
    }


    function detailAction()
    {
        $dataget = $this->getRequest()->getParams();
        $serviceManager = new Admin_Service_Manager();
        if(!$this->view->detailRole = $serviceManager->getRoleById($dataget['role_id'])){
            $this->setMsgs($serviceManager->getMsgs());
            return false;
        }
    }

    function logLoginAction()
    {

    }
}
