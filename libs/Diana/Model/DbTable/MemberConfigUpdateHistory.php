<?php
/**
  *配置更新历史
  */
class Diana_Model_DbTable_MemberConfigUpdateHistory extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_config_update_history";
    var $_primary  = array("history_id");

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
            "history_insert_time_desc" => array("history_insert_time desc"),
			"history_insert_time_asc" => array("history_insert_time asc"),
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
        //log_memberId,log_memberEmail''
        if (!empty($condition["history_id"])) {//ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("history_id",$condition["history_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
		if (!empty($condition["history_memberId"])) {//ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("history_memberId",$condition["history_memberId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["history_configId"])) {//ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("history_configId",$condition["history_configId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["history_configKey"])) {//ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("history_configKey",$condition["history_configKey"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }

}