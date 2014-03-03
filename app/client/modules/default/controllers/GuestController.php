<?php
/**
 * 首页
 *
 */
class GuestController extends Diana_Controller_Action
{
    var $menus = array();
    function init()
    {
        parent::init();
        //$this->_helper->layout->setLayout('guest');
        $this->view->menus = $this->menus = array(
            array(
                'action' => 'login',
                'label' => '会员登录',
            ),
            array(
                'action' => 'logout',
                'label' => '注销登录',
            ),
            array(
                'action' => 'forgetpwd',
                'label' => '忘记密码',
            ),
        );
    }

    /**
     * 首页
     *
     */
    function indexAction()
    {
        //$this->getHelper("layout")->disableLayout();//关闭布局
    }

    /**
     * 提示，警告
     */
    function warningAction()
    {
        $this->_helper->layout->setLayout('layouts');
        $this->view->content = $this->getRequest()->getParam('content');
        $this->setMsgs($this->view->content);
    }

    /**
     * 登录
     */
    function loginAction()
    {
        $this->view->headTitle("会员登录");
        $serviceDoorkeeper = new Client_Service_Doorkeeper();
        $cookieName = $serviceDoorkeeper->getCookieWithLastime();//获取cookie名
        if ($cookieValue = $_COOKIE[$cookieName]) {//获取最后一名登录者的名字
            $this->view->emailLasttime = $cookieValue;
            $this->view->cookieNameWithEmail = $cookieName;
        }
        $requestInput = array_map("trim",array_filter($this->getRequest()->getParams()));
        $this->view->urlTarget = '/';
        if(!empty($requestInput['url_redirect'])){
            $this->view->urlTarget = '/?url_redirect='.urlencode($requestInput['url_redirect']);
        }
        if (!empty($requestInput["show_data"])) {
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            $show = array("stat" => 0,"msgs" => "");
            if ($requestInput["show_data"] == "login") {
                if ($detailMember = $serviceDoorkeeper->login($requestInput["email"],$requestInput["passwd"],$requestInput["captcha"])) {
                    setcookie($cookieName, $detailMember['manager_email'], time()+311040000, "/", $_SERVER['SERVER_NAME']);
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

    /**
     * 注销登录
     */
    function logoutAction()
    {
        $this->view->headTitle("注销登录");
        $serviceDoorkeeper = new Client_Service_Doorkeeper();
        if ($detailMember = $serviceDoorkeeper->logout()) {
            $this->view->manager = $detailMember;
        }else{
            //$this->redirect("/default/guest/login");
        }
    }


    /**
     * 忘记密码
     */
    function forgetpwdAction()
    {
        $this->view->headTitle("忘记密码");
        $this->view->dataget = $dataget = array_map("trim",array_filter($this->getRequest()->getParams()));
        $this->view->step = $step = $dataget['step'];
        if($dataget['step'] == 2){
            $serviceMemberPassword = new Client_Service_MemberPassword();
            if ($serviceMemberPassword->forgetPwd(2,$dataget)) {
                $serviceMember = new Client_Service_Member();
                $this->view->alert = '你已经成功更新了密码！如果忘记了请返回你的邮箱查看。建议你在登录之后再修改一次密码（改成你好记又安全的）';
                if ($managerDetail = $serviceMember->getMemberById($dataget['id'])) {
                    $this->view->managerDetail = $managerDetail;
                    $this->view->dataget = $dataget;
                }else{
                    $this->setMsgs($serviceMember->getMsgs());
                }
            }else{
                $this->view->alert = '无效的链接'.implode(';',$serviceMemberPassword->getMsgs());
            }
        }
        if (!empty($dataget["show_data"])) {
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            $show = array("stat" => 0,"msgs" => "");
            if ($dataget["show_data"] == "sendmail") {
                $serviceMemberPassword = new Client_Service_MemberPassword();
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

    /**
     * 验证码
     */
    function captchaAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $snKey = $this->getRequest()->getParam("key");
        //实例化验证码类
        $serviceCaptcha = new Diana_Service_Captcha();
        if ($imgCaptcha = $serviceCaptcha->outputCaptcha($snKey)) {
            header( "Content-type: image/jpeg");
            $PSize = filesize($imgCaptcha);
            $picturedata = fread(fopen($imgCaptcha, "r"), $PSize);
            echo $picturedata;
        }else{
            echo $serviceCaptcha->getMsgs();
        }
    }

    /**
     * 判断是否在线
     */
    function onlineAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $output = array('stat' => 0,'msgs' => '','result' => array());
        $sessionMember = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MEMBER);
        if(empty($sessionMember->id)){
            $output['msgs'] = '尚未登录';
        }else{
            $serviceMemberMsg = new Client_Service_MemberMsg();
            $countMsgUnread = $serviceMemberMsg->getUnreadWithInbox($sessionMember->id);
            $output['stat'] = 1;
            $output['result'] = array(
                'member' => array(
                    'id' => $sessionMember->id,
                    'name' => $sessionMember->name,
                    'email' => $sessionMember->email,
                    'msg_unread' => $countMsgUnread,//未读短信
                ),
            );
        }
        ob_clean();
        header('content-type: application/json; charset=utf-8');
        echo  $_REQUEST['jsoncallback'].'('.json_encode($output).')';
    }

}