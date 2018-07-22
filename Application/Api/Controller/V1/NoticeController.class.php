<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\SortUtils;
use Common\Lib\TPUtils;

class NoticeController extends IController{


    // 获取消息列表
    public function get_notice_list(){

        $user_id = IV('token','require|token');
        $type = IV('type'); // array('refer','review_video','like_video','like_video_review','follower')
        $page = IV('page','require');
        $limit = IV('limit','require');
        $lang  = IV('lang','require');

        $co=array(
            'receive_user_id'=>$user_id,
        );

        if($type){
            $co['type'] = $type;
        }

        $notice_list = M('notice')->where($co)->page($page)->limit($limit)->order('create_time desc')->select();

        foreach ($notice_list as &$notice) {
            
            $notice['send_user'] = D('User')->getInfo($notice['send_user_id']);
            if($notice['video_id']){
                $notice['video_info'] = D('Video')->getInfo($notice['video_id'],$lang,$ms,$user_id);
            }

            $send_user_id = $notice['send_user_id'];
            
            $notice['has_follow_me'] = D('Follow')->hasFollow($user_id,$send_user_id);

            /*
            if($send_user_id){
                $notice['send_user']['video_list'] = D('Video')->getListByUserID($send_user_id,$lang,$fields,$page,5,$user_id);
            }*/

            unset($notice['send_user_id']);
            unset($notice['video_id']);
            M('notice')->where(array('notice_id'=>$notice['notice_id']))->setField('status', 0);
        }

        $this->iSuccess($notice_list,'notice_list');
    }

    // 获取新的消息数
    public function get_new_notice_count(){

        $user_id = IV('token','require|token');
        $co=array(
            'receive_user_id'=>$user_id,
            'status'=>1,
        );
        $count = M('notice')->where($co)->count();

        $this->iSuccess($count,'new_count');
    }
}