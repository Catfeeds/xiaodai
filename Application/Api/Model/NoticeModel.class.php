<?php
namespace Rest3\Model;

use Think\Model;
use Common\Lib\String;
use Common\Lib\Date;
use Common\Lib\DateUtils;



/**
 *  type : 
 *      1. refer 表示提及到
 *      2. review_video 评论
 *      3. like_video 赞视频
 *      4. like_video_review 赞视频的评论
 *      5. follow
 */
class NoticeModel extends Model{
    

    public function send($receive_user_id,$send_user_id,$video_id,$type,$content){


        if(!$receive_user_id||!$send_user_id){
            return;
        }

        // 如果是自己则不发送通知
        if($receive_user_id==$send_user_id){
            return;
        }


        /*
        1. xxxx 赞了你的视频
        应该改为
        أعجبه الفيديو الخاص بك xxxx

        2. xxxx 送给你了一个礼物
        应该改为
        أرسل لك هديه xxxx
        */      
    
        if($type=='refer'){
            $content = 'قام  بذكرك';
        }else if($type == 'review_video'){
            $content = 'الفيديو اللذي علّقت عليه';
        }else if($type == 'like_video'){
            $content = 'أعجبه الفيديو الخاص بك ';
        }else if($type == 'like_video_review'){
            $content = 'التعليق اللذي أعجبك';
        }else if($type == 'follow'){
            $content = 'قام بمتابعتك';
        }else if($type == 'give_gift'){

            $type='like_video';
            $content ="هدية ";
        }


        $dt=array(
            'receive_user_id'=>$receive_user_id,
            'send_user_id'=>$send_user_id,
            'video_id'=>$video_id,
            'type'=>$type,
            'content'=>$content,
            'create_time'=>DateUtils::Now(),
        );
        D('Notice')->add($dt);

        ################ 推送消息 ###############
        $pUser =  M('User')->findOne($receive_user_id,'push_token');
        Service('Push')->push_by_device_v2($pUser['push_token'],$message,$notification_alert='',C("PUSH_DEV"),$type,$send_user_id);
        #########################################
    }
}