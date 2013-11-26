<?php
/**
 * 会员
 *
 */
class Diana_Model_DbTable_MemberLog extends Diana_Model_DbTable_Abstract
{
    var $_name = "member_log";
    var $_primary  = array("log_id");

    function __construct()
    {
        parent::__construct();
        if(empty($year)){
            $year = date('Y');
        }
        $source = $this->_name;
        $dest = $this->_name.'_'.$year;
        $this->copyTableStructure($source,$dest);
        $this->_name = $dest;
    }

    /**
     * 所有的排序方式
     *
     * @return array
     */
    function setOrders()
    {
        $this->_orders = array(
            "new" => array("log_time desc"),
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
        //log_memberId,log_memberEmail''
        if (!empty($condition["log_id"])) {//ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_id",$condition["log_id"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_time_min"])) {//开始时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_time",$condition["log_time_min"],4);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_time_max"])) {//结束时间
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_time",$condition["log_time_max"],3);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_sessionId"])) {//sessionID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_sessionId",$condition["log_sessionId"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_memberId"])) {//用户ID
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_memberId",$condition["log_memberId"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_memberName"])) {//用户email
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_memberName",$condition["log_memberName"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if (!empty($condition["log_memberEmail"])) {//用户email
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_memberEmail",$condition["log_memberEmail"],2);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        if(!empty($condition['log_type'])){//日志类型,11注册，11修改个人资料，21登录，22取回密码，23修改密码，31网站发布，32修改网站，33删除网站
            $tmpWheres = array();
            $tmpWheres = $this->getWhereByCondition("log_type",$condition["log_type"],1);
            $wheres = array_merge($wheres,$tmpWheres);
        }
        return $wheres;
    }
}