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


/**
 * warn
 */
class WarnController extends IController{


    // 警告视频
    public function report(){

        $type = IV('type','require');
        $video_id = IV('video_id');
        $user_id = IV('token','token');
        $review_id = IV('review_id');
        $warn_user_id = IV('warn_user_id');

        $dt=array(
        	'type'=>$type,
        	'video_id'=>$video_id,
        	'user_id'=>$user_id,
            'review_id'=>$review_id,
            'warn_user_id'=>$warn_user_id,
            'create_time'=>DateUtils::Now()
        );

        M('warn')->add($dt);
        $this->iSuccess();
    }
}