<?php
/**
 * 管理员登录日志
 *
 */
class Diana_Service_MemberLog extends Admin_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 写入登录日志
     *
     */
    function write($type,$memberId,$memberEmail,$memberName,$remark = null)
    {
        if (empty($type)||empty($memberId)||empty($memberEmail)||empty($memberName)) {
            $this->setMsgs('各项参数不能为空');
            return false;
        }
        if ((!is_numeric($type))||(!is_numeric($memberId))||(!is_string($memberEmail))||(!is_string($memberName))) {
            $this->setMsgs('参数类型错误');
            return false;
        }
        $remark = $this->makeRemark($type,$remark);
        $modelMemberLog = new Diana_Model_MemberLog();
        if (!$rowsMemberLog = $modelMemberLog->write($type,$memberId,$memberEmail,$memberName)) {
            $this->setMsgs('日志写入失败');
            return false;
        }
        $rowMemberLog = $rowsMemberLog[0];
        $logId = $rowMemberLog['log_id'];
        $modelMemberLogRemark = new Diana_Model_MemberLogRemark();
        if(!$rowsMemberLogRemark = $modelMemberLogRemark->write($logId,$remark)){
            $this->setMsgs('登录日志【备注】写入失败');
            return false;
        }
        //合并日志信息
        $rowMemberLog = array_merge($rowMemberLog,$rowsMemberLogRemark);
        return $rowMemberLog;
    }

    /**
     * 获取今年的统计数据
     * @param $year
     */
    function getState($condition,$year = null)
    {
        $state = array();
        $condition = $this->getConditionFromSearch($condition);
        $modelMemberLog = new Diana_Model_MemberLog($year);
        $state['total'] = $modelMemberLog->getCountByCondition(null,$condition);
        if($state['total'] > 0){
            $state['rows'] = $modelMemberLog->getRowsByCondition(null,$condition,null,5);
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

    function makeLogTypeCombobox()
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
     * @param unknown_type $data
     * @param unknown_type $page
     * @param unknown_type $pagesize
     * @return unknown
     */
    function makeDataGrid($page = 1,$pageSize = 20,$condition = array())
    {
        $dataGrid = array('total' => 0,'rows'=>array());
        $condition = $this->getConditionFromSearch($condition);
        $modelMemberLog = new Diana_Model_MemberLog();
        $dataGrid['total'] = $modelMemberLog->getCountByCondition(null,$condition);
        if ($dataGrid['total'] > 0) {
            $count = $pageSize;
            $offset = ($page-1)*$count;
            $optionsLogType = $this->optionsLogType();
            if ($dataGrid['rows'] = $modelMemberLog->getRowsByCondition(null,$condition,null,$count,$offset)) {
                foreach($dataGrid['rows'] as &$row){
                    $row['log_typeLabel'] = $optionsLogType[$row['log_type']];
                }
            }
        }
        return $dataGrid;
    }

    /**
     * 根据ID得到详细信息
     * @param $logId
     * @return array|bool
     */
    function getDetailById($logId)
    {
        $modelMemberLog = new Diana_Model_MemberLog();
        if(!$rowsMemberLog = $modelMemberLog->getRowsById(null,$logId)){
            $this->setMsgs("无效的ID");
            return false;
        }
        $detailMemberLog = $rowsMemberLog[0];
        $optionsLogType = $this->optionsLogType();
        $detailMemberLog['log_typeLabel'] = $optionsLogType[$detailMemberLog['log_type']];
        $modelMemberLogRemark = new Diana_Model_MemberLogRemark();
        if($rowsMemberLogRemark = $modelMemberLogRemark->getRowsById(null,$logId)){
            $detailMemberLog = array_merge($detailMemberLog,$rowsMemberLogRemark[0]);
        }
        return $detailMemberLog;
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
            'log_memberId' => 1,
            'log_memberName' => 1,
            'log_memberEmail' => 1,
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