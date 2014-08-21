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
     * 密码更新
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

    /**
     * 更新帐号
     */
    function updateAction()
    {
        $serviceManager = new Admin_Service_Manager();
        $condition = array('log_managerId' => $this->currentManagerId);//日志查询条件
        //处理get提交
        $updateType = $this->requestParams['type'];
        if($updateType == 'name'){
            $this->view->typeLabel = $typeLabel = '帐号';
            $this->view->captchaKey = $captchaKey = 'manager-update-name';//验证码标识
            $condition['log_type'] = 130;//日志查询条件
        }else{
            $this->view->typeLabel = $typeLabel = '邮箱';
            $this->view->captchaKey = $captchaKey = 'manager-update-email';//验证码标识
            $condition['log_type'] = 120;//日志查询条件
        }
        //处理POST提交
        if($this->requestParamsPost) {
            $serviceCaptcha = new Diana_Service_Captcha();
            if ($serviceCaptcha->checkCaptchaWord($this->requestParamsPost['captcha'],$captchaKey)) {
                if($detailManager = $serviceManager->updateNameEmail($this->currentManagerId,$this->requestParamsPost['value_new'],$updateType)){
                    $this->setMsgs('更新成功');
                }else{
                    $this->setMsgs('更新失败');
                    $this->setMsgs($serviceManager->getMsgs());
                }
            }else{
                $this->setMsgs('验证码输入错误');
            }
        }
        if(empty($detailManager)){
            $detailManager = $serviceManager->getManagerById($this->currentManagerId);
        }
        $this->view->detailManager = $detailManager;
    }
}

