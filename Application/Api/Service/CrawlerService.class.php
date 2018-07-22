<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\DateUtils;
use Common\Lib\String;
use DiDom\Document;

class CrawlerService {
    

    // 解析出视频列表
    public function analyzeVideos($htm,$user_id){

        $document = new Document($htm);
        $list = $document->find('._51m-');

        $res=array();
        foreach ($list as $li) {

            # 视频名称
            $a1 = $li->find('._3v4h')[0];
            if(!$a1){
                $title = "blank";
            }else{
                $a = $a1->find('a')[0];
                $title = html_entity_decode($a->text());
            }

            if($li->find('a._5asn')[0] ==null){
                continue;
            }

            // 视频链接
            $furl = "https://www.facebook.com".$li->find('a._5asn')[0]->getAttribute('href');

            // 统计数据
            $count_str = trim($li->find('._3v4i')[0]->text());
            $count_str = str_replace(",", "", $count_str);

            $count_arr = explode('·', $count_str);
            
            $like_count = explode(" ", trim($count_arr[0]))[0];
            $view_count = explode(" ", trim($count_arr[1]))[0];


            $obj['video'] =  $furl; 
            $obj['title_en'] = $title;
            $obj['video_from'] = 'facebook';
            $obj['title_ar'] = $title;
            $obj['other_info'] = "";
            $obj['user_id'] = $user_id;
            $obj['image'] = str_replace('amp;','',$li->find('.img')[0]->getAttribute('src'));
            $obj['create_time'] = DateUtils::Now();
            $obj['update_time'] = DateUtils::Now();

            $obj['base_like_count'] = $like_count;
            $obj['base_view_count'] = $view_count;
            
            $obj['order_weight'] = $view_count;

            $obj['status'] = 3;

            array_push($res,$obj);
        }

        return $res;
    }


    # 解析名字和视频
    public function analyzeNameAndImage($htm){

        $document = new Document($htm);

        $name_dom = $document->find('a._2wma');

        if(!$name_dom){
            $name = "blank";
        }else{
            $name = $document->find('a._2wma')[0]->text();
        }
        
        if(!$document->find('img._4jhq')){
            $image = "";
        }else{
            $image = str_replace('amp;','',$document->find('img._4jhq')[0]->getAttribute('src'));
        }

        return array(
            'name'=>$name,
            'image'=>$image
        );
    }

    # 是否存在视频
    public function findVideo($url){

        $co=array(
            'video'=>$url
        );

        $n = M('video')->where($co)->find();

        if($n){
            return $n;
        }else{
            return false;
        }
    }


    # 记日志
    public function crawl_log($user_id,$url,$new_count,$update_count,$status){

        $dt=array(
            'user_id'=>$user_id,
            'url'=>$url,
            'new_count'=>$new_count,
            'update_count'=>$update_count,
            'status'=>$status,
            'create_time'=>DateUtils::Now()
        );
        D("crawl_log")->add($dt);
    }
    
}