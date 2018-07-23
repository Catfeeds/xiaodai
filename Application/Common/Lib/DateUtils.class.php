<?php

namespace Common\Lib;

class DateUtils {

    public static function NowTimeStamp(){
        return time();
    }



    public static function Now(){
        return date('Y-m-d H:i:s');
    }

    public static function Today(){
        return date('Y-m-d');
    }

    public static function Tomorrow(){
        return date("Y-m-d",strtotime("+1 day"));
    }

    public static function Yesterday(){
        return date("Y-m-d",strtotime("-1 day"));
    }

    public static function AfterTime($n,$t='d'){

        $date = (new Date(date('Y-m-d H:i:s')))->dateAdd($n,"d");
        return $date->format();
    }

    public static function AfterTimeFrom($from_time,$n,$t='d'){

        $date = (new Date(date($from_time)))->dateAdd($n,"d");
        return $date->format();
    }

    # 是否过期
    public static function isExpired($date){

        if(strtotime($date)>=time()){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 返回两个时间的秒数
     * @param $startdate
     * @param $enddate
     * @return float
     */
    public static  function diffTimeSecs($startdate,$enddate)
    {
        $seconds=floor((strtotime($enddate)-strtotime($startdate))%86400%60);
        return $seconds;
    }

    /**
     * 返回两个时间的秒数
     * @param $startdate
     * @param $enddate
     * @return float
     */
    public static  function diffTimeStampSecs($starttime,$endtime)
    {
        $seconds=floor(($endtime-$starttime)%86400%60);
        return $seconds;
    }
    
}