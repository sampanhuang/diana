<?php
/**
 * 会员资料
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class Website_DefaultController extends Admin_Controller_ActionDec
{
    var $yearStart = 2013;
    var $yearEnd = 0;
    function init()
    {
        parent::init();
        $this->view->yearStart = $this->yearStart;
        $this->view->yearEnd = $this->yearEnd = intval(date("Y"));

    }

    /**
     * 查询模块
     */
    function indexAction(){
        $request = $this->getRequest()->getParams();
        $queryGrid = array('ajax_print' => 'json','req_handle' => 'datagrid-result');
        $queryGrid = array_merge($queryGrid,$request);
        $serviceWebsite = new Admin_Service_Website();
        $this->view->dataget = $request;
        $this->view->queryGrid = $queryGrid;
        $this->view->pagesize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN) ;
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceWebsite,
                'method' => 'makeDataGrid',
            ),
        );
        $this->handleAjax($configHandle);
    }

    function updateAction()
    {

    }

    function deleteAction()
    {

    }

    function detailAction()
    {
        $this->view->websiteId = $websiteId = $this->getRequest()->getUserParam('website_id',0);
        if(!empty($websiteId)){
            $serviceWebsite = new Diana_Service_Website();
            if($detailWebsite = $serviceWebsite->detailById($websiteId)){
                $this->view->detailWebsite = $this->view->detailMember = $detailWebsite;
            }
        }
    }

    function clickInAction()
    {
        $this->view->eventId = $eventId = $this->getRequest()->getUserParam('event',0);
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteClickIn($year,$eventId)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

    function clickOutAction()
    {
        $this->view->eventId = $eventId = $this->getRequest()->getUserParam('event',0);
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteClickOut($year,$eventId)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

}
