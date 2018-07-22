<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\DateUtils;

class TagService extends IService {
	    
   

    // #xxx #bbb #ccc
    public function identify_tags($video_id,$str){


    	/**
    	 *  @zhao ,将 #嘻嘻嘻 #dddd 处理成 标签 dddd ，不要带#
    	 */
        preg_match_all("/#([^\s]*)/i",$str, $match);  //正则匹配获取tag，
        if($match[1]){
            $words=$match[1];
        }else{
            $words=array();
        }

        

    	#$words=array();
    	foreach ($words as $wd) {

    		$tg = $this->getTagCreated($wd);

    		$dt=array(
    			'tag_id'=>$tg['tag_id'],
    			'video_id'=>$video_id,
    			'create_time'=>DateUtils::Now(),
    			'order_weight'=>'1',
    			'lang'=>"ar",
    			'video_from'=>'app'
    		);

    		M("video_to_tag")->add($dt);
    	}
    }

    public function getTagCreated($tag_name){

    	$co=array(
    		'name_ar'=>$tag_name,
            'vesion'=>'v2'
    	);
    	$tag = D('tag')->where($co)->find();

    	if($tag){
    		return $tag;
    	}else{

    		$dt=array(
    			'name_en'=>$tag_name,
    			'is_recommend'=>0,
    			'name_ar'=>$tag_name
    		);
    		$dt['tag_id'] =  M('tag')->add($dt);

    		return $dt;
    	}
    }

}