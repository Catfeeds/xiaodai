<?php

namespace Common\Lib;

class SortUtils{

    #插入排序
    public static function InsertionSort(&$array,$key,$order) {

        $array_length = count($array); // 数组的长度
         
        // 进行数组排序，视第一个数组元素属于一个有序的数组。
        for ($i = 1; $i < $array_length; $i++) {

            $inserted_item = $array[$i];

            $inserted_value = $array[$i]["$key"]; // 待插入的数组元素

            $inserted_index = $i - 1; // 待插入的位置
             
            // 当$inserted_value前面还有其他数组元素并且值比它小的时候
            while (($inserted_index >= 0) && ($inserted_value < $array[$inserted_index]["$key"])) {
                $array[$inserted_index + 1] = $array[$inserted_index]; // $inserted_value的前一个数组元素被后移
                $inserted_index--; // 待插入的位置递减变化
            }
             
            // 当$inserted_index的值发生了变化才进行插入操作
            if (($inserted_index + 1) != $i) {

                // 找到了$inserted_value的正确位置，插入该元素。
                $array[$inserted_index + 1] = $inserted_item;
            }
        }

        if($order=='desc'){
            $array = array_reverse($array);
        }
    }

}