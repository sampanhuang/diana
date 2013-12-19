<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:40
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_ManagerMsgOutbox extends Diana_Model_DbTable_Abstract
{
    var $_name = "manager_msg_outbox";
    var $_primary  = array("outbox_id");

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
            "new" => array("outbox_msg_send_time desc"),
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
        if (!empty($condition["outbox_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("outbox_id",$condition["outbox_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["outbox_msgId"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("outbox_msgId",$condition["outbox_msgId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["outbox_manId"])) {//根据发件人来查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("outbox_manId",$condition["outbox_manId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["outbox_msg_send_time_min"])) {//最小发送时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("outbox_msg_send_time",$condition["outbox_msg_send_time_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["outbox_msg_send_time_max"])) {//最大发送时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("outbox_msg_send_time",$condition["outbox_msg_send_time_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["outbox_send_stat"])) {//草稿箱还是发件箱1发件箱，2草稿箱
            $tmpWheres = array();
            if($condition['outbox_send_stat'] == 1){//已发送
                $tmpWheres = $this->getWhereByCondition("outbox_msg_send_time",0,11);//大于等于1
            }elseif($condition['outbox_send_stat'] == 2){//未发送
                $tmpWheres = $this->getWhereByCondition("outbox_msg_send_time",0,1);//小于等于0
            }
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}