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
use Common\Lib\RateUtils;

/**
 * 提现
 */
class WithdrawController extends IController{

	
	public function withdraw(){

		$user_id = IV('token','require|token');
		$withdraw_type =IV('withdraw_type',array('paypal','bank','phone','xilian'));
		$withdraw_money = IV('withdraw_money','require');

		//$cost_diamond = IV('cost_diamond','require');
		
		if(intval($withdraw_money)<20){
			IE(ERROR_WITHDRAW_LIMIT);
		}
		
		// 判断是否有足够的钻石
		$cost_diamond = RateUtils::getDiamondFromMoney($withdraw_money);
		D('Wallet')->enoughDiamond($user_id,$cost_diamond);

		// 删除钻石
		D('Wallet')->decTotal($user_id,$cost_diamond,'diamond_total');

		// 写入统计
		$dt=array(
			'withdraw_user_id'=>$user_id,
			'create_time'=>DateUtils::Now(),
			'withdraw_money'=>$withdraw_money,
			'cost_diamond'=>$cost_diamond
		);

		$dt['bank_name'] = IV('bank_name');
		$dt['bank_number'] = IV('number');
		$dt['country'] = IV('country');
		$dt['firstname'] = IV('firstname');
		$dt['lastname'] = IV('lastname');
		$dt['email'] = IV('email');
		$dt['paypal_account'] = IV('paypal_account');
		$dt['phone_number'] = IV('phone_number');
		$dt['withdraw_type'] = $withdraw_type;

		if($withdraw_type=='paypal'){
		}else if($withdraw_type=='bank'){
		}else if($withdraw_type=='phone'){
		}

		M('wallet_withdraw_record')->add($dt);
		
		$this->iSuccess();
	}

	public function get_withdraw_records(){

		$user_id = IV('token','require|token');
		$page =IV('page','require');
		$limit = IV('limit','require');

		$co=array(
			'withdraw_user_id'=>$user_id
		);
		$withdraw_records = M('wallet_withdraw_record')->where($co)->page($page)->limit($limit)->select();
		
		$co['deal_progress']='process_success';
		$withdraw_total = M('wallet_withdraw_record')->where($co)->sum('withdraw_money');

		if(!$withdraw_total){
			$withdraw_total=0;
		}

		$res=array(
			'withdraw_total'=>$withdraw_total,
			'withdraw_records'=>$withdraw_records
		);

		$this->iSuccess($res);
	}

	// 
	public function get_withdraw_info(){

	   $user_id = IV('token','require|token');
       D('wallet')->init($user_id);

       $co=array(
       	  'user_id'=>$user_id
       );
       $wallet = M('Wallet')->where($co)->field('diamond_total')->find();
       $wallet['can_withdraw_money'] = RateUtils::getMoneyFromDiamond($wallet['diamond_total']);

       $res=array(
       	 'withdraw_info'=>$wallet,
       	 'rate_diamond'=>C('Rate_Diamond'),
       	 'rate_coin'=>C('Rate_Coin'),
       );

       $this->iSuccess($res);
	}
}