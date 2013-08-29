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

    function updateCountWebsite($value,$categoryId)
    {
        if(!$rows = $this->getRowsById(true,$categoryId)){
            return false;
        }
        $data = array(  "category_count_website" => new Zend_Db_Expr( 'category_count_website + ' . $value));
        $condition = array( 'category_id' => $categoryId);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 更新点击
     * @param $categoryId
     * @param $value
     * @return array|bool
     */
    function updateCountClickOut($value,$categoryId)
    {
        if(!$rows = $this->getRowsById(true,$categoryId)){
            return false;
        }
        $data = array(  "category_count_click_out" => new Zend_Db_Expr( 'category_count_click_out + ' . $value));
        $condition = array( 'category_id' => $categoryId);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 更新点击
     * @param $categoryId
     * @param $value
     * @return array|bool
     */
    function updateCountClickIn($value,$categoryId)
    {
        if(!$rows = $this->getRowsById(true,$categoryId)){
            return false;
        }
        $data = array(  "category_count_click_in" => new Zend_Db_Expr( 'category_count_click_in + ' . $value));
        $condition = array( 'category_id' => $categoryId);
        return $this->saveData(2,$data,$condition);
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