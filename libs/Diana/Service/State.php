<?php
/**
 * 配置文件
 * User: sampan
 * Date: 13-7-21
 * Time: 下午11:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_State extends Diana_Service_Abstract
{
    static  $state = array();
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $key 键
     * @param $value 值
     * @param $isFull 是否为全改，真为全改，假为增量
     */
    function setValueByKey($key,$value,$isFull = null)
    {
        $modelState = new Diana_Model_State();
        if(!$rowsState =  $modelState->setValueByKey($key,$value,$isFull)){
            $this->setMsgs($key.'状态写入失败!');
            return false;
        }
        return $rowsState[0]['state_value'];
    }

    /**
     * 通过KEy获取value
     * @param $key
     */
    function getValueByKey($key,$default)
    {
        //获取前缀
        $value = $default;
        $arrConfKey = explode("_",$key);
        $preConfKey = $arrConfKey[0];
        $stateWithPre = $this->getState($preConfKey);
        if(!is_null($stateWithPre[$key])){
            $value = $stateWithPre[$key];
        }
        return $value;
    }

    /**
     * 获取配置文件
     * @param $pre 前缀
     * @return mixed|array 获取配置文件
     */
    function getState($pre)
    {
        if(empty(self::$state[$pre])){
            $tmpPath = $this->getPathByPre($pre);
            if(file_exists($tmpPath)){//存在的话就要对比是否过期
                $modelState = new Diana_Model_State();
                $dbLastTimeWithState = $modelState->getLastTime();
                $fileLastTime = filemtime($tmpPath);//文件最后被修改时间
                if($dbLastTimeWithState > $fileLastTime){//如果最后修改时间大于文件最后被修改时间，则说明需要重新创建
                    $this->generateData();
                }
            }else{//不存在就创建
                $this->generateData();
            }
            self::$state[$pre] = include($tmpPath);
        }
        return self::$state[$pre];
    }

    /**
     * 生成配置文件
     */
    function generateData()
    {
        $importState = array();//要导入的数组
        //获取所有的配置信息
        $modelState = new Diana_Model_State();
        if(!$rowsState = $modelState->getRowsByCondition()){
            return false;
        }
        //生成要导入的数组
        foreach($rowsState as $rowState){
            if((!empty($rowState['state_key']))&&(!empty($rowState['state_value']))){
                $tmpArrConfKey = explode("_",$rowState['state_key']);
                $tmpPreConfKey = $tmpArrConfKey[0];
                $importState[$tmpPreConfKey][$rowState['state_key']] = $rowState['state_value'];
            }
        }
        //写入配置文件
        foreach($importState as $pre => $tmpRowsState){
            $tmpPath = $this->getPathByPre($pre);
            $tmpContent = "<?php return ".var_export($tmpRowsState, true).';';
            file_put_contents($tmpPath,$tmpContent);
        }
        return true;
    }

    /**
     * 通过前缀得到他的配置文件
     * @param $pre 前缀
     * @return string 路径
     */
    function getPathByPre($pre)
    {
        return DIANA_DIR_DATA_STATE.'/'.$pre.'.php';;
    }




}