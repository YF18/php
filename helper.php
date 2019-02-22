<?php
// $abc="EduAddressAsdd";
// var_dump(toLine($abc));
// 转下划线
function toLine($camelCaps, $separator='_') {
   return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}
// 
function postFile($url, $file){
	// Create a cURL handle
	$ch = curl_init($url);

	// 从php5.5开始,反对使用"@"前缀方式上传,可以使用CURLFile替代;
    // 据说php5.6开始移除了"@"前缀上传的方式
    if (class_exists('CURLFile')) {
        // Create a CURLFile object
		$cfile = curl_file_create($file);
    } else {
        $file = "@{$fileName}";
    }

	// Assign POST fields
	$fields = ['file' => $cfile];
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));

	$data = curl_exec($ch);
    var_dump($data);
    
    // errno
    if (curl_errno($curl)) {
        return curl_error($curl);
    }

	// Execute the handle
	curl_exec($ch);

	return $data;
}