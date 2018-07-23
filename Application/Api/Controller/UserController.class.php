<?php
namespace Api\Controller;
use Common\Controller\IController;
use Think\Controller;

class UserController extends IController {

    /**
     * 用户登录验证并返回
     * @param null $email 邮箱
     * @param null $password 密码
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function login() {
        $telephone = IV('telephone','require');
        $password=IV('password','require');

        $user = D('Member')->getUserByUsernameAndPass($telephone,$password);
        if(!$user) {
            IE("手机号或密码错误");
        }
        $this->iSuccess($user,'member');
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

        $this->iSuccess($user_info,'member');
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
    public function subwork()
    {
        $data['id'] = $this->member_id;         // 店铺ID
        $data['work']=json_encode($_POST,JSON_UNESCAPED_UNICODE);
        $data['gz']=1;
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了工作信息';
        D('member')->saveMember($data);

        $this->iSuccess('','data');
    }



    public function subzmf(){
        $data['id'] = $this->member_id;         // 店铺ID
        $data['zmfinfo'] = $_POST['zmf'];
        $data['zmf']=1;
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了芝麻信用';
        D('member')->saveMember($data);
        $this->iSuccess('','data');
    }

    /**
     *
     */
    public function zmf()
    {

        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $zmfinfo= json_decode($member['zmfinfo'],true);
        $this->iSuccess($zmfinfo,'zmfinfo');
    }



    public function bank(){


        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $bankinfo= json_decode($member['bankinfo'],true);
        $this->iSuccess($bankinfo,'bankinfo');
    }

    public function subbankinfo(){
        $data['username'] = $_POST['username'];
        $data['telephone'] = $_POST['telephone'];
        $data['idcard'] = $_POST['idcard'];
        $data['bankno'] = $_POST['bankno'];
        $data['name'] = $_POST['name'];

        if (!is_card($data['idcard'])) {
            $result['status'] = 0;
            $result['info'] = "身份证格式不正确";
            $this->ajaxReturn($result);
        }
        if (!is_number($data['bankno'])) {
            $result['status'] = 5;
            $result['info'] = "银行卡号格式不正确";
            $this->ajaxReturn($result);
        }
        if (!is_mobile($data['telephone'])) {
            $result['status'] = 1;
            $result['info'] = "电话号码格式不正确";
            $this->ajaxReturn($result);
        }
        $row['bankinfo'] = json_encode($data,JSON_UNESCAPED_UNICODE);
        $row['id'] = $this->member_id;
        $row['bank'] = 1;
        $row['update_time'] = date('Y-m-d H:i:s');
        $row['update_info'] = '更新了银行卡信息';
        D('member')->saveMember($row);
        $this->iSuccess('','data');
    }



    public function tmobile(){

        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $tmobile= json_decode($member['tmobile'],true);
        $this->iSuccess(array('tmobile'=>$tmobile),'tmobile');
    }

    public function subtmobile(){
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


    public function subcontact(){
        $seq=explode(',',$_POST['seq']);
        $seq=array_filter($seq);
        $relationship=explode(',',$_POST['relationship']);
        $relationship=array_filter($relationship);
        $username=explode(',',$_POST['username']);
        $username=array_filter($username);
        $telephone=explode(',',$_POST['telephone']);
        $telephone=array_filter($telephone);
        $info=array();
        foreach($seq as $k=>$v){

            $info[$k]['seq']=$v;
            $info[$k]['relationship']=$relationship[$k];
            $info[$k]['username']=$username[$k];
            $info[$k]['telephone']=$telephone[$k];
        }

        $info=json_encode($info,JSON_UNESCAPED_UNICODE);

        $data['id']=$this->member_id;
        $data = array('contactinfo'=>$info,'contacts'=>1);
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了联系人';
        D('member')->saveMember($data);
        $this->iSuccess('','contactinfo');

    }


    /**
     * 修改个人资料
     */
    public function info()
    {
        $filter['id'] = $this->member_id;         // 店铺ID
        $member = M ('member')->where ($filter['id'])->find ();
        $this->iSuccess($member,'member');

    }


    public function subinfo()
    {
        $filter['member_id']=$this->member_id;
        $data=$_POST;
        if(!is_card($data['idcard'])){
            IE('身份证号码格式不正确');
        }

        //var_dump($data);die;
        $data['cominfo']=1;
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_info'] = '更新了个人信息';
        M( 'member' )->where ( $filter)->save ($data);
        $this->iSuccess('','info');

    }

}