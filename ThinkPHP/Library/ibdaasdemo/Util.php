<?php

class Util{

    /**
     * Describe : CURL
     * @param string $url 访问地址
     * @param string $method 请求方式
     * @param string $params 请求参数
     * @param string $header 请求Header头
     * @param array  $auth 认证信息
     */
    function get_curl($url,$method,$params=array(),$header='',$auth =array()) {

        $curl = curl_init();//初始化CURL句柄
        $timeout = 15;
        curl_setopt($curl, CURLOPT_URL, $url);//设置请求的URL
        #curl_setopt($curl, CURLOPT_HEADER, false);// 不要http header 加快效率
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        $header[] = "Content-Type:application/json;charset=utf-8";
        if(!empty($header)){
            curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
        }

        curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout);//设置连接等待时间
        switch ($method){
            case "GET" :
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                if(is_array($params)){
                    $params = json_encode($params,320);
                }
                #curl_setopt($curl, CURLOPT_POST,true);
                #curl_setopt($curl, CURLOPT_NOBODY, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                //设置提交的信息
                curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
                break;
            case "PUT" :
                curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params,320));
                break;
            case "DELETE":
                curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
                break;
        }

        if (!empty($auth) && isset($auth['username']) && isset($auth['password'])) {
            curl_setopt($curl, CURLOPT_USERPWD, "{$auth['username']}:{$auth['password']}");
        }

        $data = curl_exec($curl);//执行预定义的CURL
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);//获取http返回值
        curl_close($curl);
        $res = json_decode($data,true);
        return $res;

    }

}