<?php

namespace Home\Controller;

use Think\Controller;

class AuthbaseController extends Controller {
	public function _initialize() {

		$cartnum=count(session('cart_name')['cart_items']);
		$this->assign('cartnum',$cartnum);


		/*$openid = openid();
		if (! $openid) {
			$current_url = get_current_url();
			get_auth_openid( $current_url);
		} else{
			$memberid=M("member")->where(array('openid'=>$openid))->getField('id');
			if($memberid){
				session('memberid',$memberid);
			}else{
				$current_url = get_current_url();
				get_auth_openid( $current_url);
			}
		}*/

		/*if(!is_wechat_browser()){
			exit('wechat?');
		}*/

	   $this->checkLogin ();
	}


	private function checkLogin() {
		$memberid = get_memberid ();
         //var_dump($memberid);
		if (! $memberid) {
			redirect ( U ( 'Login/index' ) );
		}
	}
}