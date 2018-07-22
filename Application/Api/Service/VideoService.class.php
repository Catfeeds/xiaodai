<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\MapUtils;
use Common\Lib\String;


class VideoService {
    

   // 获取日期的视频列表
   public function get_video_list_orderby_date($tag_id,$lang,$page,$limit,$user_id){

        if($tag_id){

            $co=array(
                'tag_id'=>$tag_id
            );
            $list =  M('video_to_tag')->where($co)->field('video_id')->order('rand()')->page($page)->limit($limit)->select();
            $vids = ArrayUtils::Collect($list,'video_id');

            if(!empty($vids)){

                $list = D('Video')->getList($vids,$lang,null,$user_id);
                //SortUtils::InsertionSort($list,'order_weight','desc');

                return $list;

            }else{
                return array();
            }

        }else{

            $co=array(
                'status'=>1,
            );

            // 时间排序
            $list = M('Video')->where($co)->field('video_id')
                              ->order('rand()')
                              ->page($page)->limit($limit)->select();

            $vids = ArrayUtils::Collect($list,'video_id');
            $list = D('Video')->getList($vids,$lang,null,$user_id);

            SortUtils::InsertionSort($list,'order_weight','desc');

            return $list;
        }
   }

   // 获取日期的视频列表
   public function get_topic_video_list_orderby_date($topic_id,$lang,$page,$limit,$user_id,$content_lang){

        $co=array(
            'topic_id'=>$topic_id
        );

        if($content_lang){
            $co['lang'] = array('in',array($content_lang,'all'));
        }

        $list =  M('video_to_topic')->where($co)->field('video_id')->order('create_time desc')->page($page)->limit($limit)->select();
        $vids = ArrayUtils::Collect($list,'video_id');

        if(!empty($vids)){

            $list = D('Video')->getList($vids,$lang,null,$user_id);
            SortUtils::InsertionSort($list,'create_time','desc');

            return $list;
        }else{
            return array();
        }
   }


   // 获取视频列表，通过权重
   public function get_topic_video_ranking($topic_id,$lang,$page,$limit,$user_id,$type){

        $co=array(
            'topic_id'=>$topic_id,
            'type'=>$type
        );

        $list =  M('topic_video_ranking')->where($co)->field('video_id')->order('order_weight desc')->page($page)->limit($limit)->select();
        $vids = ArrayUtils::Collect($list,'video_id');

        if(!empty($vids)){
            $list = D('Video')->getList($vids,$lang,null,$user_id);
            SortUtils::InsertionSort($list,'order_weight','desc');
            return $list;
        }
   }
}