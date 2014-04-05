<?php
/**
 * 密码重设
 * 忘记密码
 *
 */
class Www_Service_MemberPassword extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 重设密码
     *
     * @param int $id 用户ID
     * @param string $pwdOld 旧密码
     * @param string $pwdNew 新密码
     * @return bool 是否成功
     */
    function resetpwd($id,$pwdOld,$pwdNew)
    {
        //确认外部参数是否正确
        if (empty($id)||empty($pwdOld)||empty($pwdNew)) {
            $this->setMsgs("各项参数不能为空");
            return false;
        }
        if ((!is_scalar($id))||(!is_scalar($pwdOld))||(!is_scalar($pwdNew))) {
            $this->setMsgs("各项参数必须为标量");
            return false;
        }
        //判断用户ID是否正确
        $serviceMember = new Diana_Service_Member();
        if (!$detailMember = $serviceMember->getMemberById($id)) {
            $this->setMsgs("错误的用户ID");
            return false;
        }
        $memberId = $detailMember["member_id"];//ID
        $memberName = $detailMember["member_name"];//帐号
        $memberEmail = $detailMember["member_email"];//邮箱
        $memberPasswd = $detailMember["member_passwd"];//密码
        //判断密码是否正确
        if ($memberPasswd <> $pwdOld) {
            $this->setMsgs("旧密码输入错误 {$memberPasswd} - {$pwdOld}");
            return false;
        }
        //更新密码
        if (!$this->modifyPasswd($memberId,$pwdNew,$memberEmail,$memberName,$pwdOld)) {
            return false;
        }
        //重新获取用户数据
        if (!$detailMember = $serviceMember->getMemberById($memberId)) {
            $this->setMsgs("错误的用户ID");
            return false;
        }
        //写入新session
        $serviceDoorkeeper = new Www_Service_Doorkeeper();
        $serviceDoorkeeper->writeSession($detailMember);
        //通过后台变更密码日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if(!$serviceMemberLog->write(223,$memberId,$memberEmail,$memberName)){
        	$this->setMsgs($serviceMemberLog->getMsgs());
            return false;
        }
        return true;
    }

    /**
     * 取回新密码
     *
     * @param int $step 当前步骤
     * @param array $input 外部输入
     */
    function forgetPwd($step,$input)
    {
        if ($step == 1) {//发送确认邮件
            return $this->sendLinkWithVerify($input['email'],$input['captcha']);
        }elseif ($step == 2){//接收确认邮件
            return $this->acceptLinkWithVerify($input['id'],$input['email'],$input['time'],$input['pwd'],$input['sign']);
        }
    }

    /**
     * 发送确认链接
     *
     */
    function sendLinkWithVerify($email,$captcha)
    {
        //确认外部参数是否正确
        if (empty($email)||empty($captcha)) {
            $this->setMsgs(__METHOD__." - 各项参数不能为空");
            return false;
        }
        if ((!is_scalar($email))||(!is_scalar($captcha))) {
            $this->setMsgs("各项参数必须为标量");
            return false;
        }
        //判断验证码是否正确
        $serviceCaptcha = new Diana_Service_Captcha();
        if (!$serviceCaptcha->checkCaptchaWord($captcha,"member-forgetpwd")) {
            $this->setMsgs($serviceCaptcha->getMsgs());
            return false;
        }
        //判断用户邮箱是否正确
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->getRowsByEmail(null,$email)) {
            $this->setMsgs("当前帐号【{$email}】并不存在");
            return false;
        }
        $rowMember = $rowsMember[0];
        $memberId = $rowMember["member_id"];//ID
        $memberName = $rowMember['member_name']?$rowMember['member_name']:Com_Functions::getNameFromEmail($rowMember["member_email"]);//姓名
        $memberEmail = $rowMember["member_email"];//邮件
        //生成随机密码及签名
        $time = time();
        $newPwd = rand(100000,999999);//生成新密码
        //加密新密码
        $serviceEncrypt = new Diana_Service_Encrypt();
        if (!$md5NewPwd = $serviceEncrypt->makePwd($newPwd)) {
            $this->setMsgs($serviceEncrypt->getMsgs());
            return false;
        }
        //加密签名
        if (!$sign = $this->makeSignForResetpwd($memberId,$memberEmail,$md5NewPwd,$time)) {
            $this->setMsgs($serviceEncrypt->getMsgs());
            return false;
        }
        $query = array(
            "id" => $memberId,
            "email" => $memberEmail,
            "pwd" => $md5NewPwd,
            "time" => $time,
            "sign" => $sign,
        );
        $url = DIANA_DOMAIN_WWW.'/member/guest/forgetpwd/step/2/?'.http_build_query($query);
        $serviceMail = new Diana_Service_Mail();
        if (!$serviceMail->activePasswd($memberEmail,$memberName,$newPwd,$url,$time)) {
            $this->setMsgs($serviceMail->getMsgs());
            return false;
        }
        //写入密码保护邮件发送日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if(!$serviceMemberLog->write(221,$memberId,$memberEmail,$memberName)){
        	$this->setMsgs($serviceMemberLog->getMsgs());
            return false;
        }
        return true;
    }


    /**
     * 在验证之后变更新密码
     *
     * @param int $id 用户ID
     * @param string $email 用户帐号
     * @param int $time 发送时间
     * @param string $md5Pwd 变更密码
     * @param string $sign 签名
     */
    function acceptLinkWithVerify($id,$email,$time,$md5Pwd,$sign)
    {
        //确认外部参数是否正确
        if (empty($id)||empty($email)||empty($time)||empty($md5Pwd)||empty($sign)) {
            $this->setMsgs(__METHOD__." - 各项参数不能为空");
            return false;
        }
        if ((!is_numeric($id))||(!is_string($email))||(!is_numeric($time))||(!is_string($md5Pwd))||(!is_string($sign))) {
            $this->setMsgs("各项参数必须为标量");
            return false;
        }
        //链接权限于48小时有效
        if ((time() - $time) > (60*60*48)) {
            $this->setMsgs("当前链接已经过期（有效时间为48小时）");
            return false;
        }
        //加密签名
        if (!$localSign = $this->makeSignForResetpwd($id,$email,$md5Pwd,$time)) {
            return false;
        }
        //判断签名是否有效
        if ($localSign <> $sign) {
            $this->setMsgs("错误的签名");
            return false;
        }
        //判断用户ID是否正确
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->getRowsById(null,$id)) {
            $this->setMsgs("当前帐号【{$id}】并不存在");
            return false;
        }
        $rowMember = $rowsMember[0];
        $memberId = $rowMember["member_id"];//ID
        $memberName = $rowMember['member_name']?$rowMember['member_name']:Com_Functions::getNameFromEmail($rowMember["member_email"]);//姓名
        $memberEmail = $rowMember["member_email"];//帐号
        $memberPasswd = $rowMember["member_passwd"];//旧密码
        if (strtolower(trim($memberEmail)) <> $email) {
            $this->setMsgs("错误的链接数据");
            return false;
        }
        //修改密码
        if (!$this->modifyPasswd($memberId,$md5Pwd,$memberEmail,$memberName,$memberPasswd)) {
            return false;
        }
        //通过密码保护邮件变更密码日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if(!$serviceMemberLog->write(222,$memberId,$memberEmail,$memberName)){
        	$this->setMsgs($serviceMemberLog->getMsgs());
            return false;
        }
        return true;
    }

    /**
     * 生成用于重置密码的加密签名
     *
     * @param string $id 流水号
     * @param string $email 邮箱
     * @param string $pwd 密码
     * @param string $time 时间
     * @return string
     */
    function makeSignForResetpwd($id,$email,$pwd,$time)
    {
        if (empty($id)||empty($email)||empty($pwd)||empty($time)) {
            $this->setMsgs('要被加密码的字符串不能为空');
            return false;
        }
        if ((!is_numeric($id))||(!is_string($email))||(!is_string($pwd))||(!is_numeric($time))) {
            $this->setMsgs('参数类型错误');
            return false;
        }
        $pub = $id.$email.$pwd.$time;
        $serviceEncrypt = new Diana_Service_Encrypt();
        if (!$sign =  $serviceEncrypt->makeSign($pub)) {
            $this->setMsgs($serviceEncrypt->getMsgs());
            return false;
        }
        return $sign;
    }

    /**
     * 更新密码并写入日志
     *
     * @param id $id 用户ID
     * @param string $md5Pwd 新密码（加密过后的）
     * @param string $email 用户帐号
     * @param string $name 用户名称
     * @param string $oldMd5Pwd  旧密码
     * @return bool
     */
    function modifyPasswd($id,$md5Pwd,$email,$name,$oldMd5Pwd)
    {
        //更新新密码
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->updateWithPasswd($id,$md5Pwd)) {
            $this->setMsgs("密码更新失败");
            return false;
        }
        return true;
    }

}