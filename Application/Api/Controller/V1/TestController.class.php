<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\QiniuUtils;
use Common\Lib\Date;
use Common\Lib\HtmlUtils;
use Common\Lib\DateUtils;
use Stichoza\GoogleTranslate\TranslateClient;

use Common\Lib\MessageUtils;
use Common\Lib\NexmoMessage;
use Common\Lib\EtisalatUtils;
use Common\Lib\Logger;
use Common\Lib\PushUtils;

use Hacklee\Umeng\UmengNotifyApi;
use Common\Lib\PasswordUtil;
use Common\Lib\EmailUtils;

use Common\Lib\LangUtils;

use Common\Lib\Redis;

use Common\Lib\String;

require_once('./Application/Common/Vendor/alipay/alipay_notify.class.php');


class TestController extends IController{

    public function test_lang(){

        $obj['name_ar'] ="XXX";
        LangUtils::LangObj($obj,$k="name",$lang='ar');
    }


    public function test_pwd(){

        $sha1 = sha1('1234');
        dump($sha1);

        #7110eda4d09e062aa5e4a390b0a572ac0d2c0220

        $db_save = PasswordUtil::encryptPingCode($sha1);
        echo $db_save;


        # 132c9d1b0fd3687a4a7bdd42a7ca596cddd94ca0
    }


    public function test_exp(){

        $co=array(
            'image'=>array('neq',""),
            'vendor_id'=>1122
        );

        if(M('takeout_product')->where($co)->count()){
            echo '1';
        }else{
            echo '0';
        }

        #dump(M('takeout_product')->getLastSql());
        #dump($list);
    }

    public function test_push(){

        $data=array(
            'order_id'=>'1'
        );
            
        #PushUtils::push_android_client('Amcz9TwfK3JAG6_saXRUSSMJemxZt1UIgWCe7Qrz_YCS',DateUtils::Now(),'title','ticker',$data);
        
        #PushUtils::push_android_client('Amcz9TwfK3JAG6_saXRUSSMJemxZt1UIgWCe7Qrz_YCS',DateUtils::Now(),'title','ticker',$data);
        
        $tokens=I('tokens');
        #PushUtils::push_ios_client($tokens,$alert='this is good',$data);

        PushUtils::push_ios_manager($tokens,$alert,1,'');
    }
    
    # soap 测试
    public function test_message(){
        
        #$phone='8618980891660';
        #$content='测试';
        #$mess = MessageUtils::sendMessage($phone,$content);
        #echo $mess;
        #
        #
        echo  urlencode('http://www.baidu.com?cxx=xx&ff=t');exit;
        echo $_POST['test'];
    }

    public function test2(){
        
        #phpinfo();
        #$memcache = new \Memcache; //创建一个memcache对象 
        #$memcache->connect('localhost', 11211) or die ("Could not connect"); //连接Memcached服务器
        #$memcache->set('key', 'test'); //设置一个变量到内存中，名称是key 值是test
        #$get_value = $memcache->get('key'); //从内存中取出key的值
        #echo $get_value;
        
        $co=array(
            'product_id'=>423
        );
        $list = M('User')->where($co)->cache(1)->select();

        dump($list);
    }


    # 测试
    # 检测etisalat环境
    public function detect_etisalat_req(){

        $order_id = '123';
        $order_name='xxx';
        $order_info='info';
        $amount = '10';
        $ret = EtisalatUtils::register($order_id,$order_name,$order_info,$amount);
        dump($ret);
    }

    public function detect_etisalat_finalize($transaction_id){

        echo EtisalatUtils::finalize($transaction_id);
    }

    
    #
    public function log(){

        $t1 = microtime(true);
        $t2 = microtime();
        echo $t1.'#';
        echo $t2;
    }


    # 发送邮件
    public function test_email(){

        $to= 'funinging@qq.com' ;
        $url = "http://".C(API_HOST)."/rest2/v1/email/send_order_email?order_id=1453733781669949"."&to=".$to;
        file_get_contents($url);
    }


    public function test_fbimage($video_id){

       //$video_id = "10153231379946729"; 
       $xml = file_get_contents('http://graph.facebook.com/' . $video_id);
       $result = json_decode($xml,true);

       $small = $result->format[0]->picture; 
       $medium = $result->format[1]->picture;

       //fetch the 720px sized picture
       $large = $result->format[3]->picture;

       echo $large;
    }

    public function test_delete_qiniu($key){

        echo QiniuUtils::delete($key);
    }

    public function test_stat_qiniu($url){

        $it = QiniuUtils::imageInfo($url);
        print_r($it);
    }


    // 抓取视频
    public function test_crawl_video(){


    }


    public function test_s_function(){
        
        //S('key','vvvvvvvvvvvvvvvvvvvv',10);
        //echo S('key');exit;
    }

    public function test_merge(){

        $a1=array(1,2,3);
        $a2=array(4,5,6);

        $t = array_merge($a2,$a1);

        print_r($t);
    }

    public function testss(){

        //RedisUtils::set('test',array('b'),10);
        $v = RedisUtils::get('test');
        print_r($v);
        print_r(RedisUtils::ttl('test'));
    }


    public function test_redis(){

        $redis = new Redis();
        echo $redis->get('test');
    }

    # 测试缓存
    public function test_cache(){
        $vlist = M('video')->where($co)->cache(true)->select();
    }


    public function test_bb(){

        //phpinfo();
        /*
        $memcache = new \Memcache; //创建一个memcache对象
        $memcache->connect('10.0.0.6', 11211) or die ("Could not connect"); //连接Memcached服务器
        $memcache->set('key', 'test'); //设置一个变量到内存中，名称是key 值是test
        $get_value = $memcache->get('key'); //从内存中取出key的值  
        echo $get_value;
        */
       
        $v = M('Video')->cache(30)->find(1970);

        print_r($v);
    }

    public function idtag(){

        $str = "这是个好的好东西#xxx #bbb#  #cc ";

        $arr = $this->getNeedBetween($str,"xxx",$mark2);

        print_r($arr);
    }

    public function pushArray(){

        $pserver = Service('Push');
        $result = $pserver->push_by_device('ab680e4c70e64a229c00e1f6e7a5df6b','Ice cream أرسل لك هديه');

        print_r($result);
    }
}