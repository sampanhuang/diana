<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-12
 * Time: 下午8:30
 * To change this template use File | Settings | File Templates.
 */
class Admin_Service_IpToAddress
{
    /**
     * IP转换成为地址
     */
    function updateAddress()
    {
        $logFile = DIANA_DIR_TEMP_CLI.'/'.date('Y-m-d_').'IpToAddress.txt';
        $logContent = array(str_repeat('-',100),date('Y-m-d H:i:s').chr(9).$_SERVER['SERVER_ADDR']);
        //获取DB
        $db = Zend_Db_Table::getDefaultAdapter();
        //获取配置
        $configWithTableColumns = $this->getConfigWithTableColumns();
        //获取转换对象
        $ips = array();
        $comIpLocation = new Com_IpLocation(DIANA_PATH_DATA_IPLIBS);
        foreach($configWithTableColumns as $tableName => $columns){
            $logContent[] = $tableName;
            foreach($columns as $columnIp => $columnAddress){
                $logCounter = 0;

                $tmpSqlUpdate = null;
                $tmpSqlSelect = "select {$columnIp} from {$tableName} where ( {$columnIp} <> '' or {$columnIp} is not null ) and ( {$columnAddress} = '' or {$columnAddress} is null ) group by {$columnIp} limit 1000";
                if($result = $db->query($tmpSqlSelect)){
                    if($rows = $result->fetchAll()){
                        foreach($rows as $row){
                            $tmpIp = $row[$columnIp];
                            if(!empty($tmpIp)){
                                if(empty($ips[$tmpIp])){
                                    if($tmpAddress = $comIpLocation->getaddressutf($tmpIp)){
                                        $ips[$tmpIp] = $tmpAddress['area1'];
                                        if(!empty($tmpAddress['area2'])){
                                            $ips[$tmpIp] .= '--'.$tmpAddress['area2'];
                                        }
                                    }
                                }
                                $set = array($columnAddress => $ips[$tmpIp]);
                                $where = $db->quoteInto( $columnIp.' = ? ', $tmpIp );
                                $rowsAffected = $db->update($tableName, $set, $where);
                                $logCounter = $logCounter + $rowsAffected;
                            }
                        }
                    }
                }
                $logContent[] = chr(9).$columnIp.chr(9).'=>'.chr(9).$columnAddress.chr(9).chr(9).$logCounter.' row(s) affected';
            }
        }
        file_put_contents($logFile,implode(chr(9).chr(10),$logContent).chr(9).chr(10),FILE_APPEND );
    }

    /**
     * 获取配置
     */
    function getConfigWithTableColumns()
    {
        $tableColumns = array(
            'tb_bulletin' => array(//表名
                'bulletin_insert_ip' => 'bulletin_insert_addr',//哪个字段的IP变成哪个字段的addr
                'bulletin_update_ip' => 'bulletin_update_addr',
            ),
            'tb_config' => array(
                'conf_insert_ip' => 'conf_insert_addr',
                'conf_update_ip' => 'conf_update_addr',
                'conf_alter_ip' => 'conf_alter_addr',
            ),
        );
        return $tableColumns;
    }
}
