<?php
/**
 * 管理员帐号
 *
 */
class Admin_Service_Manager extends Admin_Service_Abstract
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
        $modelManager = new Diana_Model_Manager();
        if($countManager = $modelManager->getCountByCondition(null,$condition)){
            if($rowsManager = $modelManager->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $serviceManagerRole = new Admin_Service_ManagerRole();
                if($optionsRole = $serviceManagerRole->makeOptions()){
                    foreach($rowsManager as &$rowManager){
                        $rowManager['manager_roleName'] = $optionsRole[$rowManager['manager_roleId']];
                    }
                }
            }
        }
        $pageCount = ceil($countManager/$pagesize);
        return $modelManager->getRowsByCondition(null,$condition,null,$page,$pagesize);
    }
		
	/**
	 * 通过email获取管理员帐号
	 *
	 * @param string $email 
	 * @return array
	 */
	function getManagerByEmail($email)
	{
		//参数问题
		if ((empty($email))||(!is_scalar($email))) {
			$this->setMsgs("Invalid Param");
			return false;
		}
		$modelManager = new Diana_Model_Manager();
		if (!$rowsManager = $modelManager->getRowsByEmail(null,$email)) {
			$this->setMsgs("无效的 email - {$email}");
			return false;
		}
		return $this->getManagerById($rowsManager[0]['manager_id']);
	}

    /**
     * 通过name获取管理员帐号
     *
     * @param string $email
     * @return array
     */
    function getManagerByName($name)
    {
        //参数问题
        if ((empty($name))||(!is_scalar($name))) {
            $this->setMsgs("Invalid Param");
            return false;
        }
        $modelManager = new Diana_Model_Manager();
        if (!$rowsManager = $modelManager->getRowsByName(null,$name)) {
            $this->setMsgs("无效的 name - {$name}");
            return false;
        }
        return $this->getManagerById($rowsManager[0]['manager_id']);
    }
	
	/**
	 * 获取管理员明细
	 * 会员主表，扩展表，权限表，权限资源表过滤
	 * 
	 * @param int $id
	 * @return array
	 */
	function getManagerById($id)
	{
		
		//参数问题
		if ((empty($id))||(!is_numeric($id))) {
			$this->setMsgs("Invalid Param");
			return false;
		}
		//获取当前ID的纪录
		$modelManager = new Diana_Model_Manager();
		if (!$rowsManager = $modelManager->getRowsById(null,$id)) {
			$this->setMsgs("id - {$id}");
			return false;
		}		
		$rowManager = $rowsManager[0];
		$managerId = $rowManager["manager_id"];//管理员ID
		$managerRoleId = $rowManager["manager_roleId"];//权限组ID
		//关于锁定的描述
		$managerLockTime = $rowManager['manager_lock_time']?$rowManager['manager_lock_time']:$rowManager['manager_create_time'];
		if ($checkLockTime = $this->checkLockTime($managerLockTime)) {
			$rowManager['manager_lock_stat'] = $checkLockTime['stat'];
			$rowManager['manager_lock_second'] = $checkLockTime['second'];
		}
		//合并权限组
		if ($rowRole = $this->getRoleById($managerRoleId)) {
			$rowManager = array_merge($rowManager,$rowRole);
		}
		return $rowManager;
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
		$modelManagerRole = new Diana_Model_ManagerRole();
		if (!$rowsRole = $modelManagerRole->getRowsById(null,$roleId)) {
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
        $serviceManagerMenu = new Admin_Service_ManagerMenu();
        if($rowRole['role_admin'] == 1){
            $rowRole['role_menuTree'] = $serviceManagerMenu->makeTree();
        }elseif(!empty($rowRole['role_menuId'])){
            $rowRole['role_menuTree'] = $serviceManagerMenu->makeTreeByIds($rowRole['role_menuId']);
        }
        if(!empty($rowRole['role_menuTree'])){
            $rowRole['role_menu'] = $serviceManagerMenu->makeMenusByTree($rowRole['role_menuTree']);
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