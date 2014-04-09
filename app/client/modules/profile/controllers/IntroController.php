<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 下午11:35
 * To change this template use File | Settings | File Templates.
 */
class Profile_IntroController extends Client_Controller_ActionDec
{
    function init()
    {
        parent::init();

    }

    /**
     * 用户资料
     */
    function indexAction()
    {

    }

    /**
     * 日志查询
     */
    function logAction()
    {
        $serviceMemberLog = new Diana_Service_MemberLog();
        $this->view->request = $request = $this->view->dataget = $this->getRequest()->getParams();
        $this->view->queryGrid = $queryGrid = array_merge(array('ajax_print' => 'json','req_handle' => 'datagrid_result'),$serviceMemberLog->filterFormSearch($request));
        //ajax处理配置
        $configAjax = array(
            'datagrid_result' => array(//查询
                'object' => $serviceMemberLog,
                'method' => 'makeDataGrid',
                '_input' => $request,
            ),
            'combobox_log-type' => array(
                'object' => $serviceMemberLog,
                'method' => 'makeLogTypeCombobox',
            ),            
        );
        $configHandle = array(
            'detail' => array(//明细
                'object' => $serviceMemberLog,
                'method' => 'getDetailById',
            ),
        );
        //ajax处理
        $this->handleAjax($configAjax);
        $this->view->detailMemberLog = $this->decHandle($configHandle);
    }

}