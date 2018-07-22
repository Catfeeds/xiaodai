<?php

namespace Common\Lib;

class FloatUtils{

    
    static function equal($f1,$f2,$precision = 2) {// are 2 floats equal

        $f1=floatval($f1);
        $f2=floatval($f2);

        $e = $precision; 
        $i1 = intval($f1 * $e);  
        $i2 = intval($f2 * $e);  
        return ($i1 == $i2);  
    }

    static function gt($big,$small,$precision = 2) {// is one float bigger than another

        $big=floatval($big);
        $small=floatval($small);


        $e = $precision;
        $ibig = intval($big * $e);
        $ismall = intval($small * $e);  
        return ($ibig > $ismall);  
    }

    static function gte($big,$small,$precision = 2) {// is on float bigger or equal to another

        $big=floatval($big);
        $small=floatval($small);

        $e = $precision;
        $ibig = intval($big * $e);
        $ismall = intval($small * $e);

        return ($ibig >= $ismall);  
    }

    static function add($a,$b,$precision = 2){
        return bcadd($a,$b,$precision);
    }

    static function sub($left, $right,$precision = 2){
        return bcsub($left, $right, $precision);
    }

    static function mul($left, $right,$precision = 2){
        return bcmul($left, $right, $precision);
    }
}