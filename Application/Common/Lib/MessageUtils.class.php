<?php

namespace Common\Lib;

use Common\Lib\CurlUtils;
use \Think\Log;

class MessageUtils{
   
    public function sendMessage($number,$content){

        $url = 'https://rest.nexmo.com/sms/json';

        $data = array(
            'api_key' => C('NEXMO_KEY'),
            'api_secret' => C('NEXMO_SECRET'),
            'from' => C('NEXMO_FROM'),
            'to' => $number,
            #'type'=>'utf8',
            'text' => $content
        );

        $re = CurlUtils::SendHttpsReq($url,$data);
        $ret = json_decode($re,true);

        if($ret['messages']['status']!='0'){
            Log::write("can't send message $number # $content # $re",'WARN');
        }
    }
}