<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 下午11:35
 * To change this template use File | Settings | File Templates.
 */
class Profile_IntroController extends Admin_Controller_ActionDec
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
        $tmpInput = $this->requestParams;
        $tmpInput['log_manId'] = $this->currentManagerId;
        $serviceManagerLog = new Admin_Service_ManagerLog();
        //ajax处理配置
        $configHandle = array(
            '_input' => $tmpInput,
            'datagrid_result' => array(//查询
                'object' => $serviceManagerLog,
                'method' => 'makeDataGrid',
            ),
            'detail' => array(//明细
                'object' => $serviceManagerLog,
                'method' => 'getDetailById',
            ),
        );
        //ajax处理
        $this->handleAjax($configHandle);

        if((!empty($this->requestParams['log_detail']))&&(!empty($this->requestParams['log_id']))){
            if(!$detailManagerLog = $this->view->detailManagerLog = $serviceManagerLog->getDetailById($this->requestParams)){
                $this->setMsgs($serviceManagerLog->getMsgs());
            }
        }
    }

    /**
     * 日志查询
     */
    function logLoginAction()
    {
        $tmpInput = $this->requestParams;
        $tmpInput['log_manId'] = $this->currentManagerId;
        $serviceManagerLogLogin = new Admin_Service_ManagerLog();
        //ajax处理配置
        $configHandle = array(
            '_input' => $tmpInput,
            'datagrid_result' => array(//查询
                'object' => $serviceManagerLogLogin,
                'method' => 'makeDataGridWithLogin',
            ),
            'combobox_log-type' => array(
                'object' => $serviceManagerLogLogin,
                'method' => 'optionsTypeLabelWithLogin',
            ),
        );
        //ajax处理
        $this->handleAjax($configHandle);
    }

}