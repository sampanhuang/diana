<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteKeyword extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_keyword";
    var $_primary  = array("keyword_id");

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
            "keyword_update_time_desc" => array("keyword_update_time desc"),
            "keyword_insert_time_desc" => array("keyword_insert_time desc"),
            "keyword_count_enter_desc" => array("keyword_count_enter desc"),
            "keyword_update_time_asc" => array("keyword_update_time asc"),
            "keyword_insert_time_asc" => array("keyword_insert_time asc"),
            "keyword_count_enter_asc" => array("keyword_count_enter asc"),
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
        if (!empty($condition["keyword_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("keyword_id",$condition["keyword_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["keyword_label"])) {//标签名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("keyword_label",$condition["keyword_label"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["keyword_count_enter_min"])) {//标签名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("keyword_count_enter",$condition["keyword_count_enter_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["keyword_count_enter_max"])) {//标签名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("keyword_count_enter",$condition["keyword_count_enter_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["keyword_label_like"])) {//标签名，模糊查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("keyword_label",$condition["keyword_label_like"],5);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
