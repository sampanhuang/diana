<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-30
 * Time: 下午9:36
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteCategory extends Diana_Service_Abstract
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
    function websiteChangeCategory($oldCategoryId,$newCategoryId,$countWebsite = 0,$clickIn = 0,$clickOut = 0)
    {
        if(empty($oldCategoryId)||empty($newCategoryId)){
            $this->setMsgs("参数不能为空 - oldCategoryId - newCategoryId");
            return false ;
        }
        if(empty($countWebsite)&&empty($clickIn)&&empty($clickOut)){
            $this->setMsgs("参数不能为空 - countWebsite - clickIn - clickOut");
            return false ;
        }
        //如果这个网站没有改变类型，就什么也不用动
        if($oldCategoryId == $newCategoryId){
            return true;
        }
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if(!$modelWebsiteCategory->updateCountWebsiteClickInClickOut($oldCategoryId,$countWebsite,$clickIn,$clickOut,true)){
            $this->setMsgs('更新失败-1');
            return false;
        }
        if(!$modelWebsiteCategory->updateCountWebsiteClickInClickOut($newCategoryId,$countWebsite,$clickIn,$clickOut)){
            $modelWebsiteCategory->updateCountWebsiteClickInClickOut($oldCategoryId,$countWebsite,$clickIn,$clickOut);
            $this->setMsgs('更新失败-1');
            return false;
        }
        return true;
    }

    /**
     * 获取父类
     * @return array|bool
     */
    function getOptionsWithFather()
    {
        $options = array();
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if(!$rows = $modelWebsiteCategory->getRowsByCondition(null)){
            return false;
        }
        foreach($rows as $row){
            $tmpFatherId = $row['category_fatherId'];
            $tmpRowSonId = $row['category_id'];
            if($tmpFatherId == 0){
                $options[$tmpRowSonId] = $row['category_name_'.DIANA_TRANSLATE_DEFAULT];
            }
        }
        return $options;
    }

    /**
     * 获取子类
     * @return array|bool
     */
    function getOptionsWithSon()
    {
        $options = array();
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if(!$rows = $modelWebsiteCategory->getRowsByCondition(null)){
            return false;
        }
        foreach($rows as $row){
            $tmpFatherId = $row['category_fatherId'];
            $tmpRowSonId = $row['category_id'];
            if($tmpFatherId > 0){
                $options[$tmpFatherId][$tmpRowSonId] = $row['category_name_'.DIANA_TRANSLATE_DEFAULT];
            }
        }
        return $options;
    }

    /**
     * 生成树数据
     *
     * @return unknown
     */
    function makeTreegrid()
    {
        $treeGrid = array();
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if(!$rows = $modelWebsiteCategory->getRowsByCondition()){
            return false;
        }
        foreach($rows as $row){
            if(empty($row['category_fatherId'])){
                $treeGrid[] = $row;
            }
        }
        foreach($treeGrid as &$rowFather){
            foreach($rows as $row){
                if(!empty($row['category_fatherId'])){
                    if( $rowFather['category_id'] == $row['category_fatherId'] ){
                        $rowFather['children'][] = $row;
                    }
                }
            }
        }
        return $treeGrid;
    }

    /**
     * 获取所有分类数据
     *
     * @return unknown
     */
    function getAll($condition = null,$seq = null)
    {
        $all = array();
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if(!$rows = $modelWebsiteCategory->getRowsByCondition(null,$condition,$seq)){
            return false;
        }
        foreach($rows as $row){
            $all[$row['category_id']] = $row;
        }
        return $all;
    }

    /**
     * 获取所有分类ID
     */
    function getIds()
    {
        $ids = array();
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if(!$rows = $modelWebsiteCategory->getRowsByCondition()){
            return false;
        }
        foreach($rows as $row){
            $ids[] = $row['category_id'];
        }
        return $ids;
    }
}
