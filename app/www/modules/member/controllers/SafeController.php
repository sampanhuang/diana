<?php
/**
 * 安全选项
 *
 */
class Member_SafeController extends Www_Controller_ActionMember
{
    function init()
    {
        parent::init();

    }

    /**
     * 密码重置
     */
    function resetpwdAction()
    {
        $request = $this->_request;
        if($request->isPost()) {
            $post = $request->getPost();
            $serviceCaptcha = new Diana_Service_Captcha();
            if ($serviceCaptcha->checkCaptchaWord($post['captcha'],"admin-member-resetpwd")) {
                $serviceMemberPassword = new Www_Service_MemberPassword();
                if($serviceMemberPassword->resetpwd($this->currentMemberId,$post['password_old'],$post['password_new'])){
                    $this->setMsgs('密码修改成功');
                }else{
                    $this->setMsgs('密码修改失败');
                    $this->setMsgs($serviceMemberPassword->getMsgs());
                }
            }else{
                $this->setMsgs($serviceCaptcha->getMsgs());
                return false;
            }
        }
    }

    /**
     * 邮箱重置
     */
    function resetemailAction()
    {

    }

    /**
     * 登录日志
     */
    function logLoginAction()
    {

    }

    /**
     * 密码重置日志
     */
    function logResetpwdAction()
    {

    }

    /**
     * 邮箱重置日志
     */
    function logResetemailAction()
    {

    }
}