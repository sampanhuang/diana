<?php
/**
 * 历史密码
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:09
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_MemberHistoryPassword extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_history_password";
    var $_primary  = array("history_id");

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
            "new" => array("history_id desc"),
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
        if (!empty($condition["history_memberId"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("history_memberId",$condition["history_memberId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}
