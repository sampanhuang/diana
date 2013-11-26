<?php
/**
 * 看门人
 * 负责迎接（登录），关门（注销），找钥匙（取回密码）
 *
 */
class Www_Service_Doorkeeper extends Diana_Service_Abstract
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
        //return 1;
        //判断各项session不能为空
        $sessionMember = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MEMBER);
        if (empty($sessionMember->id)) {
            $this->setMsgs('会话超时！你需要重新登录');
            return false;
        }
        return $sessionMember->id;
    }

    /**
     * 判断当前用户是否具有权限可以进入这个功能执行模块里面
     *
     * @param string $module 模块名
     * @param string $contro 控制器
     * @param string $action 选项
     * @param array $member 用户会话信息，从session中取得，一般是从checkLogin()返回
     * @return bool
     */
    function checkPower($module,$contro,$action,$memberId)
    {
        //各项参数不能为空
        if ((empty($module))||empty($contro)||empty($action)||empty($memberId)) {
            $this->setMsgs("个别参数不能为空".implode(',',array($module,$contro,$action,$memberId)));
            return false;
        }
        //各项参数类型必须正确
        if ((!is_string($module))||(!is_string($contro))||(!is_string($action))||(!is_numeric($memberId))) {
            $this->setMsgs("个别参数类型错误");
            return false;
        }
        //读取管理员信息
        if (!$detailMember = $this->checkMember($memberId)) {
            $this->setMsgs("无效的会话数据 - {$memberId}");
            return false;
        }
        //判断权限
        if($module <> 'default'){
            $serviceMenu = new Diana_Service_MemberMenu();
            if (!$serviceMenu->check($detailMember['role_menuTree'],$module,$contro,$action)) {
                $this->setMsgs('你并没有载入当前功能的权限');
                return false;
            }
        }
        return $detailMember;
    }

    /**
     * 注册
     * @param $name
     * @param $email
     * @param $passwd
     * @param $captcha
     * @return array|bool
     */
    function register($name,$email,$passwd,$captcha)
    {
        //确认外部参数是否正确
        if (empty($name)||empty($email)||empty($passwd)||empty($captcha)) {
            $this->setMsgs("各项参数不能为空");
            return false;
        }
        if ((!is_scalar($name))||(!is_scalar($email))||(!is_scalar($passwd))||(!is_scalar($captcha))) {
            $this->setMsgs("各项参数必须为标量");
            return false;
        }
        //判断验证码是否正确
        $serviceCaptcha = new Diana_Service_Captcha();
        if (!$serviceCaptcha->checkCaptchaWord($captcha,"www-member-register")) {
            $this->focus = 5;
            $this->setMsgs($serviceCaptcha->getMsgs());
            return false;
        }
        //判断用户邮箱是否正确
        $modelMember = new Diana_Model_Member();
        if($rowsMember = $modelMember->getRowsByName(null,$name)){
            $this->focus = 1;
            $this->setMsgs("当前用户名【".$name."】已经存在");
            return false;
        }
        if($rowsMember = $modelMember->getRowsByEmail(null,$email)){
            $this->focus = 2;
            $this->setMsgs("当前邮箱【".$email."】已经存在");
            return false;
        }
        if(!$rowsMember = $modelMember->register(1,$email,$name,$passwd)){
            $this->focus = 0;
            $this->setMsgs("会员注册失败");
            return false;
        }
        $modelMemberTrendRegister = new Diana_Model_MemberTrendRegister();
        $modelMemberTrendRegister->update();

        if (!$detailMember = $this->checkMember($rowsMember[0]['member_id'])) {
            return false;
        }
        //更新最后登录时间
        $modelMember = new Diana_Model_Member();
        if ($rowsMember = $modelMember->updateWithLogin($detailMember['member_id'])) {
            $detailMember = array_merge($detailMember,$rowsMember[0]);//更新值
        }
        //写入注册日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if(!$rowsMemberLog = $serviceMemberLog->write(11,$detailMember['member_id'],$detailMember['member_email'],$detailMember['member_name'])){
        	$this->setMsgs($serviceMemberLog->getMsgs());
        }
        //写入登录日志
        if(!$rowsMemberLog = $serviceMemberLog->write(21,$detailMember['member_id'],$detailMember['member_email'],$detailMember['member_name'])){
        	$this->setMsgs($serviceMemberLog->getMsgs());
        }
        //更新统计
        $modelMemberTrendLogin = new Diana_Model_MemberTrendLogin();
        $modelMemberTrendLogin->update();
        //写入会话
        $this->writeSession($detailMember);
        return $detailMember;
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
        if (!$serviceCaptcha->checkCaptchaWord($captcha,"www-member-login")) {
            $this->focus = 3;
            $this->setMsgs($serviceCaptcha->getMsgs());
            return false;
        }
        //判断用户是否为正确的用户
        if (!$detailMember = $this->checkMember(null,$email)) {
            $this->focus = 1;
            return false;
        }
        if ($detailMember['member_passwd'] <> $passwd) {
            $this->focus = 2;
            $this->setMsgs("密码输入错误！".$detailMember['member_passwd'].'-'.$passwd);
            return false;
        }
        //更新最后登录时间
        $modelMember = new Diana_Model_Member();
        if ($rowsMember = $modelMember->updateWithLogin($detailMember['member_id'])) {
            $detailMember = array_merge($detailMember,$rowsMember[0]);//更新值
        }
        //写入登录日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if(!$rowsMemberLog = $serviceMemberLog->write(21,$detailMember['member_id'],$detailMember['member_email'],$detailMember['member_name'])){
        	$this->setMsgs($serviceMemberLog->getMsgs());
        }
        //更新统计
        $modelMemberTrendLogin = new Diana_Model_MemberTrendLogin();
        $modelMemberTrendLogin->update();
        //写入会话
        $this->writeSession($detailMember);
        return $detailMember;
    }

    /**
     * 写入会话
     *
     * @param array $detailMember
     */
    function writeSession($detailMember)
    {
        //写入session
        $sessionMember = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MEMBER);
        if ($sessionMember->isLocked()) {//如果被锁定了就解锁
            $sessionMember->unLock();
        }
        $sessionMember->id = $detailMember["member_id"];//用户ID
        $sessionMember->email = $detailMember["member_email"];//用户邮箱
        $sessionMember->name = $detailMember["member_name"];//用户邮箱
        $sessionMember->lock();
        return $sessionMember;
    }

    /**
     * 注销会话
     *
     * @return array
     */
    function logout()
    {
        $sessionMember = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MEMBER);
        if (empty($sessionMember->id)||empty($sessionMember->email)) {
            return false;
        }else{
            if (!$detailMember = $this->checkMember($sessionMember->id)) {
                $this->setMsgs("当前帐号退出异常");
                return false;
            }
            if ($sessionMember->isLocked()) {
                $sessionMember->unLock();
            }
            $sessionMember->unsetAll();
            return $detailMember;
        }
    }


    /**
     * 判断当前用户是否为有效的登录用户
     * 根据id或是email得到用户信息，然后再获取角色信息
     * 最终格式化角色信息中的资源访问范围
     * （整个过程不涉及session）
     * @param int $id 用户ID
     * @param string $loginUser  登录帐号
     */
    function checkMember($id = null,$loginUser = null)
    {
        if (empty($id)&&empty($loginUser)) {
            $this->setMsgs("当前帐号【{$id}{$loginUser}】不能为空");
            return false;
        }
        //判断用户邮箱是否正确
        $serviceMember = new Diana_Service_Member();
        if ((!empty($id))&&(is_numeric($id))) {//通过ID获取
            $detailMember = $serviceMember->getMemberById($id);
        }elseif ((!empty($loginUser))&&(is_string($loginUser))){//通过email获取
            if(!$detailMember = $serviceMember->getMemberByEmail($loginUser)){
                $detailMember = $serviceMember->getMemberByName($loginUser);
            }
        }
        //能否根据条件得到用户信息
        if (!$detailMember) {
            $this->setMsgs($serviceMember->getMsgs());
            $this->setMsgs("当前帐号【｛$id｝{$loginUser}】并不存在");
            return false;
        }
        //判断用户是否被锁定
        if ($detailMember["member_lock_time"] > time()) {
            $this->setMsgs("当前帐号【".$detailMember["member_email"]." - ".$detailMember["member_name"]."】已被锁定，解锁时间-".date("Y-m-d H:i",$detailMember["member_lock_time"]));
            return false;
        }
        //判断角色是否被锁定
        if ($detailMember["role_lock_time"] > time()) {
            $this->setMsgs("当前权限组【".$detailMember["role_label"]."】已被锁定，解锁时间-".date("Y-m-d H:i",$detailMember["role_lock_time"]));
            return false;
        }
        //资源有效性判断，并生成资源树，存进role_resource_trees
        if (empty($detailMember["role_menuTree"])||empty($detailMember["role_menu"])) {
            $this->setMsgs("无法加载菜单！");
            return false;
        }
        return $detailMember;
    }

    /**
     * cookie名
     *
     * @return string
     */
    function getCookieWithLastime()
    {
        return 'Member-Login-Email';
    }


}