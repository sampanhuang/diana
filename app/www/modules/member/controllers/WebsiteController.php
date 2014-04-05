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
        //获取分类信息
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->allWebsiteCategory = $allWebsiteCategory = $serviceWebsiteCategory->getAll();
        //获取地区信息
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $this->view->allWebsiteArea = $allWebsiteArea = $serviceWebsiteArea->getAll();
        //获取网站详细内容
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
        //获取ID
        $this->view->websiteId = $websiteId = $this->getRequest()->getParam('website_id',0);
        if(empty($websiteId)){
            $this->setMsgs("参数website_id不能为空");
            return false;
        }
        //处理提交请求
        $request = $this->_request;
        if($request->isPost()) {
            $post = $request->getPost();
            if($websiteId <> $post['website_id']){
                $this->setMsgs('无效的提交数据');
                return false;
            }
            if(!empty($this->currentMemberEmail)){
                $post['website_memberEmail'] = $post['website_update_man'] = $this->currentMemberEmail;
            }
            $serviceWebsite = new Diana_Service_Website();
            if($serviceWebsite->updateById($websiteId,$post,$this->currentMemberEmail)){
				$this->setMsgs('修改成功');
            }else{
            	$this->setMsgs($serviceWebsite->getMsgs());
            }
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
        //获取全部分类信息
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $this->view->allWebsiteCategory = $allWebsiteCategory = $serviceWebsiteCategory->getAll(null,'website');
        //父级分类,array(id=>array(...))
        $rowsWebsiteCategoryFather = array();
        //子级分类,array(father_id => array( id => array(..) ))
        $rowsWebsiteCategorySon = array();
        $optionsWebsiteCategorySon = array();
        //从$allWebsiteArea中循环得到$rowsWebsiteAreaFather与$rowsWebsiteAreaSon
        foreach ($allWebsiteCategory as $rowWebsiteCategory){
            $tmpCategoryId = $rowWebsiteCategory['category_id'];
            $tmpCategoryFatherId = $rowWebsiteCategory['category_fatherId'];
            if(empty($tmpAreaFatherId)){
                $rowsWebsiteCategoryFather[$tmpCategoryId] = $rowWebsiteCategory;
            }else{
                $rowsWebsiteCategorySon[$tmpCategoryFatherId][$tmpCategoryId] = $rowWebsiteCategory;
                $optionsWebsiteCategorySon[$tmpCategoryFatherId][$tmpCategoryId] = $rowWebsiteCategory['category_name_'.DIANA_TRANSLATE_CURRENT];
            }
        }
        $this->view->rowsWebsiteCategoryFather = $rowsWebsiteCategoryFather;
        $this->view->rowsWebsiteCategorySon = $rowsWebsiteCategorySon;
        $this->view->optionsWebsiteCategorySon = $optionsWebsiteCategorySon;
        //获取全部地区信息
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $this->view->allWebsiteArea = $allWebsiteArea = $serviceWebsiteArea->getAll(null,'website');
        //父级地区,array(id=>array(...))
        $rowsWebsiteAreaFather = array();
        //子级地区,array(father_id => array( id => array(..) ))
        $rowsWebsiteAreaSon = array();
        $optionsWebsiteAreaSon = array();
        //从$allWebsiteArea中循环得到$rowsWebsiteAreaFather与$rowsWebsiteAreaSon
        foreach ($allWebsiteArea as $rowWebsiteArea){
            $tmpAreaId = $rowWebsiteArea['area_id'];
            $tmpAreaFatherId = $rowWebsiteArea['area_fatherId'];
            if(empty($tmpAreaFatherId)){
                $rowsWebsiteAreaFather[$tmpAreaId] = $rowWebsiteArea;
            }else{
                $rowsWebsiteAreaSon[$tmpAreaFatherId][$tmpAreaId] = $rowWebsiteArea;
                $optionsWebsiteAreaSon[$tmpAreaFatherId][$tmpAreaId] = $rowWebsiteArea['area_name_'.DIANA_TRANSLATE_CURRENT];
            }
        }
        $this->view->rowsWebsiteAreaFather = $rowsWebsiteAreaFather;
        $this->view->rowsWebsiteAreaSon = $rowsWebsiteAreaSon;
        $this->view->optionsWebsiteAreaSon = $optionsWebsiteAreaSon;
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