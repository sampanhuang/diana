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
        $dataGet = $this->getRequest()->getParams();
        if ($dataGet['show_data'] == 'menu_tree') {
            echo json_encode($this->currentManagerRoleMenu);
        }
	}

    function welcomeAction()
    {
        //$this->getHelper("layout")->disableLayout();//关闭布局
        //$this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $arrIpAddr = array();//array(ip=> addre)
        //获取日志
        $serviceManagerLog = new Admin_Service_ManagerLog();

        if($dataManagerLogLogin = $serviceManagerLog->makeDataGridBeforeLogin(array('page' => 1,'rows' => 5))){
            foreach($dataManagerLogLogin['rows'] as $tmpRow){
                $arrIpAddr[$tmpRow['log_ip']] = '';
            }
            $this->view->rowsManagerLogLogin = $dataManagerLogLogin['rows'];//登录日志
        }
        //IP转换地址
        if(!empty($arrIpAddr)){
            $comIpLocation = new Com_IpLocation(DIANA_PATH_DATA_IPLIBS);
            foreach($arrIpAddr as $ip => &$addr){
                $addr = $comIpLocation->getaddressutf($ip);
            }
            $this->view->arrIpAddr = $arrIpAddr;
        }


        //获取环境变量
        $this->view->servInfo = $_SERVER;
        $this->view->phpInfo = ini_get_all();
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