<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteCategory extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteCategory();
    }

    /**
     * @param $categoryId 类别ID
     * @param $count 网站数
     * @param $clickIn 点击流入
     * @param $clickOut 点击流出
     * @param null $subtract 是否为减
     */
    function updateCountWebsiteClickInClickOut($categoryId,$countWebsite = 0,$clickIn = 0,$clickOut = 0,$subtract = null)
    {
        if(empty($categoryId)){
            return false;
        }
        //变更项不能全为空
        if(empty($countWebsite)&empty($clickIn)&empty($clickOut)){
            return false;
        }
        //判断这个类别是否正确
        if(!$rows = $this->getRowsById(true,$categoryId)){
            return false;
        }
        $symbol = " + ";
        if($subtract){$symbol = " - ";}
        $condition = array( 'category_id' => $categoryId);
        $data = array();
        if(intval($countWebsite) > 0){//更新类别网站数
            $data['category_count_website'] = new Zend_Db_Expr( 'category_count_website ' . $symbol . $countWebsite);
        }
        if(intval($clickIn) > 0){//更新类别点击流入
            $data['category_count_click_in'] = new Zend_Db_Expr( 'category_count_click_in ' . $symbol . $clickIn);
        }
        if(intval($clickOut) > 0){//更新类别点击流出
            $data['category_count_click_out'] = new Zend_Db_Expr( 'category_count_click_out ' . $symbol . $clickOut);
        }
        return $this->saveData(2,$data,$condition);
    }

    /**更新网站类型
     * @param $value
     * @param $categoryId
     * @param null $subtract
     * @return array|bool
     */
    function updateCountWebsite($value,$categoryId,$subtract = null)
    {
        return $this->updateCountWebsiteClickInClickOut($categoryId,$value,0,0,$subtract);
    }

    /**
     * 更新点击
     * @param $categoryId
     * @param $value
     * @return array|bool
     */
    function updateCountClickIn($value,$categoryId,$subtract = null)
    {
        return $this->updateCountWebsiteClickInClickOut($categoryId,0,$value,0,$subtract);
    }

    /**
     * 更新点击
     * @param $categoryId
     * @param $value
     * @return array|bool
     */
    function updateCountClickOut($value,$categoryId,$subtract = null)
    {
        return $this->updateCountWebsiteClickInClickOut($categoryId,0,0,$value,$subtract);
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