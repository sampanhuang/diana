<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-11-19
 * Time: 上午12:10
 * To change this template use File | Settings | File Templates.
 */
class Client_Service_MemberMsg extends Client_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 发送消息
     * @param $msgId
     */
    function send($rowMemberMsgOutbox)
    {
        if(empty($rowMemberMsgOutbox)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelMemberMsgOutbox = new Diana_Model_MemberMsgOutbox();
        if(!$modelMemberMsgOutbox->updateSendTime($rowMemberMsgOutbox['outbox_id'])){
            $this->setMsgs('无法更新发送时间'.$rowMemberMsgOutbox['outbox_id']);
            return false;
        }
        $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
        if(!$modelMemberMsgInbox->addMsg($rowMemberMsgOutbox['outbox_msgId'],$rowMemberMsgOutbox['msg_dest'])){
            $this->setMsgs('收件人接收失败');
            return false;
        }
        return true;
    }

    /**
     * 确认发送条件
     * @param $data
     */
    function checkInputWithSend($input)
    {
        if(empty($input['msg_source'])){
            $this->setMsgs('发件人不能为空');
            return false;
        }
        if(empty($input['msg_source'])){
            $this->setMsgs('发件人不能为空');
            return false;
        }
        if(empty($input['msg_dest'])){
            $this->setMsgs('发件人不能为空');
            return false;
        }
        if(empty($input['msg_subject'])){
            $this->setMsgs('标题不能为空');
            return false;
        }
        if(empty($input['msg_content'])){
            $this->setMsgs('消息内容不能为空');
            return false;
        }
        return $input;
    }

    /**
     * 保存消息
     * @param $data
     * @param $id
     */
    function save($data,$outboxId = null)
    {
        //msg_source,msg_subject,msg_dest,msg_content
        //检查参数
        if(!$data = $this->checkInputWithSave($data)){
            return false;
        }
        $msgId = null;
        if(!empty($outboxId)){
            $modelMemberMsgOutbox = new Diana_Model_MemberMsgOutbox();
            if(!$rowsMembermsgOutbox = $modelMemberMsgOutbox->getRowsById(null,$outboxId)){
                $this->setMsgs('无效的ID');
            }
            $msgId = $rowsMembermsgOutbox[0]['outbox_msgId'];
        }
        //保存message
        $modelMemberMsg = new Diana_Model_MemberMsg();
        if(!$rowsMemberMsg = $modelMemberMsg->saveMain($data['msg_source'],$data['msg_subject'],$msgId)){
            $this->setMsgs('消息保存失败');
            return false;
        }
        $msgId = $rowsMemberMsg[0]['msg_id'];
        //保存messagedest
        $modelMemberMsgDest = new Diana_Model_MemberMsgDest();
        if(!$rowsMemberMsgDest = $modelMemberMsgDest->updateDest($msgId,$data['msg_dest'])){
            $this->setMsgs('消息收件人保存失败');
            return false;
        }
        //保存messcontent
        $modelMemberMsgContent = new Diana_Model_MemberMsgContent();
        if(!$rowsMemberMsgContent = $modelMemberMsgContent->updateContent($msgId,$data['msg_content'])){
            $this->setMsgs('消息内容保存失败');
            return false;
        }
        //保存进草稿箱里面
        if(empty($outboxId)){
            $modelMemberMsgOutbox = new Diana_Model_MemberMsgOutbox();
            if(!$rowsMembermsgOutbox = $modelMemberMsgOutbox->addMsg($msgId,$data['msg_source'])){
                $this->setMsgs('无法将草稿放入收件箱');
                return false;
            }
            $outboxId = $rowsMembermsgOutbox[0];
        }
        return $this->readFromOutbox($outboxId);
    }

    /**
     * 确认保存的各项不能为空
     * @param $data array
     */
    function checkInputWithSave($input)
    {
        if(empty($input['msg_source'])){
            $this->setMsgs('发件人不能为空');
            return false;
        }
        if(empty($input['msg_subject'])){
            $this->setMsgs('标题不能为空');
            return false;
        }elseif(mb_strlen($input['msg_subject']) > 64){
            $this->setMsgs('标题不能超过64个字'.mb_strlen($input['msg_subject']));
            return false;
        }
        if(!empty($input['msg_content'])){
            if(mb_strlen($input['msg_content']) > 512){
                $this->setMsgs('内容不能超过512个字'.mb_strlen($input['msg_content']));
                return false;
            }
        }
        return $input;
    }

    /**
     * 删除发件箱
     * @param $outboxId
     * @param $memberId
     * @return bool
     */
    function deleteWithOutbox($outboxId,$memberId)
    {
        $modelMemberMsgOutbox = new Diana_Model_MemberMsgOutbox();
        if(!$rowsMemberMsgOutbox = $modelMemberMsgOutbox->getRowsById(null,$outboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        $delOutboxId = array();//要删除的发件消息ID
        $delMsgId = array();//要删除的
        $msgId = array();
        foreach($rowsMemberMsgOutbox as $rowMemberMsgOutbox){
            if($rowMemberMsgOutbox['outbox_manId'] == $memberId){
                $delOutboxId[] = $rowMemberMsgOutbox['outbox_id'];
                $msgId[] = $rowMemberMsgOutbox['outbox_msgId'];
            }
        }
        if(!$modelMemberMsgOutbox->delData(array('outbox_id' => $delOutboxId))){
            $this->setMsgs('删除失败');
            return false;
        }
        $modelMemberMsg = new Diana_Model_MemberMsg();
        //看看还有哪个相同msgid剩下的
        if($rowsMemberMsgOutbox = $modelMemberMsgOutbox->getRowsByMsgMember(null,$msgId)){
            foreach($rowsMemberMsgOutbox as $rowMemberMsgOutbox){
                $delMsgId[] = $rowMemberMsgOutbox['outbox_msgId'];
            }
        }else{
            $delMsgId = $msgId;
        }
        $modelMemberMsg->updateInboxOutbox($delMsgId,null,true);
        $this->deleteWithMsg();
        return count($delOutboxId);
    }

    /**
     * 将收件箱设为已读
     * @param $inboxId
     * @param $memberId
     * @return bool|int
     */
    function markReadWithInbox($inboxId,$memberId)
    {
        $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
        if(!$rowsMemberMsgInbox = $modelMemberMsgInbox->getRowsById(null,$inboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        $markReadInboxId = array();//要删除的收件消息ID
        foreach($rowsMemberMsgInbox as $rowMemberMsgInbox){
            if(($rowMemberMsgInbox['inbox_manId'] == $memberId) && ($rowMemberMsgInbox['inbox_msg_read_time'] == 0)){
                $markReadInboxId[] = $rowMemberMsgInbox['inbox_id'];
            }
        }
        if(empty($markReadInboxId)){
            $this->setMsgs('没有符合变更的纪录');
            return false;
        }
        if(!$modelMemberMsgInbox->updateReadTime($markReadInboxId)){
            $this->setMsgs('状态变更失败');
            return false;
        }
        return count($markReadInboxId);
    }

    /**
     * 删除收件箱
     * @param $inboxId 收件箱ID
     * @param $memberId 管理员ID
     * @return bool
     */
    function deleteWithInbox($inboxId,$memberId)
    {
        //确认ID是否有效
        $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
        if(!$rowsMemberMsgInbox = $modelMemberMsgInbox->getRowsById(null,$inboxId)){
            $this->setMsgs('无效的ID'.implode(',',$inboxId));
            return false;
        }
        $delInboxId = array();//要删除的收件消息ID
        $delMsgId = array();//要删除的
        $msgId = array();
        foreach($rowsMemberMsgInbox as $rowMemberMsgInbox){
            if($rowMemberMsgInbox['inbox_manId'] == $memberId){
                $delInboxId[] = $rowMemberMsgInbox['inbox_id'];
                $msgId[] = $rowMemberMsgInbox['inbox_msgId'];
            }
        }
        if(empty($delInboxId)){
            $this->setMsgs('你不能删除别人的消息');
            return false;
        }
        if(!$modelMemberMsgInbox->delData(array('inbox_id' => $delInboxId))){
            $this->setMsgs('数据删除失败');
            return false;
        }
        $modelMemberMsg = new Diana_Model_MemberMsg();
        //看看还有哪个相同msgid剩下的
        if($rowsMemberMsgInbox = $modelMemberMsgInbox->getRowsByMsgMember(null,$msgId)){
            foreach($rowsMemberMsgInbox as $rowsMemberMsgInbox){
                $delMsgId[] = $rowMemberMsgInbox['inbox_msgId'];
            }
        }else{
            $delMsgId = $msgId;
        }
        $modelMemberMsg->updateInboxOutbox($delMsgId,true);
        $this->deleteWithMsg();
        return count($delInboxId);
    }

    /**
     * 删除消息
     * @param $msgId
     */
    function deleteWithMsg()
    {
        $modelMemberMsg = new Diana_Model_MemberMsg();
        if(!$msgId = $modelMemberMsg->getIdWithDelete()){
            return false;
        }
        $condition = array('msg_id' => $msgId);
        if(!$modelMemberMsg->delData($condition)){
            $this->setMsgs('删除失败');
            return false;
        }
        $modelMemberMsgDest = new Diana_Model_MemberMsgDest();
        $modelMemberMsgDest->delData($condition);
        $modelMemberMsgContent = new Diana_Model_MemberMsgContent();
        $modelMemberMsgContent->delData($condition);
        return true;
    }

    /**
     * 过滤表单收件查询
     *
     * @param array $post 提交的表单数据
     */
    function filterFormSearchAboutInbox($post)
    {
        $exp = array(
            'inbox_msg_accept_date_min' => 1,
            'inbox_msg_accept_date_max' => 1,
            'msg_subject_like' => 1,
        );
        $post = array_filter(array_intersect_key($post,$exp));
        //开始时间
        if (!empty($post['inbox_msg_accept_date_min'])) {
            $post['inbox_msg_accept_time_min'] = strtotime($post['inbox_msg_accept_date_min']);
            unset($post['inbox_msg_accept_date_min']);
        }
        //结束时间
        if (!empty($post['inbox_msg_accept_date_max'])) {
            $post['inbox_msg_accept_time_max'] = strtotime($post['inbox_msg_accept_date_max']);
            unset($post['inbox_msg_accept_date_max']);
        }
        if(!empty($post['msg_subject_like'])){
            $modelMemberMsg = new Diana_Model_MemberMsg();
            if($rowsMemberMsg = $modelMemberMsg->getRowsByCondition(null,array('msg_subject_like' => $post['msg_subject_like']))){
                $tmpMsgId = array();
                foreach($rowsMemberMsg as $rowMemberMsg){
                    $tmpMsgId[] = $rowMemberMsg['msg_id'];
                }
                $post['inbox_msgId'] = implode(',',$tmpMsgId);
                unset($post['msg_subject_like']);
            }

        }
        return $post;
    }

    /**
     * 过滤-发件查询
     * @param $post 提交的表单数据
     * @return array
     */
    function filterFormSearchAboutOutbox($post)
    {
        $exp = array(
            'outbox_msg_send_date_min' => 1,
            'outbox_msg_send_date_max' => 1,
            'msg_subject_like' => 1,
        );
        $post = array_filter(array_intersect_key($post,$exp));
        //开始时间
        if (!empty($post['outbox_msg_send_date_min'])) {
            $post['outbox_msg_send_time_min'] = strtotime($post['outbox_msg_send_date_min']);
            unset($post['outbox_msg_send_date_min']);
        }
        //结束时间
        if (!empty($post['outbox_msg_send_date_max'])) {
            $post['outbox_msg_send_time_max'] = strtotime($post['outbox_msg_send_date_max']);
            unset($post['outbox_msg_send_date_max']);
        }
        if(!empty($post['msg_subject_like'])){
            $modelMemberMsg = new Diana_Model_MemberMsg();
            if($rowsMemberMsg = $modelMemberMsg->getRowsByCondition(null,array('msg_subject_like' => $post['msg_subject_like']))){
                $tmpMsgId = array();
                foreach($rowsMemberMsg as $rowMemberMsg){
                    $tmpMsgId[] = $rowMemberMsg['msg_id'];
                }
                $post['outbox_msgId'] = implode(',',$tmpMsgId);
                unset($post['msg_subject_like']);
            }

        }
        return $post;
    }

    /**
     * 草稿查询
     * @param $post
     */
    function filterFormSearchAboutDraft($post)
    {
        $exp = array(
            'msg_subject_like' => 1,
        );
        $post = array_filter(array_intersect_key($post,$exp));
        if(!empty($post['msg_subject_like'])){
            $modelMemberMsg = new Diana_Model_MemberMsg();
            if($rowsMemberMsg = $modelMemberMsg->getRowsByCondition(null,array('msg_subject_like' => $post['msg_subject_like']))){
                $tmpMsgId = array();
                foreach($rowsMemberMsg as $rowMemberMsg){
                    $tmpMsgId[] = $rowMemberMsg['msg_id'];
                }
                $post['outbox_msgId'] = implode(',',$tmpMsgId);
                unset($post['msg_subject_like']);
            }
        }
        return $post;
    }



    /**
     * 从发件箱查看单封邮件
     */
    function readFromOutbox($outboxId)
    {
        $modelMemberMsgOutbox = new Diana_Model_MemberMsgOutbox();
        if(!$rowsMemberMsgOutbox = $modelMemberMsgOutbox->getRowsById(null,$outboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //获取消息主体
        $rowMemberMsgOutbox = $rowsMemberMsgOutbox[0];
        if(!$tmpRowsMemberMsg = $this->getRowsWithMsg($rowMemberMsgOutbox['outbox_msgId'],true)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //将发件箱与消息主体合并
        $rowsMemberMsg = array_values($tmpRowsMemberMsg);
        $rowMemberMsgOutbox = array_merge($rowMemberMsgOutbox,$rowsMemberMsg[0]);
        //合并收件人
        $msgDest = array();
        foreach($tmpRowsMemberMsg as $tmpRowMemberMsg){
            $msgDest = array_merge($msgDest,$tmpRowMemberMsg['msg_dest']);
        }
        //读取收件人信息
        if(!empty($msgDest)){
            $modelMember = new Diana_Model_Member();
            if($rowsMember = $modelMember->getRowsById(null,$msgDest)){
                foreach($rowsMember as $rowMember){
                    if(in_array($rowMember['member_id'],$rowMemberMsgOutbox['msg_dest'])){
                        $rowMemberMsgOutbox['msg_dest_man'][$rowMember['member_id']] = $rowMember;
                        //$rowMemberMsgOutbox['msg_dest_name'][] = $rowMember['member_name'];
                        //$rowMemberMsgOutbox['msg_dest_email'][] = $rowMember['member_email'];
                    }
                }
            }
        }
        //查询阅读回执
        if($rowMemberMsgOutbox['outbox_msg_send_time'] > 0){//条件是必须是已发送
            $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
            if($rowsMemberMsgInbox = $modelMemberMsgInbox->getRowsByMsgMember(null,$rowMemberMsgOutbox['outbox_msgId'])){
                $rowMemberMsgOutbox['outbox_read_receipt'] = array();
                foreach($rowsMemberMsgInbox as $rowMemberMsgInbox){
                    $tmpInboxMemberId = $rowMemberMsgInbox['inbox_manId'];
                    $rowMemberMsgOutbox['outbox_read_receipt'][$tmpInboxMemberId] = $rowMemberMsgInbox;
                }
            }
        }
        return $rowMemberMsgOutbox;
    }



    /**
     * 发件箱
     * @param int $page 当前页
     * @param int $pagesize 每页纪录数
     * @param $condition 查询条件
     * @return array
     */
    function makeDataGridWithOutbox($page = 1,$pagesize = 20,$condition)
    {
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelMemberMsgOutbox = new Diana_Model_MemberMsgOutbox();
        //发件箱有多少条纪录
        $dataGrid['total']  = $modelMemberMsgOutbox->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            //获取发件箱指定页纪录
            if($tmpRows = $modelMemberMsgOutbox->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $msgId = array();
                foreach($tmpRows as $tmpRow){
                    $dataGrid['rows'][$tmpRow['outbox_msgId']] = $tmpRow;
                    $msgId[] = $tmpRow['outbox_msgId'];
                }
                //获取消息主体信息
                if($rowsMemberMsg = $this->getRowsWithMsg($msgId)){
                    $msgDest = array();
                    foreach($rowsMemberMsg as $rowMemberMsg){
                        $msgDest = array_merge($msgDest,$rowMemberMsg['msg_dest']);
                        $dataGrid['rows'][$rowMemberMsg['msg_id']] = array_merge($dataGrid['rows'][$rowMemberMsg['msg_id']],$rowMemberMsg);
                    }
                    $modelMember = new Diana_Model_Member();
                    if($rowsMember = $modelMember->getRowsById(null,$msgDest)){
                        foreach($dataGrid['rows'] as $tmpMsgId => $tmpRowMsg){
                            foreach($rowsMember as $rowMember){
                                if(in_array($rowMember['member_id'],$tmpRowMsg['msg_dest'])){
                                    $dataGrid['rows'][$tmpMsgId]['msg_dest_name'][] = $rowMember['member_name'];
                                    $dataGrid['rows'][$tmpMsgId]['msg_dest_email'][] = $rowMember['member_email'];
                                }
                            }
                            $dataGrid['rows'][$tmpMsgId]['msg_dest_count'] = count($dataGrid['rows'][$tmpMsgId]['msg_dest']);
                            $dataGrid['rows'][$tmpMsgId]['msg_dest_label'] = implode(',',$dataGrid['rows'][$tmpMsgId]['msg_dest_name']);
                            $dataGrid['rows'][$tmpMsgId]['outbox_receipt_read_count'] = 0;
                            $dataGrid['rows'][$tmpMsgId]['outbox_receipt_all_count'] = 0;

                        }
                    }
                }
                //获取收件人阅读情况
                $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
                if($rowsMemberMsgInbox = $modelMemberMsgInbox->getRowsByMsgMember(null,$msgId)){
                    foreach($rowsMemberMsgInbox as $rowMemberMsgInbox){
                        $tmpMsgId = $rowMemberMsgInbox['inbox_msgId'];
                        $dataGrid['rows'][$tmpMsgId]['outbox_receipt_all_count']++;
                        if($rowMemberMsgInbox['inbox_msg_read_time'] > 0){//已阅加1
                            $dataGrid['rows'][$tmpMsgId]['outbox_receipt_read_count']++;
                        }
                    }
                }
                $dataGrid['rows'] = array_values($dataGrid['rows']);
            }
        }
        return $dataGrid;
    }

    /**
     * 从收件箱查看单封邮件
     * @param $inboxId
     */
    function readFromInbox($inboxId)
    {
        $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
        if(!$rowsMemberMsgInbox = $modelMemberMsgInbox->getRowsById(null,$inboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //更新为已读
        if(!$rowsMemberMsgInbox = $modelMemberMsgInbox->updateReadTime($rowsMemberMsgInbox[0]['inbox_id'])){
            $this->setMsgs('无法更新为已读状态');
            return false;
        }
        //获取消息信息
        $rowMemberMsgInbox = $rowsMemberMsgInbox[0];
        if(!$tmpRowsMemberMsg = $this->getRowsWithMsg($rowMemberMsgInbox['inbox_msgId'],true)){
            $this->setMsgs('无效的ID');
            return false;
        }
        $rowsMemberMsg = array_values($tmpRowsMemberMsg);
        $rowMemberMsgInbox = array_merge($rowMemberMsgInbox,$rowsMemberMsg[0]);
        //获取发件人信息
        $modelMember = new Diana_Model_Member();
        if($rowsMember = $modelMember->getRowsById(null,$rowMemberMsgInbox['msg_source'])){
            $rowMemberMsgInbox['msg_source_name'] = $rowsMember[0]['member_name'];
            $rowMemberMsgInbox['msg_source_email'] = $rowsMember[0]['member_email'];
        }
        return $rowMemberMsgInbox;
    }

    function getUnreadWithInbox($manId)
    {
        $condition = array(
            'inbox_manId' => $manId,
            'inbox_msg_read_time_max' => 1,
        );
        $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
        return (int)$modelMemberMsgInbox->getCountByCondition(null,$condition);
    }

    /**
     * 收件箱
     * @param int $page 当前页
     * @param int $pagesize 每页纪录数
     * @param $condition 查询条件
     * @return array
     */
    function makeDataGridWithInbox($page = 1,$pagesize = 20,$condition)
    {
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelMemberMsgInbox = new Diana_Model_MemberMsgInbox();
        $dataGrid['total']  = $modelMemberMsgInbox->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            if($tmpRows = $modelMemberMsgInbox->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $msgId = array();
                foreach($tmpRows as $tmpRow){
                    $dataGrid['rows'][$tmpRow['inbox_msgId']] = $tmpRow;
                    $msgId[] = $tmpRow['inbox_msgId'];
                }
                if($rowsMemberMsg = $this->getRowsWithMsg($msgId)){
                    $msgSource = array();
                    foreach($rowsMemberMsg as $rowMemberMsg){
                        $msgSource[] = $rowMemberMsg['msg_source'];
                        $dataGrid['rows'][$rowMemberMsg['msg_id']] = array_merge($dataGrid['rows'][$rowMemberMsg['msg_id']],$rowMemberMsg);
                    }
                    $modelMember = new Diana_Model_Member();
                    if($rowsMember = $modelMember->getRowsById(null,$msgSource)){
                        foreach($dataGrid['rows'] as $msgId => $tmpRowMsg){
                            foreach($rowsMember as $rowMember){
                                if($tmpRowMsg['msg_source'] == $rowMember['member_id']){
                                    $dataGrid['rows'][$msgId]['msg_source_name'] = $rowMember['member_name'];
                                    $dataGrid['rows'][$msgId]['msg_source_email'] = $rowMember['member_email'];
                                }
                            }
                        }
                    }
                }
                $dataGrid['rows'] = array_values($dataGrid['rows']);
            }
        }
        return $dataGrid;
    }

    /**
     * @param $msgId 通过msgID获取纪录
     * @param $isContent 是否获取详情
     */
    function getRowsWithMsg($msgId,$isContent= null)
    {
        $data = array();
        $modelMemberMsg = new Diana_Model_MemberMsg();
        if(!$rowsMemberMsg = $modelMemberMsg->getRowsById(null,$msgId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        foreach($rowsMemberMsg as $rowMemberMsg){
            $tmpMsgId = $rowMemberMsg['msg_id'];
            $data[$tmpMsgId] = $rowMemberMsg;
        }
        //添加收件人
        $modelMemberMsgDest = new Diana_Model_MemberMsgDest();
        if($rowsMemberMsgDest = $modelMemberMsgDest->getRowsByMsg(null,$msgId)){
            foreach($rowsMemberMsgDest as $rowMemberMsgDest){
                $tmpMsgId = $rowMemberMsgDest['msg_id'];
                $tmpMsgDest = $rowMemberMsgDest['msg_dest'];
                $data[$tmpMsgId]['msg_dest'][] = $tmpMsgDest;
            }
        }
        if($isContent){
            //消息内容
            $modelMemberMsgContent = new Diana_Model_MemberMsgContent();
            if($rowsMemberMsgContent = $modelMemberMsgContent->getRowsById(null,$msgId)){
                foreach($rowsMemberMsgContent as $rowMemberMsgContent){
                    $data[$rowMemberMsgContent['msg_id']]['msg_content'] = $rowMemberMsgContent['msg_content'];
                }
            }
        }
        return $data;
    }

    function getConditionFromSearch($formData)
    {
        $condition = $formData;
        //开始时间
        if (!empty($formData['msg_date_min'])) {
            $condition['msg_time_min'] = strtotime($formData['msg_date_min']);
        }
        //结束时间
        if (!empty($formData['msg_date_max'])) {
            $condition['msg_time_max'] = strtotime($formData['msg_date_max']);
        }
        return $condition;
    }
}
