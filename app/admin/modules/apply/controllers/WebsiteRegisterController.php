<?php
/**
 * Created by JetBrains PhpStorm.
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
        $dataGet = $this->view->dataget = $this->getRequest()->getParams();
        $this->view->page = $page = $this->getRequest()->getParam('page',1);
        $this->view->pagesize = $pageSize = $this->getRequest()->getParam('row',DIANA_DATAGRID_PAGESIZE_ADMIN);
        if ($dataGet['data_ajax'] == 'datagrid_role') {
            $this->view->registerPass = $registerPass = $this->getRequest()->getParam('page',0);
            $condition = array("register_pass" => $registerPass);
            $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
            if($paginator = $serviceWebsiteApplyRegister->pageByCondition($page,$pageSize,$condition)){
                echo json_encode($paginator);
            }
        }
    }
	
    /**
     * 待处理的网站注册申请
     *
     * @return unknown
     */
    function pendingAction()
    {
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        $this->view->paginator = $paginator = $serviceWebsiteApplyRegister->pageByCondition($page,$pagesize,array("register_pass" => 0));
        if(empty($paginator['total'])){
            $this->setMsgs("没有未审核的网站注册申请纪录!");
            return false;
        }
        $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
        $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
    }

    /**
     * 通过审核的网站注册申请
     *
     * @return unknown
     */
    function succeedAction()
    {
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        $this->view->paginator = $paginator = $serviceWebsiteApplyRegister->pageByCondition($page,$pagesize,array("register_pass" => 1));
        if($paginator['total'] == 0){
            $this->setMsgs("没有通过的网站注册申请纪录!");
            return false;
        }
        $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
        $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
    }

    /**
     * 未通过审核的网站注册申请
     *
     * @return unknown
     */
    function failAction()
    {
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        $this->view->paginator = $paginator = $serviceWebsiteApplyRegister->pageByCondition($page,$pagesize,array("register_pass" => 2));
        if(empty($paginator['total'])){
            $this->setMsgs("没有被拒绝的网站注册申请纪录!");
            return false;
        }
        $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
        $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
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