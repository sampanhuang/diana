<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-27
 * Time: 下午12:44
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_MemberRole extends Admin_Service_Abstract
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
        $modelMemberRole = new Diana_Model_MemberRole();
        $dataGrid['total']  = $modelMemberRole->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            if($rows = $modelMemberRole->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
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
    function create($data)
    {
        if(empty($data)){
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
            'role_insert_manId' => $this->sessionMember['id'],
            'role_insert_manName' => $this->sessionMember['name'],
            'role_insert_manEmail' => $this->sessionMember['email'],
            'role_insert_ip' => $_SERVER['REMOTE_ADDR'],
            'role_update_time' => time(),
            'role_update_manId' => $this->sessionMember['id'],
            'role_update_manName' => $this->sessionMember['name'],
            'role_update_manEmail' => $this->sessionMember['email'],
            'role_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $modelMemberRole = new Diana_Model_MemberRole();
        if (!$rowsMemberRole = $modelMemberRole->saveData(1,$tmpData)) {
            $this->setMsgs('角色数据保存失败');
            return false;
        }
        $roleId = $rowsMemberRole[0]['role_id'];
        //保存角色权限数据
        $dataMemberRolePower = array();
        $modelMemberRoleMenu = new Diana_Model_MemberRoleMenu();
        if (!$rowsMemberROlePower = $modelMemberRoleMenu->updateMenuByRole($data['checkbox_menu_id'],$roleId)) {
            $this->setMsgs('角色权限数据保存失败');
            return false;
        }
        //更新缓存
        $serviceMember = new Admin_Service_Member();
        return $serviceMember->getRoleById($roleId);
    }

    /**
     * 修改权限组信息
     *
     * @param array $data 保存内容
     * @param int $roleId 权限组ID
     */
    function modify($data,$roleId)
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
            'role_update_manId' => $this->sessionMember['id'],
            'role_update_manName' => $this->sessionMember['name'],
            'role_update_manEmail' => $this->sessionMember['email'],
            'role_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array("role_id" => $roleId);
        $modelMemberRole = new Diana_Model_MemberRole();
        if (!$rowsMemberRole = $modelMemberRole->saveData(2,$tmpData,$condition)) {
            $this->setMsgs('角色数据保存失败');
            return false;
        }
        //保存角色权限数据
        $dataMemberRolePower = array();
        $modelMemberRoleMenu = new Diana_Model_MemberRoleMenu();
        if (!$rowsMemberROlePower = $modelMemberRoleMenu->updateMenuByRole($data['checkbox_menu_id'],$roleId)) {
            $this->setMsgs('角色权限数据保存失败');
            return false;
        }
        //更新缓存
        $serviceMember = new Admin_Service_Member();
        return $serviceMember->getRoleById($roleId);
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
        $modelMemberRole = new Diana_Model_MemberRole();
        if(!$rowsAffected = $modelMemberRole->delData(array('role_id' => $roleId))){
            return false;
        }
        $modelMemberRoleMenu = new Diana_Model_MemberRoleMenu();
        $modelMemberRoleMenu->delData(array('role_id' => $roleId));
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
        $modelMemberRole = new Diana_Model_MemberRole();
        if (!$rowsRole = $modelMemberRole->saveData(2,$tmpData,$condition)) {
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
        $modelMemberRole = new Diana_Model_MemberRole();
        if ($rows = $modelMemberRole->getRowsByName(true,$name)) {
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
        $modelMemberRole = new Diana_Model_MemberRole();
        if($rowsMemberRole = $modelMemberRole->getRowsByCondition()){
            foreach($rowsMemberRole as $rowMemberRole){
                $tmpRoleId = $rowMemberRole['role_id'];
                $tmpRoleName = $rowMemberRole['role_name'];
                if($rowMemberRole['role_lock_time'] > time()){
                    $tmpRoleName .= '&nbsp;<span style="color:red">[lock]</span>';
                }
                $options[$tmpRoleId] = $tmpRoleName;
            }
        }
        return $options;
    }
}
