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
     * 概述
     * array('total' => 数量,'time_frist' => 最开始导入的时间,'time_last' => 最后导入时间)
     */
    function summarize()
    {
        $summarize = array(
            'total' => 0,//数量
            'time_first' => 0,//最开始导入的时间
            'time_last' => 0,//最后导入时间
            'import_count' => 0,//导入次数
        );

        $modelSafeFilterWord = new Diana_Model_SafeFilterWord();
        if($total = $modelSafeFilterWord->getCountByCondition()){
            $summarize['total'] = $total;
        }
        if($timeFirst = $modelSafeFilterWord->firstTime()){
            $summarize['time_first'] = $timeFirst;
        }
        if($timeLast = $modelSafeFilterWord->lastTime()){
            $summarize['time_last'] = $timeLast;
        }
        $serviceState = new Diana_Service_State();
        if($valFilterWordImportCount = $serviceState->getValueByKey('safe_filter_word_import_count',0)){
            $summarize['import_count'] = $valFilterWordImportCount;
        }
        //获取各个敏感词库的配置信息
        $serviceConfig = new Diana_Model_Config();
        $summarize['filter_word_size'] = $serviceConfig->getValueByKey(null,'filter_word_size','small');
        $summarize['filter_word_replace'] = $serviceConfig->getValueByKey(null,'filter_word_replace','*');
        foreach($this->optionsFilterWordSize as $optionFilterWordSize){
            $tmpConfigKey = 'filter_word_count_'.$optionFilterWordSize;
            $summarize[$tmpConfigKey] = $serviceConfig->getValueByKey(null,$tmpConfigKey,1000);
        }
        return $summarize;
    }

    function getHot($count)
    {
        $modelSafeFilterWord = new Diana_Model_SafeFilterWord();
        if(!$rows = $modelSafeFilterWord->getRowsByCondition(null,null,'hot',$count)){
            $this->setMsgs("暂无敏感词数据!");
            return false;
        }
        return $rows;
    }

    /**
     *
     * @param $word
     * @return bool|int
     */
    function import($word)
    {
        if((empty($word))||(!is_scalar($word))){
            $this->setMsgs("参数不能为空");
            return false;
        }
        //各种形式的分隔符都需要接受
        $word = str_replace(array("、","，","|"),",",$word);
        $word = strtolower($word);
        $arrWord = explode(',',$word);
        $arrWord = array_map('trim',$arrWord);//过滤空值
        $arrWord = array_unique($arrWord);//过滤重复值
        $arrWord = array_filter($arrWord);//过滤空值
        //开始写入
        $counterSuccess = 0;
        $wordInsertTime = time();
        $modelSafeFilterWor = new Diana_Model_SafeFilterWord();
        foreach($arrWord as $tmpWord){
            echo $tmpWord;
            if($modelSafeFilterWor->saveWord($tmpWord,$wordInsertTime)){
                $counterSuccess++;
            }
        }
        if($counterSuccess == 0){
            $this->setMsgs("导入失败，你导入的都是重复的敏感词");
            return false;
        }
        //加入状态
        $serviceState = new Diana_Service_State();
        if(!$valueState = $serviceState->setValueByKey('safe_filter_word_import_count',1)){
            $this->setMsgs($serviceState->getMsgs());
        }
        return $counterSuccess;
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
            if(file_exists($pathWords)){//对比时间，如果晚于最后导入时间，则需要重新导过
                $fileLastTime = filemtime($pathWords);//文件最后被修改时间
                $modelSafeFilterWord = new Diana_Model_SafeFilterWord();
                $dbLastTime = $modelSafeFilterWord->lastTime();
                if($dbLastTime > $fileLastTime){
                    $this->generateData();
                }
            }else{//不存在则创建
                $this->generateData();
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
            $tmpContent = '<?php return '.var_export(array_slice ($tmpArrFilterWord, 0, $configFilterWordCount[$optionFilterWordSize]),true).';';
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