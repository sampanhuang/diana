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
        $request['website_memberId'] = $this->currentMemberId;
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
                'input' => $request,
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
            if(!$detailMember = $serviceWebsiteApplyRegister->getDetail('register_id',$request['register_id'])){
                $this->setMsgs('查询失败');
                $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
            }
        }
        $this->view->queryColumns = array('register_id' ,'website_id');
        $this->view->detail = $detailMember;
        $this->view->request = $request;

    }

    /**
     * 提交申请
     */
    function postAction()
    {
        //获取提交数据
        $this->view->request = $this->view->detail = $request = $this->getRequest()->getParams();
        //获取网站分类代码
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->optionsFatherOfCategory = $optionsFatherOfCategory =  $serviceWebsiteCategory->getOptionsWithFather();
        $this->view->optionsSonOfCategory = $optionsSonOfCategory =  $serviceWebsiteCategory->getOptionsWithSon();
        //获取网站地区代码
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $this->view->optionsFatherOfArea = $optionsFatherOfArea =  $serviceWebsiteArea->getOptionsWithFather();
        $this->view->optionsSonOfArea = $optionsSonOfArea =  $serviceWebsiteArea->getOptionsWithSon();
        //处理提交请求
        if ($this->getRequest()->isPost()) {
            //提交网站注册申请
            $request['website_memberEmail'] = $this->currentMemberEmail;
            $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
            if($serviceWebsiteApplyRegister->postApply($request)){
                $this->setMsgs('提交成功，我会们在你的网站审核成功后以电子邮件的形式通知你');
                unset($this->view->request);
            }else{
                $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
            }
        }
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