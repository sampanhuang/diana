<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:12
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_Website extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 刷新首页
     */
    function flushHtmlIndex()
    {
        $contentIndex = file_get_contents(DIANA_DOMAIN_WWW.'/default/website/index');
        $contentIndex = $contentIndex.'<!--make time '.date('Y-m-d H:i:s').' , from '.$_SERVER['SERVER_ADDR'].'-->';
        if(!file_put_contents(DIANA_DIR_WWW_PUBLIC.'/index.htm',$contentIndex)){
            return false;
        }
        return $contentIndex;
    }

    /**
     * 获取网站列表
     * @param $counter
     * @param array $condition
     * @param null $order
     * @return array|bool
     */
    function listByCondition($counter,$condition = array(),$order = null)
    {
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsByCondition(null,$condition,$order,$counter,0)){
            return false;
        }
        return $rowsWebsite;
    }

    /**
     * @param $key 关键字
     * @param $page 当前页
     * @param $pagesize 每页的纪录数
     */
    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null)
    {

        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        $modelWebsite = new Diana_Model_Website();
        if($countWebsite = $modelWebsite->getCountByCondition(null,$condition)){
            if($rowsWebsite = $modelWebsite->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $memberIds = array();
                $websiteIds = array();
                foreach($rowsWebsite as $rowWebsite){
                    $websiteIds[] = $rowWebsite['website_id'];
                    if(!empty($rowWebsite['website_memberId'])){
                        $memberIds[$rowWebsite['website_memberId']] = $rowWebsite['website_memberId'];
                    }
                }
                //获取会员信息
                $modelMember = new Diana_Model_Member();
                if($rowsMember = $modelMember->getRowsById(null,$memberIds)){
                    foreach($rowsWebsite as &$rowWebsite){
                        foreach($rowsMember as $rowMember){
                            if($rowWebsite['website_memberId'] == $rowMember['member_id']){
                                $rowWebsite['website_memberName'] = $rowMember['member_name'];
                                $rowWebsite['website_memberEmail'] = $rowMember['member_email'];
                                break;
                            }
                        }
                    }
                }
                //获取简介
                $modelWebsiteIntro = new Diana_Model_WebsiteIntro();
                if($optionsWebsiteIntro = $modelWebsiteIntro->getIntroById($websiteIds)){
                    foreach($rowsWebsite as &$rowWebsite){
                        $rowWebsite['website_intro'] = $optionsWebsiteIntro[$rowWebsite['website_id']];
                    }
                }
            }
        }
        return array('total' => $countWebsite,'rows' => $rowsWebsite);
    }

    function detailById($websiteId,$trend = false)
    {
        if(empty($websiteId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_numeric($websiteId)){
            $this->setMsgs('参数类型错误');
            return false;
        }
        //获取网站信息
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            return false;
        }
        $rowWebsite = $rowsWebsite[0];
        //获取网站描述
        $modelWebsiteIntro = new Diana_Model_WebsiteIntro();
        if($websiteIntro = $modelWebsiteIntro->getIntroById(null,$websiteId)){
            $rowWebsite['website_intro'] = $websiteIntro[$websiteId];
        }
        //网站SEO
        $modelWebsiteMeta = new Diana_Model_WebsiteMeta();
        if($rowsWebsiteMeta = $modelWebsiteMeta->getRowsById(null,$websiteId)){
            $rowWebsite['website_meta_keywords'] = $rowsWebsiteMeta[0]['website_meta_keywords'];
            $rowWebsite['website_meta_description'] = $rowsWebsiteMeta[0]['website_meta_description'];
        }else{
            $rowWebsite['website_meta_keywords'] = Com_Functions::utf8substr($rowWebsite['website_tag'],256);
            $rowWebsite['website_meta_description'] = Com_Functions::utf8substr($rowWebsite['website_intro'],256);
        }
        //获取归属人信息
        $modelMember = new Diana_Model_Member();
        if($rowsMember = $modelMember->getRowsById(null,$rowWebsite['website_memberId'])){
            $rowWebsite['website_memberName'] = $rowsMember[0]['website_memberName'];
            $rowWebsite['website_memberEmail'] = $rowsMember[0]['website_memberEmail'];
            $rowWebsite = array_merge($rowWebsite,$rowsMember[0]);
        }
        if($trend)
        {
            //更新点击
            $modelWebsite->updateClickIn($websiteId);
            $modelWebsiteTrendClickIn = new Diana_Model_WebsiteTrendClickIn();
            $modelWebsiteTrendClickIn->update(1,$rowWebsite['website_id']);
            $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
            $modelWebsiteCategory->updateCountClickIn(1,$rowWebsite['website_categoryId']);
            $modelWebsiteCountry = new Diana_Model_WebsiteCountry();
            $modelWebsiteCountry->updateCountClickIn(1,$rowWebsite['website_continent'],$rowWebsite['website_country']);
        }
        return $rowWebsite;
    }

    /**
     * 删除网站
     * @param $websiteId 网站ID
     */
    function deleteById($websiteId)
    {
        if(empty($websiteId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        print_r($websiteId);
        //删除条件
        $condition = array("website_id" => $websiteId);
        //删除主体内容
        $modelWebsite = new Diana_Model_Website();
        if(!$rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
            $this->setMsgs('错误的流水号'.implode(',',$websiteId));
            return false;
        }
        if(!$modelWebsite->delData($condition)){
            $this->setMsgs('主体删除失败');
            return false;
        }
        //删除网站简介
        $modelWebsiteIntro = new Diana_Model_WebsiteIntro();
        if($rowsWebsiteIntro = $modelWebsiteIntro->getRowsById(null,$websiteId)){
            if(!$modelWebsiteIntro->delData($condition)){
                foreach($rowsWebsite as $rowWebsite){
                    $modelWebsite->saveData(1,$rowWebsite);
                }
                $this->setMsgs('简介删除失败');
                return false;
            }
        }
        //删除网站标签关系
        $serviceWebsiteTag = new Diana_Service_WebsiteTag();
        foreach($rowsWebsite as $rowWebsite){
            $serviceWebsiteTag->deleteByWebsite($rowWebsite['website_id']);
        }
        return true;
    }


}