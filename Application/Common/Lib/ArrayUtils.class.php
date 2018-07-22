<?php

namespace Common\Lib;

class ArrayUtils {

    #提取某个字段
    public static function Collect($list,$field){

        $ret_list = array();
        foreach ($list as $p) {
            array_push($ret_list,$p["$field"]);
        }
        return $ret_list;
    }

    public static function Paging(&$list,$page,$limit){

        $start = ($page-1)*$limit;
        $list = array_slice($list,$start,$limit);
        return $list;
    }

    public static function CombineList($list,$clist,$f){

        if(empty($list)){
            return $clist;
        }
        if(empty($clist)){
            return $list;
        }

        foreach ($list as &$li) {
            foreach ($clist as &$cli) {
                if($li["$f"] == $cli["$f"]){
                    $li = array_merge($li,$cli);
                }
            }
        }
      
        return $list;
    }

    public static function CombineObject($list,$clist){

        if(empty($list)){
            return $clist;
        }
        if(empty($clist)){
            return $list;
        }
        return array_merge($list,$clist);
    }

    #  数组重命名
    public static function Rename(&$arr,$kvs){

        foreach ($kvs as $k=>$v) {
            $arr["$v"]=$arr["$k"];
            unset($arr["$k"]);
        }
    }


    public static function Group(&$list){

        $res=array();
        foreach ($list as $li) {
            if(!in_array($li,$res)){
                array_push($res,$li);
            }
        }
        $list=$res;
        return $res;
    }

    public static function GroupByField(&$items,$field){

        $res=array();
        $tmp =array();
        
        foreach ($items as $item) {
            if(!in_array($item["$field"],$tmp)){
                array_push($res,$item);
                array_push($res,$item["$field"]);
            }
        }

        $items=$res;
        return $res;
    }

    public static function GroupBy(&$list,$name){

        $res = array();
        foreach ($list as $li) {
                
            $key = $li["$name"];

            if($res["$key"]){
                array_push($res["$key"],$li);
            }else{
                $res["$key"]=array();
                array_push($res["$key"],$li);
            }
        }

        return $res;
    }
    

    public static function CollectNotNULL(&$arr,$vars){

       $count = func_num_args();
       $varArray = func_get_args();
       
       for($i=2;$i<$count;$i++){
            $name = $varArray[$i];
            if($vars["$name"]!=null){
                $arr["$name"]=$vars["$name"];
            }
       }
       return $arr;
    }

    public static function CollectIfNotNULL(&$arr,$name,$value){

        if($value){
            $arr["$name"] = $value;
        }
    }


    # 数组的筛选
    public static function StatusFilter(&$list){

        $res=array();
        foreach ($list as $li) {
            if(intval($li['status'])==1){
                array_push($res,$li);
            }
        }
        $list=$res;
    }

    # 数组的筛选
    public static function ProductNULLFilter(&$list){

        $res=array();
        foreach ($list as $li) {
            if(count($li['product_list'])){
                array_push($res,$li);
            }
        }
        $list=$res;
    }

    # 统计排序 
    public static function Count(&$list){

        $sort_arr=array();
        foreach ($list as $k) {
            
            if($sort_arr["$k"]){
                $sort_arr["$k"]['count']++;
            }else{
                $sort_arr["$k"]=array('count'=>1,'key'=>$k);
            }
        }

        $res=array();
        foreach ($sort_arr as $key => $value) {
            array_push($res,$value);
        }

        $list =$res;
        return $res;
    }

    public function pushUniqueArray(&$arr,$v){

        if(!in_array($v,$arr)){
            array_push($arr,$v);
        }
    }
}