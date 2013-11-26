<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-11-19
 * Time: 上午12:10
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_ManagerMsg extends Admin_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 发送消息
     * @param $msgId
     */
    function send($rowManagerMsgOutbox)
    {
        if(empty($rowManagerMsgOutbox)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        $modelManagerMsgOutbox = new Diana_Model_ManagerMsgOutbox();
        if(!$modelManagerMsgOutbox->updateSendTime($rowManagerMsgOutbox['outbox_id'])){
            $this->setMsgs('无法更新发送时间'.$rowManagerMsgOutbox['outbox_id']);
            return false;
        }
        $modelManagerMsgInbox = new Diana_Model_ManagerMsgInbox();
        if(!$modelManagerMsgInbox->addMsg($rowManagerMsgOutbox['outbox_msgId'],$rowManagerMsgOutbox['msg_dest'])){
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
            $modelManagerMsgOutbox = new Diana_Model_ManagerMsgOutbox();
            if(!$rowsManagermsgOutbox = $modelManagerMsgOutbox->getRowsById(null,$outboxId)){
                $this->setMsgs('无效的ID');
            }
            $msgId = $rowsManagermsgOutbox[0]['outbox_msgId'];
        }
        //保存message
        $modelManagerMsg = new Diana_Model_ManagerMsg();
        if(!$rowsManagerMsg = $modelManagerMsg->saveMain($data['msg_source'],$data['msg_subject'],$msgId)){
            $this->setMsgs('消息保存失败');
            return false;
        }
        $msgId = $rowsManagerMsg[0]['msg_id'];
        //保存messagedest
        $modelManagerMsgDest = new Diana_Model_ManagerMsgDest();
        if(!$rowsManagerMsgDest = $modelManagerMsgDest->updateDest($msgId,$data['msg_dest'])){
            $this->setMsgs('消息收件人保存失败');
            return false;
        }
        //保存messcontent
        $modelManagerMsgContent = new Diana_Model_ManagerMsgContent();
        if(!$rowsManagerMsgContent = $modelManagerMsgContent->updateContent($msgId,$data['msg_content'])){
            $this->setMsgs('消息内容保存失败');
            return false;
        }
        //保存进草稿箱里面
        if(empty($outboxId)){
            $modelManagerMsgOutbox = new Diana_Model_ManagerMsgOutbox();
            if(!$rowsManagermsgOutbox = $modelManagerMsgOutbox->addMsg($msgId,$data['msg_source'])){
                $this->setMsgs('无法将草稿放入收件箱');
                return false;
            }
            $outboxId = $rowsManagermsgOutbox[0];
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
     * @param $managerId
     * @return bool
     */
    function deleteWithOutbox($outboxId,$managerId)
    {
        $modelManagerMsgOutbox = new Diana_Model_ManagerMsgOutbox();
        if(!$rowsManagerMsgOutbox = $modelManagerMsgOutbox->getRowsById(null,$outboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        $delOutboxId = array();//要删除的发件消息ID
        $delMsgId = array();//要删除的
        $msgId = array();
        foreach($rowsManagerMsgOutbox as $rowManagerMsgOutbox){
            if($rowManagerMsgOutbox['outbox_managerId'] == $managerId){
                $delOutboxId[] = $rowManagerMsgOutbox['outbox_id'];
                $msgId[] = $rowManagerMsgOutbox['outbox_msgId'];
            }
        }
        if(!$modelManagerMsgOutbox->delData(array('outbox_id' => $delOutboxId))){
            $this->setMsgs('删除失败');
            return false;
        }
        $modelManagerMsg = new Diana_Model_ManagerMsg();
        //看看还有哪个相同msgid剩下的
        if($rowsManagerMsgOutbox = $modelManagerMsgOutbox->getRowsByMsgManager(null,$msgId)){
            foreach($rowsManagerMsgOutbox as $rowManagerMsgOutbox){
                $delMsgId[] = $rowManagerMsgOutbox['outbox_msgId'];
            }
        }else{
            $delMsgId = $msgId;
        }
        $modelManagerMsg->updateInboxOutbox($delMsgId,null,true);
        $this->deleteWithMsg();
        return count($delOutboxId);
    }

    /**
     * 将收件箱设为已读
     * @param $inboxId
     * @param $managerId
     * @return bool|int
     */
    function markReadWithInbox($inboxId,$managerId)
    {
        $modelManagerMsgInbox = new Diana_Model_ManagerMsgInbox();
        if(!$rowsManagerMsgInbox = $modelManagerMsgInbox->getRowsById(null,$inboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        $markReadInboxId = array();//要删除的收件消息ID
        foreach($rowsManagerMsgInbox as $rowManagerMsgInbox){
            if(($rowManagerMsgInbox['inbox_managerId'] == $managerId) && ($rowManagerMsgInbox['inbox_msg_read_time'] == 0)){
                $markReadInboxId[] = $rowManagerMsgInbox['inbox_id'];
            }
        }
        if(empty($markReadInboxId)){
            $this->setMsgs('没有符合变更的纪录');
            return false;
        }
        if(!$modelManagerMsgInbox->updateReadTime($markReadInboxId)){
            $this->setMsgs('状态变更失败');
            return false;
        }
        return count($markReadInboxId);
    }

    /**
     * 删除收件箱
     * @param $inboxId 收件箱ID
     * @param $managerId 管理员ID
     * @return bool
     */
    function deleteWithInbox($inboxId,$managerId)
    {
        //确认ID是否有效
        $modelManagerMsgInbox = new Diana_Model_ManagerMsgInbox();
        if(!$rowsManagerMsgInbox = $modelManagerMsgInbox->getRowsById(null,$inboxId)){
            $this->setMsgs('无效的ID'.implode(',',$inboxId));
            return false;
        }
        $delInboxId = array();//要删除的收件消息ID
        $delMsgId = array();//要删除的
        $msgId = array();
        foreach($rowsManagerMsgInbox as $rowManagerMsgInbox){
            if($rowManagerMsgInbox['inbox_managerId'] == $managerId){
                $delInboxId[] = $rowManagerMsgInbox['inbox_id'];
                $msgId[] = $rowManagerMsgInbox['inbox_msgId'];
            }
        }
        if(empty($delInboxId)){
            $this->setMsgs('你不能删除别人的消息');
            return false;
        }
        if(!$modelManagerMsgInbox->delData(array('inbox_id' => $delInboxId))){
            $this->setMsgs('数据删除失败');
            return false;
        }
        $modelManagerMsg = new Diana_Model_ManagerMsg();
        //看看还有哪个相同msgid剩下的
        if($rowsManagerMsgInbox = $modelManagerMsgInbox->getRowsByMsgManager(null,$msgId)){
            foreach($rowsManagerMsgInbox as $rowsManagerMsgInbox){
                $delMsgId[] = $rowManagerMsgInbox['inbox_msgId'];
            }
        }else{
            $delMsgId = $msgId;
        }
        $modelManagerMsg->updateInboxOutbox($delMsgId,true);
        $this->deleteWithMsg();
        return count($delInboxId);
    }

    /**
     * 删除消息
     * @param $msgId
     */
    function deleteWithMsg()
    {
        $modelManagerMsg = new Diana_Model_ManagerMsg();
        if(!$msgId = $modelManagerMsg->getIdWithDelete()){
            return false;
        }
        $condition = array('msg_id' => $msgId);
        if(!$modelManagerMsg->delData($condition)){
            $this->setMsgs('删除失败');
            return false;
        }
        $modelManagerMsgDest = new Diana_Model_ManagerMsgDest();
        $modelManagerMsgDest->delData($condition);
        $modelManagerMsgContent = new Diana_Model_ManagerMsgContent();
        $modelManagerMsgContent->delData($condition);
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
            $modelManagerMsg = new Diana_Model_ManagerMsg();
            if($rowsManagerMsg = $modelManagerMsg->getRowsByCondition(null,array('msg_subject_like' => $post['msg_subject_like']))){
                $tmpMsgId = array();
                foreach($rowsManagerMsg as $rowManagerMsg){
                    $tmpMsgId[] = $rowManagerMsg['msg_id'];
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
            $modelManagerMsg = new Diana_Model_ManagerMsg();
            if($rowsManagerMsg = $modelManagerMsg->getRowsByCondition(null,array('msg_subject_like' => $post['msg_subject_like']))){
                $tmpMsgId = array();
                foreach($rowsManagerMsg as $rowManagerMsg){
                    $tmpMsgId[] = $rowManagerMsg['msg_id'];
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
            $modelManagerMsg = new Diana_Model_ManagerMsg();
            if($rowsManagerMsg = $modelManagerMsg->getRowsByCondition(null,array('msg_subject_like' => $post['msg_subject_like']))){
                $tmpMsgId = array();
                foreach($rowsManagerMsg as $rowManagerMsg){
                    $tmpMsgId[] = $rowManagerMsg['msg_id'];
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
        $modelManagerMsgOutbox = new Diana_Model_ManagerMsgOutbox();
        if(!$rowsManagerMsgOutbox = $modelManagerMsgOutbox->getRowsById(null,$outboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //获取消息主体
        $rowManagerMsgOutbox = $rowsManagerMsgOutbox[0];
        if(!$tmpRowsManagerMsg = $this->getRowsWithMsg($rowManagerMsgOutbox['outbox_msgId'],true)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //将发件箱与消息主体合并
        $rowsManagerMsg = array_values($tmpRowsManagerMsg);
        $rowManagerMsgOutbox = array_merge($rowManagerMsgOutbox,$rowsManagerMsg[0]);
        //合并收件人
        $msgDest = array();
        foreach($tmpRowsManagerMsg as $tmpRowManagerMsg){
            $msgDest = array_merge($msgDest,$tmpRowManagerMsg['msg_dest']);
        }
        //读取收件人信息
        if(!empty($msgDest)){
            $modelManager = new Diana_Model_Manager();
            if($rowsManager = $modelManager->getRowsById(null,$msgDest)){
                foreach($rowsManager as $rowManager){
                    if(in_array($rowManager['manager_id'],$rowManagerMsgOutbox['msg_dest'])){
                        $rowManagerMsgOutbox['msg_dest_man'][$rowManager['manager_id']] = $rowManager;
                        //$rowManagerMsgOutbox['msg_dest_name'][] = $rowManager['manager_name'];
                        //$rowManagerMsgOutbox['msg_dest_email'][] = $rowManager['manager_email'];
                    }
                }
            }
        }
        //查询阅读回执
        if($rowManagerMsgOutbox['outbox_msg_send_time'] > 0){//条件是必须是已发送
            $modelManagerMsgInbox = new Diana_Model_ManagerMsgInbox();
            if($rowsManagerMsgInbox = $modelManagerMsgInbox->getRowsByMsgManager(null,$rowManagerMsgOutbox['outbox_msgId'])){
                $rowManagerMsgOutbox['outbox_read_receipt'] = array();
                foreach($rowsManagerMsgInbox as $rowManagerMsgInbox){
                    $tmpInboxManagerId = $rowManagerMsgInbox['inbox_managerId'];
                    $rowManagerMsgOutbox['outbox_read_receipt'][$tmpInboxManagerId] = $rowManagerMsgInbox;
                }
            }
        }
        return $rowManagerMsgOutbox;
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
        $modelManagerMsgOutbox = new Diana_Model_ManagerMsgOutbox();
        //发件箱有多少条纪录
        $dataGrid['total']  = $modelManagerMsgOutbox->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            //获取发件箱指定页纪录
            if($tmpRows = $modelManagerMsgOutbox->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $msgId = array();
                foreach($tmpRows as $tmpRow){
                    $dataGrid['rows'][$tmpRow['outbox_msgId']] = $tmpRow;
                    $msgId[] = $tmpRow['outbox_msgId'];
                }
                //获取消息主体信息
                if($rowsManagerMsg = $this->getRowsWithMsg($msgId)){
                    $msgDest = array();
                    foreach($rowsManagerMsg as $rowManagerMsg){
                        $msgDest = array_merge($msgDest,$rowManagerMsg['msg_dest']);
                        $dataGrid['rows'][$rowManagerMsg['msg_id']] = array_merge($dataGrid['rows'][$rowManagerMsg['msg_id']],$rowManagerMsg);
                    }
                    $modelManager = new Diana_Model_Manager();
                    if($rowsManager = $modelManager->getRowsById(null,$msgDest)){
                        foreach($dataGrid['rows'] as $tmpMsgId => $tmpRowMsg){
                            foreach($rowsManager as $rowManager){
                                if(in_array($rowManager['manager_id'],$tmpRowMsg['msg_dest'])){
                                    $dataGrid['rows'][$tmpMsgId]['msg_dest_name'][] = $rowManager['manager_name'];
                                    $dataGrid['rows'][$tmpMsgId]['msg_dest_email'][] = $rowManager['manager_email'];
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
                $modelManagerMsgInbox = new Diana_Model_ManagerMsgInbox();
                if($rowsManagerMsgInbox = $modelManagerMsgInbox->getRowsByMsgManager(null,$msgId)){
                    foreach($rowsManagerMsgInbox as $rowManagerMsgInbox){
                        $tmpMsgId = $rowManagerMsgInbox['inbox_msgId'];
                        $dataGrid['rows'][$tmpMsgId]['outbox_receipt_all_count']++;
                        if($rowManagerMsgInbox['inbox_msg_read_time'] > 0){//已阅加1
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
        $modelManagerMsgInbox = new Diana_Model_ManagerMsgInbox();
        if(!$rowsManagerMsgInbox = $modelManagerMsgInbox->getRowsById(null,$inboxId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        //更新为已读
        if(!$rowsManagerMsgInbox = $modelManagerMsgInbox->updateReadTime($rowsManagerMsgInbox[0]['inbox_id'])){
            $this->setMsgs('无法更新为已读状态');
            return false;
        }
        //获取消息信息
        $rowManagerMsgInbox = $rowsManagerMsgInbox[0];
        if(!$tmpRowsManagerMsg = $this->getRowsWithMsg($rowManagerMsgInbox['inbox_msgId'],true)){
            $this->setMsgs('无效的ID');
            return false;
        }
        $rowsManagerMsg = array_values($tmpRowsManagerMsg);
        $rowManagerMsgInbox = array_merge($rowManagerMsgInbox,$rowsManagerMsg[0]);
        //获取发件人信息
        $modelManager = new Diana_Model_Manager();
        if($rowsManager = $modelManager->getRowsById(null,$rowManagerMsgInbox['msg_source'])){
            $rowManagerMsgInbox['msg_source_name'] = $rowsManager[0]['manager_name'];
            $rowManagerMsgInbox['msg_source_email'] = $rowsManager[0]['manager_email'];
        }
        return $rowManagerMsgInbox;
    }

    /**
     * 收件箱
     * @param int $page 当前页
     * @param int $pagesize 每页纪录数
     * @param $condition 查询条件
     * @return array
     */
    function makeDataGridWIthInbox($page = 1,$pagesize = 20,$condition)
    {
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelManagerMsgInbox = new Diana_Model_ManagerMsgInbox();
        $dataGrid['total']  = $modelManagerMsgInbox->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            if($tmpRows = $modelManagerMsgInbox->getRowsByCondition(null,$condition,null,$pagesize,$offset)){
                $msgId = array();
                foreach($tmpRows as $tmpRow){
                    $dataGrid['rows'][$tmpRow['inbox_msgId']] = $tmpRow;
                    $msgId[] = $tmpRow['inbox_msgId'];
                }
                if($rowsManagerMsg = $this->getRowsWithMsg($msgId)){
                    $msgSource = array();
                    foreach($rowsManagerMsg as $rowManagerMsg){
                        $msgSource[] = $rowManagerMsg['msg_source'];
                        $dataGrid['rows'][$rowManagerMsg['msg_id']] = array_merge($dataGrid['rows'][$rowManagerMsg['msg_id']],$rowManagerMsg);
                    }
                    $modelManager = new Diana_Model_Manager();
                    if($rowsManager = $modelManager->getRowsById(null,$msgSource)){
                        foreach($dataGrid['rows'] as $msgId => $tmpRowMsg){
                            foreach($rowsManager as $rowManager){
                                if($tmpRowMsg['msg_source'] == $rowManager['manager_id']){
                                    $dataGrid['rows'][$msgId]['msg_source_name'] = $rowManager['manager_name'];
                                    $dataGrid['rows'][$msgId]['msg_source_email'] = $rowManager['manager_email'];
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
        $modelManagerMsg = new Diana_Model_ManagerMsg();
        if(!$rowsManagerMsg = $modelManagerMsg->getRowsById(null,$msgId)){
            $this->setMsgs('无效的ID');
            return false;
        }
        foreach($rowsManagerMsg as $rowManagerMsg){
            $tmpMsgId = $rowManagerMsg['msg_id'];
            $data[$tmpMsgId] = $rowManagerMsg;
        }
        //添加收件人
        $modelManagerMsgDest = new Diana_Model_ManagerMsgDest();
        if($rowsManagerMsgDest = $modelManagerMsgDest->getRowsByMsg(null,$msgId)){
            foreach($rowsManagerMsgDest as $rowManagerMsgDest){
                $tmpMsgId = $rowManagerMsgDest['msg_id'];
                $tmpMsgDest = $rowManagerMsgDest['msg_dest'];
                $data[$tmpMsgId]['msg_dest'][] = $tmpMsgDest;
            }
        }
        if($isContent){
            //消息内容
            $modelManagerMsgContent = new Diana_Model_ManagerMsgContent();
            if($rowsManagerMsgContent = $modelManagerMsgContent->getRowsById(null,$msgId)){
                foreach($rowsManagerMsgContent as $rowManagerMsgContent){
                    $data[$rowManagerMsgContent['msg_id']]['msg_content'] = $rowManagerMsgContent['msg_content'];
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
