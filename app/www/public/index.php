<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL);
//当前应用的目录
define('DIANA_APP_DIR', realpath(dirname(__FILE__). '/..'));
//载入常量定义文件
include_once( realpath(DIANA_APP_DIR."/../Define.php"));
//设置include目录
set_include_path(implode(PATH_SEPARATOR, array(realpath(DIANA_DIR_LIBS_ZEND),get_include_path())));
/** Zend_Application */
require_once 'Zend/Application.php';
//创建一个应用，并启动运行他
$application = new Zend_Application(DIANA_APP_ENV,DIANA_DIR_DATA_CONFIG . '/boot.ini');
$application->bootstrap()->run();