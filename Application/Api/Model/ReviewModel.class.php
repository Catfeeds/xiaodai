<?php
namespace Rest3\Model;

use Common\Lib\ArrayUtils;
use Common\Lib\CDNUtils;
use Common\Lib\FormatUtils;

#评论
class ReviewModel extends IModel{

        
    public function hasLike($user_id,$review_id){

        $co=array(
            'user_id'=>$user_id,
            'review_id'=>$review_id
        );

        if(M('review_like')->where($co)->count()){
            return true;
        }else{
            return false;
        }
    }


    public function getInfo($review_id,$user_id){

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

        $review = M('review')->field($fields)->find($review_id);
        CDNUtils::ImageCDNObj($review,'author_header');

        if($user_id){
            $review['has_liked'] = D('Review')->hasLike($user_id,$review_id);
        }

        if(!$review['to_user_id']){
            unset($review['to_user_id']);
            unset($review['to_user_name']);
        }

        FormatUtils::JsonDecoder($review,'refer_user_list');

        return $review;
    }

}