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
     * @param $websiteId
     * @param $websiteRow 表纪录
     * @return array
     */
    function postApply($websiteId,$websiteRow)
    {
        $data = array(
            'delete_insert_time' => time(),
            'delete_insert_ip' => $_SERVER['REMOTE_ADDR'],
            'website_id' => intval($websiteId),
            'website_memberId' => intval($memberId),
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
    function updatePass($id,$pass = 0,$reply)
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



}