<?php
/**
 * 废纸框
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteApplyDelete extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_apply_delete";
    var $_primary  = array("delete_id");

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 所有的排序方式
     *
     * @return array
     */
    function setOrders()
    {
        $this->_orders = array(
            "new" => array("delete_id desc"),
        );
    }

    /**
     * 通过条件得到where语句
     *
     * @param array $condition 条件
     * @return unknown
     */
    function getWheresByCondition($condition)
    {
        $wheres = array();
        if (!empty($condition["delete_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("delete_id",$condition["delete_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!is_null($condition["delete_pass"])) {//状态，是否通过
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("delete_pass",$condition["delete_pass"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["delete_websiteId"])) {//标签名，模糊查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("delete_websiteId",$condition["delete_websiteId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
