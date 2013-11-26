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

    /**
     * 生成分页所需数据
     * @param int $page 当前页
     * @param int $pagesize 每页纪录数
     * @param $condition 查询条件
     */
    function makeDataGrid($page = 1,$pagesize = 20,$condition)
    {
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelManagerRole = new Diana_Model_ManagerRole();
        $dataGrid['total']  = $modelManagerRole->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            if($rows = $modelManagerRole->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                foreach($rows as &$row){
                    if ($row['role_lock_time'] < DIANA_TIMESTAMP_START) {//未锁定
                        $row['role_lock_stat'] = 0;
                        $row['role_lock_second'] = DIANA_TIMESTAMP_START - $row['role_lock_time'];
                    }else{//已经锁定
                        $row['role_lock_stat'] = 1;
                        $row['role_lock_second'] = $row['role_lock_time'] - VI_TIMESTAMP_START;
                    }
                }
                $dataGrid['rows'] = $rows;
            }
        }
        return $dataGrid;
    }

    /**
     * 过滤表单查询
     *
     * @param array $post 提交的表单数据
     */
    function filterFormSearch($post)
    {
        $exp = array(
            'role_name' => 1,
            'role_lock_state' => 1,
            'role_admin_state' => 1,
        );
        return array_filter(array_intersect_key($post,$exp));
    }

    /**
     * 添加权限组信息
     *
     * @param array $data
     * @param string $email 管理员邮箱
     */
    function create($data,$email)
    {
        if(empty($data)||empty($email)){
            $this->setMsgs('各项参数不能为空！');
            return false;
        }
        if(empty($data['input_role_name'])){
            $this->setMsgs('角色名不能为空！');
            return false;
        }
        if (!$this->isExistsWithName($data['input_role_name'])) {
            $this->setMsgs('角色名重复，请更换其他角色名！');
            return false;
        }
        if (!empty($data['input_role_lock_time'])) {
            $data['input_role_lock_time'] = strtotime($data['input_role_lock_time']);
        }
        //保存角色数据
        $tmpData = array(
            'role_name' => $data['input_role_name'],
            'role_admin' => $data['radio_role_admin'],
            'role_lock_time' => intval($data['input_role_lock_time']),
            'role_insert_time' => time(),
            'role_insert_man' => $email,
            'role_insert_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $modelManagerRole = new Diana_Model_ManagerRole();
        if (!$rowsManagerRole = $modelManagerRole->saveData(1,$tmpData)) {
            $this->setMsgs('角色数据保存失败');
            return false;
        }
        $roleId = $rowsManagerRole[0]['role_id'];
        //保存角色权限数据
        $dataManagerRolePower = array();
        $modelManagerRoleMenu = new Diana_Model_ManagerRoleMenu();
        if (!$rowsManagerROlePower = $modelManagerRoleMenu->updateMenuByRole($data['checkbox_menu_id'],$roleId)) {
            $this->setMsgs('角色权限数据保存失败');
            return false;
        }
        //更新缓存
        $serviceManager = new Admin_Service_Manager();
        return $serviceManager->getRoleById($roleId);
    }

    /**
     * 修改权限组信息
     *
     * @param array $data 保存内容
     * @param int $roleId 权限组ID
     * @param array $email 操作人
     */
    function modify($data,$roleId,$email)
    {
        if (!$this->isExistsWithName($data['input_role_name'],$roleId)) {
            $this->setMsgs('角色名重复，请更换其他角色名！');
            return false;
        }
        if (!empty($data['input_role_lock_time'])) {
            $data['input_role_lock_time'] = strtotime($data['input_role_lock_time']);
        }
        //保存角色数据
        $tmpData = array(
            'role_name' => $data['input_role_name'],
            'role_admin' => $data['radio_role_admin'],
            'role_lock_time' => intval($data['input_role_lock_time']),
            'role_update_time' => time(),
            'role_update_man' => $email,
            'role_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array("role_id" => $roleId);
        $modelManagerRole = new Diana_Model_ManagerRole();
        if (!$rowsManagerRole = $modelManagerRole->saveData(2,$tmpData,$condition)) {
            $this->setMsgs('角色数据保存失败');
            return false;
        }
        //保存角色权限数据
        $dataManagerRolePower = array();
        $modelManagerRoleMenu = new Diana_Model_ManagerRoleMenu();
        if (!$rowsManagerROlePower = $modelManagerRoleMenu->updateMenuByRole($data['checkbox_menu_id'],$roleId)) {
            $this->setMsgs('角色权限数据保存失败');
            return false;
        }
        //更新缓存
        $serviceManager = new Admin_Service_Manager();
        return $serviceManager->getRoleById($roleId);
    }


    /**
     * 删除
     * @param $roleId
     * @return bool|int
     */
    function deleteById($roleId)
    {
        if (empty($roleId)) {
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelManagerRole = new Diana_Model_ManagerRole();
        if(!$rowsAffected = $modelManagerRole->delData(array('role_id' => $roleId))){
            return false;
        }
        $modelManagerRoleMenu = new Diana_Model_ManagerRoleMenu();
        $modelManagerRoleMenu->delData(array('role_id' => $roleId));
        return $rowsAffected;
    }

    /**
     * 通过ID对用户角色进行锁定及解锁操作
     *
     * @param int|string|array $roleId 角色ID
     * @param string $lockTime 锁定时间  如果为空就是解锁
     */
    function lockById($roleId,$lockTime = null)
    {
        if (empty($roleId)) {
            $this->setMsgs('角色ID不能为空');
            return false;
        }
        if (empty($lockTime)) {
            $lockTime = time() - 1;
        }else{
            $lockTime = strtotime($lockTime);
        }
        $tmpData = array('role_lock_time' => $lockTime);
        $condition = array("role_id" => $roleId);
        $modelManagerRole = new Diana_Model_ManagerRole();
        if (!$rowsRole = $modelManagerRole->saveData(2,$tmpData,$condition)) {
            $this->setMsgs('角色数据保存失败');
            return false;
        }
        return $rowsRole;
    }

    /**
     * 判断当前角色名是否有重复的
     * 如果$id不为空，那么肯定是重命名的
     * 如果$id为空，那么肯定是创建新的
     * @param string $label 角色名
     * @param int $id $id 角色ID
     * @return bool 为真则当前角色名可用，为假则不可用
     */
    function isExistsWithName($name,$id = null)
    {
        //参数不能为空
        if (empty($name)) {
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelManagerRole = new Diana_Model_ManagerRole();
        if ($rows = $modelManagerRole->getRowsByName(true,$name)) {
            if (empty($id)) {
                return false;
            }else{
                if ($rows[0]['role_id'] <> $id) {
                    return false;
                }
            }
        }
        return true;
    }



    function makeOptions($condition = array())
    {
        $options = array();
        $modelManagerRole = new Diana_Model_ManagerRole();
        if($rowsManagerRole = $modelManagerRole->getRowsByCondition()){
            foreach($rowsManagerRole as $rowManagerRole){            	
                $tmpRoleId = $rowManagerRole['role_id'];
                $tmpRoleName = $rowManagerRole['role_name'];
                if($rowManagerRole['role_lock_time'] > time()){
            		$tmpRoleName .= '&nbsp;<span style="color:red">[lock]</span>';
            	}
                $options[$tmpRoleId] = $tmpRoleName;
            }
        }
        return $options;
    }
}
