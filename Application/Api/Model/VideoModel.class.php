<?php
namespace Rest3\Model;

use Common\Lib\ArrayUtils;
use Common\Lib\LangUtils;
use Common\Lib\CDNUtils;
use Common\Lib\String;
use Common\Lib\QiniuUtils;
use Common\Lib\FormatUtils;

class VideoModel extends IModel{

        var $main_fields=array(
            'video_id',
            'user_id',
            'video',
            'video_from',
            'create_time',
            'title_en',
            'title_ar',
            'image',
            'order_weight',
            'image_width',
            'image_height',
            'country',
            'city',
            'latitude',
            'longitude',
            'refer_user_list'
        );

        public function getList($vids,$lang,$ms,$guest_user_id){

            if(!$ms){
                $ms = $this->main_fields;
            }

            // =========== 优化后 ================
            $list=array();
            foreach ($vids as $vid) {
                
                $v = $this->getInfo($vid,$lang,$ms,$guest_user_id);
                $v['order_weight'] = intval($v['order_weight']);

                array_push($list,$v);
            }
            // ===================================

            /*
            $co=array(
                'video_id'=>array('in',$vids),
                'status'=>1
            );
            $list = $this->where($co)->field($ms)->select();
            foreach ($list as &$li) {

                if($li['user_id']){
                    $li['user_info'] = D('User')->getInfo($li['user_id']);
                }

                LangUtils::LangObj($li,"title",$lang);

                $li['image_large'] = $li['image'];
                CDNUtils::ImageCDNObj($li,'image_large',720,2000);

                CDNUtils::ImageCDNObj($li,'image');
                CDNUtils::VideoCDNObj($li,'video');

                $li['video_like_count'] = $this->getVideoLike($li['video_id']);
                $li['video_view_count'] = $this->getVideoViewCount($li['video_id']);

                if($guest_user_id){
                    $li['has_like_video'] = $this->hasLikeVideo($guest_user_id,$li['video_id']);
                    $li['has_follow'] = D('Follow')->hasFollow($guest_user_id,$li['user_id']);
                }

                $li['video_share_url'] = C('VIDEO_SHARE_URL').$li['video_id'];
                $li['order_weight'] = intval($li['order_weight']);
                $li['review_count'] = $this->getReviewCount($li['video_id']);

                // 适配视频链接
                //$this->optFBVideoLink($li);
            }*/

            return $list;
        }

        public function getInfo($vid,$lang,$ms,$guest_user_id){

            if(!$ms){
                $ms = $this->main_fields;
            }

            $co=array(
                'video_id'=>$vid,
            );
            
            $li = $this->where($co)->cache(3600*24)->field($ms)->find();

            if($li['user_id']){
                $li['user_info'] = D('User')->getInfo($li['user_id']);
            }

            $li['image_large'] = $li['image'];
            CDNUtils::ImageCDNObj($li,'image_large',720,2000);
            LangUtils::LangObj($li,"title",$lang);
            CDNUtils::ImageCDNObj($li,'image');
            CDNUtils::VideoCDNObj($li,'video');

            $li['video_like_count'] = $this->getVideoLike($li['video_id']);
            $li['video_view_count'] = $this->getVideoViewCount($li['video_id']);

            if($guest_user_id){
                $li['has_like_video'] = $this->hasLikeVideo($guest_user_id,$li['video_id']);
                $li['has_follow'] = D('Follow')->hasFollow($guest_user_id,$li['user_id']);
            }

            $li['video_share_url'] = C('VIDEO_SHARE_URL').$vid;
            $li['review_count'] = $this->getReviewCount($vid);
            
            FormatUtils::JsonDecoder($li,'refer_user_list');
        
            return $li;
        }

        public function getListByUserID($user_id,$lang,$fields,$page,$limit,$guest_user_id){

            if(!$fields){
                $fields = $this->main_fields;
            }

            $uInfo = D('User')->getInfo($user_id);

            $co=array(
                'user_id'=>$user_id,
                'status'=>1
            );
            
            $list = $this->where($co)->field($fields)
                            ->order('create_time desc')->page($page)
                            ->limit($limit)
                            ->select();

            foreach ($list as &$li) {

                $li['image_large'] = $li['image'];
                CDNUtils::ImageCDNObj($li,'image_large',720,2000);
                
                $li['user_info'] = $uInfo;
                LangUtils::LangObj($li,"title",$lang);
                CDNUtils::ImageCDNObj($li,'image');
                CDNUtils::VideoCDNObj($li,'video');

                $li['video_like_count'] = $this->getVideoLike($li['video_id']);
                
                if($guest_user_id){
                    $li['has_like_video'] = $this->hasLikeVideo($guest_user_id,$li['video_id']);
                    $li['has_follow'] = D('Follow')->hasFollow($guest_user_id,$li['user_id']);
                }

                $li['video_share_url'] = C('VIDEO_SHARE_URL').$li['video_id'];

                $li['review_count'] = $this->getReviewCount($li['video_id']);

                FormatUtils::JsonDecoder($li,'refer_user_list');
            }

            return $list;
        }

        # 用户的视频统计
        public function getVideoCount($user_id){

            $co=array(
                'user_id'=>$user_id,
                //'status'=>1
            );
            return $this->where($co)->count();
        }

        # 用户时间线上的动态数(转发)
        public function getTimelineCount($user_id){

            $co=array(
                'user_id'=>$user_id,
                'type'=>'repost'
            );
            return M('user_timeline')->where($co)->count();
        }

        # 点赞
        public function getVideoLike($video_id){

            $co=array(
                'video_id'=>$video_id,
            );

            $count = M('video_like')->where($co)->count();

            return $count;

            //$base_like_count = M('video')->findField($video_id,'base_like_count');
            //return ($count+$base_like_count);
        }

        # view 数
        public function getVideoViewCount($video_id){

            $co=array(
                'video_id'=>$video_id,
            );

            $count = M('video_to_view')->where($co)->count();

            return $count;

            //$base_like_count = M('video')->cache(60)->findField($video_id,'base_view_count');
            //return ($count+$base_like_count);
        }

        public function hasLikeVideo($user_id,$video_id){

            $co=array(
                'user_id'=>$user_id,
                'video_id'=>$video_id,
            );

            if(M('video_like')->where($co)->count()==0){
                return false;
            }else{
                return true;
            }
        }

        // 操作视频的图片列表
        public function optImageOfVideoList(&$video_list){

            foreach ($video_list as &$video) {
                
                if(!$video['image_width']||!$video['image_height']){

                    // 如果没有图片，则进行处理
                    
                    $image = $video['image'];

                    if(!String::contains($image,C('IMAGE_CDN_URL'))){
                        $video['image_width'] = '200';
                        $video['image_height'] = '100';
                    }else{

                        $image_info = QiniuUtils::imageInfo($image);
        
                        if($image_info){

                            $video['image_width'] = $image_info['width'];
                            $video['image_height'] = $image_info['height'];

                            $dt=array(
                                'image_width'=>$image_info['width'],
                                'image_height'=>$image_info['height'],
                                'video_id'=>$video['video_id']
                            );
                            M('video')->save($dt);

                        }else{
                            $video['image_width'] = '200';
                            $video['image_height'] = '100';
                        }
                    }
                }
            }
        }

        // 操作视频的图片列表
        public function optImageOfVideo(&$video){   
                
            if(!$video['image_width']){

                // 如果没有图片，则进行处理
                
                $image = $video['image'];

                if(!String::contains($image,C('IMAGE_CDN_URL'))){
                    $video['image_width'] = '200';
                    $video['image_height'] = '100';
                }else{

                    $image_info = QiniuUtils::imageInfo($image);
    
                    if($image_info){

                        $video['image_width'] = $image_info['width'];
                        $video['image_height'] = $image_info['height'];

                        $dt=array(
                            'image_width'=>$image_info['width'],
                            'image_height'=>$image_info['height'],
                            'video_id'=>$video['video_id']
                        );
                        M('video')->save($dt);

                    }else{
                        $video['image_width'] = '200';
                        $video['image_height'] = '100';
                    }
                }
            }
        }

        // 适配FB的视频链接
        public function optFBVideoLink(&$video){

            $fb_video_id = $video['fb_video_id'];
            if($fb_video_id){

                $key = "fb_video_source#".$fb_video_id;
                $source = S($key);

                if($source){

                    $video['video'] = $source;
                    return true;

                }else{

                    $source = Service('FBCrawler')->getFBVideo($fb_video_id);
                    if($source){
                        $video['video'] = $source;
                        S($key,$source,3600*24);
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }

        public function getConditionList($vids,$lang,$ms,$condition,$page,$limit,$orderby,$guest_user_id){

            if(!$ms){
                $ms = $this->main_fields;
            }
            
            $list = $this->where($condition)->field($ms)->page($page)->limit($limit)->order($orderby)->select();

            foreach ($list as &$li) {

                if($li['user_id']){
                    $li['user_info'] = D('User')->getInfo($li['user_id']);
                }

                LangUtils::LangObj($li,"title",$lang);

                $li['image_large'] = $li['image'];
                CDNUtils::ImageCDNObj($li,'image_large',720,2000);

                CDNUtils::ImageCDNObj($li,'image');
                CDNUtils::VideoCDNObj($li,'video');

                $li['video_like_count'] = $this->getVideoLike($li['video_id']);
                $li['video_view_count'] = $this->getVideoViewCount($li['video_id']);

                if($guest_user_id){
                    $li['has_like_video'] = $this->hasLikeVideo($guest_user_id,$li['video_id']);
                    $li['has_follow'] = D('Follow')->hasFollow($guest_user_id,$li['user_id']);
                }

                $li['video_share_url'] = C('VIDEO_SHARE_URL').$li['video_id'];
                $li['order_weight'] = intval($li['order_weight']);
                $li['review_count'] = $this->getReviewCount($li['video_id']);

                FormatUtils::JsonDecoder($li,'refer_user_list');    
            }

            return $list;
        }

        // 获取评论统计总数
        public function getReviewCount($video_id){

            $co=array(
                'video_id'=>$video_id
            );
            return M("Review")->where($co)->count();
        }
}