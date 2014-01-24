<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-2
 * Time: 下午4:49
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_MemberConfigUpdateHistory extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct($year = null);
        $this->dt = new Diana_Model_DbTable_MemberConfigUpdateHistory($year = null);
    }

    /**
     * 写入历史纪录
     * @param $configKey 配置KEY
     * @param $configValue 配置值
     */
    function write($configId,$configKey,$configValue,$memberId)
    {
        //参数不能为空
        if(empty($configId)||empty($configKey)||empty($configValue)||empty($memberId)){
            return false;
        }

        $data = array(
			'history_memberId' => $memberId,
            'history_configId' => $configId,
            'history_configKey' => $configKey,
            'history_configValue' => $configValue,
            'history_insert_time' => time(),
            'history_insert_ip' => $_SERVER['REMOTE_ADDR'],
        );
        return $this->saveData(1,$data);
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("history_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
