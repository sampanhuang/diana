<?php
/**
 * 参数配置
 *
 */
class Diana_Model_BulletinChannel extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_BulletinChannel();
    }


    /**
     * 通过流水号ID获取一条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowById($refresh = null,$id)
    {
        if(!$rows = $this->getRowsById($refresh,$id)){
            return false;
        }
        return $rows[0];
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("channel_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }


}