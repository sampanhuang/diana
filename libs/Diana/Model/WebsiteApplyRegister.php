<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteApplyRegister extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteApplyRegister();
    }

    /**
     * 注册一个申请
     * @param $memberId 会员ID
     * @param $name 网站名
     * @param $domain 网站域名
     * @param $tag 标签
     * @param $summary 简介
     * @param $category 分类
     * @param $continent 大陆
     * @param $country 国家
     */
    function postApply($memberId,$name,$domain,$tag,$category,$area)
    {
        $data = array(
            'website_memberId' => intval($memberId),
            'website_name' => trim(strtolower($name)),
            'website_domain' => trim(strtolower($domain)),
            'website_tag' => trim($tag),
            'website_categoryId' => intval($category),
            'website_areaId' => trim(strtolower($area)),
            'register_insert_time' => time(),
            'register_insert_ip' => $_SERVER['REMOTE_ADDR'],
            'register_update_time' => time(),
            'register_update_ip' => $_SERVER['REMOTE_ADDR'],
            'register_pass' => 3,
        );
        return $this->saveData(1,$data);
    }

    function deleteById($id)
    {
        $condition = array("register_id" => $id);
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
        $condition = array("register_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过websiteid获取
     * @param $refresh
     * @param $websiteId
     * @return array
     */
    function getRowsByWebsiteId($refresh,$websiteId)
    {
        $condition = array("website_id" => $websiteId);
        return $this->getRowsByCondition($refresh,$condition);
    }


    /**
     * @param $websiteId
     * @param int $pass
     * @return array
     */
    function updatePass($id,$pass = 0)
    {
        $data = array(
            'register_pass' => $pass,
            'register_pass_time' => time(),
            'register_pass_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array('register_id' => $id);
        return $this->saveData(2,$data,$condition);
    }

}