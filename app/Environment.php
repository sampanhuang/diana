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
			if (!file_exists($constantValue)){				
				mkdir($constantValue,0777,true);
			}
		}
	}
}
define('DIANA_MICROTIME_START',microtime_float());

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
