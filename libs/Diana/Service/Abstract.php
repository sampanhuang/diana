<?php
/**
 * 业务类
 *
 */
abstract class Diana_Service_Abstract
{
	var $_translator;
	var $msgs = array();
	var $_loger;
	
	function __construct()
	{
		
	}
	
	/**
	 * 获取语言包消息
	 *
	 * @param string $msgKey 
	 * @param array $data
	 * @return string
	 */
	function getTranslatorMsg($msgKey,$data = array())
	{
		$msg = $this->getTranslator()->translate($msgKey);
		if (!empty($data)) {
			$msg = Com_Functions::strReplaceOnce($data,$msg);
		}
		return $msg;
	}
	
	/**
	 * 获取语言包
	 *
	 * @return unknown
	 */
	public function getTranslator()
    {
        if ($this->_translator === null) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $this->setTranslator(Zend_Registry::get('Zend_Translate'));
            }
        }
        return $this->_translator;
    }
    
    /**
     * 设置国际化
     *
     * @param unknown_type $translate
     */
    public function setTranslator($translate)
    {
        if ($translate instanceof Zend_Translate_Adapter) {
            $this->_translator = $translate;
        } else if ($translate instanceof Zend_Translate) {
            $this->_translator = $translate->getAdapter();
        } else {
            require_once 'Zend/View/Exception.php';
            $e = new Zend_View_Exception('You must set an instance of Zend_Translate or Zend_Translate_Adapter');
            $e->setView($this->view);
            throw $e;
        }
    }
    
    /**
     * 获取日志
     *
     * @return unknown
     */
    function getLoger()
    {
    	$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
    	if (!$bootstrap->hasPluginResource("log")) {
			throw new Exception(DIR_DATA_CONFIG."/boot.ini need resources log");
		}
		return $bootstrap->getResource("log");
    }
    
	/**
	 * 读取信息
	 *
	 * @return array
	 */
	public function getMsgs(){
		return $this->msgs;
	}
	
	/**
	 * 设置信息
	 *
	 * @param array|string $error
	 * @return bool
	 */
	public function setMsgs($msg){
		if (empty($msg)) {
			return false;
		}else{
			if (is_array($msg)) {
                //array_unshift($msg,get_called_class());
				if (empty($this->msgs)) {
					$this->msgs = $msg;
				}else{
					$this->msgs = array_merge($this->getMsgs(),$msg);
				}				
			}elseif (is_scalar($msg)){
				//$this->msgs[] = get_called_class().$msg;
                $this->msgs[] = $msg;
			}
			return $this->msgs;
		}		
	}
}