<?php
/**
 * 配置文件
 * User: sampan
 * Date: 13-7-21
 * Time: 下午11:20
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_MemberConfig extends Diana_Service_Abstract
{
    static  $config = array();
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 通过KEy获取value
     * @param $key
     */
    function getValueByKey($key,$memberId)
    {
        //获取前缀
        $arrConfKey = explode("_",$key);
        $preConfKey = $arrConfKey[0];
        $configWithPre = $this->getData($preConfKey,$memberId);
        return $configWithPre[$key];
    }

    /**
     * 获取配置文件
     * @param $pre 前缀
     * @return mixed|array 获取配置文件
     */
    function getData($pre,$memberId)
    {
        if(empty(self::$config[$pre][$memberId])){
            $tmpPath = $this->getPathByPre($pre,$memberId);
            if(file_exists($tmpPath)){//存在的话就要对比是否过期
                $modelConfig = new Diana_Model_Config();
                $dbLastTimeWithConfig = $modelConfig->getLastTime();
                $fileLastTime = filemtime($tmpPath);//文件最后被修改时间
                if($dbLastTimeWithConfig > $fileLastTime){//如果最后修改时间大于文件最后被修改时间，则说明需要重新创建
                    $this->generateData($memberId);
                }
            }else{//不存在就创建
                $this->generateData($memberId);
            }
            self::$config[$pre][$memberId] = include($tmpPath);
        }
        return self::$config[$pre][$memberId];
    }

    /**
     * 生成配置文件
     */
    function generateData($memberId)
    {
        $importConfig = array();//要导入的数组
        //获取所有的配置信息
        $modelMemberConfig = new Diana_Model_MemberConfig();
        if(!$rowsConfig = $modelMemberConfig->getRowsByCondition()){
            return false;
        }
        $modelMemberConfigValue = new Diana_Model_MemberConfigValue();
        if(!$rowsConfigValue = $modelMemberConfigValue->getRowsByCondition(null,array('conf_memberId' => $memberId))){
            return false;
        }
        //合并
        foreach($rowsConfig as &$rowConfig){
            foreach($rowsConfigValue as $rowConfigValue){
                if($rowConfig['conf_id'] == $rowsConfigValue['conf_id']){
                    $rowConfig = array_merge($rowConfig,$rowConfigValue);
                }
            }
        }
        //生成要导入的数组
        foreach($rowsConfig as $rowConfig){
            if((!empty($rowConfig['conf_key']))){
                if(empty($rowConfig['conf_value'])){
                    $rowConfig['conf_value'] = $rowConfig['conf_default'];
                }
                $tmpArrConfKey = explode("_",$rowConfig['conf_key']);
                $tmpPreConfKey = $tmpArrConfKey[0];
                $importConfig[$tmpPreConfKey][$rowConfig['conf_key']] = $rowConfig['conf_value'];
            }
        }
        //写入配置文件
        foreach($importConfig as $pre => $tmpRowsConfig){
            $tmpPath = $this->getPathByPre($pre,$memberId);
            $tmpContent = "<?php return ".var_export($tmpRowsConfig, true).';';
            file_put_contents($tmpPath,$tmpContent);
        }
        return true;
    }

    /**
     * 通过前缀得到他的配置文件
     * @param $pre 前缀
     * @return string 路径
     */
    function getPathByPre($pre,$memberId)
    {
        $path = DIANA_DIR_DATA_MEMBER_CONFIG.'/'.floor($memberId/1000).'/'.$memberId.'/'.$pre.'.php';
        if(!file_exists(dirname($path))){
            @mkdir(dirname($path),0777,true);
        }
        return $path;
    }




}