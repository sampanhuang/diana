<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_FrontChannel extends Diana_Model_DbTable_Abstract
{
    var $_name = "front_channel";
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
            "order" => array("channel_order desc"),
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
            $tmpWheres = $this->getWhereByCondition("area_id",$condition["area_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if ($condition["channel_fatherId"] !== null && $condition['channel_fatherId'] !== '') {//父ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("channel_fatherId",$condition["channel_fatherId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
