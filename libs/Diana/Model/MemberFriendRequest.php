<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-1-7
 * Time: 下午9:10
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_MemberFriendRequest extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_MemberFriendRequest();
    }

    /**
     * 添加数据
     * @param $source
     * @param $dest
     * @param $remark
     * @return array
     */
    function insertBySourceDestRemark($source,$dest,$remark,$pass = 3)
    {
        $data = array(
            'request_source' => $source,
            'request_dest' => $dest,
            'request_remark' => $remark,
            'request_insert_time' => time(),
            'request_insert_ip' => $_SERVER['REMODE_ADDR'],
            'request_pass' => $pass,
        );
        if(in_array($pass,array(1,2))){
            $data['request_pass_time'] = time();
            $data['request_pass_ip'] = $_SERVER['REMODE_ADDR'];
        }
        return $this->saveData(1,$data);
    }

    /**
     * 更新状态
     * @param $requestId
     * @return array
     */
    function updatePass($requestId,$val = 1)
    {
        if(empty($requestId)||empty($val)){
            return false;
        }
        $data = array(
            'request_pass' => $val,
            'request_pass_time' => time(),
            'request_pass_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array(
            "request_id" => $requestId
        );
        return $this->saveData(2,$data,$condition);
    }

    /**
     * @param $source
     * @param $dest
     */
    function getRowsBySourceDest($source,$dest)
    {
        $condition = array(
            "request_source" => $source,
            "request_dest" => $dest,
        );
        return $this->getRowsByCondition(null,$condition);
    }

    function getRowsByIdDest($refresh = null,$id,$dest)
    {
        $condition = array("request_id" => $id,'request_dest' => $dest);
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
        $condition = array("request_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}
