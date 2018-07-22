<?php

namespace Common\Lib;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;


class QiniuUtils{
    
    #上传二维码
    public static function upload_qrcode($code){

        $uploadMgr = new UploadManager();
        $auth = new Auth(C('Qiniu_AccessKey'), C('Qiniu_SecretKey'));
        $token = $auth->uploadToken(C('Qiniu_Bucket_Greadeal'));

        $key = "qr/$code";
        list($ret, $err) = $uploadMgr->put($token, $key, $code);
        if ($err !== null) {
            Log::record("七牛云制作二维码失败:$code",'WARN',true);
        }

        return $key;
    }


    # 获取消费码
    public static function get_qr_code_url($code){

        return C('Qiniu_Bucket_Greadeal_URL').'qr/'.$code.'?qrcode/1';
    }

    #上传二维码
    public static function upload_image($file){

        $accessKey = C('Qiniu_AccessKey');
        $secretKey = C('Qiniu_SecretKey');
        $auth = new Auth($accessKey, $secretKey);
        $bucket = C('Qiniu_Bucket_Greadeal');

        $token = $auth->uploadToken($bucket);

        $key = md5_file($file).'.jpg';

        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $file);
        if ($err !== null) {
           Logger::error("七牛云上传文件失败:",$key,$err);
           IE(ERROR_UPLOAD);
        }
        return $key;
    }

    #上传二维码
    public static function upload_str($key,$str){

        $accessKey = C('Qiniu_AccessKey');
        $secretKey = C('Qiniu_SecretKey');
        $auth = new Auth($accessKey, $secretKey);
        $bucket = C('Qiniu_Bucket_Greadeal');

        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();

        list($ret, $err) = $uploadMgr->put($token,$key, $str);
        if ($err !== null) {
            Logger::error("七牛云上传文件失败:",$key,$err);
            IE(ERROR_UPLOAD);
        }
    }

    public static function delete($key){

        $accessKey = C('Qiniu_AccessKey');
        $secretKey = C('Qiniu_SecretKey');
        $auth = new Auth($accessKey, $secretKey);

        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);
        $bucket = C('Qiniu_Bucket_Greadeal');

        $err = $bucketMgr->delete($bucket, $key);
        
        if ($err !== null) {
            return false;
        } else {
            return true;
        }
    }


    public static function imageInfo($url){

        $url_arr = explode('?', $url);
        $url=$url_arr[0];
        if(!$url){
            return false;
        }
        
        $json = file_get_contents($url.'?imageInfo');
        $arr = json_decode($json,true);

        if($arr['error']){
            return false;
        }else{
            return $arr;
        }
    }
}