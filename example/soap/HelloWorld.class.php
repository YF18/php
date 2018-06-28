<?php
/**
 +------------------------------------------------------------------------------
 * CreateSoap  
 +------------------------------------------------------------------------------
 * @CreateSoap
 * @fangyuan.qu
 +------------------------------------------------------------------------------
 */
class HelloWorld {
	private $nombre = '';
	
	/**
	 * HelloWorld::__construct() HelloWorld class Constructor.
	 * 
	 * @param string $nombre
	 * @return string
	 **/
	public function __construct($name = 'World') {
		$this->name = $name;
	}
	
	/**
	 * HelloWorld::greet() Greets the World xor $this->name xor $name if $name is not empty.
	 * 
	 * @param string $nombre
	 * @return string
	 **/
	public function greet($name = '') {
		$name = $name?$name:$this->name;
		return 'Hello '.$name.'.';
	}
	
	/**
	 * HelloWorld::servidorEstampillaDeTiempo() Returns server timestamp.
	 * 
	 * @return string 
	 **/
	public function serverTimestamp() {
		return time();
	} 
}

?>