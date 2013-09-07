<?php
/**
 * 配置文件
 * User: sampan
 * Date: 13-7-21
 * Time: 下午11:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_Config extends Diana_Service_Abstract
{
    var $captchaObj;
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 通过KEy获取value
     * @param $key
     */
    function getValueByKey($key)
    {
        $modelConfig = new Diana_Model_Config();
        if(!$rows = $modelConfig->getValueByKey(null,$key)){

        }
    }




}