<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-1-7
 * Time: 下午9:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_MemberFriend extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_MemberFriend();
    }

    /**
     * 增加一条纪录
     * @param $master 主ID
     * @param $guest 客ID
     * @return array
     */
    function insertByMasterGuest($master,$guest,$requestTme,$requestIp)
    {
        $data = array(
            'friend_master_memberId' => $master,
            'friend_guest_memberId' => $guest,
            'friend_request_time' => $requestTme,
            'friend_request_ip' => $requestIp,
            'friend_insert_time' => time(),
            'friend_insert_ip' => $_SERVER['REMOTE_ADDR'],
        );
        return $this->saveData(1,$data);
    }

    /**
     * 删除纪录
     * @param $master
     * @param $guest
     * @return int
     */
    function deleteByMasterGuest($master,$guest)
    {
        $condition = array(
            'friend_master_memberId' => $master,
            'friend_guest_memberId' => $guest,
        );
        return $this->delData($condition);
    }


    function getRowsByMasterGuest($refresh,$master,$guest)
    {
        $condition = array("friend_master_memberId" => $master , 'friend_guest_memberId' => $guest);
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
        $condition = array("friend_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}
