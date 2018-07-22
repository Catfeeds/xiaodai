<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\PasswordUtil;
use Common\Lib\VercodeUitls;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\FormatUtils;
use Common\Lib\CDNUtils;
use Common\Lib\LangUtils;

/**
 * Topic信息
 */
class TopicController extends IController{

      // 获取排行
      public function get_ranking_list(){

            $page=IV('page','require');
            $limit= IV('limit','require');
            $lang  = IV('lang','require');

            $co=array(
                'status'=>1,
            );

            $list = M('topic')->where($co)->cache(60*10)->page($page)->limit($limit)->order('view_count desc')->select();
            CDNUtils::ImageCDNArray($list,"author_header");
            LangUtils::LangList($list,'name',$lang);

            $this->iSuccess($list,'topic_list');
      }

      // 获取视频列表
      public function get_video_list(){

            $topic_id = IV('topic_id','require'); // tag_id 为0 则是全局的热门的
            $lang  = IV('lang','require');
            $limit = IV('limit','require');
            $page = IV('page','require');
            $user_id = IV('token','token');
            $orderby = IV('orderby',array('date','trending','weight'));

            if($orderby=='weight'){
              $orderby ='popular';
            }

            if($orderby=='date'){
                $list = Service('Video')->get_topic_video_list_orderby_date($topic_id,$lang,$page,$limit,$user_id,$content_lang);
            }else if($orderby=='trending'||$orderby=='popular'){
                $list = Service('Video')->get_topic_video_ranking($topic_id,$lang,$page,$limit,$user_id,$orderby);
            }

            // 处理图片
            D('Video')->optImageOfVideoList($list);
            
            $this->iSuccess($list,'vlist');
      }

      // 获取主题
      public function get_topic(){

            $topic_id = IV('topic_id','require'); // tag_id 为0 则是全局的热门的
            $lang  = IV('lang','require');

            $topic = M('topic')->find($topic_id);
            LangUtils::LangObj($topic,'name',$lang);
            CDNUtils::ImageCDNObj($topic,"author_header");
            $this->iSuccess($topic,'topic');
      }

      # 获取tag用户的排行
      public function get_topic_user_ranking(){

          $topic_id = IV('topic_id','require');
          $page = IV('page','require');
          $limit = IV('limit','require');

          $co=array(
             'tag_id'=>$tag_id,
          );

          $ulist = M('video_topic_user_ranking')->field('user_name,user_image,user_id,like_count')
                                                ->where($co)
                                                ->page($page)->limit($limit)
                                                ->order('like_count desc')
                                                ->select();

          CDNUtils::ImageCDNArray($ulist,'user_image');

          $this->iSuccess($ulist,'user_ranking');
      }
}