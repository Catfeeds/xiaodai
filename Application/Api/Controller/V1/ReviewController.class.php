<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
use Common\Lib\LangUtils;
use Common\Lib\DateUtils;
use Common\Lib\CDNUtils;
use Common\Lib\String;

# 评论
class ReviewController extends IController{

    
    // 添加评论
    public function add_review(){

        $author_user_id = IV('token','require|token');
        $video_id = IV('video_id','require');
        $to_user_id = IV('to_user_id');
        $text = IV('text','require');
        $refer_user_list = IV('refer_user_list');//[{"user_id":111,"user_name"}]

        $u = M('User')->findOne($author_user_id,'name,image');

        if(!$u){
            IE(ERROR_CUSTOMER_NOT_EXIST);
        }

        $dt=array(
            'video_id'=>$video_id,
            'author_user_id'=>$author_user_id,
            'author'=>$u['name'],
            'author_header'=>$u['image'],
            'text'=>$text,
            'date_added'=>DateUtils::Now(),
            'date_modified'=>DateUtils::Now(),
            'refer_user_list'=>$refer_user_list,
        );

        if($to_user_id){
            $dt['to_user_id'] = $to_user_id;
            $dt['to_user_name'] = M('user')->findField($to_user_id,'name');
        }

        $review_id = M('review')->add($dt);

################ 发送消息 #####################################
        $receive_user_id = D('Video')->findField($video_id,'user_id');
        D('Notice')->send($receive_user_id,$author_user_id,$video_id,"review_video",$text);
        if($to_user_id){
            D('Notice')->send($to_user_id,$author_user_id,$video_id,"refer",$text);
        }
###############################################################

################# 777 自动点赞 ################################
        if(String::contains($text,"777")){
            $dt=array(
                'user_id'=>$author_user_id,
                'video_id'=>$video_id,
                'like_time'=>DateUtils::Now()
            );
            M('video_like')->add($dt);
        }
################################################################

        $review = D("Review")->getInfo($review_id,$author_user_id);

        $this->iSuccess($review,'review');
    }

    //TOOD:
    public function delete(){

    }

    // 获取视频的评论列表(热门，最新)
    public function get_review_list_of_video(){

        $user_id = IV('token','token');
        $video_id = IV('video_id','require');
        $type = IV('type',array('hot','new'));
       
        $co=array(
            'video_id'=>$video_id,

        );
        $fields =array(
            'review_id',
            'video_id',
            'author_user_id',
            'author',
            'author_header',
            'text',
            'date_added',
            'to_user_id',
            'to_user_name',
            'like_count',
            'refer_user_list'
        );

        if($type=='new'){
            $page = IV('page','require');
            $limit = IV('limit','require');
            $review_list = M('review')->field($fields)->where($co)->page($page)->limit($limit)->order('review_id desc')->select();
        }elseif($type=='hot'){

            $co['like_count']=array('gt',1);
            $review_list = M('review')->field($fields)->where($co)->page(1)->limit(6)->order('like_count desc')->select();
        }

        foreach ($review_list as &$review) {

            if($user_id){
                $review['has_liked'] = D('Review')->hasLike($user_id,$review['review_id']);
            }

            if(!$review['to_user_id']){
                unset($review['to_user_id']);
                unset($review['to_user_name']);
            }

            CDNUtils::ImageCDNObj($review,'author_header');
            FormatUtils::JsonDecoder($review,'refer_user_list');
        }

        $this->iSuccess($review_list,'review_list');
    }


    public function get_review(){

        $user_id = IV('token','token');
        $review_id = IV('review_id','require');

        $review = D("Review")->getInfo($review_id,$user_id);
        $this->iSuccess($review,'review');
    }

    // 点赞评论
    public function like_review(){

        $user_id = IV('token','token|require');
        $review_id = IV('review_id','require');

        if(!D('Review')->hasLike($user_id,$review_id)){
            
            $dt=array(
                'user_id'=>$user_id,
                'review_id'=>$review_id,
                'like_time'=>DateUtils::Now()
            );

            if(M("review_like")->add($dt)){
                
                $co=array(
                    'review_id'=>$review_id
                );
                M('review')->where($co)->setInc('like_count',1);


            ################ 发送消息 #####################################
                $video_id = M('Review')->findField($review_id,'video_id');
                $receive_user_id = D('Video')->findField($video_id,'user_id');
                D('Notice')->send($receive_user_id,$user_id,$video_id,"like_video_review",$text);
            ################################################################
            
            }
        }

        $this->iSuccess();
    }
}