<?php

namespace Common\Lib;

use Common\Lib\MessageUtils;

class VercodeUitls{
   
    
    #发送验证码
    static function sendVercode($phone,$for,$content){

        //TODO: 这里需要做手机号的限制，每个手机号只能发3次/天

        $phone = str_replace("-","",$phone);#去掉-

        $key= $for.'#'.$phone;

        $code = S($key);
        if(!$code){
            $code = rand(1000,9999);
        }

        if(!$content){
            $content = sprintf(L('INFO_FORGET_PASSWORD_VERCODE_MESSAGE'),$code);
        }else{
            $content = sprintf($content,$code);
        }

        MessageUtils::sendMessage($phone,$content);

        # 这里是存放在本地服务器上，后面需要存放在 memcached 上面

        S($key,$code,600); #10分钟有效

        return $code;
    }


    static function verifyVercode($phone,$for,$vercode){

        $phone = str_replace("-","",$phone);#去掉-
        $key = $for.'#'.$phone;
        $v = S($key);

        if($vercode==$v){
            S($key,null);
            return true;
        }else{
            return false;
        }
    }
}