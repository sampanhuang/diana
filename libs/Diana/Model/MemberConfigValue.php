<?php
/**
 * 参数配置
 *
 */
class Diana_Model_MemberConfigValue extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_MemberConfigValue();
    }

    function getRowByConfigMember($refresh = null,$configId,$memberId)
    {
        if(empty($memberId)||empty($configId)){
            return false;
        }
        $condition = array("conf_id" => $configId,"conf_memberId" => $memberId);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows[0];
    }

    function getRowsByMember($refresh = null,$memberId)
    {
        if(empty($memberId)){
            return false;
        }
        $condition = array("conf_memberId" => $memberId);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
    }


    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}