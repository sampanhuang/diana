<?php
/**
 * 管理员活动纪录
 *
 */
class Diana_Model_ManagerLog extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct($year = null);
        $this->dt = new Diana_Model_DbTable_ManagerLog($year = null);
    }

    /**
     * 写入日志
     *
     * @param int $uid 用户ID
     * @param string $email 邮箱
     * @param string $name 用户名
     * @return array
     */
    function write($type,$id,$email,$name)
    {
        if (empty($type)||empty($id)||empty($email)||empty($name)) {
            return false;
        }
        $data = array(
            "log_time" => time(),
            "log_ip" => $_SERVER['REMOTE_ADDR'],
            "log_type" => $type,
            "log_sessionId" => session_id(),
            "log_managerId" => $id,
            "log_managerName" => $name,
            "log_managerEmail" => $email,
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