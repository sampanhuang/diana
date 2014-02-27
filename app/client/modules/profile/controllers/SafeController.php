<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 下午11:14
 * To change this template use File | Settings | File Templates.
 */
class Profile_SafeController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 密码更新
     */
    function resetpwdAction(){
        if ($this->getRequest()->isPost()) {        
            $post = $this->getRequest()->getPost();
            $serviceCaptcha = new Diana_Service_Captcha();
            if ($serviceCaptcha->checkCaptchaWord($post['captcha'],"client-member-resetpwd")) {
                $serviceMemberPassword = new Client_Service_MemberPassword();
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
     * 更新帐号
     */
    function updateAction()
    {
        $request = $this->getRequest();
        $serviceMember = new Diana_Service_Member();
        $condition = array('log_memberId' => $this->currentMemberId);//日志查询条件
        //处理get提交
        $updateType = $request->getParam('type','name');
        if($updateType == 'name'){
            $this->view->typeLabel = $typeLabel = '帐号';
            $this->view->captchaKey = $captchaKey = 'member-update-name';//验证码标识
            $condition['log_type'] = 130;//日志查询条件
        }elseif($updateType == 'email'){
            $this->view->typeLabel = $typeLabel = '邮箱';
            $this->view->captchaKey = $captchaKey = 'member-update-email';//验证码标识
            $condition['log_type'] = 120;//日志查询条件
        }else{
            $this->setMsgs('无效的外部参数 - type');
            return false;
        }
        //处理POST提交
        if ($this->getRequest()->isPost()) {        
            $post = $this->getRequest()->getPost();
            $serviceCaptcha = new Diana_Service_Captcha();
            if ($serviceCaptcha->checkCaptchaWord($post['captcha'],$captchaKey)) {
                if($detailMember = $serviceMember->updateNameEmail($this->currentMemberId,$post['value_new'],$updateType,$post['password'])){
                    $this->setMsgs('更新成功');
                }else{
                    $this->setMsgs('更新失败');
                    $this->setMsgs($serviceMember->getMsgs());
                }
            }else{
                $this->setMsgs('验证码输入错误');
            }
        }
        if(empty($detailMember)){
            $detailMember = $serviceMember->getMemberById($this->currentMemberId);
        }
        $this->view->detailMember = $detailMember;
        $serviceMemberLog = new Diana_Service_MemberLog();
        if($state = $serviceMemberLog->getState($condition)){
            $this->view->state = $state;
        }
    }
}

