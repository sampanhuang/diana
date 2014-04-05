<?php
abstract class Client_Service_Abstract extends Diana_Service_Abstract
{
    var $sessionMember;

    function __construct()
    {
        parent::__construct();
        $this->setSessionMember();
    }

    /**
     * 从当前会话中得到管理员ID
     *
     */
    function setSessionMember()
    {
        if (empty($this->sessionMember)) {
            //判断各项session不能为空
            $sessionMember = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MEMBER);
            if (empty($sessionMember->id) || empty($sessionMember->email) || empty($sessionMember->name)) {
                //$this->setMsgs('会话超时！你需要重新登录');
                return false;
            }
            $this->sessionMember = array(
                'id' => $sessionMember->id,
                'email' => $sessionMember->email,
                'name' => $sessionMember->name,
            );
        }
        return $this->sessionMember;
    }
}