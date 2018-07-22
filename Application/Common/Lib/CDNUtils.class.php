<?php

namespace Common\Lib;

use Common\Lib\String;

class CDNUtils{

        # 格式化图片链接
        public static function ImageCDN(&$imagekey,$w=500,$h=500){

            $imagekey = C('IMAGE_CDN_URL').$imagekey;
            return $imagekey;
        }

        # 格式化图片链接
        public static function ImageCDNObj(&$obj,$f,$w=500,$h=500){

            if($obj["$f"]){
                if(String::startWith(strtolower($obj["$f"]),'http')){
                    $obj["$f"] = $obj["$f"] ;
                    $obj["$f"] = str_replace('amp;','',$obj["$f"]);
                }else{
                    $obj["$f"] = CDNUtils::ImageCDN($obj["$f"]);
                }
            }
            return $obj;
        }

        # 格式化图片链接
        public static function ImageCDNArray(&$arr,$f,$w=500,$h=500){

            foreach ($arr as &$a) {
                CDNUtils::ImageCDNObj($a,$f,$w,$h);
            }
            return $arr;
        }

        #========================
        
        # 格式化图片链接
        public static function VideoCDN(&$imagekey){

            if(String::startWith($imagekey,'http')){}else{
                $imagekey = C('IMAGE_CDN_URL').$imagekey;
            }
            return $imagekey;
        }

        # 格式化图片链接
        public static function VideoCDNObj(&$obj,$f){

            if($obj["$f"]){
                if(String::startWith(strtolower($obj["$f"]),'http')){
                    $obj["$f"] = $obj["$f"];
                }else{
                    $obj["$f"] = C('IMAGE_CDN_URL').$obj["$f"];
                }
            }
            return $obj;
        }

        # 格式化图片链接
        public static function VideoCDNArray(&$arr,$f){

             foreach ($arr as &$a) {
                CDNUtils::VideoCDNObj($a,$f);
            }
            return $arr;
        }
}