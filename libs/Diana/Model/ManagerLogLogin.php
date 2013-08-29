<?php
/**
 * 管理员活动纪录
 *
 */
class Diana_Model_ManagerLogLogin extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerLogLogin();
    }

    /**
     * 写入日志
     *
     * @param int $uid 用户ID
     * @param string $email 邮箱
     * @param string $name 用户名
     * @return array
     */
    function write($id,$email,$name)
    {
        if (empty($id)||empty($email)||empty($name)) {
            throw new Exception(__METHOD__." paramt is empty ");
        }
        $data = array(
            "log_time" => time(),
            "log_ip" => $_SERVER['REMOTE_ADDR'],
            "log_sessionId" => session_id(),
            "log_managerId" => $id,
            "log_managerName" => $name,
            "log_managerEmail" => $email,
            "log_remark" => $_SERVER['HTTP_USER_AGENT'],
        );
        return $this->saveData(1,$data);
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("log_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }


}