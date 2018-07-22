<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\MapUtils;
use Common\Lib\String;


class VideoCacheService {
    

    // 从缓存中获取视频IDs
    public function getVideoIDsFromCacheByTagIDs($tag_ids,$lang,$exclude_video_ids,$limit,$page=1){


        if($lang){

            $co=array(
                'lang'=>array('in',array('all',$lang))
            );

            $video_list = M('video_to_tag')->where($co)
                                       ->field('video_id,tag_id,lang')
                                       ->cache(3600*6)
                                       ->order('create_time desc')->page($page)->limit(20000)->select();

        }else{

            $video_list = M('video_to_tag')->field('video_id,tag_id,lang')->cache(3600*6)
                                            ->order('create_time desc')->page($page)->limit(20000)->select();
        }

        $res=array();
        foreach ($video_list as $video) {
            
            $v_tagid = $video['tag_id'];
            $v_video_id = $video['video_id'];
            $v_lang = $video['lang'];
            $lang_arr = array($v_lang,'all');

            if(!in_array($v_tagid, $tag_ids)||in_array($v_video_id, $exclude_video_ids)){
                contine;
            }else{
                ArrayUtils::pushUniqueArray($res,$v_video_id);
            }

            if(count($res)>=$limit){
                return $res;
            }
        }
        return array();
    }
}