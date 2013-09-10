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
    static  $words = array();
    var $dirWords = '';

    function __construct()
    {
        parent::__construct();
        $this->dirWords = DIANA_DIR_DATA.'/filter_word/';
    }



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
            if(file_exists($pathWords)){//不存在则创建
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

    function generateData()
    {

    }

    function filter($str)
    {

    }

    /**
     * 敏感词库大小，越大性能越慢
     * @return array
     */
    function getOptionBySize()
    {
        return array('small','medium','largen','huge');
    }

}