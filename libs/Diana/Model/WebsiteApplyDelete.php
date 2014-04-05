<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteApplyDelete extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteApplyDelete();
    }

    /**
     * 提交一个删除申请
     * @param $websiteId 网站ID
     * @param $websiteRow 表纪录
     * @param $source 来源，1前台，2后台
     * @param $source
     * @return array
     */
    function postApply($websiteId,$websiteRow,$source,$sourceId)
    {
        if(empty($websiteId)||empty($websiteRow)||empty($source)||empty($sourceId)){
            return false;
        }
        $data = array(
            'delete_source' => $source,
            'delete_sourceId' => $sourceId,
            'delete_insert_time' => time(),
            'delete_insert_ip' => $_SERVER['REMOTE_ADDR'],
            'website_id' => intval($websiteId),
            'website_row' => json_encode($websiteRow),
        );
        return $this->saveData(1,$data);
    }



    /**
     * 变更删除申请的状态
     * @param $websiteId
     * @param int $pass
     * @return array
     */
    function updatePass($id,$pass = 0,$reply = null)
    {
        $data = array(
            'delete_pass' => $pass,
            'delete_pass_time' => time(),
            'delete_pass_ip' => $_SERVER['REMOTE_ADDR'],
            'delete_reply' => $reply,
        );
        $condition = array('delete_id' => $id);
        return $this->saveData(2,$data,$condition);
    }

    function deleteById($id)
    {
        $condition = array("update_id" => $id);
        return $this->delData($condition);
    }

    /**
     * 参赛过处理状态和网站ID得到纪录
     * @param $pass
     * @param $websiteId
     */
    function getRowsByPassWebsite($refresh,$pass,$websiteId)
    {
        $condition = array(
            "delete_pass" => $pass,
            "website_id" => $websiteId,
        );
        return $this->getRowsByCondition($refresh,$condition);
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

    function getCountById($refresh = null,$id)
    {
        $condition = array("apply_id" => $id);
        return $this->getCountByCondition($refresh,$condition);
    }



}