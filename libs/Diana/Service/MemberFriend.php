<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-1-7
 * Time: 下午9:14
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_MemberFriend extends Client_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 处理请求
     * @param $input
     * @return bool|int
     */
    function associateFromRequest($rowsMemberFriendRequest)
    {
        if(empty($rowsMemberFriendRequest)){
            $this->setMsgs('无效的参数!');
            return false;
        }
        $successRequest = 0;
        $failRequestId = array();
        $modelMemberFriend = new Diana_Model_MemberFriend();
        foreach($rowsMemberFriendRequest as $rowMemberFriendRequest){
            $tmpSuccessCount = 0;
            $tmpRequestId = $rowMemberFriendRequest['request_id'];
            $master = $rowMemberFriendRequest['request_dest'];
            $guest = $rowMemberFriendRequest['request_source'];
            $requestTime = $rowMemberFriendRequest['request_insert_time'];
            $requestIp = $rowMemberFriendRequest['request_insert_ip'];
            //不允许重复添加
            if(!$modelMemberFriend->getRowsByMasterGuest(null,$master,$guest)){
                if($tmpRows = $modelMemberFriend->insertByMasterGuest($master,$guest,$requestTime,$requestIp)){
                    $tmpSuccessCount++;
                }else{
                    $failRequestId[] = $tmpRequestId;
                }
            }
            //不允许重复添加
            if(!$modelMemberFriend->getRowsByMasterGuest(null,$guest,$master)){
                if($tmpRowsReverse = $modelMemberFriend->insertByMasterGuest($guest,$master,$requestTime,$requestIp)){
                    $tmpSuccessCount++;
                }else{
                    $failRequestId[] = $tmpRequestId;
                }
            }
            if($tmpSuccessCount >=1 ){
                $successRequest++;
            }
        }
        //判断成功了几条
        if( $successRequest < 1){
            $this->setMsgs('你们都已经互为好友！');
            return false;
        }
        $this->setMsgs('成功处理'.$successRequest.'条请求！');
        return $successRequest;
    }



    /**
     * 解除好友关系
     * @param $input
     * @return bool|int
     */
    function dissolution($input)
    {
        //参数不能为空
        if(empty($input['friend_id'])){
            $this->setMsgs('参数不能为空！- friend');
            return false;
        }
        $friendId = $input['friend_id'];
        //获取纪录
        $modelMemberFriend = new Diana_Model_MemberFriend();
        if(!$rowsMemberFriend = $modelMemberFriend->getRowsById(null,$friendId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //确认这些好友关系是自己的
        $passSafe = 0;
        foreach($rowsMemberFriend as &$rowMemberFriend){
            if($rowMemberFriend['friend_master_memberId'] <> $this->sessionMember['id']){
                unset($rowMemberFriend);
                $passSafe++;
            }
        }
        if($passSafe == count($rowsMemberFriend)){
            $this->setMsgs('不允许操作不属于你的好友关系');
            return false;
        }
        //提取对方的ID
        $masterMemberId = $this->sessionMember['id'];
        $guestMemberId = array();
        foreach($rowsMemberFriend as $tmpRowMemberFriend){
            $guestMemberId = $tmpRowMemberFriend['friend_guest_memberId'];
        }
        if(empty($guestMemberId)){
            $this->setMsgs('缺少关系解除条件 - guestMemberId');
            return false;
        }
        //开始解除关系
        if(!$rowsAffected = $modelMemberFriend->deleteByMasterGuest($masterMemberId,$guestMemberId)){
            $this->setMsgs('删除失败');
            return false;
        }
        if($input['reverse']){//如果也要删除他们好友列表中关于本人的纪录
            if(!$rowsAffectedReverse = $modelMemberFriend->deleteByMasterGuest($guestMemberId,$masterMemberId)){
                $this->setMsgs('逆向删除失败');
                return false;
            }
        }
        $this->setMsgs('关系解除成功！');
        return $rowsAffected;
    }

    /**
     * 生成好友树装结构
     * @param $input
     */
    function makeTree($input = null)
    {
        $tree = array();
        $memberId = array();
        $modelMemberFriend = new Diana_Model_MemberFriend();
        if(!$rows = $modelMemberFriend->getRowsByCondition(null,array('friend_guest_memberId' => $this->sessionMember['id']))){
            return $tree;
        }

        foreach($rows as $row){
            $memberId[] = $row['friend_guest_memberId'];
        }
        $modelMember = new Diana_Model_Member();
        if(!$rowsMember = $modelMember->getRowsById(null,$memberId)){
            return $tree;
        }
        foreach($rowsMember as $rowMember){
            $tree[] = array(
                'id' => $rowMember['member_id'],
                //'text' => $rowMember['member_name'].'&lt;'.$rowMember['member_email'].'&gt;',
                'text' => $rowMember['member_name'],
            );
        }
        return $tree;
    }

    /**
     * 打印好友信息
     * @param $input
     * @return array
     */
    function makeDataGrid($input)
    {
        $page = $input['page']?$input['page']:1;
        $pageSize = $input['rows']?$input['rows']:DIANA_DATAGRID_PAGELIST_CLIENT;
        $format = array('friend_id','friend_master_memberId','friend_guest_memberId','friend_insert_time_min','friend_insert_time_max');
        $conditions = $this->filterColumns(array($input),$format);
        return $this->pageByCondition($page,$pageSize,$conditions[0]);
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
        $modelMemberFriend = new Diana_Model_MemberFriend();
        if($countMemberFriend = $modelMemberFriend->getCountByCondition(null,$condition)){
            $rowsMemberFriend = $modelMemberFriend->getRowsByCondition(null,$condition,null,$pagesize,$offset);
            $memberId = array();
            foreach($rowsMemberFriend as $rowMemberFriend){
                $memberId = $rowMemberFriend['friend_guest_memberId'];
            }
            $modelMember = new Diana_Model_Member();
            if($rowsMember = $modelMember->getRowsById(null,$memberId)){
                foreach($rowsMemberFriend as &$rowMemberFriend){
                    foreach($rowsMember as $rowMember){
                        if($rowMemberFriend['friend_guest_memberId'] == $rowMember['member_id']){
                            $rowMemberFriend['friend_guest_memberName'] = $rowMember['member_name'];
                            $rowMemberFriend['friend_guest_memberEmail'] = $rowMember['member_email'];
                        }
                    }
                }
            }
        }
        return array('total' => (int)$countMemberFriend,'rows' => (array)$rowsMemberFriend);
    }


}
