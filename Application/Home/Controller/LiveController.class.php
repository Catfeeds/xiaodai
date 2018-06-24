<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/6/13
 * Time: 15:24
 */

namespace Home\Controller;


class LiveController extends AuthbaseController
{
    /**
     * 跳转到直播地址
     */
    public function index(){
        $memberid=get_memberid();
        $openid=M('member')->where(array('id'=>$memberid))->getField('openid');
        $url='http://live.hzwin.cn:8088/index.html?openid='.$openid;
        redirect($url);
    }
}