<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-19
 * Time: 下午7:54
 * To change this template use File | Settings | File Templates.
 */
class Website_KeyworkController extends Admin_Controller_ActionDec
{
    var $yearStart = 2013;
    var $yearEnd = 0;
    function init()
    {
        parent::init();
        $this->view->yearStart = $this->yearStart;
        $this->view->yearEnd = $this->yearEnd = intval(date("Y"));

    }

    function indexAction()
    {
        $request = array_filter($this->getRequest()->getParams());
        $queryGrid = array('ajax_print' => 'json_2','req_handle' => 'datagrid-result');
        $queryGrid = array_merge($queryGrid,$request);
        $serviceWebsiteKeyword = new Diana_Service_WebsiteKeyword();
        $this->view->request = $request;
        $this->view->queryGrid = $queryGrid;
        $this->view->pagesize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN) ;
        $this->view->queryOrderSc = array('desc','asc');
        $this->view->queryOrderColumns = $serviceWebsiteKeyword->getFilterColumnsForOrder();
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceWebsiteKeyword,
                'method' => 'makeDataGird',
            ),
        );
        $this->handleAjax($configHandle);
    }


    function trendAction()
    {
        $this->view->event = $eventId = $this->getRequest()->getUserParam('event');
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($rowsTrend = $serviceTrend->getWebsiteSearch($year,$eventId)){
            $this->view->trendYear = $serviceTrend->formatYear($rowsTrend);
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

}
