<?php
// 工厂类
class Factory {
    // 静态方法
    public static function get($id){
        switch ($id) {
            case 1:
                return new A();
                break;
            case 2:
                return new B();
                break;
            case 3:
                return new C();
                break;    
            default:
                return new D();
                break;
        }
        return new D();
    }
}
// 接口
interface FetchName {
    public function getName();//
}

class A implements FetchName{
    private $name = "AAAAA";
    public function getName(){
        return $this->name;
    }
}

class C implements FetchName{
    private $name = "CCCCC";
    public function getName(){
        return $this->name;
    }
}
class B implements FetchName{
    private $name = "BBBBB";
    public function getName(){
        return $this->name;
    }
}

class D implements FetchName{
    private $name = "DDDDD";
    public function getName(){
        return $this->name;
    }
}

// 调用工厂类中的方法
$f = Factory::get(6);
if($f instanceof FetchName){
  echo  $f->getName();//DDDDD
}
// 
$p=Factory::get(3);
echo $p->getName();//CCCCC