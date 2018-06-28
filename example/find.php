<?php
function findNum($str=''){
	$str=trim($str);
	if(empty($str)){return '';}
	$temp=array('1','2','3','4','5','6','7','8','9','0');
	$result='';
	for($i=0;$i<strlen($str);$i++){
		if(in_array($str[$i],$temp)){
			$result.=$str[$i];
		}
	}
	return $result;
}


$str='wh17';

echo findNum($str);








//$str = "php如何将字 符串中322的字母数字Asf f45d和中文_分割？";
$str="wh17";
$arr = preg_split("/([a-zA-Z0-9]+)/", $str, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
print_r($arr);