<?php
/**
 * 公告
 *
 */
class Diana_Model_DbTable_BulletinChannel extends Diana_Model_DbTable_Abstract
{
    var $_name = "bulletin_channel";
    var $_primary  = array("channel_id");

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
            "channel_order_desc" => array("channel_order desc"),
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
        if (!empty($condition["channel_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("channel_id",$condition["channel_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["channel_fatherId"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("channel_fatherId",$condition["channel_fatherId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }

        return $wheres;
    }
}