<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\FormatUtils;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\SortUtils;
use Common\Lib\String;
use Common\Lib\QiniuUtils;


/**
 * 钱包
 */
class WalletController extends IController{

    // 获取官方充值
    public function get_wallet(){

       $user_id = IV('token','require|token');
       D('wallet')->init($user_id);

       $co=array(
       	  'user_id'=>$user_id
       );
       $wallet = M('Wallet')->where($co)->field('diamond_total,coin_total')->find();
       $this->iSuccess($wallet,'wallet');
    }
}