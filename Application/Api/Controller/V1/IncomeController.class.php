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
use Common\Lib\CDNUtils;

/**
 * 收入
 */
class IncomeController extends IController{

	// 获取收入记录
	public function get_income_records(){

		$user_id = IV('token','require|token');
       	$lang = IV('lang','require');
       	$page = IV('page','require');
       	$limit = IV('limit','require');
       	$type = IV('type',array('gift','video_view'));

		$co=array(
			'video_user_id'=>$user_id,
			'income_type'=>$type
		);
		$income_records = M('wallet_income_record')->where($co)->order('income_time desc')->page($page)->limit($limit)->select();

		LangUtils::LangList($income_records,'income_gift_name',$lang);
		CDNUtils::ImageCDNArray($income_records,'income_video_image');
		LangUtils::LangList($income_records,'income_video_title',$lang);

		// 总收入
		$income_tatal = M('wallet_income_record')->where(array('video_user_id'=>$user_id))->sum('income_total_diamond');
		if(!$income_tatal){
            $income_tatal=0;
        }

		// 周总收入
		$co = array(
			'video_user_id'=>$user_id,
			'income_time'=>array('between',array(DateUtils::AfterTime(-30),DateUtils::Now()))
		);
		$week_income_tatal = M('wallet_income_record')->where($co)->sum('income_total_diamond');
		if(!$week_income_tatal){
            $week_income_tatal=0;
        }

		// 钱包里面的钻石
		$wallet_diamond_total = D('wallet')->getDiamondTotal($user_id);

		$res=array(
			'income_tatal'=>$income_tatal,
			'income_records'=>$income_records,
			'month_income_tatal'=>round($week_income_tatal,2),
			'wallet_diamond_total'=>round($wallet_diamond_total,2),
		);

		$this->iSuccess($res);
	}
}