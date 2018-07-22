<?php

namespace Common\Lib;

use Common\Lib\String;

class FormatUtils{

        
        # 格式化价钱
        public static function Price(&$price){
             $price = number_format(floatval($price), 1, '.', '');
             return $price;
        }


        # 格式化图片链接
        /*
        public static function ImageCDN(&$imagekey,$w=500,$h=500){
            $imagekey = C('IMAGE_CDN_URL').$imagekey;
            return $imagekey;
        }*/

        # 格式化图片链接
        /*
        public static function ImageCDNObj(&$obj,$f,$w=500,$h=500){

            if($obj["$f"]){
                if(String::startWith(strtolower($obj["$f"]),'http')){

                    $obj["$f"] = $obj["$f"];
                }else{
                    $obj["$f"] = C('IMAGE_CDN_URL').$obj["$f"];
                }
            }
            return $obj;
        }*/

        # 格式化图片链接
        /*
        public static function ImageCDNArray(&$arr,$f,$w=500,$h=500){

            foreach ($arr as &$a) {
                if($a["$f"]){
                    $a["$f"]= C('IMAGE_CDN_URL').$a["$f"];
                }                
            }
            $ret=array();
            foreach ($arr as $li) {
                array_push($ret,$li["$f"]);
            }

            $arr = $ret;
            return $ret;
        }*/

        /*
        public static function QiniuImageCDN($key,$w=500,$h=500){

            $imagekey = C('Qiniu_Bucket_Greadeal_URL').$key;
            return $imagekey;
        }*/


        # 七牛格式化图片链接
        /*
        public static function QiniuImageCDNArray(&$arr,$f,$w=500,$h=500){

            foreach ($arr as &$a) {
                if($a["$f"]){
                    $a["$f"]= C('Qiniu_Bucket_Greadeal_URL').$a["$f"];
                }                
            }

            $ret=array();
            foreach ($arr as $li) {
                array_push($ret,$li["$f"]);
            }

            $arr = $ret;
            return $ret;
        }*/


        public static function HtmlDecode(&$list){

            foreach ($list as $k=>&$v) {
                if(is_string($v)){
                    $v=html_entity_decode($v);
                }
            }
            return $list;
        }

        public static function ObjectHtmlDecode(&$list){

            foreach ($list as $k=>&$v) {
                if(is_string($v)){
                    $v=html_entity_decode($v);
                }
            }
            return $list;
        }

        public static function HtmlDecoder(&$obj,$f){

            if($obj["$f"]){
                $obj["$f"]=html_entity_decode($obj["$f"]);
            }
        }

        public static function JsonDecoder(&$obj,$f){
            
            if($obj["$f"]){
                $obj["$f"]=str_replace('&quot;', '"', $obj["$f"]);
                $obj["$f"] = json_decode($obj["$f"],true);
            }
        }

}