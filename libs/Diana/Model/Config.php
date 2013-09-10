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
		$condition = array("conf_key" => $key);
		if ((!$rows = $this->getRowsByCondition($refresh,$condition))) {
            if(empty($default)){
                throw new Exception("你需要定义此项配置参数{{$key}}");
                return false;
            }
            return $default;
		}
        if(empty($rows[0]['conf_value'])){
            $rows[0]['conf_value'] = $rows[0]['conf_default'];
        }
        return $rows[0]['conf_value'];
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