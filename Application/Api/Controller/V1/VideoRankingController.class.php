<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\SortUtils;
use Common\Lib\TPUtils;

class VideoRankingController extends IController{

    // 获取最近一天的视频排行
    public function get_lastday_video_ranking(){

        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');
        $guest_user_id = IV('token','token');
        $content_lang = I('content_lang');
        
        $co=array(
            'ranking_type'=>'day'
        );
        if($content_lang){
            $co['video_lang'] = array('in',array($content_lang,'all'));
        }

        $video_list = M('video_ranking')->where($co)->page($page)->limit($limit)->select();
        $video_ids = ArrayUtils::Collect($video_list,'video_id');

        $vlist = array();
        foreach ($video_ids as $video_id) {
            $v = D('Video')->getInfo($video_id,$lang,$ms,$guest_user_id);
            array_push($vlist,$v);
        }

        D('Video')->optImageOfVideoList($vlist);

        $this->iSuccess($vlist,'vlist');
    }

    // 获取最近一周的视频排行
    public function get_lastweek_video_ranking(){

        $lang  = IV('lang','require');
        $limit = IV('limit','require');
        $page = IV('page','require');
        $guest_user_id = IV('token','token');
        $content_lang = I('content_lang');

        $co=array(
            'ranking_type'=>'week'
        );
        if($content_lang){
            $co['video_lang'] = array('in',array($content_lang,'all'));
        }

        $video_list = M('video_ranking')->where($co)->page($page)->limit($limit)->select();
        $video_ids = ArrayUtils::Collect($video_list,'video_id');

        $vlist = array();
        foreach ($video_ids as $video_id) {
            $v = D('Video')->getInfo($video_id,$lang,$ms,$guest_user_id);
            array_push($vlist,$v);
        }

        D('Video')->optImageOfVideoList($vlist);

        $this->iSuccess($vlist,'vlist');
    }

    // 跑最近一天的视频排行
    public function run_lastday_video_ranking(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        ###############
        # 删除之前的记录
        $co=array(
            'ranking_type'=>'day'
        );
        M('video_ranking')->where($co)->delete();
        ###############

        $date_start = DateUtils::Yesterday();
        $date_end = DateUtils::Today();

        $co=array(
            'view_time'=>TPUtils::BetweenTwoDay($date_start,$date_end),
        );
        $list = M('video_to_view')->where($co)->field('video_id')->select();
        $video_ids = ArrayUtils::Collect($list,'video_id');

        ArrayUtils::Count($video_ids);
        SortUtils::InsertionSort($video_ids,'count','desc');
        $video_ids = ArrayUtils::Paging($video_ids,1,2000);

        $i=2000;
        $update_time = DateUtils::Now();
        foreach ($video_ids as $video_count_info) {
            $this->_insert_ranking($video_count_info['key'],'day',$video_count_info['count'],$update_time);
        }

        echo 'success';
    }

    // 跑最近一周的视频排行
    public function run_lastweek_video_ranking(){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        ###############
        # 删除之前的记录
        $co=array(
            'ranking_type'=>'week'
        );
        M('video_ranking')->where($co)->delete();
        ###############

        $date_start = date("Y-m-d",strtotime("-7 day")); //DateUtils::Yesterday();
        $date_end = DateUtils::Today();

        $co=array(
            'view_time'=>TPUtils::BetweenTwoDay($date_start,$date_end),
        );
        $list = M('video_to_view')->where($co)->field('video_id')->select();
        $video_ids = ArrayUtils::Collect($list,'video_id');

        ArrayUtils::Count($video_ids);
        SortUtils::InsertionSort($video_ids,'count','desc');
        $video_ids = ArrayUtils::Paging($video_ids,1,2000);

        $i=2000;
        $update_time = DateUtils::Now();
        foreach ($video_ids as $video_count_info) {
            $this->_insert_ranking($video_count_info['key'],'week',$video_count_info['count'],$update_time);
        }

        echo 'success';
    }

    public function _insert_ranking($video_id,$ranking_type,$ranking_value,$update_time){

        $v = M('Video')->findOne($video_id,'video_from,lang');
        if($v['lang']){
            $dt=array(
                'video_id'=>$video_id,
                'video_lang'=>$v['lang'],
                'video_from'=>$v['video_from'],
                'ranking_type'=>$ranking_type,
                'ranking_value'=>$ranking_value,
                'update_time'=>$update_time,
            );
            M('video_ranking')->add($dt);
        }
    }


}