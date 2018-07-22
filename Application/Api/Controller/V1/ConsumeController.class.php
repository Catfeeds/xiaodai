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

/**
 * 消费
 */
class ConsumeController extends IController{

    public function get_consume_records(){

       $user_id = IV('token','require|token');
       $lang = IV('lang','require');
       $page = I('page',1);
       $limit = I('limit',30);

       $co=array(
       	  'user_id'=>$user_id
       );
       $consume_records = M('wallet_consume_record')->where($co)->page($page)->limit($limit)->order('consume_time desc')->select();

       LangUtils::LangList($consume_records,'video_title',$lang);
       LangUtils::LangList($consume_records,'consume_gift_name',$lang);
       CDNUtils::VideoCDNArray($consume_records,'file');
       CDNUtils::ImageCDNArray($consume_records,'video_image');

       $co=array('user_id'=>$user_id);
       $consume_total = M('wallet_consume_record')->where($co)->sum('consume_total_coins');

       if(!$consume_total){
         $consume_total=0;
       }

       $res=array(
          'consume_total'=>round($consume_total,2),
          'consume_records'=>$consume_records
       );

       $this->iSuccess($res);
    }
}