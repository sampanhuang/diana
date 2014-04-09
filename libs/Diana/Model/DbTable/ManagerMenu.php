<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:43
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_ManagerMenu extends Diana_Model_DbTable_Abstract
{
    var $_name = "manager_menu";
    var $_primary  = array("menu_id");

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
            "order" => array("menu_order desc"),
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
        if (!empty($condition["menu_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("menu_id",$condition["menu_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if ( isset($condition["menu_fatherId"]) ) {//父ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("menu_fatherId",$condition["menu_fatherId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
