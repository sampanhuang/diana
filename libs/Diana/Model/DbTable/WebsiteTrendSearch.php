<?php
/**
 * 网站搜索
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-30
 * Time: 下午4:08
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteTrendSearch extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_trend_search";
    var $_primary  = array("trend_id");

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
            "new" => array("trend_month","trend_day"),
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
        if (!empty($condition["trend_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("trend_id",$condition["trend_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["trend_eventId"])) {//website id
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("trend_eventId",$condition["trend_eventId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["trend_month"])) {//月
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("trend_month",$condition["trend_month"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (isset($condition["trend_day"])) {//日
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("trend_day",$condition["trend_day"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}

