<?php
/**
 * 把秒数转换
 *
 */
class Zend_View_Helper_FormatSecond{

    function FormatSecond($sec,$format = null)
    {
        if (!$output = Com_Functions::formatSec($sec)) {
            return false;
        }
        $return = array();
        if (empty($format)) {
            $format = array('day' => '天','hour' => '小时','min' => '分钟','sec' => '秒');
        }
        foreach($output as $k => $v){
            if($k == 'day' && $v > 0){
                $return[] = $v.$format['day'];
            }elseif($k == 'hour' && $v > 0){
                $return[] = $v.$format['hour'];
            }elseif($k == 'min' && $v > 0){
                $return[] = $v.$format['min'];
            }elseif($k == 'sec' && $v > 0){
                $return[] = $v.$format['sec'];
            }
        }
        return implode('',$return);
    }
}