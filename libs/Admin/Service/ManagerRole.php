<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-27
 * Time: 下午12:44
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_ManagerRole extends Admin_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    function pageByCondition($page = 1,$pagesize = 15 ,$condition)
    {
        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        $modelManagerRole = new Diana_Model_ManagerRole();
        return $modelManagerRole->getRowsByCondition(null,$condition,null,$pagesize,$offset);
    }

    /**
     * @param int $role 角色ID
     */
    function detailById($role)
    {

    }


    function makeOptions($condition = array())
    {
        $options = array();
        $modelManagerRole = new Diana_Model_ManagerRole();
        if($rowsManagerRole = $modelManagerRole->getRowsByCondition()){
            foreach($rowsManagerRole as $rowManagerRole){
                $tmpRoleId = $rowManagerRole['role_id'];
                $tmpRoleName = $rowManagerRole['role_name'];
                $options[$tmpRoleId] = $tmpRoleName;
            }
        }
        return $options;
    }
}
