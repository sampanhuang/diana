<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:53
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_MemberRole extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_MemberRole();
    }


    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsById($refresh = null,$id)
    {
        $condition = array("role_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}