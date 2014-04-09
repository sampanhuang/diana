<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-20
 * Time: 下午4:07
 * To change this template use File | Settings | File Templates.
 */
class Zend_View_Helper_FormatBool{

    function FormatBool($p,$p1,$p2 = ''){
        $return = $p1;
        if(empty($p)){
            $return = $p2;
        }
        return $return;
    }
}