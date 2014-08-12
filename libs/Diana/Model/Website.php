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

    /**
     * 更新点击流入
     * @param $websiteId 网站ID
     * @param int $value 点击流入
     * @return array 更新后的纪录
     */
    function updateClickIn($websiteId,$value = 1)
    {
        $data = array( "website_click_in" => new Zend_Db_Expr( 'website_click_in + ' . $value));
        $condition = array( 'website_id' => $websiteId);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 更新点击流出
     * @param $websiteId ID
     * @param int $value 点击流出
     * @return array 更新后的纪录
     */
    function updateClickOut($websiteId,$value = 1)
    {
        $data = array( "website_click_out" => new Zend_Db_Expr( 'website_click_out + ' . $value));
        $condition = array( 'website_id' => $websiteId);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 更新网站主体信息
     * @param $websiteId 网站ID
     * @param $data
     * @return array
     */
    function updateMainById($websiteId,$data,$man)
    {
        $columns = array(
            "website_name",//网站名称
            "website_domain",//网站域名
            "website_logo",//网站标识
            "website_cover",//网站封面
            "website_tag",//网站标签
            "website_categoryId",//网站分类
            "website_areaId",//网站大陆
        );
        foreach($columns as $column){
            if(!empty($data[$column])){
                $tmpUpdateData[$column] = $data[$column];
            }
        }
        $tmpUpdateData["website_update_time"] = time();
        $tmpUpdateData["website_update_man"] = $man;
        $tmpUpdateData["website_update_ip"] = $_SERVER['REMOTE_ADDR'];
        $condition = array( 'website_id' => $websiteId);
        return $this->saveData(2,$tmpUpdateData,$condition);
    }
    /**
     * 通过简体网站名获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByNameWithZhCn($refresh = null,$name)
    {
        $condition = array("website_name_zh-cn" => $name);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过繁体网站名获取多条纪录
     * @param null $refresh 是否刷新
     * @param $name 网站名
     * @return array 数组
     */
    function getRowsByNameWithZhTw($refresh = null,$name)
    {
        $condition = array("website_name_zh-tw" => $name);
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
     * 判断这个网站名称是否可用,如果id不为空，则是编辑状态下的判断，如果为空，则是添加状态下的判断
     * @param $name 网站名称
     * @param $id 网站ID
     */
    function checkName($refresh = null,$name,$id = null)
    {
        $condition = array("website_name" => $name);
        if(!empty($id)){
            $condition['website_id_not'] = $id;
        }
        return $this->getCountByCondition($refresh,$condition);
    }

    /**
     * 判断这个网站域名是否可用,如果id不为空，则是编辑状态下的判断，如果为空，则是添加状态下的判断
     * @param null $refresh 是否刷新
     * @param $domain 网站域名
     * @param null $id 网站ID
     * @return int 数量
     */
    function checkDoamin($refresh = null,$domain,$id = null)
    {
        $condition = array("website_domain" => $domain);
        if(!empty($id)){
            $condition['website_id_not'] = $id;
        }
        return $this->getCountByCondition($refresh,$condition);
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