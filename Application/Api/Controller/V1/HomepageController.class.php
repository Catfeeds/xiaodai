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
use Common\Lib\MapUtils;

class HomepageController extends IController{


    public function getNewestVideos(){

        $guest_user_id = IV('token','token');
        $device_id =IV('device_id','require');
        $page = IV('page','require');

        $co=array('status'=>1);
        $list = M('video')->group('user_id')->where($co)->field('video_id')->page($page)->limit(30)->order('create_time desc')->select();
        $video_ids = ArrayUtils::Collect($list,'video_id');

        $vlist=array();
        foreach ($video_ids as $video_id) {
            $vinfo = D("Video")->getInfo($video_id,$lang='ar',$ms,$guest_user_id);
            array_push($vlist,$vinfo);
        }

        $this->iSuccess($vlist,'video_list');
    }
    
    public function getRecommodVideos(){

        $guest_user_id = IV('token','token');
        $device_id =IV('device_id','require');

        $exclude_video_ids = Service('VideoMark')->get_mark_video_ids($device_id,10001);
        $video_ids = Service('VideoMark')->get_recommod_video_ids($device_id,$guest_user_id,$exclude_video_ids);

        $vlist=array();
        foreach ($video_ids as $video_id) {
            $vinfo = D("Video")->getInfo($video_id,$lang='ar',$ms,$guest_user_id);
            array_push($vlist,$vinfo);
        }
        Service('VideoMark')->mark_video_ids($device_id,10001,$vids);

        $this->iSuccess($vlist,'video_list');
    }


    public function getExploreVideos(){

        $guest_user_id = IV('token','token');
        $device_id =IV('device_id','require');
        $exclude_video_ids = Service('VideoMark')->get_mark_video_ids($device_id,10000);
        $co=array();
        if(!empty($exclude_video_ids)){
            $co=array(
                'video_id'=>array('not in',$exclude_video_ids)
            );
        }
        $list = M('hot_video')->group('user_id')->where($co)->limit(30)->order('order_weight desc')->select();
        $vids = ArrayUtils::Collect($list,'video_id');

        foreach ($list as &$li) {

            FormatUtils::JsonDecoder($li,'user_info');
            FormatUtils::JsonDecoder($li,'refer_user_list');

            if($guest_user_id){
                $li['has_like_video'] = D('video')->hasLikeVideo($guest_user_id,$li['video_id']);
                $li['has_follow'] = D('Follow')->hasFollow($guest_user_id,$li['user_id']);
            }

            $li['review_count'] = D('video')->getReviewCount($li['video_id']);
            $li['video_like_count'] = D('video')->getVideoLike($li['video_id']);
            $li['video_view_count'] = D('video')->getVideoViewCount($li['video_id']);
        }
        Service('VideoMark')->mark_video_ids($device_id,10000,$vids);
        $this->iSuccess($list,'video_list');
    }

    public function getNearestVideos(){

        $longitude = IV('longitude','require');
        $latitude = IV('latitude','require');
        $device_id =IV('device_id','require');
        $user_id = IV('token','token');
        $page=IV('page','require');
        $limit = IV('limit','require');

        $location = Service("Map")->location($longitude,$latitude);
        $country = $location['country'];
        $city = $location['city'];

        $videos = $this->_getCityOrCountryVideo($city,$country,$latitude, $longitude);
        $videos = ArrayUtils::Paging($videos,$page,$limit);

        $video_list=array();
        foreach ($videos as  $v) {
            
            $res = D('Video')->getInfo($v['video_id']);
            $res['distance'] = $v['distance'];
            array_push($video_list,$res);
        }

        $this->iSuccess($video_list,'video_list');
    }

    private function _getCityOrCountryVideo($city,$country,$latitude, $longitude){

        $co=array(
            'country'=>$country
        );
        $list = M('video')->field('video_id,latitude,longitude,country,city')->cache(60)->limit(2000)->where($co)->order('create_time desc')->select();

        $vlist=array();
        foreach ($list as $li) {
            if($li['latitude']&&$li['longitude']){
                $li['distance'] = MapUtils::getDistance($li['latitude'], $li['longitude'], $latitude, $longitude);
                array_push($vlist,$li);
            }
        }
        SortUtils::InsertionSort($vlist,'distance','ins');

        return  $vlist;
    }
}