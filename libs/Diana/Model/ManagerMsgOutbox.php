<?php
/**
 * 管理员-消息管理
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-7
 * Time: 下午5:19
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_ManagerMsgOutbox extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerMsgOutbox();
    }

    /**
     * 更新发送时间，发送时间为0，则是指草稿箱，大于零，则是发件箱
     * @param $msgId 消息ID
     * @param $managerId 管理员ID
     * @return array|bool
     */
    function updateSendTime($outboxId)
    {
        if(empty($outboxId)){
            return false;
        }
        $data = array(
            'outbox_msg_send_time' => time(),
        );
        $condition = array(
            'outbox_id' => $outboxId,
        );
        if(!$rows = $this->saveData(2,$data,$condition)){
            return false;
        }
        return $rows;
    }

    /**
     * 增加进草稿箱或是收件箱
     * @param $msgId 消息ID
     * @param $managerId 发件人ID
     */
    function addMsg($msgId,$managerId)
    {
        if(empty($msgId)||empty($managerId)){
            return false;
        }
        $data = array(
            'outbox_msgId' => $msgId,
            'outbox_managerId' => $managerId,
        );
        return $this->saveData(1,$data);
    }

    /**
     * 删除草稿或是收件箱
     * @param $msgId
     * @param $managerId
     * @return bool|int
     */
    function delMsg($msgId,$managerId)
    {
        if(empty($msgId)||empty($managerId)){
            return false;
        }
        $condition = array(
            'outbox_msgId' => $msgId,
            'outbox_managerId' => $managerId,
        );
        return $this->delData($condition);
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
            $condition['outbox_msgId'] = $msgId;
        }
        if(!empty($managerId)){
            $condition['outbox_managerId'] = $managerId;
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
        $condition = array("outbox_id" => $id);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
    }
}
