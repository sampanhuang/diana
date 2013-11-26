<?php
/**
 * 管理员-消息管理
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-7
 * Time: 下午5:19
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_ManagerMsgInbox extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerMsgInbox();
    }

    /**
     * 将消息设为已读
     * @param $inboxId
     * @return array|bool
     */
    function updateReadTime($inboxId)
    {
        if(empty($inboxId)){
            return false;
        }
        if(!is_array($inboxId)){
            $inboxId = explode(',',$inboxId);
        }
        $data = array('inbox_msg_read_time' => time());
        $condition = array("inbox_id" => $inboxId);
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 给收件人的收件箱里添加东西
     * @param $msgId 消息ID
     * @param $managerId 管理员ID
     */
    function addMsg($msgId,$managerId)
    {
        if(empty($msgId)||empty($managerId)){
            return false;
        }
        if(!is_array($managerId)){
            $managerId = explode(',',$managerId);
        }
        foreach($managerId as $val){
            $tmpData = array(
                'inbox_msgId' => $msgId,
                'inbox_managerId' => $val,
                'inbox_msg_accept_time' => time(),
            );
            $this->saveData(1,$tmpData);
        }
        return true;
    }

    /**
     * 通过msgid和managerid获取
     * @param null $refresh
     * @param null $msgId
     * @param null $managerId
     * @return array|bool
     */
    function getRowsByMsgManager($refresh = null,$msgId = null,$managerId = null)
    {
        if(empty($msgId)&&empty($managerId)){
            return false;
        }
        $condition = array();
        if(!empty($msgId)){
            $condition['inbox_msgId'] = $msgId;
        }
        if(!empty($managerId)){
            $condition['inbox_managerId'] = $managerId;
        }
        return $this->getRowsByCondition($refresh,$condition);
    }

    /**
     * 通过ID获取消息
     * @param null $refresh
     * @param $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("inbox_id" => $id);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
    }
}
