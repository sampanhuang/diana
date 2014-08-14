<?php
/**
 * 管理员登录日志
 *
 */
class Admin_Service_ManagerLog extends Admin_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }


    /**
     * 填写登录前日志
     */
    function writeBeforeLogin($type,$inputUserName,$inputPassword,$inputCaptcha)
    {
        if (empty($type)||empty($inputUserName)||empty($inputPassword)||empty($inputCaptcha)) {
            $this->setMsgs('各项参数不能为空');
            return false;
        }
        if ((!is_scalar($type))||(!is_scalar($inputUserName))||(!is_scalar($inputPassword))||(!is_scalar($inputCaptcha))) {
            $this->setMsgs('参数类型错误');
            return false;
        }
        $modelManagerLogLogin = new Diana_Model_ManagerLogLogin();
        if (!$rowsManagerLogLogin = $modelManagerLogLogin->write($type,$inputUserName,$inputPassword,$inputCaptcha)) {
            $this->setMsgs('日志写入失败');
            return false;
        }
        return $rowsManagerLogLogin[0];
    }

    /**
     * 写入登录后日志
     *
     */
    function writeAfterLogin($type,$managerId,$managerEmail,$managerName,$remark = null)
    {
        if (empty($type)||empty($managerId)||empty($managerEmail)||empty($managerName)) {
            $this->setMsgs('各项参数不能为空');
            return false;
        }
        if ((!is_numeric($type))||(!is_numeric($managerId))||(!is_string($managerEmail))||(!is_string($managerName))) {
            $this->setMsgs('参数类型错误');
            return false;
        }
        $remark = $this->makeRemark($type,$remark);
        $modelManagerLog = new Diana_Model_ManagerLog();
        if (!$rowsManagerLog = $modelManagerLog->write($type,$managerId,$managerEmail,$managerName)) {
            $this->setMsgs('日志写入失败');
            return false;
        }
        $rowManagerLog = $rowsManagerLog[0];
        $logId = $rowManagerLog['log_id'];
        $modelManagerLogRemark = new Diana_Model_ManagerLogRemark();
        if(!$rowsManagerLogRemark = $modelManagerLogRemark->write($logId,$remark)){
            $this->setMsgs('登录日志【备注】写入失败');
            return false;
        }
        //合并日志信息
        $rowManagerLog = array_merge($rowManagerLog,$rowsManagerLogRemark);
        return $rowManagerLog;
    }

    /**
     * 获取今年的统计数据
     * @param $year
     */
    function getState($condition,$year = null)
    {
        $state = array();
        $condition = $this->getConditionFromSearch($condition);
        $modelManagerLog = new Diana_Model_ManagerLog($year);
        $state['total'] = $modelManagerLog->getCountByCondition(null,$condition);
        if($state['total'] > 0){
            $state['rows'] = $modelManagerLog->getRowsByCondition(null,$condition,null,5);
        }
        return $state;
    }

    /**
     * 生成日志备注
     * @param $type 日志类型
     * @param null $data 备注内容
     * @return array
     */
    function makeRemark($type,$data = null)
    {
        $remark = array();
        switch($type){
            case 110://修改个人资料
                break;
            case 120;//修改密保邮箱
                break;
            case 130;//修改登录帐号
                break;
            case 210://管理员登录
                break;
            case 221://发送密保邮件
                break;
            case 222://通过密保邮件取回密码
                break;
            case 223://通过后台修改密码
                break;
            case 311://通过网站申请
                break;
            case 312://拒绝网站申请
                break;
            case 320://修改网站
                break;
            case 330://删除网站
                break;
            default://
                break;
        }
        return $remark;
    }

    /**
     * 日志类型选项
     * @return array
     */
    function optionsLogType()
    {
        return array(
            110 => '修改个人资料',
            120 => '修改密保邮箱',
            130 => '修改登录帐号',
            210 => '管理员登录',
            221 => '发送密保邮件',
            222 => '通过密保邮件取回密码',
            223 => '通过后台修改密码',
            311 => '通过网站申请',
            312 => '拒绝网站申请',
            320 => '修改网站',
            330 => '删除网站',
        );
    }

    function makeLogTypeCombobox($request = null)
    {
        $arrCombobox = array();
        $optionsLogType = $this->optionsLogType();
        foreach($optionsLogType as $typeId => $typeLabel){
            $arrCombobox[] = array('id' => $typeId,'label'=> $typeLabel);
        }
        return $arrCombobox;
    }

    /**
     * 查询
     *
     * @param array $input
     * @return unknown
     */
    function makeDataGrid($input)
    {
        $dataGrid = array('total' => 0,'rows'=>array());
        $page = $input['page'];
        $pageSize = $input['rows'];
        $condition = $this->getConditionFromSearch($input);
        $modelManagerLog = new Diana_Model_ManagerLog();
        $dataGrid['total'] = $modelManagerLog->getCountByCondition(null,$condition);
        if ($dataGrid['total'] > 0) {
            $count = $pageSize;
            $offset = ($page-1)*$count;
            $optionsLogType = $this->optionsLogType();
            if ($dataGrid['rows'] = $modelManagerLog->getRowsByCondition(null,$condition,null,$count,$offset)) {
                foreach($dataGrid['rows'] as &$row){
                    $row['log_typeLabel'] = $optionsLogType[$row['log_type']];
                }
            }
        }
        return $dataGrid;
    }

    /**
     * 根据ID得到详细信息
     * @param $input
     * @return array|bool
     */
    function getDetailById($input)
    {
        $logId = $input['log_id'];
        $modelManagerLog = new Diana_Model_ManagerLog();
        if(!$rowsManagerLog = $modelManagerLog->getRowsById(null,$logId)){
            $this->setMsgs("无效的ID");
            return false;
        }
        $detailManagerLog = $rowsManagerLog[0];
        $optionsLogType = $this->optionsLogType();
        $detailManagerLog['log_typeLabel'] = $optionsLogType[$detailManagerLog['log_type']];
        $modelManagerLogRemark = new Diana_Model_ManagerLogRemark();
        if($rowsManagerLogRemark = $modelManagerLogRemark->getRowsById(null,$logId)){
            $detailManagerLog = array_merge($detailManagerLog,$rowsManagerLogRemark[0]);
        }
        return $detailManagerLog;
    }


    /**
     * 过滤表单查询
     *
     * @param array $post 提交的表单数据
     */
    function filterFormSearch($post)
    {
        $exp = array(
            'log_id' => 1,
            'log_date_min' => 1,
            'log_date_max' => 1,
            'log_ip' => 1,
            'log_type' => 1,
            'log_sessionId' => 1,
            'log_managerId' => 1,
            'log_managerName' => 1,
            'log_managerEmail' => 1,
        );
        return array_filter(array_intersect_key($post,$exp));
    }


    function getConditionFromSearch($formData)
    {
        $condition = $formData;
        //开始时间
        if (!empty($formData['log_date_min'])) {
            $condition['log_time_min'] = strtotime($formData['log_date_min']);
        }
        //结束时间
        if (!empty($formData['log_date_max'])) {
            $condition['log_time_max'] = strtotime($formData['log_date_max']);
        }
        return $condition;
    }
}