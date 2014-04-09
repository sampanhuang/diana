<?php
/**
 * 管理配置
 *
 */
class Diana_Model_DbTable_State extends Diana_Model_DbTable_Abstract
{
    var $_name = "state";
    var $_primary  = array("state_id");

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
            "order" => array("state_order desc"),
            "last_update_time" => array("state_update_time desc"),
            'last_insert_time' => array('state_insert_time desc'),
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
        if (!empty($condition["state_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("state_id",$condition["state_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (isset($condition["state_fatherId"])) {//父亲ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("state_fatherId",$condition["state_fatherId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["state_key"])) {//配置KEY值
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("state_key",$condition["state_key"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}