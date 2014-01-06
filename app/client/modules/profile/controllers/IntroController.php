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
        $this->view->request = $request = $this->view->dataget = $this->getRequest()->getParams();
        $serviceMemberLog = new Diana_Service_MemberLog();
        //ajax处理配置
        $configHandle = array(
            'datagrid_result' => array(//查询
                'object' => $serviceMemberLog,
                'method' => 'makeDataGrid',
            ),
            'combobox_log-type' => array(
                'object' => $serviceMemberLog,
                'method' => 'makeLogTypeCombobox',
            ),
            'detail' => array(//明细
                'object' => $serviceMemberLog,
                'method' => 'getDetailById',
            ),
        );
        //ajax处理
        $this->handleAjax($configHandle);
    }

}