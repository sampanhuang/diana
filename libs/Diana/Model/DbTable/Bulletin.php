<?php
/**
 * 公告
 *
 */
class Diana_Model_DbTable_Bulletin extends Diana_Model_DbTable_Abstract
{
	var $_name = "bulletin";
	var $_primary  = array("bulletin_id");
	
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
			"top_time" => array("bulletin_top desc,bulletin_insert_time desc"),
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
		if (!empty($condition["bulletin_id"])) {//流水号
			$tmpWheres = array();
			$tmpWheres = $this->getWhereByCondition("bulletin_id",$condition["bulletin_id"],1);
			$wheres = array_merge($wheres,$tmpWheres);
		}
        if (isset($condition["bulletin_type"])) {//类别ID，1前台2后台
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_type",$condition["bulletin_type"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
		return $wheres;
	}
}