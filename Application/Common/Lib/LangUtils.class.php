<?php

namespace Common\Lib;

class LangUtils{

    public static function LangObj(&$obj,$k,$lang) {

        $fd=$k."_".$lang;
        $obj["$k"] = $obj["$fd"];

        //print_r();exit;

        unset($obj[$k."_"."en"]);
        unset($obj[$k."_"."ar"]);
    }

    public static function LangList(&$list,$k,$lang){
        
        foreach ($list as &$li) {
            LangUtils::LangObj($li,$k,$lang);
        }
    }
}