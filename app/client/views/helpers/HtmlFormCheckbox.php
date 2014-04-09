<?php
class Zend_View_Helper_HtmlFormCheckbox{
	
	function HtmlFormCheckbox($name,$options,$checked = array(),$separator = "&nbsp;&nbsp;"){
		$html = array();
		foreach ($options as $value => $label){
			$attName = $name.'[]';
			$attId = $name.'_'.$value;
			$attChecked = '';
			if ((!empty($checked))) {
				if (!is_array($checked)) {$checked = explode(",",$checked);}
				if (in_array($value,$checked)) {
					$attChecked = ' checked="checked" ';
				}
			}			
			$html[] = '<input type="checkbox" '.$attChecked.' name="'.$attName.'" id="'.$attId.'" value="'.$value.'"><label for="'.$attId.'">'.$label.'</label>';
		}
		return implode($separator,$html);
	}
}	