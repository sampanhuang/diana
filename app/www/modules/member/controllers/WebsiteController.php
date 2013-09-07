<?php
/**
 * 首页
 *
 */
class Member_WebsiteController extends Www_Controller_ActionMember
{
    var $yearStart = 2013;
    var $yearEnd = 0;
    function init()
    {
        parent::init();
        $this->view->yearStart = $this->yearStart;
        $this->view->yearEnd = $this->yearEnd = intval(date("Y"));

    }

    /**
     * 我的网站
     */
    function indexAction()
    {
        $pagesize = 10;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $condition = array("website_memberId" => $this->currentMemberId);
        $serviceWebsite = new Diana_Service_Website();
        $this->view->paginator = $paginator = $serviceWebsite->pageByCondition($page,$pagesize,$condition);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    /**
     * 提交申请
     */
    function applyAction()
    {
        $pagesize = 10;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $condition = array("website_memberId" => $this->currentMemberId);
        $serviceWebsiteApply = new Diana_Service_WebsiteApply();
        $this->view->paginator = $paginator = $serviceWebsiteApply->pageByCondition($page,$pagesize,$condition);
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
        $this->view->websiteId = $websiteId = $this->getRequest()->getUserParam('website_id',0);
        if(empty($websiteId)){
            $this->setMsgs("参数website_id不能为空");
            return false;
        }
        $serviceWebsite = new Diana_Service_Website();
        if($detailWebsite = $serviceWebsite->detailById($websiteId)){
            if($detailWebsite['website_memberId'] == $this->currentMemberId){
                $this->view->detailWebsite = $detailWebsite;
            }
        }
    }

    /**
     * 网站编辑
     */
    function updateAction()
    {
        $this->view->websiteId = $websiteId = $this->getRequest()->getUserParam('website_id',0);
        if(empty($websiteId)){
            $this->setMsgs("参数website_id不能为空");
            return false;
        }
        //获取网站详细资料
        $serviceWebsite = new Diana_Service_Website();
        if($detailWebsite = $serviceWebsite->detailById($websiteId)){
            if($detailWebsite['website_memberId'] == $this->currentMemberId){
                $this->view->detailWebsite = $detailWebsite;
            }else{
                $this->setMsgs("你并无权限修改当前网站资料!");
                return false;
            }
        }
        //获取网站分类代码
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->websiteCategoryIds = $serviceWebsiteCategory->getIds();
        //获取洲与国家代码
        $serviceCountry = new Diana_Service_Country();
        $this->view->websiteContinents = $continents =  $serviceCountry->getContinentsKey();
        if($countries = $serviceCountry->getCountriesKey()){
            $optionsCountry = array();
            $translate = Zend_Registry::get('Zend_Translate');
            foreach($countries as $continentKey => $countries){
                foreach($countries as $countryKey => $countryValue){
                    $optionsCountry[$continentKey][$countryKey] = $countryKey;
                }
            }
            $this->view->optionsCountry = $optionsCountry;
        }

    }

    /**
     * 删除
     */
    function deleteAction()
    {
        $request = $this->_request;
        if($request->isPost()) {
            $this->view->post = $post = $request->getPost();
            if(empty($post['website_id'])){
                $this->setMsgs('请指定要删除的站点ID');
                return false;
            }
            $checkWebsiteId = array();
            $serviceWebsite = new Diana_Service_Website();
            if(!$rowsWebsite = $serviceWebsite->listByCondition(null,array("website_id" => $post['website_id']))){
                $this->setMsgs('无效的站点ID');
                return false;
            }
            foreach($rowsWebsite as &$rowWebsite){
                if($rowWebsite['website_memberId'] <> $this->currentMemberId){
                    unset($rowWebsite);
                }else{
                    $checkWebsiteId[] = $rowWebsite['website_id'];
                }
            }
            if(empty($rowsWebsite)){
                $this->setMsgs('请确认你有删除的权限');
                return false;
            }
            $this->view->rowsWebsite = $rowsWebsite;
            $this->view->checkWebsiteId = $checkWebsiteId;
            //初始提交删除请求
            if(!empty($post['check-again'])){
                if($serviceWebsite->deleteById($checkWebsiteId)){
                    $this->setMsgs('删除成功');
                }else{
                    $this->setMsgs($serviceWebsite->getMsgs());
                    $this->setMsgs('删除失败');
                }
            }
        }else{
            $this->setMsgs('暂无提交数据');
            return false;
        }
    }


    /**
     * 流出点击
     * @return bool
     */
    function clickOutAction()
    {
        $this->view->eventId = $eventId = $this->getRequest()->getUserParam('event',0);
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        if(empty($eventId)){
            $this->setMsgs('参数event不能为空');
            return false;
        }
        $serviceWebsite = new Diana_Service_Website();
        if($detailWebsite = $serviceWebsite->detailById($eventId)){
            if($detailWebsite['website_memberId'] == $this->currentMemberId){
                $this->view->detailWebsite = $detailWebsite;
                $serviceTrend = new Diana_Service_Trend();
                if($trendYear = $serviceTrend->getWebsiteClickOut($year,$eventId)){
                    $this->view->trendYear = $trendYear;
                }else{
                    $this->setMsgs($serviceTrend->getMsgs());
                    $this->setMsgs('暂无数据');
                }
            }
        }
    }

    /**
     * 流入点击
     * @return bool
     */
    function clickInAction()
    {
        $this->view->eventId = $eventId = $this->getRequest()->getUserParam('event',0);
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        if(empty($eventId)){
            $this->setMsgs('参数event不能为空');
            return false;
        }
        $serviceWebsite = new Diana_Service_Website();
        if($detailWebsite = $serviceWebsite->detailById($eventId)){
            if($detailWebsite['website_memberId'] == $this->currentMemberId){
                $this->view->detailWebsite = $detailWebsite;
                $serviceTrend = new Diana_Service_Trend();
                if($trendYear = $serviceTrend->getWebsiteClickIn($year,$eventId)){
                    $this->view->trendYear = $trendYear;
                }else{
                    $this->setMsgs($serviceTrend->getMsgs());
                    $this->setMsgs('暂无数据');
                }
            }
        }
    }

}