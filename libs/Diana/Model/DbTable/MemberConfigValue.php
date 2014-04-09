<?php
/**
 * 管理配置
 *
 */
class Diana_Model_DbTable_MemberConfigValue extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_config_value";
    var $_primary  = array("id");

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
            "conf_insert_time_desc" => array("conf_insert_time desc",),
            "conf_insert_time_asc" => array("conf_insert_time asc",),
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
        if (!empty($condition["conf_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("conf_id",$condition["conf_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["conf_memberId"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("conf_memberId",$condition["conf_memberId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}