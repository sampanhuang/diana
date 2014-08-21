<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:49
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_Manager extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_Manager();
    }

    /**
     * 通过用户名得到多条纪录
     * @param null $refresh 是否刷新
     * @param string|array $name 用户名
     * @return array
     */
    function getRowsByName($refresh = null,$name)
    {
        $condition = array("manager_name" => $name);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过邮箱得到多条纪录
     * @param null $refresh 是否刷新
     * @param string|array $email 邮箱
     * @return array
     */
    function getRowsByEmail($refresh = null,$email)
    {
        $condition = array("manager_email" => $email);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过流水号ID获取多条纪录
     * @param null $refresh 是否刷新
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("manager_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
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
            "manager_passwd" => $newPwd,
            "manager_passwd_change_time" => time(),
            "manager_passwd_change_ip" => $_SERVER['REMOTE_ADDR'],
            "manager_passwd_change_count" => new Zend_Db_Expr("manager_passwd_change_count + 1"),
        );
        $condition = array("manager_id" => $id);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 变更密码
     *
     * @param int $id 用户ID
     * @param string $newPwd 新密码
     * @return array
     */
    function updateWithName($id,$name)
    {
        $data = array(
            "manager_name" => $name,
            "manager_name_change_time" => time(),
            "manager_name_change_ip" => $_SERVER['REMOTE_ADDR'],
            "manager_name_change_count" => new Zend_Db_Expr("manager_name_change_count + 1"),
        );
        $condition = array("manager_id" => $id);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 变更密码
     *
     * @param int $id 用户ID
     * @param string $newPwd 新密码
     * @return array
     */
    function updateWithEmail($id,$email)
    {
        $data = array(
            "manager_email" => $email,
            "manager_email_change_time" => time(),
            "manager_email_change_ip" => $_SERVER['REMOTE_ADDR'],
            "manager_email_change_count" => new Zend_Db_Expr("manager_email_change_count + 1"),
        );
        $condition = array("manager_id" => $id);
        return $this->saveData(2,$data,$condition);
    }



    /**
     * 用于登录的更新
     *
     */
    function updateWithLogin($id)
    {
        $data = array(
            "manager_login_count" => new Zend_Db_Expr("manager_login_count + 1"),
            "manager_login_last_time" => time(),
            "manager_login_last_ip" => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array("manager_id" => $id);
        return $this->saveData(2,$data,$condition);
    }

}

