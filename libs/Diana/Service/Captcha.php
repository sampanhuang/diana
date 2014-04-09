<?php
/**
 * 输出验证码
 *
 */
class Diana_Service_Captcha extends Diana_Service_Abstract 
{
	var $captchaObj;
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 确认验证码输入是否正确
	 *
	 * @param string $word 验证码
	 * @return bool
	 */
	function checkCaptchaWord($word,$snKey)
	{
		if (empty($word)) {return false;}
		if (!is_scalar($word)) {return false;}
		$sessionNamespace = $this->getSessionNamespace($snKey);		
		$sessionWord = $sessionNamespace->word;
		//file_put_contents("session_snKey.txt",implode(chr(10).chr(13),array($snKey,$sessionWord)).chr(10).chr(13),FILE_APPEND);
		if (strtolower(trim($word)) <> strtolower(trim($sessionWord))) {
			$this->setMsgs($this->getTranslatorMsg("www_msg_captcha_error"));
			return false;
		}
		return true;
	}
	
	
	/**
	 * 获取验证码图片
	 *
	 * @param unknown_type $snKey
	 * @return unknown
	 */
	function outputCaptcha($snKey)
	{
		$captcha = $this->getCaptchaOjb();
		$sessionNamespace = $this->getSessionNamespace($snKey);
		$captcha->setSession($sessionNamespace);
		$captcha->generate(); //生成图片
		$captchaImgpath=$captcha->getImgDir() .$captcha->getId().$captcha->getSuffix();////存放目录
		//file_put_contents("session_snKey.txt",implode(chr(10).chr(13),array($snKey,$sessionNamespace->word)).chr(10).chr(13),FILE_APPEND);
		return  $captchaImgpath;
	}
	
	/**
	 * 获取对像
	 *
	 * @return unknown
	 */
	function getCaptchaOjb()
	{
		if (empty($this->captchaObj)) {
			$configCaptcha = new Zend_Config_Ini(DIANA_DIR_DATA_CONFIG."/captcha.ini",DIANA_APP_ENV);
			$this->captchaObj = new Zend_Captcha_Image($configCaptcha);
			//$this->captchaObj->setUseNumbers(false);
		}
		return $this->captchaObj;
	}
	
	/**
	 * 获取session命名空间
	 *
	 * @param unknown_type $snKey
	 * @return unknown
	 */
	function getSessionNamespace($snKey)
	{
		$snKey = "captcha_".$snKey;
		$sessionNamespace = new Zend_Session_Namespace($snKey);
		return $sessionNamespace;
	}
	
}