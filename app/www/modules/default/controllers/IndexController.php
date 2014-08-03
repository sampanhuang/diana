<?php
/**
 * 首页
 *
 */
class IndexController extends Www_Controller_Action
{
	function init()
	{
		parent::init();
		
	}
	
	/**
	 * 首页
	 *
	 */
	function indexAction()
	{
        /*
        $this->redirect('/default/website');
        */
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        if(DIANA_TRANSLATE_CURRENT == 'en-us'){
            $domain = DIANA_DOMAIN_WWW_US;
        }elseif(DIANA_TRANSLATE_CURRENT == 'zh-tw'){
            $domain = DIANA_DOMAIN_WWW_TW;
        }else{
            $domain = DIANA_DOMAIN_WWW_CN;
        }
        $contentIndex = file_get_contents('http://'.$domain.'/default/website/index');
        echo $contentIndex.chr(10).chr(13).'<!--form '.$domain.'-->';


	}
	
	function testAction()
	{
		//$this->getHelper("layout")->disableLayout();//关闭布局
		$this->getHelper("viewRenderer")->setNoRender();//关闭视图
		$url = $this->getRequest()->getParam("url");
		$snKey = "website_register";
		//判断验证码是否正确
		$snKey = "captcha_".$snKey;
		$sessionNamespace = new Zend_Session_Namespace($snKey);
		$sessionWord = $sessionNamespace->word;
		echo $sessionWord;
	}
	
	/**
	 * 频道
	 *
	 */
	function channelAction()
	{
		//$this->getHelper("layout")->disableLayout();//关闭布局
		//$this->getHelper("viewRenderer")->setNoRender();//关闭视图
		$this->_forward("index","website","default") ;
	}

    function captchaAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $snKey = $this->getRequest()->getParam("key");
        //实例化验证码类
        $serviceCaptcha = new Diana_Service_Captcha();
        if ($imgCaptcha = $serviceCaptcha->outputCaptcha($snKey)) {
            header( "Content-type: image/jpeg");
            $PSize = filesize($imgCaptcha);
            $picturedata = fread(fopen($imgCaptcha, "r"), $PSize);
            echo $picturedata;
        }else{
            echo $serviceCaptcha->getMsgs();
        }
    }



	
	
	
}