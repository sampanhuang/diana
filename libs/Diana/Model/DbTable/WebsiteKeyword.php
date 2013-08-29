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
            "new" => array("keyword_id desc"),
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
        if (!empty($condition["keyword_label_like"])) {//标签名，模糊查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("keyword_label",$condition["keyword_label_like"],5);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
