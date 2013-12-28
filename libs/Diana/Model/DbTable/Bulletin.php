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
			"top_time" => array("bulletin_top desc","bulletin_insert_time desc"),
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
        if (!empty($condition["bulletin_channel"])) {//频道
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_channel",$condition["bulletin_channel"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (isset($condition["bulletin_type"])) {//显示范围，1是www公告,2是client公告,3是admin公告
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_type",$condition["bulletin_type"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_click_min"])) {//点击查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_click",$condition["bulletin_click_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_click_max"])) {//点击查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_click",$condition["bulletin_click_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_title_like"])) {//标题模糊查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_title",$condition["bulletin_title_like"],5);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_author"])) {//作者查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_author",$condition["bulletin_author"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_insert_time_min"])) {//插入时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_insert_time",$condition["bulletin_insert_time_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_insert_time_max"])) {//插入时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_insert_time",$condition["bulletin_insert_time_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_update_time_min"])) {//更新时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_update_time",$condition["bulletin_update_time_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["bulletin_update_time_max"])) {//更新时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("bulletin_update_time",$condition["bulletin_update_time_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
		return $wheres;
	}
}