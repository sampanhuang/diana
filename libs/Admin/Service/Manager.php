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
     * 生成树状结构
     */
    function makeTree()
    {
        $tree = array();
        //获取角色数据
        $modelManagerRole = new Diana_Model_ManagerRole();
        if(!$rowsManagerRole = $modelManagerRole->getRowsByCondition()){
            return false;
        }
        //获取管理成员数据
        $modelManager = new Diana_Model_Manager();
        if(!$rowsManager = $modelManager->getRowsByCondition()){
            return false;
        }
        foreach($rowsManagerRole as $rowManagerRole){
            if($rowsManagerRole['role_lock_time'] < time()){
                $tmpRoleId = $rowManagerRole['role_id'];
                $tmpRowManagerRole = array();
                $tmpRowManager = array();
                foreach($rowsManager as $rowManager){
                    if(($rowManager['manager_lock_time'] < time())&&($rowManager['manager_roleId'] == $rowManagerRole['role_id'])){
                        $tmpRowManager[] = array(
                            'id' => $rowManager['manager_id'],
                            'text' => $rowManager['manager_name'],
                        );
                    }
                }
                if(!empty($tmpRowManager)){
                    $tree[] = array(
                        'id' => $rowManagerRole['role_id'],
                        'text' => $rowManagerRole['role_name'],
                        'children' => $tmpRowManager,
                    );
                }
            }
        }
        return $tree;
    }

    /**
     * 生成datagrid所需数据
     * @param int $page 当前页
     * @param int $pagesize 每页纪录数
     * @param $condition 条件
     * @return array
     */
    function makeDataGrid($page = 1,$pagesize = 1,$condition)
    {
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelManager = new Diana_Model_Manager();
        $dataGrid['total'] = $modelManager->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ( $page - 1 ) * $pagesize;
            if($offset < 0){$offset = 0;}

            if($dataGrid['rows'] = $modelManager->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $serviceManagerRole = new Admin_Service_ManagerRole();
                if($optionsRole = $serviceManagerRole->makeOptions()){
                    foreach($dataGrid['rows'] as &$rowManager){
                    	if ($rowManager['manager_lock_time'] < DIANA_TIMESTAMP_START) {//未锁定
                            $rowManager['manager_lock_stat'] = 0;
                            $rowManager['manager_lock_second'] = DIANA_TIMESTAMP_START - $rowManager['role_lock_time'];
	                    }else{//已经锁定
                            $rowManager['manager_lock_stat'] = 1;
                            $rowManager['manager_lock_second'] = $rowManager['manager_lock_time'] - VI_TIMESTAMP_START;
	                    }
                        $rowManager['manager_roleName'] = $optionsRole[$rowManager['manager_roleId']];
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
            'manager_name' => 1,
            'manager_email' => 1,
            'manager_roleId' => 1,
            'manager_lock_time' => 1,
        );
        return array_filter(array_intersect_key($post,$exp));

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
        $modelManager = new Diana_Model_Manager();
        if (!$rowsManager = $modelManager->getRowsById(null,$id)) {
            $this->setMsgs('错误的用户ID');
            return false;
        }
        $managerName = $rowsManager[0]['manager_name'];
        $managerEmail = $rowsManager[0]['manager_email'];
        $logType = 0;//日志类型
        $logRemark = array('old'=>'','new' => $input);//日志备注
        if($type == 'name'){//帐号判断
            if(strtolower($input) == strtolower($managerName)){
                $this->setMsgs('未进行任何帐号变更操作');
                return false;
            }
            if(!$this->isExistsWithName($input,$id)){
                $this->setMsgs('帐号'.$input.'已经被使用');
                return false;
            }
            $logType =130;
            $logRemark['old'] = $managerName;
            $dataUpdate = array('manager_name' => $input);
        }elseif($type == 'email'){//邮箱判官
            if(strtolower($input) == strtolower($managerEmail)){
                $this->setMsgs('未进行任何邮箱变更操作');
                return false;
            }
            if(!$this->isExistsWithEmail($input,$id)){
                $this->setMsgs('邮箱'.$input.'已经被使用');
                return false;
            }
            $logType =120;
            $logRemark['old'] = $managerEmail;
            $dataUpdate = array('manager_email' => $input);

        }else{
            $this->setMsgs('无效的参数Type');
            return false;
        }
        //更新数据
        if(!$modelManager->saveData(2,$dataUpdate,array('manager_id' => $id))){
            $this->setMsgs('数据保存失败!');
            return false;
        }
        //写入新session
        if (!$detailManager = $this->getManagerById($id)) {
            $this->setMsgs("错误的用户ID");
            return false;
        }
        $serviceDoorkeeper = new Admin_Service_Doorkeeper();
        $serviceDoorkeeper->writeSession($detailManager);
        //更新日志
        $serviceManagerLog = new Admin_Service_ManagerLog();
        if (!$serviceManagerLog->write($logType,$id,$detailManager['manager_email'],$detailManager['manager_name'],$logRemark)) {
            $this->setMsgs('当前用户【'.$detailManager['manager_email'].'】密码变更日志写入失败');
            //return false;//日志更新失败不鸟他
        }
        return $detailManager;
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
        $modelManager = new Diana_Model_Manager();
        if ($rows = $modelManager->getRowsByName(true,$name)) {
            if (empty($id)) {
                return false;
            }else{
                if ($rows[0]['manager_id'] <> $id) {
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
        $modelManager = new Diana_Model_Manager();
        if ($rows = $modelManager->getRowsByEmail(true,$email)) {
            if (empty($id)) {
                return false;
            }else{
                if ($rows[0]['manager_id'] <> $id) {
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
            $rowRole['role_menu'] = $serviceManagerMenu->makeMenusByTreeForEasyui($rowRole['role_menuTree']);
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