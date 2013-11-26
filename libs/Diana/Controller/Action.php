<?php
/**
 * 控制类
 *
 */
class Diana_Controller_Action extends Zend_Controller_Action
{
	var $msgs;
	var $currentModuleName;
	var $currentControllerName;
	var $currentActionName;
	
	function init()
	{
		parent::init();
		$this->view->addBasePath(DIANA_APP_DIR."/views");
		//获取当前位置
		$this->view->currentModuleName = $this->currentModuleName = $this->getRequest()->getModuleName();//获取当前模块
		$this->view->currentControllerName = $this->currentControllerName = $this->getRequest()->getControllerName();//获取当前控制器
		$this->view->currentActionName = $this->currentActionName = $this->getRequest()->getActionName();//获取当前选项

        //打印JSON
        $dataget = $this->getRequest()->getParams();
        if ($dataget['show_ajax']) {//打印ajax
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            if ($dataget['show_ajax'] == 'json') {
                ob_clean();
                header('content-type: application/json; charset=utf-8');
            }
        }
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
				if (empty($this->msgs)) {
					$this->msgs = $msg;
				}else{
					$this->msgs = array_merge($this->getMsgs(),$msg);
				}				
			}elseif (is_scalar($msg)){
				$this->msgs[] = $msg;
			}
			return $this->msgs;
		}		
	}
	
	public function postDispatch() {
		parent::postDispatch();
        $this->view->pageCostMicrotime = microtime_float() - DIANA_MICROTIME_START;
		$this->view->msgs = $this->getMsgs();
	}
}