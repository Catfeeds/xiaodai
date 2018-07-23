<?php
namespace Rest3\Model;

use models\systemconfig\SystemConfigModel;
use Think\Model;

class LoanModel extends Model{

	18683672689
    #获取最新的app版本
    public function getHome(){

	    /*
        $data['amount_limit'] = SystemConfigModel::getValue('amount_limit','10000');
        $data['card_desc_cn'] = SystemConfigModel::getValue('card_desc_cn','铂金卡');
        $data['card_desc_en'] = SystemConfigModel::getValue('card_desc_en','Bo Jin Ka');
        $data['card_no'] = SystemConfigModel::getValue('card_no','12343455');
        $data['loan_amount_from'] = SystemConfigModel::getValue('loan_amount_from','2000');
        $data['loan_amount_to'] = SystemConfigModel::getValue('loan_amount_to','30000');
        $data['loan_day_from'] = SystemConfigModel::getValue('loan_day_from','7');
        $data['loan_day_to'] = SystemConfigModel::getValue('loan_day_to','14');
        $data['waring'] = SystemConfigModel::getValue('loan_day_from','bu xiang xueshent daikuan');
*/
	    $finish_status = '4';   // 表示已还款状态
	    $filter['memberid'] = get_memberid();
	    $filter['status'] = array('neq',$finish_status);
	    $lastest_loan = M('loan')
            ->alias('a')
            ->field('
                a.id,                       -- 订单id
                a.orderno,                  -- 订单号
                a.memberid,                 -- 会员id
                a.idcard,                   -- 身份证号
                a.damount,                  -- 贷款金额
                a.interest,                 -- 利率 
                a.interestrate,             -- 利息金额
                a.amount,                   -- 应还金额，本金+利息+逾期费
                a.days,                     -- 贷款天数
                a.step,                     -- json 格式，贷款记录过程，（逾期次数）
                a.deadline,                 -- 还款截止日期
                a.status,                   -- 状态
                a.shenhestatus,             -- 审核状态 0-未通过，1-已通过
                a.refusereason,             -- 拒绝理由
                a.no,                       -- 优惠劵号
                a.status1,                  -- 客户确认         -- 到账金额
                a.daozhang      
            ')
            ->where($filter)
            ->find();
        $statuslist = array(
            '0'=>'待审核',
            '1'=>'已审核',
            '2'=>'已放款',
            '3'=>'已延期',
            '3'=>'已逾期',
            '4'=>'已还款'
        );
	    if($lastest_loan)   // 如果有最新还未还款的数据,就取实际还款进度
        {

            switch ($status)
            {
                case 1:
                    echo "Number 1";
                    break;
                case 2:
                    echo "Number 2";
                    break;
                case 3:
                    echo "Number 3";
                    break;
                default:
                    echo "No number between 1 and 3";
            }
        }
        else            // 不然，取首页配置数据返回
        {
            $data = SystemConfigModel::getValue('home_config','');
        }
        $co=array(
            'checked'=>true,
            'type'=>$app_type,
            #'bundle_id'=>$app_bundle_id
        );
        $fields='version,
                info,
                created,
                force_update,
                app_link,appstore_updated';

        $it= $this->where($co)->field($fields)->order('version desc')->find();
        return $it;
    }
   
}