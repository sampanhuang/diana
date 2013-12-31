<?php
/**
 * 会员资料
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class Website_IndexController extends Admin_Controller_ActionDec
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
        $this->view->request = $request = $this->getRequest()->getParams();
        $queryGrid = array('ajax_print' => 'json_2','req_handle' => 'datagrid-result');
        $queryGrid = array_merge($queryGrid,$request);
        $serviceWebsite = new Diana_Service_Website();
        $this->view->queryGrid = $queryGrid;
        $this->view->pagesize = $this->getRequest()->getParam('rows',DIANA_DATAGRID_PAGESIZE_ADMIN) ;
        $configHandle = array(
            'datagrid-result' => array(
                'object' => $serviceWebsite,
                'method' => 'makeDataGird',
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
        $request = $this->getRequest()->getParams();
        $serviceWebsite = new Diana_Service_Website();
        if ($this->getRequest()->isPost()) {
            if($request['handle'] = 'query'){
                if(!$detailMember = $serviceWebsite->getDetail($request['query_column'],$request['query_key'])){
                    $this->setMsgs('查询失败');
                    $this->setMsgs($serviceWebsite->getMsgs());
                }
            }
        }
        if((empty($detailMember))&&(!empty($request['website_id']))){
            if(!$detailMember = $serviceWebsite->getDetail('id',$request['website_id'])){
                $this->setMsgs('查询失败');
                $this->setMsgs($serviceWebsite->getMsgs());
            }
        }
        $this->view->queryColumns = array('id' , 'name' , 'domain');
        $this->view->detail = $detailMember;
        $this->view->request = $request;

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
