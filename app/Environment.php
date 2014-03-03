<?php
//遍历常量
foreach (get_defined_constants() as $constantName => $constantValue){
	$tmpConstantName = explode('_',$constantName);//常量名数组	
	$countConstantName = count($tmpConstantName);//被分隔符“_”分成了几截
	$prefixConstantName = $tmpConstantName[0];//常量名第一前缀	
	if ($prefixConstantName == 'DIANA'){
		$prefixSecondConstantName = $tmpConstantName[1];//常量名第二前缀
		$suffixConstantName = $tmpConstantName[($countConstantName - 1)];//常量名后缀
		if ($prefixSecondConstantName == 'DIR'){//生成程序运行必须带有的目录
            if(!empty($constantValue)){
                if (!file_exists($constantValue)){
                    mkdir($constantValue,0777,true);
                }
            }
		}
	}
}
//微秒开始值
define('DIANA_MICROTIME_START',microtime_float());

//设置当前语言
if(in_array($_SERVER["HTTP_HOST"],array(DIANA_DOMAIN_ADMIN_US,DIANA_DOMAIN_CLIENT_US,DIANA_DOMAIN_WWW_US))){
    $tmpTranslateCurrent = 'en-us';
}elseif(in_array($_SERVER["HTTP_HOST"],array(DIANA_DOMAIN_ADMIN_TW,DIANA_DOMAIN_CLIENT_TW,DIANA_DOMAIN_WWW_TW))){
    $tmpTranslateCurrent = 'zh-tw';
}elseif(in_array($_SERVER["HTTP_HOST"],array(DIANA_DOMAIN_ADMIN_CN,DIANA_DOMAIN_CLIENT_CN,DIANA_DOMAIN_WWW_CN))){
    $tmpTranslateCurrent = 'zh-cn';
}else{
    if (strpos(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']) , 'zh-tw') !== false) {
        $tmpTranslateCurrent = 'zh-tw';
    }elseif(strpos(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']) , 'zh-cn') !== false){
        $tmpTranslateCurrent = 'zh-cn';
    }else{
        $tmpTranslateCurrent = 'en-us';
    }
}

if($tmpTranslateCurrent == 'en-us'){
    define('DIANA_DOMAIN_WWW_CURRENT',DIANA_DOMAIN_WWW_US);
    define('DIANA_DOMAIN_ADMIN_CURRENT',DIANA_DOMAIN_ADMIN_US);
    define('DIANA_DOMAIN_CLIENT_CURRENT',DIANA_DOMAIN_CLIENT_US);
}elseif($tmpTranslateCurrent == 'zh-tw'){
    define('DIANA_DOMAIN_WWW_CURRENT',DIANA_DOMAIN_WWW_TW);
    define('DIANA_DOMAIN_ADMIN_CURRENT',DIANA_DOMAIN_ADMIN_TW);
    define('DIANA_DOMAIN_CLIENT_CURRENT',DIANA_DOMAIN_CLIENT_TW);
}else{
    define('DIANA_DOMAIN_WWW_CURRENT',DIANA_DOMAIN_WWW_CN);
    define('DIANA_DOMAIN_ADMIN_CURRENT',DIANA_DOMAIN_ADMIN_CN);
    define('DIANA_DOMAIN_CLIENT_CURRENT',DIANA_DOMAIN_CLIENT_CN);
}
define('DIANA_TRANSLATE_CURRENT',$tmpTranslateCurrent);//zh-cn 简体中文件 zh-tw 繁体中文 en-us 英文
define('DIANA_DIR_DATA_TRANSLATE_CURRENT', DIANA_DIR_DATA_TRANSLATE."/".DIANA_TRANSLATE_CURRENT);//语言包目录

//获取秒数
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}