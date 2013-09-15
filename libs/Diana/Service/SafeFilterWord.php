<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-10
 * Time: 上午1:04
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_SafeFilterWord extends Diana_Service_Abstract
{
    //敏感词库
    static  $words = array();
    //敏感词库路径
    var $dirWords = '';
    //敏感词库大小，越大性能越慢
    var $optionsFilterWordSize = array("small","medium","largen","huge");

    function __construct()
    {
        parent::__construct();
        $this->dirWords = DIANA_DIR_DATA.'/filter_word/';
    }


    /**
     * 获取单词库
     * @return array|mixed 单词库
     */
    function getWords()
    {
        if(empty(self::$words)){
            //敏感词级别
            $serviceConfig = new Diana_Model_Config();
            $valFilterWordSize = $serviceConfig->getValueByKey(null,'filter_word_size','small');
            //敏感词库目录
            $pathWords = $this->dirWords.'/'.$valFilterWordSize.'.php';
            //最后一次提交时间

            //创建文件
            if(!file_exists($pathWords)){//不存在则创建
                $this->generateData();
            }else{//对比时间，如果晚于最后导入时间，则需要重新导过
                $fileLasttime = filemtime($pathWords);//文件最后被修改时间
                $modelSafeFilterWord = new Diana_Model_SafeFilterWord();
                $importLasttime = $modelSafeFilterWord->lastTime();
                if($importLasttime > $fileLasttime){
                    $this->generateData();
                }
            }
            self::$words = include($pathWords);
        }
        return self::$words;
    }

    /**
     * 生成单词数据
     */
    function generateData()
    {
        $configFilterWordCount = array();
        //获取各个敏感词库的配置信息
        $serviceConfig = new Diana_Model_Config();
        foreach($this->optionsFilterWordSize as $optionFilterWordSize){
            $configFilterWordCount[$optionFilterWordSize] = $serviceConfig->getValueByKey(null,'filter_word_count_'.$optionFilterWordSize,1000);
        }
        //从数据库中取出敏感词
        $modelSafeFilterWord = new Diana_Model_SafeFilterWord();
        if(!$rowsFilterWord = $modelSafeFilterWord->getRowsByCondition(null,null,'hot',$configFilterWordCount['huge'],0)){
            $this->setMsgs("暂无任何关键字");
            return false;
        }
        $tmpArrFilterWord = array();
        foreach($rowsFilterWord as $rowFilterWord){
            $tmpArrFilterWord[] = $rowFilterWord["word_val"];
        }
        //写入敏感词库
        foreach($this->optionsFilterWordSize as $optionFilterWordSize){
            $tmpPath = $this->dirWords.'/'.$optionFilterWordSize.'.php';
            $tmpContent = "return ".var_export(array_slice ($tmpArrFilterWord, 0, $configFilterWordCount[$optionFilterWordSize]));
            file_put_contents($tmpPath,$tmpContent);
        }
        return true;
    }

    /**
     * 过滤$str内的敏感词
     * @param $str
     */
    function filter($str)
    {
        //校验
        if(empty($str)){
            $this->setMsgs("参数不能为空");
            return false;
        }
        if(!is_scalar($str)){
            $this->setMsgs("参数必须为标量");
            return false;
        }
        //获取敏感词库
        if(!$words = $this->getWords()){
            $this->setMsgs("敏感词库获取失败!");
            return false;
        }
        $searchWords = array();
        $replaceWords = array();
        $tmpStrToLower = strtolower($str);
        $modelSafeFilterWord = new Diana_Model_SafeFilterWord();
        foreach($words as $word){
            if(!empty($word)){
                $tmpCount = substr_count($tmpStrToLower,$word);
                if($tmpCount > 0){
                    $searchWords[] = $word;
                    $modelSafeFilterWord->updateCount($word,$tmpCount);
                }
            }
        }
        //准备好被换好的单词
        foreach($searchWords as $searchWord){
            $replaceWords[] = str_repeat('*',strlen($searchWord));
        }
        $str = str_replace($searchWords,$replaceWords,$str);
        return $str;
    }

}