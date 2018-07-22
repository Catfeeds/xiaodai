<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\MapUtils;
use Common\Lib\String;
use Common\Lib\DateUtils;
use Common\Lib\FormatUtils;


class VideoMarkService {


   // 标记浏览过的视频ids
   public function mark_video_ids($device_id,$tag_id,$new_mark_video_ids){


        $co=array(
            'device_id'=>$device_id,
            'tag_id'=>$tag_id,
        );
        $dev = M('video_mark')->where($co)->find();
        $mark_video_ids = json_decode($dev['mark_videos_json'],true);

        if($dev){

            // 保存
            $dt=array(
                'mark_videos_json'=> $this->_merge_video_ids($mark_video_ids,$new_mark_video_ids),
                'update_time'=>DateUtils::Now(),
            );
            M('video_mark')->where($co)->save($dt);

        }else{

            // 提交
            $dt=array(
                'mark_videos_json'=>$this->_merge_video_ids($mark_video_ids,$new_mark_video_ids),
                'create_time'=>DateUtils::Now(),
                'update_time'=>DateUtils::Now(),
                'tag_id'=>$tag_id,
                'device_id'=>$device_id
            );

            M('video_mark')->add($dt);
        }
   }

   public function get_mark_video_ids($device_id,$tag_id){

        $co=array(
            'device_id'=>$device_id,
            'tag_id'=>$tag_id
        );
        $json = M('video_mark')->where($co)->getField('mark_videos_json');

        if(!$json){
            return array();
        }else{
            return json_decode($json,true);
        }
   }


   public function _merge_video_ids($mark_video_ids,$new_mark_video_ids){

        if(!$mark_video_ids){
            $mark_video_ids=array();
        }

        // 保留最新加入的
        $mark_video_ids = array_merge($mark_video_ids,$new_mark_video_ids);
        $mark_video_ids = array_unique($mark_video_ids);
        $mark_video_ids = array_reverse($mark_video_ids);
        $mark_video_ids = array_slice($mark_video_ids,0,10000);
        $mark_video_ids = array_reverse($mark_video_ids);

        if($mark_video_ids){
            return json_encode($mark_video_ids);
        }else{
            return json_encode(array());
        }
   }


   # 记录喜欢的视频标签一次     
   public function mark_like_video_tag_one_time($device_id,$user_id,$tag_id){

        $co=array(
            'device_id'=>$device_id,
            'tag_id'=>$tag_id
        );

        $c = M('user_like_video_tag')->where($co)->count();

        if($c==0){

            $data = $co;
            if($user_id){
                 $data['user_id'] = $user_id;
            }
            $data['create_time'] = DateUtils::Now();
            $data['update_time'] = DateUtils::Now();

            M('user_like_video_tag')->add($data);
        }else{
            M('user_like_video_tag')->where($co)->setInc('like_depth',1);
            M('user_like_video_tag')->where($co)->setField('update_time',DateUtils::Now());
        }
   }

   public function mark_like_video_tags($device_id,$user_id,$video_id){

        $co=array(
            'video_id'=>$video_id
        );
        $tag_list = M('video_to_tag')->where($co)->field('tag_id')->select();

        foreach ($tag_list as $tag) {
            $this->mark_like_video_tag_one_time($device_id,$user_id,$tag['tag_id']);
        }
   }

   // 获取推荐的视频 video_ids
   public function get_recommod_video_ids($device_id,$user_id,$exclude_video_ids){

        if($user_id){
            $co['user_id'] = $user_id;
        }else{
            $co['device_id'] = $device_id;
        }

        $taglist = M('user_like_video_tag')->where($co)->order('like_depth desc')->limit(3)->field('tag_id')->select();
        $depth_tag_ids = ArrayUtils::Collect($taglist,'tag_id');

        $taglist = M('user_like_video_tag')->where($co)->order('update_time desc')->limit(3)->field('tag_id')->select();
        $new_tag_ids = ArrayUtils::Collect($taglist,'tag_id');
        $new_tag_ids = array_diff($new_tag_ids,$depth_tag_ids);

        $resVideos=array();

        // 最新点击的取9个
        foreach ($depth_tag_ids as $tag_id) {
            $videos = $this->_getVideosByTagID($tag_id,3,$exclude_video_ids);
            $resVideos = array_merge($resVideos,$videos);
        }

        // 最新点击的取9个
        foreach ($new_tag_ids as $tag_id) {
            $videos = $this->_getVideosByTagID($tag_id,3,$exclude_video_ids);
            $resVideos = array_merge($resVideos,$videos);
        }

        // 其他没有全的，则用热门视频进行补充
        $hotLimit = 30-count($resVideos);
        $co=array();
        if(!empty($exclude_video_ids)){
            $co=array(
                'video_id'=>array('not in',$exclude_video_ids)
            );
        }
        $list = M('hot_video')->where($co)->field('video_id')->limit($hotLimit)->order('order_weight desc')->select();
        $vids = ArrayUtils::Collect($list,'video_id');

        return array_merge($resVideos,$vids);
   }


   private function getDepthVideos($depth_tag_ids,$exclude_video_ids){

        if(empty($depth_tag_ids)){
            return array();
        }else{
            $c = 3;
            $res=array();
            foreach ($depth_tag_ids as $tag_id) {
                $videos = $this->_getVideosByTagID($tag_id,$c,$exclude_video_ids);
                array_push($res,$videos);
            }
        }
   }

   private function _getVideosByTagID($tag_id,$limit,&$exclude_video_ids){

        $co=array(
            'tag_id'=>$tag_id
        );
        $co['video_id'] = array('not in',$exclude_video_ids);

        $list = M('video_to_tag_ranking')->where($co)->limit($limit)->order('weight desc')->select();
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

        $exclude_video_ids = array_unique(array_merge($exclude_video_ids,$vids));
        return $list;
   }
}