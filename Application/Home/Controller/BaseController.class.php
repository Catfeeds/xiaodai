<?php

namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller {
	public function _initialize() {


//		if(CONTROLLER_NAME!=='Settle')
//			$this->checkLogin ();
		$cartnum=count(session('cart_name')['cart_items']);
		$this->assign('cartnum',$cartnum);

		//session('memberid',1);
		
			/*$openid = openid();
			if (!$openid) {
				$current_url = get_current_url();
				get_auth_openid($current_url);
			} else {
				$memberid = M("member")->where(array('openid' => $openid))->getField('id');
				if ($memberid) {
					session('memberid', $memberid);
				} else {
					$current_url = get_current_url();
					get_auth_openid($current_url);
				}
            
			}*/
            
//			if (!is_wechat_browser()) {
//				exit('wechat?');
//			}
//

          $this->checkLogin();
	}

	private function checkLogin() {
		$memberid = get_memberid ();

		if (! $memberid) {
			redirect ( U ( 'Login/index' ) );
		}
	}
}