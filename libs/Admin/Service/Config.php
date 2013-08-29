<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-21
 * Time: 下午11:21
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_Config extends Diana_Service_Config
{
    function __construct()
    {
        parent::__construct();
    }


    function indexByFather($fatherId)
    {
        $modelConfig = new Diana_Model_Config();
        if(!$rows = $modelConfig->getRowsByFatherId(null,$fatherId)){
            return false;
        }
        return $rows;
    }

    function getDetail($configId)
    {
        if(empty($configId)){
            return false;
        }
        $modelConfig = new Diana_Model_Config();
        if(!$rows = $modelConfig->getRowsById(null,$configId)){
            return false;
        }
        return $rows[0];
    }
}