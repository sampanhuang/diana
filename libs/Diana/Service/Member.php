<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-28
 * Time: 上午1:12
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_Member extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $key 关键字
     * @param $page 当前页
     * @param $pagesize 每页的纪录数
     */
    function pageByCondition($page = 1,$pagesize = 1,$condition = array())
    {
        $offset = ($page - 1)*$pagesize;
        if($offset < 0){
            $offset = 0;
        }
        $modelMember = new Diana_Model_Member();
        if($countMember = $modelMember->getCountByCondition(null,$condition)){
            $rowsMember = $modelMember->getRowsByCondition(null,$condition,null,$pagesize,$offset);
        }
        return array('total' => $countMember,'rows' => $rowsMember);
    }

    function detailById($memberId)
    {
        if(empty($memberId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        if(!is_numeric($memberId)){
            $this->setMsgs('参数类型错误');
            return false;
        }
        //获取网站信息
        $modelMember = new Diana_Model_Member();
        if(!$rowsMember = $modelMember->getRowsById(null,$memberId)){
            return false;
        }
        $rowMember = $rowsMember[0];
        return $rowMember;
    }

    /**
 * 通过email获取管理员帐号
 *
 * @param string $email
 * @return array
 */
    function getMemberByEmail($email)
    {
        //参数问题
        if ((empty($email))||(!is_scalar($email))) {
            $this->setMsgs("Invalid Param");
            return false;
        }
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->getRowsByEmail(null,$email)) {
            $this->setMsgs("无效的 email - {$email}");
            return false;
        }
        return $this->getMemberById($rowsMember[0]['member_id']);
    }

    /**
     * 通过名称获取管理员帐号
     *
     * @param string $email
     * @return array
     */
    function getMemberByName($name)
    {
        //参数问题
        if ((empty($name))||(!is_scalar($name))) {
            $this->setMsgs("Invalid Param");
            return false;
        }
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->getRowsByName(null,$name)) {
            $this->setMsgs("无效的 name - {$name}");
            return false;
        }
        return $this->getMemberById($rowsMember[0]['member_id']);
    }

    /**
     * 获取会员明细
     * 会员主表，扩展表，权限表，权限资源表过滤
     *
     * @param int $id
     * @return array
     */
    function getMemberById($id)
    {

        //参数问题
        if ((empty($id))||(!is_numeric($id))) {
            $this->setMsgs("Invalid Param");
            return false;
        }
        //获取当前ID的纪录
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->getRowsById(null,$id)) {
            $this->setMsgs("id - {$id}");
            return false;
        }
        $rowMember = $rowsMember[0];
        $memberId = $rowMember["member_id"];//管理员ID
        $memberRoleId = $rowMember["member_roleId"];//权限组ID
        //关于锁定的描述
        $memberLockTime = $rowMember['member_lock_time']?$rowMember['member_lock_time']:$rowMember['member_register_time'];
        if ($checkLockTime = $this->checkLockTime($memberLockTime)) {
            $rowMember['member_lock_stat'] = $checkLockTime['stat'];
            $rowMember['member_lock_second'] = $checkLockTime['second'];
        }
        //合并权限组
        if ($rowRole = $this->getRoleById($memberRoleId)) {
            $rowMember = array_merge($rowMember,$rowRole);
        }
        return $rowMember;
    }

    function getRoleById($roleId)
    {
        //参数判断
        if ((empty($roleId))||(!is_numeric($roleId))) {
            $this->setMsgs('$roleId 不能为空且类型必须为数字类型！');
            return false;
        }
        //读取类型数据
        $modelMemberRole = new Diana_Model_MemberRole();
        if (!$rowsRole = $modelMemberRole->getRowsById(null,$roleId)) {
            $this->setMsgs('$roleId 为无效的参数！');
            return false;
        }
        $rowRole = $rowsRole[0];//角色信息
        //关于锁定的描述
        $rowRole['role_lock_time'] = $rowRole['role_lock_time']?$rowRole['role_lock_time']:$rowRole['role_create_time'];
        if ($checkLockTime = $this->checkLockTime($rowRole['role_lock_time'])) {
            $rowRole['role_lock_stat'] = $checkLockTime['stat'];
            $rowRole['role_lock_second'] = $checkLockTime['second'];
        }
        //获取角色的权限
        $serviceMemberMenu = new Diana_Service_MemberMenu();
        if($rowRole['role_admin'] == 1){
            $rowRole['role_menuTree'] = $serviceMemberMenu->makeTree();
        }elseif(!empty($rowRole['role_menuId'])){
            $rowRole['role_menuTree'] = $serviceMemberMenu->makeTreeByIds($rowRole['role_menuId']);
        }
        if(!empty($rowRole['role_menuTree'])){
            $rowRole['role_menu'] = $serviceMemberMenu->makeMenusByTree($rowRole['role_menuTree']);
        }
        return $rowRole;
    }

    /**
     * 确认锁定的超时时间
     *
     */
    function checkLockTime($time)
    {
        $return = array();
        if ($time < DIANA_TIMESTAMP_START) {//未锁定
            $return['stat'] = 1;
            $return['second'] = DIANA_TIMESTAMP_START - $time;
        }else{//已经锁定
            $return['stat'] = 0;
            $return['second'] = $time - DIANA_TIMESTAMP_START;
        }
        return $return;
    }
}