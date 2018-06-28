<?php
class SNMP_Wrapper {
    protected $_host;
    protected $_community;
    protected $_version;

    public function __construct($host='localhost',$community='public',$version=1)
    {
        $this->_host = $host;
        $this->_community = $community;
        switch ($version) {
            case 2:
                $this->_version = '2';
                break;
            case 3:
                $this->_version = '3';
            default:
                $this->_version = '';
                break;
        }
    }

    public function __call($func,$args)
    {
        $func = strtolower(preg_replace('/([A-Z])/', '_$1', $func));
        $function = 'snmp' . $this->_version . (empty($this->_version) ? '' : '_') . $func;
        if (function_exists($function)) {
            return call_user_func_array($function, array_merge(array($this->_host,$this->_community),$args));
        }
    }
}

// Testing it
$snmp = new SNMP_Wrapper('localhost','public','2');
print_r($snmp->realWalk('IF'));
?>