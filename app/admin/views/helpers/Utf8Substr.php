<?php
class Zend_View_Helper_Utf8Substr{
	
	function Utf8Substr($string, $sublen=50, $start = 0,$code = '..'){
		return Com_Functions::utf8substr($string,$sublen,$start,$code);
	}
}	