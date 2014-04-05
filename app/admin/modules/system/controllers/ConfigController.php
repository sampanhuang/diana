<?php
/**
 * 参数配置
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class System_ConfigController extends Admin_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 查询模块
     */
    function indexAction()
    {
        $request = $this->getRequest()->getParams();
        if ($request['data_ajax'] == 'treegrid_data') {
            $serviceConfig = new Admin_Service_Config();
            if($dataTreeGrid = $serviceConfig->makeTreegrid()){
                echo json_encode($dataTreeGrid);
            }
        }
    }

    /**
     * 获取详细方式
     */
    function detailAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceConfig = new Admin_Service_Config();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] == 'search'){
                if(!$detail = $serviceConfig->getDetail(null,$request['conf_key'])){
                    $this->setMsgs('查询失败！');
                    $this->setMsgs($serviceConfig->getMsgs());
                }
            }
        }
        $this->view->handle = 'search';//当前操作类型
        $this->view->request = $request;//
        if((empty($detail))&&(!empty($request['conf_id']))){
            if(!$detail = $serviceConfig->getDetail(intval($request['conf_id']))){
                $this->setMsgs($serviceConfig->getMsgs());
                return false;
            }
        }
        $this->view->detail = $detail;
    }

    /**
     * 添加新配置项
     */
    function insertAction()
    {
        $request = $this->getRequest()->getParams();

        $serviceConfig = new Admin_Service_Config();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] == 'insert'){
                if($serviceConfig->insert($request)){
                    $this->setMsgs('配置参数添加成功！');
                    unset($request);
                }else{
                    $this->setMsgs('配置参数添加失败！');
                    $this->setMsgs($serviceConfig->getMsgs());
                }
            }
        }
        $this->view->handle = 'insert';//当前操作类型
        $this->view->detail = $request;//
        $this->view->inputType = $serviceConfig->getInputType();
        if($request['data_ajax'] == 'make-father-config-father'){
            if($comboBox = $serviceConfig->makeComboBoxWithFather()){
                echo json_encode($comboBox);
            }
        }
    }


    /**
     * 更新配置值
     */
    function updateAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceConfig = new Admin_Service_Config();
        if($request['handle'] == 'update'){
            if($serviceConfig->updateValByKey($request['conf_key'],$request['conf_value'])){
                $this->setMsgs('配置参数更新成功！');
            }else{
                $this->setMsgs('配置参数更新失败！');
                $this->setMsgs($serviceConfig->getMsgs());
            }
        }
        $this->view->handle = 'update';
        if( !$this->view->detail = $serviceConfig->getDetail(intval($request['conf_id']))){
            $this->setMsgs($serviceConfig->getMsgs());
            return false;
        }
        //打印参数变更历史纪录
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'config_update_history');
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($request['rows'])){$request['rows'] = DIANA_DATAGRID_PAGESIZE_ADMIN;}
        $this->view->pagesize = $request['rows'];
        //取数据源
        if ($request['data_ajax'] == 'config_update_history') {
            if($dataGrid = $serviceConfig->makeDataGridWithUpdateHistory($request['page'],$request['rows'],array('history_configId' => $request['conf_id']))){
                echo json_encode($dataGrid);
            }
        }
    }

    /**
     * 修改配置输入方式
     */
    function alterAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceConfig = new Admin_Service_Config();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] == 'alter'){
                if($serviceConfig->alter($request,$request['conf_id'])){
                    $this->setMsgs('配置参数设置成功！');
                }else{
                    $this->setMsgs('配置参数设置失败！');
                    $this->setMsgs($serviceConfig->getMsgs());
                }
            }
        }
        $this->view->handle = 'alter';
        $this->view->inputType = $serviceConfig->getInputType();
        if($request['data_ajax'] == 'make-father-config-father'){
            if($comboBox = $serviceConfig->makeComboBoxWithFather()){
                echo json_encode($comboBox);
            }
        }else{
            if(!$this->view->detail = $serviceConfig->getDetail($request['conf_id'])){
                $this->setMsgs($serviceConfig->getMsgs());
            }
        }
    }




    /**
     * 配置项处理
     */
    function handleAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceConfig = new Admin_Service_Config();
        if(!empty($request['data_ajax'])){
            if($request['data_ajax'] == 'delete'){//删除处理
                if($rowsAffected = $serviceConfig->deleteById($request['conf_id'])){
                    $dataAjax = array(
                        'stat' => 1,
                        'msgs' => '成功删除'.$rowsAffected.'条菜单！',
                    );
                }else{
                    $dataAjax = array(
                        'stat' => 0,
                        'msgs' => '删除失败！'.implode(';',$serviceConfig->getMsgs()),
                    );
                }
            }
            echo json_encode($dataAjax);
        }
    }

}
