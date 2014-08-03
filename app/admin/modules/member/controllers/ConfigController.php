<?php
/**
 * 参数配置
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class Member_ConfigController extends Admin_Controller_Action
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
            $serviceMemberConfig = new Admin_Service_MemberConfig();
            if($dataTreeGrid = $serviceMemberConfig->makeTreegrid()){
                echo json_encode($dataTreeGrid);
            }
        }
    }

    /**
     * 获取详细方式
     */
    function detailAction()
    {
        $this->view->reqHandleQuery = $reqHandleQuery = 'query';//当前操作类型
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        if(empty($inputHandle['req_handle'])){
            $inputHandle['req_handle'] = $reqHandleQuery;
        }
        $serviceMemberConfig = new Admin_Service_MemberConfig();
        $configHandle = array(
            '_input' => $inputHandle,
            'query' => array(
                'object' => $serviceMemberConfig,
                'method' => 'getDetail',
            ),
        );
        $this->view->detail = $detail = $this->decHandle($configHandle);
    }

    /**
     * 添加新配置项
     */
    function createAction()
    {
        $request = $this->getRequest()->getParams();

        $serviceMemberConfig = new Admin_Service_MemberConfig();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] == 'insert'){
                if($serviceMemberConfig->create($request)){
                    $this->setMsgs('配置参数添加成功！');
                    unset($request);
                }else{
                    $this->setMsgs('配置参数添加失败！');
                    $this->setMsgs($serviceMemberConfig->getMsgs());
                }
            }
        }
        $this->view->handle = 'insert';//当前操作类型
        $this->view->detail = $request;//
        $this->view->inputType = $serviceMemberConfig->getInputType();
        if($request['data_ajax'] == 'make-father-config-father'){
            if($comboBox = $serviceMemberConfig->makeComboBoxWithFather()){
                echo json_encode($comboBox);
            }
        }
    }

    /**
     * 修改配置输入方式
     */
    function alterAction()
    {
        $request = $this->getRequest()->getParams();
        $serviceMemberConfig = new Admin_Service_MemberConfig();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] == 'alter'){
                if($serviceMemberConfig->alter($request,$request['conf_id'])){
                    $this->setMsgs('配置参数设置成功！');
                }else{
                    $this->setMsgs('配置参数设置失败！');
                    $this->setMsgs($serviceMemberConfig->getMsgs());
                }
            }
        }
        $this->view->handle = 'alter';
        $this->view->inputType = $serviceMemberConfig->getInputType();
        if($request['data_ajax'] == 'make-father-config-father'){
            if($comboBox = $serviceMemberConfig->makeComboBoxWithFather()){
                echo json_encode($comboBox);
            }
        }else{
            if(!$this->view->detail = $serviceMemberConfig->getDetail($request)){
                $this->setMsgs($serviceMemberConfig->getMsgs());
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
