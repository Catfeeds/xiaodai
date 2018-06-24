<?php

namespace Crm\Controller;

use Think\Controller;

class AuthController extends Controller {
	
	/**
	 * 授权
	 *
	 * @param string $callback        	
	 * @param string $code        	
	 */
	public function index($callback = '', $code = '') {
		
		$mpid = get_mp_str ();
		$weChat = get_wechat_obj ( $mpid );
		
		if ($code) {
			$accessToken = $weChat->getOauthAccessToken ();
			if ($accessToken) {
				$openid = $accessToken ['openid'];
				$access_token = $accessToken ['access_token'];
				$user = $weChat->getOauthUserinfo ( $access_token, $openid );
				if (! $user ['errcode']) {

					openid($openid);
					redirect ( $callback );
				} else {
					$this->err ();
				}
			} else {
				$this->err ();
			}
		} else {
			$url = $weChat->getOauthRedirect ( get_current_url () );
			redirect ( $url );
		}
	}
	
	/**
	 * 出错提示
	 */
	public function err() {
		$this->show ( '信息获取失败！', 'utf-8' );
	}
}