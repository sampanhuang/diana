<?php
/**
 * 申请 - 网站注册
 * User: sampan
 * Date: 13-8-1
 * Time: 下午7:36
 * To change this template use File | Settings | File Templates.
 */
class Apply_WebsiteRegisterController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    function indexAction()
    {
        $request = $this->view->request = $this->getRequest()->getParams();
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'datagrid-apply');
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        if ($this->getRequest()->isPost()) {
            $dataPost = $this->getRequest()->getPost();
            $this->view->dataPost = $dataPost;
            $queryGridPost = $serviceWebsiteApplyRegister->filterFormSearch($dataPost);
            $queryGrid = array_merge($queryGrid,$queryGridPost);
        }
        $this->view->queryGrid = $queryGrid;
        $this->view->page = $page = $this->getRequest()->getParam('page',1);
        $this->view->pagesize = $pageSize = $this->getRequest()->getParam('row',DIANA_DATAGRID_PAGESIZE_ADMIN);
        if ($request['data_ajax'] == 'datagrid-apply') {
            $this->view->registerPass = $registerPass = $this->getRequest()->getParam('register_pass',0);
            $condition = array("register_pass" => $registerPass);
            if($paginator = $serviceWebsiteApplyRegister->pageByCondition($page,$pageSize,$condition)){
                echo json_encode($paginator);
            }
        }
    }

    /**
     * 网站申请明细
     *
     * @return unknown
     */
    function detailAction()
    {
        $this->view->websiteId = $websiteId = $this->getRequest()->getUserParam('register_id',0);
        if(empty($registerId)){
        	$this->setMsgs("缺少参数，无法进行处理!");
        	return false;
        }
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        if($detailWebsiteApply = $serviceWebsiteApplyRegister->detailById($websiteId)){
            $this->view->detailWebsiteApply = $detailWebsiteApply;
        }
    }

    /**
     * 审核处理
     *
     */
    function judgeAction()
    {
        $this->view->registerId = $registerId = $this->getRequest()->getParam('register_id',0);
        $this->view->pass = $pass = $this->getRequest()->getParam('pass',0);
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        if(empty($registerId)){
        	$this->setMsgs("缺少参数，无法进行处理!");
        	return false;
        }
        if($pass == 1){
            if($rowsWebsite = $serviceWebsiteApplyRegister->accedeApply($registerId)){
                $this->view->rows = $rowsWebsite;
                $this->setMsgs('同意申请成功！');
            }else{
                $this->setMsgs('同意申请失败！');
                $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
            }
        }elseif($pass == 2){
            if($rowsWebsiteApply = $serviceWebsiteApplyRegister->demurApply($registerId)){
                $this->setMsgs('拒绝申请成功！');
                $this->view->rows = $rowsWebsiteApply;
            }else{
                $this->setMsgs('拒绝申请失败！');
                $this->setMsgs($serviceWebsiteApplyRegister->getMsgs());
            }
        }
    }
}