<?php

namespace Home\Controller;

class SettleController extends AuthbaseController {
	public function index() {
	}
	
	/**
	 * 购物车列表
	 */
	public function cart() {


		$cart = session ( 'cart_name' );

		$items=$cart['cart_items'];
		sort($cart['cart_items']);
		$this->assign('cart',$cart);


		foreach($items as $key=>$val){
			$items[$key]['attrs']=json_decode($val['attrs'],true);
		}

		$this->assign('cart_items',$items);
//dump($items);die;


		$title = '购物车';
		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 删除购物车商品
	 */
	public function deletecart(){
		if($_POST){
			$cart = session ( 'cart_name' );
			$items=$cart['cart_items'];
			if($_POST['id']){
				$del=$_POST['id'].'_';
				unset($items[$del]);
				$cart['cart_items']=$items;
				session ( 'cart_name' ,$cart);
				$result=array();
				$result['status']=1;
				$result['info']='删除成功！';
			}else{
				$result=array();
				$result['status']=0;
				$result['info']='请选择要删除的商品！';
			}
			$this->ajaxReturn($result);
		}
	}

	
	/**
	 * 确认订单
	 */
	public function confirm($ids = '') {
		if (IS_POST) {


			$data = $_POST;
			if (isN ( $data ['addressid'] )) {
				$this->error ( '对不起，请选择收货地址！' );
			}
			$memberid = get_memberid ();
			$data ['memberid'] = $memberid;
			// 取收货地址
			$address = M ( 'address' )->where ( array (
					'id' => $data ['addressid'],
					'memberid' => $memberid 
			) )->find ();
			if(!$address){
				$this->error('对不起，请重新选择收货地址！');
			}
			unset ( $address ['id'], $address ['addtime'], $address ['isdefault'] );
			$data=array_merge ( $data, $address );
			

			$cart=session('cart_name');
			foreach ($cart['cart_items'] as $k=>$v){
				$items[$v['id']]=$v['num'];
			}


			$arr=explode(',',$data['ids']);

			$items=$cart['cart_items'];
			foreach($items as $key=>$val){
				if(in_array(str_replace('_','',$key),$arr)){
					$item[$val['id']]['num']=$val['num'];
					$item[$val['id']]['attrs']=$val['attrs'];
				}else{
					unset($items[$key]);
				}
			}
			$add=add_order($memberid,$data,$item);

			if($add['status']==1){

				foreach($cart['cart_items'] as $key=>$val){
					if(in_array(str_replace('_','',$key),$arr)){
						unset($cart['cart_items'][$key]);
					}
				}
				//清购物车
				session ( 'cart_name.cart_items', $cart['cart_items'] );
				//消券
				$orderno=$add['info'];

				$this->success($orderno);

			}else{
				$this->error($add['info']);
			}
		} else {

			$memberid=get_memberid();
			// 获取收货地址列表

			$addresslist = get_address ();

			if (!$addresslist) {
				redirect ( U ( 'Member/editAddress', 'fromurl=' . think_encrypt ( get_current_url () ) ) );
			} else {
				$addresslist [0] ['isdefault'] = 1;
			}
			$this->assign ( 'addresslist', $addresslist );
			
			$where = array ();
			$where ['memberid'] = get_memberid ();
			if ($ids) {
				$arr=explode(',',$ids);
			}else{
				$this->error('请选择要结算的商品');
			}
			
			//取购物车
			$cartlist = session('cart_name');
			if(!$cartlist['cart_num']){
				redirect(U('Index/index'));
			}

			$items=$cartlist['cart_items'];
			$i=0;
			$allprice=0;

			

			foreach($items as $key=>$val){
				if(!in_array(str_replace('_','',$key),$arr)){
					unset($items[$key]);
				}else{
					$prod_info=M('content_product')->where(array('id'=>$val['id']))->find();
					
					$items[$key]['prod_info']=$prod_info;
					$items[$key]['attr']=json_decode($val['attr'],true);
					$allprice+=$prod_info['price']*$val['num'];
				}
				$i++;
			}

			

			


			$this->assign('items',$items);
			$this->assign('allprice',$allprice);
			

			
			$this->assign('paymethodlist',C('PAYMETHOD'));
			$this->assign('ids',$ids);
//			dump(get_member_credit());die;

//			$this->assign('credit',get_member_credit());
			$title = '确认订单';
			$this->assign ( 'title', $title );
			$this->display ();
		}
	}



	/**
	 * 确认积分订单
	 */
	public function pointconfirm($ids = '',$num='') {
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['addressid'] )) {
				$this->error ( '对不起，请选择收货地址！' );
			}
			$memberid = get_memberid ();
			$data ['memberid'] = $memberid;
			// 取收货地址
			$address = M ( 'address' )->where ( array (
					'id' => $data ['addressid'],
					'memberid' => $memberid
			) )->find ();
			if(!$address){
				$this->error('对不起，请重新选择收货地址！');
			}
			unset ( $address ['id'], $address ['addtime'], $address ['isdefault'] );
			$data=array_merge ( $data, $address );

			$item[$ids]['num']=$data['num'];
			$item[$ids]['attrs']='{"产品规格":"随机配送"}';

			$add=add_order($memberid,$data,$item);
			if($add['status']==1){
				$orderno=$add['info'];
				$this->success($orderno);
			}else{
				$this->error($add['info']);
			}
		} else {

			$memberid=get_memberid();
			// 获取收货地址列表

			$addresslist = get_address ();

			if (!$addresslist) {
				redirect ( U ( 'Member/editAddress', 'fromurl=' . think_encrypt ( get_current_url () ) ) );
			} else {
				$addresslist [0] ['isdefault'] = 1;
			}
			$this->assign ( 'addresslist', $addresslist );

			$where = array ();
			$where ['memberid'] = get_memberid ();
			if ($ids) {
				$items=M('content_product')->where(array('id'=>$ids))->select();
				$allprice=$items[0]['point']*$num;
			}else{
				$this->error('请选择要结算的商品');
			}

//
//			$point=M('member')->where(array('id'=>$memberid))->getField('point');
//			$this->assign('point',$point);


			$credit=get_w_r_credit($memberid);
			$this->assign('point',$credit['rcredit']);



//			$this->assign('credit',$credit);



			$this->assign('items',$items);
			$this->assign('allprice',$allprice);

			$this->assign('ids',$ids);
			$this->assign('num',$num);
			//$this->assign('balance',get_member_balance());
			$title = '确认积分订单';
			$this->assign ( 'title', $title );
			$this->display ();
		}
	}



	/**
	 * 生成付款跳转
	 *
	 * @param string $orderno
	 * @param string $paytype
	 */
	public function order($orderno = '') {

		if(is_wechat_browser()){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
			echo "<meta name='viewport' content='width=device-width,initial-scale=1.0,user-scalable=no'>";
			echo "<link rel='stylesheet' href='/PUBLIC/Home/css/mui.css' />";
			echo "<link rel='stylesheet' href='/PUBLIC/Home/css/fonts.css' />";
			echo "<link rel='stylesheet' href='/PUBLIC/Home/css/style.css' />";
			echo "<div class='floatdiv display-box-v'><div>点击右上角 “…” 选择“在浏览器中打开”<br>完成支付</div>";
			echo "<div class=' mt10'><button type='button' class='mui-btn mui-btn-warning>关闭</button></div></div>";
			die;
		}


		$order=M('order')->where(array('orderno'=>$orderno))->find();

//		dump( $order );
		vendor ( 'alipaydirect.create.loader' );
		$alipay_config = alipay_config ();

		// 支付类型
		$payment_type = "1";
		// 必填，不能修改
		// 服务器异步通知页面路径
		$notify_url = get_current_domain () . U ( 'Settle/notify' );
		// 需http://格式的完整路径，不能加?id=123这类自定义参数

		// 页面跳转同步通知页面路径
		$return_url = get_current_domain () . U ( 'Settle/result' );
		// 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

		// 商户订单号
		$out_trade_no = $orderno;
		// 商户网站订单系统中唯一订单号，必填

		// 订单名称
		$subject = $order ['name'];
		// 必填

		// 付款金额元$order ['total']
		$total_fee =$order ['total'];
		// 必填

		// 订单描述

		$body = '';
		// 商品展示地址
		$show_url = get_current_domain () . U('Member/orderView','orderno='.$orderno);
		// 需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

		// 防钓鱼时间戳
		$anti_phishing_key = "";
		// 若要使用请调用类文件submit中的query_timestamp函数

		// 客户端的IP地址
		$exter_invoke_ip = "";
		// 非局域网的外网IP地址，如：221.0.0.1
		/**
		 * *********************************************************
		 */
		// 构造要请求的参数数组，无需改动
		$parameter = array (
				"service" => "alipay.wap.create.direct.pay.by.user",
				"partner" => trim ( $alipay_config ['partner'] ),
				"_input_charset" => trim ( strtolower ( $alipay_config ['input_charset'] )),
				"notify_url" => $notify_url,
				"return_url" => $return_url,
				"out_trade_no" => $out_trade_no,
				"subject" => $subject,
				"total_fee" => $total_fee,
				"seller_id"=>trim ( $alipay_config ['partner'] ),
				"payment_type" => $payment_type,
				"show_url" => $show_url,
				//"body" => $body,
				"app_pay"	=> "Y"//启用此参数能唤起钱包APP支付宝

		);

		// 建立请求
		$alipaySubmit = new \AlipaySubmit ( $alipay_config );

		$html_text = $alipaySubmit->buildRequestForm ( $parameter, "get", "确认" );
//		dump($html_text);die;
		header("Content-type:text/html;charset=utf-8");

		echo $html_text;
	}



	/**
	 * 后台通知
	 */
	public function notify() {
		if (IS_POST) {

			// logdebug ( '通知：' . $_POST ['trade_status'] . '！' );
			logdebug ( $_POST );
			vendor ( 'alipaydirect.create.loader' );
			$alipay_config = alipay_config ();
			$alipayNotify = new \AlipayNotify ( $alipay_config );
			$verify_result = $alipayNotify->verifyNotify ();

			if ($verify_result) {
				$return = $_POST;

				$orderno = $return ['out_trade_no'];

				// 写日志
				//$this->logpay ( $orderno, $return );
				// 付款
				switch ($return ['trade_status']) {
					case 'TRADE_FINISHED' :
						break;
					case 'TRADE_SUCCESS' :
						$this->paid ( $orderno,$return );
						break;
				}
				echo ('success');
			} else {
				echo ('fail');
			}
		} else {
			echo ('fail');
		}
	}

	/**
	 * 前台通知
	 */
	public function result() {
		// logdebug ( '前台通知！' );
		// logdebug ( $_GET );
		vendor ( 'alipaydirect.create.loader' );
		$alipay_config = alipay_config ();
		$alipayNotify = new \AlipayNotify ( $alipay_config );
		$verify_result = $alipayNotify->verifyReturn ();

		if ($verify_result) {
			redirect(get_current_domain () . U('Member/order','status=1'));
			//echo ('success');
		} else {
			redirect(get_current_domain () . U('Member/order','status=1'));
			//echo ('fail');
		}
	}

	/**
	 * 写订单通知记录
	 *
	 * @param string $orderno
	 * @param unknown $return
	 */
	private function logpay($orderrand = '', $return = array()) {
		$data = array ();
		$org_id=M('organization_shop')->where(array('orderrand'=>$orderrand))->getField('org_id');
		$data ['org_id'] = $org_id;
		$data ['type'] = 2;
		$data ['no'] = $return['out_trade_no'];
		$data ['raw_data'] = json_encode ( $return,JSON_UNESCAPED_UNICODE );
		$data ['payment_date'] = time_format ();
		//$data['price']=$return['total_fee'];
		$data ['result_code'] = $return ['trade_status'];
		$addid = M ( 'payment_log' )->data ( $data )->add ();
	}


	/**
	 * 支付成功：处理事务
	 *
	 * @param string $orderno
	 * @param unknown $payinfo
	 */
	private function paid($orderno = '',$result='') {
		if ($orderno) {
			// 改状态
			$where = array ();
			$where ['orderno'] = $orderno;
			$where ['status'] = 0;
			$where['type']=1;

			$find = M ( 'order' )->where ( $where )->find ();
			logdebug('find=======================');
			logdebug($find);
			if ($find) {
				$data = array ();
				$data ['status'] = 1;
				$data ['paystatus'] = 1;
				$data['paytime']=date('Y-m-d H:i:s');
				$data['payinfo']=json_encode($result,JSON_UNESCAPED_UNICODE);
				$save = M ( 'order' )->where ( $where )->data ( $data )->save ();
				if($save!==false) {
					$member=M('member')->where(array('id'=>$find['memberid']))->find();
					$membersort=$member['sortpath'];
					$pro_detail=M('order_detail')->where(array('orderno'=>$find['orderno']))->select();
					for($i=0;$i<count($pro_detail);$i++){
						$prod_refee=M('content_product')->where(array('id'=>$pro_detail[$i]['productid']))->field('first_refee,second_refee,third_refee')->find();

						$refee[0]=$prod_refee['first_refee']*$pro_detail[$i]['num'];
						$refee[1]=$prod_refee['second_refee']*$pro_detail[$i]['num'];
						$refee[2]=$prod_refee['third_refee']*$pro_detail[$i]['num'];
						return_money($membersort,$refee,$find['memberid']);
					}
					return_point($find['memberid'],$find['total']);
				}
			}
		}
	}



	/**
	 * 在线充值
	 */
	public function chargeorder($orderno){
		if(is_wechat_browser()){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
			echo "<meta name='viewport' content='width=device-width,initial-scale=1.0,user-scalable=no'>";
			echo "<link rel='stylesheet' href='/PUBLIC/Home/css/mui.css' />";
			echo "<link rel='stylesheet' href='/PUBLIC/Home/css/fonts.css' />";
			echo "<link rel='stylesheet' href='/PUBLIC/Home/css/style.css' />";
			echo "<div class='floatdiv display-box-v'><div>点击右上角 “…” 选择“在浏览器中打开”<br>完成支付</div>";
			echo "<div class=' mt10'><button type='button' class='mui-btn mui-btn-warning>关闭</button></div></div>";
			die;
		}

		$order=M('account_log')->where(array('orderno'=>$orderno,'type'=>1,'status'=>0))->find();

//		dump( $order );
		vendor ( 'alipaydirect.create.loader' );
		$alipay_config = alipay_config ();

		// 支付类型
		$payment_type = "1";
		// 必填，不能修改
		// 服务器异步通知页面路径
		$notify_url = get_current_domain () . U ( 'Settle/chargenotify' );
		// 需http://格式的完整路径，不能加?id=123这类自定义参数

		// 页面跳转同步通知页面路径
		$return_url = get_current_domain () . U ( 'Settle/chargeresult' );
		// 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

		// 商户订单号
		$out_trade_no = $orderno;
		// 商户网站订单系统中唯一订单号，必填

		// 订单名称
		$subject = '在线充值';
		// 必填

		// 付款金额元$order ['total']
		$total_fee =$order ['amount'];
//		$total_fee =0.01;
		// 必填

		// 订单描述

		$body = '';
		// 商品展示地址
		$show_url = get_current_domain () . U('Member/charge');
		// 需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

		// 防钓鱼时间戳
		$anti_phishing_key = "";
		// 若要使用请调用类文件submit中的query_timestamp函数

		// 客户端的IP地址
		$exter_invoke_ip = "";
		// 非局域网的外网IP地址，如：221.0.0.1
		/**
		 * *********************************************************
		 */
		// 构造要请求的参数数组，无需改动
		$parameter = array (
				"service" => "alipay.wap.create.direct.pay.by.user",
				"partner" => trim ( $alipay_config ['partner'] ),
				"_input_charset" => trim ( strtolower ( $alipay_config ['input_charset'] )),
				"notify_url" => $notify_url,
				"return_url" => $return_url,
				"out_trade_no" => $out_trade_no,
				"subject" => $subject,
				"total_fee" => $total_fee,
				"seller_id"=>trim ( $alipay_config ['partner'] ),
				"payment_type" => $payment_type,
				"show_url" => $show_url,
			//"body" => $body,
				"app_pay"	=> "Y"//启用此参数能唤起钱包APP支付宝

		);

		// 建立请求
		$alipaySubmit = new \AlipaySubmit ( $alipay_config );

		$html_text = $alipaySubmit->buildRequestForm ( $parameter, "get", "确认" );
//		dump($html_text);die;
		header("Content-type:text/html;charset=utf-8");

		echo $html_text;
	}

	/**
	 * 在线充值后台通知
	 */
	public function chargenotify() {
		if (IS_POST) {

			// logdebug ( '通知：' . $_POST ['trade_status'] . '！' );
			logdebug ( $_POST );
			vendor ( 'alipaydirect.create.loader' );
			$alipay_config = alipay_config ();
			$alipayNotify = new \AlipayNotify ( $alipay_config );
			$verify_result = $alipayNotify->verifyNotify ();

			if ($verify_result) {
				$return = $_POST;

				$orderno = $return ['out_trade_no'];

				// 写日志
				//$this->logpay ( $orderno, $return );
				// 付款
				switch ($return ['trade_status']) {
					case 'TRADE_FINISHED' :
						break;
					case 'TRADE_SUCCESS' :
						$this->chargepaid ( $orderno,$return );
						break;
				}
				echo ('success');
			} else {
				echo ('fail');
			}
		} else {
			echo ('fail');
		}
	}

	/**
	 * 在线充值前台通知
	 */
	public function chargeresult() {
		// logdebug ( '前台通知！' );
		// logdebug ( $_GET );
		vendor ( 'alipaydirect.create.loader' );
		$alipay_config = alipay_config ();
		$alipayNotify = new \AlipayNotify ( $alipay_config );
		$verify_result = $alipayNotify->verifyReturn ();

		if ($verify_result) {
			redirect(get_current_domain () . U('Member/credit'));
			//echo ('success');
		} else {
			redirect(get_current_domain () . U('Member/credit'));
			//echo ('fail');
		}
	}

	/**
	 * 在线充值支付成功：处理事务
	 *
	 * @param string $orderno
	 * @param unknown $payinfo
	 */
	private function chargepaid($orderno = '',$result='') {
		if ($orderno) {
			// 改状态
			$where = array ();


			$where ['orderno'] = $orderno;

			$where ['status'] = 0;
			$where['type']=1;

			$find = M ( 'account_log' )->where ( $where )->find ();
			if ($find) {
				$data = array ();
				$data ['status'] = 1;
				$data['paytime']=date('Y-m-d H:i:s');
				$data['payinfo']=json_encode($result,JSON_UNESCAPED_UNICODE);

				$save = M ( 'account_log' )->where ( $where )->data ( $data )->save ();

				if($save!==false) {
					M('member')->where(array('id'=>$find['memberid']))->setField('credit',array('exp','credit + '.$find['amount']));

					logdebug(M('member')->getLastSql());

				}
			}
		}
	}


}