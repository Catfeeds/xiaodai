<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\MapUtils;
use Common\Lib\String;
use Common\Lib\DateUtils;


class VideoWeightService {

    /**
     *  @zhao
     */
    /*
     新用户定义:注册时间在24小时之内的为新用户
    ○ 每个赞获得的权重为1，每个礼物获得的权重为3，每个 UV 播放一次视频权重为0.2；
    ○ 每个视频上传后的1小时内，赞和 UV 的权重为3倍计算，每个礼物获得的权重为4倍计算；
    ○ 新用户的第一条视频上传后的1小时内，赞和 UV 的权重为4倍计算，每个礼物获得的权重为5倍计算；
    ○ 新用户的第二条视频和第三条视频上传后的1小时内，赞和 UV 的权重为3.5倍计算，每个礼物获得的权重为4.5倍计算；
    ○ 每个视频上传3小时之后，赞的权重为0.7倍，每个礼物的权重为0.6倍，UV 播放权重不降权；
    ○ 新用户的前三条视频上传3小时之后，赞、礼物、UV 的播放量不降权
    ○ 每个用户为一条视频赠送五个礼物之后，该用户送的礼物将不记录权重。自己送礼物不记录权重；

    以上为 权重计算逻辑


    ○ 每条视频在 explore 推荐列表中的前50条不能超过30分钟；
    ○ 每条视频在 explore 推荐列表中的前100条不能超过60分钟；
    ○ 每条视频上传超过24小时，就不再获得 explore 推荐；
    ○ 每个用户同时出现在 explore 中的视频不能超过三条，且仅有一条获得前20的推荐位（后台推荐的不受此影响）。
    ○ 后台 admin 可以根据前50推荐位进行推荐，推荐时长为5分钟、10分钟、30分钟、1小时、2小时。推荐结束后，将遵循 explore 逻辑。
    ○  explore 推荐列表5分钟刷新一次。
     */

    public $new_user_threshold = 24*60*60;/*新用户注册时间阈值 24*60*60 秒*/
    public $new_user_down_weight_threshold = 3;  /*新用户前3条视频 不降权阈值*/
    public $after_how_many_gift_to_one_video__threshold = 5; /*每个用户为一条视频赠送多少个礼物之后，该用户送的礼物将不记录权重阈值*/
    public $self_gift_self = false; /*自己给自己送礼，不记录权重*/
    public $base_like_weight = 10; /*赞的权重基数*/
    public $base_gift_weight = 30; /*礼物的权重基数*/
    public $base_view_weight = 2; /*UV的权重基数*/
    public $in_one_hour_like_ratio = 30; /*一小时内 赞的系数是 4倍*/
    public $in_one_hour_view_ratio = 30; /*一小时内 UV的系数是 4倍*/
    public $in_one_hour_gift_ratio = 40; /*一小时内 礼物的系数是 4倍*/

    public $new_user_first_video_in_one_hour_like_ratio = 40; /*新用户第一条视频一小时内 赞的系数是 4倍*/
    public $new_user_first_video_in_one_hour_view_ratio = 40; /*新用户第一条视频一小时内 UV的系数是 4倍*/
    public $new_user_first_video_in_one_hour_gift_ratio = 50; /*新用户第一条视频一小时内 礼物的系数是 5倍*/

    public $new_user_secthr_video_in_one_hour_like_ratio = 35; /*新用户第二三条视频一小时内 赞的系数是 3.5倍*/
    public $new_user_secthr_video_in_one_hour_view_ratio = 35; /*新用户第二三条视频一小时内 UV的系数是 3.5倍*/
    public $new_user_secthr_video_in_one_hour_gift_ratio = 45; /*新用户第二三条视频一小时内 礼物的系数是 4.5倍*/

    public $after_three_hours_like_ratio = 7; /*三小时之后 赞的系数是 0.7倍*/
    public $after_three_hours_view_ratio = 7; /*三小时之后 UV的系数是 0.7倍*/
    public $after_three_hours_gift_ratio = 6; /*三小时之后 礼物的系数是 0.6倍*/

    public $one_hour_secs = 1*60*60;  /*一个小时*/
    public $three_hour_secs = 3*60*60;  /*三个小时*/


    public function addLikeWeight($video_id){
        $video = $this->_getVideo($video_id);
        if(!$video){
            return;
        }else{
            $weight = $this->_calLikeWeight($video);
            $this->_addWeight($video_id,'like',$weight);
        }

    }
    /*
     * 每个礼物获得的权重为30
     */
    public function addGiftWeight($video_id,$gift_user_id){
        $video = $this->_getVideo($video_id);
        if(!$video){
            return;
        }else{
            $u_cond['user_id']=$gift_user_id;
            $user_c = M('user')->where($u_cond)->count();
            if(!$user_c){
                return;
            }else{
                $weight = $this->_calGiftWeight($video,$gift_user_id);
                $this->_addWeight($video_id,'like',$weight);
            }
        }

    }
    /*
     * 每个 UV 播放一次视频权重为2；
     */
    public function addViewWeight($video_id){
        $video = $this->_getVideo($video_id);
        if(!$video){
            return;
        }else{
            $weight = $this->_calViewWeight($video);
            $this->_addWeight($video_id,'like',$weight);
        }

    }

    /*
     * 计算点赞的权重
     */
    private function _calLikeWeight($video){
        $video_id = $video['video_id'];
        $create_time = $video['create_time'];
        $user_id = $video['user_id'];
        $is_new_user = $this->_isNewUser($user_id);
        $is_in_one_hour = $this->_isInOneHour($create_time);
        $is_after_three_hours = $this->_isAfterThreeHours($create_time);
        $is_first_video = $this->_isUserFirstVideo($user_id,$video_id);
        $is_secthr_video = $this->_isUserSecThrVideo($user_id,$video_id);
        if($is_new_user){  //新用户
            if($is_in_one_hour){ //视频在一小时之内
                if($is_first_video){ //新用户第一个视频
                    $ratio = $this->new_user_first_video_in_one_hour_like_ratio;
                }elseif($is_secthr_video){  //新用户用户第二三条视频
                    $ratio =$this->new_user_secthr_video_in_one_hour_like_ratio;
                }else{ //新用户一小时内的其他视频
                    $ratio =$this->base_like_weight*$this->in_one_hour_like_ratio;
                }
            }elseif($is_after_three_hours){ /*三小时之后不降权，维持基本的权重*/
                $ratio = 1;
            }else{ /*1-3小时*/
                $ratio = 1;
            }
        }else{
            if($is_in_one_hour){ //视频在一小时之内
                $ratio = $this->in_one_hour_like_ratio;
            }elseif($is_after_three_hours){ /*三小时之后*/
                $ratio = $this->after_three_hours_like_ratio;
            }else{ /*1-3小时*/
                $ratio = 1;
            }
        }
        return $this->base_like_weight*$ratio;
    }

    /*
     * 计算UV的权重
     */
    private function _calViewWeight($video){
        $video_id = $video['video_id'];
        $create_time = $video['create_time'];
        $user_id = $video['user_id'];
        $is_new_user = $this->_isNewUser($user_id);
        $is_in_one_hour = $this->_isInOneHour($create_time);
        $is_after_three_hours = $this->_isAfterThreeHours($create_time);
        $is_first_video = $this->_isUserFirstVideo($user_id,$video_id);
        $is_secthr_video = $this->_isUserSecThrVideo($user_id,$video_id);
        if($is_new_user){  //新用户
            if($is_in_one_hour){ //视频在一小时之内
                if($is_first_video){ //新用户第一个视频
                    $ratio = $this->new_user_first_video_in_one_hour_view_ratio;
                }elseif($is_secthr_video){  //新用户用户第二三条视频
                    $ratio =$this->new_user_secthr_video_in_one_hour_view_ratio;
                }else{ //新用户一小时内的其他视频
                    $ratio =$this->base_like_weight*$this->in_one_hour_view_ratio;
                }
            }elseif($is_after_three_hours){ /*三小时之后不降权，维持基本的权重*/
                $ratio = 1;
            }else{ /*1-3小时*/
                $ratio = 1;
            }
        }else{
            if($is_in_one_hour){ //视频在一小时之内
                $ratio = $this->in_one_hour_view_ratio;
            }elseif($is_after_three_hours){ /*三小时之后*/
                $ratio = $this->after_three_hours_view_ratio;
            }else{ /*1-3小时*/
                $ratio = 1;
            }
        }
        return $this->base_view_weight*$ratio;
    }


    /*
     * 计算打赏的权重
     */
    private function _calGiftWeight($video,$gift_user_id){
        $video_id = $video['video_id'];
        $create_time = $video['create_time'];
        $user_id = $video['user_id'];
        $is_new_user = $this->_isNewUser($user_id);
        $is_in_one_hour = $this->_isInOneHour($create_time);
        $is_after_three_hours = $this->_isAfterThreeHours($create_time);
        $is_first_video = $this->_isUserFirstVideo($user_id,$video_id);
        $is_secthr_video = $this->_isUserSecThrVideo($user_id,$video_id);
        $is_ignore = $this->_isIgnore($video,$gift_user_id);
        if($is_ignore){
            $ratio = 0;
        }else{
            if($is_new_user){  //新用户
                if($is_in_one_hour){ //视频在一小时之内
                    if($is_first_video){ //新用户第一个视频
                        $ratio = $this->new_user_first_video_in_one_hour_gift_ratio;
                    }elseif($is_secthr_video){  //新用户用户第二三条视频
                        $ratio =$this->new_user_secthr_video_in_one_hour_gift_ratio;
                    }else{ //新用户一小时内的其他视频
                        $ratio =$this->base_like_weight*$this->in_one_hour_gift_ratio;
                    }
                }elseif($is_after_three_hours){ /*三小时之后不降权，维持基本的权重*/
                    $ratio = 1;
                }else{ /*1-3小时*/
                    $ratio = 1;
                }
            }else{
                if($is_in_one_hour){ //视频在一小时之内
                    $ratio = $this->in_one_hour_gift_ratio;
                }elseif($is_after_three_hours){ /*三小时之后*/
                    $ratio = $this->after_three_hours_gift_ratio;
                }else{ /*1-3小时*/
                    $ratio = 1;
                }
            }
        }
        return $this->base_gift_weight*$ratio;
    }

    /*
     * 是否忽略打赏权重，每个用户为一条视频赠送五个礼物之后，该用户送的礼物将不记录权重。自己送礼物不记录权重；
     */
    private function _isIgnore($video,$gift_user_id){
        $user_id = $video['user_id'];
        if($user_id==$gift_user_id)//自己给自己打赏
        {
            return true;
        }else{
            $g_cond['video_id'] =$video['video_id'];
            $g_cond['gift_user_id'] = $gift_user_id;
            $gift_count = M('wallet_income_record')->where($g_cond)->count('wallet_income_record_id');
            if($gift_count > $this->after_how_many_gift_to_one_video__threshold){ //给同一个视频打赏超过限制
                return true;
            }else{
                return false;
            }
        }
    }

    /*
     * //视频是否用户第一条视频
     */
    private function _isUserFirstVideo($user_id,$video_id){
        $v_cond['user_id'] = $user_id;
        $first_video_id  = M('video')->where($v_cond)->order('create_time desc')->getField('video_id');
        if($first_video_id){
            if($first_video_id==$video_id){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*
     * //视频是否用户第二三条视频
     */
    private function _isUserSecThrVideo($user_id,$video_id){
        $v_cond['user_id'] = $user_id;
        $video_ids  = M('video')->where($v_cond)->order('create_time desc')->limit(1,3)->getField('video_id',true);
        if($video_ids){
            if(in_array($video_id,$video_ids)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    private function _isInOneHour($create_time){
        $added_sec = time()-strtotime($create_time);
        if($added_sec-$this->one_hour_secs>0){
            return false;
        }else{
            return true;
        }
    }

    private function _isAfterThreeHours($create_time){
        $added_sec = time()-strtotime($create_time);
        if($added_sec-$this->three_hours_secs>0){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 判断新用户
     */
    private function _isNewUser($user_id){
        $u_cond['user_id'] = $user_id;
        $date_added = M('user')->where($u_cond)->getField('date_added');
        if($date_added&&$date_added!='null'){
            $date_added_secs = strtotime($date_added);
            if($date_added_secs-$this->new_user_threshold<0)
            {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    private  function _getVideo($video_id){
        $v_cond['video_id'] = $video_id;
        $video = M('video')->where($v_cond)->field('video_id,order_weight,create_time,user_id')->find();
        return $video;
    }

    private function _addWeight($video_id,$type,$add_weight){

        $dt=array(
            'video_id'=>$video_id,
            'type'=>$type,
            'create_time'=>DateUtils::Now(),
            'add_weight'=>$add_weight
        );
        M('video_weight_record')->add($dt);
        M('video')->where(array('video_id'=>$video_id))->setInc('weight',$add_weight);
    }
}