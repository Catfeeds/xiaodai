<?php

return array(

    'ERROR_UNKONW'     => '10000#未知异常',
    'ERROR_PARAM_REQUARE'     => '10001#缺少 %s 参数',
    'ERROR_PARAM_FORMAT'     =>  '10002#%s 参数格式错误',
    'ERROR_SERVER_DB'     =>  '10003#数据库错误',
    'ERROR_PARAM_MISS'     =>  '10004#缺少参数',
    'ERROR_VERCODE_UNVALID' =>'10005#验证码失败',
    'ERROR_ORDER_FAIL' => '10006#下订单失败', #下订单失败

    'ERROR_CUSTOMER_NOT_EXIST'     => '20002#用户不存在',
    'ERROR_CUSTOMER_WRONG_PASSWORD'     => '20003#用户密码错误',
    'ERROR_CUSTOMER_UNVALID_TOKEN'     => '20004#用户登录失效',
    'ERROR_CUSTOMER_REPEAT_EMAIL'     => '20005#邮箱重复',
    'ERROR_CUSTOMER_REPEAT_PHONE'     => '20006#电话号码重复',

    'ERROR_VENDOR_NOT_EXIST'     => '20016#商家不存在',

    'ERROR_PAYMENT_PAID_FAIL'=>"30000#未支付",
    
    'ERROR_PAYMENT_UNSUPPORTED_COURIER'=>'30001#该地区不支持该物流方式',
    'ERROR_ORDER_CONFIRM_FAIL'=>'30002#',
    
    'ERROR_ORDER_CANCEL_FAIL'=>'30003#取消失败',
    'ERROR_ORDER_CONSUMECODE_INVALID'=>"30004#消费码失效",

    'ERROR_ORDER_CONSUMECODE_COMPLETED'=>'30005#这个消费码已经被消费',

    'ERROR_ORDER_NOT_ENOUGH_QUANTITY'=>'30006#没有足够多的库存',

    'ERROR_PRODUCT_EXPIRES'=>'30007#产品过期',

    'ERROR_SHIPPING_UNREACH_STANDARD'=>'31001#没有达到购买标准',

    'ERROR_MEMBERSHIP_CARD_REDUCE'=>"40001#你的会员卡不能降级", # 不能降

    'ERROR_MEMBERSHIP_CARD_LOWER'=>'40002#Membership level is not enough',

    'ERROR_MEMBERSHIP_CARD_MORE'=>'40003#会员不能重新购买优惠劵',

    'ERROR_VENDOR_PINGCODE_FAIL'=>'40004#Ping code 不正确',

    'ERROR_ORDER_CONSUMECODE_INVALID'=>"40005#消费码失效",

    'ERROR_ORDER_CONSUMECODE_EXPIRES'=>"40006#消费码已经过期",

    'ERROR_FILM_OUT'=>'40007#免费的电影票已经被领完',

    'ERROR_FILM_REPEAT'=>'40008#你已经领过了，必须消费了才能领劵。',
    

    # ETISALAT 
    'ETISALAT_EXCEPTION' => '50001#ETISALAT异常',
    'ETISALAT_UNPAID' => '50002#ETISALAT未支付', #未支付
   
    ##########################

    'INFO_FORGET_PASSWORD_VERCODE_MESSAGE' => "%s (Dynamic verification code), Valid for 30 minutes (Greadeal)",  #发验证码
    'INFO_REGISTER_VERCODE_MESSAGE' => "%s (Dynamic verification code), Valid for 30 minutes (Greadeal)",  #发验证码


    "TESTTTT"=>"1111111#IOS传值传错了",
);