<?php

return array(

    'ERROR_UNKONW'     => '10000#Unknown exception',
    'ERROR_PARAM_REQUARE'     => '10001#Missing %s parameter',
    'ERROR_PARAM_FORMAT'     =>  '10002#%s parameter format error',
    'ERROR_SERVER_DB'     =>  '10003#Database exception',
    'ERROR_PARAM_MISS'     =>  '10004#Missing parameters',
    'ERROR_VERCODE_UNVALID' =>'10005#Verification code failure',
    'ERROR_ORDER_FAIL' => '10006#error to order', #下订单失败

    'ERROR_USER_NOT_EXIST'     => '20002#Users do not exist',
    'ERROR_USER_WRONG_PASSWORD'     => '20003#User password error',
    'ERROR_USER_UNVALID_TOKEN'     => '20004#Token failure',
    'ERROR_USER_REPEAT_EMAIL'     => '20005#Email repeat',
    'ERROR_USER_REPEAT_PHONE'     => '20006#Phone number repeat',
    

    'ERROR_VENDOR_NOT_EXIST'     => '20016#vendor do not exist',

    'ERROR_PAYMENT_PAID_FAIL'=>"30000#Fail TO Pay",
    
    'ERROR_PAYMENT_UNSUPPORTED_COURIER'=>'30001#The area does not support the express delivery',
    'ERROR_ORDER_CONFIRM_FAIL'=>'30002#The area does not support the express delivery',
    
    'ERROR_ORDER_CANCEL_FAIL'=>'30003#fail to cancel',
    'ERROR_ORDER_CONSUMECODE_INVALID'=>"30004#consume code invalid",

    'ERROR_ORDER_CONSUMECODE_COMPLETED'=>'30005#The code has been consumption',

    'ERROR_PRODUCT_EXPIRES'=>'30007#Prodcut Expires',

    'ERROR_ORDER_NOT_ENOUGH_QUANTITY'=>'30006#There is no enough quantity',

    'ERROR_SHIPPING_UNREACH_STANDARD'=>'31001#Did not reach the shipping standard',

    'ERROR_MEMBERSHIP_CARD_REDUCE'=>"40001#Membership card cannot be downgraded", # 不能降

    'ERROR_MEMBERSHIP_CARD_LOWER'=>'40002#Membership Level is not matchable',

    'ERROR_MEMBERSHIP_CARD_MORE'=>'40003#Members cannot rebuy coupons.',

    'ERROR_VENDOR_PINGCODE_FAIL'=>'40004#vendor pin code invalid',

    'ERROR_ORDER_CONSUMECODE_INVALID'=>"40005#consume code invalid",

    'ERROR_ORDER_CONSUMECODE_EXPIRES'=>"40006#consume code expires",

    'ERROR_FILM_OUT'=>'40007#Free movie tickets have been brought End',

    'ERROR_FILM_REPEAT'=>"40008#You've been brought up one, Must be received after you consume it.",


    # ETISALAT 
    'ETISALAT_EXCEPTION' => '50001#ETISALAT ETISALAT_EXCEPTION',
    'ETISALAT_UNPAID' => '50002#ETISALAT_UNPAID', #未支付

    ##########################

    'INFO_VERCODE_MESSAGE' => "%s (Dynamic verification code), Valid for 30 minutes【Greadeal】",  #发验证码

    'INFO_FORGET_PASSWORD_VERCODE_MESSAGE' => "%s (Dynamic verification code), Valid for 30 minutes (Greadeal)",  #发验证码
    'INFO_REGISTER_VERCODE_MESSAGE' => "%s (Dynamic verification code), Valid for 30 minutes (Greadeal)",  #发验证码


    "TESTTTT"=>"1111111#IOS传值传错了",
);