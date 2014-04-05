<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteTag extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_tag";
    var $_primary  = array("tag_id");

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
            "new" => array("tag_id desc"),
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
        if (!empty($condition["tag_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("tag_id",$condition["tag_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["tag_name"])) {//标签名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("tag_name",$condition["tag_name"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["tag_name_like"])) {//标签名，模糊查询
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("tag_name",$condition["tag_name_like"],5);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
