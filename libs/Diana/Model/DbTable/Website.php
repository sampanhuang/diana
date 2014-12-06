<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:07
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_DbTable_Website extends Diana_Model_DbTable_Abstract
{
    var $_name = "website";
    var $_primary  = array("website_id");

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
            "new" => array("website_apply_time desc"),
            "click_out" => array("website_click_out desc"),
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
        if (!empty($condition["website_id"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_id",$condition["website_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_id_not"])) {//流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_id",$condition["website_id_not"],11);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_memberId"])) {//会员流水号
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_memberId",$condition["website_memberId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_name"])) {//网站名称
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_name",$condition["website_name-cn"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_domain"])) {//网站域名
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_domain",$condition["website_domain"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_categoryId"])) {//网站类别
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_categoryId",$condition["website_categoryId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["website_areaId"])) {//所属地区
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_areaId",$condition["website_areaId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["keywork"])) {//关键字
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_country",$condition["website_country"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["wait_pass"])) {//关键字
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("website_update_stat",array(2,3),1);
            $wheres = array_merge($wheres,$tmpWheres);
        }


        return $wheres;
    }
}
