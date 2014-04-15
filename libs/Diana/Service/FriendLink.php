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
        $modelMemberFriend = new Diana_Model_FriendLink();
        if(!$rows = $modelMemberFriend->getRowsWithEnable()){
            return false;
        }
        foreach($rows as $row){

        }
    }
}
