<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-19
 * Time: 下午7:49
 * To change this template use File | Settings | File Templates.
 */
class Website_TagController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    function indexAction()
    {
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        $this->view->paginator = $paginator = $serviceWebsiteTag->pageByCondition($page,$pagesize);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    /**
     * 标签明细
     */
    function detailAction()
    {
        $this->view->tagId = $tagId = $this->getRequest()->getUserParam('tag_id',1);
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        if($detailWebsiteTag = $serviceWebsiteTag->detailById($tagId)){
            $this->view->detailWebsiteTag = $detailWebsiteTag;
        }
    }

}