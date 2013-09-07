<?php
/**
 * 会员-消息管理
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



    function getRowsById($refresh = null,$id)
    {
        $condition = array("msg_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }
}
