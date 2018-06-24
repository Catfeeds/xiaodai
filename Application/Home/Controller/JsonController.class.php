<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/17
 * Time: 13:49
 */

namespace Home\Controller;


use Think\Controller;

class JsonController extends Controller
{
    public function sel_user(){
        $result=array();
        $u_tel=$_POST['u_tel'];
        $time=date('Ymd');
        $tic=$_POST['tic'];


        $sign=md5($time.'livehztic');
        if($tic!=$sign){
            $result['status']=2;
            $result['errormsg']="tic签名不正确";
            $this->ajaxReturn($result);
        }

        if(isN($u_tel)){
            $result['status']=2;
            $result['errormsg']="手机号码为空";
            $this->ajaxReturn($result);
        }
        if(!is_mobile($u_tel)){
            $result['status']=2;
            $result['errormsg']="手机号码格式不正确";
            $this->ajaxReturn($result);
        }

        $find=M('member')->where(array('telephone'=>$u_tel))->find();
        if(!$find){
            $result['status']=0;
            $result['errormsg']="会员不存在";
        }else{
            $result['status']=1;
            $result['errormsg']="会员存在";
        }
        $this->ajaxReturn($result);
    }



    public function add_user(){
        $userinfo=$_POST;

        $result=array();
		
        $time=date('Ymd');
        $tic=$userinfo['tic'];

        $sign=md5($time.'livehztic');
		
		
        if($tic!=$sign){
            $result['status']=0;
            $result['errormsg']="tic签名不正确";
            $this->ajaxReturn($result);
        }

        if(isN($userinfo['u_name'])){
            $result['status']=0;
            $result['errormsg']="会员姓名为空";
            $this->ajaxReturn($result);
        }

        if(isN($userinfo['u_tel'])){
            $result['status']=0;
            $result['errormsg']="手机号码为空";
            $this->ajaxReturn($result);
        }
        if(!is_mobile($userinfo['u_tel'])){
            $result['status']=0;
            $result['errormsg']="手机号码格式不正确";
            $this->ajaxReturn($result);
        }

        if($userinfo['u_source']!=='zhibo'){
            $result['status']=0;
            $result['errormsg']="用户来源不正确";
            $this->ajaxReturn($result);
        }

        $find=M('member')->where(array('telephone'=>$userinfo['u_tel']))->find();
        if($find){
            $result['status']=0;
            $result['errormsg']="电话号码已使用";
            $this->ajaxReturn($result);
        }

        $dataadd=array();
        $dataadd['username']=$userinfo['u_name'];
        $dataadd['telephone']=$userinfo['u_tel'];
        $add=M('member')->data($dataadd)->add();
        if($add===false){
            $result['status']=0;
            $result['errormsg']="注册失败";
            $this->ajaxReturn($result);
        }
        $result['status']=1;
        $result['errormsg']="注册成功";
        $this->ajaxReturn($result);

    }

}