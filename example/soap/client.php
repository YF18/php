<?php
/**
 +------------------------------------------------------------------------------
 * CreateSoap  
 +------------------------------------------------------------------------------
 * @CreateSoap
 * @fangyuan.qu
 +------------------------------------------------------------------------------
 */

//$client = new SoapClient(null,array('location'=>'http://localhost/createsoap/HelloWorld.class.php','uri'=>'http://localhost/createsoap/HelloWorld.class.php'));


$client = new SoapClient("http://127.0.0.1/createsoap/hello.wsdl");

try {
        $result = $client->greet('ieliwb');
		var_dump($result);
        echo "The answer isresult";
}
catch (SoapFault $f){
        echo "Error Message: {$f->getMessage()}";
}


?>