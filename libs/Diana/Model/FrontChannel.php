<?php
/**
 * 前端菜单
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-10-24
 * Time: 下午9:59
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_FrontChannel extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_FrontChannel();
    }


    /**
     * 通过父ID获取子ID纪录
     * @param null $refresh 是否刷新
     * @param $fatherId 父ID
     * @return array
     */
    function getRowsByFather($refresh = null,$fatherId)
    {
        $condition = array("channel_fatherId" => $fatherId);
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
        $condition = array("channel_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}
