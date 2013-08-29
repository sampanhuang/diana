<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-1
 * Time: 下午7:36
 * To change this template use File | Settings | File Templates.
 */
class Website_ApplyController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    function pendingAction()
    {
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteApply = new Diana_Service_WebsiteApply();
        $this->view->paginator = $paginator = $serviceWebsiteApply->pageByCondition($page,$pagesize,array("apply_pass" => 0));
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    function succeedAction()
    {
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteApply = new Diana_Service_WebsiteApply();
        $this->view->paginator = $paginator = $serviceWebsiteApply->pageByCondition($page,$pagesize,array("apply_pass" => 1));
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    function failAction()
    {
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteApply = new Diana_Service_WebsiteApply();
        $this->view->paginator = $paginator = $serviceWebsiteApply->pageByCondition($page,$pagesize,array("apply_pass" => 2));
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    function detailAction()
    {
        $this->view->websiteId = $websiteId = $this->getRequest()->getUserParam('apply_id',0);
        if(!empty($websiteId)){
            $serviceWebsiteApply = new Diana_Service_WebsiteApply();
            if($detailWebsiteApply = $serviceWebsiteApply->detailById($websiteId)){
                $this->view->detailWebsiteApply = $detailWebsiteApply;
            }
        }

    }

    function judgeAction()
    {
        $this->view->applyId = $applyId = $this->getRequest()->getParam('apply_id',0);
        $this->view->pass = $pass = $this->getRequest()->getParam('pass',0);
        $serviceWebsiteApply = new Diana_Service_WebsiteApply();
        if(!empty($applyId)){
            if($pass == 1){
                if($rowsWebsite = $serviceWebsiteApply->accedeApply($applyId)){
                    $this->view->rows = $rowsWebsite;
                    $this->setMsgs('同意申请成功！');
                }else{
                    $this->setMsgs('同意申请失败！');
                    $this->setMsgs($serviceWebsiteApply->getMsgs());
                }
            }elseif($pass == 2){
                if($rowsWebsiteApply = $serviceWebsiteApply->demurApply($applyId)){
                    $this->setMsgs('拒绝申请成功！');
                    $this->view->rows = $rowsWebsiteApply;
                }else{
                    $this->setMsgs('拒绝申请失败！');
                    $this->setMsgs($serviceWebsiteApply->getMsgs());
                }
            }

        }
    }
}