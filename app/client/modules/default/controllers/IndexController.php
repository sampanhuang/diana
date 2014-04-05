<?php
/**
 * 首页
 *
 */
class IndexController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 首页
     *
     */
    function indexAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $requestInput = $this->getRequest()->getParams();
        if(!empty($requestInput['url_redirect'])){
            $tmpUrlDefault = array_filter(explode('/',$requestInput['url_redirect']));
            $tmpUrlLabel = array_slice($tmpUrlDefault, 0, 3);
            $this->view->tagAuto = array(
                'label' => implode('/',$tmpUrlLabel),
                'link' => $requestInput['url_redirect'],
            );
        }
        if ($requestInput['show_data'] == 'menu_tree') {
            echo json_encode($this->currentMemberRoleMenu);
        }
    }

    function welcomeAction()
    {
        //$this->getHelper("layout")->disableLayout();//关闭布局
        //$this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $arrIpAddr = array();//array(ip=> addre)
        //获取日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if($dataMemberLogLogin = $serviceMemberLog->makeDataGrid(array('page' => 1,'rows' => 5,'log_type' => DIANA_MANAGERLOG_TYPE_LOGIN))){
            foreach($dataMemberLogLogin['rows'] as $tmpRow){
                $arrIpAddr[$tmpRow['log_ip']] = '';
            }
            $this->view->rowsMemberLogLogin = $dataMemberLogLogin['rows'];//登录日志
        }
        if($dataMemberLogResetPwdAfterLogin = $serviceMemberLog->makeDataGrid(array('page' => 1,'rows' => 5,'log_type' => DIANA_MANAGERLOG_TYPE_RESETPWD_AFTER_LOGIN))){
            foreach($dataMemberLogResetPwdAfterLogin['rows'] as $tmpRow){
                $arrIpAddr[$tmpRow['log_ip']] = '';
            }
            $this->view->rowsMemberLogResetPwdAfterLogin = $dataMemberLogResetPwdAfterLogin['rows'];//修改密码，登录后
        }
        if($dataMemberLogResetPwdBeforeLogin = $serviceMemberLog->makeDataGrid(array('page' => 1,'rows' => 5,'log_type' => DIANA_MANAGERLOG_TYPE_RESETPWD_BEFORE_LOGIN))){
            foreach($dataMemberLogResetPwdBeforeLogin['rows'] as $tmpRow){
                $arrIpAddr[$tmpRow['log_ip']] = '';
            }
            $this->view->rowsMemberLogResetPwdBeforeLogin = $dataMemberLogResetPwdBeforeLogin['rows'];//修改密码，登录前
        }
        //IP转换地址
        if(!empty($arrIpAddr)){
            $comIpLocation = new Com_IpLocation(DIANA_PATH_DATA_IPLIBS);
            foreach($arrIpAddr as $ip => &$addr){
                $addr = $comIpLocation->getaddressutf($ip);
            }
            $this->view->arrIpAddr = $arrIpAddr;
        }


        //获取环境变量
        $this->view->servInfo = $_SERVER;
        $this->view->phpInfo = ini_get_all();
    }

    function testAction()
    {
        //$this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $url = $this->getRequest()->getParam("url");
        $snKey = "website_register";
        //判断验证码是否正确
        $snKey = "captcha_".$snKey;
        $sessionNamespace = new Zend_Session_Namespace($snKey);
        $sessionWord = $sessionNamespace->word;
        echo $sessionWord;
    }

    /**
     * 频道
     *
     */
    function channelAction()
    {
        //$this->getHelper("layout")->disableLayout();//关闭布局
        //$this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $this->_forward("index","website","default") ;
    }



}