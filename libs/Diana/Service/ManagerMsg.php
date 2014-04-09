<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-9-7
 * Time: 下午5:23
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_ManagerMsg extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 打草稿
     * @param $param 消息内容
     * @param null $msgId 消息ID
     */
    function draft($param,$msgId = null)
    {
        //参数过滤及校验
        if(!$param = $this->filterInput($param)){
            $this->setMsgs('参数错误 - data');
            return false;
        }
        if((!empty($msgId))&&(!is_numeric($msgId))){
            $this->setMsgs('参数错误 - msgId');
            return false;
        }
        $modelManagerMsg = new Diana_Model_ManagerMsg();
        //过滤及校验$msgId
        if((!empty($msgId))&&(is_numeric($msgId))){
            if(!$tmpRowsManagerMsg = $modelManagerMsg->getRowsById(null,$msgId)){
                $this->setMsgs('无效的参数 - msgId');
                return false;
            }
            if(!empty($tmpRowsManagerMsg[0]['msg_send_time'])){
                $this->setMsgs('当前消息已经发送，无法变更');
                return false;
            }
        }
        //写入消息
        $data = array(
            'msg_source' => $param['msg_source'],
            'msg_dest' => $param['msg_dest'],
            'msg_subject' => $param['msg_subject'],
            'msg_content' => $param['msg_content'],
        );
        if(!$rowsManagerMsg = $modelManagerMsg->write($data,$msgId)){
            $this->setMsgs('消息写入失败');
            return false;
        }
        $rowManagerMsg = $rowsManagerMsg[0];
        return $rowManagerMsg;
    }

    /**
     * 邮件发送
     * @param $data 发送内容
     * @param null $id
     * @param bool $isSystem 是否为系统发送
     * @return bool
     */
    function send($data,$id = null,$isSystem = null)
    {
        if(empty($data)||(!is_array($data))){
            $this->setMsgs('参数错误 - data');
            return false;
        }
        //如果是系统发送，则发件人为空
        if($isSystem){$data['msg_source'] = 0;}
        if(empty($isSystem)){//不是系统邮件就需要限制发送数据
            //获取已发送的数量
            $modelManagerMsg = new Diana_Model_ManagerMsg();
            $countWithOutboxByMnanager = $modelManagerMsg->getCountWithOutboxByMnanager($data['msg_source']);
            $modelConfig = new Diana_Model_Config();
            if(!$configMessageSendCountManager = $modelConfig->getValueByKey(null,'message_send_count_manager')){
                $this->setMsgs('配置获取失败 - message_send_count_manager');
                return false;
            }
            if($configMessageSendCountManager < $countWithOutboxByMnanager ){
                $this->setMsgs('发送失败，你已经发送了'.$countWithOutboxByMnanager.'条消息，超过了消息发送限制');
                return false;
            }
        }
        $data['msg_send_time'] = time();
        $data['msg_send_ip'] = $_SERVER['REMOTE_ADDR'];
        return $this->draft($data,$id);
    }

    /**
     * 过滤及校验输入值
     * @param $data 输入值
     */
    function filterInput($data)
    {
        $filters = array(
            'msg_source' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'msg_dest' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'msg_subject' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'msg_content' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'msg_send_time' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'msg_send_ip' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
        );
        $validators = array(
            'msg_source' => array(new Zend_Validate_Int()),
            'msg_dest' => array(new Zend_Validate_Int()),
            'msg_subject' => array(new Zend_Validate_StringLength(array('max' => '64','encoding' => 'utf-8'))),
            'msg_content' => array(new Zend_Validate_StringLength(array('max' => '512','encoding' => 'utf-8'))),
            'msg_send_time' => array(new Zend_Validate_Int()),
            'msg_send_ip' => array(new Zend_Validate_Ip()),
        );
        $input = new Zend_Filter_Input($filters, $validators, $data);
        if(!$input->hasInvalid()){
            $this->setMsgs($input->getMessages());
        }
        return $input->getEscaped();
    }
}
