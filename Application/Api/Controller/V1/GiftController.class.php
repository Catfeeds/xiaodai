<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\SortUtils;
use Common\Lib\String;
use Common\Lib\QiniuUtils;
use Common\Lib\LangUtils;
use Common\Lib\CDNUtils;
use Common\Lib\FloatUtils;
use Common\Lib\RateUtils;

/**
 * 礼物
 */
class GiftController extends IController{


    // 获取礼物列表
    public function get_gift_list(){

        $lang = IV('lang','require');

        $gift_list = M('Gift')->order('order_no desc')->select();
        foreach ($gift_list as &$gift) {
             LangUtils::LangObj($gift,"gift_name",$lang);
        }
        $this->iSuccess($gift_list,'gift_list');
    }

    // 赠送礼物
    public function give_gift(){

        $lang = IV('lang','require');
        $gift_key = IV('gift_key','require');
        $video_id = IV('video_id','require');
        $user_id = IV('token','require|token');
        $gift_count = IV('gift_count','require');

        // 获取礼物的信息
        $gift = M('gift')->find($gift_key);
        $cost_gift_coin_total = FloatUtils::mul($gift_count, $gift['gift_coin_total']); 

        // 兑换成钻石
        $addDiamond_total = RateUtils::getDiamondFromCoin($cost_gift_coin_total);

        // 判断是否有足够的金币Wallet
        D('Wallet')->enoughCoin($user_id,$cost_gift_coin_total);

        // 获取视频的用户ID
        $vinfo = M("video")->findOne($video_id,'user_id,image,title_en,title_ar');
        D('Wallet')->init($vinfo['user_id']);

        // 减少赠送者金币，增加被赠送者钻石
        D('Wallet')->decTotal($user_id,$cost_gift_coin_total,'coin_total');
        D('Wallet')->incTotal($vinfo['user_id'],$addDiamond_total,'diamond_total');
        
        // 增加钱包收入和消费记录
        $income_dt=array(
            'video_user_id'=>$vinfo['user_id'],
            'income_status'=>'success',
            'income_type'=>'gift',
            'income_time'=>DateUtils::Now(),
            'income_gift_key'=>$gift_key,
            'income_gift_name_en'=>$gift['gift_name_en'],
            'income_gift_name_ar'=>$gift['gift_name_ar'],
            'income_gift_count'=>$gift_count,
            'income_total_coins'=>$cost_gift_coin_total,
            'income_total_diamond'=>$addDiamond_total,
            'income_video_image'=>$vinfo['image'],
            'income_video_title_ar'=>$vinfo['title_ar'],
            'income_video_title_en'=>$vinfo['title_en'],
            'gift_user_id'=>$user_id,
            'video_id'=>$video_id,
        );
        M('wallet_income_record')->add($income_dt);


        $consume_dt=array(
            'user_id'=>$user_id,
            'video_id'=>$video_id,
            'video_user_id'=>$vinfo['user_id'],
            'video_image'=>$vinfo['image'],
            'video_title_ar'=>$vinfo['title_ar'],
            'video_title_en'=>$vinfo['title_en'],
            'consume_time'=>DateUtils::Now(),
            'consume_total_coins'=>$cost_gift_coin_total,
            'consume_gift_key'=>$gift_key,
            'consume_gift_name_en'=>$gift['gift_name_en'],
            'consume_gift_name_ar'=>$gift['gift_name_ar'],
            'consume_gift_count'=>$gift_count
        );
        M('wallet_consume_record')->add($consume_dt);


        ################# 送礼物自动加评论 ################################
        $u = M('User')->findOne($user_id,'name,image,push_token');
        $dt=array(
            'video_id'=>$video_id,
            'author_user_id'=>$user_id,
            'author'=>$u['name'],
            'author_header'=>$u['image'],
            'text'=>C('Copywriting_Review_GiveGift'),
            'date_added'=>DateUtils::Now(),
            'date_modified'=>DateUtils::Now(),
        );
        $review_id = M('review')->add($dt);
        ###################################################################
        

        try{
            ################ 发送消息 #########################################
            $receive_user_id = D('Video')->findField($video_id,'user_id');
            D('Notice')->send($vinfo['user_id'],$user_id,$video_id,"give_gift","");
            ###################################################################
        } catch( \Exception $e ) {}

        Service('VideoWeight')->addGiftWeight($video_id);
        
        $this->iSuccess();
    }
}