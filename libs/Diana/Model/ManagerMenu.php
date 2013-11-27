<?php
/**
 * 后台菜单
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:46
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_ManagerMenu extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerMenu();
    }

    function insert($data)
    {
        if(empty($data)){
            return false;
        }
        $data['menu_insert_time'] = $data['menu_update_time'] = time();
        $data['menu_insert_ip'] = $data['menu_update_ip'] = $_SERVER['REMODE_ADDR'];
        return $this->saveData(1,$data);
    }

    function update($data,$id)
    {
        if(empty($data)||empty($id)){
            return false;
        }
        $condition = array('menu_id' => $id);
        $data['menu_update_time'] = time();
        $data['menu_update_ip'] = $_SERVER['REMODE_ADDR'];
        return $this->saveData(2,$data,$condition);
    }

    /**
     * 通过父ID获取多条纪录
     * @param null $refresh 是否刷新
     * @param int $fatherId 父ID
     */
    function getRowsByFather($refresh = null,$fatherId = 0)
    {
        $condition = array("menu_fatherId" => $fatherId);
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
        $condition = array("menu_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

    function getRows($refresh = null)
    {
        return $this->getRowsByCondition($refresh);
    }

}
