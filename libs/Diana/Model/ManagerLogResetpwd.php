<?php
/**
 * 管理员活动纪录
 *
 */
class Diana_Model_ManagerLogResetpwd extends DIANA_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerLogResetpwd();
    }

    /**
     * 写入日志
     *
     * @param int $id 用户ID
     * @param string $email 邮箱
     * @param string $name 用户名
     * @return array
     */
    function write($id,$email,$name = null)
    {
        if (empty($id)||empty($email)) {
            throw new Exception(__METHOD__."paramt is empty ");
        }
        if (is_array($remark)) {
            $remark = json_encode($remark);
        }
        $data = array(
            "log_time" => time(),
            "log_ip" => $_SERVER['REMOTE_ADDR'],
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