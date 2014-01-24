<?php
/**
 * 控制类
 *
 */
class Admin_Controller_Action extends Diana_Controller_Action
{
    var $currentManagerDetail;//当前用户详细资料
    var $currentManagerId;//当前用户ID
    var $currentManagerEmail;//当前用户帐号
    var $currentManagerName;//当前用户姓名
    var $currentManagerRoleId;//当前用户角色ID
    var $currentManagerRoleMenu;//当前用户菜单
    var $currentManagerRoleMenuTree;//当前用户树
    var $showBreadcrumb ;//是否显示选项导航栏
    function init()
    {
        parent::init();
        //设置jquery
        $this->setJqueryLang();
        //判断是否为已登录用户，且达到条件进入这个功能模块模块
        $serviceDoorkeeper = new Admin_Service_Doorkeeper();
        if (!$sessionManagerId = $serviceDoorkeeper->checkSession()) {
            $this->redirect('/default/guest/login/check/fail');
            exit();
        }
        if (!$detailManager = $serviceDoorkeeper->checkPower($this->currentModuleName,$this->currentControllerName,$this->currentActionName,$sessionManagerId)) {
            $dataget = $this->getRequest()->getParams();
            if ((!empty($dataget['data_ajax']))&&(!empty($dataget['show_ajax']))){
                $tmpJsonPower = array(
                    'stat' => 0,
                    'msgs' => '很可惜啊，你没有权限进行当前操作!<br>如果有需要，请联系管理员吧<br>'.implode(';',$serviceDoorkeeper->getMsgs()),
                );
                echo json_encode($tmpJsonPower);
            }else{
                $this->redirect('/default/guest/warning/content/'.implode('<br>',$serviceDoorkeeper->getMsgs()));
            }
            exit();
        }
        $this->view->currentManagerDetail = $this->currentManagerDetail = $detailManager;//用户详细资料
        $this->view->currentManagerId = $this->currentManagerId = $detailManager['manager_id'];//用户ID
        $this->view->currentManagerEmail = $this->currentManagerEmail = $detailManager['manager_email'];//用户帐号
        $this->view->currentManagerName = $this->currentManagerName = $detailManager['manager_name'];//用户姓名
        $this->view->currentManagerRoleId = $this->currentManagerRoleId = $detailManager['rold_id'];//角色ID
        $this->view->currentManagerRoleMenu = $this->currentManagerRoleMenu = $detailManager['role_menu'];//角色资源范围
        $this->view->currentManagerRoleMenuTree = $this->currentManagerRoleMenuTree = $detailManager['role_menuTree'];//角色资源范围
        //print_r($detailManager['breadcrumb']);
        //获取当前位置
        $currentModuleLabel = $this->view->currentModuleLabel = $this->currentManagerRoleMenuTree[$this->currentModuleName]["menu_label"];
        $currentControllerLabel = $this->view->currentControllerLabel = $this->currentManagerRoleMenuTree[$this->currentModuleName]["son"][$this->currentControllerName]["menu_label"];
        $currentActionLabel = $this->view->currentActionLabel = $this->currentManagerRoleMenuTree[$this->currentModuleName]["son"][$this->currentControllerName]["son"][$this->currentActionName]["menu_label"];

        //当前导航
        $this->view->headTitle(implode(">",array($currentModuleLabel,$currentControllerLabel,$currentActionLabel)));
        $this->view->headTitle("[".implode("::",array_filter(array($this->currentManagerEmail,$this->currentManagerName)))."]");
        $this->view->headTitle("--".DIANA_WEBSITE_TITLE);


        $serviceConfig = new Diana_Service_Config();
        $this->debug['ajax_type'] = $serviceConfig->getValueByKey('debug_ajax_type_admin');
        $this->debug['ajax_on_clean'] = $serviceConfig->getValueByKey('debug_ajax_ob_clean_admin');

    }


    public function postDispatch()
    {
        parent::postDispatch();
    }
}