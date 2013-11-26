<?php
    /**
     * 访客
     *
     */
class Member_GuestController extends Www_Controller_Action
{
    function init()
    {
        parent::init();

    }

    function loginAction()
    {
        $this->view->headTitle("会员登录");
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        $cookieName = $serviceDoorkeeper->getCookieWithLastime();//获取cookie名
        if ($cookieValue = $_COOKIE[$cookieName]) {//获取最后一名登录者的名字
            $this->view->emailLasttime = $cookieValue;
            $this->view->cookieNameWithEmail = $cookieName;
        }
        $dataget = array_map("trim",array_filter($this->getRequest()->getParams()));
        if (!empty($dataget["show_data"])) {
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            $show = array("stat" => 0,"msgs" => "");
            if ($dataget["show_data"] == "login") {
                if ($detailManager = $serviceDoorkeeper->login($dataget["email"],$dataget["passwd"],$dataget["captcha"])) {
                    setcookie($cookieName, $detailManager['manager_email'], time()+311040000, "/", $_SERVER['SERVER_NAME']);
                    $show["stat"] = 1;
                }else{
                    $show['focus'] = $serviceDoorkeeper->focus;
                    $show["msgs"] = implode(",",$serviceDoorkeeper->getMsgs());
                }
            }elseif ($dataget['show_data'] = 'clear-cookie'){
                try {
                    setcookie($cookieName, '', time()+311040000, "/", $_SERVER['SERVER_NAME']);
                    $show['stat'] = 1;
                }catch (Exception $e){
                    $show['msgs'] = $e->getMessage();
                }
            }
            ob_clean();
            header('content-type: application/json; charset=utf-8');
            echo json_encode($show);
        }
    }

    function registerAction()
    {
        $this->view->headTitle("会员注册");
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        $dataget = array_map("trim",array_filter($this->getRequest()->getParams()));
        if (!empty($dataget["show_data"])) {
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            $show = array("stat" => 0,"msgs" => "");
            if ($dataget["show_data"] == "register") {
                if ($detailManager = $serviceDoorkeeper->register($dataget["name"],$dataget["email"],$dataget["passwd"],$dataget["captcha"])) {
                    $show["stat"] = 1;
                }else{
                    $show['focus'] = $serviceDoorkeeper->focus;
                    $show["msgs"] = implode(",",$serviceDoorkeeper->getMsgs());
                }
            }
            ob_clean();
            header('content-type: application/json; charset=utf-8');
            echo json_encode($show);
        }
    }

    function forgetpwdAction()
    {
        $this->view->headTitle("忘记密码");
        $this->view->dataget = $dataget = array_map("trim",array_filter($this->getRequest()->getParams()));
        $this->view->step = $step = $dataget['step'];
        if($dataget['step'] == 2){//更新密码
            $serviceMemberPassword = new Www_Service_MemberPassword();
            if ($serviceMemberPassword->forgetPwd(2,$dataget)) {
                $serviceMember = new Diana_Service_Member();
                $this->setMsgs('你已经成功更新了密码！如果忘记了请返回你的邮箱查看。建议你在登录之后再修改一次密码（改成你好记又安全的）');
                if ($memberDetail = $serviceMember->getMemberById($dataget['id'])) {
                    $this->view->memberDetail = $memberDetail;
                    $this->view->dataget = $dataget;
                }else{
                    $this->setMsgs($serviceMember->getMsgs());
                }
            }else{
                $this->setMsgs('无效的链接');
                $this->setMsgs($serviceMemberPassword->getMsgs());
            }
        }
        if (!empty($dataget["show_data"])) {
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            $show = array("stat" => 0,"msgs" => "");
            if ($dataget["show_data"] == "sendmail") {
                $serviceMemberPassword = new Www_Service_MemberPassword();
                if ($detailMember = $serviceMemberPassword->forgetPwd(1,$dataget)){
                    $show["stat"] = 1;
                }else{
                    $show["msgs"] = implode(",",$serviceMemberPassword->getMsgs());
                }
            }
            ob_clean();
            header('content-type: application/json; charset=utf-8');
            echo json_encode($show);
        }
    }

    function logoutAction()
    {
        $this->view->headTitle("注销登录");
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        if ($detailMember = $serviceDoorkeeper->logout()) {
            $this->view->member = $detailMember;
        }else{
            $this->redirect("/member/guest/login");
        }
    }

    function menuAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        if ($sessionMemberId = $serviceDoorkeeper->checkSession()) {
            if ($detailMember = $serviceDoorkeeper->checkMember($sessionMemberId)) {
                $this->view->detailMember = $detailMember;
            }
        }
    }

    function ajaxAction()
    {
        $this->view->dataget = $dataget = array_map("trim",array_filter($this->getRequest()->getParams()));
        if (!empty($dataget["show_data"])) {
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            $show = array("stat" => 0,"msgs" => "",'items' => array());
            if ($dataget["show_data"] == "member") {
                $serviceDoorkeeper = new Www_Service_Doorkeeper();
                if ($sessionMemberId = $serviceDoorkeeper->checkSession()) {
                    if ($detailMember = $serviceDoorkeeper->checkMember($sessionMemberId)) {
                        $show = array("stat" => 1,"msgs" => "",'items' => $detailMember);
                    }else{
                        $show['msgs'] = implode(";",$serviceDoorkeeper->getMsgs());
                    }
                }else{
                    $show['msgs'] = implode(";",$serviceDoorkeeper->getMsgs());
                }
            }
            ob_clean();
            header('content-type: application/json; charset=utf-8');
            echo json_encode($show);
        }
    }


}