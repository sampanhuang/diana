<?php
/**
 * 参数配置
 *
 */
class Diana_Model_Config extends Diana_Model_Abstract
{		
	function __construct()
	{
		parent::__construct();
		$this->dt = new Diana_Model_DbTable_Config();
	}
	
	/**
	 * 通过key值获取多条纪录
	 *
	 * @param int|bool $refresh 是否刷新
	 * @param string $key KEY值
	 * @return array
	 */
	function getValueByKey($refresh = null,$key,$default = null)
	{
        $value = $default;
		$condition = array("conf_key" => $key);
		if ($rows = $this->getRowsByCondition($refresh,$condition)) {
            if(($rows[0]['conf_value'] == '')||($rows[0]['conf_value'] == null)){
                $rows[0]['conf_value'] = $rows[0]['conf_default'];
            }
            $value = $rows[0]['conf_value'];
		}
        return $value;
	}


    /**
     * 获取这个表的最后时间（插入或是更新）
     * @return bool 最后更新时间
     */
    function getLastTime()
    {
        if(!$rowsLastUpdateTime = $this->getRowsByCondition(null,null,'last_update_time',1)){
            return false;
        }
        if(!$rowsLastInsertTime = $this->getRowsByCondition(null,null,'last_insert_time',1)){
            return false;
        }
        $lastTime = $rowsLastUpdateTime[0]['conf_update_time'];
        if($rowsLastInsertTime[0]['conf_insert_time'] > $lastTime){
            $lastTime = $rowsLastInsertTime[0]['conf_insert_time'];
        }
        return $lastTime;
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