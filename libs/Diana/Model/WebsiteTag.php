<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-17
 * Time: 上午1:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteTag extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteTag();
    }

    function deleteById($tagId)
    {
        if(empty($tagId)){
            return false;
        }
        $condition = array("tag_id" => $tagId);
        return $this->delData($condition);
    }

    /**
     * 减少使用统计
     * @param $tagIds 标签ID
     */
    function subtractCount($tagIds)
    {
        if(empty($tagIds)){
            return false;
        }
        $condition = array("tag_id" => $tagIds);
        $data = array("tag_count" => new Zend_Db_Expr( 'tag_count - 1'),'tag_update_time' => time());
        return $this->saveData(2,$data,$condition);
    }

    /**增加使用统计
     * @param $tagIds 使用统计
     */
    function addCount($tagIds)
    {
        if(empty($tagIds)){
            return false;
        }
        $condition = array("tag_id" => $tagIds);
        $data = array("tag_count" => new Zend_Db_Expr( 'tag_count + 1'),'tag_update_time' => time());
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 保存标签，已经存在的不会保存，不存在的会保存
     * @param $id
     * @param $name
     * @return bool
     */
    function saveTag($name)
    {
        if(empty($name)){
            return false;
        }
        if(!$name = Com_Functions::tagToArray($name)){
            $this->setMsgs('标签过滤失败');
            return false;
        }
        $ids = $this->getIdByName(null,$name);
        if(empty($ids)){
            $saveName = $name;
        }else{
            $nameExist = array_keys($ids);
            $saveName = array_diff($name,$nameExist);
        }
        foreach($saveName as $valueSaveName){
            $data = array("tag_name" => $valueSaveName,'tag_count' => 0,'tag_insert_time' => time());
            $this->saveData(1,$data);
        }
        $ids = $this->getIdByName(true,$name);
        return $this->getRowsById(true,array_values($ids));
    }

    function getIdByKeyword($refresh = null,$keyword)
    {
        $ids = array();
        $condition = array("tag_name_like" => $keyword);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        foreach($rows as $row){
            $ids[$row['tag_name']] = $row['tag_id'];
        }
        return $ids;
    }

    /**
     * 通过标签获取ID
     * @param null $refresh
     * @param $name
     * @return array|bool
     */
    function getIdByName($refresh = null,$name)
    {
        $id = array();
        $condition = array("tag_name" => $name);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        foreach($rows as $row){
            $id[$row['tag_name']] = $row['tag_id'];
        }
        return $id;
    }


    /**
     * 通过标签模糊查询获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByNameLike($refresh = null,$nameLike)
    {
        $condition = array("tag_name_like" => $nameLike);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过标签获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByName($refresh = null,$name)
    {
        $condition = array("tag_name" => $name);
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
        $condition = array("tag_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
