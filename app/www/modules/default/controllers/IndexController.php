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

        $service = new Diana_Service_Website();
        if($contentIndex = $service->getHtmlIndex()){
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            echo $contentIndex;
        }



	}

    /**
     * 测试
     */
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

    /**
     * 验证码
     */
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