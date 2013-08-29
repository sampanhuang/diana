<?php
/**
 * 会员资料
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class Member_RegisterController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function selectAction(){
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceMember = new Diana_Service_Member();
        $this->view->paginator = $paginator = $serviceMember->pageByCondition($page,$pagesize);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    function insertAction()
    {

    }

    function updateAction()
    {

    }

    function deleteAction()
    {

    }

    function detailAction()
    {
        $this->view->memberId = $memberId = $this->getRequest()->getUserParam('member_id',0);
        if(!empty($memberId)){
            $serviceMember = new Diana_Service_Member();
            if($detailMember = $serviceMember->detailById($memberId)){
                $this->view->detailMember = $detailMember;
            }
        }
    }

    function logLoginAction()
    {

    }
}
