<?php

set_error_handler(array("Common\Lib\ExceptionHandler","handleError"));
set_exception_handler(array("Common\Lib\ExceptionHandler","handleException"));
register_shutdown_function(array("Common\Lib\ExceptionHandler","handleShutdown"));
/**
 * 数据发送
 * @param string|array $messages
 * @param int $code
 * @param bool|false $crypt
 */
function sendMsg( $messages, $code = 10000, $crypt = false){
    $crypt = !is_int($code) ? $code : $crypt;
    $code = !is_int($code) ? 10000 : $code;
    $messages = $crypt ? rsa_encode($messages) : $messages;
    if (is_string($messages)){
        $messages = ['message'=>$messages];
    }
    $messages = [
        "send" => $code == 10000 ? "OK" : "ERROR",
        "messages" => [$messages],
        "code" => $code,
        "crypt" => $crypt,
        "time" => time(),
    ];
    $sign = _sign($messages);
    $messages['sign'] = $sign;
    ksort($messages);
    $messages = json_encode($messages,JSON_UNESCAPED_UNICODE);
    header("Content-type: ". "application/json" ); //类型
    die($messages);
}

/**
 * 生成Token
 * @param string $mobile
 * @return string
 */
function setToken($mobile){
    return strtoupper(md5($mobile.microtime()));
}

/**
 * 检查验证码
 * @param $loginMobile
 * @param $verifyCode
 * @param $delete
 * @return bool
 */
function checkVerify($loginMobile,$verifyCode,$delete = true){
    if(getRedis($loginMobile)){
        if((int)$verifyCode === (int)getRedis($loginMobile)){
            if($delete)
                delRedis($loginMobile);
            return true;
        }else{
            return false;
        }
    }
    sendMsg('验证码已失效!',10001);
    return false;
}

/**
 * 生成订单号
 * @param $orderId
 * @return string
 */
function setOrderNum($orderId){
    $orderLen = strlen($orderId);
    $diff = 10-$orderLen;
    $orderNum = '6';
    for($i=0;$i<$diff;$i++){
        $orderNum .= 0;
    }
    $orderNum .= $orderId;
    return (string)$orderNum;
}

/**
 * 生成验证码
 * @param int $length
 * @return string
 */
function setVerifyCode($length = 6){
    $length = (int)$length<4?4:(int)$length;
    $verifyCode = '';
    for($i=0;$i<$length;$i++){
        $verifyCode.=(string)rand(0,9);
    }
    return $verifyCode;
}

/**
 * 设置Log
 * @param string $messages
 * @param string $file
 * @param string $dir
 */
function setLog( $messages, $file = "system", $dir = "log"){
    if(is_array($messages)){
        $messages = (string)array_to_string($messages);
    }
    $dir = BASE_PATH."/logs/".$dir;
    if(!is_dir($dir)){
        @mkdir($dir,0655);
    }
    $fp = fopen($dir."/".$file.'.log',"a+") or die("Wrong!");
    fwrite($fp,date("Y-m-d H:i:s")." ".$messages."\r\n");
    fclose($fp);
}

/**
 * 设置Redis
 * @param string $key
 * @param string $value
 * @param int $timeout
 * @return bool
 */
function setRedis( $key, $value, $timeout = 1800){
    RedisL::setex($key,$timeout,$value);
    return true;
}

/**
 * 获取Redis
 * @param $key
 * @return bool|string
 */
function getRedis($key){
    $value = RedisL::get($key);
    return empty($value)?false:$value;
}

/**
 * 删除Redis
 * @param $key1
 * @param null $key2
 * @param null $key3
 * @return int
 */
function delRedis( $key1, $key2 = null, $key3 = null){
    return RedisL::del( $key1, $key2, $key3);
}

/**
 * 发送短信(验证码)
 * @param string $mobile
 * @param string $code
 * @param string $type
 * @return bool
 */
function sendSMS($mobile, $code, $type=0){
    $params = getTemplate($type,$code);
    if(!$params)
        return false;
    $SmsDemo = new SmsDemo();
    $response = $SmsDemo->sendSms(
        $params['template'], // 短信模板编号
        $mobile, // 短信接收者
        $params['data']
    );
    return $response->Code=='OK'?true:($response->Code=='isv.BUSINESS_LIMIT_CONTROL'?true:false);
}

/**
 * 获取短信模版
 * @param $type
 * @param $code
 * @return array|bool
 */
function getTemplate($type,$code){
    if($type == 0){
        $map = [
            'key' => 'customer'
        ];
    }else{
        $map = [
            'type' => $type
        ];
    }
    $template = DB::table('messages_template')->where($map)->first();
    if(!$template)
        return false;
    if(!is_array($code)){
        $code = ['code'=>$code];
    }
    $data = $template->data;
    $params = [];
    if(!empty($data)){
        $data = explode(',',$data);
        foreach($data as $value){
            $params[$value] = $code[$value];
        }
    }
    $data = [
        'template' => $template->code,
        'data' => $params
    ];
    return $data;
}

/**
 * 发送短信(通知)
 * @param $mobile
 * @param $customer
 * @param $message
 * @return bool
 */
function sendMassage($mobile, $customer, $message){
    $SmsDemo = new SmsDemo();
    $response = $SmsDemo->sendSms(
        'SMS_94840153', // 短信模板编号
        $mobile, // 短信接收者
        Array(  // 短信模板中字段的值
            "customer" => $customer,
            "message" => $message
        )
    );
    return $response->Code=='OK'?true:($response->Code=='isv.BUSINESS_LIMIT_CONTROL'?true:false);
}

/**
 * 发送短信
 * @param $mobile
 * @param $type
 * @param $params
 * @return bool
 */
function sendOtherMassage($mobile, $type,$params){
    $params = getTemplate($type,$params);
    $SmsDemo = new SmsDemo();
    $response = $SmsDemo->sendSms(
        $params['template'], // 短信模板编号
        $mobile, // 短信接收者
        $params['data']
    );
    return $response->Code=='OK'?true:($response->Code=='isv.BUSINESS_LIMIT_CONTROL'?true:false);
}

/**
 * 发送短信(提货码)
 * @param $mobile
 * @param $name
 * @param $code
 * @return bool
 */
function sendCodeMassage($mobile, $name, $code){
    $params = getTemplate(5,['name'=>$name,'code'=>$code]);
    $SmsDemo = new SmsDemo();
    $response = $SmsDemo->sendSms(
        $params['template'], // 短信模板编号
        $mobile, // 短信接收者
        $params['data']
    );
    return $response->Code=='OK'?true:($response->Code=='isv.BUSINESS_LIMIT_CONTROL'?true:false);
}

/**
 * 生成提货码
 * @return string
 */
function getCode(){
    return rand(100000,999999);
}

/**
 * 发送短信(门店通知)
 * @param $mobile
 * @param $customer
 * @param $order
 * @param $time
 * @param $tel
 * @param $address
 * @return bool
 */
function sendEngineerMessage($mobile, $customer, $order,$time,$tel,$address){
    $SmsDemo = new SmsDemo();
    $response = $SmsDemo->sendSms(
        'SMS_104290005', // 短信模板编号
        $mobile, // 短信接收者
        Array(  // 短信模板中字段的值
            "order" => $order,
            "time" => $time,
            "customer" => $customer,
            "tel" => $tel,
            'address' => $address
        )
    );
    return $response->Code=='OK'?true:($response->Code=='isv.BUSINESS_LIMIT_CONTROL'?true:false);
}
/**
 * 密码加密
 * @param string $mobile
 * @param string $password
 * @return string
 */
function password( $mobile, $password){
    $str = $mobile.$password;
    $str = rsa_encode($str);
    return strtoupper(md5($str));
}

function print_sql ($sql,$params=array())
{
    exit;
    $sql =  preg_replace('/--.*/','',$sql);
    $sql =  preg_replace('/#.*/','',$sql);
    echo $sql."------------";
}
/**
 * RSA加密
 * @param string $string (size < 1013)
 * @return bool|string
 */
function rsa_encode($string){
    $private_key = @file_get_contents(APP_PATH."/Api/Rsakey/rsa_private_key.pem") or die("Rsa_Private_key's Files Don't Found!");
    $private_key = openssl_pkey_get_private($private_key);
    if(!$private_key){
        die("Wrong Private Key!!");
    }
    $string = is_array($string)?json_encode($string):$string;
    $encrypted = false;
    openssl_private_encrypt($string,$encrypted,$private_key);
    $encrypted = base64_encode($encrypted);
    if($encrypted)
        return $encrypted;
    die("Encryption error!");
}

/**
 * RSA解密
 * @param string $string
 * @return bool|array
 */
function rsa_decode($string){
    $public_key = @file_get_contents(BASE_PATH."/rsa_key/rsa_public_key.pem") or die("Rsa_Public_key's Files Don't Found!");
    $public_key = '
    ';
    $public_key = openssl_pkey_get_public($public_key);
    if(!$public_key){
        die("Wrong Public Key!!");
    }
    $decrypted = false;
    openssl_public_decrypt(base64_decode($string),$decrypted,$public_key);
    if($decrypted)
        return $decrypted;
    die('Decryption error!');
}



/**
 * 生成签名
 * @param string|array $params
 * @return string
 */
function _sign($params){
    $sha_key = 'SHK845uhcWODjus5';
    $strToSign  = is_array($params)?'':$params;
    if($strToSign == ''){
        ksort($params);
        foreach($params as $key => $value){
            $value = is_array($value)?str_split(json_encode($value)):str_split($value);
            $key = str_split($key);
            $max = count($key) >= count($value) ? $key : $value;
            $min = count($key) < count($value) ? $key : $value;
            $sMerchantKey = '';
            foreach($max as $k=>$v){
                $sMerchantKey .= isset($min[$k])?($v.$min[$k]):$v;
            }
            $strToSign .= strtoupper($sMerchantKey);
        }
    }
    $strToSign = $strToSign.$sha_key;
    $baSrc = mb_convert_encoding($strToSign,"UTF-8");
    $baResult = hash('sha256', $baSrc);
    return strtoupper($baResult);
}


/**
 * 手机商城用户端
 * 需要认证的action列表
 * @param string $string
 * @return bool
 */
function comprises_store($string){
    $comprises = [
        'UserController@show'
    ];
    return in_array( $string, $comprises);
}
/**
 * 设置GEO并缓存
 * @param int $lat
 * @param int $long
 * @param int $storeId
 * @return bool
 */
function setStoreGeo($lat = 0, $long = 0, $storeId = 0){
    $geoHashStr = Geohash::encode($lat, $long);
    $data = ['store_geo'=>$geoHashStr];
    DB::table('shop_info')->where(['store_id'=>$storeId])->update($data);
    return true;
}

function getStoreGeo($lat = 0, $long = 0){
    return Geohash::encode($lat, $long);
}

/**
 * 删除门店GEO信息
 * @param int $storeId
 * @return bool
 */
function delStoreGeo($storeId = 0){
    $rs = DB::table('shop_info')->where(['store_id'=>$storeId])->update(['store_geo'=>'']);
    if($rs)
        return true;
    return false;
}

/**
 * 获取附近店面ID
 * @param int $lat
 * @param int $long
 * @param int $int
 * @return bool|mixed
 */
function getNearStore($lat = 0, $long = 0, $int = 5){
    $geoHashStr = Geohash::encode($lat, $long);
    $five    = substr($geoHashStr,0,$int); //5位, 2.4KM
    $rs = DB::table('shop_info')->where('store_geo','like',"$five%")->select('store_id')->get();
    if($rs){
        $ids = [];
        foreach ($rs as $id){
            $ids[] = $id->store_id;
        }
        return $ids;
    }
    return false;
}


/**
 * 设置是否发放微信红包
 * @param $customerId
 * @param $equipment
 * @param $type
 * @param $weChat
 */
function setWeChatPacket($customerId,$equipment,$type,$weChat){
    $packetInfo = DB::table('WeChat_packet')->where(['customer_id'=>$customerId])->OrWhere(['equipment'=>$equipment])->first();
    if(!$packetInfo){
        $data = [
            'equipment' => $equipment,
            'customer_id' => $customerId,
            'create_time' => time(),
            'type' => (int)$type,
            'wechat_id' => $weChat
        ];
        $rs = DB::table('WeChat_packet')->insert($data);
        if($rs){

        }
    }
}

/**
 * 发送通知消息
 * @param $customer_id     //客户ID 全部:0
 * @param $message_type  //消息类型 0:无 1:客服消息 2:活动消息 3:物流消息 4:订单消息
 * @param $message_info   //消息主体
 * @param $massage_title   //消息主题
 * @param $order_id           //订单ID
 * @return bool
 */
function notice($customer_id,$message_type,$message_info,$massage_title,$order_id=0){
    $data = [
        'customer_id' => $customer_id,
        'order_id' => $order_id,
        'message_type' => (string)$message_type,
        'massage_title' => (string)$massage_title,
        'massage_info' => (string)$message_info
    ];
    $rs = DB::table('messages')->insert($data);
    return $rs;
}

/**
 * 字符串去掉空格
 * @param $str
 * @return mixed
 */
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");//strtoupper
    return strtolower(str_replace($qian,$hou,$str));
}

function alipay($order = 'DESC'){
    return new AliPay($order);
}

function rep($str){
    return str_replace(' ', '', $str);
}

/**
 * 支付宝 网站
 * @param $order_num
 * @param $title
 * @param $total
 * @param string $remarks
 * @return bool|mixed|SimpleXMLElement|string|提交表单HTML文本
 */
function aliPay_web($order_num,$title,$total,$remarks=''){
    $config = [];
    require_once dirname(dirname(__FILE__)).'/Common/lib/aliPay/config.php';
    require_once dirname(__FILE__).'/lib/aliPay/pagepay/service/AlipayTradeService.php';
    require_once dirname(__FILE__).'/lib/aliPay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
//商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no = trim($order_num);
//订单名称，必填
    $subject = trim($title);
//付款金额，必填
    $total_amount = trim($total);
//商品描述，可空
    $body = trim($remarks);
//构造参数
    $payRequestBuilder = new AlipayTradePagePayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setOutTradeNo($out_trade_no);

    $aop = new AlipayTradeService($config);

    /**
     * pagePay 电脑网站支付请求
     * @param $builder 业务参数，使用buildmodel中的对象生成。
     * @param $return_url 同步跳转地址，公网可以访问
     * @param $notify_url 异步通知地址，公网可以访问
     * @return $response 支付宝返回的信息
     */
    $response = $aop->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
//    $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
//    sendMsg($response);die;
//输出表单
    return $response;
}

/**
 * curl
 * @param $url
 * @param $post_data
 * @param array $header
 * @return mixed
 */
function _curl_post($url, $post_data, $header = []){
    $post_string = false;
    foreach($post_data as $key => $value){
        $value = urlencode($value);
        $post_string .= "$key=$value&";
    }
    if($post_string){
        $post_string = substr($post_string, 0, -1);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    $output = curl_exec($ch);
    return json_decode($output,true);
//    return $this->validateData($output);
//        return $output;
}

/**
 * 绑定账号
 * @param $customer_id
 * @return mixed|\Push\Request\V20160801\SimpleXMLElement
 */
function bindAccount($customer_id){
    return \Push\Request\V20160801\PushClass::bind($customer_id);
}

/**
 * 推送消息
 * @param $title
 * @param $body
 * @param bool $customer_id
 * @return array
 */
function pushMessage($title,$body,$customer_id=false){
    return \Push\Request\V20160801\PushClass::push($title,$body,$customer_id);
}

/**
 * 推送通知
 * @param $title
 * @param $body
 * @param bool $customer_id
 * @return array
 */
function pushNotify($title,$body,$customer_id=false,$params=""){
    return \Push\Request\V20160801\PushClass::notify($title,$body,$customer_id,$params);
}

/**
 * @param $expressType
 * @param $flag
 * @return string | array
 */
function getExpressName($expressType,$flag=false){
    $expressType = strtoupper($expressType);
    $expressList = ['AAE'=>'AAEWEB','民航'=>'CAE','Aramex'=>'ARAMEX','能达'=>'ND56','DHL'=>'DHL','配思航宇'=>'PEISI','DPEX'=>'DPEX','平安快递'=>'EFSPOST','D速'=>'DEXP','秦远物流'=>'CHINZ56','EMS'=>'EMS','全晨'=>'QCKD','EWE'=>'EWE','全峰'=>'QFKD','FedEx'=>'FEDEX','全一'=>'APEX','FedEx国际'=>'FEDEXIN','如风达'=>'RFD','PCA'=>'PCA','三态'=>'SFC','TNT'=>'TNT','申通'=>'STO','UPS'=>'UPS','盛丰'=>'SFWL','安捷'=>'ANJELEX','盛辉'=>'SHENGHUI','安能'=>'ANE','顺达快递'=>'SDEX','安能快递'=>'ANEEX','顺丰'=>'SFEXPRESS','安信达'=>'ANXINDA','苏宁'=>'SUNING','百福东方'=>'EES','速尔'=>'SURE','百世快递'=>'HTKY','天地华宇'=>'HOAU','百世快运'=>'BSKY','天天'=>'TTKDEX','程光'=>'FLYWAYEX','万庚'=>'VANGEN','大田'=>'DTW','万家物流'=>'WANJIA','德邦'=>'DEPPON','万象'=>'EWINSHINE','飞洋'=>'GCE','文捷航空'=>'GZWENJIE','凤凰'=>'PHOENIXEXP','新邦'=>'XBWL','富腾达'=>'FTD','信丰'=>'XFEXPRESS','共速达'=>'GSD','亚风'=>'BROADASIA','国通'=>'GTO','宜送'=>'YIEXPRESS','黑狗'=>'BLACKDOG','易达通'=>'QEXPRESS','恒路'=>'HENGLU','易通达'=>'ETD','鸿远'=>'HYE','优速'=>'UC56','华企'=>'HQKY','邮政包裹'=>'CHINAPOST','急先达'=>'JOUST','原飞航'=>'YFHEX','加运美'=>'TMS','圆通'=>'YTO','佳吉'=>'JIAJI','源安达'=>'YADEX','佳怡'=>'JIAYI','远成'=>'YCGWL','嘉里物流'=>'KERRY','越丰'=>'YFEXPRESS','锦程快递'=>'HREX','运通'=>'YTEXPRESS','晋越'=>'PEWKEE','韵达'=>'YUNDA','京东'=>'JD','宅急送'=>'ZJS','京广'=>'KKE','芝麻开门'=>'ZMKMEX','九曳'=>'JIUYESCM','中国东方'=>'COE','跨越'=>'KYEXPRESS','中铁快运'=>'CRE','快捷'=>'FASTEXPRESS','中铁物流'=>'ZTKY','蓝天'=>'BLUESKY','中通'=>'ZTO','联昊通'=>'龙邦','LTS'=>'LBEX','中通快运'=>'中邮','ZTO56'=>'CNPL'];
    if($flag){
        return $expressList;
    }
    $expressList = array_flip($expressList);
    return isset($expressList[$expressType])?$expressList[$expressType]:'other';
}

/**
 * @desc 数组转为 字符串
 * @param array $param
 * @param string $pieces
 * @return string
 */
function array_to_string($param,$pieces = '&',$pieces1 = '|'){
    $str = null;
    if(is_array($param)){
        foreach ($param as $key=>$value){
            if(is_array($value)){
                $str .= $key.$pieces1.array_to_string($value,$pieces,$pieces1).$pieces;
            }else{
                $str .= $key.$pieces1.$value.$pieces;
            }
        }
        $str = rtrim($str,$pieces);
    }elseif (is_string($param)){
        $str = $param;
    }else{
        $str = $param;
    }
    return $str;
}

function string_to_array($string,$pieces = '&',$pieces1 = '|'){
    $param = [];
    if(is_string($string)){
        $data = explode($pieces,$string);
        foreach ($data as $value){
            $v = explode($pieces1,$value);
            $param[$v[0]] = $v[1];
        }
    }elseif (is_array($string)){
        $param = $string;
    }else{
        $param = $string;
    }
    return $param;
}

/**
 * 多订单支付时生成付款码
 * @return string
 */
function build_order_no(){
    return 'x'.date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/**
 * 对象转数组
 * @param $object
 * @return mixed
 */
function object2array($object) {
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    }
    else {
        $array = $object;
    }
    return $array;
}

/**
 * 无限递归函数
 * @param $a
 * @param $pid
 * @return array
 */
function get_attr($a,$pid=0){
    $tree = array();                                //每次都声明一个新数组用来放子元素
    foreach($a as $v){
        if($v->parent_id == $pid){                      //匹配子记录
            $v->children = get_attr($a,$v->id); //递归获取子记录
            if($v->children == null){
                unset($v->children);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
            }
            $tree[] = $v;                           //将记录存入新数组
        }
    }
    return $tree;                                  //返回新数组
}

/**
 * 唯一标识生成器
 * @return string
 */
function create_guid() {
    return md5(uniqid("", true).rand(0,30000));
}


/**
 * 获取指定地址经纬度
 * @param $address
 * @return mixed
 */
function getLat($address){
    $appKey = BAIDU_APPKEY;
    $url = "http://api.map.baidu.com/geocoder/v2/?address=$address&output=json&ak=$appKey";
    $lat = _curl_post($url,[]);
    if($lat['status'] !=0 || !$lat)
        sendMsg('地址错误!',10001);
    return $lat;
}

/**
 * 根据经纬度 获取地址信息
 * @param $lat
 * @param $long
 * @return mixed
 */
function getAddressForLocation($lat,$long){
    $appKey = BAIDU_APPKEY;
    $url = "http://api.map.baidu.com/geocoder/v2/?location=$lat,$long&output=json&pois=1&ak=$appKey";
    $lat = _curl_post($url,[]);
    if($lat['status'] !=0 || !$lat)
        sendMsg('经纬度信息错误!',10001);
    return $lat;
}

/**
 * 根据IP获取经纬度信息
 * @param $ip
 * @return mixed
 */
function getLatForIp($ip){
    $appKey = BAIDU_APPKEY;
    $url = "https://api.map.baidu.com/location/ip?ip=$ip&ak=$appKey&coor=bd09ll";
    $lat = _curl_post($url,[]);
    if($lat['status'] !=0 || !$lat)
        sendMsg('地址错误!',10001);
    return $lat;
}

/**
 * 计算两点地理坐标之间的距离
 * @param  float $longitude1 起点经度
 * @param  float $latitude1  起点纬度
 * @param  float $longitude2 终点经度
 * @param  float $latitude2  终点纬度
 * @param  Int     $unit       单位 1:米 2:公里
 * @param  Int     $decimal    精度 保留小数位数
 * @return float
 */
function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=1, $decimal=2){
    if(!is_numeric($latitude1)){
        return 0;
    }
    if(!is_numeric($longitude1)){
        return 0;
    }
    $EARTH_RADIUS = 6370.996; // 地球半径系数
    $PI = 3.1415926;
    $radLat1 = $latitude1 * $PI / 180.0;
    $radLat2 = $latitude2 * $PI / 180.0;
    $radLng1 = $longitude1 * $PI / 180.0;
    $radLng2 = $longitude2 * $PI /180.0;
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $distance = $distance * $EARTH_RADIUS * 1000;
    if($unit==2){
        $distance = $distance / 1000;
    }
    return round($distance, $decimal);
}

function wechatH5Pay(){
    return new WechatH5Pay();
}

function setNotifyToStore($customerId,$orderId){
    if($customerId > 0){
        $customerStoreId = $customerId;
        $params = [
            'action' => 'traderOrdersDetails',
            'orderId' => $orderId,
            'customerId' => $customerStoreId
        ];
        $body = "您有新订单:$orderId,请在5分钟内接单,否则会自动取消";
        pushNotify(__TITLE__,$body,$customerStoreId,json_encode($params,JSON_UNESCAPED_UNICODE));
    }
}
/**
 * 合成图片地址
 * @param $url
 * @return string
 */
function images_url($url){
    if($url == '' || empty($url))
        return '';
    if(!is_numeric(strripos($url,'http')))
        $url = GOODS_URL.$url;
    return $url;
}



/**
 * mysql 经纬度计算 店铺
 * @param $lat
 * @param $long
 * @return string
 */
function getDistanceString($lat,$long,$table){
    return " ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-$table.lat*PI()/180)/2),2)+COS($lat*PI()/180)*COS($table.lat*PI()/180)*POW(SIN(($long*PI()/180-$table.lon*PI()/180)/2),2)))*1000)";
}

/**
 * @param $string
 * @return mixed
 */
function censorWords( $string )
{
    $fileName = BASE_PATH.'/uploads/words/CensorWords.txt';
    if ( !($words = file_get_contents( $fileName )) ){
        die('file read error!');
    }
    $string = strtolower($string);
    $word = preg_replace("/[1,2,3]\r\n|\r\n/i", '', $words);
    $matched = preg_replace('/'.$word.'/i', '***', $string);
    return $matched;
}

/**
 * 抛异常函数
 * @param $error
 * @param  $s
 */
function IE($error,$s){
    header('Content-Type:application/json; charset=utf-8');
    die(json_encode(array('status'=>0,'msg'=>sprintf(L($error),$s)),JSON_UNESCAPED_UNICODE));
}

/**
 * 获取参数并且进行验证
 * @param $name 参数名
 * @param $vs 参数格式 # require|email|phone;
 * require 表示必传
 */
function IV($name,$vs=array()){
    # require|email|phone;
    if($name=='token'){
        $param =trim($_SERVER['HTTP_TOKEN']);
    }
    elseif($name=='file')
    {
        $param = $_FILES;
    }
    else
    {
        $param =trim(I($name,false));
        if(!$vs) return $param;
    }


    $vs = explode('|', $vs);

    foreach ($vs as $v) {

        $v=strtolower($v);
        if($v=='require'){

            if (is_null($param)||$param===false) {

                IE(ERROR_PARAM_REQUARE,"$name");

            } elseif (is_string($param) && trim($param) === '') {
                IE(ERROR_PARAM_REQUARE,"$name");
            }

        }elseif($v=='email'){

            if($param){
                if(!filter_var($param, \FILTER_VALIDATE_EMAIL)){
                    IE(ERROR_PARAM_FORMAT,"$name");
                };
            }

        }elseif($v=='phone'){

            /*
            if($param){
                if (!preg_match('/^1[3458]\d{10}$/', $param)) {
                    IE(ERROR_PARAM_FORMAT,"$name");
                }
            }
            */
            //TODO: 后面再来验证

        }elseif($v=='nickname'){

            if($param){
                if ((mb_strlen($param) < 1) || (mb_strlen($param)) > 32) {
                    IE(ERROR_PARAM_FORMAT,"name");
                }
            }

        }elseif($v=='token'){

            if(!$param){
                return null;
            }

            $co=array(
                'token'=>$param,
            );
            $it = M('member')->field('id')->where($co)->find();
            if($it){
                return $it['id'];
            }else{
                return null;
            }

        }elseif($v=='token_check'){

            if(!$param){
                return null;
            }

            $co=array(
                'token'=>$param,
            );
            $it = M('shop')->field('id')->where($co)->find();
            if($it){
                return $it['id'];
            }else{
                IE(ERROR_USER_UNVALID_TOKEN);
            }

        }elseif($v=='language'){

            if($param){
                if(!in_array($param,array('1','2','3'))){
                    IE(ERROR_PARAM_FORMAT,"name");
                }
            }

        }elseif($v=='json'){

            if($param){

                $param=urldecode($param);
                $param=str_replace('&quot;', '"', $param);

                if(!json_decode($param,true)){
                    IE(ERROR_PARAM_FORMAT,"$name");
                }
            }

        }elseif($v=='json_format'){

            if($param){

                $param=urldecode($param);
                $param=str_replace('&quot;', '"', $param);

                if(!json_decode($param,true)){
                    IE(ERROR_PARAM_FORMAT,"$name");
                }

                $param = json_decode($param,true);
            }
        }elseif($v=='file'){ // 上传文件
            if(!$_FILES[$name]) return '';
            $apiImagePath = '/Public/Shop/Images/';
            $maxSize = C('UPLOAD_FILE_LIMIT');
            $maxSize = $maxSize?$maxSize:3145728;
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     $maxSize ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      '.'.$apiImagePath; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            $info   =   $upload->uploadOne($_FILES[$name]);
            if(!$info) {// 上传错误提示错误信息
                IE($upload->getError(),'');
            }else{// 上传成功 获取上传文件信息
                $param =  $apiImagePath.$info['savepath'].$info['savename'];
            }
        }elseif($v=='utf8mb_filter'){

            if($param){
                $param = filterUtf8($param);
                if(!$param){
                    $param="***";
                }
            }
        }
    }

    return $param;
}


/**
 * 初始化分页数据
 * @param $default_order
 * @param int $default_page
 * @param int $default_limit
 * @return array
 */
function initPage($default_order,$default_page=0,$default_limit=10)
{
    $maxlimit = 30;
    $page = IV('page');
    $order = IV('order');
    $limit = IV('limit');
    $page = $page?$page-1:$default_page;
    $order = $order?$order:$default_order;
    $limit = $limit?$limit>$maxlimit?$maxlimit:$limit:$default_limit;
    return array('order'=>$order,'start'=>$page*$limit,'limit'=>$limit);
}


#价钱格式化
function PriceFormat($price){
    return number_format(floatval($price), 1, '.', '');
}

#价格格式化
function PriceFormatFloat($price){

    return round($price, 2);
}

/**
 * 生成Guid主键作为Token
 * @return Boolean
 */
function genToken() {
    return str_replace('-','',substr(String::uuid(),1,-1));
}