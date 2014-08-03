<?php
/**
 * 首页
 *
 */
class Member_ApplyController extends Www_Controller_ActionMember
{
    function init()
    {
        parent::init();

    }

    /**
     * 提交申请
     */
    function indexAction()
    {
        $pagesize = 10;
        $this->view->applyPass = $applyPass = $this->getRequest()->getUserParam('apply_pass',null);
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $condition = array("website_memberId" => $this->currentMemberId );
        if(is_null($applyPass) ){$condition['apply_pass'] = $applyPass;}
        $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
        $this->view->paginator = $paginator = $serviceWebsiteApplyRegister->pageByCondition($page,$pagesize,$condition);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    /**
     * 详情
     */
    function detailAction()
    {
        $this->view->applyId = $applyId =  $this->getRequest()->getUserParam('apply_id',0);
        if(empty($applyId)){
            $this->setMsgs("缺少参数apply_id");
        }
        if(!empty($applyId)){
            $serviceWebsiteApplyRegister = new Diana_Service_WebsiteApplyRegister();
            if($detailWebsiteApply = $serviceWebsiteApplyRegister->detailById($applyId)){
                if($detailWebsiteApply['website_memberId'] == $this->currentMemberId){
                    $this->view->detailApply = $detailWebsiteApply;
                }else{
                    $this->setMsgs('你没有权限查看当前申请纪录');
                }
            }else{
                $this->setMsgs('无效的参数');
            }
        }
    }
}