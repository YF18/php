<?php
  
/**
 +------------------------------------------------------------------------------
 * CreateSoap  
 +------------------------------------------------------------------------------
 * @CreateSoap
 * @fangyuan.qu
 +------------------------------------------------------------------------------
 */

require_once 'HelloWorld.class.php';

// Enciende el servidor o despliega WSDL
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') {
	$servidorSoap = new SoapServer('http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF'].'?wsdl');
	$servidorSoap->setClass('HelloWorld');
	$servidorSoap->handle();
}
else {
	require_once 'SoapDiscovery.class.php';

	// Crea el servidor de descubrimiento
	$disco = new SoapDiscovery('HelloWorld','Solsoft_HelloWorld');
    header("Content-type: text/xml");
	if (isset($_SERVER['QUERY_STRING']) && strcasecmp($_SERVER['QUERY_STRING'],'wsdl')==0) {
		echo $disco->getWSDL();
	}
	else {
		echo $disco->getDiscovery();
	}
}

?>