<?php
/**
 * 管理员登录日志
 *
 */
class Diana_Service_MemberLog extends Diana_Service_Abstract
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
            $this->setMsgs('登录日志写入失败');
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
     * 生成日志备注
     * @param $type 日志类型
     * @param null $data 备注内容
     * @return array
     */
    function makeRemark($type,$data = null)
    {
        $remark = array();
        switch($type){
            case 11://注册
                break;
            case 12;//修改个人资料
                break;
            case 21://登录
                break;
			case 221://发送密保邮件
                break;                
            case 222://通过密保邮件取回密码
                break;
            case 223://通过后台修改密码
            	break;
            case 31://提交网站发布申请
                break;
            case 32://修改网站
                break;
            case 33://删除网站
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
        return array(11,12,21,22,23,31,32,33);
    }

    /**
     * 查询
     *
     * @param unknown_type $data
     * @param unknown_type $page
     * @param unknown_type $pagesize
     * @return unknown
     */
    function pageByCondition($condition = array(),$page = 1,$pagesize = 20)
    {
        $return = array();
        $condition = $this->getConditionFromSearch($condition);
        $modelMemberLog = new Diana_Model_MemberLog();
        if ($total = $modelMemberLog->getCountByCondition(null,$condition)) {
            $return['total'] = $total;
            $count = $pagesize;
            $offset = ($page-1)*$count;
            if ($rows = $modelMemberLog->getRowsByCondition(null,$condition,null,$count,$offset)) {
                $return['rows'] = $rows;
            }
        }
        return $return;
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
            'log_time_min' => 1,
            'log_time_max' => 1,
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
        if (!empty($formData['log_time_min'])) {
            $condition['log_time_min'] = strtotime($formData['log_time_min']);
        }
        //结束时间
        if (!empty($formData['log_time_max'])) {
            $condition['log_time_max'] = strtotime($formData['log_time_max']);
        }
        return $condition;
    }
}