<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteUpdate extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteApplyUpdate();
    }

    /**
     * 提交一个变更申请
     * @param $memberId 会员ID
     * @param $name 网站名
     * @param $domain 网站域名
     * @param $tag 标签
     * @param $summary 简介
     * @param $category 分类
     * @param $continent 大陆
     * @param $country 国家
     */
    function postApply($websiteId,$memberId,$websiteName,$websiteDomain,$websiteTag,$websiteCategory,$websiteContinent,$websiteCountry)
    {
        $data = array(
            'website_id' => intval($websiteId),
            'website_memberId' => intval($memberId),
            'website_name' => trim(strtolower($websiteName)),
            'website_domain' => trim(strtolower($websiteDomain)),
            'website_tag' => trim($websiteTag),
            'website_categoryId' => intval($websiteCategory),
            'website_continent' => trim(strtolower($websiteContinent)),
            'website_country' => trim(strtolower($websiteCountry)),
            'update_insert_time' => time(),
            'update_insert_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array(
            "update_pass" => 0,
            "website_id" => $websiteId,
        );
        //判断是否已经存在未处理的资料变更纪录
        if($rowsWebsiteUpdate = $this->saveData(2,$data,$condition)){
            return $rowsWebsiteUpdate;
        }else{
            return $this->saveData(1,$data);
        }
    }


    function deleteById($id)
    {
        $condition = array("update_id" => $id);
        return $this->delData($condition);
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("apply_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 更新更新申请的状态
     * @param $websiteId
     * @param int $pass
     * @return array
     */
    function updatePass($id,$pass = 0,$reply)
    {
        $data = array(
            'update_pass' => $pass,
            'update_pass_time' => time(),
            'update_pass_ip' => $_SERVER['REMOTE_ADDR'],
            'update_reply' => $reply,
        );
        $condition = array('update_id' => $id);
        return $this->saveData(2,$data,$condition);
    }

}