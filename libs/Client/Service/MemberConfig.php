<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-21
 * Time: 下午11:21
 * To change this template use File | Settings | File Templates.
 */
class Client_Service_MemberConfig extends Client_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }


    /**
     * 更新
     * @param $input
     * @return array|bool
     */
    function update($input)
    {
        return $this->updateValByKey($input['conf_key'],$input['conf_value'],$input['conf_memberId']);
    }

    /**
     * 更新配置项变量值
     * @param $key
     * @param $val
     */
    function updateValByKey($key,$val,$memberId)
    {
        if(empty($key)||empty($val)||empty($memberId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //查询
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rowMemberConfig = $modelMemberConfig->getRowByKey(null,$key)){
            $this->setMsgs('无效的Key'.$key);
            return false;
        }
        //如果是多选，则要将数组转换为字符
        if($rowMemberConfig['conf_input_type'] == 'checkbox'){
            $val = implode(',',$val);
        }
        $configId = $rowMemberConfig['conf_id'];
        //如果存在则更新，不存在则插入
        $modelMemberConfigValue = new Diana_Model_MemberConfigValue();
        if($modelMemberConfigValue->getRowByConfigMember(null,$configId,$memberId)){
            //更新
            $data = array(
                'conf_value' => $val,
                'conf_update_time' => time(),
                'conf_update_ip' => $_SERVER['REMOTE_ADDR'],
            );
            $condition = array('conf_memberId' => $memberId,'conf_id' => $configId);
            if(!$rowsMemberConfigValue = $modelMemberConfigValue->saveData(2,$data,$condition)){
                $this->setMsgs('更新失败');
                return false;
            }
        }else{
            //插入
            $data = array(
                'conf_id' => $configId,
                'conf_memberId' => $memberId,
                'conf_value' => $val,
                'conf_insert_time' => time(),
                'conf_insert_ip' => $_SERVER['REMOTE_ADDR'],
                'conf_update_time' => time(),
                'conf_update_ip' => $_SERVER['REMOTE_ADDR'],
            );
            if(!$rowsMemberConfigValue = $modelMemberConfigValue->saveData(1,$data)){
                $this->setMsgs('插入失败');
                return false;
            }
        }
        //如果值发生变化了
        if($val <> $rowMemberConfig['conf_value']){
            $modelMemberConfigUpdateHistory = new Diana_Model_MemberConfigUpdateHistory();
            if(!$modelMemberConfigUpdateHistory->write($rowMemberConfig['conf_id'],$rowMemberConfig['conf_key'],$val,$memberId)){
                $this->setMsgs('配置历史纪录保存失败');
                return false;
            }
        }
        return $this->getRowByKey($key,$memberId);
    }


    /**
     * 打印参数值变更纪录
     */
    function makeDataGridWithUpdateHistory($page = 1,$pagesize = 20,$condition)
    {
        $dataGrid = array('total' => 0 , 'rows' => array());
        $modelMemberConfigUpdateHistory = new Diana_Model_MemberConfigUpdateHistory();
        $dataGrid['total']  = $modelMemberConfigUpdateHistory->getCountByCondition(null,$condition);
        if($dataGrid['total'] > 0){
            $offset = ($page - 1)*$pagesize;
            if($offset < 0){$offset = 0;}
            $dataGrid['rows'] = $modelMemberConfigUpdateHistory->getRowsByCondition(null,$condition,null,$pagesize,$offset);
        }
        return $dataGrid;
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
    function makeTreeGird($input)
    {
        $treeGrid = array();
        //获取所有数据
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rows = $modelMemberConfig->getRowsByCondition()){
            return false;
        }
        $modelMemberConfigValue = new Diana_Model_MemberConfigValue();
        if($rowsMemberConfigValue = $modelMemberConfigValue->getRowsByMember(null,$input['conf_memberId'])){
            foreach($rows as &$tmpRow_1){
                foreach($rowsMemberConfigValue as $rowMemberConfigValue){
                    if($tmpRow_1['conf_id'] == $rowMemberConfigValue['conf_id']){
                        $tmpRow_1 = array_merge($tmpRow_1,$rowMemberConfigValue);
                    }
                }
            }
        }else{
            foreach($rows as &$tmpRow_2){
                $tmpRow_2['conf_value'] = $tmpRow_2['conf_default'];
            }
        }
        //获取一级配置
        foreach($rows as $key => $tmpRow_3){
            if(empty($tmpRow_3['conf_fatherId'])){
                $tmpRow_3['state'] = 'closed';
                $treeGrid[] = $tmpRow_3;
            }
        }
        foreach($treeGrid as &$rowFather){
            foreach($rows as $tmpRow_4){
                if($rowFather['conf_id'] == $tmpRow_4['conf_fatherId']){
                    $rowFather['children'][] = $tmpRow_4;
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
        if ((empty($input['conf_key']))||(!is_scalar($input['conf_key']))) {
            $this->setMsgs("Invalid Param - conf_key");
            return false;
        }
        if ((empty($input['conf_memberId']))||(!is_scalar($input['conf_memberId']))) {
            $this->setMsgs("Invalid Param - conf_memberId");
            return false;
        }
        if($row = $this->getRowByKey($input['conf_key'],$input['conf_memberId'])){
            if(!empty($row['conf_fatherId'])){
                $row['conf_father'] = $this->getRowById($row['conf_fatherId'],$input['conf_memberId']);
            }
        }
        return $row;
    }

    function getRowById($configId,$memberId)
    {
        if(empty($configId)||empty($memberId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //查询
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rowsMemberConfig = $modelMemberConfig->getRowsById(null,$configId)){
            $this->setMsgs('无效的Id'.$configId);
            return false;
        }
        $rowMemberConfig = $rowsMemberConfig[0];
        $configId = $rowMemberConfig['conf_id'];
        $modelMemberConfigValue = new Diana_Model_MemberConfigValue();
        if($rowMemberConfigValue = $modelMemberConfigValue->getRowByConfigMember(null,$configId,$memberId)){
            $rowMemberConfig = array_merge($rowMemberConfig,$rowMemberConfigValue);
        }
        if(empty($rowMemberConfig['conf_val'])){
            $rowMemberConfig['conf_val'] = $rowMemberConfig['conf_default'];
        }
        return $rowMemberConfig;
    }

    /**
     * 通过KEY得到VAL
     * @param $key
     * @param $memberId
     * @return bool
     */
    function getValByKey($key,$memberId)
    {
        if(!$row = $this->getRowByKey($key,$memberId)){
            return false;
        }
        return $row['conf_value'];
    }

    /**
     * 通过KEY得到一条纪录
     * @param $key
     * @param $memberId
     * @return array|bool
     */
    function getRowByKey($key,$memberId)
    {
        if(empty($key)||empty($memberId)){
            $this->setMsgs('参数不能为空');
            return false;
        }
        //查询
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rowMemberConfig = $modelMemberConfig->getRowByKey(null,$key)){
            $this->setMsgs('无效的Key'.$key);
            return false;
        }
        $configId = $rowMemberConfig['conf_id'];
        $modelMemberConfigValue = new Diana_Model_MemberConfigValue();
        if($rowMemberConfigValue = $modelMemberConfigValue->getRowByConfigMember(null,$configId,$memberId)){
            $rowMemberConfig = array_merge($rowMemberConfig,$rowMemberConfigValue);
        }
        if(empty($rowMemberConfig['conf_value'])){
            $rowMemberConfig['conf_value'] = $rowMemberConfig['conf_value'];
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