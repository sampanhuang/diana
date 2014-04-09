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

}