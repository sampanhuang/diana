<?php
class Zend_View_Helper_FormatDate{

    function FormatDate($format,$timestamp){
        if(empty($timestamp)){
            return false;
        }
        return date($format,$timestamp);
    }
}	