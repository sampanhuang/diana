<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 14-1-7
 * Time: 下午9:18
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_MemberFriendRequest extends Client_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 发送请求
     */
    function send($input)
    {
        if(empty($input['query_column'])||empty($input['query_key'])){
            $this->setMsgs('无效的查询');
            return false;
        }
        $queryColumn = $input['query_column'];
        $queryKey = $input['query_key'];
        //获取当前会员
        $serviceMember = new Diana_Service_Member();
        if(!$detailMember = $serviceMember->getDetail($queryColumn,$queryKey)){
            $this->setMsgs('无效的关键字 - '.$queryKey);
            return false;
        }
        //添加消息
        $requestSource = $input['request_source'];//发送人
        $requestDest = $detailMember['member_id'];//接收人
        $requestRemark = $input['request_remark'];
        if(empty($requestSource)){
            $this->setMsgs('发送人不能为空！');
            return false;
        }
        if(empty($requestDest)){
            $this->setMsgs('收件人不能为空！');
            return false;
        }
        if($requestSource == $requestDest){
            $this->setMsgs('发送人与收件人不能为同一人！');
            return false;
        }
        //判断是否已经是好友（一共四种情况，1、我是他好友，他也是我好友。2、我是他好友，他不是我好友。3、我不是他好友，他是我好友。4、我不是他好友，但他同样不是我好友，）
        //除了不是第一种情况，都可以发
        $modelMemberFriend = new Diana_Model_MemberFriend();
        $rowsMemberFriend_1 = $modelMemberFriend->getRowsByMasterGuest(null,$requestSource,$requestDest);
        $rowsMemberFriend_2 = $modelMemberFriend->getRowsByMasterGuest(null,$requestDest,$requestSource);
        if((!empty($rowsMemberFriend_1))&&(!empty($rowsMemberFriend_2))){
            $this->setMsgs('你们双方都已经是好友，不能再重复发送！');
            return false;
        }
        //判断是否已经发送过
        $modelMemberFriendRequest = new Diana_Model_MemberFriendRequest();
        if($rowsMemberFriendRequest = $modelMemberFriendRequest->getRowsBySourceDest($requestSource,$requestDest)){
            $this->setMsgs('无法重复发送！你已经在'.date('Y-m-d H:i:s',$rowsMemberFriendRequest[0]['request_insert_time']).'的时候发送过一次');
            return false;
        }

        if(!$modelMemberFriendRequest->insertBySourceDestRemark($requestSource,$requestDest,$requestRemark)){
            $this->setMsgs('好友请求发送失败！');
            return false;
        }
        $this->setMsgs('好友请求发送成功！');
        return true;
    }

    /**
     * 接受
     * @param $input
     */
    function accept($input)
    {
        //请求ID和收件人不能为空
        if(empty($input['request_id'])){
            $this->setMsgs('无效的参数');
            return false;
        }
        $requestId = $input['request_id'];
        //判断这些ID是否正确
        $modelMemberFriendRequest = new Diana_Model_MemberFriendRequest();
        if(!$rowsMemberFriendRequest = $modelMemberFriendRequest->getRowsById(null,$requestId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //将不属于自己的请求过滤掉，将已经处理过的过滤掉
        $passOut = 0;//收件已经被处理的请求数
        $passSafe = 0;//收件人不是自己的请求数
        foreach($rowsMemberFriendRequest as &$rowMemberFriendRequest){
            if(in_array($rowMemberFriendRequest['request_pass'],array(1,2))){
                $passOut++;
                unset($rowMemberFriendRequest);
            }
            if($rowMemberFriendRequest['request_dest'] <> $this->sessionMember['id']){
                $passSafe++;
                unset($rowMemberFriendRequest);
            }
        }
        $validRequestId = array();//正确的请求ID
        foreach($rowsMemberFriendRequest as $tmpRowMemberFriendRequest){
            $validRequestId[] = $tmpRowMemberFriendRequest['request_id'];
        }
        if($passOut == count($rowsMemberFriendRequest)){
            $this->setMsgs('不要操作已经处理过的请求');
            return false;
        }
        if($passSafe == count($rowsMemberFriendRequest)){
            $this->setMsgs('不要操作不属于你的请求');
            return false;
        }
        //添加好友
        $serviceMemberFriend = new Diana_Service_MemberFriend();
        if(!$successRequest = $serviceMemberFriend->associateFromRequest($rowsMemberFriendRequest)){
            $this->setMsgs($serviceMemberFriend->getMsgs());
            return false;
        }
        //更新状态或删除
        if($input['req_del'] == 1){
            if(!$this->delete(array('request_id' => $validRequestId,'request_dest' => $this->sessionMember['id']))){
                $this->setMsgs('请求删除失败');
                return false;
            }
        }else{
            if(!$modelMemberFriendRequest->updatePass($validRequestId,1)){
                $this->setMsgs('状态更新失败');
                return false;
            }
        }
        $this->setMsgs('成功处理'.$successRequest.'条请求!');
        return true;
    }

    /**
     * 拒绝
     * @param $input
     */
    function reject($input)
    {
        //请求ID和收件人不能为空
        if(empty($input['request_id'])){
            $this->setMsgs('无效的参数');
            return false;
        }
        $requestId = $input['request_id'];
        //判断这些ID是否正确
        $modelMemberFriendRequest = new Diana_Model_MemberFriendRequest();
        if(!$rowsMemberFriendRequest = $modelMemberFriendRequest->getRowsById(null,$requestId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //将不属于自己的请求过滤掉，将已经处理过的过滤掉
        $passOut = 0;//收件已经被处理的请求数
        $passSafe = 0;//收件人不是自己的请求数
        foreach($rowsMemberFriendRequest as &$rowMemberFriendRequest){
            if(in_array($rowMemberFriendRequest['request_pass'],array(1,2))){
                $passOut++;
                unset($rowMemberFriendRequest);
            }
            if($rowMemberFriendRequest['request_dest'] <> $this->sessionMember['id']){
                $passSafe++;
                unset($rowMemberFriendRequest);
            }
        }
        $validRequestId = array();//正确的请求ID
        foreach($rowsMemberFriendRequest as $tmpRowMemberFriendRequest){
            $validRequestId[] = $tmpRowMemberFriendRequest['request_id'];
        }
        if($passOut == count($rowsMemberFriendRequest)){
            $this->setMsgs('不要操作已经处理过的请求');
            return false;
        }
        if($passSafe == count($rowsMemberFriendRequest)){
            $this->setMsgs('不要操作不属于你的请求');
            return false;
        }
        //状态是否接受收删除
        if($input['req_del'] == 1){
            if(!$this->delete(array('request_id' => $validRequestId,'request_dest' => $this->sessionMember['id']))){
                return false;
            }
        }else{
            if(!$modelMemberFriendRequest->updatePass($validRequestId,2)){
                $this->setMsgs('状态更新失败');
                return false;
            }
        }
        $this->setMsgs('操作成功!-'.$input['req_del']);
        return $validRequestId;
    }

    /**
     * 删除
     * @param $input
     */
    function delete($input)
    {
        if(empty($input['request_id'])){
            $this->setMsgs('无效的参数');
            return false;
        }
        $requestId = $input['request_id'];
        //获取纪录
        $modelMemberFriendRequest = new Diana_Model_MemberFriendRequest();
        if(!$rowsMemberFriendRequest = $modelMemberFriendRequest->getRowsByIdDest(null,$requestId,$this->sessionMember['id'])){
            $this->setMsgs('无效的ID');
            return false;
        }
        $validRequestId = array();//正确的请求ID
        foreach($rowsMemberFriendRequest as &$rowMemberFriendRequest){
            if($rowMemberFriendRequest['request_dest'] <> $this->sessionMember['id']){
                unset($rowMemberFriendRequest);
            }else{
                $validRequestId[] = $rowMemberFriendRequest['request_id'];
            }
        }
        if(empty($rowsMemberFriendRequest)){
            $this->setMsgs('不要操作不属于你的请求');
            return false;
        }

        $condition = array( 'request_id' => $validRequestId);
        $modelMemberFriendRequest = new Diana_Model_MemberFriendRequest();
        if(!$rowsAffected = $modelMemberFriendRequest->delData($condition)){
            $this->setMsgs('删除失败');
            return false;
        }
        $this->setMsgs('成功删除'.$rowsAffected.'条纪录');
        return $rowsAffected;
    }


    /**
     * 生成数据
     * @param $input
     * @return array
     */
    function makeDataGridOfInbox($input)
    {
        $page = $input['page']?$input['page']:1;
        $pageSize = $input['rows']?$input['rows']:DIANA_DATAGRID_PAGELIST_CLIENT;
        $format = array('request_id','request_source','request_dest');
        $conditions = $this->filterColumns(array($input),$format);
        $conditions[0]['request_dest'] = $this->sessionMember['id'];
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
        $rowsMemberFriendRequest = array();
        $modelMemberFriendRequest = new Diana_Model_MemberFriendRequest();
        if($countMemberFriendRequest = $modelMemberFriendRequest->getCountByCondition(null,$condition)){
            $rowsMemberFriendRequest = $modelMemberFriendRequest->getRowsByCondition(null,$condition,null,$pagesize,$offset);
            $memberIds = array();
            foreach($rowsMemberFriendRequest as $rowMemberFriendRequest){
                $memberIds[] = $rowMemberFriendRequest['request_source'];
                $memberIds[] = $rowMemberFriendRequest['request_dest'];
            }
            $memberIds = array_filter(array_unique($memberIds));
            $option = array();
            $modelMember = new Diana_Model_Member();
            if($rowsMember = $modelMember->getRowsById(null,$memberIds)){
                foreach($rowsMember as $rowMember){
                    $tmpMemberId = $rowMember['member_id'];
                    $option[$tmpMemberId] = $rowMember;
                }
                foreach($rowsMemberFriendRequest as &$rowMemberFriendRequest){
                    $tmpRequestSource = $rowMemberFriendRequest['request_source'];
                    $tmpRequestDest = $rowMemberFriendRequest['request_dest'];
                    $rowMemberFriendRequest['request_source_memberName'] = $option[$tmpRequestSource]['member_name'];
                    $rowMemberFriendRequest['request_source_memberEmail'] = $option[$tmpRequestSource]['member_email'];
                    $rowMemberFriendRequest['request_dest_memberName'] = $option[$tmpRequestDest]['member_name'];
                    $rowMemberFriendRequest['request_dest_memberEmail'] = $option[$tmpRequestDest]['member_email'];
                }
            }
        }
        return array('total' => intval($countMemberFriendRequest),'rows' => $rowsMemberFriendRequest);
    }
}
