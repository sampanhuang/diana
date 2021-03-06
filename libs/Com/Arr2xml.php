<?php
/****
* @PHPVER4.0
*
* @author	emnu
* @ver	--
* @date	12/08/08
*
* use this class to convert from mutidimensional array to xml.
* see example.php file on howto use this class
*
*/

class Com_Arr2xml
{
	var $array = array();
	var $xml = '';

	function __construct($array)
	{
		$this->array = $array;
		
		if(is_array($array) && count($array) > 0)
		{
			$this->struct_xml($array);
		}
		else
		{
			$this->xml .= "no data";
		}
	}

	function struct_xml($array)
	{
		foreach($array as $k=>$v)
		{
			if(is_array($v))
			{
				$tag = ereg_replace('^[0-9]{1,}','data',$k); // replace numeric key in array to 'data'
				$this->xml .= "<$tag>";
				$this->struct_xml($v);
				$this->xml .= "</$tag>";
			}
			else
			{
				$tag = ereg_replace('^[0-9]{1,}','data',$k); // replace numeric key in array to 'data'
				$this->xml .= "<$tag>$v</$tag>";
			}
		}
	}
	
	function get_xml()
	{
		$header = "<?xml version=\"1.0\" encoding=\"utf-8\"?><root>";
		$footer = "</root>";		
		return  $header.$this->xml.$footer;
	}
}
?>