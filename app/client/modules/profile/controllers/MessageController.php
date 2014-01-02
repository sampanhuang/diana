<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 下午11:14
 * To change this template use File | Settings | File Templates.
 */
class Profile_MessageController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 收件箱
     */
    function inboxAction(){
        $dataGet = $this->view->dataget = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'datagrid_msg');
        $serviceManagerMsg = new Admin_Service_ManagerMsg();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceManagerMsg->filterFormSearchAboutInbox($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_msg') {
            $dataGet['inbox_managerId'] = $this->currentManagerId;
            if($dataGrid = $serviceManagerMsg->makeDataGridWIthInbox($dataGet['page'],$dataGet['rows'],$dataGet)){
                echo json_encode($dataGrid);
            }
        }
        //获取详细信息
        if(($dataGet['inbox_detail'] == 'yes')&&(!empty($dataGet['inbox_id']))){
            if(!$this->view->detailInbox = $serviceManagerMsg->readFromInbox($dataGet['inbox_id'])){
                 $this->setMsgs($serviceManagerMsg->getMsgs());
            }
        }
    }


    /**
     * 收件处理
     */
    function inboxHandleAction()
    {
        $this->view->dataGet = $dataGet = $this->getRequest()->getParams();
        $json = array('stat' => 0 ,'msgs' => '');
        if(empty($dataGet['inbox_id'])){
            $json['msgs'] = '无法接收到inbox_id';
        }else{
            $serviceManagerMsg = new Admin_Service_ManagerMsg();
            if($dataGet['data_ajax'] == 'delete'){
                if($countDelete = $serviceManagerMsg->deleteWithInbox($dataGet['inbox_id'],$this->currentManagerId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功删除'.$countDelete.'条消息';
                }else{
                    $json['msgs'] = '删除失败;'.implode(';',$serviceManagerMsg->getMsgs());
                }
            }elseif($dataGet['data_ajax'] == 'markread'){
                if($countMarkRead = $serviceManagerMsg->markReadWithInbox($dataGet['inbox_id'],$this->currentManagerId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功将'.$countMarkRead.'条消息的状态变更为已读';
                }else{
                    $json['msgs'] = '已读状态变更失败;'.implode(';',$serviceManagerMsg->getMsgs());
                }
            }
        }
        echo json_encode($json);
    }

    /**
     * 发件箱
     */
    function outboxAction()
    {
        $dataGet = $this->view->dataget = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'datagrid_msg');
        $serviceManagerMsg = new Admin_Service_ManagerMsg();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceManagerMsg->filterFormSearchAboutOutbox($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_msg') {
            $dataGet['outbox_managerId'] = $this->currentManagerId;
            $dataGet['outbox_send_stat'] = 1;//1是已发送，2是草稿
            if($dataGrid = $serviceManagerMsg->makeDataGridWIthOutbox($dataGet['page'],$dataGet['rows'],$dataGet)){
                echo json_encode($dataGrid);
            }
        }
        //获取详细信息
        if(($dataGet['outbox_detail'] == 'yes')&&(!empty($dataGet['outbox_id']))){
            if(!$this->view->detailOutbox = $serviceManagerMsg->readFromOutbox($dataGet['outbox_id'])){
                $this->setMsgs($serviceManagerMsg->getMsgs());
            }
        }
    }

    /**
     * 发件处理-删除
     */
    function outboxHandleAction()
    {
        $this->view->dataGet = $dataGet = $this->getRequest()->getParams();
        $json = array('stat' => 0 ,'msgs' => '');
        if(empty($dataGet['outbox_id'])){
            $json['msgs'] = '无法接收到outbox_id';
        }else{
            if($dataGet['data_ajax'] == 'delete'){
                $serviceManagerMsg = new Admin_Service_ManagerMsg();
                if($countDelete = $serviceManagerMsg->deleteWithOutbox($dataGet['outbox_id'],$this->currentManagerId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功删除'.$countDelete.'条消息';
                }else{
                    $json['msgs'] = '删除失败;'.implode($serviceManagerMsg->getMsgs());
                }
            }
        }
        echo json_encode($json);
    }

    /**
     * 草稿箱
     */
    function draftAction()
    {
        $dataGet = $this->view->dataget = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'datagrid_msg');
        $serviceManagerMsg = new Admin_Service_ManagerMsg();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceManagerMsg->filterFormSearchAboutDraft($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_msg') {
            $dataGet['outbox_managerId'] = $this->currentManagerId;
            $dataGet['outbox_send_stat'] = 2;//1是已发送，2是草稿
            if($dataGrid = $serviceManagerMsg->makeDataGridWithOutbox($dataGet['page'],$dataGet['rows'],$dataGet)){
                echo json_encode($dataGrid);
            }
        }
    }

    /**
     * 草稿箱处理
     */
    function draftHandleAction()
    {
        $this->view->dataGet = $dataGet = $this->getRequest()->getParams();
        $json = array('stat' => 0 ,'msgs' => '');
        if(empty($dataGet['outbox_id'])){
            $json['msgs'] = '无法接收到outbox_id';
        }else{
            if($dataGet['data_ajax'] == 'delete'){
                $serviceManagerMsg = new Admin_Service_ManagerMsg();
                if($countDelete = $serviceManagerMsg->deleteWithOutbox($dataGet['outbox_id'],$this->currentManagerId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功删除'.$countDelete.'条消息';
                }else{
                    $json['msgs'] = '删除失败;'.implode($serviceManagerMsg->getMsgs());
                }
            }
        }
        echo json_encode($json);
    }

    /**
     * 发送消息
     */
    function sendAction()
    {
        $this->view->dataGet = $dataGet = $this->getRequest()->getParams();
        $serviceManagerMsg = new Admin_Service_ManagerMsg();
        //有无提交post，如果提交了，就保存
        if ($this->getRequest()->isPost()) {
            $this->view->detail = $dataPost = $this->getRequest()->getPost();
            if($dataPost['handle_type'] == 'save' || $dataPost['handle_type'] == 'send'){
                $dataPost['msg_source'] = $this->currentManagerId;
                if(!$detailManagerMsgOutbox = $serviceManagerMsg->save($dataPost,$dataPost['outbox_id'])){
                    $this->setMsgs('草稿保存失败');
                    $this->setMsgs($serviceManagerMsg->getMsgs());
                }else{
                    if($dataPost['handle_type'] == 'send'){
                        if(!$serviceManagerMsg->send($detailManagerMsgOutbox)){
                            $this->setMsgs('消息发送失败');
                            $this->setMsgs($serviceManagerMsg->getMsgs());
                        }else{
                            $this->setMsgs('消息发送成功');
                            unset($this->view->detail);
                        }
                    }else{
                        $this->setMsgs('消息草稿保存成功');
                        unset($this->view->detail);
                    }
                }
            }
        }
        if(empty($dataGet['data_ajax'])){
            $this->view->outboxId = $outboxId = $dataGet['outbox_id'];
            if(!empty($outboxId)){
                if($detailManagerMsgOutbox = $serviceManagerMsg->readFromOutbox($outboxId)){
                    if($detailManagerMsgOutbox['outbox_msg_send_time'] == 0){
                        $this->view->detail = $detailManagerMsgOutbox;
                    }
                }else{
                    $this->setMsgs('消息草稿读取失败');
                    $this->setMsgs($serviceManagerMsg->getMsgs());
                }
            }
        }elseif ($dataGet['data_ajax'] == 'msg_dest') {//收件人树状菜单
            $serviceManager = new Admin_Service_Manager();
            if($treeManager = $serviceManager->makeTree()){
                echo json_encode($treeManager);
            }
        }
    }
}
