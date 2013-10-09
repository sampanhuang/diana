<?php
//error_reporting(E_ALL || ~E_NOTICE);
date_default_timezone_set('asia/shanghai');
mb_internal_encoding("UTF-8");//编码
//定义起始时间
define('DIANA_TIMESTAMP_START',time());
define('DIANA_REMOTE_ADDR',$_SERVER['REMOTE_ADDR']);


//define('DIANA_WEBSITE_NAME',"Diana's Website");//网站名称
//define('DIANA_WEBSITE_TITLE',"Diana's Website");//网站名称
define('DIANA_WEBSITE_NAME',"海华门");//网站名称
define('DIANA_WEBSITE_TITLE',"海华门");//网站名称
define('DIANA_WEBSITE_SUBTITLE',"海外华人同出一门");//网站名称

//定义域名常量
define('DIANA_DOMAIN_ROOT',"diana.local");//根域名
define('DIANA_DOMAIN_STATIC',"http://static.".DIANA_DOMAIN_ROOT);//静态
define('DIANA_DOMAIN_WWW',"http://www.".DIANA_DOMAIN_ROOT);//客人
define('DIANA_DOMAIN_ADMIN',"http://admin.".DIANA_DOMAIN_ROOT);//主人
define('DIANA_DOMAIN_CURRENT',$_SERVER['SERVER_NAME']);


define('DIANA_DOMAIN_WWW_CN',"http://cn.".DIANA_DOMAIN_ROOT);//简体中文
define('DIANA_DOMAIN_WWW_TW',"http://tw.".DIANA_DOMAIN_ROOT);//繁体中文
//define('DIANA_DOMAIN_WWW_EN',"http://en.".DIANA_DOMAIN_ROOT);//英文

define('DIANA_WEBSITE_VERSION','beate 1');//网站版本



//定义目录及路径常量
define('DIANA_DIR_ROOT', dirname(__FILE__));//根目录
define('DIANA_DIR_LIBS', realpath(DIANA_DIR_ROOT."/../libs"));//库目录
define('DIANA_DIR_WWW_PUBLIC', realpath(DIANA_DIR_ROOT."/www/public"));//库目录
define('DIANA_DIR_ADMIN_PUBLIC', realpath(DIANA_DIR_ROOT."/admin/public"));//库目录

define('DIANA_DIR_LIBS_ZEND', realpath("D:/PhpSourceCode/FrameWrok/ZendFramework-1.12.0-minimal/library"));//zend框架目录

define('DIANA_DIR_DATA', DIANA_DIR_ROOT."/../data");//数据目录
define('DIANA_DIR_DATA_CONFIG', DIANA_DIR_DATA."/configs");//配置文件目录
define('DIANA_DIR_DATA_STATE', DIANA_DIR_DATA."/state");//配置文件目录
define('DIANA_DIR_DATA_TEMPLATE', DIANA_DIR_DATA."/template");//模板目录
define('DIANA_DIR_DATA_TRANSLATE', DIANA_DIR_DATA."/translate");//语言包目录
define('DIANA_DIR_DATA_FONT', DIANA_DIR_DATA."/font");//字体目录
define('DIANA_DIR_DATA_IPLIBS', DIANA_DIR_DATA."/iplibs");//IP数据库目录
define('DIANA_PATH_DATA_IPLIBS', DIANA_DIR_DATA_IPLIBS."/qqwry_".rand(1,5).".dat");//IP数据库路径，防文件锁定排队，所以有多个
define('DIANA_DIR_UPLOAD', DIANA_DIR_ROOT."/www/public/upload");//文件上传路径

define('DIANA_DIR_TEMP', DIANA_DIR_ROOT."/../temp");//临时目录
define('DIANA_DIR_TEMP_SESSION', DIANA_DIR_TEMP."/session");//临时目录session
define('DIANA_DIR_TEMP_LOG', DIANA_DIR_TEMP."/logs");//临时目录日志
define('DIANA_DIR_TEMP_CACHE', DIANA_DIR_TEMP."/cache");//临时缓存文件
define('DIANA_DIR_TEMP_CAPTCHA', DIANA_DIR_TEMP."/captcha");//临时验证码图片
define('DIANA_DIR_TEMP_DOWN', DIANA_DIR_TEMP."/down");//下载目录


//定义当前环境
define('DIANA_DEBUG_ENABLE',true);
define('DIANA_DEBUG_LABEL',"<BR>");
define('DIANA_TODAY',intval(date("Ymd")));
define('DIANA_APP_ENV', (getenv('DIANA_APP_ENV') ? getenv('DIANA_APP_ENV') : 'production'));//当前应用所处的环境

//定义标识
define('DIANA_TAG_SESSIONNAMESPAN_CUSTOM',"custom".md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']));//个性化标签
define('DIANA_TAG_SESSIONNAMESPAN_MANAGER',"Diana_Back_Manager");//存入后台管理员登录后的会话数据标签
define('DIANA_TAG_SESSIONNAMESPAN_MEMBER',"Diana_Back_Member");//存入后台管理员登录后的会话数据标签
define('DIANA_TAG_SESSIONNAMESPAN_CAPTCHA_MANAGERLOGIN',"DDiana_Back_ManagerLogin");//存入后台管理员登录后的会话数据标签
define('DIANA_TAG_SESSIONNAMESPAN_PLAYER',"Diana_Font_Player");//存入前台玩家会员登录后的会话数据标签
define('DIANA_TAG_SESSIONNAMESPAN_CAPTCHA_PLAYERLOGIN',"Diana_Font_Captcha_PlayerLogin");//存放会员登录验证码的会话数据标签
define('DIANA_TAG_SESSIONNAMESPAN_CAPTCHA_PLAYERREGISTER',"Diana_Font_Captcha_MemberRegister");//存放会员注册验证码的会话数据标签

//全站跨接器安全私钥
define('DIANA_JUMPER_PRIVATEKEY',"lenovo2012#!$!%(@^");

//当前使用的语言
/*
if (strtolower($_SERVER['SERVER_NAME']) == strtolower(DIANA_DOMAIN_WWW_CN)) {
	define('DIANA_TRANSLATE_DEFAULT',"zh-cn");
}elseif (strtolower($_SERVER['SERVER_NAME']) == strtolower(DIANA_DOMAIN_WWW_TW)){
	define('DIANA_TRANSLATE_DEFAULT',"zh-tw");
}else{
	if (in_array(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']),array("zh-cn","zh-tw"))) {
		define('DIANA_TRANSLATE_DEFAULT',strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']));
	}else{
		define('DIANA_TRANSLATE_DEFAULT',"zh-cn");
	}	
}
*/
define('DIANA_TRANSLATE_DEFAULT',"zh-cn");
define('DIANA_DIR_DATA_TRANSLATE_CURRENT', DIANA_DIR_DATA_TRANSLATE."/".DIANA_TRANSLATE_DEFAULT);//语言包目录

include_once("Environment.php");