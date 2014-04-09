<?php
class Com_Brower {
	function getBrower() {
	    $brower = $_SERVER['HTTP_USER_AGENT'];
	    if(preg_match('/360SE/', $brower)) {
	        $brower = "360se";
	    } elseif (preg_match('/Maxthon/', $brower)) {
	        $brower ="Maxthon";
	    } elseif (preg_match('/Tencent/', $brower)) {
	        $brower = "Tencent Brower";
	    } elseif (preg_match('/Green/',$brower)) {
	        $brower = "Green Brower";
	    }elseif(preg_match('/baidu/', $brower)) {
	        $brower = "baidu";
	    } elseif (preg_match('/TheWorld/', $brower)) {
	        $brower = "The World";
	    } elseif (preg_match('/MetaSr/', $brower)) {
	        $brower = "Sogou Brower";
	    } elseif (preg_match('/Firefox/', $brower)) {
	        $brower = "Firefox";
	    } elseif (preg_match('/MSIE\s6\.0/', $brower)) {
	        $brower ="IE6.0";
	    } elseif (preg_match('/MSIE\s7\.0/', $brower)) {
	        $brower = "IE7.0";
	    } elseif (preg_match('/MSIE\s8\.0/', $brower)) {
	        $brower = "IE8.0";
	    } elseif (preg_match('/MSIE\s9\.0/', $brower)) {
	        $brower = "IE9.0";
	    } elseif (preg_match('/Netscape/', $brower)) {
	        $brower = "Netscape";
	    } elseif (preg_match('/Opera/', $brower)) {
	        $brower ="Opera";
	    } elseif (preg_match('/Chrome/', $brower)) {
	        $brower =  "Chrome";
	    } elseif (preg_match('/Gecko/', $brower)) {
	        $brower ="Gecko";
	    } elseif (preg_match('/Safari/', $brower)) {
	        $brower = "Safari";
	    } else {
	        $brower = "Unknow Browser";
	    }
	    return $brower;
	}
	
	// 获取客户端操作系统信息
	function getOs() {
	    $os = $_SERVER['HTTP_USER_AGENT'];
	    if(preg_match('/NT\s5\.1/', $os)) {
	        $os = "Windows XP";
	    } elseif (preg_match('/NT\s6\.0/', $os)) {
	        $os =  "Windows Vista \ server 2008";
	    } elseif (preg_match('/NT\s5\.2/', $os)) {
	        $os = "Windows Server 2003";
	    } elseif (preg_match('/NT\s5/', $os)) {
	        $os = "Windows 2000";
	    } elseif (preg_match('/NT/', $os)) {
	        $os = "Windows NT";
	    } elseif (preg_match('/NT\s6\.1/', $os)) {
	        $os = "Windows 7";
	    } elseif (preg_match('/Linux/', $os)) {
	        $os = "Linux";
	    } elseif (preg_match('/Unix/', $os)) {
	        $os = "Unix";
	    } elseif (preg_match('/Mac/',$os)) {
	        $os = "Macintosh";
	    } elseif (preg_match('/NT\s6\.1/',$os)) {
	        $os = "Windows 7";
	    } else {
	        $os = "Unknow OS";
	    }
	    return $os;
	}
	
}


