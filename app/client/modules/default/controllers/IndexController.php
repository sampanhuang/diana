<?php
/**
 * 首页
 *
 */
class IndexController extends Admin_Controller_Action
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
        $this->getHelper("layout")->disableLayout();//关闭布局
        $dataget = $this->getRequest()->getParams();
        if ($dataget['show_data'] == 'menu_tree') {
            echo json_encode($this->currentManagerRoleMenu);
        }
	}

    function welcomeAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        echo phpinfo();
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
	
	
	
}