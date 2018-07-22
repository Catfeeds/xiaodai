<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\PasswordUtil;
use Common\Lib\VercodeUitls;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\FormatUtils;
use Common\Lib\CDNUtils;

/**
 * User信息
 */
class UserController extends IController{

    
    #第三方
    public function login_from_open_platform(){

        $open_platform=IV('open_platform','require');
        $open_id=IV('open_id','require');
        $open_name=IV('open_name','require');
        $open_token=IV('open_token','require');
        $open_email=IV('open_email','email');
        $open_image=IV('open_image','require');
        $push_token = IV('push_token');

        $gender = IV('gender');
        $age = IV('age');
        $birthday = IV('birthday');
        $longitude = IV('longitude');
        $latitude = IV('latitude');
        $country = IV('country');

        $f= D('User')->isRegisterOpenPlatform($open_platform,$open_id,$user_id);

        if(!$f){

            $u=array(
                'name'=>$open_name,
                'image'=>$open_image,
                'push_token'=>$push_token,
            );
            ArrayUtils::CollectIfNotNULL($u,'age',$age);
            ArrayUtils::CollectIfNotNULL($u,'gender',$gender);
            ArrayUtils::CollectIfNotNULL($u,'birthday',$birthday);
            ArrayUtils::CollectIfNotNULL($u,'longitude',$longitude);
            ArrayUtils::CollectIfNotNULL($u,'latitude',$latitude);
            ArrayUtils::CollectIfNotNULL($u,'country',$country);

            $user_id = M('User')->add($u);

            $dt=array(
                'open_platform'=>$open_platform,
                'open_id'=>$open_id,
                'open_name'=>$open_name,
                'open_token'=>$open_token,
                'open_email'=>$open_email,
                'open_image'=>$open_image,
                'created'=>DateUtils::Now(),
                'user_id'=>$user_id,
            );
            M('user_open_platform')->add($dt);
            
        }else{

            // 保存
            $u= array(
                'user_id'=>$user_id,
                'push_token'=>$push_token
            );
            ArrayUtils::CollectIfNotNULL($u,'age',$age);
            ArrayUtils::CollectIfNotNULL($u,'gender',$gender);
            ArrayUtils::CollectIfNotNULL($u,'birthday',$birthday);
            ArrayUtils::CollectIfNotNULL($u,'longitude',$longitude);
            ArrayUtils::CollectIfNotNULL($u,'latitude',$latitude);
            ArrayUtils::CollectIfNotNULL($u,'country',$country);

            M('user')->save($u);
        }

        // 获取用户信息
        $user_info = D('User')->getInfo($user_id);
        $user_info['token'] = D('UserToken')->genToken($user_id);

        $this->iSuccess($user_info,'user_info');
    }

    # 获取绑定的开放平台
    /*
    public function get_bind_open_platforms(){

        $token = IV('token''require');
        $user_id = $this->checkToken($token);
        M('user_open_platform')->where()->field('platform,open_id,open_token,open_email,open_image,open_name');
    }*/


    # 获取我自己的信息
    public function get_my_info(){

        $user_id = IV('token','require|token');
        
        //$user_id = $this->checkToken($token);

        $info = D('user')->getInfo($user_id);

        $info['video_count'] = D('Video')->getVideoCount($user_id);
        $info['follower_count'] = D('Follow')->getFollowerCount($user_id);
        $info['star_count'] = D('Follow')->getStarCount($user_id);
        $info['repost_count'] = D('Video')->getTimelineCount($user_id);

        $this->iSuccess($info,'user_info');
    }


    # 获取用户主页的信息
    public function get_host_user_info(){

        $user_id = IV('token','token');
        $host_user_id = IV('host_user_id','require');//主页ID

        $info = D('user')->getInfo($host_user_id);

        $info['video_count'] = D('Video')->getVideoCount($host_user_id); 
        $info['follower_count'] = D('Follow')->getFollowerCount($host_user_id);
        $info['star_count'] = D('Follow')->getStarCount($host_user_id);
        $info['like_count'] = D('User')->likeCount($host_user_id);
        $info['has_follow'] = D('Follow')->hasFollow($user_id,$host_user_id); # 是否关注
                
        $this->iSuccess($info);
    }

    # 转发
    public function repost_video(){

        $user_id = IV('token','require|token');
        $video_id = IV('video_id','require');

        $dt=array(
            'create_time'=>DateUtils::Now(),
            'user_id'=>$user_id,
            'type'=>'repost',
            'video_id'=>$video_id,
            'video_user_id'=>M('video')->findField($video_id,'user_id')
        );

        M('user_timeline')->add($dt);

        $this->iSuccess();
    }

    # 喜欢视频
    public function like_video(){

        $user_id = IV('token','require|token');
        $video_id = IV('video_id','require');

        if(D('Video')->hasLikeVideo($user_id,$video_id)){
            $this->iSuccess();
        }

        $dt=array(
            'user_id'=>$user_id,
            'video_id'=>$video_id,
            'like_time'=>DateUtils::Now()
        );
        M('video_like')->add($dt);

    ################ 发送消息 #####################################
        $receive_user_id = D('Video')->findField($video_id,'user_id');
        D('Notice')->send($receive_user_id,$user_id,$video_id,"like_video",$text);
    ################################################################
        
        Service('VideoWeight')->addLikeWeight($video_id);

        $this->iSuccess();
    }


    # 不喜欢视频
    public function unlike_video(){

        $user_id = IV('token','require|token');
        $video_id = IV('video_id','require');

        $co=array(
            'user_id'=>$user_id,
            'video_id'=>$video_id
        );
        M('video_like')->where($co)->delete();
        $this->iSuccess();
    }


    # 反馈
    public function feedback(){

        $user_id = IV('token','token');
        $contact = IV('contact');
        $content = IV('content','require');

        $dt=array(
            'user_id'=>$user_id,
            'contact'=>$contact,
            'content'=>$content,
            'create_time'=>DateUtils::Now()
        );
        M("feedback")->add($dt);

        $this->iSuccess();
    }


    # 添加黑名单
    public function blockuser(){
        $user_id = IV('user_id','user_id');

        /*
        $dt=array(
            'user_id'=>$user_id,
            'blocked'=>1
        );
        */
        M("user")->save($dt);
        $this->iSuccess();
    }

    # 获取黑名单列表
    public function getblocklist(){
        
        /*
        $cond=array(
            'blocked'=>1
        );
        $user_ids = M("user")->where($cond)->field('user_id')->select();
        $ulist = D('User')->getList($user_ids);
        */

        $ulist=array();
        $this->iSuccess($ulist,'block_list');
    }


    # 获取用户的排行榜
    public function get_user_ranking(){

       $page = I('page',1);
       $limit = I('limit',10);

       $co=array(
         'tag_id'=>$tag_id,
       );

       $ulist = M('user_ranking')->field('user_name,user_image,user_id,like_count')
                                             ->where($co)
                                             ->cache(60*10)
                                             ->page($page)->limit($limit)
                                             ->order('like_count desc')
                                             ->select();
                                             
       CDNUtils::ImageCDNArray($ulist,'user_image');
       $this->iSuccess($ulist,'user_ranking');
    }


    // 更新用户推送设置
    public function updateUserPushSetting(){

        $user_id = IV('token','token_check');

        $like_video = IV("like_video",'require');
        $review_video = IV("review_video",'require');
        $like_video_review = IV("like_video_review",'require');
        $follow = IV("follow",'require');
        $gift = IV("gift",'require');
        $refer =IV("refer",'require');

        $co=array(
            'user_id'=>$user_id
        );
        $dt=array(
            'like_video'=>$like_video,
            'review_video'=>$review_video,
            'like_video_review'=>$like_video_review,
            'follow'=>$follow,
            'gift'=>$gift,
            'refer'=>$refer,
        );
        $setting = M('user_push_setting')->where($co)->save($dt);

        $this->iSuccess();
    }


    public function getUserPushSetting(){

        $user_id = IV('token','token_check');
        $co=array(
            'user_id'=>$user_id
        );
        $setting = M('user_push_setting')->where($co)->find($co);
        if($setting){
            $this->iSuccess($setting,'user_push_setting');
        }else{
            $dt=array(
                'user_id'=>$user_id
            );
            M('user_push_setting')->add($dt);
            $setting = M('user_push_setting')->find($co);
            $this->iSuccess($setting,'user_push_setting');
        }
    }
}