<?php
/**
 * 管理员活动纪录
 *
 */
class Diana_Model_MemberLogRemark extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_MemberLogRemark();
    }

    /**
     * 写入日志
     *
     * @param int $uid 用户ID
     * @param string $remark 备注
     * @return array
     */
    function write($id,$remark = null)
    {
        if (empty($id)) {
            return false;
        }
        $data = array(
            'log_id' => $id,
            'log_user_agent' => $_SERVER['HTTP_USER_AGENT'],
        );
        if(!empty($remark)){
            $data['log_remark'] = json_encode($remark);
        }
        return $this->saveData(1,$data);
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("log_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }


}