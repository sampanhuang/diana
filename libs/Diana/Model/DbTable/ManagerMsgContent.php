<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:40
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_ManagerMsgContent extends Diana_Model_DbTable_Abstract
{
    var $_name = "manager_msg_content";
    var $_primary  = array("msg_id");

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
            "new" => array("msg_id desc"),
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
        if (!empty($condition["msg_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("msg_id",$condition["msg_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}