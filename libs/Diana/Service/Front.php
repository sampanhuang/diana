<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-10-24
 * Time: 下午10:01
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_Front extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取主菜单
     */
    function getMainChannels()
    {
        $modelFrontChannel = new Diana_Model_FrontChannel();
        if(!$rowsFrontChannel = $modelFrontChannel->getRowsByFather(null,0)){
            $this->setMsgs('暂无频道信息');
            return false;
        }
        $modelFrontChannelEnable = new Diana_Model_FrontChannelEnable();
        foreach($rowsFrontChannel as $key => $rowFrontChannel){
            if($rowFrontChannel['channel_enable'] == 2){
                if(!$modelFrontChannelEnable->checkEnable(null,$rowFrontChannel['channel_id'],DIANA_TIMESTAMP_START)){
                    unset($rowsFrontChannel[$key]);
                }
            }elseif($rowFrontChannel['channel_enable'] == 3){
                unset($rowsFrontChannel[$key]);
            }
        }
        return $rowsFrontChannel;
    }

    /**
     * 获取频道信息
     */
    function getChannels()
    {
        $arrChannel = array();
        $modelFrontChannel = new Diana_Model_FrontChannel();
        if(!$rowsFrontChannel = $modelFrontChannel->getRowsByFather(null,0)){
            $this->setMsgs('暂无频道信息');
            return false;
        }
        foreach($rowsFrontChannel as &$rowFrontChannel){
            if($rowsSonFrontChannel = $modelFrontChannel->getRowsByFather(null,$rowFrontChannel['channel_id'])){
                $rowFrontChannel['channel_son'] = $rowsSonFrontChannel;
                return false;
            }
        }
        return $rowsFrontChannel;
    }
}
