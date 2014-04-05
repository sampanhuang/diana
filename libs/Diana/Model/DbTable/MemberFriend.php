<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:09
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_MemberFriend extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_friend";
    var $_primary  = array("friend_id");

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
            "friend_insert_time_desc" => array("friend_insert_time desc"),
            "friend_insert_time_asc" => array("friend_insert_time asc"),
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
        if (!empty($condition["friend_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("friend_id",$condition["friend_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["friend_master_memberId"])) {//会员邮箱
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("friend_master_memberId",$condition["friend_master_memberId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
