<?php

namespace Common\Lib;

use Hacklee\Umeng\UmengNotifyApi;
use Common\Lib\PushUtils;
use Common\Lib\DateUtils;

class PushUtils {

    
    # 推送安卓manager
    public static function push_android_manager($tokens,$text,$title,$ticker,$data){

        $app_key = C('UMENG_ANDROID_PUSH_MANAGER_KEY');
        $app_secret = C('UMENG_ANDROID_PUSH_MANAGER_SECRET');

        $title=$title.DateUtils::Now();

        PushUtils::push_android($app_key,$app_secret,$tokens,$text,$title,$ticker,$data);
    }

    # 推送安卓manager
    public function push_android_client($tokens,$text,$title,$ticker,$data){

        $app_key = C('UMENG_ANDROID_PUSH_CLIENT_KEY');
        $app_secret = C('UMENG_ANDROID_PUSH_CLIENT_SECRET');
        PushUtils::push_android($app_key,$app_secret,$tokens,$text,$title,$ticker,$data);
    }

    # 推送ios
    public function push_ios_manager($tokens,$alert,$badge,$data){

        $app_key = C('UMENG_IOS_PUSH_MANAGER_KEY');
        $app_secret = C('UMENG_IOS_PUSH_MANAGER_SECRET');

        PushUtils::push_ios($app_key,$app_secret,$tokens,$alert,$badge,$data);
    }

    # 推送ios
    public function push_ios_client($tokens,$alert,$badge,$data){

        $app_key = C('UMENG_IOS_PUSH_CLIENT_KEY');
        $app_secret = C('UMENG_IOS_PUSH_CLIENT_SECRET');

        PushUtils::push_ios($app_key,$app_secret,$tokens,$alert,$badge,$data);
    }

    # 推送android
    public static function push_android($app_key,$app_secret,$tokens,$text,$title,$ticker,$data){

        if(!$tokens){
            return;
        }

        $customizeApi = new UmengNotifyApi('android',$app_key,$app_secret,true);

        $customizeApi->sendAndroidUnicast([
            'device_tokens'=>$tokens,
            'ticker' => $ticker,
            'title' => $title,
            'text' => $text,
            'after_open' => 'go_app'
        ], $data);
    }

    # 推送ios
    public static function push_ios($app_key,$app_secret,$tokens,$alert,$badge,$data){

        if(!$tokens){
            return;
        }

        $customizeApi = new UmengNotifyApi('ios', $app_key, $app_secret, C('IOS_PUSH_OFFICIAL'));
        $customizeApi->setDeviceTokens($tokens)->sendIOSUnicast([
            'device_tokens'=>$tokens,
            'alert' => $alert,
            'badge'=>$badge,
        ],$data);
    }


    public static function broadcast_ios($app_key,$app_secret,$alert,$badge,$data){

        if(!$data){
            $data=array();
        }

        $api = new UmengNotifyApi('ios', $app_key, $app_secret, C('IOS_PUSH_OFFICIAL'));
        $api->sendIOSBroadcast([
            'alert' => $alert
        ],$data);
    }

    public static function broadcast_android($app_key,$app_secret,$text,$title,$ticker,$data){

        if(!$data){
            $data=array();
        }
        
        $api = new UmengNotifyApi('android',$app_key,$app_secret,true);
        $api->sendAndroidBroadcast([
            'ticker' => $ticker,
            'title' => "Greadeal",
            'text' => $text,
            'after_open' => 'go_app'
        ],$data);
    }
    
}