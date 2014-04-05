<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:53
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_ManagerRole extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerRole();
    }

    function getRowsByName($refresh = null,$name)
    {
        $condition = array("role_name" => $name);
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        return $rows;
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
        if(!$rows = $this->getRowsByCondition($refresh,$condition)){
            return false;
        }
        $modelManagerRoleMenu = new Diana_Model_ManagerRoleMenu();
        if($rowsMenu = $modelManagerRoleMenu->getMenuByRole($refresh,$id)){
            foreach($rows as &$row){
                $row['role_menuId'] = $rowsMenu[$row['role_id']];
            }
        }
        return $rows;
    }

}