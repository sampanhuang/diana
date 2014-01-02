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
        $request = $this->getRequest()->getParams();
        if ((!empty($request['show_ajax']))||(!empty($request['ajax_show']))) {//打印ajax
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            if ($request['show_ajax'] == 'json' || $request['ajax_show'] == 'json') {
                ob_clean();
                header('content-type: application/json; charset=utf-8');
            }
        }
	}


    /**
     * 设置调用哪个JQUERY
     */
    function setJqueryLang()
    {
        $suffixJqueryLang = 'en';
        if(DIANA_TRANSLATE_CURRENT == 'zh-cn'){
            $suffixJqueryLang = 'zh_CN';
        }elseif(DIANA_TRANSLATE_CURRENT == 'zh-tw'){
            $suffixJqueryLang = 'zh_TW';
        }
        $this->view->suffixJqueryLang = $suffixJqueryLang;
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

    /**
     * 处理ajax
     * @param $configHandle
     * @return bool
     */
    function handleAjax($configHandle)
    {
        $request = $this->getRequest()->getParams();
        $ajaxPrint = strtolower(trim($request['ajax_print']));//打印方式，如果是json则是json，如果是xml则是xml
        if(empty($ajaxPrint)){
            return false;
        }
        $result = $this->decHandle($configHandle);
        //如果输出的是json（有些输出json,有些输出xml），内做如下处下
        if(substr_count($ajaxPrint,'json') >= 1){
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            //ob_clean();
            header('content-type: application/json; charset=utf-8');
            if($ajaxPrint == 'json_1'){
                $response = array(
                    'stat' => 0,
                    //'msgs' => implode(';',$this->getMsgs()),
                    //'result' => $result,
                );
                if($result){
                    $response['stat'] = 1;
                }
                if($this->getMsgs()){
                    $response['msgs'] = implode(';',$this->getMsgs());
                }
                echo json_encode($response);
            }elseif($ajaxPrint == 'json_2'){
                if($result){
                    echo json_encode($result);
                }else{
                    echo json_encode(array('total' => 0,'rows' => array(),));
                }
            }else{
                echo json_encode($result);
            }
        }
    }

    /**
     * 处理POST
     * @param $configHandle
     * @return bool
     */
    function handlePost($configHandle)
    {
        if ($this->getRequest()->isPost()) {
            if($result = $this->decHandle($configHandle)){
                $this->setMsgs('操作成功');
                return true;
            }else{
                $this->setMsgs('操作失败');
                return false;
            }
        }
    }

    /**
     * 处理
     * @param $configHandle
     * @return bool
     */
    function decHandle($configHandle)
    {
        $request = $this->getRequest()->getParams();
        $reqHandle = strtolower(trim($request['req_handle']));//请求处理的事务
        //如果不是ajax请求，则忽略
        if(empty($reqHandle)){
            $this->setMsgs('没有指定操作类型！');
            return false;
        }

        //检查是否有这项配置
        if(empty($configHandle[$reqHandle])){
            $this->setMsgs('你需要确认是否配置成功 - '.$reqHandle);
            return false;
        }
        //负责处理的类
        $object = $configHandle[$reqHandle]['object'];
        //负责处理的类的方法
        $method = $configHandle[$reqHandle]['method'];
        //判断这个类中是否有这个方法

        if(!method_exists($object,$method)){
            $this->setMsgs('无效的方法 - '.$method);
            return false;
        }

        //执行查询
        $result = $object->$method($request);
        $this->setMsgs($object->getMsgs());
        return $result;
    }

	public function postDispatch() {
		parent::postDispatch();
        $this->view->pageCostMicrotime = microtime_float() - DIANA_MICROTIME_START;
		$this->view->msgs = $this->getMsgs();
	}
}