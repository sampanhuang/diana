<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:40
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_MemberMsgInbox extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_msg_inbox";
    var $_primary  = array("inbox_id");

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
            "new" => array("inbox_msg_accept_time desc"),
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
        if (!empty($condition["inbox_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("inbox_id",$condition["inbox_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["inbox_msgId"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("inbox_msgId",$condition["inbox_msgId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["inbox_manId"])) {//根据收件人来查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("inbox_manId",$condition["inbox_manId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["inbox_msg_accept_time_min"])) {//接收时间查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("inbox_msg_accept_time",$condition["inbox_msg_accept_time_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["inbox_msg_accept_time_max"])) {//接收时间查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("inbox_msg_accept_time",$condition["inbox_msg_accept_time_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["inbox_msg_read_time_min"])) {//查看时间查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("inbox_msg_read_time",$condition["inbox_msg_read_time_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["inbox_msg_read_time_max"])) {//查看时间查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("inbox_msg_read_time",$condition["inbox_msg_read_time_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}