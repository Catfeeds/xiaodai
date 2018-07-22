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
use Common\Lib\LangUtils;
use Common\Lib\ReceiptValidator;

/**
 * 充值
 */
class RechargeController extends IController{

    // 充值金币
    public function recharge_coins(){

        $user_id = IV('token','require|token');
        $type = IV('type',array('ios','android'));
        $official_recharge_item_id = IV('official_recharge_item_id','require');
        $official_recharge_item = M('official_recharge_item')->find($official_recharge_item_id);

        $rv = new ReceiptValidator();

        if($type=='ios'){

            $endpoint = C("ReceiptValidator_URL"); 
            //$endpoint = ItunesReceiptValidator::PRODUCTION_URL;  /*正式环境*/
            $receipt = IV('receipt','require');
           
            $result = $rv->validateIOSReceipt($endpoint,$receipt);
            if(!$result){   
                IE(ERROR_PAY_FAIL);
            }
        }else if($type=='android'){

            $responseData = IV('responseData','require|json_format');
            $responseData = json_encode($responseData);

            $signature = IV('signature','require');
            $result = $rv->validateAndroidReceipt($responseData,$signature);
            if(!$result){
                IE(ERROR_PAY_FAIL);
            }
        }
    
        // 加到金钱里面去
        D('wallet')->init($user_id);
        $t = bcadd($official_recharge_item['get_coin_total'],$official_recharge_item['award_coins'],2);

        // 判断是否有充值记录，第一次充值加
        $hasRecharge = M('wallet_recharge_record')->where(array('user_id'=>$user_id))->count();
        if($hasRecharge){
            $t = bcadd($t,$official_recharge_item['event_award_coins']);
        }

        D('wallet')->incTotal($user_id,$t,'coin_total');

        // 充值成功写入记录
        $dt=array(
            'user_id'=>$user_id,
            'award_coins'=>$official_recharge_item['award_coins'],
            'money'=>$official_recharge_item['money'],
            'get_coin_total'=>$official_recharge_item['get_coin_total'],
            'create_time'=>DateUtils::Now(),
            'recharge_status'=>'success',
            'type'=>$type,
            'receipt'=>$receipt,
            'responseData'=>$responseData,
            'signature'=>$signature
        );
        M('wallet_recharge_record')->add($dt);

        $this->iSuccess();
    }

    // 获取官方充值
    public function get_offical_recharge_items(){

       $lang = IV('lang','require');
       $recharge_items = M('official_recharge_item')->order('order_no desc')->select();
       LangUtils::LangList($recharge_items,'event_desc',$lang);

       $this->iSuccess($recharge_items,'recharge_items');
    }

    // 获取充值记录
    public function get_recharge_records(){

        $lang = IV('lang','require');
        $user_id = IV('token','require|token');
        $page = IV('page','require');
        $limit = IV('limit','require');

        $co=array(
            'user_id'=>$user_id
        );
        $recharge_records = M("wallet_recharge_record")->where($co)
                                                      ->page($page)
                                                      ->limit($limit)
                                                      ->order('create_time desc')->select();

        $co['recharge_status'] = 'success';
        $sum_coin_total = M("wallet_recharge_record")->where($co)->sum('get_coin_total');
        $sum_coin_total = round($sum_coin_total, 2);

        if(!$sum_coin_total){
            $sum_coin_total=0;
        }

        $res=array(
            'recharge_total'=>$sum_coin_total,
            'recharge_records'=>$recharge_records
        );

        $this->iSuccess($res);
    }

}