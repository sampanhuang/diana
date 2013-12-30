<?php
/**
 * 会员资料
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class Member_IndexController extends Admin_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function indexAction(){
        $request = $this->getRequest()->getParams();
        $queryGrid = array('ajax_print' => 'json','req_handle' => 'datagrid-result');
        $queryGrid = array_merge($queryGrid,$request);
        //获取角色数据
        $serviceMemberRole = new Admin_Service_MemberRole();
        if (!$optionsRole = $serviceMemberRole->makeOptions()) {
            $this->setMsgs('你需要先创建角色才能够进行当前操作');
            return false;
        }
        $serviceMember = new Admin_Service_Member();
        $this->view->optionsRole = $optionsRole;
        $this->view->dataget = $request;
        $this->view->queryGrid = $queryGrid;
        $this->view->pagesize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN) ;
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceMember,
                'method' => 'makeDataGrid',
            ),
        );
        $this->handleAjax($configHandle);
    }

    /**
     * 处理
     */
    function handleAction()
    {
        $serviceMember = new Admin_Service_Member();
        $configHandle = array(
            'delete' => array(
                'object' => $serviceMember,
                'method' => 'delete',
            ),
            'lock' => array(
                'object' => $serviceMember,
                'method' => 'lock',
            ),
            'unlock' => array(
                'object' => $serviceMember,
                'method' => 'unlock',
            ),
            'pass' => array(
                'object' => $serviceMember,
                'method' => 'pass',
            ),
        );
        $this->handleAjax($configHandle);
    }

    /**
     * 会员明细
     */
    function detailAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceMember = new Diana_Service_Member();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] = 'query'){
                if(!$detailMember = $serviceMember->getDetail($request['query_column'],$request['query_key'])){
                    $this->setMsgs('查询失败');
                    $this->setMsgs($serviceMember->getMsgs());
                }
            }
        }
        if((empty($detailMember))&&(!empty($request['member_id']))){
            $request['query_column'] = 'id';
            $request['query_key'] = $request['member_id'];
            if(!$detailMember = $serviceMember->getDetail('id',$request['member_id'])){
                $this->setMsgs('查询失败');
                $this->setMsgs($serviceMember->getMsgs());
            }
        }
        $this->view->queryColumns = array('id','name','email');
        $this->view->detail = $detailMember;
        $this->view->request = $request;
    }

    /**
     * 会员日志
     */
    function logAction()
    {
        $dataGet = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'member-log-datagrid');
        $serviceMemberLog = new Diana_Service_MemberLog();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->datapost = $dataPost;
            $queryGridPost = $serviceMemberLog->filterFormSearch($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->dataget = $dataGet;
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if (empty($dataGet['rows'])) {
            $this->view->pagesize = DIANA_DATAGRID_PAGESIZE_ADMIN;
        }
        if(($dataGet['log_detail'] == 'yes')&&(!empty($dataGet['log_id']))){
            if(!$detailMemberLog = $serviceMemberLog->getDetailById($dataGet['log_id'])){
                $this->setMsgs($serviceMemberLog->getMsgs());
            }
            $this->view->detailMemberLog = $detailMemberLog;
        }
        if ($dataGet['data_ajax'] == 'member-log-datagrid') {
            $grid = $serviceMemberLog->makeDataGrid($dataGet['page'],$dataGet['rows'],$dataGet);
            echo json_encode($grid);
        }elseif($dataGet['data_ajax'] == 'log-type-combobox'){
            $logTypeComboBox = $serviceMemberLog->makeLogTypeCombobox();
            echo json_encode($logTypeComboBox);
        }
    }

}
