<?php
abstract class Diana_Model_DbTable_Abstract extends Zend_Db_Table_Abstract
{
	var $_orders = array();//所有的排序方式
	
	public function __construct($registerKeyWithDbAbstract = null)
	{
		parent::__construct($registerKeyWithDbAbstract);		
		$this->_name = "tb_".$this->_name;//加上表前缀
		$this->setOrders();//设置默认的排序方式
	}
	
	abstract function setOrders();//定义排序方式
	abstract function getWheresByCondition($condition);//通过Condition获取Wheres

	/**
	 * 保存数据
	 *
	 * @param array $data 保存数据
	 * @param array $condition 保存条件
	 * @param int $type 保存类型 1是添加，2是编辑
	 */
	function save($type,$data,$condition = null)
	{		
		if ($type == 1) {
			return $this->insert($data);
		}elseif ($type == 2){
			if ($wheres = $this->getWheresByCondition($condition)) {	
				return $this->update($data,$wheres);
			}else{
				return false;
				//throw new Exception("错误的保存条件".json_encode($condition).json_encode($wheres));
			}			
		}else{
			throw new Exception("错误的类型");
		}
	}
	
	/**
	 * 删除数据
	 *
	 * @param array $condition 删除条件
	 * @return int 删除了多少条
	 */
	function del($condition)
	{
		if (empty($condition)) {
			throw new Exception("参数不能为空!");
		}
		if ($wheres = $this->getWheresByCondition($condition)) {	
			return $this->delete($wheres);
		}else{
			throw new Exception("错误的查询条件".json_encode($condition).json_encode($wheres));
		}
	}
	
	/**
	 * 通过SQL得到纪录总数
	 *
	 * @param array $condition 查询条件
	 */
	function getCountByCondition($condition)
	{		
		$select = $this->select()->from($this->_name, 'count(*)');
		if ($wheres = $this->getWheresByCondition($condition)) {			
			foreach ($wheres as $whereKey => $whereVal){
				$select->where($whereKey,$whereVal);
			}
		}
		return $this->getAdapter()->fetchOne($select);		
	}
	

	
	/**
	 * 通过SQL得到纪录
	 *
	 */
	function getRowsByCondition($condition,$seq = null,$count = null,$offset = null)
	{	
		$order = $this->getOrderBySeq($seq);
		$wheres = $this->getWheresByCondition($condition);		
		//echo '$count'.$count.'$offset'.$offset;
		if (!$rows = $this->fetchAll($wheres,$order,$count,$offset)) {
			return false;
		}
		return $rows->toArray();
	}
	
	/**
	 * 获取排序方式
	 *
	 * @param string $key 排序方式
	 */
	function getOrderBySeq($seq = null)
	{
		if (empty($seq)) {
			$firstOrders = array_slice($this->_orders,0,1);
			$firstOrders = array_values($firstOrders);
			return $firstOrders[0];
		}else{
			return $this->_orders[$seq];
		}
	}
	
	/**
	 * 过滤查询条件的条目值
	 * 通过条件得到SQL语句
	 *
	 * @param int|string|array $item 查询条件的条目值
	 * @param int|bool $type 当成什么类型过滤，1数字，2是等于，3是小于等于，4是大于等于,5是模糊查询
	 */
	function getWhereByCondition($columnKey,$columnVal,$type = null)
	{
		if (empty($columnKey)&&empty($columnVal)) {throw new Exception('参数$columnKey及$columnVal不能为空');}
		if ($type == 1 || $type == 11 || $type == 2 || $type == 5) {//数字或是等于
			if ($type == 1 && empty($columnVal) && is_numeric($columnVal)) {
				$columnVal = array(0);
			}
			if($type == 5){
                $columnVal = str_replace("、",",",$columnVal);
                $columnVal = str_replace("，",",",$columnVal);
                $columnVal = str_replace(" ",",",$columnVal);
            }
			if (is_scalar($columnVal)) {$columnVal = explode(",",$columnVal);}
			if (!is_array($columnVal)) {throw new Exception("错误的参数类型".$columnVal);}	
			$columnVal = array_map("strtolower",$columnVal);		
			$columnVal = array_map("trim",$columnVal);
			if ($type == 2) {//如果是字符比较的话，才允许过滤空值
				$columnVal = array_filter($columnVal);
			}			
		}else{//大于或是小于
			$columnVal = intval(trim($columnVal));
		}
		
		if ($type == 1) {//数字
			$columnVal = array_map("intval",$columnVal);			
			$wheres["`{$columnKey}` in (?)"] = $columnVal;
		}elseif ($type == 11){
			$columnVal = array_map("intval",$columnVal);			
			$wheres["`{$columnKey}` not in (?)"] = $columnVal;
		}elseif ($type == 2){//字符
			$arrWheres = array();
			$strWheres = "";
			foreach ($columnVal as $tmpV){
				$arrWheres[] = "( `{$columnKey}` = '{$tmpV}' )";
			}
			$strWheres = implode("or",$arrWheres);
			$wheres[$strWheres] = "1";
		}elseif ($type == 3){//小于
			$wheres["`{$columnKey}` <= ?"] = $columnVal;
		}elseif ($type == 4){//大于
			$wheres["`{$columnKey}` >= ?"] = $columnVal;
		}elseif ($type == 5){//类似
			$arrWheres = array();
			$strWheres = "";
            $columnVal = array_filter(array_unique($columnVal));
            if(!empty($columnVal)){
                foreach ($columnVal as $tmpV){
                    $arrWheres[] = "( `{$columnKey}` like '%{$tmpV}%' )";
                }
                $strWheres = implode("or",$arrWheres);
                $wheres[$strWheres] = "1";
            }
		}
		return $wheres;
	}
	
	/**
	 * 通过$suffix生成用于复制对像表的名称，然后判断是否存在，不存在则创建
	 *
	 * @param int $suffix 后缀
	 */
	function setTableName($suffix)
	{
		if (empty($suffix)) {
			throw new Exception(__METHOD__." param is empty");
		}
		$sourceTableName = $this->_name;
		$destTableName = $sourceTableName."_".$suffix;
		$this->copyTableStructure($sourceTableName,$destTableName);
		$this->_name = $destTableName;
	}
	
	/**
	 * 复制表$source的结构成为$dest
	 *
	 * @param string $dest
	 * @param string $source
	 */
	function copyTableStructure($source,$dest)
	{
		if (empty($dest)||empty($source)) {//参数不能为空
			throw new Exception(__METHOD__."param is empty");
		}
		try {
			$db = $this->getAdapter();
			$sql="SHOW TABLES LIKE '".$source."'";
			if(!$db->fetchOne($sql)){//判断表$source是否存在	
				throw new Exception(__METHOD__."复制失败，复制源表{$source}不存在");
			}
			$sql="SHOW TABLES LIKE '".$dest."'";
			if(!$db->fetchOne($sql)){//判断表$dest是否存在				
				if(!empty($source)){
					$sql_copy = "CREATE TABLE ".$dest." LIKE ".$source;				
					$db->query($sql_copy);
				}
			}
		}catch (Exception $e){
			die($e->getMessage());
		}		
	}
}