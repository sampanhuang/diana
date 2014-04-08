<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-30
 * Time: 下午3:21
 * To change this template use File | Settings | File Templates.
 */
class Www_Service_Website extends Www_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 通过关键字搜索
     * @param $keyword 关键字
     * @param $page 当前页
     * @param $pagesize 每页多少条
     */
    function search($keyword,$page = 1,$pagesize = 10,$order = 'new')
    {
        if(empty($keyword)||empty($page)||empty($pagesize)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        $keyword = htmlspecialchars(trim($keyword));
        //保存关键字并获取搜索关键字的ID
        $serviceWebsiteKeyword = new Diana_Service_WebsiteKeyword();
        if(!$serviceWebsiteKeyword->search($keyword)){
            $this->setMsgs($serviceWebsiteKeyword->getMsgs());
            return false;
        }
        //获取标签ID
        $modelWebsiteTag = new Diana_Model_WebsiteTag();
        if(!$websiteTagId = $modelWebsiteTag->getIdByKeyword(null,$keyword)){
            $this->setMsgs('无法根据'.$keyword.'搜索到相应的网站标签');
            return false;
        }
        //获取网站ID
        $modelWebsiteTagRelation = new Diana_Model_WebsiteTagRelation();
        if(!$websiteId = $modelWebsiteTagRelation->getWebsiteByTag(null,$websiteTagId)){
            $this->setMsgs('无法根据'.$keyword.'搜索到相应的网站ID');
            return false;
        }

        //通过网站ID获取网站详细信息
        $condition = array("website_id" => $websiteId,'website_tag_like' => $keyword);
        $serviceWebsite = new Diana_Service_Website();
        if(!$paginator = $serviceWebsite->pageByCondition($page,$pagesize,$condition,$order)){
            $this->setMsgs('无效的网站ID');
            return false;
        }
        return $paginator;

    }

    /**
     * 跳转
     * @param $websiteId
     * @return bool|string
     */
    function jumper($websiteId)
    {
        if(empty($websiteId)){
            $this->setMsgs('$websiteId 参数不能为空');
            return false;
        }
        if(!is_numeric($websiteId)){
            $this->setMsgs('$websiteId 参数类型错误');
            return false;
        }
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            $this->setMsgs('$websiteId 参数错误');
            return false;
        }
        $rowWebsite = $rowsWebsite[0];
        $websiteDomain = $rowWebsite['website_domain'].'/?source='.$_SERVER['SERVER_NAME'];
        if(empty($websiteDomain)){
            $this->setMsgs('域名为空');
            return false;
        }
        //更新点击
        $modelWebsite->updateClickOut($websiteId);
        $modelWebsiteTrendClickOut = new Diana_Model_WebsiteTrendClickOut();
        $modelWebsiteTrendClickOut->update(1,$rowWebsite['website_id']);
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        $modelWebsiteCategory->updateCountClickOut(1,$rowWebsite['website_categoryId']);
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        $modelWebsiteArea->updateCountClickOut(1,$rowWebsite['website_areaId']);
        return $websiteDomain;
    }



}
