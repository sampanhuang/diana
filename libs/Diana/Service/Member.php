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

    /**
     * 通过多种渠道获取会员详细信息
     * @param $column 字段，id,name,email
     * @param $key 值
     */
    function getDetail($column,$key)
    {
        if ((empty($column))||(!is_scalar($column))) {
            $this->setMsgs("Invalid Param - Column");
            return false;
        }
        if ((empty($key))||(!is_scalar($key))) {
            $this->setMsgs("Invalid Param - Key");
            return false;
        }
        if($column == 'member_id'){
            $detailMember = $this->getMemberById($key);
        }elseif($column == 'member_name'){
            $detailMember = $this->getMemberByName($key);
        }elseif($column == 'member_email'){
            $detailMember = $this->getMemberByEmail($key);
        }else{
            $this->setMsgs("Invalid Param - column ".$column);
            return false;
        }
        return $detailMember;
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

    /**
     * 通过ID获取权限组
     * @param $roleId
     * @return bool
     */
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
            $rowRole['role_menu'] = $serviceMemberMenu->makeMenusByTreeForEasyui($rowRole['role_menuTree']);
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



    /**
     * 更新帐号或是邮箱
     * @param $id ID
     * @param $input 外部输入
     * @param $type 类型 1帐号，2邮箱
     */
    function updateNameEmail($id,$input,$type,$password)
    {
        //确认外部参数是否正确
        if (empty($id)||empty($input)||empty($type)||empty($password)) {
            $this->setMsgs('各项参数不能为空');
            return false;
        }
        if ((!is_numeric($id))||(!is_string($input))||(!is_string($type))||(!is_string($password))) {
            $this->setMsgs('各项参数必须为标量');
            return false;
        }
        //判断用户ID是否正确
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->getRowsById(null,$id)) {
            $this->setMsgs('错误的用户ID');
            return false;
        }
        $memberName = $rowsMember[0]['member_name'];
        $memberEmail = $rowsMember[0]['member_email'];
        $memberPasswd = $rowsMember[0]['member_passwd'];
        $logType = 0;//日志类型
        //判断密码是否正确
        if($memberPasswd <> $password){
            $this->setMsgs('密码输入错误');
            return false;
        }
        $logRemark = array('old'=>'','new' => $input);//日志备注
        if($type == 'name'){//帐号判断
            if(strtolower($input) == strtolower($memberName)){
                $this->setMsgs('未进行任何帐号变更操作');
                return false;
            }
            if(!$this->isExistsWithName($input,$id)){
                $this->setMsgs('帐号'.$input.'已经被使用');
                return false;
            }
            $logType =130;
            $logRemark['old'] = $memberName;
            $dataUpdate = array('member_name' => $input);
        }elseif($type == 'email'){//邮箱判官
            if(strtolower($input) == strtolower($memberEmail)){
                $this->setMsgs('未进行任何邮箱变更操作');
                return false;
            }
            if(!$this->isExistsWithEmail($input,$id)){
                $this->setMsgs('邮箱'.$input.'已经被使用');
                return false;
            }
            $logType =120;
            $logRemark['old'] = $memberEmail;
            $dataUpdate = array('member_email' => $input);

        }else{
            $this->setMsgs('无效的参数Type');
            return false;
        }
        //更新数据
        if(!$modelMember->saveData(2,$dataUpdate,array('member_id' => $id))){
            $this->setMsgs('数据保存失败!');
            return false;
        }
        //写入新session
        if (!$detailMember = $this->getMemberById($id)) {
            $this->setMsgs("错误的用户ID");
            return false;
        }
        $serviceDoorkeeper = new Client_Service_Doorkeeper();
        $serviceDoorkeeper->writeSession($detailMember);
        //更新日志
        $serviceMemberLog = new Diana_Service_MemberLog();
        if (!$serviceMemberLog->write($logType,$id,$detailMember['member_email'],$detailMember['member_name'],$logRemark)) {
            $this->setMsgs('当前用户【'.$detailMember['member_email'].'】密码变更日志写入失败');
            //return false;//日志更新失败不鸟他
        }
        return $detailMember;
    }

    /**
     * 判断当前帐号是否有重复的
     * 如果$id不为空，那么肯定是重命名的
     * 如果$id为空，那么肯定是创建新的
     * @param string $label 帐号
     * @param int $id $id ID
     * @return bool 为真则当前帐号可用，为假则不可用
     */
    function isExistsWithName($name,$id = null)
    {
        //参数不能为空
        if (empty($name)) {
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelMember = new Diana_Model_Member();
        if ($rows = $modelMember->getRowsByName(true,$name)) {
            if (empty($id)) {
                return false;
            }else{
                if ($rows[0]['member_id'] <> $id) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 判断当前邮箱是否有重复的
     * 如果$id不为空，那么肯定是重命名的
     * 如果$id为空，那么肯定是创建新的
     * @param string $label 邮箱
     * @param int $id $id ID
     * @return bool 为真则当前邮箱可用，为假则不可用
     */
    function isExistsWithEmail($email,$id = null)
    {
        //参数不能为空
        if (empty($email)) {
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelMember = new Diana_Model_Member();
        if ($rows = $modelMember->getRowsByEmail(true,$email)) {
            if (empty($id)) {
                return false;
            }else{
                if ($rows[0]['member_id'] <> $id) {
                    return false;
                }
            }
        }
        return true;
    }
}