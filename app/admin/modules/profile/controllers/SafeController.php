<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 下午11:14
 * To change this template use File | Settings | File Templates.
 */
class Profile_SafeController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function resetpwdAction(){
        $request = $this->_request;
        if($request->isPost()) {
            $post = $request->getPost();
            $serviceCaptcha = new Diana_Service_Captcha();
            if ($serviceCaptcha->checkCaptchaWord($post['captcha'],"admin-manager-resetpwd")) {
                $serviceManagerPassword = new Admin_Service_ManagerPassword();
                if($serviceManagerPassword->resetpwd($this->currentManagerId,$post['password_old'],$post['password_new'])){
                    $this->setMsgs('密码修改成功');
                }else{
                    $this->setMsgs('密码修改失败');
                    $this->setMsgs($serviceManagerPassword->getMsgs());
                }
            }else{
                $this->setMsgs($serviceCaptcha->getMsgs());
                return false;
            }
        }
    }

    function logResetAction()
    {

    }

    function logLoingAction()
    {

    }
}

