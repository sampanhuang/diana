<?php
    /**
     * 首页
     * API 输入格式 JSON
     * API 输出格式 $response = array('stat' => 0,'msgs' => implode(';',$this->getMsgs()),'result' => $result);
     *
     */
class ApiController extends Diana_Controller_Action
{
    var $currentMemberId;//当前用户ID
    var $currentMemberEmail;//当前用户帐号
    var $currentMemberName;//当前用户姓名
    var $outputTlp = array('stat' => 0,'msgs' => '','result' => array());

    function init()
    {
        parent::init();
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图

        $serviceDoorkeeper = new Client_Service_Doorkeeper();
        if($sessionMember = new Zend_Session_Namespace(DIANA_TAG_SESSIONNAMESPAN_MEMBER)){
            $this->currentMemberId = $sessionMember->id;
            $this->currentMemberName = $sessionMember->name;
            $this->currentMemberEmail = $sessionMember->email;
        }

    }

    function indexAction()
    {

    }

    /**
     * 查看用户信息
     * 消息提醒
     */
    function userInfoAction()
    {
        $output = $this->outputTlp;
        if(empty($this->currentMemberId)){
            $output['msgs'] = '尚未登录';
        }else{
            $serviceMemberMsg = new Client_Service_MemberMsg();
            $countMsgUnread = $serviceMemberMsg->getUnreadWithInbox($this->currentMemberId);

            $output['stat'] = 1;
            $output['result'] = array(
                'user-info' => array(
                    'id' => $this->currentMemberId,
                    'name' => $this->currentMemberName,
                    'email' => $this->currentMemberEmail,
                    'msg_unread' => array(//未读短消息
                        'count' => $countMsgUnread,
                        'link' => '/profile/message/inbox',
                    ),
                ),
            );
        }
        echo json_encode($output);
    }

}