<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-2
 * Time: 下午8:44
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteCountry extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 网站变更国家
     * @param $oldCountry 旧的国家
     * @param $newCountry 新的国家
     * @param int $countWebsite 网站数量
     * @param int $clickIn 网站流入点击
     * @param int $clickOut 网站流出点击
     * @return bool 成功或失败
     */
    function websiteChangeCountry($oldCountry,$newCountry,$countWebsite = 0,$clickIn = 0,$clickOut = 0)
    {
        if(empty($oldCountry)||empty($newCountry)){
            $this->setMsgs("参数不能为空 - oldCountry - newCountry");
            return false ;
        }
        if(empty($countWebsite)&&empty($clickIn)&&empty($clickOut)){
            $this->setMsgs("参数不能为空 - countWebsite - clickIn - clickOut");
            return false ;
        }
        //如果这个网站没有改变类型，就什么也不用动
        if($oldCountry == $newCountry){
            return true;
        }
        $modelWebsiteCountry = new Diana_Model_WebsiteCountry();
        if(!$modelWebsiteCountry->updateCountWebsiteClickInClickOut($oldCountry,$countWebsite,$clickIn,$clickOut,true)){
            $this->setMsgs("更新失败 - 1");
            return false ;
        }
        if(!$modelWebsiteCountry->updateCountWebsiteClickInClickOut($newCountry,$countWebsite,$clickIn,$clickOut)){
            $this->setMsgs("更新失败 - 3");
            return false ;
        }
        return true;
    }

    /**
     * 通过洲获取国家信息
     */
    function getCountriesByContinent($continent)
    {
        $countries = array();
        $modelWebsiteCountry = new Diana_Model_WebsiteCountry();
        if(!$rowsWebsiteCountry = $modelWebsiteCountry->getRowsByContinent(true,$continent)){
            return false;
        }
        foreach($rowsWebsiteCountry as $rowWebsiteCountry){
            $countries[$rowWebsiteCountry['country_key']] = $rowWebsiteCountry;
        }
        return $countries;
    }

    /**
     * 获取洲信息
     * @return array|bool
     */
    function getContinents()
    {
        $continents = array();
        $modelWebsiteCountry = new Diana_Model_WebsiteCountry();
        if(!$rowsWebsiteCountry = $modelWebsiteCountry->getRowsByCondition(true)){
            return false;
        }
        foreach($rowsWebsiteCountry as $rowWebsiteCountry){
            $continentKey = $rowWebsiteCountry['country_continent'];
            if(empty($continents[$continentKey])){
                $continents[$continentKey] = array(
                    'continent_key' => $continentKey,
                    'continent_count_country' => 1,
                    'continent_count_website' => $rowWebsiteCountry['country_count_website'],
                    'continent_count_click_in' => $rowWebsiteCountry['country_count_click_in'],
                    'continent_count_click_out' => $rowWebsiteCountry['country_count_click_out'],
                );
            }else{
                $continents[$continentKey]['continent_count_country'] += 1;
                $continents[$continentKey]['continent_count_website'] += $rowWebsiteCountry['country_count_website'];
                $continents[$continentKey]['continent_count_click_in'] += $rowWebsiteCountry['country_count_click_in'];
                $continents[$continentKey]['continent_count_click_out'] += $rowWebsiteCountry['country_count_click_out'];
            }
        }
        return $continents;
    }

}
