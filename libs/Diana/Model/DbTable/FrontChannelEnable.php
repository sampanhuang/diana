<?php
/**
 * 前台频道开放设定
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_FrontChannelEnable extends Diana_Model_DbTable_Abstract
{
    var $_name = "front_channel_enable";
    var $_primary  = array("enable_id");

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
            "order" => array("enable_time_max desc"),
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
        if (!empty($condition["enable_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("enable_id",$condition["enable_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["enable_channelId"])) {//频道 ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("enable_channelId",$condition["enable_channelId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
