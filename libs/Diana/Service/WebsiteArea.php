<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-30
 * Time: 下午9:36
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteArea extends Diana_Service_Abstract
{

    function __construct()
    {
        parent::__construct();
    }


    /**
     * 网站变更类型，需要更新这个类型的网站数，点击流入和点击流出
     * @param $oldCategoryId 旧的类别ID
     * @param $newCategoryId 新的类别ID
     * @param $websiteId 网站ID
     * @param $clickIn 网站的点击流入
     * @param $clickOut 网站的点击流出

     */
    function websiteChangeArea($oldAreaId,$newAreaId,$countWebsite = 0,$clickIn = 0,$clickOut = 0)
    {
        if(empty($oldAreaId)||empty($newAreaId)){
            $this->setMsgs("参数不能为空 - $oldAreaId - $newAreaId");
            return false ;
        }
        if(empty($countWebsite)&&empty($clickIn)&&empty($clickOut)){
            $this->setMsgs("参数不能为空 - countWebsite - clickIn - clickOut");
            return false ;
        }
        //如果这个网站没有改变类型，就什么也不用动
        if($oldAreaId == $newAreaId){
            return true;
        }
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if(!$modelWebsiteArea->updateCountWebsiteClickInClickOut($oldAreaId,$countWebsite,$clickIn,$clickOut,true)){
            $this->setMsgs('更新失败-1');
            return false;
        }
        if(!$modelWebsiteArea->updateCountWebsiteClickInClickOut($newAreaId,$countWebsite,$clickIn,$clickOut)){
            $modelWebsiteArea->updateCountWebsiteClickInClickOut($oldAreaId,$countWebsite,$clickIn,$clickOut);
            $this->setMsgs('更新失败-1');
            return false;
        }
        return true;
    }

    /**
     * 获取所有的父地区
     * @return array|bool array(ID => 名称)
     */
    function getOptionsWithFather()
    {
        $options = array();
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if(!$rwsFather = $modelWebsiteArea->getRowsByFather(null,0)){
            return false;
        }
        foreach($rwsFather as $rowFather){
            $areaId = $rowFather['area_id'];
            $options[$areaId] = $rowFather['area_name_'.DIANA_TRANSLATE_DEFAULT];
        }
        return $options;
    }

    /**
     * 获取所有子地区
     * @return bool array(父ID => array(子ID=> 子名称))
     */
    function getOptionsWithSon()
    {
        $rowsSon = array();
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if(!$rowsFather = $modelWebsiteArea->getRowsByFather(null,0)){
            return false;
        }
        foreach($rowsFather as $rowFather){
            $tmpFatherId = $rowFather['area_id'];
            if($tmpRowsSon = $this->getRowsByFather($tmpFatherId)){
                foreach($tmpRowsSon as $tmpRowSon){
                    $tmpRowSonId = $tmpRowSon['area_id'];
                    $rowsSon[$tmpFatherId][$tmpRowSonId] = $tmpRowSon['area_name_'.DIANA_TRANSLATE_DEFAULT];
                }
            }
        }
        return $rowsSon;
    }

    /**
     * 生成树数据
     *
     * @return unknown
     */
    function makeTreegrid()
    {
        $treeGrid = array();
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if(!$rows = $modelWebsiteArea->getRowsByCondition()){
            return false;
        }
        //获取父ID
        foreach($rows as $row){
            if(empty($row['area_fatherId'])){
                $treeGrid[] = $row;
            }
        }
        foreach($treeGrid as &$rowFather){
            foreach($rows as $row){
                if(!empty($row['area_fatherId'])){
                    if($rowFather['area_id'] == $row['area_fatherId']){
                        $rowFather['children'][] = $row;
                    }
                }
            }
        }
        return $treeGrid;
    }

    /**
     * 通过父ID获取纪录
     * @param int $fatherId
     * @return bool
     */
    function getRowsByFather($fatherId = 0)
    {
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if(!$rows = $modelWebsiteArea->getRowsByFather(null,$fatherId)){
            return false;
        }
        foreach($rows as &$row){
            $row['area_count_website_total'] = $row['area_count_website'];
            $row['area_count_click_in_total'] = $row['area_count_website'];
            $row['area_count_click_out_total'] = $row['area_count_website'];
            $tmpCountSon = 0;
            $tmpCountWebsite = 0;
            $tmpCountClickin = 0;
            $tmpCountClickOut = 0;
            if($rowsSon = $this->getRowsByFather($row['area_id'])){
                foreach($rowsSon as $rowSon){
                    $tmpCountSon++;
                    $tmpCountWebsite += $rowSon['area_count_website'];
                    $tmpCountClickin += $rowSon['area_count_click_in'];
                    $tmpCountClickOut += $rowSon['area_count_click_out'];
                }
                $row['area_count_son'] = $tmpCountSon;
                $row['area_count_website_total'] += $tmpCountWebsite;
                $row['area_count_click_in_total'] += $tmpCountClickin;
                $row['area_count_click_out_total'] += $tmpCountClickOut;
            }
        }
        return $rows;
    }

    /**
     * 获取所有网站数据
     *
     * @return unknown
     */
    function getAll($condition = null,$seq = null)
    {
        $all = array();
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if(!$rows = $modelWebsiteArea->getRowsByCondition(null,$condition,$seq)){
            return false;
        }
        foreach($rows as $row){
            $all[$row['area_id']] = $row;
        }
        return $all;
    }

    /**
     * 获取所有分类ID
     */
    function getIds()
    {
        $ids = array();
        $modelWebsiteArea = new Diana_Model_WebsiteArea();
        if(!$rows = $modelWebsiteArea->getRowsByCondition()){
            return false;
        }
        foreach($rows as $row){
            $ids[] = $row['category_id'];
        }
        return $ids;
    }
}
