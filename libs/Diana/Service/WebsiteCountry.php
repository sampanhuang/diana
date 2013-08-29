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
