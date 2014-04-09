<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:36
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_Manager extends Diana_Model_DbTable_Abstract
{
    var $_name = "manager";
    var $_primary  = array("manager_id");

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
            "new" => array("manager_id desc"),
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
        if (!empty($condition["manager_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("manager_id",$condition["manager_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["manager_role"])) {//角色流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("manager_role",$condition["manager_role"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["manager_name"])) {//用户名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("manager_name",$condition["manager_name"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["manager_email"])) {//用户邮箱
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("manager_email",$condition["manager_email"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }

        return $wheres;
    }
}
