<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteTagRelation extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_tag_relation";
    var $_primary  = array("relation_id");

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
            "new" => array("relation_time desc"),
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
        if (!empty($condition["relation_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("relation_id",$condition["relation_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["relation_websiteId"])) {//网站ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("relation_websiteId",$condition["relation_websiteId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["relation_tagId"])) {//标签ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("relation_tagId",$condition["relation_tagId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
