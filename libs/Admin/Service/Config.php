<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-21
 * Time: 下午11:21
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_Config extends Admin_Service_Abstract
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
    function insert($input)
    {
        if(empty($input)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //确认外部参数是否正确
        if(!$input = $this->checkInputWithConf($input)){
            return false;
        }
        $input['conf_insert_time'] = $input['conf_update_time'] = $input['conf_alter_time'] = time();
        $input['conf_insert_manId'] = $input['conf_update_manId'] = $input['conf_alter_manId'] = $this->sessionManager['id'];
        $input['conf_insert_manName'] = $input['conf_update_manName'] = $input['conf_alter_manName'] = $this->sessionManager['name'];
        $input['conf_insert_manEmail'] = $input['conf_update_manEmail'] = $input['conf_alter_manEmail'] = $this->sessionManager['email'];
        $input['conf_insert_ip'] = $input['conf_update_ip'] = $input['conf_alter_ip'] = $_SERVER['REMOTE_ADDR'];
        $modelConfig = new Diana_Model_Config();
        if(!$rowsConfig = $modelConfig->saveData(1,$input)){
            $this->setMsgs('配置保存失败');
            return false;
        }
        //写入历史
        $modelConfigUpdateHistory = new Diana_Model_ConfigUpdateHistory();
        if(!$modelConfigUpdateHistory->write($rowsConfig[0]['conf_id'],$input['conf_key'],$input['conf_value'],$this->sessionManager)){
            $this->setMsgs('配置历史纪录保存失败');
            return false;
        }
        return $rowsConfig;
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
        $modelConfig = new Diana_Model_Config();
        if(!$rowsConfig = $modelConfig->getRowsById(null,$confId)){
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
        $modelConfig = new Diana_Model_Config();
        if(!$rowsConfig = $modelConfig->saveData(2,$input,$condition)){
            $this->setMsgs('保存失败');
            return false;
        }
        //写入纪录
        if($input['conf_key'] <> $rowsConfig[0]['conf_key'] || $input['conf_value'] <> $rowsConfig[0]['conf_value']){
            $modelConfigUpdateHistory = new Diana_Model_ConfigUpdateHistory();
            if(!$modelConfigUpdateHistory->write($confId,$input['conf_key'],$input['conf_value'],$this->sessionManager)){
                $this->setMsgs('配置历史纪录保存失败');
                //return false;
            }
        }
        return $rowsConfig;
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
     * 更新配置项变量值
     * @param $key
     * @param $val
     */
    function updateValByKey($key,$val)
    {
        if(empty($key)||empty($val)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //查询
        $modelConfig = new Diana_Model_Config();
        if(!$rowConfig = $modelConfig->getRowByKey(null,$key)){
            $this->setMsgs('无效的Key'.$key);
            return false;
        }
        //如果是多选，则要将数组转换为字符
        if($rowConfig['conf_input_type'] == 'checkbox'){
            $val = implode(',',$val);
        }
        //更新
        $data = array(
            'conf_value' => $val,
            'conf_update_time' => time(),
            'conf_update_manId' => $this->sessionManager['id'],
            'conf_update_manName' => $this->sessionManager['name'],
            'conf_update_manEmail' => $this->sessionManager['email'],
            'conf_update_ip' => $_SERVER['REMOTE_ADDR'],
        );
        $condition = array('conf_key' => $key);
        if(!$rowsConfig = $modelConfig->saveData(2,$data,$condition)){
            $this->setMsgs('无效的变量类型');
            return false;
        }
        //如果值发生变化了
        if($val <> $rowConfig['conf_value']){
            $modelConfigUpdateHistory = new Diana_Model_ConfigUpdateHistory();
            if(!$modelConfigUpdateHistory->write($rowConfig['conf_id'],$rowConfig['conf_key'],$val,$this->sessionManager)){
                $this->setMsgs('配置历史纪录保存失败');
                //return false;
            }
        }
        return $rowsConfig;
    }

    /**
     * 删除配置参数
     * @param $configId
     * @return bool
     */
    function deleteById($configId)
    {
        if(empty($configId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //不能删除有下级的配置项
        $modelConfig = new Diana_Model_Config();
        if($rows = $modelConfig->getRowsByFatherId(null,$configId)){
            $this->setMsgs('不能删除仍有下级的配置项！');
            return false;
        }
        //删除数据
        $condition = array('conf_id' => $configId);
        if(!$rowsAffected = $modelConfig->delData($condition)){
            $this->setMsgs('被删除的对象并不存在！');
            return false;
        }
        return $rowsAffected;
    }

    /**
     * 打印参数值变更纪录
     */
    function makeDataGridWithUpdateHistory($page = 1,$pagesize = 20,$condition)
    {
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelConfigUpdateHistory = new Diana_Model_ConfigUpdateHistory();
        $dataGrid['total']  = $modelConfigUpdateHistory->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            $dataGrid['rows'] = $modelConfigUpdateHistory->getRowsByCondition(null,$condition,null,$pagesize,$offset);
        }
        return $dataGrid;
    }

    /**
     * 生成$comboBox所需数据
     */
    function makeComboBoxWithFather()
    {
        $modelConfig = new Diana_Model_Config();
        if(!$rows = $modelConfig->getRowsByFatherId(null,0)){
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
        $modelConfig = new Diana_Model_Config();
        //获取所有数据
        if(!$rows = $modelConfig->getRowsByCondition()){
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
        $modelConfig = new Diana_Model_Config();
        if(!$rows = $modelConfig->getRowsByFatherId(null,$fatherId)){
            return false;
        }
        return $rows;
    }

    /**
     * 获取配置详细信息
     * @param $configId
     * @return bool
     */
    function getDetail($configId = null,$configKey = null)
    {
        if((empty($configId))&&(empty($configKey))){
            return false;
        }
        $modelConfig = new Diana_Model_Config();
        if(!empty($configId)){
            if(!$rows = $modelConfig->getRowsById(null,$configId)){
                $this->setMsgs('无效的参数 - '.$configId.'！');
                return false;
            }
            $row = $rows[0];
        }elseif(!empty($configKey)){
            if(!$row = $modelConfig->getRowByKey(null,$configKey)){
                $this->setMsgs('无效的参数 - '.$configKey.'！');
                return false;
            }
        }

        if(!empty($row['conf_fatherId'])){
            if($rowsFahter = $modelConfig->getRowsById(null,$row['conf_fatherId'])){
                $row['conf_father'] = $rowsFahter[0];
            }
        }
        return $row;
    }

    /**
     * 获取输入类型
     */
    function getInputType()
    {
        return array('input','textarea','select','radio','checkbox');
    }


}