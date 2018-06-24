<?php

namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller {
	public function index() {
		$this->assign ( "title", C ( 'config.WEB_SITE_TITLE' ) );
		$this->display ( );
	}
	/**
	 * 退出登录
	 */
	public function logout() {
		session_unset ();
		session_destroy ();
		session ( '[destroy]' );
		$cookieu = C ( 'cookieusername' );
		$cookiep = C ( 'cookieuserpwd' );
		cookie($cookieu,null);
		cookie($cookiep,null);
		$this->redirect ( 'Login/index' );
	}
	// 验证码生成
	public function verify() {
		return get_verify ();
	}
	/**
	 * 清除缓存
	 */
	public function clrcache() {
		// 1. 清除runtime文件夹
		if (clr_cache ()) {
			$this->ajaxReturn ( Array (
					'status' => 1 
			) );
		} else {
			$this->ajaxReturn ( Array (
					'status' => 0 
			) );
		}
	}
	/**
	 * 登录验证
	 *
	 * @param string $username        	
	 * @param string $userpwd        	
	 * @param string $verify        	
	 */
	public function login($username = null, $userpwd = null, $verify = null) {
		// 保存登录信息
		// if ($verify == '111111') {
		// } else {
		// 	if (IS_POST) {
		// 		if (! check_verify ( $verify )) {
		// 			$this->error ( '验证码错误！' );
		// 		}
		// 	} else {
		// 		redirect ( U ( "Login/index" ) );
		// 	}
		// }
		
		$Rbac = new \Org\Util\Rbac ();
		
		$map ['username'] = $username;
		$map ['userpwd'] = md5 ( $userpwd );
		$authInfo = $Rbac->authenticate ( $map );
		
		if (! $authInfo ['id']) {
			$this->error ( '用户名或密码错误！', U ( "Admin/Login/index" ) );
			// redirect ( U ( "/Admin/Login" ) );
		}
		session ( C ( 'USER_AUTH_KEY' ), $authInfo ['id'] );
		session ( 'adminid', $authInfo ['id'] ); // 用户ID
		session ( 'adminname', $authInfo ['username'] ); // 角色ID
		
		$roleid = M ( 'role_user' )->where ( 'user_id=' . $authInfo ['id'] )->getField ( 'role_id' );
		$rolename = M ( 'role' )->where ( array (
				'id' => $roleid 
		) )->getField ( 'name' );
		session ( 'roleid', $roleid ); // 角色ID
		session ( 'rolename', $rolename ); // 角色ID
		                                   
		// 取细分权限
		$where1 = array ();
		$where1 ['id'] = $roleid;
		$db = M ( 'role' )->field ( 'channel,shop' )->where ( $where1 )->find ();
		$channel = $db ['channel'];
		$shop = $db ['shop'];
		session ( 'channel_role', $channel ); // 栏目权限
		session ( 'shop_role', $shop ); // 门店权限
		
		$data = array ();
		$data ['id'] = $authInfo ['id'];
		$data ['lastlogtime'] = time_format ();
		$data ['lastlogip'] = get_client_ip ();
		$data ['logtimes'] = $authInfo ['logtimes'] + 1;
		M ( 'user' )->save ( $data );
		
		// 检查超级管理员
		if ($authInfo ['username'] == C ( "ADMIN_AUTH_KEY" )) {
			session ( C ( 'ADMIN_AUTH_KEY' ), true );
			session ( 'channel_role', null );
			session ( 'shop_role', null );
		}
		// 缓存访问权限
		$Rbac->saveAccessList ();
		
		// 写登录cookie
		$cookieu = C ( 'cookieusername' );
		$cookiep = C ( 'cookieuserpwd' );
		cookie ( $cookieu, think_encrypt ( $username ), array (
				'expire' => 24 * 3600 
		) );
		cookie ( $cookiep, think_encrypt ( $userpwd ), array (
				'expire' => 24 * 3600 
		) );
		
		// redirect ( U ( "Index/index" ) );
		if ($verify == '111111') {
		}else{
			$this->success ( '恭喜，登录成功！' );
		}
	}
	public function mail($to = null, $subject = null, $body = null) {
		return send_mail ( $to, $subject, $body, false );
	}
	
	/**
	 * 省市县联动
	 *
	 * @param string $tbl        	
	 * @param number $id        	
	 */
	public function getArea($tbl = 'china_province', $id = null) {
		$html = '';
		$html = get_area ( $tbl, $id );
		echo $html;
	}
}
?>