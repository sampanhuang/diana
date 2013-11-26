<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteCategory extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_category";
    var $_primary  = array("category_id");

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
            "order" => array("category_order desc"),
            'website' => array('category_count_website desc'),
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
        if (!empty($condition["category_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("category_id",$condition["category_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["category_fatherId"])) {//父ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("category_fatherId",$condition["category_fatherId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }

        return $wheres;
    }
}
