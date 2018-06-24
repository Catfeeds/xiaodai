<?php

require_once("Exception.php");

class TokenHelper {

    private $accessKey = "";
    private $secretKey = "";
    private $tokenVersion = "";
    private $allowMethod = array('GET','POST','PUT','DELETE');

    /**
     * 构造函数
     *
     * @param int $accessKey AccessKey,Token 认证的存取 Key
     * @param string $secretKey Secure Key,Token 认证的安全密钥
     * @param string $tokenVersion 签名协议版本标识
     */
    function __construct($accessKey, $secretKey , $tokenVersion)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->tokenVersion = $tokenVersion;
    }

    /**
     * 通过请求内容或者输出内容生成Token
     *
     * @param string:urlPath 请求URL
     * @param string:method  请求方法, 方法必须为 'GET'、'POST'、'DELETE'、'HEAD'、'PUT' 其中一项
     * @param string:queryParam 请求URL的参数
     * @param string:body    请求Body
     * @param int:expireTime Token过期时间
     * @return string 返回Token
     */
    function generateToken($urlPath, $method = "POST", $queryParam = '', $body = '', $expireTime = 300){

        if (empty($this->accessKey) || empty($this->secretKey)) {
            throw new Yunfeng_Exception("general_1","Invalid AK or SK");
        }

        if (empty($urlPath)){
            throw new Yunfeng_Exception("general_1","Empty urlPath");
        }

        if(!in_array($method,$this->allowMethod)){
            throw new Yunfeng_Exception("general_1","invalid request method");
        }


        // Signaure:=md5(|v2-{AK}-{ExpireTime}|{SK}|{UrlPath}|{Method}|{Querystring}|{body}|)
        $signature_urlPath = $this->decodeUtf8($urlPath);
        $signature_Querystring = $this->generateParamToString($queryParam);

        $signature = "|{$this->tokenVersion}-{$this->accessKey}-{$expireTime}|{$this->secretKey}|{$signature_urlPath}|{$method}|{$signature_Querystring}|{$body}|";
        $signature = md5($signature);

        // token : v2-{AK}-{ExpireTime}-{Signature}
        $token = "{$this->tokenVersion}-{$this->accessKey}-{$expireTime}-{$signature}";
        return $token;

    }


    /**
     * 将参数排序后，用&拼接输出字符串
     * @param array $date 参数数据
     * @return string 返回按&拼接的参数
     */
    function generateParamToString($data) {

        if(!empty($data)){

            if(!is_array($data)){

                parse_str($data,$data);

            }

            ksort($data);
            $items = array();
            foreach ($data as $key => $value) {
                $items[] = $key . "=" . $value;
            }
            return join("&", $items);
        }
        return $data;
    }

    /**
     * 将数据转换成UTF8编码
     *
     * @param 需要转码的数据
     * @return string UTF-8 编码的数据
     */
    function decodeUtf8($str){

        $result = mb_convert_encoding($str, 'UTF-8','ascii,GB2312,gbk,UTF-8');
        return $result;

    }

	

}