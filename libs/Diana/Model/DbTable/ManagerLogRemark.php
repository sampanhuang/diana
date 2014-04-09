<?php
/**
 * 管理员
 *
 */
class Diana_Model_DbTable_ManagerLogRemark extends Diana_Model_DbTable_Abstract
{
    var $_name = "manager_log_remark";
    var $_primary  = array("log_id");

    function __construct()
    {
        parent::__construct();
        if(empty($year)){
            $year = date('Y');
        }
        $source = $this->_name;
        $dest = $this->_name.'_'.$year;
        $this->copyTableStructure($source,$dest);
        $this->_name = $dest;
    }

    /**
     * 所有的排序方式
     *
     * @return array
     */
    function setOrders()
    {
        $this->_orders = array(
            "new" => array("log_id desc"),
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