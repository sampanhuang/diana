<?php
/**
 * 网站注册申请
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-30
 * Time: 下午4:08
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_WebsiteApplyRegister extends Diana_Model_DbTable_Abstract
{
    var $_name = "website_apply_register";
    var $_primary  = array("register_id");

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
            "new" => array("register_id desc"),
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
        if (!empty($condition["register_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("register_id",$condition["register_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_memberId"])) {//会员流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_memberId",$condition["website_memberId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_name"])) {//网站名称
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_name",$condition["website_name"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_domain"])) {//网站域名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_domain",$condition["website_domain"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (isset($condition["register_pass"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("register_pass",$condition["register_pass"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}

