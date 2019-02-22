<?php
class Single {
    //
    public static $instance;
    //
    private static $sex=16;
    //
    private $name;
    //
    private function __construct(){}
    //
    public static function getinstance(){
        if(!self::$instance) self::$instance = new self();
        return self::$instance;
    }
    //
    public function setName($name){
        $this->name = $name;
    }
    //
    public function getName(){
        return $this->name;
    }
    //
    public function getSex(){
        return self::$sex;
    }
}
//
$single = Single::getinstance();
$single = Single::getinstance();
$single->setName('hello world');
$single->setName('good morning');
echo $single->getName();//good morning
echo $single->getName();//good morning
echo $single->getSex();//16