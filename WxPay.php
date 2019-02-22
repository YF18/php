<?php
// 
$wxPay=WxPay::init();
$prepayId=$wxPay::preOrder("JSAPI","O2019555555", 100, "商品名称", "oUpF8uMuAJO_M2pxb1Q9zNjWeS6o");
$minSign=$wxPay::getMinAppSign($prepayId);
//
$prepayId=$wxPay::preOrder("APP","O2019555555", 100, "商品名称", "oUpF8uMuAJO_M2pxb1Q9zNjWeS6o");
$appSign=$wxPay::getAppSign($prepayId);

//
class WxPay{
    //
    private static $instance;
    // config
    private static $appId   = "";
    private static $mchId   = "";
    private static $notify  = "";// https://xx.com/v1/Payment/wxNotify/
    // 
    private function __construct() {
        
    }
    // 
    private function __clone(){
        trigger_error('禁止克隆' ,E_USER_ERROR);
    }
    // 
    public static function init(){
        //
        if(!isset(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }
    // 预下单
    public static function preOrder($type, $order, $price, $title, $openid=""){
        // 统一支付
        $unifiedOrder = new \UnifiedOrder();
        // 应用号
        $unifiedOrder->setParameter("appid", self::$appId);
        // 商户号
        $unifiedOrder->setParameter("mch_id", self::$mchId);
        // 交易类型
        $unifiedOrder->setParameter("trade_type", $type);
        // 通知地址
        $unifiedOrder->setParameter("notify_url", self::$notify);
        // 商品描述
        $unifiedOrder->setParameter("body", $title);
        // 订单号
        $unifiedOrder->setParameter("out_trade_no", $order);
        // 总金额
        $unifiedOrder->setParameter("total_fee", doubleval($price)*100);
        // 用户标识
        if($openid){
            $unifiedOrder->setParameter("openid", $openid);
        }
        // 起始时间
        $unifiedOrder->setParameter("time_start", date('YmdHis'));
        // 结束时间
        $unifiedOrder->setParameter("time_expire", date('YmdHis', strtotime( '+30 Minute')));
        //
        $result=$unifiedOrder->getPrepayResult();
        // 失败
        if($result['return_code']=="FAIL"){
            var_dump($result['return_msg']);
            return 0;
        }
        // 预支付ID
        $prepayId=isset($result['prepay_id'])?$result['prepay_id']:0;
        if($prepayId===0){
            echo "订单已超时";
            return 0;
        }
        //
        return $prepayId;
    }

    // 小程序签名
    public static function getMinSign($prepayId){
        // 小程序
        $minPay =new \MinPay();
        // 设置参数
        $minPay->setParameter("appId", self::$appId);
        $minPay->setParameter("package", "prepay_id={$prepayId}");
        // 获取参数
        $data=$minPay->getPayParam();
        //
        return $data;
    }

    // app sign
    public static function getAppSign($prepayId){
        // 小程序
        $appPay =new \AppPay();
        // 设置参数
        $appPay->setParameter("appId", self::$appId);
        $appPay->setParameter("package", "prepay_id={$prepayId}");
        // 获取参数
        $data=$appPay->getPayParam();
        //
        return $data;
    }
}

/**
 * APP支付
 */
class AppPay extends ApiCommon{
    /**
     * 获取app支付的参数
     * @return string
     */
    public function getPayParam(){
        // 内置补全参数
        $this->createParam();
        //
        return $this->parameters;
    }

    protected function createParam(){
        try {
            // 检测必填参数
            if ($this->parameters["appid"] == null) {
                throw new Exception("缺少统一支付接口必填参数appid！" . "<br>");
            }
            if ($this->parameters["partnerid"] == null) {
                throw new Exception("缺少统一支付接口必填参数partnerid！" . "<br>");
            }
            if ($this->parameters["prepayid"] == null) {
                throw new Exception("缺少统一支付接口必填参数prepayid！" . "<br>");
            }
            // 以下参数自动补全
            $this->parameters["package"] = 'Sign=WXPay';
            $this->parameters["timestamp"] = (string)time();
            $this->parameters["noncestr"] = $this->randomString();
            // 排序
            ksort($this->parameters);
            // 签名
            $this->parameters["sign"] = $this->getSign($this->parameters);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

/**
 * 小程序支付
 */
class MinPay extends  ApiCommon {
    /**
     * 获取app支付的参数
     * @return string
     */
    public function getPayParam(){
        // 内置补全参数
        $this->createParam();
        //
        return $this->parameters;
    }

    protected function createParam(){
        try {
            // 检测必填参数
            if ($this->parameters["appId"] == null) {
                throw new Exception("缺少统一支付接口必填参数appId！" . "<br>");
            }
            if ($this->parameters["package"] == null) {
                throw new Exception("缺少统一支付接口必填参数package！" . "<br>");
            }
            // 以下参数自动补全
            $this->parameters["timeStamp"] = (string)time();
            // 随机字符串
            $this->parameters["nonceStr"] = $this->randomString();
            // 签名
            $this->parameters["signType"] = 'MD5';
            // 排序
            ksort($this->parameters);
            //
            $this->parameters["paySign"] = $this->getSign($this->parameters);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

/**
 * 统一支付
 */
class UnifiedOrder extends ApiCommon{

    /**
     * 构造方法
     * UnifiedOrder constructor.
     */
    function __construct(){
        //设置接口链接
        $this->curlUrl = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //设置curl超时时间
        $this->curlTimeout = 30;
    }

    /**
     * 生成接口参数xml
     */
    protected function createParamXml(){
        try {
            // 检测必填参数
            if ($this->parameters["appid"] == null) {
                throw new Exception("缺少统一支付接口必填参数appid！" . "<br>");
            }
            if ($this->parameters["mch_id"] == null) {
                throw new Exception("缺少统一支付接口必填参数mch_id！" . "<br>");
            }
            if ($this->parameters["notify_url"] == null) {
                throw new Exception("缺少统一支付接口必填参数notify_url！" . "<br>");
            }
            if ($this->parameters["out_trade_no"] == null) {
                throw new Exception("缺少统一支付接口必填参数out_trade_no！" . "<br>");
            }
            if ($this->parameters["body"] == null) {
                throw new Exception("缺少统一支付接口必填参数body！" . "<br>");
            }
            if ($this->parameters["total_fee"] == null) {
                throw new Exception("缺少统一支付接口必填参数total_fee！" . "<br>");
            }
            if ($this->parameters["trade_type"] == null) {
                throw new Exception("缺少统一支付接口必填参数trade_type！" . "<br>");
            }
            // 以下参数自动补全
            // 终端ip
            $this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];
            // 随机字符串
            $this->parameters["nonce_str"] = $this->randomString();
            // 字典序排序
            ksort($this->parameters);
            // 签名
            $this->parameters["sign"] = $this->getSign($this->parameters);
            return $this->arrayToXml($this->parameters);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * 获取预支付交易会话标识
     * @return mixed
     */
    public function getPrepayResult(){
        // 参数转xml
        $xml=$this->createParamXml();
        // 发送请求
        $this->response = $this->postXmlCurl($xml, $this->curlUrl, $this->curlTimeout);
        $this->result = $this->xmlToArray($this->response);
        //
        return $this->result;
    }
}

/**
 * 请求接口
 */
class ApiCommon{
    //
    public $key='agga3204811990020174197419741919';

    //=======【curl超时设置】===================================
    // 请求链接
    public $curlUrl;
    // 超时时间，默认为30秒
    public $curlTimeout= 30;

    //=======【响应设置】===================================
    // 请求参数，类型为关联数组
    public $parameters;
    // 微信返回的响应
    public $response;
    // 返回参数，类型为关联数组
    public $result;

    /**
     * 设置请求参数
     * @param $parameter
     * @param $parameterValue
     */
    public function setParameter($parameter, $parameterValue){
        //
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    /**
     * @param $value
     * @return null
     */
    protected function trimString($value){
        $ret = null;
        if (null != $value) {
            $ret = $value;
            if (strlen($ret) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }

    /**
     * array转xml
     * @param $arr
     * @return string
     */
    protected function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            //$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    protected function xmlToArray($xml){
        // 将XML转为array
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return string
     */
    protected function randomString($length = 32){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function toUrlParams($params){
        $buff = "";
        foreach ($params as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成签名
     * @param $params
     * @return string
     */
    protected function getSign($params){
        // 签名步骤一：按字典序排序参数
        ksort($params);
        $urlParams = $this->toUrlParams($params);
        // 签名步骤二：在string后加入KEY
        $string = $urlParams . "&key=".$this->key;
        // 签名步骤三：MD5加密
        $signature = md5($string);
        // 签名步骤四：所有字符转为大写
        $signature = strtoupper($signature);
        return $signature;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param $xml
     * @param $url
     * @param int $second
     * @return bool|mixed
     */
    public function postXmlCurl($xml, $url, $second = 30){
        // 初始化curl
        $ch = curl_init();
        // 设置超时
        //curl_setopt($ch, CURLOP_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        //
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        // 设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        // 运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            echo "curl出错，错误码:$error" . "<br>";
            return false;
        }
    }
}