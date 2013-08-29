<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_Member extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_Member();
    }

    function deleteById($id)
    {
        $condition = array("member_id" => $id);
        return $this->delData($condition);
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("member_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

    function getRowsByEmail($refresh = null,$email)
    {
        $condition = array("member_email" => $email);
        return $this->getRowsByCondition($refresh,$condition);
    }

    function getRowsByName($refresh = null,$name)
    {
        $condition = array("member_name" => $name);
        return $this->getRowsByCondition($refresh,$condition);
    }


    function register($roleId = 0,$email,$name,$passwd)
    {
        $data = array(
            'member_roleId' => $roleId,
            'member_email' => trim(strtolower($email)),
            'member_name' => trim(strtolower($name)),
            'member_passwd' => trim(strtolower($passwd)),
            'member_register_time' => time(),
            'member_register_ip' => $_SERVER['REMOTE_ADDR'],
        );
        if(!$memberId = $this->saveData(1,$data)){
            return false;
        }
        return $memberId;
    }


    /**
     * 变更密码
     *
     * @param int $id 用户ID
     * @param string $newPwd 新密码
     * @return array
     */
    function updateWithPasswd($id,$newPwd)
    {
        $data = array(
            "member_passwd" => $newPwd,
            "member_passwd_change_time" => time(),
            "member_passwd_change_ip" => $_SERVER['REMOTE_ADDR'],
            "member_passwd_change_count" => new Zend_Db_Expr("member_passwd_change_count + 1"),
        );
        $condition = array("member_id" => $id);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 用于登录的更新
     *
     */
    function updateWithLogin($id)
    {
        $data = array(
            "member_login_count" => new Zend_Db_Expr("member_login_count + 1"),
            "member_login_last_time" => time(),
            "member_login_last_ip" => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array("member_id" => $id);
        return $this->saveData(2,$data,$condition);
    }
}