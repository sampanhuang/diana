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

    /**
     * 生成treegrid所需数据
     */
    function makeTreegrid()
    {
        $treeGrid = array();
        $modelConfig = new Diana_Model_Config();
        //获取所有数据
        if(!$rows = $modelConfig->getRowsByCondition()){
            return false;
        }
        //获取一级配置
        foreach($rows as $row){
            if(empty($row['conf_fatherId'])){
                $row['state'] = 'closed';
                $treeGrid[] = $row;
            }
        }
        foreach($treeGrid as &$rowFather){
            foreach($rows as $row){
                if($rowFather['conf_id'] == $row['conf_fatherId']){
                    $rowFather['children'][] = $row;
                }
            }
        }
        return $treeGrid;
        
    }

    /**
     * 通过父级ID得到子配置索引
     * @param $fatherId
     * @return array|bool 配置列表
     */
    function indexByFather($fatherId)
    {
        $modelConfig = new Diana_Model_Config();
        if(!$rows = $modelConfig->getRowsByFatherId(null,$fatherId)){
            return false;
        }
        return $rows;
    }

    /**
     * 获取配置详细信息
     * @param $configId
     * @return bool
     */
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