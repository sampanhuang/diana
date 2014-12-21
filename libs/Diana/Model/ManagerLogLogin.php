<?php
/**
 * 管理员活动纪录
 *
 */
class Diana_Model_ManagerLogLogin extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct($year = null);
        $this->dt = new Diana_Model_DbTable_ManagerLogLogin($year = null);
    }

    /**
     * 写入日志
     * @param $type 登录类型，1成功，2用户不存在，3密码错误，4验证码错误
     * @param $inputUserName 输入的用户名
     * @param $inputPassword 输入的密码
     * @param $inputCaptcha 验证码
     * @return array|bool
     */
    function write($type,$manId = 0,$inputUserName,$inputPassword,$inputCaptcha)
    {
        if (empty($type)||empty($inputUserName)||empty($inputPassword)||empty($inputCaptcha)) {
            return false;
        }
        $data = array(
            'log_time' => time(),
            'log_ip' => $_SERVER['REMOTE_ADDR'],
            'log_type' => $type,
            'log_manId' => $manId,
            'log_input_username' => $inputUserName,
            'log_input_password' => $inputPassword,
            'log_input_captcha' => $inputCaptcha,
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
        $condition = array('log_id' => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}