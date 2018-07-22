<?php
namespace Common\Controller;

use Think\Controller;
use Common\Lib\Verification;
use Common\Lib\FormatUtils;

class IController extends Controller {

	public function iSuccess($data,$data_key) {

		if($data_key){
			$data=array("$data_key"=>$data);
		}

		$ret=array(
				'data' => $data,
				'status' => 1
		);

		if(!$data){
			unset($ret['data']);
		}
		
		return $this->ajaxReturn($ret);
	}


	// 校验 token 
	public function checkToken($token){

		$co=array(
			'token'=>$token,
		);
		$it = M('user_token')->field('user_id')->where($co)->find();
		if($it){
			return $it['user_id'];
		}else{
		 	IE(ERROR_USER_UNVALID_TOKEN);
		}
	}
}