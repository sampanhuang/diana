<?php
/**
 * 管理员-消息管理
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-7
 * Time: 下午5:19
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_ManagerMsg extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerMsg();
    }

    /**
     * 保存消息
     * @param $data 保存内容
     * @param null $id 消息流水号
     */
    function write($data,$id = null)
    {
        if(empty($data)||(!is_array($data))){
            return false;
        }
        if(empty($id)){
            $data['msg_insert_time'] = time();
            $data['msg_insert_ip'] = $_SERVER['REMOTE_ADDR'];
            return $this->saveData(1,$data);
        }else{
            $data['msg_update_time'] = time();
            $data['msg_update_ip'] = $_SERVER['REMOTE_ADDR'];
            $condition = array("msg_id" => $id);
            return $this->saveData(2,$data,$condition);
        }
    }

    /**
     * 通过管理员ID获取他收到的消息数量
     * @param $managerId
     * @return int 收件箱数量
     */
    function getCountWithInboxByMnanager($managerId)
    {
        $condition = array("msg_dest" => $managerId);
        return $this->getCountByCondition(null,$condition);
    }

    /**
     * 通过管理员ID获取他发送的消息数量
     * @param $managerId
     * @return int 收件箱数量
     */
    function getCountWithOutboxByMnanager($managerId)
    {
        $condition = array("msg_source" => $managerId);
        return $this->getCountByCondition(null,$condition);
    }

    /**
     * 通过ID获取消息
     * @param null $refresh
     * @param $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("msg_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
