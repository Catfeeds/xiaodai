<?php

namespace Common\Lib;

class TPUtils{

    public static function BetweenTwoDay($date_start,$date_end) {
        
        $date_end = date('Y-m-d',strtotime("$date_end +1 day"));
        return array('between',array($date_start,$date_end));
    }

    public static function RandomData($mid,$b){

    	

    }
}
