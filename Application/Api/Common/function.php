<?php

/**
 * 抛异常函数
 */
function IE($error,$s){
    $data['status'] = 0;
    $data['data'] = $error;
    header('Content-type: application/json');
    die(json_encode($data));
}

/**
 * 获取参数并且进行验证
 */
function IV($name,$vs){

    # require|email|phone;

    $param =trim(I($name));

    if(is_array($vs)){

        if(!in_array($param,$vs)){
            IE(ERROR_PARAM_FORMAT,"$name");  
        }

    }else{

        $vs = explode('|', $vs);

        foreach ($vs as $v) {
            
            $v=strtolower($v);
            if($v=='require'){
                
                if (is_null($param)) {
                        
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
                $it = M('user_token')->field('user_id')->where($co)->find();
                if($it){
                    return $it['user_id'];
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
                $it = M('user_token')->field('user_id')->where($co)->find();
                if($it){
                    return $it['user_id'];
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
            }
        }
    }

    return $param;
}


#价钱格式化
function PriceFormat($price){
    return number_format(floatval($price), 1, '.', '');
}

#价格格式化
function PriceFormatFloat($price){

    return round($price, 2);
}


function isArabic($string){
    // Initializing count variables with zero
    $arabicCount = 0;
    $englishCount = 0;
    // Getting the cleanest String without any number or Brackets or Hyphen
    $noNumbers = preg_replace('/[0-9]+/', '', $string);
    $noBracketsHyphen = array('(', ')', '-');
    $clean = trim(str_replace($noBracketsHyphen , '', $noNumbers));
    // After Getting the clean string, splitting it by space to get the total entered words 
    $array = explode(" ", $clean); // $array contain the words that was entered by the user
    for ($i=0; $i <= count($array) ; $i++) {
        // Checking either word is Arabic or not
        $checkLang = preg_match('/\p{Arabic}/u', $array[$i]);
        if($checkLang == 1){
            ++$arabicCount;
        } else{
            ++$englishCount;
        }
    }
    if($arabicCount >= $englishCount){
        // Return 1 means TRUE i-e Arabic
        return 1;
    } else{
        // Return 0 means FALSE i-e English
        return 0;
    }
}