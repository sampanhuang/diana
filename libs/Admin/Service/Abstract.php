<?php
abstract class Admin_Service_Abstract extends Diana_Service_Abstract
{
    var $sessionManager;

    function __construct()
    {
        parent::__construct();
        $this->setSessionManager();
    }

    /**
     * 从当前会话中得到管理员ID
     *
     */
    function setSessionManager()
    {
        if (empty($this->sessionManager)) {
            //判断各项session不能为空
            $sessionManager = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MANAGER);
            if (empty($sessionManager->id) || empty($sessionManager->email) || empty($sessionManager->name)) {
                //$this->setMsgs('会话超时！你需要重新登录');
                return false;
            }
            $this->sessionManager = array(
                'id' => $sessionManager->id,
                'email' => $sessionManager->email,
                'name' => $sessionManager->name,
            );
        }
        return $this->sessionManager;
    }
}