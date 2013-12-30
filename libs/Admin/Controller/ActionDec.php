<?php
/**
 * 十二月新版（重大变更）
 * 代号：Dec（December的缩写）
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-9
 * Time: 下午3:00
 * To change this template use File | Settings | File Templates.
 */
class Admin_Controller_ActionDec extends Admin_Controller_Action
{
    function init()
    {
        parent::init();
        $this->setViewPageSize();
    }

    function decPrintDataGrid()
    {

    }

    /**
     *
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

    /**
     * 得到查询用的URL
     */
    function setUrlAboutQuery($request)
    {

    }

    /**
     * @param $configColumns array( 'frozen' => array('columns_name' => array('')) ,'mobile' => array(移动的))
     */
    function setDataGridColumns($configColumns)
    {
        $this->view->dataGridColumns = $configColumns;
    }

    function setViewPageSize()
    {
        $pageSize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN);
        $this->view->pagesize = $pageSize;
    }

}
