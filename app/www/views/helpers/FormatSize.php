<?php
class Zend_View_Helper_FormatSize{

    function FormatSize($size){
        return Com_Functions::formatBytes($size);
    }
}	