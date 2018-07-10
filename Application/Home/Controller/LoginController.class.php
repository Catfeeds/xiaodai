<?php

namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller {
	public function index() {
		$this->assign ( 'title', '用户登录' );
		$this->display ();
	}
	//登录
	public function login(){
        $memberid = get_memberid ();
        if ($memberid) {
            //redirect(U('Index/menu'));
        }
		$data = $_POST;
		$result = [];
		if (! is_mobile ( $data['telephone'] )) {
			$result['status']=0;
			$result['info']="输入的电话号码格式不正确，请重试！！";
			$this->ajaxReturn($result);
		}
		//手机号
		$row = M('member')->where(['telephone'=>$data['telephone']])->find();
		if(!$row){
			$result['status']=1;
			$result['info']="手机号或密码不正确";
			$this->ajaxReturn($result);
		}else{
			//账号正确，判断账号密码匹配
			$where['telephone']= $data['telephone'];
			$where['userpwd']=md5($data['userpwd']);
			$result = M('member')->where($where)->find();
			if(!$result){
				$result['status']=1;
				$result['info']="手机号或密码不正确";
				$this->ajaxReturn($result);
				//成功
			}else{
				session('memberid',$result['id']);
				$result['status']=2;
				$result['info']="登录成功";
				$this->ajaxReturn($result);
			}
		}


	}
	//退出
	public function logout() {
		session ( '[destroy]' );
		redirect ( U ( 'Index/index' ) );
	}
	
	/**
	 * 加入或取消收藏
	 * 
	 * @param number $productid        	
	 */
	public function fav($productid = 0) {
		$guid = get_memberid ();
		if (! $guid) {
			$this->error ( '对不起，请登录后操作！' );
		}
		$params = array ();
		$params ['guid'] = $guid;
		$params ['productid'] = $productid;
		$result = api ( 'Member/fav', $params );
		if ($result ['err_code']) {
			$this->error ( $result ['err_msg'] );
		} else {
			$id = $result ['err_msg'];
			if ($id) {
				$this->success ( '恭喜，收藏成功！' );
			} else {
				$this->success ( '恭喜，收藏已取消！' );
			}
		}
	}
	
	/**
	 * 绑定
	 * 
	 * @param string $_openid        	
	 */
	public function bind($_openid = '') {

		if(!$_openid)
			get_auth_openid ( get_current_url () );

		$tempuser = session ( 'user_temp' );
		$memberid = get_memberid ();
		if ($memberid) {
			redirect ( U ( 'Member/index' ) );
		} else {
			if ($tempuser) {
				$openid = $tempuser ['openid'];
				if ($openid) {
					$member=M('member')->where(array('openid'=>$openid))->find();
					if(!$member){
						U_Subscribe ( $openid );
					}
					session ( 'memberid',$member['id'] );
					redirect ( U ( 'Index/index' ) );
				} else {
					get_auth_openid ( get_current_url () );
				}
			} else {
				get_auth_openid ( get_current_url () );
			}
		}
	}
	public function unbind() {
		$id = get_memberid ();
		$where = array ();
		$where ['id'] = $id;
		$unbind = M ( 'member' )->where ( $where )->setField ( 'status', 0 );
		if ($unbind) {
			clr_cache ();
			relogin ();
			$this->success ( '恭喜，解绑成功！' );
		} else {
			$this->error ( '对不起，解绑失败！' );
		}
	}


	/**
	 * 注册
	 */
	public function register(){

		$this->assign('title','注册');
		$this->display();
	}
	public function getsmsverify( $telephone = '',$type='') {

		if (! is_mobile ( $telephone )) {
			$result['status']=0;
			$result['info']="输入的电话号码格式不正确，请重试！！";
			$this->ajaxReturn($result);
			//err ( 3004 );
		}
		$memberid=get_memberid();
		if($type==1){
			$exist=M('member')->where(array('telephone'=>$telephone))->find();
			if($exist){
				$result['status']=0;
				$result['info']="该手机号码已经绑定，请更换手机号再试";
				$this->ajaxReturn($result);
			}
		}

		$tblname = 'sms';
		$where = array ();
		$where ['telephone'] = $telephone;
		$where ['type'] = $type;
		$where ['status'] = 0;
		$time = NOW_TIME - 60 * 1;
		$where ['addtime'] = array (
				'gt',
				time_format ( $time )
		);
		$find = M ( $tblname )->where ( $where )->order ( 'id desc' )->find ();
		if ($find) {
			$result['status']=0;
			$result['info']="请勿重复发送短信";
			$this->ajaxReturn($result);
			//err ( 3008, '请稍候重试' );
		} else {
			// 随机6位数字码
			$code = rand_str ( 6, 1 );
//			$send = api_send_sms ( $type, $telephone, $code );
			$send=send_sms($telephone,$code);
			//$send =1;
			if ($send) {
				$data = array ();
				$data ['telephone'] = $telephone;
				$data ['type'] = $type;
				$data ['status'] = 0;
				$data ['code'] = $code;
				$add = M ( $tblname )->data ( $data )->add ();
				if ($add) {
					$result['status']=1;
					$result['info']="验证码已发送，请在10分钟内输入验证码，否则需要重新发送验证码！";
					$this->ajaxReturn($result);
					//ret ( $code );
				} else {
					$result['status']=0;
					$result['info']="发送短信失败，请重试！！";
					$this->ajaxReturn($result);
				}
			} else {
				$result['status']=0;
				$result['info']="发送短信失败，请重试！！";
				$this->ajaxReturn($result);
			}
		}

	}

	public function verifySms($type = '', $mobile = '', $code = '') {
		$tblname = 'sms';
		$where = array ();
		$where ['mobile'] = $mobile;
		$where ['type'] = $type;
		$where ['code'] = $code;
		$where ['status'] = 0;
		$time = NOW_TIME - 60 * 10;
		$where ['addtime'] = array (
				'gt',
				time_format ( $time )
		);
		$find = M ( $tblname )->where ( $where )->order ( 'id desc' )->find ();
		$result=array();
		if ($find) {
			$data = array ();
			$data ['verifytime'] = time_format ();
			$data ['status'] = 1;
			M ( $tblname )->where ( $where )->data ( $data )->save ();
			$result['status']=1;
			$result['info']="短信验证码正确";
			return $result;
		} else {
			$result['status']=0;
			$result['info']="验证码不正确或已失效请重新获取！";
			return $result;
		}
	}

	public function ajaxregister(){
       if(IS_POST){
		   $data=$_POST;
		   $result=array();
		   if($data['registercode']){
              $row = M('member')->where(['registercode'=>$data['registercode']])->find();
			   if(!$row){
				   $result['status']=0;
				   $result['info']="注册码不存在";
				   $this->ajaxReturn($result);
			   }

		   }
		   if(!is_mobile($data['telephone'])){
			   $result['status']=0;
			   $result['info']="电话号码格式不正确";
			   $this->ajaxReturn($result);
		   }

		   if($data['userpwd']!==$data['repassword']){
			   $result['status']=1;
			   $result['info']="两次密码必须一致";
			   $this->ajaxReturn($result);
		   }


		   $data['userpwd']=md5($data['userpwd']);


		   $verify=$this->verifySms(1,$data['telephone'],$data['varf']);
		   if($verify['status']==0){
			   $this->ajaxReturn($verify);
		   }

		   $add=M('member')->data($data)->add();

		   if($add===false){
			   $result['status']=3;
			   $result['info']="注册失败，请稍后再试";
			   $this->ajaxReturn($result);
		   }

		   $result['status']=4;
           session('memberid',$add);
		   $result['info']="注册成功";
		   $this->ajaxReturn($result);
	   }

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

	public function tmobilecallback(){
		$data = file_get_contents('php://input');
		$res=json_decode($data,true);
		$taskno=$res['taskNo'];
		$taskstatus=$res['taskStatus'];
		$datas=array();
		$datas['taskno']=$taskno;
		$datas['datadetail']=$data;
		$set=M('member_tmobile_data')->data($datas)->add();

		if($taskstatus=='success'){
			$taskrec=M('member_tmobile_record')->where(array('taskno'=>$taskno))->find();
			if($taskrec){
				$info=array();
				$info['telephone']=$taskrec['telephone'];
				$info['servicepwd']=$taskrec['servicepwd'];
				$info=json_encode($info,JSON_UNESCAPED_UNICODE);
                $set=M('member')->where(array('id'=>$taskrec['memberid']))->setField(array('tmobileinfo'=>$info,'tmobile'=>1,'update_time'=>date('Y-m-d H:i:s'),'update_info'=>'更新了运营商信息'));
				$result=$data;
				$find=M('member_info')->where(array('memberid'=>$taskrec['memberid']))->find();
				if($find){
					M('member_info')->where(array('memberid'=>$taskrec['memberid']))->setField('tmobileinfo',$result);
				}else{
					M('member_info')->data(array('memberid'=>$taskrec['memberid'],'tmobileinfo'=>$result))->add();
				}
			}
		}

	}
	public function zfbcallurl(){
		$data = file_get_contents('php://input');
		$res=json_decode($data,true);
		
		$taskno=$res['taskNo'];
		$taskstatus=$res['taskStatus'];
	
		
		$datas=array();
		$datas['taskno']=$taskno;
		$datas['data']=$data;
	    $datas['addtime']=date('Y-m-d H:i:s');
		$set=M('member_zfb')->data($datas)->add();
		if($taskstatus=='success'){
			$taskrec=M('member_zfb_record')->where(array('taskno'=>$taskno))->find();
			if($taskrec){
				$set=M('member')->where(array('id'=>$taskrec['memberid']))->setField(array('zfb'=>1));
				$result=$data;
				$find=M('member_info')->where(array('memberid'=>$taskrec['memberid']))->find();
				if($find){
					M('member_info')->where(array('memberid'=>$taskrec['memberid']))->setField('alipay',$result);
				}else{
					M('member_info')->data(array('memberid'=>$taskrec['memberid'],'alipay'=>$result))->add();
				}
			}
		}

	}
	public function taobaocallurl(){
		$data = file_get_contents('php://input');
		$res=json_decode($data,true);
		
		$taskno=$res['taskNo'];
		$taskstatus=$res['taskStatus'];
	
		
		$datas=array();
		$datas['taskno']=$taskno;
		$datas['data']=$data;
		$datas['addtime']=date('Y-m-d H:i:s');
	 
		$set=M('member_taobao')->data($datas)->add();
		if($taskstatus=='success'){
			$taskrec=M('member_taobao_record')->where(array('taskno'=>$taskno))->find();
			if($taskrec){
				$set=M('member')->where(array('id'=>$taskrec['memberid']))->setField(array('taobao'=>1));
				$result=$data;
				$find=M('member_info')->where(array('memberid'=>$taskrec['memberid']))->find();
				if($find){
					M('member_info')->where(array('memberid'=>$taskrec['memberid']))->setField('taobao',$result);
				}else{
					M('member_info')->data(array('memberid'=>$taskrec['memberid'],'taobao'=>$result))->add();
				}
			}
		}

	}

	public function duotoucallurl(){
		$data = file_get_contents('php://input');
		$res=json_decode($data,true);

		$taskno=$res['taskNo'];
		$taskstatus=$res['taskStatus'];


		$datas=array();
		$datas['taskno']=$taskno;
		$datas['data']=$data;
		$datas['addtime']=date('Y-m-d H:i:s');

		$set=M('member_duotou')->data($datas)->add();
		if($taskstatus=='success'){
			$taskrec=M('member_duotou_record')->where(array('taskno'=>$taskno))->find();
			if($taskrec){
				$result=$data;
				$find=M('member_info')->where(array('memberid'=>$taskrec['memberid']))->find();
				if($find){
					M('member_info')->where(array('memberid'=>$taskrec['memberid']))->setField('bull',$result);
				}else{
					M('member_info')->data(array('memberid'=>$taskrec['memberid'],'taobao'=>$result))->add();
				}
			}
		}

	}

	public function ajaxforget(){
		if(IS_POST){
			$data=$_POST;
			$data['userpwd']=md5($_POST['userpwd']);
			$result=array();

			if($data['userpwd']!==md5($data['repassword'])){
				$result['status']=3;
				$result['info']="两次密码必须一致";
				$this->ajaxReturn($result);
			}


			if(!is_mobile($data['telephone'])){
				$result['status']=2;
				$result['info']="电话号码格式不正确";
				$this->ajaxReturn($result);
			}

//			dump($data);die;
			$add=M('member')->where(array('telephone'=>$data['telephone']))->data($data)->save();
			if($add===false){
				$result['status']=0;
				$result['info']="失败，请稍后再试";
				$this->ajaxReturn($result);
			}

			$result['status']=1;
			$result['info']="修改成功";
			$this->ajaxReturn($result);

		}
	}
	public function forget(){
		$this->assign('title','修改');
		$this->display();
	}



	

}