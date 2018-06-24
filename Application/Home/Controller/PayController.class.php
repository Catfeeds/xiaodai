<?php

namespace Home\Controller;

use Think\Controller;

class PayController extends Controller {
	public function index() {
	}
	
	/**
	 * 订单支付
	 *
	 * @param string $orderNo        	
	 */
	public function order($orderno = '', $code = '',$no='') {
		$where = array ();
		$where ['status'] =array('in','2,3'); // 未还款或已逾期
		$where ['orderno'] = $orderno;
		$order = M ( 'loan' )->where ( $where )->find ();
		if ($order) {
			load_wxpay ();
			vendor ( "WxPayPubHelper/WxPayPubHelper" );
			$jsApi = new \JsApi_pub ();
			$out_trade_no = $order ['orderno'];
			$body = "贷款还款";
			$total_fee = $order ['refundamount'] * 100;
			$jscallurl = get_current_url ();
			if ($code == '') {
				// 触发微信返回code码
				$url = $jsApi->createOauthUrlForCode ( $jscallurl );
				Header ( "Location: $url" );
				exit ();
			} else {
				// 获取code码，以获取openid
				$jsApi->setCode ( $code );
				$openid = $jsApi->getOpenId ();
			}
			//$openid=M('member')->where(array('id'=>$order['memberid']))->getField('openid');
//			dump($openid);die;
			$unifiedOrder = new \UnifiedOrder_pub ();
			$unifiedOrder->setParameter ( "openid", $openid ); // openid
			$unifiedOrder->setParameter ( "body", $body ); // 商品描述
			$unifiedOrder->setParameter ( "out_trade_no", $out_trade_no.'_'.NOW_TIME ); // 商户订单号
			$unifiedOrder->setParameter ( "total_fee", intval($total_fee) ); // 总金额$total_fee
			
			$notify_url = (C ( 'BASE_URL' ) . '/Pay/notify');
			$unifiedOrder->setParameter ( "notify_url", $notify_url ); // 通知地址
			$unifiedOrder->setParameter ( "trade_type", "JSAPI" ); // 交易类型

//			dump($unifiedOrder);die;

			$prepay_id = $unifiedOrder->getPrepayId ();
			// =========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId ( $prepay_id );
			
			$jsApiParameters = $jsApi->getParameters ();

			// 返回地址
			$urlok = C ( 'BASE_URL' ) . '/Member/ordersuccess/orderno/'.$out_trade_no.'/status/1.html';//成功
			$url = C ( 'BASE_URL' ) . '/Member/ordersuccess/orderno/'.$out_trade_no.'/status/2.html';//失败
			
			$html = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /><title>支付中...</title><style type="text/css">.poppay{position:absolute;left:0;top:50%;width:100%;margin-top:-70px}.zzsc6{width:150px;margin:auto;text-align:center}.zzsc6>div{width:30px;height:30px;background-color:#67cf22;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.zzsc6 .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.zzsc6 .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0)}40%{-webkit-transform:scale(1)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0);-webkit-transform:scale(0)}40%{transform:scale(1);-webkit-transform:scale(1)}}</style></head><body><div class="poppay"><div class="zzsc6"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><p style="text-align:center">正在创建支付链接，请稍后……</p></div><script>function jsApiCall(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $jsApiParameters . ',function(res){var urlok="' . $urlok . '";var url="' . $url . '";/*alert(JSON.stringify(res.err_desc));【"+res.err_desc+"】*/if(res.err_msg=="get_brand_wcpay_request:ok"){if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{location=urlok;}}else{ alert("对不起，支付失败！");if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{setTimeout(function(){location=url;	},1000);}}});};function callpay(){if(typeof WeixinJSBridge=="undefined"){if(document.addEventListener){document.addEventListener("WeixinJSBridgeReady",jsApiCall,false)}else{if(document.attachEvent){document.attachEvent("WeixinJSBridgeReady",jsApiCall);document.attachEvent("onWeixinJSBridgeReady",jsApiCall)}}}else{jsApiCall()}};callpay();</script></body></html>';
			echo ($html);
			exit ();
		}
	}
	
	/**
	 * 支付通知
	 */
	public function notify() {
		load_wxpay ();
		
		vendor ( "WxPayPubHelper/WxPayPubHelper" );
		$notify = new \Notify_pub ();
		$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];
		
		$notify->saveData ( $xml );
		// 使用通用通知接口
		if (! $notify->data ['appid']) {
			echo ('FAIL');
			exit ();
		}
		$orderno = $notify->data ['out_trade_no'];
		// 验证签名，并回应微信。
		// 对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		// 微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		// 尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if ($notify->checkSign () == FALSE) {
			$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		}
		$returnXml = $notify->returnXml ();
		
		logdebug ( "【接收到的notify通知】:\n" . $xml . "\n" );
		
		if ($notify->checkSign () == TRUE) {
			if ($notify->data ["return_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【通信出错】:\n" . $xml . "\n" );
			} elseif ($notify->data ["result_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【业务出错】:\n" . $xml . "\n" );
			} else {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【支付成功】:\n" . $xml . "\n" );
				
				// 商户订单号
				$out_trade_no = $notify->data ["out_trade_no"];
				// 支付交易号
				$trade_no = $notify->data ["transaction_id"];
				// 交易状态
				$trade_status = $notify->data ["return_code"];
				// 交易金额
				$total_fee = $notify->data ["total_fee"] / 100;
				$payinfo = serialize ( $notify->data );
				$this->payok ( $out_trade_no, $trade_no, $trade_status, $total_fee, $payinfo );
				echo ('SUCCESS');
			}
			
			// 商户自行增加处理流程,
			// 例如：更新订单状态
			// 例如：数据库操作
			// 例如：推送支付完成信息
		}
	}
	
	/**
	 * 支付成功
	 *
	 * @param string $data        	
	 */
	private function payok($out_trade_no = '', $trade_no = '', $trade_status = '', $total_fee = '', $payinfo = '') {

		if ($out_trade_no) {

			if(strpos($out_trade_no,'_')){
				$out_trade_no=str2arr($out_trade_no,'_');
				$out_trade_no=$out_trade_no[0];
			}

			// 改状态
			$where = array ();

			$where ['orderno'] = $out_trade_no;

			$where ['status'] =array('in','2,3'); // 未还款或已逾期

			$find = M ( 'loan' )->where ( $where )->find ();
			if ($find) {
				$data = array ();
				$data ['status'] = 4;
				$data ['paystatus'] = 1;
				$data['refundtime']=date('Y-m-d H:i:s');
				$data['refundinfo']=json_encode($payinfo,JSON_UNESCAPED_UNICODE);
				$save = M ( 'loan' )->where ( $where )->data ( $data )->save ();

				$data = array('status'=>1,'usetime'=>data('Y-m-d H:i:s'));
				//优惠劵改状态
				M('coupon')->where(array('no'=>$find['no']))->setField($data);

			}
		}
	}




	/**
	 * 在线充值订单支付
	 *
	 * @param string $orderNo
	 */
	public function classorder($orderno = '', $code = '') {

		$order=M('class_order')->where(array('orderno'=>$orderno,'status'=>0))->find();
//		dump($order);die;
		if ($order) {
			load_wxpay ();

			vendor ( "WxPayPubHelper/WxPayPubHelper" );
			$jsApi = new \JsApi_pub ();
			$out_trade_no = $order ['orderno'];
			$body = $order ['classname'];
			$total_fee = $order ['amount'] * 100;

			$jscallurl = get_current_url ();
//			if ($code == '') {
//				// 触发微信返回code码
//				$url = $jsApi->createOauthUrlForCode ( $jscallurl );
//				Header ( "Location: $url" );
//				exit ();
//			} else {
//				// 获取code码，以获取openid
//				$jsApi->setCode ( $code );
//				$openid = $jsApi->getOpenId ();
//			}
			$openid=M('member')->where(array('id'=>$order['memberid']))->getField('openid');
//			dump($openid);die;
			$unifiedOrder = new \UnifiedOrder_pub ();
			$unifiedOrder->setParameter ( "openid", $openid ); // openid
			$unifiedOrder->setParameter ( "body", $body ); // 商品描述
			$unifiedOrder->setParameter ( "out_trade_no", $out_trade_no.'_'.NOW_TIME ); // 商户订单号
			$unifiedOrder->setParameter ( "total_fee", intval($total_fee) ); // 总金额

			$notify_url = (C ( 'BASE_URL' ) . '/Pay/chargenotify');
			$unifiedOrder->setParameter ( "notify_url", $notify_url ); // 通知地址
			$unifiedOrder->setParameter ( "trade_type", "JSAPI" ); // 交易类型

			$prepay_id = $unifiedOrder->getPrepayId ();
			// =========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId ( $prepay_id );

			$jsApiParameters = $jsApi->getParameters ();

			// 返回地址
			$urlok = C ( 'BASE_URL' ) . 'Classes/view/id/'.$order['classid'].'.html';
			$url = C ( 'BASE_URL' )  . 'Classes/view/id/'.$order['classid'].'.html';

			$html = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /><title>支付中...</title><style type="text/css">.poppay{position:absolute;left:0;top:50%;width:100%;margin-top:-70px}.zzsc6{width:150px;margin:auto;text-align:center}.zzsc6>div{width:30px;height:30px;background-color:#67cf22;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.zzsc6 .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.zzsc6 .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0)}40%{-webkit-transform:scale(1)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0);-webkit-transform:scale(0)}40%{transform:scale(1);-webkit-transform:scale(1)}}</style></head><body><div class="poppay"><div class="zzsc6"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><p style="text-align:center">正在创建支付链接，请稍后……</p></div><script>function jsApiCall(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $jsApiParameters . ',function(res){var urlok="' . $urlok . '";var url="' . $url . '";/*alert(JSON.stringify(res.err_desc));【"+res.err_desc+"】*/if(res.err_msg=="get_brand_wcpay_request:ok"){if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{location=urlok;}}else{ alert("对不起，支付失败！");if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{setTimeout(function(){location=url;	},1000);}}});};function callpay(){if(typeof WeixinJSBridge=="undefined"){if(document.addEventListener){document.addEventListener("WeixinJSBridgeReady",jsApiCall,false)}else{if(document.attachEvent){document.attachEvent("WeixinJSBridgeReady",jsApiCall);document.attachEvent("onWeixinJSBridgeReady",jsApiCall)}}}else{jsApiCall()}};callpay();</script></body></html>';
			echo ($html);
			exit ();
		}
	}

	/**
	 * 在线充值支付通知
	 */
	public function chargenotify() {
		load_wxpay ();

		vendor ( "WxPayPubHelper/WxPayPubHelper" );
		$notify = new \Notify_pub ();
		$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];

		$notify->saveData ( $xml );
		// 使用通用通知接口
		if (! $notify->data ['appid']) {
			echo ('FAIL');
			exit ();
		}
		$orderno = $notify->data ['out_trade_no'];
		// 验证签名，并回应微信。
		// 对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		// 微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		// 尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if ($notify->checkSign () == FALSE) {
			$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		}
		$returnXml = $notify->returnXml ();

		logdebug ( "【接收到的notify通知】:\n" . $xml . "\n" );

		if ($notify->checkSign () == TRUE) {
			if ($notify->data ["return_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【通信出错】:\n" . $xml . "\n" );
			} elseif ($notify->data ["result_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【业务出错】:\n" . $xml . "\n" );
			} else {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【支付成功】:\n" . $xml . "\n" );

				// 商户订单号
				$out_trade_no = $notify->data ["out_trade_no"];
				// 支付交易号
				$trade_no = $notify->data ["transaction_id"];
				// 交易状态
				$trade_status = $notify->data ["return_code"];
				// 交易金额
				$total_fee = $notify->data ["total_fee"] / 100;
				$payinfo = serialize ( $notify->data );
				$this->chargepayok ( $out_trade_no, $trade_no, $trade_status, $total_fee, $payinfo );
				echo ('SUCCESS');
			}

			// 商户自行增加处理流程,
			// 例如：更新订单状态
			// 例如：数据库操作
			// 例如：推送支付完成信息
		}
	}

	/**
	 * 在线充值支付成功
	 *
	 * @param string $data
	 */
	private function chargepayok($out_trade_no = '', $trade_no = '', $trade_status = '', $total_fee = '', $payinfo = '') {

		if ($out_trade_no) {

			if(strpos($out_trade_no,'_')){
				$out_trade_no=str2arr($out_trade_no,'_');
				$out_trade_no=$out_trade_no[0];
			}

			// 改状态
			$where = array ();


			$where ['orderno'] = $out_trade_no;

			$where ['status'] = 0;

			$find = M ( 'class_order' )->where ( $where )->find ();
			if ($find) {
				$data = array ();
				$data ['status'] = 1;
				$data['paytime']=date('Y-m-d H:i:s');
				$data['payinfo']=json_encode($payinfo,JSON_UNESCAPED_UNICODE);

				$save = M ( 'class_order' )->where ( $where )->data ( $data )->save ();

			}
		}
	}


	/**
	 * 活动报名订单支付
	 *
	 * @param string $orderNo
	 */
	public function activeorder($orderno = '', $code = '') {

		$order=M('content_active_member')->where(array('no'=>$orderno,'status'=>0))->find();
//		dump($order);die;
		if ($order) {
			load_wxpay ();

			vendor ( "WxPayPubHelper/WxPayPubHelper" );
			$jsApi = new \JsApi_pub ();
			$out_trade_no = $order ['no'];
			$body = $order ['activename'];
			$total_fee = $order ['amount'] * 100;

//			$jscallurl = get_current_url ();
//			if ($code == '') {
//				// 触发微信返回code码
//				$url = $jsApi->createOauthUrlForCode ( $jscallurl );
//				Header ( "Location: $url" );
//				exit ();
//			} else {
//				// 获取code码，以获取openid
//				$jsApi->setCode ( $code );
//				$openid = $jsApi->getOpenId ();
//			}
			$openid=M('member')->where(array('id'=>$order['memberid']))->getField('openid');
//			dump($openid);die;
			$unifiedOrder = new \UnifiedOrder_pub ();
			$unifiedOrder->setParameter ( "openid", $openid ); // openid
			$unifiedOrder->setParameter ( "body", $body ); // 商品描述
			$unifiedOrder->setParameter ( "out_trade_no", $out_trade_no.'_'.NOW_TIME ); // 商户订单号
			$unifiedOrder->setParameter ( "total_fee", intval($total_fee) ); // 总金额

			$notify_url = (C ( 'BASE_URL' ) . '/Pay/activenotify');
			$unifiedOrder->setParameter ( "notify_url", $notify_url ); // 通知地址
			$unifiedOrder->setParameter ( "trade_type", "JSAPI" ); // 交易类型

			$prepay_id = $unifiedOrder->getPrepayId ();
			// =========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId ( $prepay_id );

			$jsApiParameters = $jsApi->getParameters ();

			// 返回地址
			$urlok = C ( 'BASE_URL' ) . 'Active/signsuccess/id/'.$order['activeid'].'.html';
			$url = C ( 'BASE_URL' )  . 'Active/view/id/'.$order['activeid'].'.html';

			$html = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /><title>支付中...</title><style type="text/css">.poppay{position:absolute;left:0;top:50%;width:100%;margin-top:-70px}.zzsc6{width:150px;margin:auto;text-align:center}.zzsc6>div{width:30px;height:30px;background-color:#67cf22;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.zzsc6 .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.zzsc6 .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0)}40%{-webkit-transform:scale(1)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0);-webkit-transform:scale(0)}40%{transform:scale(1);-webkit-transform:scale(1)}}</style></head><body><div class="poppay"><div class="zzsc6"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><p style="text-align:center">正在创建支付链接，请稍后……</p></div><script>function jsApiCall(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $jsApiParameters . ',function(res){var urlok="' . $urlok . '";var url="' . $url . '";/*alert(JSON.stringify(res.err_desc));【"+res.err_desc+"】*/if(res.err_msg=="get_brand_wcpay_request:ok"){if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{location=urlok;}}else{ alert("对不起，支付失败！");if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{setTimeout(function(){location=url;	},1000);}}});};function callpay(){if(typeof WeixinJSBridge=="undefined"){if(document.addEventListener){document.addEventListener("WeixinJSBridgeReady",jsApiCall,false)}else{if(document.attachEvent){document.attachEvent("WeixinJSBridgeReady",jsApiCall);document.attachEvent("onWeixinJSBridgeReady",jsApiCall)}}}else{jsApiCall()}};callpay();</script></body></html>';
			echo ($html);
			exit ();
		}
	}

	/**
	 * 活动报名支付通知
	 */
	public function activenotify() {
		load_wxpay ();

		vendor ( "WxPayPubHelper/WxPayPubHelper" );
		$notify = new \Notify_pub ();
		$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];

		$notify->saveData ( $xml );
		// 使用通用通知接口
		if (! $notify->data ['appid']) {
			echo ('FAIL');
			exit ();
		}
		$orderno = $notify->data ['out_trade_no'];
		// 验证签名，并回应微信。
		// 对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		// 微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		// 尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if ($notify->checkSign () == FALSE) {
			$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		}
		$returnXml = $notify->returnXml ();

		logdebug ( "【接收到的notify通知】:\n" . $xml . "\n" );

		if ($notify->checkSign () == TRUE) {
			if ($notify->data ["return_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【通信出错】:\n" . $xml . "\n" );
			} elseif ($notify->data ["result_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【业务出错】:\n" . $xml . "\n" );
			} else {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【支付成功】:\n" . $xml . "\n" );

				// 商户订单号
				$out_trade_no = $notify->data ["out_trade_no"];
				// 支付交易号
				$trade_no = $notify->data ["transaction_id"];
				// 交易状态
				$trade_status = $notify->data ["return_code"];
				// 交易金额
				$total_fee = $notify->data ["total_fee"] / 100;
				$payinfo = serialize ( $notify->data );
				$this->activepayok ( $out_trade_no, $trade_no, $trade_status, $total_fee, $payinfo );
				echo ('SUCCESS');
			}

			// 商户自行增加处理流程,
			// 例如：更新订单状态
			// 例如：数据库操作
			// 例如：推送支付完成信息
		}
	}

	/**
	 * 活动报名支付成功
	 *
	 * @param string $data
	 */
	private function activepayok($out_trade_no = '', $trade_no = '', $trade_status = '', $total_fee = '', $payinfo = '') {

		if ($out_trade_no) {

			if(strpos($out_trade_no,'_')){
				$out_trade_no=str2arr($out_trade_no,'_');
				$out_trade_no=$out_trade_no[0];
			}

			// 改状态
			$where = array ();
			$where ['no'] = $out_trade_no;
			$where ['status'] = 0;

			$find = M ( 'content_active_member' )->where ( $where )->find ();
			if ($find) {
				$data = array ();
				$data ['status'] = 1;
				$data['paytime']=date('Y-m-d H:i:s');
				$data['payinfo']=json_encode($payinfo,JSON_UNESCAPED_UNICODE);

				$save = M ( 'content_active_member' )->where ( $where )->data ( $data )->save ();
				//报名加1
				M ( 'content_active' )->where ( array('id'=>$find['activeid']) )->setInc('sold');

				//参加报名活动返利
				$member=M('member')->where(array('id'=>$find['memberid']))->find();
				$membersort=$member['sortpath'];
				$active=M('content_active')->where(array('id'=>$find['activeid']))->find();
				$refee[0]=$active['first_refee'];
				$refee[1]=$active['second_refee'];
				$refee[2]=$active['third_refee'];
				if($refee[0]>0){
					return_money($membersort,$refee,$find['memberid']);
				}
				
				if($active['type']==2){
					$wherep = array ();
					$wherep ['orderno'] = $out_trade_no;
					$wherep ['status'] = 0;
					M ( 'content_active_memberp' )->where ( $wherep )->setField('status',1);
				}
				
			}
		}
	}
	//申请延期
	public function delay($orderno = '', $code = '') {
		$where = array ();
		$where ['status'] =0; // 未还款或已逾期
		$where ['dealno'] = $orderno;
		$order = M ( 'delay' )->where ( $where )->find ();
		if ($order) {
			load_wxpay ();
			vendor ( "WxPayPubHelper/WxPayPubHelper" );
			$jsApi = new \JsApi_pub ();
			$out_trade_no = $order ['orderno'];
			$body = "申请延期服务费";
			$total_fee = $order['money'] * 100;
			$jscallurl = get_current_url ();
			if ($code == '') {
				// 触发微信返回code码
				$url = $jsApi->createOauthUrlForCode ( $jscallurl );
				Header ( "Location: $url" );
				exit ();
			} else {
				// 获取code码，以获取openid
				$jsApi->setCode ( $code );
				$openid = $jsApi->getOpenId ();
			}
			//$openid=M('member')->where(array('id'=>$order['memberid']))->getField('openid');
//			dump($openid);die;
			$unifiedOrder = new \UnifiedOrder_pub ();
			$unifiedOrder->setParameter ( "openid", $openid ); // openid
			$unifiedOrder->setParameter ( "body", $body ); // 商品描述
			$unifiedOrder->setParameter ( "out_trade_no", $out_trade_no.'_'.NOW_TIME ); // 商户订单号
			$unifiedOrder->setParameter ( "total_fee", intval($total_fee) ); // 总金额$total_fee

			$notify_url = (C ( 'BASE_URL' ) . '/Pay/delenotify');
			$unifiedOrder->setParameter ( "notify_url", $notify_url ); // 通知地址
			$unifiedOrder->setParameter ( "trade_type", "JSAPI" ); // 交易类型

//			dump($unifiedOrder);die;

			$prepay_id = $unifiedOrder->getPrepayId ();
			// =========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId ( $prepay_id );

			$jsApiParameters = $jsApi->getParameters ();

			// 返回地址
			$urlok = C ( 'BASE_URL' ) . '/Member/ordersuccess/orderno/'.$out_trade_no.'/status/1.html';
			$url = C ( 'BASE_URL' ) . '/Member/ordersuccess/orderno/'.$out_trade_no.'/status/2.html';

			$html = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /><title>支付中...</title><style type="text/css">.poppay{position:absolute;left:0;top:50%;width:100%;margin-top:-70px}.zzsc6{width:150px;margin:auto;text-align:center}.zzsc6>div{width:30px;height:30px;background-color:#67cf22;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.zzsc6 .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.zzsc6 .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0)}40%{-webkit-transform:scale(1)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0);-webkit-transform:scale(0)}40%{transform:scale(1);-webkit-transform:scale(1)}}</style></head><body><div class="poppay"><div class="zzsc6"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><p style="text-align:center">正在创建支付链接，请稍后……</p></div><script>function jsApiCall(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $jsApiParameters . ',function(res){var urlok="' . $urlok . '";var url="' . $url . '";/*alert(JSON.stringify(res.err_desc));【"+res.err_desc+"】*/if(res.err_msg=="get_brand_wcpay_request:ok"){if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{location=urlok;}}else{ alert("对不起，支付失败！");if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{setTimeout(function(){location=url;	},1000);}}});};function callpay(){if(typeof WeixinJSBridge=="undefined"){if(document.addEventListener){document.addEventListener("WeixinJSBridgeReady",jsApiCall,false)}else{if(document.attachEvent){document.attachEvent("WeixinJSBridgeReady",jsApiCall);document.attachEvent("onWeixinJSBridgeReady",jsApiCall)}}}else{jsApiCall()}};callpay();</script></body></html>';
			echo ($html);
			exit ();
		}
	}
	public function delenotify() {
		load_wxpay ();

		vendor ( "WxPayPubHelper/WxPayPubHelper" );
		$notify = new \Notify_pub ();
		$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];

		$notify->saveData ( $xml );
		// 使用通用通知接口
		if (! $notify->data ['appid']) {
			echo ('FAIL');
			exit ();
		}
		$orderno = $notify->data ['out_trade_no'];
		// 验证签名，并回应微信。
		// 对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		// 微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		// 尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if ($notify->checkSign () == FALSE) {
			$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		}
		$returnXml = $notify->returnXml ();

		logdebug ( "【接收到的notify通知】:\n" . $xml . "\n" );

		if ($notify->checkSign () == TRUE) {
			if ($notify->data ["return_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【通信出错】:\n" . $xml . "\n" );
			} elseif ($notify->data ["result_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【业务出错】:\n" . $xml . "\n" );
			} else {
				// 此处应该更新一下订单状态，商户自行增删操作
				logdebug ( "【支付成功】:\n" . $xml . "\n" );

				// 商户订单号
				$out_trade_no = $notify->data ["out_trade_no"];
				// 支付交易号
				$trade_no = $notify->data ["transaction_id"];
				// 交易状态
				$trade_status = $notify->data ["return_code"];
				// 交易金额
				$total_fee = $notify->data ["total_fee"] / 100;
				$payinfo = serialize ( $notify->data );
				$this->hpayok ( $out_trade_no, $trade_no, $trade_status, $total_fee, $payinfo );
				echo ('SUCCESS');
			}

			// 商户自行增加处理流程,
			// 例如：更新订单状态
			// 例如：数据库操作
			// 例如：推送支付完成信息
		}
	}

	private function hpayok($out_trade_no = '', $trade_no = '', $trade_status = '', $total_fee = '', $payinfo = '') {

		if ($out_trade_no) {

			if(strpos($out_trade_no,'_')){
				$out_trade_no=str2arr($out_trade_no,'_');
				$out_trade_no=$out_trade_no[0];
			}

			// 改状态
			$where = array ();

			$where ['dealno'] = $out_trade_no;

			$where ['status'] =0; // 未还款或已逾期

			$find = M ( 'delay' )->where ( $where )->find ();
			if ($find) {
				$data = array ();
				//$data ['status'] = 4;
				$data ['status'] = 1;
				$data['refundtime']=date('Y-m-d H:i:s');
				$data['refundinfo']=json_encode($payinfo,JSON_UNESCAPED_UNICODE);

				$save = M ( 'delay' )->where ( $where )->data ( $data )->save ();

				//$rows = array('deadline'=>$where ['dealno']);
				$r['deadline'] =

				$row = M('loan')->where(['orderno'=>$where ['dealno']])->find();

				$r['deadline'] =date('Y-m-d H:i:s',strtotime($row['deadline'])+259200);

				M ( 'loan' )->where ( ['orderno'=>$where ['dealno']] )->save ($r);


			}
		}
	}
}