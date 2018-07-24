<?php
namespace Api\Controller;

/**
 * 用户相关接口
 */
use Common\Controller\BaseController;
use Common\Controller\IController;
use Common\Lib\DateUtils;
use Think\Controller;


/**
 * User登录
 */
class LoanController extends IController {

    /**
     *  获取订单列表
     */
    public function lists()
    {
        $filter['a.memberid'] = $this->member_id;
        $page = initPage('a.addtime desc');  // 初始化分页

        /** 获取订单列表  */
        $loan_list = D('Loan')->getLoans($filter,$page);

        $this->iSuccess($loan_list,'loan_list');
    }


    /**
     * 获取单个订单信息
     */
    public function get()
    {
        $filter['a.memberid'] = $this->member_id;
        $filter['a.id'] = IV('loan_id','require');
        $loan = D('Loan')->getLoan($filter);
        $this->iSuccess($loan,'loan');
    }

    /**
     *申请额度接口
     */
    public function applyLoan()
    {
        $productid=3;
        //$amount=$_POST['amount'];
        $days=IV('days','require');
        $memberid=$this->member_id;
        $member=M('member')->where(array('id'=>$memberid))->find();
        $result=array();
        $product=M('loan_product')->where(array('id'=>$productid))->find();
        $result=array();

        /*if($amount<$product['limitstart']){
            $result['status']=0;
            $result['info']="贷款金额不能小于最低限额";
            $this->ajaxReturn($result);
        }

        if($amount>$product['limitend']){
            $result['status']=0;
            $result['info']="贷款金额不能大于最高限额";
            $this->ajaxReturn($result);
        }*/

        $data=array();
        $step=array();
        $step[0]['addtime']=date("Y-m-d H:i:s");
        $step[0]['act']="提交贷款申请";
        $step=json_encode($step,JSON_UNESCAPED_UNICODE);
        $orderno="D-".get_order_no();
        $data['orderno']=$orderno;
        $data['memberid']=$memberid;
        $data['idcard']=$member['idcard'];
        $data['telephone']=$member['telephone'];
        $data['productid']=$productid;
        $data['productinfo']=json_encode($product,JSON_UNESCAPED_UNICODE);
        //$data['damount']=$amount;
        //$data['interestrate']=$product['interest'];
        //$data['interest']=$product['interest']*$amount/100;
        //$data['amount']=$amount+$data['interest'];
        $data['days']=$days;
        $deadline = date('Y-m-d',strtotime('+ '.($days-1).' days')).' 23:59:59';
        $data['deadline']=get_date_add(strtotime($deadline));
        $data['step']=$step;
        $data['overduefee']=$product['overdue'];
        $find=M('loan')->where(array('memberid'=>$memberid,'status'=>array('lt',4)))->find();
        if($find){
            IE('您还有贷款未还款，请还款后再申请贷款');
        }
        $add=M('loan')->data($data)->add();
        if($add===false){
            IE('申请失败，请稍后再试');
        }
        $result['info']="申请成功,请等待审核";
        $this->iSuccess($result,'data');
    }



    /**
     * 用户注册
     * @param null $username 用户名
     * @param null $email   邮箱
     * @param null $password 密码
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function register() {

        $password=IV('password','require');
        $repassword=IV('repassword','require');
        $data['telephone'] = IV('telephone','require');
        $varf=IV('varf','require');
        if($password != $repassword)
        {
            IE('两次密码不一样！');
        }
        $verify=D('Member')->verifySms(1,$data['telephone'],$varf);
        if(!$verify){
            IE('验证码不正确或已失效请重新获取');
        }
        $data['password'] = $password;
        $data['token'] = D('Member')->genToken();
        D('Member')->save($data);
        $this->iSuccess($data,'data');
    }


    /**
     * 获取验证码
     * @param string $telephone
     * @param string $type
     */
    public function getSmsVerify() {
        $where['telephone'] = IV('telephone','require');
        $type = IV('type','require');

        if($type==1){
            $member = D('Member')->getOneUser($where);
            if($member){
                IE("该手机号码已经绑定，请更换手机号再试");
            }
        }
        $tblname = 'sms';
        $where ['type'] = $type;
        $where ['status'] = 0;
        $time = NOW_TIME - 60 * 1;
        $where ['addtime'] = array (
            'gt',
            time_format ( $time )
        );
        $find = M ( $tblname )->where ( $where )->order ( 'id desc' )->find ();
        if ($find) {
            IE("请勿重复发送短信");
        } else {
            // 随机6位数字码
            $code = rand_str ( 6, 1 );
//			$send = api_send_sms ( $type, $telephone, $code );
            $telephone = $where['telephone'];
            $send=send_sms($telephone,$code);
            //$send =1;
            if ($send) {
                $data = array ();
                $data ['telephone'] = $telephone;
                $data ['type'] = $type;
                $data ['status'] = 0;
                $data ['code'] = $code;
                $add = M ( $tblname )->data ( $data )->add ();
                if (!$add) {
                    IE("发送短信失败，请重试！！");
                }
            } else {
                IE("发送短信失败，请重试！！");
            }
        }
        $this->iSuccess('','member');

    }


    /**
     * 保存订单
     */
    public function  save()
    {
        $data['shop_id'] = IV('token','require|token');
        $data['coupon_id'] = IV('coupon_id');
        $data['route'] = IV('route','require');
        $data['start_time'] = strtotime(IV('start_time','require'));
        $data['end_time'] = strtotime(IV('end_time','require'));
        $data['type'] = 1;
        D('Coupon')->saveCoupon($data);
        $this->iSuccess('','info');
    }



}