<?php
function add($a, $b){
    return $a+$b;
}
function test($str){
    return $str;
}
$server = new SoapServer(null, array('uri' => 'http://localhost/dev/'));
//$server->addFunction("add");
//$server->addFunction("test");
$server->addFunction(array('add', 'test'));
$server->addFunction(SOAP_FUNCTIONS_ALL);
$server->handle();