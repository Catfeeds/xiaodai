<?php

namespace Common\Lib;

class HtmlUtils{

   
   static function parseImgSrcLinks($html){

        $html = html_entity_decode($html);
        
        #正则表达式升级
        $reg="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png|\.JPG|\.PNG|\.JPEG]))[\'|\"].*?[\/]?>/";

        preg_match_all( $reg , $html, $results );

        return $results[1];
   }

   #字符串过滤器
   public function stringFilter(&$s){
        $s = html_entity_decode($s);
        return $s;
   }

   
}