<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-1-8
 * Time: 上午10:52
 * To change this template use File | Settings | File Templates.
 */
class Profile_FriendController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 好友列表
     */
    function indexAction()
    {
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        $inputHandle['friend_master_memberId'] = $this->currentMemberId;
        $queryGrid = array('ajax_print' => 'json_2','req_handle' => 'datagrid-result');
        $queryGrid = array_merge($queryGrid,$request);
        $serviceMemberFriend = new Diana_Service_MemberFriend();
        $this->view->queryGrid = $queryGrid;
        $this->view->page = $page = $this->getRequest()->getParam('page',1);
        $this->view->pagesize = $pageSize = $this->getRequest()->getParam('row',DIANA_DATAGRID_PAGESIZE_ADMIN);
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceMemberFriend,
                'method' => 'makeDataGrid',
                'input' => $inputHandle,
            ),
        );
        $this->handleAjax($configHandle);

    }

    /**
     * 好友处理
     */
    function handleAction()
    {
        $this->view->request = $request = $inputHandle = $inputHandle = $this->getRequest()->getParams();
        $serviceMemberFriend = new Diana_Service_MemberFriend();
        $configHandle = array(
            'dissolution' => array(
                'object' => $serviceMemberFriend,
                'method' => 'dissolution',
                'input' => $inputHandle,
            ),
        );
        $this->handleAjax($configHandle);
    }

    /**
     * 交友申请发送纪录
     */
    function requestAction()
    {
        $serviceMemberConfig = new Client_Service_MemberConfig();
        $this->view->config_FriendRequestDeleteAfterHand = $serviceMemberConfig->getValByKey('friend_request-delete-after-hand',$this->currentMemberId);
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        $this->view->queryGrid = $queryGrid = array_merge(array('ajax_print' => 'json_2','req_handle' => 'datagrid-result'),$request);
        $inputHandle['request_dest'] = $this->currentMemberId;
        $serviceMemberFriendRequest = new Diana_Service_MemberFriendRequest();
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceMemberFriendRequest,
                'method' => 'makeDataGridOfInbox',
                'input' => $inputHandle,
            ),
        );
        $this->handleAjax($configHandle);

    }

    /**
     * 发送交友请求
     */
    function requestSendAction()
    {
        $this->view->queryColumns = array('member_name','member_email');
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        $inputHandle['request_source'] = $this->currentMemberId;
        $serviceMemberFriendRequest = new Diana_Service_MemberFriendRequest();
        $configHandle = array(
            'send' => array(
                'object' => $serviceMemberFriendRequest,
                'method' => 'send',
                'input' => $inputHandle,
            ),
        );
        if($this->handlePost($configHandle)){
            unset($this->view->request);
        }
    }


    /**
     * 申请处理
     */
    function requestHandleAction()
    {
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        $inputHandle['request_dest'] = $this->currentMemberId;
        $serviceMemberFriendRequest = new Diana_Service_MemberFriendRequest();
        $configHandle = array(
            'accept' => array(
                'object' => $serviceMemberFriendRequest,
                'method' => 'accept',
                'input' => $inputHandle,
            ),
            'reject' => array(
                'object' => $serviceMemberFriendRequest,
                'method' => 'reject',
                'input' => $inputHandle,
            ),
            'delete' => array(
                'object' => $serviceMemberFriendRequest,
                'method' => 'delete',
                'input' => $inputHandle,
            ),
        );
        $this->handleAjax($configHandle);
    }

}
