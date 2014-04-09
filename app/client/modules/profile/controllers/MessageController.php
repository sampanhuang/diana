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
        $serviceMemberMsg = new Client_Service_MemberMsg();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceMemberMsg->filterFormSearchAboutInbox($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_msg') {
            $dataGet['inbox_manId'] = $this->currentMemberId;
            if($dataGrid = $serviceMemberMsg->makeDataGridWIthInbox($dataGet['page'],$dataGet['rows'],$dataGet)){
                echo json_encode($dataGrid);
            }
        }
        //获取详细信息
        if(($dataGet['inbox_detail'] == 'yes')&&(!empty($dataGet['inbox_id']))){
            if(!$this->view->detailInbox = $serviceMemberMsg->readFromInbox($dataGet['inbox_id'])){
                 $this->setMsgs($serviceMemberMsg->getMsgs());
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
            $serviceMemberMsg = new Client_Service_MemberMsg();
            if($dataGet['data_ajax'] == 'delete'){
                if($countDelete = $serviceMemberMsg->deleteWithInbox($dataGet['inbox_id'],$this->currentMemberId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功删除'.$countDelete.'条消息';
                }else{
                    $json['msgs'] = '删除失败;'.implode(';',$serviceMemberMsg->getMsgs());
                }
            }elseif($dataGet['data_ajax'] == 'markread'){
                if($countMarkRead = $serviceMemberMsg->markReadWithInbox($dataGet['inbox_id'],$this->currentMemberId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功将'.$countMarkRead.'条消息的状态变更为已读';
                }else{
                    $json['msgs'] = '已读状态变更失败;'.implode(';',$serviceMemberMsg->getMsgs());
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
        $serviceMemberMsg = new Client_Service_MemberMsg();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceMemberMsg->filterFormSearchAboutOutbox($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_msg') {
            $dataGet['outbox_manId'] = $this->currentMemberId;
            $dataGet['outbox_send_stat'] = 1;//1是已发送，2是草稿
            if($dataGrid = $serviceMemberMsg->makeDataGridWIthOutbox($dataGet['page'],$dataGet['rows'],$dataGet)){
                echo json_encode($dataGrid);
            }
        }
        //获取详细信息
        if(($dataGet['outbox_detail'] == 'yes')&&(!empty($dataGet['outbox_id']))){
            if(!$this->view->detailOutbox = $serviceMemberMsg->readFromOutbox($dataGet['outbox_id'])){
                $this->setMsgs($serviceMemberMsg->getMsgs());
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
                $serviceMemberMsg = new Client_Service_MemberMsg();
                if($countDelete = $serviceMemberMsg->deleteWithOutbox($dataGet['outbox_id'],$this->currentMemberId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功删除'.$countDelete.'条消息';
                }else{
                    $json['msgs'] = '删除失败;'.implode($serviceMemberMsg->getMsgs());
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
        $serviceMemberMsg = new Client_Service_MemberMsg();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceMemberMsg->filterFormSearchAboutDraft($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($dataGet['rows'])){$dataGet['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $dataGet['rows'];
        //取数据源
        if ($dataGet['data_ajax'] == 'datagrid_msg') {
            $dataGet['outbox_manId'] = $this->currentMemberId;
            $dataGet['outbox_send_stat'] = 2;//1是已发送，2是草稿
            if($dataGrid = $serviceMemberMsg->makeDataGridWithOutbox($dataGet['page'],$dataGet['rows'],$dataGet)){
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
                $serviceMemberMsg = new Client_Service_MemberMsg();
                if($countDelete = $serviceMemberMsg->deleteWithOutbox($dataGet['outbox_id'],$this->currentMemberId)){
                    $json['stat'] = 1;
                    $json['msgs'] = '成功删除'.$countDelete.'条消息';
                }else{
                    $json['msgs'] = '删除失败;'.implode($serviceMemberMsg->getMsgs());
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
        $this->view->queryGridOfDest = $queryGridOfDest = array('ajax_print' => 'json','req_handle' => 'combotree-dest');
        $this->view->dataGet = $dataGet = $input = $this->getRequest()->getParams();
        //处理AJAX
        $configHandleAjax = array(
            'combotree-dest' => array(
                'object' => new Diana_Service_MemberFriend(),
                'method' => 'makeTree',
                'input'  => array_merge($input,array('friend_master_memberId' => $this->currentMemberId)),
            ),
        );

        $serviceMemberMsg = new Client_Service_MemberMsg();
        //有无提交post，如果提交了，就保存
        if ($this->getRequest()->isPost()) {
            $this->view->detail = $dataPost = $this->getRequest()->getPost();
            if($dataPost['handle_type'] == 'save' || $dataPost['handle_type'] == 'send'){
                $dataPost['msg_source'] = $this->currentMemberId;
                if(!$detailMemberMsgOutbox = $serviceMemberMsg->save($dataPost,$dataPost['outbox_id'])){
                    $this->setMsgs('草稿保存失败');
                    $this->setMsgs($serviceMemberMsg->getMsgs());
                }else{
                    if($dataPost['handle_type'] == 'send'){
                        if(!$serviceMemberMsg->send($detailMemberMsgOutbox)){
                            $this->setMsgs('消息发送失败');
                            $this->setMsgs($serviceMemberMsg->getMsgs());
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

        $this->handleAjax($configHandleAjax);
        if(empty($dataGet['req_handle'])){
            $this->view->outboxId = $outboxId = $dataGet['outbox_id'];
            if(!empty($outboxId)){
                if($detailMemberMsgOutbox = $serviceMemberMsg->readFromOutbox($outboxId)){
                    if($detailMemberMsgOutbox['outbox_msg_send_time'] == 0){
                        $this->view->detail = $detailMemberMsgOutbox;
                    }
                }else{
                    $this->setMsgs('消息草稿读取失败');
                    $this->setMsgs($serviceMemberMsg->getMsgs());
                }
            }
        }
    }
}
