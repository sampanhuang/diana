<?php
/**
 * 控制类
 *
 */
class Www_Controller_ActionMember extends Www_Controller_Action
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
        //判断是否为已登录用户，且达到条件进入这个功能模块模块
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        if (!$sessionMemberId = $serviceDoorkeeper->checkSession()) {
            $this->redirect('/member/guest/login/check/fail');
            exit();
        }
        if (!$detailMember = $serviceDoorkeeper->checkPower($this->currentModuleName,$this->currentControllerName,$this->currentActionName,$sessionMemberId)) {
            throw new Exception(implode('<br>',$serviceDoorkeeper->getMsgs()));
            exit();
        }
        $this->view->currentMemberDetail = $this->currentMemberDetail = $detailMember;//用户详细资料
        $this->view->currentMemberId = $this->currentMemberId = $detailMember['member_id'];//用户ID
        $this->view->currentMemberEmail = $this->currentMemberEmail = $detailMember['member_email'];//用户帐号
        $this->view->currentMemberName = $this->currentMemberName = $detailMember['member_name'];//用户姓名
        $this->view->currentMemberRoleId = $this->currentMemberRoleId = $detailMember['rold_id'];//角色ID
        $this->view->currentMemberRoleMenu = $this->currentMemberRoleMenu = $detailMember['role_menu'];//角色资源范围
        $this->view->currentMemberRoleMenuTree = $this->currentMemberRoleMenuTree = $detailMember['role_menuTree'];//角色资源范围

        //获取当前位置
        $currentModuleLabel = $this->view->currentModuleLabel = $this->currentMemberRoleMenuTree[$this->currentModuleName]['menu_label_'.DIANA_TRANSLATE_CURRENT];
        $currentControllerLabel = $this->view->currentControllerLabel = $this->currentMemberRoleMenuTree[$this->currentModuleName]["son"][$this->currentControllerName]['menu_label_'.DIANA_TRANSLATE_CURRENT];
        $currentActionLabel = $this->view->currentActionLabel = $this->currentMemberRoleMenuTree[$this->currentModuleName]["son"][$this->currentControllerName]["son"][$this->currentActionName]['menu_label_'.DIANA_TRANSLATE_CURRENT];

        //当前导航
        $this->view->headTitle(implode(">",array($currentModuleLabel,$currentControllerLabel,$currentActionLabel)));
        $this->view->headTitle("[".implode("::",array_filter(array($this->currentMemberEmail,$this->currentMemberName)))."]");

        //是否显示面包屑
        $this->view->showBreadcrumb = true;
        $this->breadcrumbActions = $this->currentMemberRoleResourceTrees[$this->currentModuleName]["controller"][$this->currentControllerName]["action"];


    }

    /**
     * 设置是否显示导航条
     *
     * @param int|bool $showBreadcrumb
     */
    function setShowBreadcrumb($showBreadcrumb)
    {
        $this->showBreadcrumb = $showBreadcrumb;
    }

    /**
     * 设置导航下拉选项
     *
     * @param array $breadcrumbActions array("key" => xxx,"label" => "xxx")
     */
    function showBreadcrumbActions($breadcrumbActions)
    {
        $this->breadcrumbActions = $breadcrumbActions;
    }

    public function postDispatch()
    {
        parent::postDispatch();
        $this->view->showBreadcrumb = $this->showBreadcrumb;
        $this->view->breadcrumbActions = $this->breadcrumbActions;
    }
}