<?php

namespace Common\Lib;

class Verification{
   
    #验证非空参数
    static function NotNullParams($array,$validate_keys){

        foreach ($validate_keys as $k){
            $s=trim($array["$k"]);
            if(!$s){
                E(L('ERROR_PARAM_WRONG'));
            }
        }
    }

    #必须为email
    static function isEmail($array,$validate_keys){

        foreach ($validate_keys as $k){
            $email=trim($array["$k"]);
            if(!$email){
                E(L('ERROR_PARAM_WRONG'));
            }else{

                if (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$email)) {
                
                } else {
                    E(L('ERROR_PARAM_WRONG'));    
                }
            }
        }
    }
}