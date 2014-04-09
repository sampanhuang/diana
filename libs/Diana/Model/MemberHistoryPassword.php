<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_MemberHistoryPassword extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTableMemberHistoryPassword();
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("history_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过会员ID获取历史纪录
     * @param null $refresh
     * @param $memberId
     * @return array
     */
    function getRowsByMember($refresh = null,$memberId)
    {
        $condition = array("history_memberId" => $memberId);
        return $this->getRowsByCondition($refresh,$condition);
    }

    function getRowsByName($refresh = null,$name)
    {
        $condition = array("member_name" => $name);
        return $this->getRowsByCondition($refresh,$condition);
    }


    /**
     * 插入一条旧密码
     * @param $memberId 会员ID
     * @param $memberPassword 会员旧密码
     * @param null $timestamp 时间戳
     * @return array|bool 返回值为假或是新建的历史
     */
    function insert($memberId,$memberPassword,$timestamp = null)
    {
        if(empty($memberId)||empty($memberPassword)){
            return false;
        }
        if((!is_numeric($memberId))||(!is_string($memberPassword))){
            return false;
        }
        if(empty($timestamp)||(!is_numeric($timestamp))){
            $timestamp = time();
        }
        $data = array(
            'history_time' => $timestamp,
            'history_memberId' => $memberId,
            'history_memberPassword' => $memberPassword,
        );
        return $this->saveData(1,$data);
    }

}