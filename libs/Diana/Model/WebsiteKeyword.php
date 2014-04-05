<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-17
 * Time: 上午1:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteKeyword extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteKeyword();
    }

    /**增加使用统计
     * @param $tagIds 使用统计
     */
    function addCount($keywordId)
    {
        if(empty($keywordId)){
            return false;
        }
        $condition = array("keyword_id" => $keywordId);
        $data = array("keyword_update_time" => time(),"keyword_count_enter" => new Zend_Db_Expr( 'keyword_count_enter + 1'));
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 保存标签，已经存在的不会保存，不存在的会保存
     * @param $id
     * @param $intro
     * @return bool
     */
    function saveLabel($label)
    {
        if(empty($label)){
            return false;
        }
        if(!is_array($label)){
            $label = htmlspecialchars(trim($label));
            $label = str_replace("、",",",$label);
            $label = str_replace("，",",",$label);
            $label = str_replace(" ",",",$label);
            $label = array_filter(array_unique(explode(",",$label)));
        }
        $id = $this->getIdByLabel(null,$label);
        if(empty($id)){
            $saveLabel = $label;
        }else{
            $labelExist = array_keys($id);
            $saveLabel = array_diff($label,$labelExist);
        }
        foreach($saveLabel as $valSaveLabel){
            $data = array("keyword_label" => $valSaveLabel,'keyword_count_enter' => 0,'keyword_insert_time' => time(),"keyword_update_time" => time());
            $this->saveData(1,$data);
        }
        $id = $this->getIdByLabel(true,$label);
        return $this->getRowsById(true,array_values($id));
    }

    function getIdByKeyword($refresh = null,$keyword)
    {
        $id = array();
        $condition = array("keyword_label_like" => $keyword);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        foreach($rows as $row){
            $id[] = $row['keyword_id'];
        }
        return $id;
    }

    /**
     * 通过标签获取ID
     * @param null $refresh
     * @param $name
     * @return array|bool
     */
    function getIdByLabel($refresh = null,$label)
    {
        $ids = array();
        $condition = array("keyword_label" => $label);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        foreach($rows as $row){
            $ids[$row['keyword_label']] = $row['keyword_id'];
        }
        return $ids;
    }


    /**
     * 通过标签模糊查询获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByLabelLike($refresh = null,$labelLike)
    {
        $condition = array("keyword_label_like" => $labelLike);
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过标签获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByName($refresh = null,$label)
    {
        $condition = array("keyword_label" => $label);
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
        $condition = array("keyword_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
