<?php
/**
 * 管理员
 *
 */
class Diana_Model_DbTable_MemberLogRemark extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_log_remark";
    var $_primary  = array("log_id");

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
            "new" => array("log_time desc"),
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
        if (!empty($condition["log_id"])) {//ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_id",$condition["log_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}