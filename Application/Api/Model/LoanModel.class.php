<?php
namespace Api\Model;

use models\systemconfig\SystemConfigModel;
use Think\Model;

class LoanModel extends Model{

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
            '4'=>'已逾期',
            '5'=>'已还款'
        );

        $timeline = array();
	    if($lastest_loan)   // 如果有最新还未还款的数据,就取实际还款进度
        {

            $ck_status = $lastest_loan;
            switch ($status)
            {
                case 0: //
                    $timeline[] = '提交审核';
                    break;
                case 1:
                    $timeline[] = '提交审核';
                    if($ck_status)
                    {
                        $timeline[] = '审核通过，等待放款';
                    }
                    else
                    {
                        $timeline[] = '审核失败，'.$lastest_loan['refusereason'];
                    }
                    break;
                case 2:             // 已放款
                    $timeline[] = '提交审核';
                    $timeline[] = '审核通过，等待放款';
                    $timeline[] = '已放款，请按时还款';

                    $refundamount=0;
                    $days=0;
                    if($loan['status']==2){
                        $refundamount+=$loan['damount'];
                        if(strtotime($loan['deadline'])<strtotime(date("Y-m-d H:i:s"))){
                            $overdue=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
                            $fee=$overdue*$loan['damount']*$loan['overduefee']/100;
                            $refundamount+=$fee;
                            $days="已逾期".$overdue."天";
                        }else{
                            $days=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
                        }
                    }
                    if($loan['status']==4){
                        $refundamount=$loan['refundamount'];
                    }
                    $damount = $loan['damount'];
                    $delayrate=C('config.DELAY_CONFIG');

                    if(!$delayrate)
                    {
                        $delayrate = 0.3;
                    }

                    $delay_fee = $damount*$delayrate;
                    // $result['delay_fee'] = $delay_fee;


                    $result=array();
                    $result['damount']=$loan['damount'];
                    $result['interest']=$delay_fee;
                    $result['refundamount']=$refundamount;
                    if($loan['no'])
                    {
                        $coupon = M('coupon')->where(array('no'=>$loan['no']))->find();
                        if($coupon)
                        {
                            $result['refundamount'] = $refundamount - $coupon['amount'];
                            $result['refundamount'] = $result['refundamount']<0?0:$result['refundamount'];
                        }
                    }
                    $result['no'] = $loan['no'];
                    $result['days']=$days;
                    $result['delayconfig'] = $delayconfig;
                    $result['status']=$loan['status'];
                    $result['status1']=$loan['status1'];
                    $result['shenhestatus']=$loan['shenhestatus'];
                    $result['refusereason']=$loan['refusereason'];
                    $result['addtime']=$loan['addtime'];
                    $result['daozhang']=$loan['daozhang'];
                    $result['deadline']=$loan['deadline'];
                    $result['paiedtime']=$loan['paiedtime']?$loan['paiedtime']:"暂无";
                    $result['refundtime']=$loan['refundtime']?$loan['refundtime']:"暂无";
                    $result['info']=$html;
                case 3:
                    $timeline[] = '提交审核';
                    $timeline[] = '审核通过，等待放款';
                    $timeline[] = '已放款，请按时还款';
                    $timeline[] = '已延期，请按时还款';
                    break;
                case 4:
                    $timeline[] = '提交审核';
                    $timeline[] = '审核通过，等待放款';
                    $timeline[] = '已放款，请按时还款';
                    $timeline[] = '已预期，请按时还款';
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


    /**
     * @param array $filter 搜索条件
     * @param array $page   页数条件
     * @return array        订单列表
     */

    public function getLoans($filter=array(),$page=array()){

        if(!$page['order'])
            $page['a.order'] = 'create_time desc';
        $list = M('loan')
            ->alias('a')
            ->field('
                    a.id,				        -- 订单ID
                    a.orderno,				    -- 贷款订单号
                    a.memberid,				    -- 会员id
                    a.idcard,				    -- 身份证号
                    a.telephone,				-- 联系电话
                    a.productid,				-- 贷款产品id
                    a.productinfo,				-- 申请时产品信息
                    a.damount,				    -- 贷款金额
                    a.interest,				    -- 利息，金额
                    a.interestrate,				-- 利率，百分比
                    a.amount,				    -- 应还金额，本金+利息+逾期费
                    a.handcharge,				-- 手续费
                    a.days,				        -- 贷款天数
                    a.deadline,				    -- 贷款还款期限
                    a.status,				    -- 0-待审核，1-已审核，2-已放款，3-已逾期，4-已还款
                    a.overduefee,				-- 逾期费
                    a.refundtime,				-- 还款时间
                    a.refundinfo,				-- 还款信息
                    a.refundamount,				-- 还款总额
                    a.step,				        -- 贷款过程记录
                    a.addtime,				    -- 申请时间
                    a.paiedtime,				-- 放款时间
                    a.shenhestatus,				-- 0-未通过，1-已通过
                    a.refusereason,				-- 拒绝理由
                    a.no,				        -- 优惠劵号
                    a.discount,				    -- 折扣金额
                    a.url,				        -- 合同url
                    a.status1,				    -- 客户确认  -- 到账金额
                    a.daozhang				 
                ')
            ->where($filter)
            ->order($page['order'])
            ->limit($page['start'],$page['limit'])
            ->select();
        return $list;
    }


    /**
     * 获取单个订单
     * @param $data  订单数据
     */

    public function getLoan($filter)
    {
        if(!$filter)
            return false;
        return M('loan')
            ->alias('a')
            ->field('
                    a.id,				        -- 订单ID
                    a.orderno,				    -- 贷款订单号
                    a.memberid,				    -- 会员id
                    a.idcard,				    -- 身份证号
                    a.telephone,				-- 联系电话
                    a.productid,				-- 贷款产品id
                    a.productinfo,				-- 申请时产品信息
                    a.damount,				    -- 贷款金额
                    a.interest,				    -- 利息，金额
                    a.interestrate,				-- 利率，百分比
                    a.amount,				    -- 应还金额，本金+利息+逾期费
                    a.handcharge,				-- 手续费
                    a.days,				        -- 贷款天数
                    a.deadline,				    -- 贷款还款期限
                    a.status,				    -- 0-待审核，1-已审核，2-已放款，3-已逾期，4-已还款
                    a.overduefee,				-- 逾期费
                    a.refundtime,				-- 还款时间
                    a.refundinfo,				-- 还款信息
                    a.refundamount,				-- 还款总额
                    a.step,				        -- 贷款过程记录
                    a.addtime,				    -- 申请时间
                    a.paiedtime,				-- 放款时间
                    a.shenhestatus,				-- 0-未通过，1-已通过
                    a.refusereason,				-- 拒绝理由
                    a.no,				        -- 优惠劵号
                    a.discount,				    -- 折扣金额
                    a.url,				        -- 合同url
                    a.status1,				    -- 客户确认  -- 到账金额
                    a.daozhang       
                ')
            ->where($filter)
            ->find();
    }


    /**
     * 保存订单
     * @param $data  订单数据
     */

    public function saveLoan($data)
    {
        $loan_id = $data['loan_id'];
        $co = array();
        if($loan_id)
        {
            $co['id'] = $loan_id;
            $co['memberid'] = $data['memberid'];
            M('loan')->where($co)->save($data);
        }
        else
        {
            M('loan')->add($data);
        }

    }
}