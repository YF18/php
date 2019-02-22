<?php
// 日志
class Logger{
    //
    static public function info($msg, $data=[]){
        //
        if($data){
            $msg=$msg.PHP_EOL.json_encode($data);
        }
        $msg="=>".date("y-m-d H:i:s")."=>".$msg.PHP_EOL;
        echo $msg.PHP_EOL;
        $file=dirname(__FILE__).'/log/'.date("ymdh").'-info.log';
        file_put_contents($file, $msg.PHP_EOL, FILE_APPEND);
    }
    //
    static public function dump($data){
        //
        var_dump($data);
    }
    //
    static public function debug($msg, $data=[]){
        //
        if($data){
            $msg=$msg.PHP_EOL.json_encode($data);
        }
        $msg="=>".date("y-m-d H:i:s")."=>".$msg.PHP_EOL;
        $file=dirname(__FILE__).'/log/'.date("ymdh").'-debug.log';
        file_put_contents($file, $msg.PHP_EOL, FILE_APPEND);
    }
    //
    static public function error($msg, $data=[]){
        //
        if($data){
            $msg=$msg.PHP_EOL.json_encode($data);
        }
        $msg="=>".date("y-m-d H:i:s")."=>".$msg.PHP_EOL;
        $file=dirname(__FILE__).'/log/'.date("ymdh").'-error.log';
        file_put_contents($file, $msg.PHP_EOL, FILE_APPEND);
    }
    //
    static public function warn($msg, $data=[]){
        //
        if($data){
            $msg=$msg.PHP_EOL.json_encode($data);
        }
        $msg="=>".date("y-m-d H:i:s")."=>".$msg.PHP_EOL;
        $file=dirname(__FILE__).'/log/'.date("ymdh").'-warn.log';
        file_put_contents($file, $msg.PHP_EOL, FILE_APPEND);
    }
}