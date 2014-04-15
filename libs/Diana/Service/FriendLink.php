<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-4-12
 * Time: 下午4:08
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_FriendLink extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取前台所需要的友情链接
     */
    function getRowsForFront()
    {
        //获取前台显示友情链接的条数
        $serviceConfig = new Diana_Service_Config();
        if(!$valConfigFriendLinkIndexShowCount = $serviceConfig->getValueByKey('friend-link_index-show-count')){
            $this->setMsgs($serviceConfig->getMsgs());
            return false;
        }
        $condition = array(//查询条件
            'link_enable_time'  => time(),
        );
        $modelFriendLink = new Diana_Model_FriendLink();
        if(!$rowsFriendLink = $modelFriendLink->getRowsByCondition(false,$condition,null,$valConfigFriendLinkIndexShowCount)){
            return false;
        }
        foreach($rowsFriendLink as &$rowFriendLink){
            $rowFriendLink['link_label'] = $rowFriendLink['link_label_'.DIANA_TRANSLATE_CURRENT];
        }
        return $rowsFriendLink;
    }

    /**
     * 点击友情链接
     * @param $linkId
     * @return bool
     */
    function click($linkId)
    {
        //参数判断
        if(empty($linkId)||(!is_numeric($linkId))){
            $this->setMsgs('参数ＩＤ 不能为空且必须为数字');
            return false;
        }
        //判断当前链接是否有效
        $modelFriendLink = new Diana_Model_FriendLink();
        if(!$rowsFriendLink = $modelFriendLink->getRowsById(null,$linkId)){
            $this->setMsgs('无效的参数ＩＤ ');
            return false;
        }
        //更新当前点击数
        $isfFirstClick = false;
        if(empty($rowsFriendLink[0]['link_click_first_time'])){
            $isfFirstClick = true;
        }
        if(!$modelFriendLink->updateForClick($rowsFriendLink[0]['link_id'],$isfFirstClick)){
            $this->setMsgs('点击数更新失败');
            return false;
        }
        return $rowsFriendLink[0];

    }
}
