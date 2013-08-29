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

    function getAll()
    {
        $modelWebsiteCategory = new Diana_Model_WebsiteCategory();
        if(!$rows = $modelWebsiteCategory->getRowsByCondition()){
            return false;
        }
        return $rows;
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
