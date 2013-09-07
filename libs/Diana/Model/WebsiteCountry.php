<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteCountry extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteCountry();
    }

    /**
     * 网站变更资料
     * @param $country
     * @param $countWebsite
     * @param $clickIn
     * @param $clickOut
     * @param null $subtract
     */
    function updateCountWebsiteClickInClickOut($country,$countWebsite,$clickIn,$clickOut,$subtract = null)
    {
        if(empty($country)){
            return false;
        }
        //变更项不能全为空
        if(empty($countWebsite)&empty($clickIn)&empty($clickOut)){
            return false;
        }
        //判断这个类别是否正确
        if(!$rows = $this->getRowsByContinent(true,$country)){
            return false;
        }
        $symbol = " + ";
        if($subtract){$symbol = " - ";}
        $condition = array( 'country_key' => $country);
        $data = array();
        if(intval($countWebsite) > 0){//更新类别网站数
            $data['country_count_website'] = new Zend_Db_Expr( 'country_count_website ' . $symbol . $countWebsite);
        }
        if(intval($clickIn) > 0){//更新类别点击流入
            $data['country_count_click_in'] = new Zend_Db_Expr( 'country_count_click_in ' . $symbol . $clickIn);
        }
        if(intval($clickOut) > 0){//更新类别点击流出
            $data['country_count_click_out'] = new Zend_Db_Expr( 'country_count_click_out ' . $symbol . $clickOut);
        }
        return $this->saveData(2,$data,$condition);
    }


    function updateCountWebsite($value,$continent,$country)
    {
        $data = array(  "country_count_website" => new Zend_Db_Expr( 'country_count_website + ' . $value));
        $condition = array( 'country_key' => $country);
        if(!$rows = $this->saveData(2,$data,$condition)){
            $data = array(
                "country_continent" => $continent,
                "country_key" => $country,
                "country_count_website" => $value,
                "country_count_click_in" => 0,
                "country_count_click_out" => 0,
                "country_order" => 0,
                "country_insert_time" => time(),
            );
            $rows = $this->saveData(1,$data);
        }
        return $rows;
    }

    function updateCountClickOut($value,$continent,$country)
    {
        $data = array(  "country_count_click_out" => new Zend_Db_Expr( 'country_count_click_out + ' . $value));
        $condition = array( 'country_key' => $country);
        if(!$rows = $this->saveData(2,$data,$condition)){
            $data = array(
                "country_continent" => $continent,
                "country_key" => $country,
                "country_count_website" => 0,
                "country_count_click_in" => 0,
                "country_count_click_out" => $value,
                "country_order" => 0,
                "country_insert_time" => time(),
            );
            $rows = $this->saveData(1,$data);
        }
        return $rows;
    }

    function updateCountClickIn($value,$continent,$country)
    {
        $data = array(  "country_count_click_in" => new Zend_Db_Expr( 'country_count_click_in + ' . $value));
        $condition = array( 'country_key' => $country);
        if(!$rows = $this->saveData(2,$data,$condition)){
            $data = array(
                "country_continent" => $continent,
                "country_key" => $country,
                "country_count_website" => 0,
                "country_count_click_in" => $value,
                "country_count_click_out" => 0 ,
                "country_order" => 0,
                "country_insert_time" => time(),
            );
            $rows = $this->saveData(1,$data);
        }
        return $rows;
    }

    /**
     * @param null $refresh
     * @param $continent
     * @return array
     */
    function getRowsByContinent($refresh = null,$continent)
    {
        $condition = array("country_continent" => $continent);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     *
     */
    function getRowsByKey($refresh = null,$key)
    {
        $condition = array("country_key" => $key);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("website_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}