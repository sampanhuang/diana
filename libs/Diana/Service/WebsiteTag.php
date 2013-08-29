<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-17
 * Time: 上午6:26
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_WebsiteTag extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取网站列表
     * @param $counter
     * @param array $condition
     * @param null $order
     * @return array|bool
     */
    function listByCondition($counter,$condition = array(),$order = null)
    {
        $modelWebsiteTag = new Diana_Model_WebsiteTag();
        if(!$rowsWebsiteTag = $modelWebsiteTag->getRowsByCondition(null,$condition,$order,$counter,0)){
            return false;
        }
        return $rowsWebsiteTag;
    }

    /**
     * @param $key 关键字
     * @param $page 当前页
     * @param $pagesize 每页的纪录数
     */
    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null)
    {

        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        $modelWebsiteTag = new Diana_Model_WebsiteTag();
        if($countWebsiteTag = $modelWebsiteTag->getCountByCondition(null,$condition)){
            $rowsWebsiteTag = $modelWebsiteTag->getRowsByCondition(null,$condition,$order,$pagesize,$offset);
        }
        return array('total' => $countWebsiteTag,'rows' => $rowsWebsiteTag);
    }

    /**
     * 标签详情
     * @param $tagId
     * @return bool
     */
    function detailById($tagId)
    {
        $condition = array("tag_id" => $tagId);
        $modelWebsiteTag = new Diana_Model_WebsiteTag();
        if(!$rowsWebsiteTag = $modelWebsiteTag->getRowsByCondition(null,$condition,null,1,0)){
            return false;
        }
        $detailWebsiteTag = $rowsWebsiteTag[0];
        //获取使用这个标签的网站
        $modelWebsiteTagRelation = new Diana_Model_WebsiteTagRelation();
        if($rowsWebsiteTagRelation = $modelWebsiteTagRelation->getRowsByWebsiteTag(null,null,$tagId)){
            $websiteId = array();
            foreach($rowsWebsiteTagRelation as $rowWebsiteTagRelation){
                $websiteId[] = $rowWebsiteTagRelation['relation_websiteId'];
            }
            $modelWebsite = new Diana_Model_Website();
            if($rowsWebsite = $modelWebsite->getRowsById(null,$websiteId)){
                $detailWebsiteTag['tag_website'] = $rowsWebsite;
            }
        }
        return $detailWebsiteTag;
    }

    /**
     * 更新网站标签
     * 先删了关系，然后再创建新的
     * @param $websiteId 网站ID
     * @param $websiteTag 网站标签
     */
    function updateWebsiteTag($websiteId,$websiteTag)
    {
        //参数不能为空
        if(empty($websiteId)||empty($websiteTag)){
            return false;
        }
        if(!$arrWebsiteTag = Com_Functions::tagToArray($websiteTag)){
            $this->setMsgs('标签过滤失败');
            return false;
        }
        $arrWebsiteTagId = array();
        //通过标签得到标签ID
        $modelWebsiteTag = new Diana_Model_WebsiteTag();
        if(!$rowsWebsiteTag = $modelWebsiteTag->saveTag($arrWebsiteTag)){
            $this->setMsgs('标签保存失败');
            return false;
        }
        foreach($rowsWebsiteTag as $rowWebsiteTag){
            $arrWebsiteTagId[] = $rowWebsiteTag['tag_id'];
        }
        //更新关系表
        if(!$rowsWebsiteTagRelation = $this->resetWebsiteRelation($websiteId,$arrWebsiteTagId)){
            $this->setMsgs('标签关联更新失败');
            return false;
        }
        return $rowsWebsiteTagRelation;
    }

    /**
     * 更新标签的使用
     * @param $websiteId
     * @param $tagIds
     * @return array|bool
     */
    function resetWebsiteRelation($websiteId,$tagIds)
    {
        if(empty($websiteId)||empty($tagIds)){
            return false;
        }
        if(!is_array($tagIds)){
            $tagIds = array_filter(array_unique(explode(",",$tagIds)));
        }
        $modelWebsiteTag = new Diana_Model_WebsiteTag();
        $modelWebsiteTagRelation = new Diana_Model_WebsiteTagRelation();
        //要被删除的标签
        if($rowsWebsiteTagRelation = $modelWebsiteTagRelation->getRowsByWebsiteTag(null,$websiteId)){
            $subtractTagId = array();
            foreach($rowsWebsiteTagRelation as $rowWebsiteTagRelation){
                $subtractTagId[] = $rowWebsiteTagRelation['relation_tagId'];
            }
            if(!$modelWebsiteTagRelation->deleteByWebsite($websiteId)){
                return false;
            }
            $modelWebsiteTag->subtractCount($subtractTagId);
        }
        //会被增加的标签关系
        $rows = array();
        foreach($tagIds as $tagId){
            $data = array("relation_websiteId" => $websiteId,'relation_tagId' => $tagId,'relation_time' => time());
            if($tmpRows = $modelWebsiteTagRelation->saveData(1,$data)){
                $rows = array_merge($rows,$tmpRows);
            }
        }
        $modelWebsiteTag->addCount($tagIds);
        return $rows;
    }

    /**
     * 删除网站标签
     * @param $websiteId 网站ID
     */
    function deleteByWebsite($websiteId)
    {
        if(empty($websiteId)){
            return false;
        }
        $websiteTagId = array();
        $deleteWebsiteTagId = array();
        $updateWebsiteTagId = array();
        //获取网站标签对应关系
        $modelWebsiteTagRelation = new Diana_Model_WebsiteTagRelation();
        if(!$rowsWebsiteTagRelation = $modelWebsiteTagRelation->getRowsByWebsiteTag(true,$websiteId)){
            return false;
        }
        foreach($rowsWebsiteTagRelation as $rowWebsiteTagRelation){
            $websiteTagId[] = $rowWebsiteTagRelation['relation_tagId'];
        }
        $modelWebsiteTagRelation->deleteByWebsite($websiteId);
        $modelWebsiteTag = new Diana_Model_WebsiteTag();
        if($rowsWebsiteTag = $modelWebsiteTag->getRowsById(true,$websiteTagId)){
            foreach($rowsWebsiteTag as $rowWebsiteTag){
                if($rowWebsiteTag['tag_count'] == 1){
                    $deleteWebsiteTagId[] = $rowWebsiteTag['tag_id'];
                }elseif($rowWebsiteTag['tag_count'] > 1){
                    $updateWebsiteTagId[] = $rowWebsiteTag['tag_id'];
                }
            }
            if(!empty($deleteWebsiteTagId)){
                $modelWebsiteTag->deleteById($deleteWebsiteTagId);
            }
            if(!empty($updateWebsiteTagId)){
                $modelWebsiteTag->subtractCount($updateWebsiteTagId);
            }
        }
        return true;
    }
}
