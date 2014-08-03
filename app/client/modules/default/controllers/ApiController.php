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

    }

    function indexAction()
    {

    }

}