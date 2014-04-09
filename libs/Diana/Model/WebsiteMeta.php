<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-17
 * Time: 上午1:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteMeta extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteMeta();
    }


    /**
     * 保存介绍
     * @param $id
     * @param $intro
     * @return bool
     */
    function saveMeta($id,$metaKeywords,$metaDescription)
    {
        $condition = array("website_id" => $id);
        $data = array("website_meta_keywords" => $metaKeywords,"website_meta_description" => $metaDescription);
        if(!$rows = $this->saveData(2,$data,$condition)){
            $data['website_id'] = $id;
            if(!$rows = $this->saveData(1,$data)){
                return false;
            }
        }
        return $rows;
    }

    /**
     * 清除介绍
     * @param $id
     * @return int
     */
    function clearMeta($id)
    {
        $condition = array("website_id" => $id);
        return $this->delData($condition);
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
