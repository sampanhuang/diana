<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_SafeFilterWord extends Diana_Model_DbTable_Abstract
{
    var $_name = "safe_filter_word";
    var $_primary  = array("word_id");

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
            "hot" => array("word_count desc"),
            "new" => array("word_time desc"),
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
        if (!empty($condition["word_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("word_id",$condition["word_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["word_val"])) {//标签名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("word_val",$condition["word_val"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
