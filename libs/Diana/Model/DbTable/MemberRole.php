<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:40
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_MemberRole extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_role";
    var $_primary  = array("role_id");

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
            "new" => array("role_id desc"),
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
        if (!empty($condition["role_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("role_id",$condition["role_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["role_name"])) {//用户名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("role_name",$condition["role_name"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["like_role_name"])) {//用户邮箱
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("like_role_name",$condition["like_role_name"],5);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}