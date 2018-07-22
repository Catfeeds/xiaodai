<?php
namespace Rest3\Service;

use Common\Service\IService;
use Common\Lib\ArrayUtils;
use Common\Lib\SortUtils;
use Common\Lib\DateUtils;
use Common\Lib\String;
use DiDom\Document;

class FBCrawlerService {
    

    // 初始化公众号的视频列表
    public function initFBOldPageVideos($fbPageID){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $url = "https://graph.facebook.com/v2.8/$fbPageID/?fields=videos.limit(100){created_time,description,format,id,source}&access_token=574682966066129%7CX-Dde5GFi8XUFxDlXKqoYVnOKCI&format=json&method=get";
        $count=1;
        $loop_count=1;

        while(true){

            PrintLine($url);

            $json = file_get_contents($url);
            if(!$json){
                PrintLine('Fail to getJson fbPageID:'.$fbPageID);
                return null;
            }
            $info = json_decode($json,true);

            if($loop_count>1){
                $info['videos']['data'] = $info['data'];
                $info['videos']['paging'] = $info['paging'];
            }

            if(!$info['videos']['data']){
                PrintLine('Fail to getData fbPageID:'.$fbPageID);
                return null;
            }

            //===================================
            $user_id = M('user')->where(array('update_source_url'=>$fbPageID))->getField('user_id');

            if(!$user_id){
                PrintLine('Fail to getUserID fbPageID:'.$fbPageID);
                return null;
            }
            
            foreach ($info['videos']['data'] as $v) {

                $updateTime = str_replace('+0000','',str_replace('T',' ',$v['created_time']));
                $v['format'] = array_reverse($v['format']);

                $dt=array(
                    'title_en'=>$v['description'],
                    'title_ar'=>$v['description'],
                    'create_time'=>$updateTime,
                    'update_time'=>$updateTime,
                    'user_id'=>$user_id,
                    'video_from'=>'facebook',
                    'order_weight'=>10000,
                    'fb_video_id'=>$v['id'],
                    'base_like_count'=>rand(5000,100000),
                    'base_view_count'=>rand(10000,1000000),
                    'image_width'=>$v['format'][0]['width'],
                    'image_height'=>$v['format'][0]['height'],
                    'video'=>$v['source'],
                    'image'=>$v['format'][0]['picture']
                );

                $coo=array('fb_video_id'=>$v['id']);
                $c = M('video')->where($coo)->count();

                if($c==0){
                    $id = M('video')->add($dt);
                    PrintLine('Success to Save Video video_id:'.$id);
                }else{
                    PrintLine('Fail to save Repeat Video fb_video_id:'.$v['id']);
                }

                PrintLine('Success to Save Video count:'.$count++);
            }

            PrintLine('Success to dumpin Video count:100');

            //==============

            if($info['videos']['paging']['next']){

                $url = $info['videos']['paging']['next'];
                PrintLine('Success to loop Video count:'.$loop_count++);

            }else{
                PrintLine('End to loop Video count:'.$loop_count);
                break;
            }
            //=============
        }

        PrintLine('End to Loop Count:'.$loop_count);
        PrintLine('End to Video Count:'.$count);
    }


    // 获取公众号基本信息
    public function initFBPage($fbPageID){

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $url ="https://graph.facebook.com/$fbPageID?fields=name,picture{url}&access_token=574682966066129%7CX-Dde5GFi8XUFxDlXKqoYVnOKCI&format=json&method=get";
        $json = file_get_contents($url);
        $info = json_decode($json,true);

        if(!$info['name']){
            PrintLine('Fail to getFBPageBaseInfo fbPageID:'.$fbPageID);
            return null;
        }else{
            PrintLine('Success to getFBPageBaseInfo fbPageID:'.$fbPageID);
        }

        $dt=array(
            'name'=>$info['name'],
            'image'=>$info['picture']['data']['url'],
            'date_added'=>DateUtils::Now(),
            'role'=>'facebook_crawler',
            'update_source_url'=>$fbPageID
        );

        M('user')->add($dt);
        PrintLine('Success to save DB fbPageID:'.$fbPageID);
    }


    public function getFBVideo($fb_video_id){

        //$xml = file_get_contents('http://graph.facebook.com/'.$fb_video_id);
        $url = "https://graph.facebook.com/v2.8/$fb_video_id?fields=source&access_token=574682966066129%7CX-Dde5GFi8XUFxDlXKqoYVnOKCI&format=json&method=get";
        $xml = file_get_contents($url);

        if(!$xml){
            return false;
        }
        $result = json_decode($xml,true);
        return $result['source'];
    }

}