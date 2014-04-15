<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_FriendLink extends Diana_Model_DbTable_Abstract
{
    var $_name = "friend_link";
    var $_primary  = array("link_id");

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
            "order" => array("link_order desc"),
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
        if (!empty($condition["link_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("link_id",$condition["link_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["link_enable_time"])) {//有效
            $tmpWheres_1 = array();
            $tmpWheres_1 = $this->getWhereByCondition("link_enable_time_start",$condition["link_enable_time"],3);
            $wheres = array_merge($wheres,$tmpWheres_1);
            $tmpWheres_2 = array();
            $tmpWheres_2 = $this->getWhereByCondition("link_enable_time_end",$condition["link_enable_time"],4);
            $wheres = array_merge($wheres,$tmpWheres_2);
        }
        return $wheres;
    }
}
