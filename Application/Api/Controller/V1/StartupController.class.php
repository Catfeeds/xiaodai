<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
//use Rest\Common\function;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

/** 
 * 启动时请求的数据
 */
class StartupController extends IController{


    #设备启动初始化
    public function init(){

        $data['device_id'] = IV('device_id','require');
        $data['device_name'] = IV('device_name','require');
        $data['device_version'] = IV('device_version','require');
        $data['device_language'] = IV('device_language','require');
        $data['app_version'] = IV('app_version','require');
        $data['app_type'] = IV('app_type','require'); // ios,iphone,ipad,android,androidpad        
        $data['app_bundle_id'] = IV('app_bundle_id','require');

        $Device=D('AppDevice');
        $Device->addOrUpdate($data);

        #返回最新的app信息
        $AppVersion=D('AppVersion');
        $it=$AppVersion->getCheckedVersion($data['app_type'],$data['app_bundle_id']);

        $it['course_url']=C('COURSE_URL');
        $it['user_protocol_url']=C('USER_PROTOCOL_URL');
        $it['newcomer_url'] = C('Newcomer_URL');
        $it['aboutus_url'] = C('ABOUTUS_URL');
        $it['startup_ads'] = C('Startup_Ads').$data['device_language'];
        $it['pay_notice_url'] = C('Pay_Notice_Link').$data['device_language'].'.html';

        $it['skill_url'] = C('Skill_Link').$data['device_language'].'.html';
        $it['rule_url'] = C('Rule_Link').$data['device_language'].'.html';
        $it['faq_url'] = C('Faq_Link').$data['device_language'].'.html';

        $this->iSuccess($it);
    }

    # 获取qiniu token
    /*
    public function getQiniuToken(){

        $accessKey = C('Qiniu_AccessKey');
        $secretKey = C('Qiniu_SecretKey');
        $auth = new Auth($accessKey, $secretKey);
        $bucket = C('Qiniu_Bucket_Greadeal');

        $token = $auth->uploadToken($bucket);

        $this->iSuccess($token);
    }*/

    # 获取阿里云上传信息
    public function getAliStoreToken(){

        $res=array(
            'endpoint'=>'oss-me-east-1.aliyuncs.com',
            'bucket'=>'dadacast-dubai',
            'accessKeyId'=> 'LTAIRYTW2hKDnlM4',
            'accessKeySecret'=>'HqGflpbTfkqLvSh11gwUY5uVahrvyg'
        );
        $this->iSuccess($res);
    }


    # 获取阿里云上传信息
    public function getAwsUploadToken(){

        $res=array(
            'endpoint'=>'eu-central-1',
            'bucket'=>'dadacast',
            'accessKeyId'=> 'AKIAJBTIGBSMKVWVDUTA',
            'accessKeySecret'=>'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',
        );
        $this->iSuccess($res);
    }

}