<?php

namespace Admin\Controller;

use Think\Controller;

class BaseController extends Controller {
	public function _initialize() {
		// 加入权限判断
		$this->checkLogin ();
	}
	private function checkLogin() {
		$this->autoLogin ();
		// 用户权限检查
		if (C ( 'USER_AUTH_ON' ) && ! in_array ( MODULE_NAME, explode ( ',', C ( 'NOT_AUTH_MODULE' ) ) )) {
			$Rbac = new \Org\Util\Rbac ();
			if (! $Rbac->AccessDecision ()) {
				// 检查认证识别号
				if (! session ( C ( 'USER_AUTH_KEY' ) )) {
					// 跳转到认证网关
					redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
				}
				
				// 提示错误信息
				$this->error ( "对不起，您的权限不足！" );
			}
		}
	}
	
	
	/**
	 * 自动登录
	 */
	private function autoLogin() {
		// 写登录cookie
		if (! session ( 'adminid' )) {
			$cookieu = C ( 'cookieusername' );
			$cookiep = C ( 'cookieuserpwd' );
			$username = cookie ( $cookieu );
			$username = think_decrypt ( $username );
			if ($username) {
				$userpwd = cookie ( $cookiep );
				$userpwd = think_decrypt ( $userpwd );
				$ctrl = A ( 'Login' );
				$ret=$ctrl->login ( $username, $userpwd, '111111' );
			}
		}
	}
}
?>