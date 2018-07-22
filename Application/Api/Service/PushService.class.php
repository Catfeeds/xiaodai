<?php
namespace Rest3\Service;
require 'JPush.php';
class PushService {

    const JIGUANG_USER		=   'ffd3699a173e8732b46205c1';
    const JIGUANG_PASS		=   'f8086d5951f24b18f90c50b4';
    /**
     * M函数用于实例化一个没有模型文件的Model
     * @param string $name Model名称 支持指定基础模型 例如 MongoModel:User
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     * @return Model
     */

    /*
     * push_by_device 函数用户推送设备消息
     * @param string $alias 设备的pushtoken，多个token用逗号隔开
     * @param string $notification_alert 通知title
     * @param string $msg_content 消息主体
     * $param string $product_env 如果是true ，表示生产环境，否则为测试环境（只有ios调用需要）
     */
    public function push_by_device($alias,$msg_content='',$notification_alert='',$product_env=false){

        $result['status'] = 1;
        $result['info'] = 'Success';
        $arg = I('data');
        $jsondata = '';

        $platform=array('ios', 'android');

        //解析notification

        if(!$alias){
            $result['status'] = 0;
            $result['info'] = 'No PushTokens!!';
        }

        if(!$msg_content){
            $result['status'] = 0;
            $result['info'] = 'No Message Content!!';
        }
        if($result['status']){
            $base64 = base64_encode(PushService::JIGUANG_USER.':'.PushService::JIGUANG_PASS);
            $app_key = PushService::JIGUANG_USER;
            $master_secret = PushService::JIGUANG_PASS;

            // 初始化
            $client = new \JPush($app_key, $master_secret);

            // 完整的推送示例,包含指定Platform,指定Alias,Tag,指定iOS,Android notification,指定Message等
            // $push->setOptions($sendno=null, $time_to_live=null, $override_msg_id=null, $apns_production=null, $big_push_duration=null)

            try {
                $response = $client->push()
                    ->setPlatform($platform)
                    ->addAlias($alias)
                    ->setNotificationAlert($notification_alert)
                    ->addAndroidNotification($msg_content, $msg_title, 1, array("type"=>"message", "video_id"=>"$msg_extras_video_id"))
                    ->addIosNotification($msg_content, 'default', \JPush::DISABLE_BADGE, true, 'iOS category', array("type"=>"message", "video_id"=>"$msg_extras_video_id"))
                    //->setMessage($msg_content, $msg_title,$msg_content_type, array("video_id"=>$msg_extras_video_id))
                    ->setOptions(100000, null, null, $product_env)
                    ->send();
                if($result['status']){
                    if(isset($response->data)){
                        M('push')->add($push_video);//添加推送视频
                        $info = "Push Success!!";
                        $status = 1;
                    }else{
                        $info = "No Response!";
                        $status = 0;
                    }
                    $result['status'] = $status;
                    $result['info'] = $info;

                    $push_data['info'] = $info;
                    $push_data['status'] = $status;
                    M('push_log')->add($push_data);
                }
            } catch (\JPush\Exceptions\APIConnectionException $e) {
                // try something here
                $result['status'] = 0;
                $result['info'] = $e;
            } catch (\JPush\Exceptions\APIRequestException $e) {
                // try something here
                $result['status'] = 0;
                $result['info'] = $e;
            }

        }
        return $result;
    }


    /*
     * test code
     * $pusher = Service('Push');
       $re = $pusher->push_by_device('55e0a71b03924b2da2fe5474916d5dfb','nihao','haha',false,'like_video',3);
     * push_by_device 函数用户推送设备消息
     * @param string $alias 设备的pushtoken，多个token用逗号隔开
     * @param string $notification_alert 通知title
     * @param string $msg_content 消息主体
     * $param string $product_env 如果是true ，表示生产环境，否则为测试环境（只有ios调用需要）
     * $param string $type 支持 like_video,review_video,like_video_review,follow,gift,refer 类型
     *  @param string $from_user_id 来自谁的消息，在评论和点赞的时候需要
     */
    public function push_by_device_v2($alias,$msg_content='',$notification_alert='',$product_env=false,$type='',$from_user_id=''){

        
        //$name = M('User')->getField($from_user_id, 'name');

        $u = M('User')->findOne($from_user_id,'name');
        $name = $u['name'];

        if($type=='refer'){
            $msg_content = 'قام  بذكرك '.$name;
        }else if($type == 'review_video'){
            $msg_content = 'الفيديو اللذي علّقت عليه '.$name;
        }else if($type == 'like_video'){
            $msg_content = 'أعجبه الفيديو الخاص بك '.$name;
        }else if($type == 'like_video_review'){
            $msg_content = 'التعليق اللذي أعجبك '.$name;
        }else if($type == 'follow'){
            $msg_content = 'قام بمتابعتك '.$name;
        }


        $result['status'] = 1;
        $result['info'] = 'Success';
        $valid=$this->_check_push_valid($alias,$type,$from_user_id);
        if(!$valid){
            $result['status'] = 0;
            $result['info'] = 'not valid!';
            return $result;
        }

        $platform=array('ios', 'android');

        //解析notification

        if(!$alias){
            $result['status'] = 0;
            $result['info'] = 'No PushTokens!!';
        }

        if(!$msg_content){
            $result['status'] = 0;
            $result['info'] = 'No Message Content!!';
        }
        if($result['status']){
            $base64 = base64_encode(PushService::JIGUANG_USER.':'.PushService::JIGUANG_PASS);
            $app_key = PushService::JIGUANG_USER;
            $master_secret = PushService::JIGUANG_PASS;

            // 初始化
            $client = new \JPush($app_key, $master_secret);

            // 完整的推送示例,包含指定Platform,指定Alias,Tag,指定iOS,Android notification,指定Message等
            // $push->setOptions($sendno=null, $time_to_live=null, $override_msg_id=null, $apns_production=null, $big_push_duration=null)

            try {
                $response = $client->push()
                    ->setPlatform($platform)
                    ->addAlias($alias)
                    ->setNotificationAlert($notification_alert)
                    ->addAndroidNotification($msg_content, $msg_title, 1, array("type"=>"message", "video_id"=>"$msg_extras_video_id"))
                    ->addIosNotification($msg_content, 'default', \JPush::DISABLE_BADGE, true, 'iOS category', array("type"=>"message", "video_id"=>"$msg_extras_video_id"))
                    //->setMessage($msg_content, $msg_title,$msg_content_type, array("video_id"=>$msg_extras_video_id))
                    ->setOptions(100000, null, null, $product_env)
                    ->send();
                if($result['status']){
                    if(isset($response->data)){
                        M('push')->add($push_video);//添加推送视频
                        $info = "Push Success!!";
                        $status = 1;
                    }else{
                        $info = "No Response!";
                        $status = 0;
                    }
                    $result['status'] = $status;
                    $result['info'] = $info;

                    $push_data['info'] = $info;
                    $push_data['status'] = $status;
                    M('push_log')->add($push_data);
                }
            } catch (\JPush\Exceptions\APIConnectionException $e) {
                // try something here
                $result['status'] = 0;
                $result['info'] = $e;
            } catch (\JPush\Exceptions\APIRequestException $e) {
                // try something here
                $result['status'] = 0;
                $result['info'] = $e;
            }

        }
        return $result;
    }

    private function _check_push_valid($alias,$type,$from_user_id){


        if(!$type){
            return true;
        }
        if(!$alias){
            return false;
        }
        $u_cond['push_token'] = $alias;
        $user = M('user')->where($u_cond)->find();

        if(!$user){
            return false;
        }
        $user_id = $user['user_id'];
        $p_cond['user_id'] = $user_id;

        $push_setting = M('user_push_setting')->where($p_cond)->find();

        if(!$push_setting){
            return true;
        }
        $result = false;
        switch ($type){
            case 'like_video':
                $type = $push_setting['like_video'];
                $result =$this->_check_follow_valid($type,$user_id,$from_user_id);
                break;
            case 'review_video':
                $type = $push_setting['review_video'];
                $result = $this->_check_follow_valid($type,$user_id,$from_user_id);
                break;
            case 'like_video_review':
                $type = $push_setting['like_video_review'];
                $result =$this->_check_valid($type);
                break;
            case 'follow':
                $type = $push_setting['follow'];
                $result =$this->_check_valid($type);
                break;
            case 'gift':
                $result =$type = $push_setting['gift'];
                $this->_check_valid($type);
                break;
            case 'refer':
                $type = $push_setting['refer'];
                $result =$this->_check_valid($type);
                break;
        }
        return $result;
    }
    private function _check_follow_valid($type,$to_user_id,$from_user_id)
    {

        if(!$from_user_id){
            return false;
        }
        if ($type == 'off') {
            return false;
        } elseif ($type == 'everyone') {
            return true;
        } else {
            $cond['star_user_id'] = $to_user_id;
            $cond['follower_user_id'] = $from_user_id;

            $cc = M('follow')->where($cond)->count();

            if ($cc) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function _check_valid($type){
        if($type=='off'){
            return false;
        }else{
            return true;
        }
    }


}