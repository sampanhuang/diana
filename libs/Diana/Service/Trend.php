<?php
    /**
     * 动态趋势
     * Created by JetBrains PhpStorm.
     * User: sampan
     * Date: 13-8-1
     * Time: 下午9:07
     * To change this template use File | Settings | File Templates.
     */
class Diana_Service_Trend extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取事件ID
     * @param $rows
     * @return array|bool
     */
    function getTrendEventId($rows)
    {
        if(empty($rows)){
            return false;
        }
        $eventId = array();
        foreach($rows as $row){
            if(!empty($row['trend_eventId'])){
                $eventId[] = $row['trend_eventId'];
            }
        }
        if(empty($eventId)){
            return false;
        }
        $eventId = array_unique(array_filter($eventId));
        return $eventId;
    }

    function formatOneDayRowsOfEventId($rows)
    {
        $resources = array();
        foreach($rows as $row){
            $key = $row['trend_eventId'];
            $resources[$key] = $row;
        }
        return $resources;
    }

    /**
     * 按小时分布
     * @param $rows
     * @return array
     */
    function formatDay($rows)
    {
        $resources = array();
        foreach($rows as $row){
            for($tmpHour = 0;$tmpHour < 24;$tmpHour++){
                $resources[$tmpHour] += $row['trend_hour_'.$tmpHour];
            }
        }
        return $resources;
    }

    /**
     * 按天分布
     * @param $rows
     * @return array
     */
    function formatYear($rows)
    {
        $resources = array();
        foreach($rows as $row){
            $tmpMonth = $row['trend_month'];
            $tmpDay = $row['trend_day'];
            $resources[$tmpMonth][$tmpDay] += $row['trend_hour_total'];
        }
        return $resources;
    }

    /**搜索动态
     * @param null $year 年
     * @param $keywordId 关键字ID
     * @return array
     */
    function getWebsiteSearch($date = null,$keywordId = null)
    {
        list($year, $month, $day) = explode('-',$date);
        $modelWebsiteTrendSearch = new Diana_Model_WebsiteTrendSearch($year);
        if(!$all = $modelWebsiteTrendSearch->getRowsByCondition(null,array("trend_eventId" => $keywordId,'trend_month' => $month,'trend_day' => $day))){
            return $all;
        }
        return $all;
    }

    /**
     * 获取注册申请状态
     *
     * @param unknown_type $year
     * @return unknown
     */
    function getWebsiteApplyRegister($year = null)
    {
        $modelWebsiteTrendApplyRegister = new Diana_Model_WebsiteTrendApplyRegister($year);
        if(!$all = $modelWebsiteTrendApplyRegister->getRowsByCondition()){
            return $all;
        }
        return $this->formatRows($all);
    }

    function getWebsiteRegister($year = null)
    {
        $modelWebsiteTrendRegister = new Diana_Model_WebsiteTrendRegister($year);
        if(!$all = $modelWebsiteTrendRegister->getRowsByCondition()){
            return $all;
        }
        return $this->formatRows($all);
    }

    function getWebsiteClickOut($year = null,$websiteId)
    {
        $modelWebsiteTrendClickOut = new Diana_Model_WebsiteTrendClickOut($year);
        if(!$all = $modelWebsiteTrendClickOut->getRowsByCondition(null,array("trend_eventId" => $websiteId))){
            return $all;
        }
        return $this->formatRows($all);
    }

    function getWebsiteClickIn($year = null,$websiteId)
    {
        $modelWebsiteTrendClickIn = new Diana_Model_WebsiteTrendClickIn($year);
        if(!$all = $modelWebsiteTrendClickIn->getRowsByCondition(null,array("trend_eventId" => $websiteId))){
            return $all;
        }
        return $this->formatRows($all);
    }

    function formatRows($rows)
    {
        $arrYear = array();
        $arrMonth = array();
        $arrDay = array();
        for($hour = 0;$hour <= 23;$hour++){
            $arrDay[$hour] = 0;
        }
        for($day = 0;$day <= 31;$day++){
            $arrMonth[$day]['day'] = $arrDay;
        }
        for($month = 1;$month <=12;$month++){
            $arrYear[$month]['month'] = $arrMonth;
        }
        foreach($rows as $row){
            $month = $row['trend_month'];
            $day = $row['trend_day'];
            for($hour = 0;$hour <= 23;$hour++){
                $columnHour = 'trend_hour_'.$hour;
                $arrYear[$month]['month'][$day]['day'][$hour] =  $row[$columnHour];
                $arrYear[$month]['month'][$day]['total'] += $row[$columnHour];
                $arrYear[$month]['total'] += $row[$columnHour];
            }
        }
        return $arrYear;
    }
}