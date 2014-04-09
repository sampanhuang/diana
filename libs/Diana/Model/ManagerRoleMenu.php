<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-18
 * Time: 下午11:53
 * To change this template use File | Settings | File Templates.
 */
class Diana_Model_ManagerRoleMenu extends Diana_Model_Abstract
{
    function __construct()
    {
        parent::__construct();
        $this->dt = new Diana_Model_DbTable_ManagerRoleMenu();
    }

    /**
     * 更新角色的菜单信息
     * @param $menuId 菜单ID array(菜单1ID,菜单2ID,菜单3ID)
     * @param $roleId 角色ID
     * @return array|bool
     */
    function updateMenuByRole($menuId,$roleId)
    {
        //参数判断
        if (empty($menuId)||empty($roleId)) {
            return false;
        }
        if ((!is_array($menuId))||(!is_numeric($roleId))) {
            return false;
        }
        //删除原来的角色权限
        $this->delByRole($roleId);
        foreach ($menuId as $value){
            if (is_numeric($value)) {
                $data = array('role_id' => $roleId,'menu_id' => $value);
                $this->saveData(1,$data);
            }
        }
        return $this->getRowsByRole(true,$roleId);
    }

    /**
     * 删除ID所有的菜单ID
     * @param $roleId 角色ID
     * @return int 删除数
     */
    function delByRole($roleId)
    {
        $condition = array("role_id" => $roleId);
        return $this->delData($condition);
    }

    /**
     * 通过角色ID得到菜单ID
     * @param null $refresh 是否刷新
     * @param $roleId  角色ID
     * @return array( roleId => array(menuId,menuId))
     */
    function getMenuByRole($refresh = null,$roleId)
    {
        $menuId = array();
        if(!$rows = $this->getRowsByRole($refresh,$roleId)){
            return false;
        }
        foreach($rows as $row){
            $menuId[$row['role_id']][] = $row['menu_id'];
        }
        return $menuId;
    }

    /**
     * 通过流水号ID获取多条纪录
     *
     * @param int|string|array $id
     * @return array
     */
    function getRowsByRole($refresh = null,$roleId)
    {
        $condition = array("role_id" => $roleId);
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
        $condition = array("role_id" => $id);
        return $this->getRowsByCondition($refresh,$condition);
    }

}