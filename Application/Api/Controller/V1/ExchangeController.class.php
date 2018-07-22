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
use Common\Lib\FloatUtils;

/**
 * 兑换
 */
class ExchangeController extends IController{

    // 获取官方兑换
    public function get_offical_exchange_items(){

       $exchange_items = M('official_exchange_item')->order('order_no desc')->select();
       $this->iSuccess($exchange_items,'exchange_items');
    }

    // 获取兑换记录
    public function get_exchange_records(){

    	$lang = IV('lang','require');
        $user_id = IV('token','require|token');
        $page = IV('page','require');
        $limit = IV('limit','require');

        $co=array(
        	'user_id'=>$user_id
        );
    	$list = M('wallet_exchange_record')->where($co)->page($page)->limit($limit)->select();

        $co=array('user_id'=>$user_id);
        $exchange_total = M('wallet_exchange_record')->where($co)->sum('get_coin_total');

        if(!$exchange_total){
           $exchange_total=0;
        }

        $res=array(
          'exchange_total'=> round($exchange_total,2),
          'exchange_record'=>$list
        );
        
    	$this->iSuccess($res);
    }

    // 将钻石兑换成金币
    public function exchange_coins(){

    	$official_exchange_item_id = IV('official_exchange_item_id','require');
    	$user_id = IV('token','require|token');

    	$official_exchange_item = M('official_exchange_item')->find($official_exchange_item_id);

    	// 判断是user 账户里面是否有足够的钻石
     	$getTotal = D('Wallet')->getTotal($user_id);
     	$diamondTotal = $getTotal['diamond_total'];
     	$coin_total = $getTotal['coin_total'];
     	if(!FloatUtils::gte($diamondTotal,$official_exchange_item['pay_diamond_total'])){
     		IE(ERROR_LESS_DIAMOND_EXCHANGE);
     	}

     	// 减少钻石，并且加上金币
     	$dt=array(
     		'diamond_total'=>FloatUtils::sub($diamondTotal,$official_exchange_item['pay_diamond_total']),
     		'coin_total'=>FloatUtils::add($coin_total,$official_exchange_item['get_coin_total'])
     	);

     	$co=array(
     		'user_id'=>$user_id
     	);
     	$s = M('wallet')->where($co)->save($dt);

     	if($s){
     		//写记录
     		$dt=array(
     			'user_id'=>$user_id,
				'pay_diamond_total'=> $official_exchange_item['pay_diamond_total'],
				'get_coin_total'=> $official_exchange_item['get_coin_total'],
				'pay_time'=>DateUtils::Now(),
     		);
     		M('wallet_exchange_record')->add($dt);

     		$this->iSuccess();
     	}else{
     		IE(ERROR_SERVER_DB);
     	}
    }
}