<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-3-4
 * Time: 下午1:57
 * To change this template use File | Settings | File Templates.
 */
class MemberController extends Www_Controller_Action
{


    function init()
    {
        parent::init();

    }

    function indexAction()
    {

    }

    /**
     * 会员注册
     */
    function registerAction()
    {
        $this->view->headTitle("会员注册");
        $serviceConfig = new Diana_Service_Config();

        if(!$configMemberRegisterEnable = $serviceConfig->getValueByKey('member-register_enable')){
            $this->setMsgs('你需要设置全局参数 member-register_open ');
            return false;
        }
        if($configMemberRegisterEnable == 2){
            if(!$configMemberRegisterDisableNotice = $serviceConfig->getValueByKey('member-register_disable-notice')){
                $this->setMsgs('你需要设置全局参数 member-register_disable-notice ');
                return false;
            }
            $this->setMsgs($configMemberRegisterDisableNotice);
        }
        $this->view->configMemberRegisterEnable = $configMemberRegisterEnable;
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        $requestParams = array_map("trim",array_filter($this->getRequest()->getParams()));
        if (!empty($requestParams["show_data"])) {
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            $show = array("stat" => 0,"msgs" => "");
            if ($requestParams["show_data"] == "register") {
                if($configMemberRegisterEnable == 1){
                    if ($detailManager = $serviceDoorkeeper->register($requestParams["name"],$requestParams["email"],$requestParams["passwd"],$requestParams["captcha"])) {
                        $show["stat"] = 1;
                    }else{
                        $show['focus'] = $serviceDoorkeeper->focus;
                        $show["msgs"] = implode(",",$serviceDoorkeeper->getMsgs());
                    }
                }elseif($configMemberRegisterOpen == 2){
                    $show['focus'] = $serviceDoorkeeper->focus;
                    $show["msgs"] = $configMemberRegisterDisableNotice;
                }
            }
            ob_clean();
            header('content-type: application/json; charset=utf-8');
            echo json_encode($show);
        }
    }

}