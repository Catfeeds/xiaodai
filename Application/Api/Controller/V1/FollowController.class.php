<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\PasswordUtil;
use Common\Lib\VercodeUitls;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\FormatUtils;


/**
 * User信息d
 */
class FollowController extends IController{

    
    public function follow(){

        $user_id = IV('token','require|token');
        $star_user_id = IV('star_user_id','require');

        if(!D('Follow')->hasFollow($user_id,$star_user_id)){

            $dt=array(
                'star_user_id'=>$star_user_id,
                'follower_user_id'=>$user_id,
                'follow_time'=>DateUtils::Now()
            );

            M('follow')->add($dt);


    ################ 发送消息 #####################################
            D('Notice')->send($star_user_id,$user_id,$video_id=0,"follow",'');
    ###############################################################

        }
        $this->iSuccess();
    }

    # 取消 follow
    public function unfollow(){

        $user_id = IV('token','require|token');
        $star_user_id = IV('star_user_id');

        $co=array(
            'star_user_id'=>$star_user_id,
            'follower_user_id'=>$user_id,
        );

        M('follow')->where($co)->delete();

        $this->iSuccess();
    }

    # 获取粉丝列表
    public function get_follower_list(){

        $page = IV('page','require');
        $limit = IV('limit','require');

        $star_user_id = IV('token','require|token');

        $co=array(
            'star_user_id'=>$star_user_id
        );

        $list = M('follow')->where($co)->field('follower_user_id')->order('follow_time desc')->select();

        if(empty($list)){
            $this->iSuccess(array(),'follower_list');
        }

        $follower_user_ids = ArrayUtils::Collect($list,'follower_user_id');
        $follower_user_ids = ArrayUtils::Paging($follower_user_ids,$page,$limit);

        $ulist = D('User')->getList($follower_user_ids);

        foreach ($ulist as &$li) {
            $li['is_my_star'] = D('Follow')->hasFollow($star_user_id,$li['user_id']) ; # 是否是我关注的人
        }


        $this->iSuccess($ulist,'follower_list');
    }

    # 获取我关注的
    public function get_star_list(){

        $follower_user_id = IV('token','require|token');
        $page = IV('page','require');
        $limit = IV('limit','require');

        $co=array(
            'follower_user_id'=>$follower_user_id
        );
        $list = M('follow')->where($co)->field('star_user_id')->order('follow_time desc')->select();

        if(empty($list)){
            $this->iSuccess(array(),'star_list');
        }

        $star_user_ids = ArrayUtils::Collect($list,'star_user_id');
        $star_user_ids = ArrayUtils::Paging($star_user_ids,$page,$limit);

        $ulist = D('User')->getList($star_user_ids);

        foreach ($ulist as &$li) {
            $li['is_my_follower'] = D('Follow')->hasFollow($li['user_id'],$follower_user_id) ; # 是否是关注我的
        }

        $this->iSuccess($ulist,'star_list');
    }

    public function get_friend_list(){

        $user_id = IV('token','require|token');

        // 获取我关注的
        $co=array(
            'follower_user_id'=>$user_id
        );
        $list = M('follow')->where($co)->field('star_user_id')->order('follow_time desc')->select();
        $star_user_ids = ArrayUtils::Collect($list,'star_user_id');

        // 获取我关注的人中同时关注我的
        if(!$star_user_ids){
            $this->iSuccess(array(),'friend_list');
        }

        $co=array(
            'follower_user_id'=>array('in',$star_user_ids),
            'star_user_id'=>$user_id
        );
        $list = M('follow')->where($co)->field('follower_user_id')->order('follow_time desc')->select();
        $friend_user_ids = ArrayUtils::Collect($list,'follower_user_id');
        $friend_list = D('User')->getList($friend_user_ids);

        $this->iSuccess($friend_list,'friend_list');
    }

    // 获取推荐的明显
    public function get_recommend_stars(){

        $user_id = IV('token','token');
        $page = IV('page','require');
        $limit = IV('limit','require');

        #######################
        $co=array(
            'follower_user_id'=>$user_id
        );
        $list = M('follow')->where($co)->field('star_user_id')->select();
        $star_user_ids = ArrayUtils::Collect($list,'star_user_id');
        #######################

        if(!empty($star_user_ids)){
            $co=array(
                'user_id'=>array('not in',$star_user_ids),
            );
        }else{
            $co=array();
        }

        $video_list = M('video')->where($co)->group("user_id")->field('user_id')->page($page)->limit(100)->order('video_id desc')->select();
        $uids = ArrayUtils::Collect($video_list,'user_id');
        $recommend_stars = D('User')->getList($uids);

        $this->iSuccess($recommend_stars,'recommend_stars');
    }
}