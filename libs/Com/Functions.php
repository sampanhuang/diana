<?php
/**
 * 公用函数
 *
 */
class Com_Functions {

    function filterChr($string)
    {
        $string=str_replace("\r\n",'',$string);//清除换行符
        $string=str_replace("\n",'',$string);//清除换行符
        $string=str_replace("\t",'',$string);//清除制表符
        return $string;
    }

    /**
     * 过滤JS
     * @param $string
     * @return mixed
     */
    function filterJavascript($string)
    {
        $reg="/<script.*?>(\s|.)*?<\/script>/i";
        $siftStr=preg_replace($reg,'',$string);
        return $siftStr;
    }
	/**
	 * 将秒化成天小时分种
	 *
	 * @param int $p
	 * @return array
	 */
	function formatSec($p)
	{
		if ((empty($p))&&(!is_numeric($p))) {return false;}
		$min = floor($p/60);
		$sec = $p%60;
		if($min >= 60){
			$hour = floor($min/60);
			$min = $min%60;
		}
		if($hour >= 24){
			$day = floor($hour/24);
			$hour = $hour%24;
		}
		
		$output = array( 'day' => intval($day),'hour' => intval($hour),'min' => intval($min), 'sec' => intval($sec) );
		return $output;
	}
	/**
	 * 生成GUID
	 *
	 * @param unknown_type $include_braces
	 * @return unknown
	 */
	function makeGuid($include_braces = false) 
	{
	    if (function_exists('com_create_guid')) {
	        if ($include_braces === true) {
	            return com_create_guid();
	        } else {
	            return substr(com_create_guid(), 1, 36);
	        }
	    } else {
	        mt_srand((double) microtime() * 10000);
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	       
	        $guid = substr($charid,  0, 8) . '-' .
	                substr($charid,  8, 4) . '-' .
	                substr($charid, 12, 4) . '-' .
	                substr($charid, 16, 4) . '-' .
	                substr($charid, 20, 12);
	 
	        if ($include_braces) {
	            $guid = '{' . $guid . '}';
	        }
	   
	        return $guid;
	    }
	}
	
	/**
	 * 通过URL获取网页内容
	 *
	 * @param string $url URL
	 * @return string
	 */
	function getContentByUrl($url)
	{
		if (empty($url)||(!is_string($url))) {
			return $url;
		}
		if (!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
			return $url;
		}
	    $ch = curl_init();	
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	    //curl_setopt($ch,CURLOPT_HTTPHEADER,array ("Content-Type: text/xml; charset=utf-8","Expect: 100-continue"));	     
	    $content = curl_exec($ch);
	    return $content;
	}

	/**
	 * 小时
	 *
	 * @return unknown
	 */
	function makeOptionsWithTimeHour()
	{
		$options = array();
		for ($tmpH = 0;$tmpH < 24;$tmpH++){
			$options[$tmpH] = self::zerofill($tmpH,2);
		}
		return $options;
	}
	
	/**
	 * 分钟
	 *
	 * @return unknown
	 */
	function makeOptionsWithTimeMin()
	{
		$options = array();
		for ($tmpM = 0;$tmpM < 60;$tmpM++){
			$options[$tmpM] = self::zerofill($tmpM,2);
		}
		return $options;
	}
	
	/**
	 * 获取毫秒时间
	 *
	 * @return int
	 */
	function getmicrotime()  
	{  
	    list($usec, $sec) = explode(" ",microtime());  
	    return ((float)$usec + (float)$sec);  
	} 
	
	/**
	 * UTF8字符串截取函数
	 *
	 * @param string $string 被截取的字符
	 * @param int $sublen 截取多少个字符
	 * @param int $start 从哪里开始截取
	 * @param string $code
	 * @return string
	 */
	function utf8substr($string, $sublen=50, $start = 0,$code = '..')
	{ 
		$string = strip_tags($string);
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
		preg_match_all($pa, $string, $t_string); 	
		if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)).$code; 
		return join('', array_slice($t_string[0], $start, $sublen)); 
	}
	
	/**
	 * 切割字符串成数组
	 *
	 * @param string $string 字符串
	 * @param int $size 隔几个字符切
	 */
	function sawString2Arr($string,$size)
	{
		if ((empty($string))||(!is_scalar($string))||
		(empty($size))||(!is_numeric($size))) {
			return false;
		}
		$arr = array();
		$len = strlen($string);
		$max = $len/$size;
		for ($i = 0 ; $i<$max ; $i++){
			$start = $i*$size;
			$arr[] = substr($string,$start,$size);
		}
		return $arr;
	}
	
	/**
	 * 输出格式化的文件size
	 *
	 * @param int $bytes 文件大小
	 * @return unknown
	 */
	function formatBytes($bytes) {
		if($bytes >= 1099511627776) {
			$bytes = round($bytes / 1099511627776 * 100) / 100 . 'GB';
		} elseif($bytes >= 1073741824) {
			$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
		} elseif($bytes >= 1048576) {
			$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
		} elseif($bytes >= 1024) {
			$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
		} else {
			$bytes = $bytes . 'Bytes';
		}
		return $bytes;
	}
	
	/**
	 * 将数组换成字符
	 * 这将会应用在缓存KEY上面
	 *
	 * @param array|scalar $keys
	 * @return string
	 */
	function makeCachekey($keys)
	{
		$separtor = '_';
		if ((!empty($keys))&&(is_array($keys))) {
            $keys = md5(json_encode($keys));
			/*foreach ($keys as $k => $v){
				if (!is_null($v)) {
					if (is_scalar($v)) {
						if (is_numeric($v)) {
							$cachekey[] = $k.$separtor.$v;
						}else{
							$cachekey[] = $k.$separtor.md5($v);
						}						
					}elseif (is_array($v)){
						$cachekey[] = $k.Com_Functions::makeCachekey($v);
					}
				}
			}*/
		}
		if (!empty($cachekey)) {
			return $separtor.$keys;
		}
	}
	
	/**
	 * 清除URL最后的斜杠
	 *
	 */
	function clearSprit($str)
	{
		$str = stripslashes($str);//去除反斜杠
		if (substr($str, -1) == "/" || substr($str, -1) == "\\") {
			$str = substr($str, 0, -1); 
			return self::clearSprit($str);
		}else{
			return $str;
		}
	}

    /**
     * 添加http://前缀
     * @param $url
     * @return string
     */
    function UrlAddHttpPre($url)
    {
        $httpsPre = 'https://';
        $httpPre = 'http://';
        if(substr($url,0,strlen($httpPre)) <> $httpPre && substr($url,0,strlen($httpsPre)) <> $httpsPre){
            $url = $httpPre.$url;
        }
        return $url;
    }

    function tagToArray($tag)
    {
        if(empty($tag)){
            return false;
        }
        if(!is_array($tag)){
            //过滤html
            $tag = strip_tags($tag);
            //各种替换，把各种分隔符换成半角逗号
            $tag = str_replace("、",",",$tag);
            $tag = str_replace("，",",",$tag);
            $tag = str_replace(" ",",",$tag);
            $tag = str_replace("|",",",$tag);
            //然后以半角逗号切成数组，然后再过滤重置值与空值
            $tag = explode(",",$tag);//切换成数组
        }
        $tag = array_map('strtolower',$tag);//切换成小写
        $tag = array_map('trim',$tag);//过滤空值
        $tag = array_unique($tag);//过滤重复值
        $tag = array_filter($tag);//过滤空值
        return $tag;
    }

	/**
	 * 循环$replaces，从$haystack中查找$needle，替换成$replaces
	 *
	 * @param array|string|int $replaces 替换值
	 * @param string $haystack 被查找的内容
	 * @param string $needle 替换的对像
	 * @return string
	 */
	function strReplaceOnce( $replaces, $haystack ,$needle = '[#DATA#]') 
	{
		if (is_scalar($replaces)) {
			$replaces = array($replaces);
		}
        $haystack = str_replace('[#DATA-CURRENT-DATETIME#]',date("Y-m-d H:i"),$haystack);
        $haystack = str_replace('[#DATA-CURRENT-DATE#]',date("Y-m-d"),$haystack);
		foreach ($replaces as $replace){
			$pos = strpos($haystack, $needle);
			if ($pos === false) {
				return $haystack;
			}
			$haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
		}
		return $haystack;
	}
	
	function getNameFromEmail($email)
	{
		list($name,$host) = explode('@',$email);
		return $name;
	}
	
	/**
	 * 从邮件中获取邮件的登录地址
	 *
	 * @param string $email 邮箱地址
	 * @param string $pre 前缀
	 */
	function getHostFromEmail($email,$pre = "mail")
	{
		list($name,$host) = explode('@',$email);
		return $pre.'.'.$host;
	}

    /**
     * 确认邮箱
     * @param $email 邮箱
     * @return bool
     */
    function validEmail($email)
    {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex)
        {
            $isValid = false;
        }
        else
        {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64)
            {
                // local part length exceeded
                $isValid = false;
            }
            else if ($domainLen < 1 || $domainLen > 255)
            {
                // domain part length exceeded
                $isValid = false;
            }
            else if ($local[0] == '.' || $local[$localLen-1] == '.')
            {
                // local part starts or ends with '.'
                $isValid = false;
            }
            else if (preg_match('/\\.\\./', $local))
            {
                // local part has two consecutive dots
                $isValid = false;
            }
            else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
            {
                // character not valid in domain part
                $isValid = false;
            }
            else if (preg_match('/\\.\\./', $domain))
            {
                // domain part has two consecutive dots
                $isValid = false;
            }
            else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                    str_replace("\\\\","",$local)))
            {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                    str_replace("\\\\","",$local)))
                {
                    $isValid = false;
                }
            }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
          // domain not found in DNS
          $isValid = false;
      }
   }
        return $isValid;
    }
	
	/**
	 * 自动补零
	 *
	 */
	function zerofill($int,$len)
	{
		$intlen = strlen($int);
		$diff = $len - $intlen;
		if ($diff > 0) {
			$filler = '';
			for ($i = 1 ;$i<=$diff;$i++){
				$filler .= '0';
			}
			$int = $filler.$int;
		}
		return $int;
	}
	
	/**
	 * Recursive function to scan a directory with * scandir() *
	 *
	 * @param String $rootDir
	 * @return multi dimensional array
	 */
	function scanDirectories($rootDir) 
	{
	    // set filenames invisible if you want 
	    $invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd");
	    // run through content of root directory
	    $dirContent = scandir($rootDir);
	    $allData = array();
	    // file counter gets incremented for a better 
	    $fileCounter = 0;
	    foreach($dirContent as $key => $content) {
	        // filter all files not accessible
	        $path = $rootDir.'/'.$content;
	        if(!in_array($content, $invisibleFileNames)) {
	            // if content is file & readable, add to array
	            if(is_file($path) && is_readable($path)) {
	                $tmpPathArray = explode("/",$path);
	                // saving filename
	                $allData[$fileCounter]['fileName'] = end($tmpPathArray);
	                // saving while path (for better access)
	                $allData[$fileCounter]['filePath'] = $path;
	                // get file extension
	                $filePartsTmp = explode(".", end($tmpPathArray));
	                $allData[$fileCounter]['fileExt'] = end($filePartsTmp);
	                // get file date
	                $allData[$fileCounter]['fileDate'] = filectime($path);
	                // get filesize in byte
	                $allData[$fileCounter]['fileSize'] = filesize($path);
	                $fileCounter++;
	            // if content is a directory and readable, add path and name
	            }elseif(is_dir($path) && is_readable($path)) {
	                $dirNameArray = explode('/',$path);
	                $allData[$path]['dirPath'] = $path;
	                $allData[$path]['dirName'] = end($dirNameArray);
	                // recursive callback to open new directory
	                $allData[$path]['content'] = Com_Functions::scanDirectories($path);
	            }
	        }
	    }
	    return $allData;
	}

	

}