<?php
function getPageContent($url) {   
           
        //$url='http://www.phplover.cn';   
           
        $pageinfo = array();   
        $pageinfo[content_type] = '';   
        $pageinfo[charset] = '';   
        $pageinfo[title] = '';   
        $pageinfo[description] = '';   
        $pageinfo[keywords] = '';   
        $pageinfo[body] = '';   
        $pageinfo['httpcode'] = 200;   
        $pageinfo['all'] = '';   
           
  
           
           
        $ch = curl_init();   
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);   
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);   
        curl_setopt($ch, CURLOPT_FILETIME, 1);   
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   
        //curl_setopt($ch, CURLOPT_HEADER, 1);         
        curl_setopt($ch, CURLOPT_URL,$url);   
           
        $curl_start = microtime(true);   
        $store = curl_exec ($ch);   
           
        $curl_time = microtime(true) - $curl_start;   
        if( curl_error($ch) ) {   
            $pageinfo['httpcode'] = 505;  //gate way error   
            echo 'Curl error: ' . curl_error($ch) ."\n";   
            return $pageinfo;   
        }   
           
        //print_r(curl_getinfo($ch));   
        $pageinfo['httpcode'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);   
        //echo curl_getinfo($ch,CURLINFO_CONTENT_TYPE)."\n";   
        $pageinfo[content_type] = curl_getinfo($ch,CURLINFO_CONTENT_TYPE);   
        if(intval($pageinfo['httpcode']) <> 200 or !preg_match('@text/html@',curl_getinfo($ch,CURLINFO_CONTENT_TYPE) )   ) {   
                //print_r(curl_getinfo($ch) );   
                //exit;   
                return $pageinfo;   
        }   
        preg_match('/charset=([^\s\n\r]+)/i',curl_getinfo($ch,CURLINFO_CONTENT_TYPE),$matches); //从header 里取charset   
        if( trim($matches[1]) ) {   
            $pageinfo[charset] = trim($matches[1]);   
        }   
        //echo $pageinfo[charset];   
        //exit;   
        curl_close ($ch);   
        //echo $store;   
  
  
        //remove javascript   
        $store = preg_replace("/<script.*>(.*)<\/script>/smUi",'',$store);   
        //remove link    
        $store = preg_replace("/<link\s+[^>]+>/smUi",'',$store);   
        //remove <!--  -->   
        $store = preg_replace("/<!--.*-->/smUi",'',$store);   
        //remove <style  </<style>   
        $store = preg_replace("/<style.*>(.*)<\/style>/smUi",'',$store);   
        //remove 中文空格   
        $store = preg_replace("/　/",'',$store);   
        //remove 标点符号   
        //$store = preg_replace("/[\~`!@#$%^&*()_\-+={}|\[\]\\;':"\<\>\?\,\.\/]/",'',$store);   
  
           
        //preg_match("/<head.*>(.*)<\/head>/smUi",$store, $matches);   
        //$head = $matches[1];   
        //echo $head. "\n";   
  
        //charset   
        if($pageinfo[charset] == '' ) {  
            preg_match('@<meta.+charset=([\w\-]+)[^>]*>@i',$store,$matches);  
            $pageinfo[charset] = trim($matches[1]);  
        }  
        //desctiption  
        preg_match('@<meta\s+name=\"*description\"*\s+content\s*=\s*([^/>]+)/*>@i',$store,$matches);  
        //print_r($matches);  
        $desc = trim($matches[1]);  
        $pageinfo[description] = str_replace("\"", '',$desc);  
          
 
        preg_match('@<meta\s+name=\"*keywords\"*\s+content\s*=\s*([^/>]+)/*>@i',$store,$matches);  
        //print_r($matches);  
        $keywords = trim($matches[1]);  
        $pageinfo[keywords] = str_replace("\"", '',$keywords);  
 
          
        preg_match("/<title>(.*)<\/title>/smUi",$store, $matches);  
        $pageinfo[title] = trim($matches[1]);  
          
        preg_match("/<body.*>(.*)<\/body>/smUi",$store, $matches);  
        $pageinfo[body] = addslashes( replaceHtmlAndJs($matches[1]) ) ;  
        $pageinfo['all'] = addslashes( replaceHtmlAndJs($store) ) ;  
          
        //echo "charset = " . $pageinfo[charset] . "\n";  
          
        //print_r($pageinfo);  
        //exit;  
          
          
        return $pageinfo;  
 
}  
 
/**  
 * 去掉所有的HTML标记和JavaScript标记  
 */  
function replaceHtmlAndJs($document)   
{   
         $document = trim($document);   
         if (strlen($document) <= 0)   
         {   
          return $document;   
         }   
         $search = array (         
                                            "'<script[^>]*?>.*?</script>'si",  // 去掉 javascript   
                          "'<[\/\!]*?[^<>]*?>'si",          // 去掉 HTML 标记   
                          "'[\r\n\s+]'",                // 去掉空白字符   
                          "'&(\w+);'i"              // 替换 HTML 实体   
                         );                    // 作为 PHP 代码运行   
           
         $replace = array ( "", "", "", ""  );   
           
         return @preg_replace ($search, $replace, $document);   
  
}  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>
<pre>
<?php
$a = getPageContent("http://www.phplover.cn");
print_r($a);
?>
    </pre>
</body>
</html>
