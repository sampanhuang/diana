<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:09
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_Member extends Diana_Model_DbTable_Abstract
{
    var $_name = "member";
    var $_primary  = array("member_id");

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
            "new" => array("member_id desc"),
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
        if (!empty($condition["member_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("member_id",$condition["member_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["member_email"])) {//会员邮箱
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("member_email",$condition["member_email"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["member_name"])) {//会员邮箱
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("member_name",$condition["member_name"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
