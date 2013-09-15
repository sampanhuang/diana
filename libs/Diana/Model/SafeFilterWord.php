<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-17
 * Time: 上午1:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_SafeFilterWord extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_SafeFilterWord();
    }

    /**
     * 更新敏感词使用量
     * @param $word 敏感词
     * @param $count 使用量
     * @param null $isFull 是否为全的
     * @return array
     */
    function updateCount($word,$count,$isFull = null)
    {
        if(empty($isFull)){
            $data = array( "word_count" => new Zend_Db_Expr(" word_count + ".$count) );
        }else{
            $data = array( "word_count" => $count );
        }
        $condition = array("word_val" => $word);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 保存敏感词
     * @param $word
     * @return bool|array
     */
    function saveWord($word)
    {
        if(empty($word)){
            return false;
        }
        $word = trim(strtolower($word));
        if(!$this->getCountByWord(null,$word)){
            return false;
        }
        $tmpData = array("word_val" => $word,"word_time" => time());
        return $this->saveData(1,$tmpData);
    }

    /**最后一次的插入时间
     * @return bool
     */
    function lastTime()
    {
        if(!$rowsSafeFilterWord = $this->getRowsByCondition(null,null,"new",1)){
            return false;
        }
        return $rowsSafeFilterWord['word_time'];
    }

    /**
     * 通过敏感词获取数量
     *
     * @param string|array $word
     * @return array
     */
    function getCountByWord($refresh = null,$word)
    {
        $condition = array("word_val" => $word);
        return $this->getCountByCondition($refresh,$condition);
    }

    /**
     * 通过敏感词获取多条纪录
     *
     * @param string|array $word
     * @return array
     */
    function getRowsByWord($refresh = null,$word)
    {
        $condition = array("word_val" => $word);
        return $this->getRowsByCondition($refresh,$condition);
    }


    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("website_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
