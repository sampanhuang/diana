<?php
/**
 * 网站标签的使用情况
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-17
 * Time: 上午1:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteTagRelation extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteTagRelation();
    }


    /**
     * 保存对应关系
     * @param $id
     * @param $intro
     * @return bool
     */
    function saveRelation($websiteId,$tagId)
    {
        if(!$rows = $this->getRowsByWebsiteTag(true,$websiteId,$tagId)){
            $data = array("relation_websiteId" => $websiteId,'relation_tagId' => $tagId,'relation_time' => time());
            if(!$rows = $this->saveData(1,$data)){
                return false;
            }
        }
        return $rows;
    }

    /**
     * 保存网站关系
     * @param $websiteId
     * @param $tagId
     */
    function resetWebsiteRelation($websiteId,$tagIds)
    {
        if(empty($websiteId)||empty($tagIds)){
            return false;
        }
        if(!is_array($tagIds)){
            $tagIds = array_filter(array_unique(explode(",",$tagIds)));
        }
        if($this->getRowsByWebsiteTag(null,$websiteId)){
            if(!$this->deleteByWebsite($websiteId)){
                return false;
            }
        }
        $rows = array();
        foreach($tagIds as $tagId){
            $data = array("relation_websiteId" => $websiteId,'relation_tagId' => $tagId,'relation_time' => time());
            if($tmpRows = $this->saveData(1,$data)){
                $rows = array_merge($rows,$tmpRows);
            }
        }
        return $rows;
    }

    /**
     * 根据网站ID删除对应关系
     * @param $websiteId
     * @return bool|int
     */
    function deleteByWebsite($websiteId)
    {
        if(empty($websiteId)){
            return false;
        }
        $condition['relation_websiteId'] = $websiteId;
        return $this->delData($condition);
    }

    /**
     * 通过标签ID得到网站ID
     * @param null $refresh 是否刷新
     * @param $tagId 标签ID
     * @return array|bool 网站ID
     */
    function getWebsiteByTag($refresh = null,$tagId)
    {
        $websiteId = array();
        if(!$rows = $this->getRowsByWebsiteTag($refresh,null,$tagId)){
            return false;
        }
        foreach($rows as $row){
            $websiteId[] = $row['relation_websiteId'];
        }
        return $websiteId;
    }

    /**
     * 通过网站Id和标签ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByWebsiteTag($refresh = null,$websiteId = null,$tagId = null)
    {
        if(empty($websiteId)&&empty($tagId)){
            return false;
        }
        $condition = array();
        if(!empty($websiteId)){
            $condition['relation_websiteId'] = $websiteId;
        }
        if(!empty($tagId)){
            $condition['relation_tagId'] = $tagId;
        }
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
        $condition = array("relation_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
