<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\SortUtils;
use Common\Lib\String;
use Common\Lib\QiniuUtils;
use Common\Lib\Logger;
use Common\Lib\LangUtils;
use Common\Lib\CDNUtils;

class VideoController extends IController{


    # 获取用户的视频列表
    public function get_host_video_list(){

        $host_user_id = IV('host_user_id');
        $guest_user_id = IV('token','token');
        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');
       
        # ==========
        $co=array(
            'user_id'=>$host_user_id
        );

        $fds=array(
            'create_time',
            'video_id',
            'type'
        );

        $list = M('user_timeline')->where($co)->field($fds)
                                  ->page($page)->limit($limit)->order('create_time desc')
                                  ->select();
        foreach ($list as &$li) {
            $li['video_info'] = D('Video')->getInfo($li['video_id'],$lang,null,$guest_user_id);
            D('Video')->optImageOfVideo($li['video_info']);
        }

        $this->iSuccess($list,'video_timeline');
    }

    # 获取自己的视频列表
    public function get_my_video_list(){

        $host_user_id = IV('token','require|token');
        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');

        # ==========
        $co=array(
            'user_id'=>$host_user_id
        );

        $fds=array(
            'create_time',
            'video_id',
            'type'
        );

        $list = M('user_timeline')->where($co)->field($fds)
                                  ->page($page)->limit($limit)->order('create_time desc')
                                  ->select();
        foreach ($list as &$li) {
            $li['video_info'] = D('Video')->getInfo($li['video_id'],$lang,null,$host_user_id);
            D('Video')->optImageOfVideo($li['video_info']);
        }

        $this->iSuccess($list,'video_timeline');
    }

    # 获取主页的视频列表
    public function get_host_video_list2(){

        $host_user_id = IV('host_user_id','require');
        $guest_user_id = IV('token','token');
        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');

        $co=array(
            'user_id'=>$host_user_id
        );

        $fds=array(
            'video_id'
        );

        $list = M('video')->where($co)->field($fds)
                                  ->page($page)->limit($limit)->order('create_time desc')
                                  ->select();

        if(empty($list)){
            $this->iSuccess(array(),'vlist');
        }

        $vids = ArrayUtils::Collect($list,'video_id');

        $vlist = D('Video')->getList($vids,$lang,null,$guest_user_id);
        SortUtils::InsertionSort($vlist,"create_time","desc");

        D('Video')->optImageOfVideoList($vlist);

        $this->iSuccess($vlist,'vlist');
    }

    # 获取主页转发的视频列表
    public function get_host_repost_video_list(){

        $host_user_id = IV('host_user_id','require');
        $guest_user_id = IV('token','token');

        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');

        # ==========
        $co=array(
            'user_id'=>$host_user_id,
            'type'=>'repost'
        );

        $fds=array(
            'create_time',
            'video_id',
            'type'
        );

        $list = M('user_timeline')->where($co)->field($fds)
                                  ->page($page)->limit($limit)->order('create_time desc')
                                  ->select();
        
        $vlist= array();
        foreach ($list as &$li) {
            $li['video_info'] = D('Video')->getInfo($li['video_id'],$lang,null,$user_id);
            D('Video')->optImageOfVideo($li['video_info']);
            array_push($vlist,$li['video_info']);
        }

        $this->iSuccess($vlist,'vlist');
    }

    # 获取用户的视频列表
    public function get_my_video_list2(){

        $guest_user_id = IV('token','require|token');
        $host_user_id = $guest_user_id;

        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');

        $co=array(
            'user_id'=>$host_user_id
        );

        $fds=array(
            'video_id'
        );

        $list = M('video')->where($co)->field($fds)
                                  ->page($page)->limit($limit)->order('create_time desc')
                                  ->select();

        if(empty($list)){
            $this->iSuccess(array(),'vlist');
        }

        $vids = ArrayUtils::Collect($list,'video_id');
        $vlist = D('Video')->getList($vids,$lang,null,$guest_user_id);
        SortUtils::InsertionSort($vlist,"create_time","desc");

        D('Video')->optImageOfVideoList($vlist);

        $this->iSuccess($vlist,'vlist');
    }

    # 获取用户转发的视频列表
    public function get_my_repost_video_list(){

        $guest_user_id = IV('token','require|token');
        $host_user_id = $guest_user_id;

        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');

        # ==========
        $co=array(
            'user_id'=>$host_user_id,
            'type'=>'repost'
        );

        $fds=array(
            'create_time',
            'video_id',
            'type'
        );

        $list = M('user_timeline')->where($co)->field($fds)
                                  ->page($page)->limit($limit)->order('create_time desc')
                                  ->select();
        
        $vlist= array();
        foreach ($list as &$li) {
            $li['video_info'] = D('Video')->getInfo($li['video_id'],$lang,null,$user_id);
            D('Video')->optImageOfVideo($li['video_info']);
            array_push($vlist,$li['video_info']);
        }

        $this->iSuccess($vlist,'vlist');
    }

    public function upload_video(){

        $user_id = IV('token','require|token');
        $image = IV('image','require');
        $video = IV('video','require');
        $title = IV('title');

        $topic_id = IV('topic_id');
        $content_lang = I('content_lang','en');
        $image_width = IV('image_width','require');
        $image_height = IV('image_height','require');
        $latitude = IV('latitude');
        $longitude = IV('longitude');

        $stat_upload_start_time = IV('stat_upload_start_time');
        $stat_upload_end_time = IV('stat_upload_end_time');
        $stat_video_timelength = IV('stat_video_timelength');
        $stat_video_size = IV('stat_video_size');
        $refer_user_list = IV('refer_user_list');//[{"user_id":111,"user_name"}]
        $stat_upload_from = IV('stat_upload_from');

        $dt=array(
            'user_id'=>$user_id,
            'image'=>$image,
            'title_ar'=>$title,
            'title_en'=>$title,
            'create_time'=>DateUtils::Now(),
            'video_from'=>'app',
            'video'=>$video,
            'lang'=>String::checkLang($title),
            'image_width'=>$image_width,
            'image_height'=>$image_height,
            'refer_user_list'=>$refer_user_list,
            'longitude'=>$longitude,
            'latitude'=>$latitude
        );

        if($dt['stat_upload_start_time']){
            $dt['stat_upload_start_time'] = $stat_upload_start_time;
        }
        if($dt['stat_upload_end_time']){
            $dt['stat_upload_end_time'] = $stat_upload_end_time;
        }
        if($dt['stat_video_timelength']){
            $dt['stat_video_timelength'] = $stat_video_timelength;
        }
        if($dt['stat_video_size']){
            $dt['stat_video_size'] = $stat_video_size;
        }
        if($dt['stat_upload_from']){
            $dt['stat_upload_from'] = $stat_upload_from;
        }

        #############

        // 定位 *****
        try{
            if($latitude&&$longitude){
                $location = Service('Map')->location($longitude,$latitude);
                if($location){
                    $dt = array_merge($dt,$location);
                }
            }
        }catch(\Think\Exception $r){}
        // **********

        $video_id = M('video')->add($dt);
        if($video_id){

            // =========== 加入时间线 ===============
            $dt=array(
                'create_time'=>DateUtils::Now(),
                'user_id'=>$user_id,
                'type'=>'upload',
                'video_id'=>$video_id,
                'video_user_id'=>$user_id
            );
            M('user_timeline')->add($dt);
            // ======================================
            
            if($topic_id){

                $dt=array(
                    'video_id'=>$video_id,
                    'topic_id'=>$topic_id,
                    'create_time'=>DateUtils::Now(),
                    'order_weight'=>1,
                    'lang'=>D('Video')->findField($video_id,'lang')
                );
                M('video_to_topic')->add($dt);
            }

            // 自动识别tag
            Service('Tag')->identify_tags($video_id,$title);

        }else{
            IE(ERROR_SERVER_DB);
        }

        $data = D('Video')->getInfo($video_id);

        $this->iSuccess($data);
    }

    // 删除视频
    public function delete_video(){

        $user_id = IV('token','require|token');
        $video_id = IV('video_id','require');
        $co=array(
            'user_id'=>$user_id,
            'video_id'=>$video_id
        );
        M('video')->where($co)->delete();

        //=========================
        $co=array(
            'video_id'=>$video_id
        );
        M('video_like')->where($co)->delete();
        M('user_timeline')->where($co)->delete();
        M('video_to_tag')->where($co)->delete();
        M('video_to_topic')->where($co)->delete();
        M('video_ranking')->where($co)->delete();
        M('notice')->where($co)->delete();
        M('wallet_income_record')->where($co)->delete();
        M('wallet_consume_record')->where($co)->delete();
        //========================

        $this->iSuccess();
    }

    // 添加视频播放统计
    public function inc_video_view_count(){

        $video_id = IV('video_id','require');
        $user_id=IV('token','token');
        $device_id = IV('device_id','require');

        $co=array(
            'video_id'=>$video_id
        );
        M('video')->where($co)->setInc('base_view_count',1);

        //////////////////////
        $dt=array(
            'video_id'=>$video_id,
            'user_id'=>$user_id,
            'view_time'=>DateUtils::Now(),
            'device_id'=>$device_id,
            'lang'=>D('Video')->findField($video_id,'lang'),
            'video_from'=>D('Video')->findField($video_id,'video_from'),
        );
        M('video_to_view')->add($dt);
        /////////////////////
        
        #　记录观看的视频的tag
        //Service('VideoMark')->mark_like_video_tags($device_id,$user_id,$video_id);
        
        Service('VideoWeight')->addViewWeight($video_id);

        $this->iSuccess();
    }

    // 获取跟随的人的视频列表
    public function get_video_list_of_star(){

        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');
        $user_id = IV('token','require|token');

        $co=array(
            'follower_user_id'=>$user_id
        );
        $list = M('follow')->where($co)->field('star_user_id')->order('follow_time desc')->select();
        $star_user_ids = ArrayUtils::Collect($list,'star_user_id');

        if(empty($star_user_ids)){
            $this->iSuccess(array(),'vlist');
        }

        $co=array(
            'user_id'=>array('in',$star_user_ids),
            'status'=>1
        );
        $list = D('Video')->getConditionList($vids,$lang,$ms,$co,$page,$limit,'create_time desc',$user_id);

        // 处理图片
        D('Video')->optImageOfVideoList($list);
        
        $this->iSuccess($list,'vlist');
    }


    // 获取视频信息
    public function get_video_info(){

        $lang  = IV('lang','require');
        $guest_user_id = IV('token','token');
        $video_id = IV('video_id','require');

        $video_info = D("Video")->getInfo($video_id,$lang,null,$guest_user_id);

        $this->iSuccess($video_info,'video_info');
    }

    // 获取
    public function get_push_video_list(){

        $guest_user_id = IV('token','token');
        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');

        $pList = M('Push')->order('create_time desc')->page($page)->limit($limit)->select();

        foreach ($pList as &$push) {
            $push['video_info'] = D("Video")->getInfo($push['video_id'],$lang,null,$guest_user_id);
        }

        $this->iSuccess($pList,'push_list');
    }

    // 分享的统计
    public function inc_share_count(){

        $user_id = IV('token','token');
        $video_id = IV('video_id','require');
        $share_platform = IV('share_platform','require');

        $dt=array(
            'create_time'=>DateUtils::Now(),
            'video_id'=>$video_id,
            'share_platform'=>$share_platform,
        );
        if($user_id){
            $dt['user_id'] = $user_id;
        }
        M("video_share")->add($dt);

        $this->iSuccess();
    }

    // 获取相关的视频
    public function get_related_video_list(){

        $lang  = IV('lang','require');
        $limit = IV('limit','require');      
        $user_id = IV('token','token');
        $content_lang = IV('content_lang'); # 内容的语言, ar：阿语 en:英语 ，没有则表示不区分
        $video_id = IV('video_id','require');

        $co=array(
            'video_id'=>array('lt',$video_id)
        );
        if($content_lang){
            $co['lang'] = array('in',array($content_lang,'all'));
        }

        $list = M('video')->where($co)->field('video_id')->limit($limit)->page(1)->cache(1000)->order('video_id desc')->select();
        $video_ids = ArrayUtils::Collect($list,'video_id');
        $list = D('Video')->getList($video_ids,$lang,null,$user_id);

        $this->iSuccess($list,'video_list');
    }

    // 获取视频的礼物列表
    public function get_gift_list_of_video(){

        $video_id = IV('video_id','require');
        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');

        $co=array(
            'video_id'=>$video_id
        );

        $count = M('wallet_consume_record')->where($co)->count();
        $gift_list = M('wallet_consume_record')->where($co)->page($page)->limit($limit)->select();

        foreach ($gift_list as &$gift) {
            LangUtils::LangObj($gift,"consume_gift_name",$lang);
            LangUtils::LangObj($gift,"video_title",$lang);

            $gift['user_image'] = M('user')->findField($gift['video_user_id'],'image');
        }

        CDNUtils::ImageCDNArray($gift_list,'video_image');
        CDNUtils::ImageCDNArray($gift_list,'user_image');

        $dt=array(
            'gift_list'=>$gift_list,
            'count'=>$count
        );
        $this->iSuccess($dt);
    }
    


    public function location(){

        $latitude = IV('latitude','require');
        $longitude = IV('longitude','require');

        $location = Service('Map')->location($longitude,$latitude);

        if(!$location){
            $this->iSuccess(array(),'location');
        }

        $this->iSuccess($location,'location');
    }
}