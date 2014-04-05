<?php
/**
 * 管理员-消息管理
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-7
 * Time: 下午5:19
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_MemberMsg extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_MemberMsg();
    }

    function updateInboxOutbox($msgId,$inBox = null,$outBox = null)
    {
        if(empty($msgId)&&(empty($inBox)||empty($outBox))){
            return false;
        }
        $data = array();
        $condition = array('msg_id' => $msgId);
        if($inBox){
            $data['msg_delete_inbox'] = 1;
        }
        if($outBox){
            $data['msg_delete_outbox'] = 1;
        }
        return $this->saveData(2,$data,$condition);
    }

    /**
     *
     * @param $source
     * @param $subject
     * @param $id
     */
    function saveMain($source,$subject,$id = null)
    {
        $data = array(
            'msg_source' => intval($source),
            'msg_subject' => $subject,
        );
        if(empty($id)){
            $type = 1;
            $condition = array();
            $data['msg_insert_time'] = time();
            $data['msg_insert_ip'] = $_SERVER['REMOTE_ADDR'];
            $data['msg_update_time'] = time();
            $data['msg_update_ip'] = $_SERVER['REMOTE_ADDR'];
        }else{
            $type = 2;
            $condition = array('msg_id' => $id);
            $data['msg_update_time'] = time();
            $data['msg_update_ip'] = $_SERVER['REMOTE_ADDR'];
        }
        return $this->saveData($type,$data,$condition);
    }

    function getIdWithDelete()
    {
        $msgId = array();
        $condition = array('msg_delete_inbox' => 1,'msg_delete_outbox' => 1);
        if(!$rows = $this->getRowsByCondition(null,$condition)){
            return false;
        }
        foreach($rows as $row){
            $msgId[] = $row['msg_id'];
        }
        return $msgId;
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
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
    }
}
