<?php
/**
 * 看门人
 * 负责迎接（登录），关门（注销），找钥匙（取回密码）
 *
 */
class Admin_Service_Doorkeeper extends Diana_Service_Abstract
{
    var $focus = 0;
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 确认会话是否正常
     * 如果不正常就会退出
     * 如果正常就返回会话信息
     *
     * @return int 管理员ID
     */
    function checkSession()
    {
        //判断各项session不能为空
        $sessionManager = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MANAGER);
        if (empty($sessionManager->id)) {
            $this->setMsgs('会话超时！你需要重新登录');
            return false;
        }
        return $sessionManager->id;
    }

    /**
     * 判断当前用户是否具有权限可以进入这个功能执行模块里面
     *
     * @param string $module 模块名
     * @param string $contro 控制器
     * @param string $action 选项
     * @param array $manager 用户会话信息，从session中取得，一般是从checkLogin()返回
     * @return bool
     */
    function checkPower($module,$contro,$action,$managerId)
    {
        //各项参数不能为空
        if ((empty($module))||empty($contro)||empty($action)||empty($managerId)) {
            $this->setMsgs("个别参数不能为空".implode(',',array($module,$contro,$action,$managerId)));
            return false;
        }
        //各项参数类型必须正确
        if ((!is_string($module))||(!is_string($contro))||(!is_string($action))||(!is_numeric($managerId))) {
            $this->setMsgs("个别参数类型错误");
            return false;
        }
        //读取管理员信息
        if (!$detailManager = $this->checkManager($managerId)) {
            $this->setMsgs("无效的会话数据 - {$managerId}");
            return false;
        }
        //判断权限
        if($module <> 'default'){
            $serviceMenu = new Admin_Service_ManagerMenu();
            if (!$serviceMenu->check($detailManager['role_menuTree'],$module,$contro,$action)) {
                $this->setMsgs('你并没有载入当前功能的权限');
                return false;
            }
        }
        return $detailManager;
    }

    /**
     * 用户登录
     *
     * @param string $email 用户帐号
     * @param string $passwd 用户密码
     * @param string $captcha 验证码
     * @return array
     */
    function login($email,$passwd,$captcha)
    {
        //确认外部参数是否正确
        if (empty($email)||empty($passwd)||empty($captcha)) {
            $this->setMsgs("各项参数不能为空");
            return false;
        }
        if ((!is_scalar($email))||(!is_scalar($passwd))||(!is_scalar($captcha))) {
            $this->setMsgs("各项参数必须为标量");
            return false;
        }
        //判断验证码是否正确
        $serviceCaptcha = new Diana_Service_Captcha();
        if (!$serviceCaptcha->checkCaptchaWord($captcha,"admin-manager-login")) {
            $this->focus = 3;
            $this->setMsgs($serviceCaptcha->getMsgs());
            return false;
        }
        //判断用户是否为正确的用户
        if (!$detailManager = $this->checkManager(null,$email)) {
            $this->focus = 1;
            return false;
        }
        if ($detailManager['manager_passwd'] <> $passwd) {
            $this->focus = 2;
            $this->setMsgs("密码输入错误！");
            return false;
        }
        //更新最后登录时间
        $modelManager = new Diana_Model_Manager();
        if ($rowsManager = $modelManager->updateWithLogin($detailManager['manager_id'])) {
            $detailManager = array_merge($detailManager,$rowsManager[0]);//更新值
        }
        //写入登录纪录
        $serviceManagerLog = new Admin_Service_ManagerLog();
        if (!$serviceManagerLog = $serviceManagerLog->write(210,$detailManager['manager_id'],$detailManager['manager_email'],$detailManager['manager_name'])) {
            $this->setMsgs($serviceManagerLog->getMsgs());
        }
        //写入会话
        $this->writeSession($detailManager);
        return $detailManager;
    }

    /**
     * 写入会话
     *
     * @param array $detailManager
     */
    function writeSession($detailManager)
    {
        //写入session
        $sessionManager = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MANAGER);
        if ($sessionManager->isLocked()) {//如果被锁定了就解锁
            $sessionManager->unLock();
        }
        $sessionManager->id = $detailManager["manager_id"];//用户ID
        $sessionManager->email = $detailManager["manager_email"];//用户邮箱
        $sessionManager->name = $detailManager["manager_name"];//用户邮箱
        $sessionManager->lock();
        return $sessionManager;
    }

    /**
     * 注销会话
     *
     * @return array
     */
    function logout()
    {
        $sessionManager = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MANAGER);
        if (empty($sessionManager->id)||empty($sessionManager->email)) {
            return false;
        }else{
            if (!$detailManager = $this->checkManager($sessionManager->id)) {
                $this->setMsgs("当前帐号退出异常");
                return false;
            }
            if ($sessionManager->isLocked()) {
                $sessionManager->unLock();
            }
            $sessionManager->unsetAll();
            return $detailManager;
        }
    }


    /**
     * 判断当前用户是否为有效的登录用户
     * 根据id或是email得到用户信息，然后再获取角色信息
     * 最终格式化角色信息中的资源访问范围
     * （整个过程不涉及session）
     * @param int $id 用户ID
     * @param string $loginUser  用户帐号
     */
    function checkManager($id = null,$loginUser = null)
    {
        if (empty($id)&&empty($loginUser)) {
            $this->setMsgs("当前帐号【{$id}{$loginUser}】不能为空");
            return false;
        }
        //判断用户邮箱是否正确
        $serviceManager = new Admin_Service_Manager();
        if ((!empty($id))&&(is_numeric($id))) {//通过ID获取
            $detailManager = $serviceManager->getManagerById($id);
        }elseif ((!empty($loginUser))&&(is_string($loginUser))){//通过email获取
            if(!$detailManager = $serviceManager->getManagerByEmail($loginUser)){
                $detailManager = $serviceManager->getManagerByName($loginUser);
            }

        }
        //能否根据条件得到用户信息
        if (!$detailManager) {
            //$this->setMsgs($serviceManager->getMsgs());
            $this->setMsgs("当前帐号【 $id $loginUser 】并不存在");
            return false;
        }
        //判断用户是否被锁定
        if ($detailManager["manager_lock_time"] > time()) {
            $this->setMsgs("当前帐号【".$detailManager["manager_email"]." - ".$detailManager["manager_name"]."】已被锁定，解锁时间-".date("Y-m-d H:i",$detailManager["manager_lock_time"]));
            return false;
        }
        //判断角色是否被锁定
        if ($detailManager["role_lock_time"] > time()) {
            $this->setMsgs("当前权限组【".$detailManager["role_label"]."】已被锁定，解锁时间-".date("Y-m-d H:i",$detailManager["role_lock_time"]));
            return false;
        }
        //资源有效性判断，并生成资源树，存进role_resource_trees
        if (empty($detailManager["role_menuTree"])||empty($detailManager["role_menu"])) {
            $this->setMsgs("无法加载菜单！");
            return false;
        }
        return $detailManager;
    }

    /**
     * cookie名
     *
     * @return string
     */
    function getCookieWithLastime()
    {
        return 'loginEmail';
    }


}