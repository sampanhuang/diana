<?php
/**
 * 前端菜单开放设定
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-10-24
 * Time: 下午9:59
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_FrontChannelEnable extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_FrontChannelEnable();
    }

    /**
     * 确认当前频道是否有效
     * @param null $refresh
     * @param $channelId
     * @param int $time
     */
    function checkEnable($refresh = null,$channelId,$time = 0)
    {
        if(empty($time)){$time = time();}
        if(!$rows = $this->getRowsByChannel($refresh,$channelId)){
            return false;
        }
        foreach($rows as $row){
            if( ( $time > $row['enable_time_min'] ) && ( $time < $row['enable_time_min'] ) ){
                return true;
            }
        }
    }

    /**
     * 通过频道ID获取纪录
     * @param null $refresh 是否刷新
     * @param $fatherId 父ID
     * @return array
     */
    function getRowsByChannel($refresh = null,$channelId)
    {
        $condition = array("enable_channelId" => $channelId);
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
        $condition = array("enable_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}
