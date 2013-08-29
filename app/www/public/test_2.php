<?php
define('DIANA_MICROTIME_START',microtime_float());
/* Connect to an ODBC database using driver invocation */
$dsn = 'mysql:dbname=utf8_diana;host=127.0.0.1';
$user = 'sampan';
$password = 'gobyfeel';

set_include_path(implode(PATH_SEPARATOR, array(realpath("D:\PhpSourceCode\FrameWrok\ZendFramework-1.12.0-minimal\library"),get_include_path())));
// 建立一个 adapter
require_once 'Zend/Db.php';
$params = array (
    'host'     => '127.0.0.1',
    'username' => 'sampan',
    'password' => 'gobyfeel',
    'dbname'   => 'utf8_diana'
);

$db = Zend_Db::factory('PDO_MYSQL', $params);

// 为所有的Zend_Db_Table对象设定默认的adapter
require_once 'Zend/Db/Table.php';
Zend_Db_Table::setDefaultAdapter($db);


$db = Zend_Db::factory('PDO_MYSQL', $params);
class RoundTable extends Zend_Db_Table {
    protected $_name = 'tb_config';
    protected $_primary = 'conf_id';


}

$table = new RoundTable();

// SELECT * FROM round_table WHERE id = "1"
$row = $table->find(1);

// SELECT * FROM round_table WHERE id IN("1", "2", 3")
$rowset = $table->find(array(1, 2, 3));

print_r($rowset);
define('DIANA_MICROTIME_END',microtime_float());
echo DIANA_MICROTIME_END - DIANA_MICROTIME_START ;

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

