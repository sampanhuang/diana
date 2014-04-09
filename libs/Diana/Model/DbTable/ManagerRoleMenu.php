<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:40
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_ManagerRoleMenu extends Diana_Model_DbTable_Abstract
{
    var $_name = "manager_role_menu";
    var $_primary  = array("id");

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
            "new" => array("id desc"),
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
        if (!empty($condition["id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("id",$condition["id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["role_id"])) {//角色ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("role_id",$condition["role_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["menu_id"])) {//菜单ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("menu_id",$condition["menu_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}