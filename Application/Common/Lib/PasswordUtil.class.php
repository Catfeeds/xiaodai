<?php

namespace Common\Lib;

class PasswordUtil {

    #加密    
    public static function encryptPassword($pwdsha1,$salt){
        $pwd= sha1($salt.(sha1($salt.$pwdsha1)));
        return $pwd;
    }

    #盐值
    public static function genSalt(){

        $salt=substr(md5(uniqid(rand(), true)), 0, 9);
        return $salt;
    }


    # 验证ping code
    public static function encryptPingCode($pwdsha1){

        $pwd= sha1((sha1($pwdsha1)));
        return $pwd;
    }

}