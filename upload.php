<?php

$dir = getcwd();
$file = $dir . "/test.jpeg"; //这里非常重要！一定要绝对地址才行，所以使用这个拼接成了绝对地址

echo $file;


PostFile('up2.php', $file);

function filePost($url, $file){
	// Create a cURL handle
	$ch = curl_init($url);

	// Create a CURLFile object
	$cfile = curl_file_create($file);

	// Assign POST data
	$data = array('fff' => $cfile);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file)); //这句非常重要，告诉远程服务器，文件大小，查到的是前辈的文章http://blog.csdn.net/cyuyan112233/article/details/21015351

	// Execute the handle
	curl_exec($ch);
}


function PostFile($url, $file){

	define("LANGS", "php");
	define("VERSION", "3.1.1");
	define("USERAGENT", LANGS."/".VERSION."/".PHP_OS."/".$_SERVER ['SERVER_SOFTWARE']."/Zend Framework/".zend_version()."/".PHP_VERSION."/".$_SERVER['HTTP_ACCEPT_LANGUAGE']."/");
var_dump(USERAGENT);

	$curl = curl_init();
    curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);

    // 从php5.5开始,反对使用"@"前缀方式上传,可以使用CURLFile替代;
    // 据说php5.6开始移除了"@"前缀上传的方式
    if (class_exists('CURLFile')) {
        // 禁用"@"上传方法,这样就可以安全的传输"@"开头的参数值
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
        $file = new CURLFile($file);
    } else {
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
        $file = "@{$fileName}";
    }
	$fields['fff'] = $file;
    curl_setopt($curl, CURLOPT_BUFFERSIZE, 128);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

	$data = curl_exec($curl);
    var_dump($data);

    if (curl_errno($curl)) {
        return curl_error($curl);
    }

	curl_close($curl);
	return $data;
}