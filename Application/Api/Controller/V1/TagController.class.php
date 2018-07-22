<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
use Common\Lib\LangUtils;
use Common\Lib\CDNUtils;
use Common\Lib\ArrayUtils;

# 分类
class TagController extends IController{

    
    # 获取首页目录列表
    public function get_top_tag_list(){
        
        $lang = IV('lang','require');

        $TagModel=D('Tag');
        $fs=array('tag_id','name_en','name_ar','view_type');
        $co=array(
            'is_recommend'=>1,
            'vesion'=>'v2'
        );
        $tag_list=$TagModel->where($co)->cache(60*10)->field($fs)->order('order_no desc')->select();
        LangUtils::LangList($tag_list,"name",$lang);
        
        $this->iSuccess($tag_list,'tag_list');
    }

    # tag下面的视频列表
    public function get_video_list(){

        $guest_user_id = IV('token','token');
        $limit = IV('limit','require');
        $page = IV('page','require');
        $type = IV('type',array('new','hot'));
        $tag_id = IV('tag_id','require');

        $orderby;
        if($type=='new'){
            $orderby="create_time desc";
        }else{
            $orderby="order_weight desc";
        }

        $co=array(
            'tag_id'=>$tag_id
        );
        $list =  M('video_to_tag')->where($co)->field('video_id')->cache(60*1)->order($orderby)->page($page)->limit($limit)->select();
        $vids = ArrayUtils::Collect($list,'video_id');

        if(!empty($vids)){
            $list = D('Video')->getList($vids,$lang,null,$user_id);
            $this->iSuccess($list,'video_list');
        }else{
            $this->iSuccess(array(),'video_list');
        }
    }

    # 获取tag用户的排行
    public function get_tag_user_ranking(){

        $tag_id = IV('tag_id','require');
        $page = IV('page','require');
        $limit = IV('limit','require');

        $co=array(
            'tag_id'=>$tag_id,
        );
        $ulist = M('video_tag_user_ranking')->field('user_name,user_image,user_id,like_count')
                                            ->cache(60*10)
                                            ->where($co)
                                            ->page($page)->limit($limit)
                                            ->order('like_count desc')
                                            ->select();

        CDNUtils::ImageCDNArray($ulist,'user_image');

        $this->iSuccess($ulist,'user_ranking');
    }
}