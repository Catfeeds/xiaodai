<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2016/12/26
 * Time: 14:01
 */

namespace Crm\Controller;


use Think\Controller;

class EmptyController extends Controller
{
    function _empty(){
        header( " HTTP/1.0  404  Not Found" );

        $this->display("Public:404");
    }

    function  index(){

        header( " HTTP/1.0  404  Not Found" );

        $this->display("Public:404");

    }
}