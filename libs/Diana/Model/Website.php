<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_Website extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_Website();
    }

    function updateClickIn($websiteId,$value = 1)
    {
        $data = array( "website_click_in" => new Zend_Db_Expr( 'website_click_in + ' . $value));
        $condition = array( 'website_id' => $websiteId);
        return $this->saveData(2,$data,$condition);
    }

    function updateClickOut($websiteId,$value = 1)
    {
        $data = array( "website_click_out" => new Zend_Db_Expr( 'website_click_out + ' . $value));
        $condition = array( 'website_id' => $websiteId);
        return $this->saveData(2,$data,$condition);
    }
    /**
     * 通过网站名获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByName($refresh = null,$name)
    {
        $condition = array("website_name" => $name);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过域名获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByDomain($refresh = null,$domain)
    {
        $condition = array("website_domain" => $domain);
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