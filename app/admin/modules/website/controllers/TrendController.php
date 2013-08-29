<?php
/**
 * 会员动态
 * User: sampan
 * Date: 13-7-19
 * Time: 下午5:51
 * To change this template use File | Settings | File Templates.
 */
class Website_TrendController extends Admin_Controller_Action
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
    function registerAction(){
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteRegister($year)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }

    }

    /**
     * 点击流入
     */
    function clickInAction()
    {
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteClickIn($year)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

    /**
     * 点击流出
     */
    function clickOutAction()
    {
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteClickOut($year)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

    /**
     * 网站申请
     */
    function applyAction()
    {
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteApply($year)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

    /**
     * 网站搜索
     */
    function searchAction()
    {
        $this->view->year = $year = $this->getRequest()->getUserParam('year',date('Y'));
        $serviceTrend = new Diana_Service_Trend();
        if($trendYear = $serviceTrend->getWebsiteSearch($year)){
            $this->view->trendYear = $trendYear;
        }else{
            $this->setMsgs($serviceTrend->getMsgs());
        }
    }

}
