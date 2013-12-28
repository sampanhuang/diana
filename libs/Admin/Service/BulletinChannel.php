<?php
/**
 * 输出验证码
 *
 */
class Admin_Service_BulletinChannel extends Admin_Service_Abstract
{
    var $captchaObj;
    function __construct()
    {
        parent::__construct();
    }
    /**
     * 新建一个公告频道
     * @param $input
     */
    function insert($input)
    {
        if(!$input = $this->filterInputOfChannel($input)){
            return false;
        }
        $inputOther = array(
            'channel_insert_time' => time(),
            'channel_insert_manId' => $this->sessionManager['id'],
            'channel_insert_manName' => $this->sessionManager['name'],
            'channel_insert_manEmail' => $this->sessionManager['email'],
            'channel_insert_ip' => $_SERVER['REMOTE_ADDR'],
            'channel_update_time' => time(),
            'channel_update_manId' => $this->sessionManager['id'],
            'channel_update_manName' => $this->sessionManager['name'],
            'channel_update_manEmail' => $this->sessionManager['email'],
            'channel_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $data = array_merge($input,$inputOther);
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if(!$rows = $modelBulletinChannel->saveData(1,$data)){
            $this->setMsgs('数据写入失败');
            return false;
        }
        return $rows[0];
    }

    /**
     * 更新一个公告频道
     * @param $input
     * @param $id
     */
    function update($input,$id)
    {
        if(empty($id)&&(!is_numeric($id))){
            $this->setMsgs('错误的ID');
            return false;
        }
        if(!$input = $this->filterInputOfChannel($input)){
            return false;
        }
        $inputOther = array(
            'channel_update_time' => time(),
            'channel_update_manId' => $this->sessionManager['id'],
            'channel_update_manName' => $this->sessionManager['name'],
            'channel_update_manEmail' => $this->sessionManager['email'],
            'channel_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $data = array_merge($input,$inputOther);
        $condition = array('channel_id' => $id);
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if(!$rows = $modelBulletinChannel->saveData(2,$data,$condition)){
            $this->setMsgs('数据更新失败');
            return false;
        }
        return $rows[0];
    }

    /**
     * 过滤公告提交内容
     * @param $data
     */
    function filterInput($data)
    {
        $filters = array(
            'channel_label' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
        );
        $validators = array(
            'channel_label' => array(new Zend_Validate_StringLength(array('max' => '64','encoding' => 'utf-8'))),
        );
        $input = new Zend_Filter_Input($filters, $validators, $data);
        $input = new Zend_Filter_Input($filters, $validators, $data);
        if (!$input->isValid()) {
            $messageInput = $input->getMessages();
            foreach($messageInput as $valMsg){
                $this->setMsgs($valMsg);
            }
            return false;
        }
        return $input->getEscaped();
    }

    /**
     * 生成数据
     * @param $params
     * @return array
     */
    function makeDataGird($params)
    {
        $page = $params['page']?$params['page']:1;
        $pageSize = $params['rows']?$params['rows']:DIANA_DATAGRID_PAGESIZE_ADMIN;
        $tmpCondition = $this->filterColumns(array($params),$this->getFilterColumnsForQuery());
        $order = implode('_',array($params['order_by_1'],$params['order_by_2']));
        return $this->pageByCondition($page,$pageSize,$tmpCondition[0],$order);
    }

    /**
     * 获取分页
     *
     * @param int $page 当前页
     * @param int $pagesize 每页纪录数
     * @param array $condition 查询条件
     * @param array $order 排序方式
     * @return array
     */
    function pageByCondition($page = 1,$pagesize = 1,$condition = array(),$order = null)
    {
        $offset = ($page - 1)*$pagesize;
        if($offset < 0){$offset = 0;}
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if($countBulletin = $modelBulletinChannel->getCountByCondition(null,$condition)){
            $rowsBulletin = $modelBulletinChannel->getRowsByCondition(null,$condition,$order,$pagesize,$offset);
        }
        return array('total' => $countBulletin,'rows' => $rowsBulletin);
    }


    /**
     * 过滤
     * @param $input
     * @return bool
     */
    function filterRequestQuery($input)
    {
        if(empty($input)){
            return false;
        }
        return $input;
    }

    /**
     * 获取查询过滤字段
     * @return array
     */
    function getFilterColumnsForQuery()
    {
        return array(
            'channel_id',
            'channel_label',
        );
    }

    /**
     * 获取排序字段
     * @return array 排序字段
     */
    function getFilterColumnsForOrder()
    {
        return array(
            'channel_insert_time',
            'channel_update_time',
            'channel_count',
        );
    }

}