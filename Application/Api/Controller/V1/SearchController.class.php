<?php
namespace Rest3\Controller\V1;

use Common\Controller\IController;
use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\MapUtils;
use Common\Lib\LangUtils;
use Common\Lib\CDNUtils;

/** 
 * 搜索
 */
class SearchController extends IController{


    public function search_video_list(){

        $keyword = IV('keyword');
        $lang = IV('lang','require');
        $page = IV('page','require');
        $limit = IV('limit','require');
        $user_id = IV('token','token');

        if(!$keyword){
            $this->iSuccess(array(),'video_list');
        }

        if($lang=='ar'){

            $co=array(
                'title_ar'=>array('like',"%$keyword%"),
                'status'=>1
            );

        }elseif($lang=='en'){

            $co=array(
                'title_en'=>array('like',"%$keyword%"),
                'status'=>1
            );
        }

        $vlist = D('video')->where($co)->field('video_id')->page($page)->limit($limit)->select();
        $vids = ArrayUtils::Collect($vlist,'video_id');

        if(empty($vids)){
            $this->iSuccess(array(),'video_list');
        }

        $vlist = D('video')->getList($vids,$lang,null,$user_id);

        D('Video')->optImageOfVideoList($vlist);
    
        $this->iSuccess($vlist,'video_list');
    }

    public function search_tag(){

        $keyword = IV('keyword');
        $lang = IV('lang','require');
        $page = IV('page','require');
        $limit = IV('limit','require');
        $user_id = IV('token','token');

        $co=array(
            'name_ar'=>array('like',"%$keyword%"),
        );  
        $tag_list = M('tag')->where($co)->field('tag_id,name_en,name_ar')->page($page)->limit($limit)->select();

        foreach ($tag_list as &$tag) {
            LangUtils::LangObj($tag,"name",'ar');
            $co=array('tag_id'=>$tag['tag_id']);
            $tag['video_count'] = M('video_to_tag')->where($co)->count();
        }

        $this->iSuccess($tag_list,'tag_list');
    }

    public function search_user(){

        $keyword = IV('keyword');
        $lang = IV('lang','require');
        $page = IV('page','require');
        $limit = IV('limit','require');
        $user_id = IV('token','token');

        if(!$keyword){

            $hotlist = M('user_ranking')->field('user_name,user_image,user_id,like_count')->page($page)->limit($limit)->order('like_count desc')->select();

            $list=array();
            foreach ($hotlist as $li) {
                $t=array(
                    'user_id'=>$li['user_id'],
                    'image'=>$li['user_image'],
                    'name'=>$li['user_name'],
                    'v_flag'=>M('user')->findField($li['user_id'],'v_flag'),
                );
                array_push($list,$t);
            }
        }else{

            $co=array(
                'name'=>array('like',"%$keyword%"),
            );  
            $list = M('user')->where($co)->field('user_id,image,name,v_flag')->page($page)->limit($limit)->select();
        }

        CDNUtils::ImageCDNArray($list,'image');
        $this->iSuccess($list,'user_list');
    }

    public function search_hot_words(){

        $lang = IV('lang','require');
        
        $co=array(
            'lang'=>$lang
        );
        $list = M('search_word')->where($co)->field('word')->select();

        $this->iSuccess($list,'words');
    }

    public function auto_complete(){

        $lang = IV('lang','require');
        $filter = IV('filter','require');
        $limit = 10;

        $co=array(
            'lang'      =>$lang,
            'word'    =>array('like',"%$filter%")
        );
        $list = M('search_word')->where($co)->field('word')->limit($limit)->select();
        
        $this->iSuccess($list,'words');
    }
}