<?php
/**
 * 管理员-消息管理
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-7
 * Time: 下午5:19
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_ManagerMsgDest extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerMsgDest();
    }

    /**
     *
     * @param $msgId 消息ID
     * @param $msgDest 收件人
     */
    function updateDest($msgId,$msgDest = null)
    {
        if(empty($msgId)){
            return false;
        }
        $condition = array("msg_id" => $msgId);
        $rowsAffected = $this->delData($condition);
        if(!empty($msgDest)){
            if(!is_array($msgDest)){
                $msgDest = explode(',',$msgDest);
            }
            foreach($msgDest as $valDest){
                $tmpData = array(
                    'msg_id' => $msgId,
                    'msg_dest' => $valDest,
                );
                $this->saveData(1,$tmpData);
            }
        }
        return true;
    }

    /**
     * 通过ID获取消息
     * @param null $refresh
     * @param $id
     * @return array
     */
    function getRowsByMsg($refresh = null,$msgId)
    {
        $condition = array("msg_id" => $msgId);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
    }

    /**
     * 通过ID获取消息
     * @param null $refresh
     * @param $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("id" => $id);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
    }
}
