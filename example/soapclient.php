<?php 
$arr_options = array('uri' => 'http://localhost/dev/', 'location' => 'http://localhost/dev/soapserver.php', 'trace' => true);
$soap = new SoapClient(null, $arr_options);
echo $soap->add(20, 30);
echo $soap->test('caleng');