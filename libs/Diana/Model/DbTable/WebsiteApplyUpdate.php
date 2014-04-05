<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteApplyUpdate extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_apply_update";
    var $_primary  = array("update_id");

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
            "new" => array("update_id desc"),
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
        if (!empty($condition["update_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("update_id",$condition["update_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_id"])) {//站点ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_id",$condition["website_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
