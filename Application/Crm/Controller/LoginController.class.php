<?php

namespace Crm\Controller;

use Think\Controller;

class LoginController extends Controller {
	public function index() {
		$this->assign ( 'title', '用户登录' );
		$this->display ();
	}
	public function logout() {
		session ( '[destroy]' );
		redirect ( U ( 'Index/index' ) );
	}
	


	public function login($fromurl=''){



		$this->assign('fromurl',think_decrypt($fromurl));
		$this->assign('title','登录');
		$this->display();
	}


	public function sublogin(){
		$username=$_POST['username'];
		$userpwd=$_POST['userpwd'];
		$result=array();
		if(isN($username)){
			$result['status']=0;
			$result['info']='登录账号不能为空';
			$this->ajaxReturn($result);
		}
		if(isN($userpwd)){
			$result['status']=0;
			$result['info']='登录密码不能为空';
			$this->ajaxReturn($result);
		}
		$find=M('staff')->where(array('username'=>$username,'status'=>1))->find();
		if(!$find){
			$result['status']=0;
			$result['info']='登录账号不存在或被转为离职状态，请联系管理员';
			$this->ajaxReturn($result);
		}

		if(md5($userpwd)!=$find['userpwd']){
			$result['status']=0;
			$result['info']='密码不正确';
			$this->ajaxReturn($result);
		}

		session('manageid',$find['id']);
		session('managename',$find['username']);

		$result['status']=1;
		$result['info']='';
		$this->ajaxReturn($result);

	}


	/**
	 * 注册
	 */
	public function register(){


		$this->assign('title','注册');
		$this->display();
	}



	public function subregister(){
		if(IS_POST){

			$memberid=get_memberid();

			$data=$_POST;
			$result=array();
			if(!is_mobile($data['telephone'])){
				$result['status']=0;
				$result['info']="电话号码格式不正确";
				$this->ajaxReturn($result);
			}

			$act="提交注册";


//			dump($data);die;

			$add=M('member')->where(array('id'=>$memberid))->data($data)->save();
			if($add===false){
				$result['status']=0;
				$result['info']=$act."失败，请稍后再试";
				$this->ajaxReturn($result);
			}

			$result['status']=1;
			$result['info']=$act."成功";
			$this->ajaxReturn($result);

		}
	}

}