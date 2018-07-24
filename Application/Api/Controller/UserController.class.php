<?php
namespace Api\Controller;
use Common\Controller\IController;
use Think\Controller;

class UserController extends IController {




    
    /**
     * 用户注册
     * @param null $token 令牌
     * @param null $old_password   原密码
     * @param null $password 新密码
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function modify_password($token = null, $old_password = null, $password = null) {
        
        $User = D('Member');
        $resultByCheckToken = $User -> checkTokenAndEchoInfo($token);
        $uid = $resultByCheckToken['uid'];
        if($password == null) {
            $array['status'] = -1;
            $array['msg'] = "原密码或新密码不能为空";
        } else {
            $resultByMidifyPassword = $User -> modifyPassword($uid, $old_password, $password);
            if($resultByMidifyPassword) {
                $array['status'] = 0;
                $array['msg'] = "修改密码成功";
            } else {
                $array['status'] = -2;
                $array['msg'] = "原密码错误或原密码与新密码相同";
            }
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }


    //工作信息
    public function work()
    {

        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $work = json_decode($member['work'],true);
        $this->iSuccess($work,'data');
    }

    /**
     * 保存工作信息
     */
    public function savework()
    {
        $data['id'] = $this->member_id;         // 店铺ID
        $data['work']=json_encode($_POST,JSON_UNESCAPED_UNICODE);
        $data['gz']=1;
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了工作信息';

        D('member')->saveMember($data);

        $this->iSuccess('','data');
    }



    public function saveZmf(){
        $data['id'] = $this->member_id;         // 店铺ID
        $data['zmfinfo'] = IV('zmf','require');
        $data['zmf']=1;
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了芝麻信用';
        D('member')->saveMember($data);
        $this->iSuccess('','data');
    }

    /**
     *
     */
    public function zmfInfo()
    {
        $filter['id'] = $this->member_id;         // 店铺ID
        $zmfinfo = M ('member')->where ($filter['id'])->field('zmfinfo')->find ();
        $this->iSuccess($zmfinfo,'zmfinfo');
    }



    public function bank(){


        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $bankinfo= json_decode($member['bankinfo'],true);
        $this->iSuccess($bankinfo,'bankinfo');
    }

    public function savebankinfo(){
        if (!is_card($_POST['idcard'])) {
            IE("身份证格式不正确");
        }
        if (!is_number($_POST['bankno'])) {
            IE("银行卡号格式不正确");
        }
        if (!is_mobile($_POST['telephone'])) {
            IE("电话号码格式不正确");
        }
        $data['bankinfo'] = json_encode($_POST,JSON_UNESCAPED_UNICODE);
        $data['id'] = $this->member_id;
        $data['bank'] = 1;
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了银行卡信息';
        D('member')->saveMember($data);
        $this->iSuccess('','data');
    }



    public function tmobile(){

        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $tmobile= json_decode($member['tmobile'],true);
        $this->iSuccess(array('tmobile'=>$tmobile),'tmobile');
    }

    public function savetmobile(){
        $memberid=get_memberid();
        $telephone=$_POST['telephone'];
        $servicepwd=$_POST['servicepwd'];
        $step=$_POST['step'];
        $PatchCode=$_POST['PatchCode'];
        $taskNo=$_POST['taskno'];
        $smscode=$_POST['smscode'];
        $PatchCode=str_replace('[','',$PatchCode);
        $PatchCode=str_replace(']','',$PatchCode);
        $idcardno=$_POST['idcard'];
        $username=$_POST['username'];

        $member=M('member')->where(array('id'=>$memberid))->find();

        $baseurl="https://api.ibeesaas.com";
        $appkey="dcxadyh4kp";
        $ak="SHmSJHhvDaq5bY33Bpiz1mDOZHbwQQjp";
        $sk="ciNoa4SPUIMWVvq7k6y19HnbD4AYT9eO";
        $tv="v2";
        vendor("Zhengxin.TokenHelper");
        $tokenobj=new \TokenHelper($ak,$sk,$tv);

        $currenttime=strtotime(date("Y-m-d H:i:s"))+5*60;

        $method="POST";

        $tmobileinfo=json_decode($member['tmobileinfo'],true);
        $find=M('member_info')->where(array('memberid'=>$memberid))->find();

        if(!$find){
            $dataf=array();
            $dataf['memberid']=$memberid;
            M('member_info')->data($dataf)->add();
        }

        $result=json_decode($find['tmobileinfo'],true);
        if($result['code']=="general_0" && $result['taskStatus']=="success"){
            $return['status']=2;
            $return['info']="已授权成功";

        }
        else{

            $return=array();
            $taskno="";
            switch ($step){
                case "first":
                    $body=array();
                    $body['callbackUrl']=C('HTTP_SERVER')."Login/tmobilecallback.html";
                    $body['data']['loginType']=0;
                    //$body['data']['sync']=1;
                    $body['data']['account']=$telephone;
                    $body['data']['password']=$servicepwd;
                    $body=json_encode($body,JSON_UNESCAPED_UNICODE);
                    $body=str_replace("\\","",$body);

                    $urlpath="/daas/v1/tasks";
                    $querystring="appKey={$appkey}&taskType=carrier";
                    $url=$baseurl.$urlpath."?".$querystring;

                    $token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);

                    $header[] = "X-IbeeAuth-Token:{$token}";
                    $result = get_curl($url, $method, $body, $header);

                    //dump($result);
                    $result['gettime']=date("Y-m-d H:i:s");
                    $taskno=$result['taskNo'];
                    //$taskno="12fe252a49f643c883d430c3bf0dcc731524459231523";
                    //$result['taskStatus']="pending";

                    break;
                case "carrier_1":
                    $body=array();
                    $body['patchCode']=intval($PatchCode);
                    $body['data']['account']=$telephone;
                    $body['data']['password']=$servicepwd;
                    $body=json_encode($body,JSON_UNESCAPED_UNICODE);
                    $body=str_replace("\\","",$body);
                    //dump($body);die;

                    $urlpath="/daas/v1/tasks/".$taskNo;
                    $querystring="appKey={$appkey}";
                    $url=$baseurl.$urlpath."?".$querystring;

                    $token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
                    $header[] = "X-IbeeAuth-Token:{$token}";
                    $result = get_curl($url, $method, $body, $header);
                    $result['gettime']=date("Y-m-d H:i:s");
                    $taskno=$result['taskNo'];


                    break;
                case "carrier_4":
                case "carrier_5":
                case "carrier_6":
                case "carrier_10":
                case "carrier_11":
                    $body=array();
                    $body['patchCode']=intval($PatchCode);
                    //$body['data']['sync']=1;
                    $body['data']=$smscode;
                    $body=json_encode($body,JSON_UNESCAPED_UNICODE);
                    $body=str_replace("\\","",$body);
                    //dump($body);die;
                    $urlpath="/daas/v1/tasks/".$taskNo;
                    $querystring="appKey={$appkey}";
                    $url=$baseurl.$urlpath."?".$querystring;
                    $token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
                    $header[] = "X-IbeeAuth-Token:{$token}";
                    $result = get_curl($url, $method, $body, $header);
                    $result['gettime']=date("Y-m-d H:i:s");
                    $taskno=$taskNo;
                    break;
                case "carrier_16":
                case "carrier_17":
                    $body=array();
                    $body['patchCode']=intval($PatchCode);
                    $body['data']['idCardNo']=$idcardno;
                    $body['data']['username']=$username;
                    $body=json_encode($body,JSON_UNESCAPED_UNICODE);
                    $body=str_replace("\\","",$body);
                    $urlpath="/daas/v1/tasks/".$taskNo;
                    $querystring="appKey={$appkey}";
                    $url=$baseurl.$urlpath."?".$querystring;
                    $token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
                    $header[] = "X-IbeeAuth-Token:{$token}";
                    $result = get_curl($url, $method, $body, $header);
                    $result['gettime']=date("Y-m-d H:i:s");
                    $taskno=$taskNo;
                    break;
                default:
                    break;
            }

            $datarec=array();
            $datarec['memberid']=$memberid;
            $datarec['taskno']=$taskno;
            $datarec['telephone']=$telephone;
            $datarec['servicepwd']=$servicepwd;
            $exist=M('member_tmobile_record')->where(array('taskno'=>$taskno))->find();
            if(!$exist){
                M('member_tmobile_record')->data($datarec)->add();
            }
            if( $result['taskStatus']=="pending" || $result['taskStatus']=="processing"){
                //$info=array();
                //$info['telephone']=$telephone;
                //$info['servicepwd']=$servicepwd;
                //$info=json_encode($info,JSON_UNESCAPED_UNICODE);
                //$set=M('member')->where(array('id'=>$memberid))->setField(array('tmobileinfo'=>$info,'tmobile'=>1));
                $return['status']=1;
                $return['taskno']=$taskno;
            }else{
                $return['status']=0;
                $return['info']=$result['message'];
                $return['taskno']=$taskno;
            }


        }

        $this->ajaxReturn($return);

    }



    public function contact(){

        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $contactinfo= json_decode($member['contactinfo'],true);
        $this->iSuccess($contactinfo,'contactinfo');
    }


    public function savecontact(){
        $data['id']=$this->member_id;
        $info[] = array(
            'seq'   => 1,
            'username' => IV('username1','require'),
            'telephone' => IV('telephone1','require'),
            'relationship' => IV('relationship1','require'),
        );
        $info[] = array(
            'seq'   => 2,
            'username' => IV('username2','require'),
            'telephone' => IV('telephone2','require'),
            'relationship' => IV('relationship2','require'),
        );
        $data['contactinfo']=json_encode($info,JSON_UNESCAPED_UNICODE);
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['contacts'] = 1;
        $data['update_info'] = '更新了联系人';
        D('member')->saveMember($data);
        $this->iSuccess('','contactinfo');
    }


    /**
     * 修改个人资料
     */
    public function info()
    {
        $filter['id'] = $this->member_id;
        // 店铺ID
        $member = M ('member')
            ->field('
            username,           -- 用户名
            idcard,             -- 身份证号码  
            telephone,          -- 电话号码
            idcardimg1,         -- 身份证 正面
            idcardimg2,         -- 身份证 反面  -- 身份证 手持 拍照
            idcardimg3          
            ')
            ->where ($filter['id'])
            ->find ();
        $this->iSuccess($member,'member');

    }


    public function saveinfo()
    {
        $data=$_POST;
        $data['id']=$this->member_id;
        if(!is_card($data['idcard'])){
            IE('身份证号码格式不正确');
        }

        //var_dump($data);die;
        $data['cominfo']=1;
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了个人信息';
        D('member')->saveMember($data);
        $this->iSuccess('','info');

    }


    /**
     * 获取认证情况
     */
    public function authInfo()
    {
        $filter['id']=$this->member_id;
        $authInfo = M('member')
            ->field('
            cominfo,            -- 个人信息
            gz,                 -- 工作信息
            bank,               -- 银行卡信息
            tmobile,            -- 运营商信息
            contacts,           -- 紧急联系人        -- 芝麻信息
            zmf
            ')
            ->where($filter)
            ->find();
        $this->iSuccess($authInfo,'data');
    }


    //个人中心
    public function home(){
        $filter['id']=$this->member_id;
        $data = M('member')->where($filter['id'])->find();
        $this->iSuccess($data,'data');
    }


    //延期
    public function delay()
    {
        $data['memberid']=$this->member_id;
        $data['orderno'] = IV('orderno','require');
        $row = M('loan')->where(['orderno'=>$data['orderno']])->find();

        $data['addtime'] = date('Y-m-d H:i:s');
        //未支付
        $data['status'] = 0;
        $data['money'] =  $row['interest'];

        $data['days'] = 3;
        $data['dealno'] ='H-'.get_order_no();
        $rs = M('delay')->add($data);
        $result=[];
        if($rs){
            $result['status']=1;
            $result['info'] = $data['dealno'];
        }else{
            $result['status']=0;
            $result['info'] = '获取订单失败，请稍后在试';
        }
        $this->ajaxReturn($result);

    }

}