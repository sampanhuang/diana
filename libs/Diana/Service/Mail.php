<?php
/**
 * 邮件发送类
 *
 */
class Diana_Service_Mail extends Diana_Service_Abstract
{
    function __construct()
    {
        $this->setDefault();
    }

    /**
     * 激活新电邮地址
     *
     * @param string $email 收件地址
     * @param string $name 收件人
     * @param string $url 邮箱生效链接
     * @param int $time 时间
     * @return bool
     */
    function activeEmail($email,$name,$url,$time)
    {
        if (empty($email)||empty($name)||empty($url)||empty($time)) {
            $this->setMsgs( __METHOD__.' - 各项参数不能为空');
            return false;
        }
        if ((!is_string($email))||(!is_string($name))||(!is_string($url))||(!is_numeric($time))) {
            $this->setMsgs('各项参数类型必须正确');
            return false;
        }
        $subject = "你好，".$name."，这是一份来自【".DIANA_DOMAIN_ROOT."】的邮箱地址验证请求";
        $bodyTlp = file_get_contents(DIANA_DIR_DATA_TEMPLATE.'/mail/activeEmail.htm');
        $body = Com_Functions::strReplaceOnce(array($name,$email,$url,$url,date("Y-m-d H:i:s",$time)),$bodyTlp);
        return $this->sendMail($email,$name,$subject,$body);
    }

    /**
     * 激活新密码
     *
     * @param string $email 收件人
     * @param string $name 收件人
     * @param string $passwd 新密码
     * @param string $url 新密码生效链接
     * @param int $time 时间
     * @return bool
     */
    function activePasswd($email,$name,$passwd,$url,$time)
    {
        if (empty($email)||empty($name)||empty($passwd)||empty($url)||empty($time)) {
            $this->setMsgs(__METHOD__.' - 各项参数不能为空');
            return false;
        }
        $subject = "你好，".$name."，请激活你的新密码[来自".DIANA_DOMAIN_ROOT."的系统邮件]";
        $bodyTlp = file_get_contents(DIANA_DIR_DATA_TEMPLATE.'/mail/activePassword.htm');
        $body = Com_Functions::strReplaceOnce(array($name,$passwd,$url,$url,date("Y-m-d H:i:s",$time)),$bodyTlp);
        return $this->sendMail($email,$name,$subject,$body);
    }

    /**
     * 发送邮件
     *
     * @param string $email 收件人地址
     * @param string $name 收件人性名
     * @param string $subject 邮件标题
     * @param string $body 邮件内容
     */
    function sendMail($email,$name,$subject,$body)
    {
        $mail = new Zend_Mail('UTF-8');
        $mail->addTo($email, $name);
        $mail->setSubject($subject);
        $mail->setBodyHtml($body);
        try {
            $mail->send();
            $logger = $this->getLoger();
            $logger->info(implode(chr(9),array($email,$name,$subject)));
            return true;
        }catch (Exception $e){
            $this->setMsgs('邮件发送失败！');
            $this->setMsgs($e->getMessage());
            return false;
        }
    }

    /**
     * 设置默认值
     *
     */
    function setDefault()
    {
        if (!$transport = Zend_Mail::getDefaultTransport()) {
            if (!$configMail = new Zend_Config_Ini(DIANA_DIR_DATA_CONFIG."/mail.ini",DIANA_APP_ENV)) {
                $this->setMsgs('无法读取邮件发送配置');
            }
            $transport = new Zend_Mail_Transport_Smtp($configMail->transport->host, $configMail->transport->toArray());
            Zend_Mail::setDefaultTransport($transport);
            Zend_Mail::setDefaultFrom($configMail->defaultFrom->email,$configMail->defaultFrom->name);
            Zend_Mail::setDefaultReplyTo($configMail->defaultReplyTo->email,$configMail->defaultReplyTo->name);
        }
    }
}