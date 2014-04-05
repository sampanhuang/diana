<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:09
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_MemberFriendRequest extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_friend_request";
    var $_primary  = array("request_id");

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
            "request_insert_time_desc" => array("request_insert_time desc"),
            "request_insert_time_asc" => array("request_insert_time asc"),
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
        if (!empty($condition["request_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("request_id",$condition["request_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["request_source"])) {//发送人
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("request_source",$condition["request_source"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["request_dest"])) {//收件人
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("request_dest",$condition["request_dest"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
