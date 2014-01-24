<?php
/**
 * 参数配置
 *
 */
class Diana_Model_MemberConfig extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_MemberConfig();
    }

    /**
     * 通过key值获取多条纪录
     * @param null $refresh 是否刷新
     * @param sting Key值
     * @param null $default
     * @return bool
     */
    function getRowByKey($refresh = null,$key)
    {
        $condition = array("conf_key" => $key);
        if (!$rows = $this->getRowsByCondition($refresh,$condition)) {
            return false;
        }
        return $rows[0];
    }

    function getRowById($refresh = null,$id)
    {
        if(!$rows = $this->getRowsById(null,$id)){
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
        $condition = array("conf_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

    function getRowsByFatherId($refresh = null,$fatherId)
    {
        $condition = array("conf_fatherId" => $fatherId);
        return $this->getRowsByCondition($refresh,$condition);
    }
}