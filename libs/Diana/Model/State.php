<?php
/**
 * 参数配置
 *
 */
class Diana_Model_State extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_State();
    }

    /**
     * @param $key 键
     * @param $value 值
     * @param null $isFull 直接赋值，否为增量
     */
    function setValueByKey($key,$value,$isFull = null)
    {
        $condition = array("state_key" => $key);
        $data = array('state_update_time' => time());
        if(empty($isFull)){
            $data['state_value'] = new Zend_Db_Expr(" state_value + ".$value);
        }else{
            $data['state_value'] = $value;
        }
        return $this->saveData(2,$data,$condition);
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
        $condition = array("state_key" => $key);
        if ((!$rows = $this->getRowsByCondition($refresh,$condition))) {
            if(empty($default)){
                throw new Exception("你需要定义此项配置参数{{$key}}");
                return false;
            }
            return $default;
        }
        if(empty($rows[0]['state_value'])){
            $rows[0]['state_value'] = $rows[0]['state_default'];
        }
        return $rows[0]['state_value'];
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
        $lastTime = $rowsLastUpdateTime[0]['state_update_time'];
        if($rowsLastInsertTime[0]['state_insert_time'] > $lastTime){
            $lastTime = $rowsLastInsertTime[0]['state_insert_time'];
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
        $condition = array("state_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

    function getRowsByFatherId($refresh = null,$fatherId)
    {
        $condition = array("state_fatherId" => $fatherId);
        return $this->getRowsByCondition($refresh,$condition);
    }
}