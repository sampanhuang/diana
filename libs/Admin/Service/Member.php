<?php
/**
 * 管理员帐号
 *
 */
class Admin_Service_Member extends Admin_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 生成树状结构
     */
    function makeTree()
    {
        $tree = array();
        //获取角色数据
        $modelMemberRole = new Diana_Model_MemberRole();
        if(!$rowsMemberRole = $modelMemberRole->getRowsByCondition()){
            return false;
        }
        //获取管理成员数据
        $modelMember = new Diana_Model_Member();
        if(!$rowsMember = $modelMember->getRowsByCondition()){
            return false;
        }
        foreach($rowsMemberRole as $rowMemberRole){
            if($rowsMemberRole['role_lock_time'] < time()){
                $tmpRoleId = $rowMemberRole['role_id'];
                $tmpRowMemberRole = array();
                $tmpRowMember = array();
                foreach($rowsMember as $rowMember){
                    if(($rowMember['member_lock_time'] < time())&&($rowMember['member_roleId'] == $rowMemberRole['role_id'])){
                        $tmpRowMember[] = array(
                            'id' => $rowMember['member_id'],
                            'text' => $rowMember['member_name'],
                        );
                    }
                }
                if(!empty($tmpRowMember)){
                    $tree[] = array(
                        'id' => $rowMemberRole['role_id'],
                        'text' => $rowMemberRole['role_name'],
                        'children' => $tmpRowMember,
                    );
                }
            }
        }
        return $tree;
    }

    /**
     * 生成datagrid所需数据
     * @param $params 条件
     * @return array
     */
    function makeDataGrid($params)
    {
        $page = $params['page'];
        $pagesize = $params['rows'];
        $condition = $this->filterFormSearch($params);
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelMember = new Diana_Model_Member();
        $dataGrid['total'] = $modelMember->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ( $page - 1 ) * $pagesize;
            if($offset < 0){$offset = 0;}

            if($dataGrid['rows'] = $modelMember->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $serviceMemberRole = new Admin_Service_MemberRole();
                if($optionsRole = $serviceMemberRole->makeOptions()){
                    foreach($dataGrid['rows'] as &$rowMember){
                        if ($rowMember['member_lock_time'] < DIANA_TIMESTAMP_START) {//未锁定
                            $rowMember['member_lock_stat'] = 1;
                            $rowMember['member_lock_second'] = DIANA_TIMESTAMP_START - $rowMember['role_lock_time'];
                        }else{//已经锁定
                            $rowMember['member_lock_stat'] = 2;
                            $rowMember['member_lock_second'] = $rowMember['member_lock_time'] - VI_TIMESTAMP_START;
                        }
                        $rowMember['member_roleName'] = $optionsRole[$rowMember['member_roleId']];
                    }
                }
            }
        }
        return $dataGrid;
    }

    /**
     * 过滤查询条件
     * @param $post 查询数据
     * @return array 过滤后的查询数据
     */
    function filterFormSearch($post)
    {
        $exp = array(
            'member_name' => 1,
            'member_email' => 1,
            'member_roleId' => 1,
            'member_lock_time' => 1,
        );
        return array_filter(array_intersect_key($post,$exp));

    }

    /**
     * 删除
     * @param $params
     */
    function delete($params)
    {
        $memberId = $params['member_id'];
        return $this->deleteById($memberId);
    }

    /**
     * 锁定
     * @param $params
     */
    function lock($params)
    {
        $memberId = $params['member_id'];
        $lockTime = strtotime($params['member_lock_time']);
        return $this->setLockTimeById($memberId,$lockTime);

    }

    /**
     * 解锁
     * @param $params
     */
    function unlock($params)
    {
        $memberId = $params['member_id'];
        $lockTime = 0;
        return $this->setLockTimeById($memberId,$lockTime);
    }

    /**
     * 通过会员ID删除
     * @param $memberId 会员ID
     */
    function deleteById($memberId)
    {
        //确认会员ID正常
        if(!$rowsMember = $this->checkMemberId($memberId)){
            return false;
        }
        $modelMember = new Diana_Model_Member();
        $rowsAffected =  $modelMember->deleteById($memberId);
        if( $rowsAffected > 0){
            $this->setMsgs('成功删除'.$rowsAffected.'条纪录');
        }
        return $rowsAffected;
    }

    /**
     * 设置锁定时间
     * @param $memberId 会员ID
     * @param int $lockTime 锁定时间
     * @return array|bool 受影响的纪录
     */
    function setLockTimeById($memberId,$lockTime = 0)
    {
        //确认会员ID正常
        if(!$rowsMember = $this->checkMemberId($memberId)){
            return false;
        }
        $modelMember = new Diana_Model_Member();
        if(!$rowsMember = $modelMember->updateWithLock($memberId,$lockTime)){
            $this->setMsgs('更新失败');
            return false;
        }
        $this->setMsgs('成功更新'.count($rowsMember).'条纪录');
        return $rowsMember;
    }


    /**
     * 确认会员ID是否正确
     * @param $memberId 会员ID
     * @return array|bool 返回这些会员ID的纪录
     */
    function checkMemberId($memberId)
    {
        if(empty($memberId)){
            $this->setMsgs('会员ID不能为空');
            return false;
        }
        $modelMember = new Diana_Model_Member();
        if (!$rowsMember = $modelMember->getRowsById(null,$memberId)) {
            $this->setMsgs('无效的会员ID');
            return false;
        }
        return $rowsMember;
    }


    /**
     * 更新帐号或是邮箱
     * @param $id ID
     * @param $input 外部输入
     * @param $type 类型 1帐号，2邮箱
     */
    function updateNameEmail($id,$input,$type)
    {
        //确认外部参数是否正确
        if (empty($id)||empty($input)||empty($type)) {
            $this->setMsgs('各项参数不能为空');
            return false;
        }
        if ((!is_numeric($id))||(!is_string($input))||(!is_string($type))) {
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
        $logType = 0;//日志类型
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
        $serviceDoorkeeper = new Admin_Service_Doorkeeper();
        $serviceDoorkeeper->writeSession($detailMember);
        //更新日志
        $serviceMemberLog = new Admin_Service_MemberLog();
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
     * 通过name获取管理员帐号
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
     * 获取管理员明细
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
        $memberLockTime = $rowMember['member_lock_time']?$rowMember['member_lock_time']:$rowMember['member_create_time'];
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
     * 获取角色明细
     * 并更新资源信息
     * @param int $roleId 角色ID
     * @return array
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
        $serviceMemberMenu = new Admin_Service_MemberMenu();
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
            $return['stat'] = 0;
            $return['second'] = DIANA_TIMESTAMP_START - $time;
        }else{//已经锁定
            $return['stat'] = 1;
            $return['second'] = $time - DIANA_TIMESTAMP_START;
        }
        return $return;
    }
}