<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-12
 * Time: 下午9:31
 * To change this template use File | Settings | File Templates.
 */
$i = 1;
while ($i <= 2) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt ($ch, CURLOPT_URL, 'http://admin.diana.local/task-scheduler/default/ip-to-address');
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
    //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $contents = curl_exec($ch);
    curl_close($ch);
    echo $contents;
    $i++;
}


