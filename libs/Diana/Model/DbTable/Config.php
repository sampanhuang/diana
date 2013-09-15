<?php
/**
 * 管理配置
 *
 */
class Diana_Model_DbTable_Config extends Diana_Model_DbTable_Abstract
{
	var $_name = "config";
	var $_primary  = array("conf_id");
	
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
			"order" => array("conf_order desc"),
            "last_update_time" => array("conf_update_time desc"),
            'last_insert_time' => array('conf_insert_time desc'),
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
        if (isset($condition["conf_fatherId"])) {//父亲ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("conf_fatherId",$condition["conf_fatherId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
		if (!empty($condition["conf_key"])) {//配置KEY值
			$tmpWheres = array();
			$tmpWheres = $this->getWhereByCondition("conf_key",$condition["conf_key"],2);
			$wheres = array_merge($wheres,$tmpWheres);
		}
		return $wheres;
	}
}