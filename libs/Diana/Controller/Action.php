<?php
/**
 * 控制类
 *
 */
class Diana_Controller_Action extends Zend_Controller_Action
{
    var $debug;
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

        //调试模式设定
        $this->debug = array(
            'ajax_type' => 'GET',//AJAX清求方法
            'ajax_ob_clean' => 1,//AJAX输出前是否清空缓冲区
        );
	}


    function getTranslateCurrent()
    {

    }


    /**
     * 设置ajax type
     * @param $ajaxType (默认: "GET") 请求方式 ("POST" 或 "GET")， 默认为 "GET"。注意：其它 HTTP 请求方法，如 PUT 和 DELETE 也可以使用，但仅部分浏览器支持。
     */
    function setViewAjaxType()
    {
        $this->view->ajaxType = $this->debug['ajax_type'];
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
        $request = $configHandle['_input']?$configHandle['_input']:$this->getRequest()->getParams();
        $ajaxPrint = strtolower(trim($request['ajax_print']));//打印方式，如果是json则是json，如果是xml则是xml
        if(empty($ajaxPrint)){
            return false;
        }
        $result = $this->decHandle($configHandle);
        //如果输出的是json（有些输出json,有些输出xml），内做如下处下
        if(substr_count($ajaxPrint,'json') >= 1){
            $this->getHelper("layout")->disableLayout();//关闭布局
            $this->getHelper("viewRenderer")->setNoRender();//关闭视图
            if($this->debug['ajax_ob_clean'] == 1){
                ob_clean();
            }
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
                    echo json_encode(array('msg'=>$this->getMsgs(),'total' => 0,'rows' => array(),));
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
                return $result;
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
        $request = $configHandle['_input']?$configHandle['_input']:$this->getRequest()->getParams();
        $reqHandle = strtolower(trim($request['req_handle']));//请求处理的事务
        //如果不是ajax请求，则忽略
        if(empty($reqHandle)){
            //$this->setMsgs('没有指定操作类型！');
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
        $input = $configHandle[$reqHandle]['input']?$configHandle[$reqHandle]['input']:$request;

        if(!method_exists($object,$method)){
            $this->setMsgs('无效的方法 - '.$method);
            return false;
        }

        //执行查询
        $result = $object->$method($input);
        $this->setMsgs($object->getMsgs());
        return $result;
    }

    /**
     * 最后执行
     */
    public function postDispatch() {
		parent::postDispatch();
        $this->view->pageCostMicrotime = microtime_float() - DIANA_MICROTIME_START;
		$this->view->msgs = $this->getMsgs();
	}
}