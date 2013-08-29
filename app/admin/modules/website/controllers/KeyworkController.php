<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-19
 * Time: 下午7:54
 * To change this template use File | Settings | File Templates.
 */
class Website_KeyworkController extends Admin_Controller_Action
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
        $pagesize = 15;
        $this->view->page = $page = $this->getRequest()->getUserParam('page',1);
        $serviceWebsiteKeyword = new Diana_Service_WebsiteKeyword();
        $this->view->paginator = $paginator = $serviceWebsiteKeyword->pageByCondition($page,$pagesize);
        if($paginator['total'] > 0){
            $servicePageNum = new Diana_Service_PageNum($paginator['total'],$pagesize,$page);
            $this->view->pagenum = $pagenum = $servicePageNum->getPageNum();
        }
    }

    function hotAction()
    {

    }

    function searchAction()
    {
        $this->view->event = $eventId = $this->getRequest()->getUserParam('event');
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteSearch($year,$eventId)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

}
