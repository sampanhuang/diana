<?php
/**
 * 参数配置
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class System_ConfigController extends Client_Controller_ActionDec
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
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        $inputHandle['conf_memberId'] = $this->currentMemberId;
        $queryGrid = array('ajax_print' => 'json_2','req_handle' => 'treegrid-result');
        $this->view->queryGrid = $queryGrid = array_merge($queryGrid,$request);
        $serviceMemberConfig = new Client_Service_MemberConfig();
        $configHandle = array(
            'treegrid-result' => array(
                'object' => $serviceMemberConfig,
                'method' => 'makeTreeGird',
                'input' => $inputHandle,
            ),
        );
        $this->handleAjax($configHandle);
    }

    /**
     * 获取详细方式
     */
    function detailAction()
    {
        $this->view->reqHandleQuery = $reqHandleQuery = 'query';//当前操作类型
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        $inputHandle['conf_memberId'] = $this->currentMemberId;
        if(empty($inputHandle['req_handle'])){
            $inputHandle['req_handle'] = $reqHandleQuery;
        }
        $serviceMemberConfig = new Client_Service_MemberConfig();
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
     * 更新配置值
     */
    function updateAction()
    {
        $this->view->request = $request = $inputHandle = $this->getRequest()->getParams();
        $serviceMemberConfig = new Client_Service_MemberConfig();
        if($request['handle'] == 'update'){
            if($serviceMemberConfig->updateValByKey($request['conf_key'],$request['conf_value'],$this->currentMemberId)){
                $this->setMsgs('配置参数更新成功！');
            }else{
                $this->setMsgs('配置参数更新失败！');
                $this->setMsgs($serviceMemberConfig->getMsgs());
            }
        }
        $this->view->handle = 'update';
        if( !$this->view->detail = $detail = $serviceMemberConfig->getDetail(array('conf_key' => $request['conf_key'],'conf_memberId' => $this->currentMemberId))){
            $this->setMsgs($serviceMemberConfig->getMsgs());
            return false;
        }
        //打印参数变更历史纪录
        $queryGrid = array('show_ajax' => 'json','data_ajax' => 'config_update_history');
        $this->view->queryGrid = $queryGrid;
        //默认是20纪录一页
        if(empty($request['rows'])){$request['rows'] = DIANA_DATAGRID_PAGESIZE_CLIENT;}
        $this->view->pagesize = $request['rows'];
        //取数据源
        if ($request['data_ajax'] == 'config_update_history') {
            if($dataGrid = $serviceMemberConfig->makeDataGridWithUpdateHistory($request['page'],$request['rows'],array('history_configId' => $detail['conf_id'],'history_memberId' => $this->currentMemberId))){
                echo json_encode($dataGrid);
            }
        }
    }

}
