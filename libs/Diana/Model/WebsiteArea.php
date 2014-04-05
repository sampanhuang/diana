<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteArea extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteArea();
    }



    /**更新网站数量
     * @param $value
     * @param $areaId
     * @param null $subtract
     * @return array|bool
     */
    function updateCountWebsite($value,$areaId,$subtract = null)
    {
        return $this->updateCountWebsiteClickInClickOut($areaId,$value,0,0,$subtract);
    }

    /**
     * 更新点击
     * @param $areaId
     * @param $value
     * @return array|bool
     */
    function updateCountClickIn($value,$areaId,$subtract = null)
    {
        return $this->updateCountWebsiteClickInClickOut($areaId,0,$value,0,$subtract);
    }

    /**
     * 更新点击
     * @param $areaId
     * @param $value
     * @return array|bool
     */
    function updateCountClickOut($value,$areaId,$subtract = null)
    {
        return $this->updateCountWebsiteClickInClickOut($areaId,0,0,$value,$subtract);
    }

    /**
     * @param $areaId 类别ID
     * @param $count 网站数
     * @param $clickIn 点击流入
     * @param $clickOut 点击流出
     * @param null $subtract 是否为减
     */
    function updateCountWebsiteClickInClickOut($areaId,$countWebsite = 0,$clickIn = 0,$clickOut = 0,$subtract = null)
    {
        if(empty($areaId)){
            return false;
        }
        //变更项不能全为空
        if(empty($countWebsite)&empty($clickIn)&empty($clickOut)){
            return false;
        }
        //判断这个类别是否正确
        if(!$rows = $this->getRowsById(true,$areaId)){
            return false;
        }
        $symbol = " + ";
        if($subtract){$symbol = " - ";}
        $condition = array( 'area_id' => $areaId);
        $data = array();
        if(intval($countWebsite) > 0){//更新类别网站数
            $data['area_count_website'] = new Zend_Db_Expr( 'area_count_website ' . $symbol . $countWebsite);
        }
        if(intval($clickIn) > 0){//更新类别点击流入
            $data['area_count_click_in'] = new Zend_Db_Expr( 'area_count_click_in ' . $symbol . $clickIn);
        }
        if(intval($clickOut) > 0){//更新类别点击流出
            $data['area_count_click_out'] = new Zend_Db_Expr( 'area_count_click_out ' . $symbol . $clickOut);
        }
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 通过父ID获取子ID纪录
     * @param null $refresh 是否刷新
     * @param $fatherId 父ID
     * @return array
     */
    function getRowsByFather($refresh = null,$fatherId)
    {
        $condition = array("area_fatherId" => $fatherId);
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
        $condition = array("area_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}