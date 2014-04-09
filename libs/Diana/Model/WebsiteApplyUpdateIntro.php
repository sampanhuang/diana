<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-17
 * Time: 上午1:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_WebsiteApplyUpdateIntro extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_WebsiteApplyUpdateIntro();
    }


    /**
     * 保存介绍
     * @param $id
     * @param $intro
     * @return bool
     */
    function saveIntro($id,$intro)
    {
        if(empty($id)||empty($intro)){
            return false;
        }
        $condition = array("update_id" => $id);
        $data = array("website_intro" => $intro);
        if(!$rows = $this->saveData(2,$data,$condition)){
            $data['update_id'] = $id;
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
    function clearIntro($id)
    {
        $condition = array("update_id" => $id);
        return $this->delData($condition);
    }

    function getIntroById($refresh = null,$id)
    {
        $intro = array();
        $condition = array("update_id" => $id);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        foreach($rows as $row){
            $intro[$row['update_id']] = $row['website_intro'];
        }
        return $intro;
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("update_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
