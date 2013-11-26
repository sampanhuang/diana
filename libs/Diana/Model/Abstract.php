<?php
/**
 * 抽像类
 *
 */
abstract class Diana_Model_Abstract
{
	static $cacheDefault;
	static $loger;
	var $dt;
	var $preCachekey;
	
	
	function __construct()
	{
		$this->setBootstartResources();
		$this->setPreCachekey();
	}


	/**
	 * 获取缓存前缀
	 */
    function setPreCachekey()
    {
        $this->preCachekey = get_called_class();
        return $this->preCachekey;
    }


	
	/**
	 * 通过条件获取多条纪录
	 * @param int|bool $refresh 刷新
	 * @param array $condition 条件
	 * @param array $seq 排序
	 * @param int $count 纪录数
	 * @param int $offset 开始纪录
	 * @return array
	 */
	function getRowsByCondition($refresh = null ,$condition = null,$seq = null,$count = null,$offset = null)
	{
		if (!empty($seq)) {
			$ordersKey = array_keys($this->dt->_orders);
			if (!in_array($seq,$ordersKey)) {
				unset($seq);
			}
		}
		$func = "getRowsByConditionFromDb";
		return $this->getDataByConditionFromCache($refresh,$this->preCachekey,$func,$condition,$seq,$count,$offset);
	}
	
	/**
	 * 通过条件获取纪录总数
	 *
	 * @param unknown_type $refresh
	 * @param unknown_type $condition
	 * @return unknown
	 */
	function getCountByCondition($refresh = null ,$condition = null)
	{
		$func = "getCountByConditionFromDb";		
		return $this->getDataByConditionFromCache($refresh,$this->preCachekey,$func,$condition);		
	}
	
	/**
	 * 从数据库获取纪录
	 *
	 * @param array $condition 查询条件
	 * @param array $seq 排序
	 * @param int $count 纪录数
	 * @param int $offset 开始纪录
	 * @return array 
	 */
	function getRowsByConditionFromDb($condition = null,$seq = null,$count = null,$offset = null)
	{
		//echo '$count'.$count.'$offset'.$offset;
		return $this->dt->getRowsByCondition($condition,$seq,$count,$offset);
	}
	
	/**
	 * 从数据库获取纪录总数
	 *
	 * @param array $condition 条件
	 * @return int
	 */
	function getCountByConditionFromDb($condition)
	{
		return $this->dt->getCountByCondition($condition);
	}
	
	/**
	 * 读取数据模型
	 * 
	 * @param string $funcName 函数名 合并多个数据表的函数
	 * @param string $cachekey 当前缓存键值前缀
	 * @param bool|int $refresh 是否刷新缓存
	 * @return array 数据模型
	 */
	function getDataByConditionFromCache($refresh,$preCachekey,$func,$condition = null,$seq = null,$count = null,$offset = null)
	{
		$data = array();
		$cachekey = $preCachekey."_".$func.
					Com_Functions::makeCachekey($condition).
					Com_Functions::makeCachekey($seq).
					Com_Functions::makeCachekey(array("count" => $count,"offset" => $offset));
		if ($refresh) {self::$cacheDefault->remove($cachekey);}//清理缓存
		if (!$data = self::$cacheDefault->load($cachekey)) {
			if (!$data = $this->{$func}($condition,$seq,$count,$offset)) {
				return false;
			}
			//self::$cacheDefault->save($data,$cachekey,array($this->preCachekey));
		}
		return $data;
	}
	
	/**
	 * 保存数据
	 *
	 * @param string $type 插入还是保存(1插入，2保存)
	 * @param array $data 要保存的数据
	 * @param array $condition 保存的条件
	 */
	function saveData($type,$data,$condition = null)
	{
		//参数不能为空
		if (empty($type)||empty($data)) {throw new Exception(__METHOD__.'的参数$type与$data不能为空');}
		//参数不能有错
		if ($type <> 1 && $type <> 2) {throw new Exception(__METHOD__.'的参数$type只能是1或是2');}
		//data不能有误
		if (!is_array($data)) {throw new Exception(__METHOD__.'的参数$data必须是数组类型');}
		//如果是更新的话，条件不能为空
		if (($type == 2)&&(empty($condition))) {throw new Exception(__METHOD__.'的参数$condition不能为空');}
		$rows = array();		
		if ($type == 1) {
			$id = $this->dt->save(1,$data);//插入数据，获取主键ID			
			$rows = $this->getRowsById(true,$id);//更新缓存
		}elseif ($type == 2){
			if ($this->dt->save(2,$data,$condition)) {//更新纪录
				//self::$cacheDefault->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, $this->preCachekey);//更新缓存
				$rows = $this->getRowsByCondition(true,$condition);
				//print_r($rows);
			}
		}
		return $rows;
	}
	
	/**
	 * 删除数据
	 *
	 * @param array $condition 删除条件
	 * @return int 受影响的纪录数
	 */
	function delData($condition)
	{
		//参数不能为空
		if (empty($condition)) {throw new Exception(__METHOD__.'的参数$condition不能为空');}
		//$condition不能有误
		if (!is_array($condition)) {throw new Exception(__METHOD__.'的参数$condition必须是数组类型');}
		//删除数据
		$rowsAffected = 0;
		if ($rowsAffected = $this->dt->del($condition)) {
			self::$cacheDefault->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($this->preCachekey));//更新缓存
		}
		return $rowsAffected;
	}

	
	/**
	 * 获取启动需要载入的资源
	 * 例如缓存管理、日志等
	 *
	 */
	public function setBootstartResources()
	{
		if (empty(self::$cacheDefault)||empty(self::$loger)) {
			$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
			if (!$bootstrap->hasPluginResource('cachemanager')) {
				throw new Exception(DIR_DATA_CONFIG."/boot.ini need resources cachemanager");
			}
			$cachemanager = $bootstrap->getResource('cachemanager');
			self::$cacheDefault = $cachemanager->getCache('default');
			
			if (!$bootstrap->hasPluginResource("log")) {
				throw new Exception(DIR_DATA_CONFIG."/boot.ini need resources log");
			}
			self::$loger = $bootstrap->getResource("log");
		}		
	}
	
	
}