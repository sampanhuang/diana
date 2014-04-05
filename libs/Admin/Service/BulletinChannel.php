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

    function delete($input)
    {
        $channelId = $input['channel_id'];
        if(empty($channelId)&&(!is_numeric($channelId))){
            $this->setMsgs('错误的ID');
            return false;
        }
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        //判断是否有下级，有下级就无法删除
        if($modelBulletinChannel->getSonByFather(null,$channelId)){
            $this->setMsgs('无法删除仍有子类的父类');
            return false;
        }
        $condition = array( 'channel_id' => $channelId );
        if(!$rowsAffected = $modelBulletinChannel->delData($condition)){
            $this->setMsgs('删除失败');
            return false;
        }
        $this->setMsgs('成功删除'.$rowsAffected.'条纪录');
        return $rowsAffected;
    }
    /**
     * 新建一个公告频道
     * @param $input
     */
    function insert($input)
    {
        if(!$input = $this->filterInput($input)){
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
        $this->setMsgs('保存成功');
        return $rows[0];
    }

    /**
     * 更新一个公告频道
     * @param $input
     * @param $id
     */
    function update($input)
    {
        $channelId = $input['channel_id'];
        if(empty($channelId)&&(!is_numeric($channelId))){
            $this->setMsgs('错误的ID');
            return false;
        }
        if(!$input = $this->filterInput($input)){
            return false;
        }
        if($channelId == $input['channel_fatherId']){
            $this->setMsgs('不能将自己设为自己的父类');
            return false;
        }
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        //如果有子类，就不能把自己设为别人的子类
        if(!empty($input['channel_fatherId'])){
            if($modelBulletinChannel->getSonByFather(null,$input['channel_fatherId'])){
                $this->setMsgs('当前类别已有子类，无法将他从父类降为子类');
                return false;
            }
        }
        $inputOther = array(
            'channel_update_time' => time(),
            'channel_update_manId' => $this->sessionManager['id'],
            'channel_update_manName' => $this->sessionManager['name'],
            'channel_update_manEmail' => $this->sessionManager['email'],
            'channel_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $data = array_merge($input,$inputOther);
        $condition = array('channel_id' => $channelId);
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
            'channel_fatherId' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
            'channel_label_zh-cn' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'channel_label_zh-tw' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'channel_label_en-us' => array(new Zend_Filter_StripNewlines(),new Zend_Filter_HtmlEntities(),new Zend_Filter_StringTrim()),
            'channel_order' => array(new Zend_Filter_Int(),new Zend_Filter_Digits(),new Zend_Filter_StringTrim()),
        );
        $validators = array(
            'channel_fatherId' => array(new Zend_Validate_Int(),'allowEmpty' => true),
            'channel_label_zh-cn' => array(new Zend_Validate_StringLength(array('max' => '64','encoding' => 'utf-8'))),
            'channel_label_zh-tw' => array(new Zend_Validate_StringLength(array('max' => '64','encoding' => 'utf-8')),'allowEmpty' => true),
            'channel_label_en-us' => array(new Zend_Validate_StringLength(array('max' => '64','encoding' => 'utf-8')),'allowEmpty' => true),
            'channel_order' => array(new Zend_Validate_Int(),'allowEmpty' => true),
        );
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


    function makeOptionsOfFather()
    {
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if(!$rows = $modelBulletinChannel->getRowsByCondition()){
            return false;
        }
        $options = array();
        foreach($rows  as $row){
            if(empty($row['channel_fatherId'])){
                $options[$row['channel_id']] = $row['channel_label_'.DIANA_TRANSLATE_CURRENT];
            }
        }
        return $options;
    }

    /**
     * @return array|bool
     */
    function makeOptionsOfSon()
    {
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if(!$rows = $modelBulletinChannel->getRowsByCondition()){
            return false;
        }
        $options = array();
        foreach($rows  as $row){
            if(!empty($row['channel_fatherId'])){
                $options[$row['channel_fatherId']][$row['channel_id']] = $row['channel_label_'.DIANA_TRANSLATE_CURRENT];
            }
        }
        return $options;
    }


    /**
     * 为tree grid提供数据
     */
    function makeTreeGrid($input)
    {
        $treeGrid = array();
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        //获取所有数据
        if(!$rows = $modelBulletinChannel->getRowsByCondition()){
            return false;
        }
        //获取一级配置
        foreach($rows as $row){
            if(empty($row['channel_fatherId'])){
                $row['state'] = 'closed';
                $treeGrid[] = $row;
            }
        }
        foreach($treeGrid as &$rowFather){
            foreach($rows as $row){
                if($rowFather['channel_id'] == $row['channel_fatherId']){
                    $rowFather['children'][] = $row;
                }
            }
            if(empty($rowFather['children'])){
                unset($rowFather['state']);
            }
        }
        return $treeGrid;
    }


    function getDetail($column,$key)
    {
        if ((empty($column))||(!is_scalar($column))) {
            $this->setMsgs("Invalid Param - 无效的column值");
            return false;
        }
        if ((empty($key))||(!is_scalar($key))) {
            $this->setMsgs("Invalid Param - 无效的key值");
            return false;
        }
        if($column == 'channel_id'){
            $detail = $this->getDetailById($key);
        }else{
            $this->setMsgs("Invalid Param - Column ".$column);
            return false;
        }
        return $detail;
    }

    function getDetailById($channelId)
    {
        $modelBulletinChannel = new Diana_Model_BulletinChannel();
        if($row = $modelBulletinChannel->getRowById(null,$channelId)){
            if(!empty($row['channel_fatherId'])){
                if($rowFather = $modelBulletinChannel->getRowById(null,$row['channel_fatherId'])){
                    $row['channel_fatherLabel_zh-cn'] = $rowFather['channel_label_zh-cn'];
                    $row['channel_fatherLabel_zh-tw'] = $rowFather['channel_label_zh-tw'];
                    $row['channel_fatherLabel_en-us'] = $rowFather['channel_label_en-us'];
                }
            }
        }
        return $row;
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