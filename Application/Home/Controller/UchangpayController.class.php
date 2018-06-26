<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/11/20
 * Time: 9:50
 */

namespace Home\Controller;


use Think\Controller;


class UchangpayController extends Controller
{
    public function order($orderno)
    {
        if (!$orderno) {
            echo "订单号错误";
            die;
        }
        $tblname = get_order_type($orderno);

        $where = array();
        $where ['status'] = 0; // 未支付
        $where ['orderno'] = $orderno;
        $order = M($tblname)->where($where)->find();

//		dump($order);die;
        if ($order) {

            $out_trade_no = $order ['orderno'];
            $body = $order ['name'];
            $total_fee = $order ['amount'] * 100;


            $openid = M('member')->where(array('id' => $order['memberid']))->getField('openid');
//			dump($openid);die;
            $unifiedOrder = array();
            $apiurl=C('config.UCHANGPAY_APIURL');
            $mchid=C('config.UCHANGPAY_MCHID');
            $mpinfo = get_mp_info ( get_mp_str () );
            $appid=$mpinfo['app_id'];
            $unifiedOrder["mch_id"]= $mchid;
            $unifiedOrder["sub_appid"]= $appid;
			//dump($total_fee);die;
            $unifiedOrder["nonce_str"]= u_createNoncestr();
            $unifiedOrder["body"]=$body; // 商品描述
            $unifiedOrder["out_trade_no"]= $out_trade_no . '_' . NOW_TIME; // 商户订单号
            $unifiedOrder["total_fee"]= intval($total_fee); // 总金额$total_fee
            $unifiedOrder['spbill_create_ip']=get_client_ip();
            $notify_url = (C('BASE_URL') . 'Uchangpay/notify');
            $unifiedOrder["notify_url"]= $notify_url; // 通知地址
            $unifiedOrder["trade_type"]= "JSAPI"; // 交易类型
            $unifiedOrder["sub_openid"]= $openid; // openid
            $sign=u_get_signature($unifiedOrder,'','MD5');
            $unifiedOrder['sign']=$sign;
			
			//dump($unifiedOrder);
			

            $url=$apiurl.'wechat/orders';
            $xml=u_arrayToXml($unifiedOrder);
            $returndata=u_curl_post_ssl($url,$xml);

			//dump($url);
			//dump($xml);
	
			//dump($returndata);die;


////			dump($unifiedOrder);die;
//
            $prepay_id = $this->getPrepayId ($returndata);
//            // =========步骤3：使用jsapi调起支付============
//            $jsApi->setPrepayId ( $prepay_id );
//

            $jsApiParameters = $this->getParameters ($returndata);
			//dump($jsApiParameters);die;

            // 返回地址
            $urlok = C('BASE_URL') . '/Member/ordersuccess/orderno/' . $out_trade_no . '/status/1.html';
            $url = C('BASE_URL') . '/Member/ordersuccess/orderno/' . $out_trade_no . '/status/2.html';

            $html = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /><title>支付中...</title><style type="text/css">.poppay{position:absolute;left:0;top:50%;width:100%;margin-top:-70px}.zzsc6{width:150px;margin:auto;text-align:center}.zzsc6>div{width:30px;height:30px;background-color:#67cf22;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.zzsc6 .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.zzsc6 .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0)}40%{-webkit-transform:scale(1)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0);-webkit-transform:scale(0)}40%{transform:scale(1);-webkit-transform:scale(1)}}</style></head><body><div class="poppay"><div class="zzsc6"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><p style="text-align:center">正在创建支付链接，请稍后……</p></div><script>function jsApiCall(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $jsApiParameters . ',function(res){var urlok="' . $urlok . '";var url="' . $url . '";/*alert(JSON.stringify(res.err_desc));【"+res.err_desc+"】*/if(res.err_msg=="get_brand_wcpay_request:ok"){if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{location=urlok;}}else{ alert("对不起，支付失败！");if(url=="closeWindow"){WeixinJSBridge.invoke("closeWindow",{},function(res){});}else{setTimeout(function(){location=url;	},1000);}}});};function callpay(){if(typeof WeixinJSBridge=="undefined"){if(document.addEventListener){document.addEventListener("WeixinJSBridgeReady",jsApiCall,false)}else{if(document.attachEvent){document.attachEvent("WeixinJSBridgeReady",jsApiCall);document.attachEvent("onWeixinJSBridgeReady",jsApiCall)}}}else{jsApiCall()}};callpay();</script></body></html>';//
            echo($html);
            exit ();
        }
    }
	
	
	/**
	 * 支付通知
	 */
	public function notify() {
		
		$xml = file_get_contents('php://input');
		
		$data=u_xml_to_array($xml);
		logdebug("paydata");
		logdebug($data);
		
		logdebug ( "【接收到的notify通知】:\n" . $xml . "\n" );
		
		if($data['return_code']=='SUCCESS' && $data['result_code']=='SUCCESS'){
			// 商户订单号
			$out_trade_no = $data["out_trade_no"];
			// 支付交易号
			$trade_no = $data["transaction_id"];
			// 交易状态
			$trade_status = $data["result_code"];
			// 交易金额
			$total_fee = $data["total_fee"] / 100;
			$payinfo = serialize ( $data );
			$this->payok ( $out_trade_no, $trade_no, $trade_status, $total_fee, $payinfo );
			echo ('SUCCESS');
		}
		// 使用通用通知接口
		//if (! $notify->data ['appid']) {
		//	echo ('FAIL');
		//	exit ();
		//}
		//$orderno = $notify->data ['out_trade_no'];
		//// 验证签名，并回应微信。
		//// 对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		//// 微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		//// 尽可能提高通知的成功率，但微信不保证通知最终能成功。
		//if ($notify->checkSign () == FALSE) {
		//	$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
		//	$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		//} else {
		//	$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		//}
		//$returnXml = $notify->returnXml ();
		//
		//logdebug ( "【接收到的notify通知】:\n" . $xml . "\n" );
		//
		//if ($notify->checkSign () == TRUE) {
		//	if ($notify->data ["return_code"] == "FAIL") {
		//		// 此处应该更新一下订单状态，商户自行增删操作
		//		logdebug ( "【通信出错】:\n" . $xml . "\n" );
		//	} elseif ($notify->data ["result_code"] == "FAIL") {
		//		// 此处应该更新一下订单状态，商户自行增删操作
		//		logdebug ( "【业务出错】:\n" . $xml . "\n" );
		//	} else {
		//		// 此处应该更新一下订单状态，商户自行增删操作
		//		logdebug ( "【支付成功】:\n" . $xml . "\n" );
		//		
		//		// 商户订单号
		//		$out_trade_no = $notify->data ["out_trade_no"];
		//		// 支付交易号
		//		$trade_no = $notify->data ["transaction_id"];
		//		// 交易状态
		//		$trade_status = $notify->data ["return_code"];
		//		// 交易金额
		//		$total_fee = $notify->data ["total_fee"] / 100;
		//		$payinfo = serialize ( $notify->data );
		//		$this->payok ( $out_trade_no, $trade_no, $trade_status, $total_fee, $payinfo );
		//		echo ('SUCCESS');
		//	}
		//	
		//	// 商户自行增加处理流程,
		//	// 例如：更新订单状态
		//	// 例如：数据库操作
		//	// 例如：推送支付完成信息
		//}
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

			$tblname = get_order_type($out_trade_no);
			
			// 改状态
			$where = array ();

			$where ['orderno'] = $out_trade_no;

			$where ['status'] = 0;
			$where['type']=1;
			$find = M ( $tblname )->where ( $where )->find ();
			if ($find) {
				$data = array ();
				$data ['status'] = 1;
				$data ['paystatus'] = 1;
				$data['paytime']=date('Y-m-d H:i:s');
				$data['payinfo']=json_encode($payinfo,JSON_UNESCAPED_UNICODE);
				$save = M ( $tblname )->where ( $where )->data ( $data )->save ();
				if($save!==false) {
					//修改订单详细的状态
					if($tblname=='order')
						M('order_detail')->where(array('orderno'=>$out_trade_no))->setField('status',1);

				}
			}
		}
	}


	
	

    /**
     * 获取prepay_id
     */
    public function getPrepayId($returndata)
    {

        $result=u_xml_to_array($returndata);
        $prepay_id = $result["prepay_id"];
        return $prepay_id;
    }
    /**
     * 	作用：设置jsapi的参数
     */
    public function getParameters($returndata)
    {
		
		$result=u_xml_to_array($returndata);
		
		$prepayinfo=json_decode($result['js_prepay_info'],true);

        $jsApiObj["appId"] =$prepayinfo['appId'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = $prepayinfo['timeStamp'];
        $jsApiObj["nonceStr"] = $prepayinfo['nonceStr'];
        $jsApiObj["package"] = $prepayinfo['package'];
        $jsApiObj["signType"] = $prepayinfo['signType'];
		
        $jsApiObj["paySign"] = $prepayinfo['paySign'];
	
        $parameters = json_encode($jsApiObj);
		
        return $parameters;
    }
}