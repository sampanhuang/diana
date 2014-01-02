<?php
/**
 * 申请 - 网站注册
 * User: sampan
 * Date: 13-8-1
 * Time: 下午7:36
 * To change this template use File | Settings | File Templates.
 */
class Apply_WebsiteRegisterController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    function indexAction()
    {
        $request = $this->view->request = $this->getRequest()->getParams();
        $queryGrid = array('ajax_print' => 'json_2','req_handle' => 'datagrid-result');
        $queryGrid = array_merge($queryGrid,$request);
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        $this->view->queryGrid = $queryGrid;
        $this->view->page = $page = $this->getRequest()->getParam('page',1);
        $this->view->pagesize = $pageSize = $this->getRequest()->getParam('row',DIANA_DATAGRID_PAGESIZE_ADMIN);
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceWebsiteApplyRegister,
                'method' => 'makeDataGrid',
            ),
        );
        $this->handleAjax($configHandle);
    }

    /**
     * 网站申请明细
     *
     * @return unknown
     */
    function detailAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] = 'query'){
                if(!$detailMember = $serviceWebsiteApplyRegister->getDetail($request['query_column'],$request['query_key'])){
                    $this->setMsgs('查询失败');
                    $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
                }
            }
        }
        if((empty($detailMember))&&(!empty($request['register_id']))){
            if(!$detailMember = $serviceWebsiteApplyRegister->getDetail('id',$request['register_id'])){
                $this->setMsgs('查询失败');
                $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
            }
        }
        $this->view->queryColumns = array('register_id' ,'website_id');
        $this->view->detail = $detailMember;
        $this->view->request = $request;

    }

    /**
     * 审核处理
     *
     */
    function handleAction()
    {
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        $configHandle = array(
            'delete' => array(
                'object' => $serviceWebsiteApplyRegister,
                'method' => 'deleteWithAjax',
            ),
            'accept' => array(
                'object' => $serviceWebsiteApplyRegister,
                'method' => 'acceptApply',
            ),
            'reject' => array(
                'object' => $serviceWebsiteApplyRegister,
                'method' => 'rejectApply',
            ),
        );
        $this->handleAjax($configHandle);
    }
}