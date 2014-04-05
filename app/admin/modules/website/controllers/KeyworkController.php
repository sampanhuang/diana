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
        $this->view->event = $eventId = $this->getRequest()->getParam('event');
        $this->view->year = $year = $this->getRequest()->getParam('year',date('Y'));
        $this->view->show = $show = $this->getRequest()->getParam('show','year');
        $this->view->date = $date = $this->getRequest()->getParam('date');
        $this->view->numColumn = $numColumn = $this->getRequest()->getParam('num_column',3);
        $date = $date?$date:$year;
        $serviceWebsiteKeyword = new Diana_Service_WebsiteKeyword();
        if(!empty($eventId)){
            if($tmpTrendEventLabelOfYear = $serviceWebsiteKeyword->getLabelById($eventId)){
                $this->view->trendEventLabelOfYear =  $tmpTrendEventLabelOfYear[$eventId];
            }
        }
        $this->view->trendEventLabel = $trendEventLabel = $serviceWebsiteKeyword->getLabelById($keywordId);
        $serviceTrend = new Diana_Service_Trend();
        if($rowsTrend = $serviceTrend->getWebsiteSearch($date,$eventId)){
            if($show == 'day'){
                $this->view->rowsTrend = $serviceTrend->formatOneDayRowsOfEventId($rowsTrend);
                $this->view->trendDay = $serviceTrend->formatDay($rowsTrend);
                $keywordId = $serviceTrend->getTrendEventId($rowsTrend);
                $this->view->trendEventLabel = $trendEventLabel = $serviceWebsiteKeyword->getLabelById($keywordId);
            }elseif($show == 'year'){
                $this->view->trendYear = $serviceTrend->formatYear($rowsTrend);
            }
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

}
