<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-21
 * Time: 下午11:21
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_MemberConfig extends Admin_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加新配置项
     * @param $input
     * @return array|bool
     */
    function create($input)
    {
        if(empty($input)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //确认外部参数是否正确
        if(!$input = $this->checkInputWithConf($input)){
            return false;
        }

        $input['conf_create_time'] = $input['conf_alter_time'] = time();
        $input['conf_create_manId'] = $input['conf_alter_manId'] = $this->sessionManager['id'];
        $input['conf_create_manName'] = $input['conf_alter_manName'] = $this->sessionManager['name'];
        $input['conf_create_manEmail'] = $input['conf_alter_manEmail'] = $this->sessionManager['email'];
        $input['conf_create_ip'] = $input['conf_alter_ip'] = $_SERVER['REMOTE_ADDR'];
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rowsMemberConfig = $modelMemberConfig->saveData(1,$input)){
            $this->setMsgs('配置保存失败');
            return false;
        }
        return $rowsMemberConfig;
    }

    /**
     * 修改已有配置项
     * @param $input
     * @param $confId
     * @return array|bool
     */
    function alter($input,$confId)
    {
        if(empty($input)||empty($confId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //确认外部参数是否正确
        if(!$input = $this->checkInputWithConf($input)){
            return false;
        }
        if($input['conf_fatherId'] == $confId){
            $this->setMsgs('不能把自己设为自己的类别！');
            return false;
        }
        //查询
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rowsMemberConfig = $modelMemberConfig->getRowsById(null,$confId)){
            $this->setMsgs('无效的Id - '.$confId);
            return false;
        }
        //更新
        $input['conf_alter_time'] = time();
        $input['conf_alter_manId'] = $this->sessionManager['id'];
        $input['conf_alter_manName'] = $this->sessionManager['name'];
        $input['conf_alter_manEmail'] = $this->sessionManager['email'];
        $input['conf_alter_ip'] = $_SERVER['REMOTE_ADDR'];
        $condition = array('conf_id' => $confId);
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rowsMemberConfig = $modelMemberConfig->saveData(2,$input,$condition)){
            $this->setMsgs('保存失败');
            return false;
        }
        return $rowsMemberConfig;
    }

    /**
     * 确认外部参数是否正确
     * @param $data 外部参数
     */
    function checkInputWithConf($data)
    {
        $filters = array(
            'conf_fatherId'   => array(new Zend_Filter_Int()),
            'conf_key'   => array(new Zend_Filter_StringTrim()),
            'conf_label'   => array(new Zend_Filter_StringTrim()),
            'conf_value'   => array(new Zend_Filter_StringTrim()),
            'conf_default'   => array(new Zend_Filter_StringTrim()),
            'conf_input_type'   => array(new Zend_Filter_StringTrim(),new Zend_Filter_StringToLower()),
            'conf_options'   => array(new Zend_Filter_StringTrim()),
            'conf_remark'   => array(new Zend_Filter_StringTrim()),
            'conf_order'   => array(new Zend_Filter_Int()),
        );
        $validators = array(
            'conf_fatherId'   => array(new Zend_Validate_Int(),'allowEmpty' => true),
            'conf_key'   => array(new Zend_Validate_StringLength(1,128)),
            'conf_label'   => array(new Zend_Validate_StringLength(1,128)),
            'conf_value'   => array(new Zend_Validate_StringLength(1,512),'allowEmpty' => true),
            'conf_default'   => array(new Zend_Validate_StringLength(1,512),'allowEmpty' => true),
            'conf_input_type'   => array(new Zend_Validate_Alpha()),
            'conf_options'   => array(new Zend_Validate_StringLength(1,1024),'allowEmpty' => true),
            'conf_remark'   => array(new Zend_Validate_StringLength(1,1024),'allowEmpty' => true),
            'conf_order'   => array(new Zend_Validate_Int(),'allowEmpty' => true),
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


    /**
     * 生成$comboBox所需数据
     */
    function makeComboBoxWithFather()
    {
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rows = $modelMemberConfig->getRowsByFatherId(null,0)){
            return false;
        }
        $comboBox = array();
        foreach($rows as $row){
            $comboBox[] = array(
                'id' => $row['conf_id'],
                'text' => $row['conf_label'],
            );
        }
        $comboBox = array(
            array(
                'id' => 0,
                'text' => 'root',
                'children' => $comboBox,
            ),
        );
        return $comboBox;
    }

    /**
     * 生成treegrid所需数据
     */
    function makeTreegrid()
    {
        $treeGrid = array();
        $modelMemberConfig = new Diana_Model_MemberConfig();
        //获取所有数据
        if(!$rows = $modelMemberConfig->getRowsByCondition()){
            return false;
        }
        //获取一级配置
        foreach($rows as $row){
            if(empty($row['conf_fatherId'])){
                $row['state'] = 'closed';
                $treeGrid[] = $row;
            }
        }
        foreach($treeGrid as &$rowFather){
            foreach($rows as $row){
                if($rowFather['conf_id'] == $row['conf_fatherId']){
                    $rowFather['children'][] = $row;
                }
            }
            if(empty($rowFather['children'])){
                unset($rowFather['state']);
            }
        }
        return $treeGrid;
    }

    /**
     * 通过父级ID得到子配置索引
     * @param $fatherId
     * @return array|bool 配置列表
     */
    function indexByFather($fatherId)
    {
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rows = $modelMemberConfig->getRowsByFatherId(null,$fatherId)){
            return false;
        }
        return $rows;
    }
    /**
     * 获取配置详细信息
     * @param $configId
     * @return bool
     */
    function getDetail($input)
    {
        if ((empty($input['conf_id']))&&(empty($input['conf_key']))) {
            $this->setMsgs("Invalid Param");
            return false;
        }
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!empty($input['conf_id'])){
            if(!$rowMemberConfig = $modelMemberConfig->getRowById(null,$input['conf_id'])){
                $this->setMsgs('无效的Key'.$input['conf_id']);
                return false;
            }
        }
        if(!empty($input['conf_key'])){
            if(!$rowMemberConfig = $modelMemberConfig->getRowByKey(null,$input['conf_key'])){
                $this->setMsgs('无效的Key'.$input['conf_key']);
                return false;
            }
        }
        //获取类别
        if(!empty($rowMemberConfig['conf_fatherId'])){
            if($rowFather = $modelMemberConfig->getRowById(null,$rowMemberConfig['conf_fatherId'])){
                $rowMemberConfig['conf_father'] = $rowFather;
            }
        }
        return $rowMemberConfig;
    }

    /**
     * 获取输入类型
     */
    function getInputType()
    {
        return array('input','textarea','select','radio','checkbox');
    }


}