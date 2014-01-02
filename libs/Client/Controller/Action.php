<?php
/**
 * 控制类
 *
 */
class Client_Controller_Action extends Diana_Controller_Action
{
    var $currentMemberDetail;//当前用户详细资料
    var $currentMemberId;//当前用户ID
    var $currentMemberEmail;//当前用户帐号
    var $currentMemberName;//当前用户姓名
    var $currentMemberRoleId;//当前用户角色ID
    var $currentMemberRoleMenu;//当前用户菜单
    var $currentMemberRoleMenuTree;//当前用户树
    var $showBreadcrumb ;//是否显示选项导航栏
    function init()
    {
        parent::init();
        //设置jquery
        $this->setJqueryLang();
        //判断是否为已登录用户，且达到条件进入这个功能模块模块
        $serviceDoorkeeper = new Client_Service_Doorkeeper();
        if (!$sessionMemberId = $serviceDoorkeeper->checkSession()) {
            $this->redirect('/default/guest/login/check/fail');
            exit();
        }
        if (!$detailMember = $serviceDoorkeeper->checkPower($this->currentModuleName,$this->currentControllerName,$this->currentActionName,$sessionMemberId)) {
            $dataget = $this->getRequest()->getParams();
            if ((!empty($dataget['data_ajax']))&&(!empty($dataget['show_ajax']))){
                $tmpJsonPower = array(
                    'stat' => 0,
                    'msgs' => '很可惜啊，你没有权限进行当前操作!<br>如果有需要，请联系管理员吧<br>'.implode(';',$serviceDoorkeeper->getMsgs()),
                );
                echo json_encode($tmpJsonPower);
            }else{
                echo $sessionMemberId;
                //$this->redirect('/default/guest/warning/content/'.implode('<br>',$serviceDoorkeeper->getMsgs()));
            }
            exit();
        }
        $this->view->currentMemberDetail = $this->currentMemberDetail = $detailMember;//用户详细资料
        $this->view->currentMemberId = $this->currentMemberId = $detailMember['member_id'];//用户ID
        $this->view->currentMemberEmail = $this->currentMemberEmail = $detailMember['member_email'];//用户帐号
        $this->view->currentMemberName = $this->currentMemberName = $detailMember['member_name'];//用户姓名
        $this->view->currentMemberRoleId = $this->currentMemberRoleId = $detailMember['rold_id'];//角色ID
        $this->view->currentMemberRoleMenu = $this->currentMemberRoleMenu = $detailMember['role_menu'];//角色资源范围
        $this->view->currentMemberRoleMenuTree = $this->currentMemberRoleMenuTree = $detailMember['role_menuTree'];//角色资源范围
        //print_r($detailMember['breadcrumb']);
        //获取当前位置
        $currentModuleLabel = $this->view->currentModuleLabel = $this->currentMemberRoleMenuTree[$this->currentModuleName]["menu_label"];
        $currentControllerLabel = $this->view->currentControllerLabel = $this->currentMemberRoleMenuTree[$this->currentModuleName]["son"][$this->currentControllerName]["menu_label"];
        $currentActionLabel = $this->view->currentActionLabel = $this->currentMemberRoleMenuTree[$this->currentModuleName]["son"][$this->currentControllerName]["son"][$this->currentActionName]["menu_label"];

        //当前导航
        $this->view->headTitle(implode(">",array($currentModuleLabel,$currentControllerLabel,$currentActionLabel)));
        $this->view->headTitle("[".implode("::",array_filter(array($this->currentMemberEmail,$this->currentMemberName)))."]");
        $this->view->headTitle("--".DIANA_WEBSITE_TITLE);

    }



    public function postDispatch()
    {
        parent::postDispatch();
    }
}