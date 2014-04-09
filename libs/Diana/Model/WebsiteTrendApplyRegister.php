<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-1
 * Time: 下午10:38
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteTrendApplyRegister extends Diana_Model_Abstract
{
    var $year;
    function __construct($year = null)
    {
        parent::__construct();
        if(empty($year)){$year = date('Y'); }
        $this->dt = new Diana_Model_DbTable_WebsiteTrendApplyRegister($year);
        $this->year = $year;
    }

    function update($value = 1)
    {
        list($month,$day,$hour) = explode('-',date('n-j-G'));
        $column = 'trend_hour_'.intval($hour);
        $data = array( "trend_hour_total" => new Zend_Db_Expr( 'trend_hour_total + ' . $value),$column => new Zend_Db_Expr( $column .' + ' . $value));
        $condition = array('trend_month' => $month,'trend_day' => $day);
        if (!$rows = $this->saveData(2,$data,$condition)) {
            $data = array(
                'trend_year' => $this->year,
                'trend_month' => $month,
                'trend_day' => $day,
                'trend_hour_total' => $value,
                $column => $value,
            );
            if (!$rows = $this->saveData(1,$data)) {
                return false;
            }
        }
        return $rows;
    }

    function getAll()
    {

    }

    function getRowByDate($refresh,$month,$day)
    {
        $condition = array("trend_month" => $month,"trend_day"=>$day);
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