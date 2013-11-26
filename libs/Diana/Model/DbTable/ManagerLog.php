<?php
/**
 * 管理员
 *
 */
class Diana_Model_DbTable_ManagerLog extends Diana_Model_DbTable_Abstract
{
    var $_name = "manager_log";
    var $_primary  = array("log_id");

    function __construct($year = null)
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
        //log_managerId,log_managerEmail''
        if (!empty($condition["log_id"])) {//ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_id",$condition["log_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_time_min"])) {//开始时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_time",$condition["log_time_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_time_max"])) {//结束时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_time",$condition["log_time_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_ip"])) {//IP地址
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_ip",$condition["log_ip"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_type"])) {//IP地址
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_type",$condition["log_type"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_sessionId"])) {//sessionID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_sessionId",$condition["log_sessionId"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_managerId"])) {//用户ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_managerId",$condition["log_managerId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_managerName"])) {//用户email
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_managerName",$condition["log_managerName"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_managerEmail"])) {//用户email
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_managerEmail",$condition["log_managerEmail"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}