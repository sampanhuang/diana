<?php
/**
 * 管理员登录日志
 *
 */
class Diana_Service_MemberLogLogin extends Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 写入登录日志
     *
     */
    function write($id,$email,$name)
    {
        if (empty($id)||empty($email)||empty($name)) {
            $this->setMsgs('各项参数不能为空');
            return false;
        }
        if ((!is_numeric($id))||(!is_string($email))||(!is_string($name))) {
            $this->setMsgs('参数类型错误');
            return false;
        }
        $model = new Diana_Model_MemberLogLogin();
        if (!$rows = $model->write($id,$email,$name)) {
            $this->setMsgs('登录日志写入失败');
            return false;
        }
        return $rows;
    }

    /**
     * 查询
     *
     * @param unknown_type $data
     * @param unknown_type $page
     * @param unknown_type $pagesize
     * @return unknown
     */
    function search($data = array(),$page = 1,$pagesize = 20)
    {
        $return = array();
        $condition = $this->getConditionFromSearch($data);
        $modelMemberLogLogin = new Diana_Model_MemberLogLogin();
        if ($total = $modelMemberLogLogin->getCountByCondition(null,$condition)) {
            $return['total'] = $total;
            $count = $pagesize;
            $offset = ($page-1)*$count;
            if ($rows = $modelMemberLogLogin->getRowsByCondition(null,$condition,null,$count,$offset)) {
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