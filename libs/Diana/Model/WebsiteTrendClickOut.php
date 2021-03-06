<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-3
 * Time: 下午4:05
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteTrendClickOut extends Diana_Model_Abstract
{
    var $year;
    function __construct($year = null)
    {
        parent::__construct();
        if(empty($year)){$year = date('Y'); }
        $this->dt = new Diana_Model_DbTable_WebsiteTrendClickOut($year);
        $this->year = $year;
    }

    function update($value = 1,$websiteId)
    {
        if(empty($websiteId)){
            return false;
        }
        if(!is_array($websiteId)){
            $websiteId = explode(",",$websiteId);
        }
        list($month,$day,$hour) = explode('-',date('n-j-G'));
        $column = 'trend_hour_'.intval($hour);
        $data = array( "trend_hour_total" => new Zend_Db_Expr( 'trend_hour_total + ' . $value),$column => new Zend_Db_Expr( $column .' + ' . $value));
        $condition = array( 'trend_eventId' => $websiteId,'trend_month' => $month,'trend_day' => $day);
        if (!$rows = $this->saveData(2,$data,$condition)) {
            $rows = array();
            foreach($websiteId as $valWebsiteId){
                $data = array(
                    'trend_eventId' => $valWebsiteId,
                    'trend_year' => $this->year,
                    'trend_month' => $month,
                    'trend_day' => $day,
                    'trend_hour_total' => $value,
                    $column => $value,
                );
                if ($tmpRows = $this->saveData(1,$data)) {
                    $rows = array_merge($rows,$tmpRows);
                }
            }
        }
        return $rows;
    }


    function getRowByDateEvent($refresh,$month,$day,$event)
    {
        $condition = array("trend_eventId" => $event,"trend_month" => $month,"trend_day"=>$day);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows[0];
    }

    function getRowsById($refresh,$id)
    {
        $condition = array("trend_id" => $id);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
    }
}
