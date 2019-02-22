<?php
//
require_once getcwd().'/core/Config.php';
//
use vod\Request\V20170321 as vod;
use OSS\OssClient;
//
class AliHelper{
    //
    private static $instance;
    // client
    private static $vodClient;
    private static $ossClient;
    // config
    private static $regionId = 'cn-beijing';
    private static $accessKeyId="";
    private static $accessKeySecret="";
    private static $ossBucket="";
    // 对象实例
    private function __construct() {
        // 初始化
        self::initClient();
    }
    // 对象复制
    private function __clone(){
        trigger_error('禁止克隆' ,E_USER_ERROR);
    }
    // 单例方法
    public static function init(){
        //
        if(!isset(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }
    // 客户端
    private static function initClient(){
        //
        $profile = DefaultProfile::getProfile(self::$regionId, self::$accessKeyId, self::$accessKeySecret);
        self::$vodClient = new DefaultAcsClient($profile);
        //
        self::$ossClient = new OssClient(self::$accessKeyId, self::$accessKeySecret, "http://oss-".self::$regionId.".aliyuncs.com");
    }
    // 上传视频
    public static function uploadVideo($title, $fileName) {
        //
        try {
            $request = new vod\CreateUploadVideoRequest();
            $request->setTitle($title);       // 视频标题(必填参数)
            $request->setFileName($fileName); // 视频源文件名称，必须包含扩展名(必填参数)
//            $request->setTemplateGroupId("31feb86dff54181dfc02f364523380bd");// 视频转码ID
//            $request->setCateId("1000019374");// 视频分类ID
            return self::$vodClient->getAcsResponse($request);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die("err");
        }
    }
    // 上传文件
    public static function uploadFile($object, $file){
        //
        try {
            $info=self::$ossClient->uploadFile(self::$ossBucket, $object, $file);
            return $info['oss-request-url'];// 对公
            //return "oss://".self::$ossBucket."/{$object}"; //对私
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die("err");
        }
    }
    // 获取文件Url
    public static function signUrl($object){
        return self::$ossClient->ossClient->signUrl(self::$ossBucket, $object, 64800);
    }
}

// 记录器
function log($msg, $file="info"){
    $dir = getcwd()."/log/".date("ymd")."-{$file}.txt";
    file_put_contents($dir, $msg.PHP_EOL, FILE_APPEND);
}

// 错误
function error($msg){
    die($msg);
}

// AliHelp
//require_once $_SERVER["DOCUMENT_ROOT"].'vendor/aliyun/AliHelper.php';

// 文件上传
function file(){
    //
    $sum = count($_FILES);
    if($sum==0){
        error("文件必传");
    }
    // AliHelper
    $aliHelper=AliHelper::init();
    //
    $result=[];
    for ($x = 1; $x <=$sum; $x++) {
        //
        $file= $_FILES["file".$x];
        if(is_null($file)){
            error("参数名称不正确");
        }
        if($file['error']){
            error("文件上传失败");
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $tmp = $file['tmp_name'];
        $fileName=date('YmdHis') . rand(0, 9999) . '.' . $ext;
        $fileUrl=$aliHelper::uploadFile($fileName, $tmp);
        array_push($result, $fileUrl);
        unlink($tmp);
    }
    var_dump($result);
}

// 视频点播
function video(){
    // Json
    $json = json_decode(file_get_contents('php://input'),true);
    //
    $title      = $json['title'];
    $fileName   = $json['fileName'];
    //
    if(!$title){
        error("视频标题必须");
    }
    //
    if(!$fileName){
        error("视频源文件名称必须");
    }
    // AliHelper
    $aliHelper=AliHelper::init();
    $result=$aliHelper::uploadVideo($title, $fileName);
    var_dump($result);
}
