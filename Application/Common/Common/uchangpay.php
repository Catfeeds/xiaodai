<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/11/20
 * Time: 9:53
 */
/**
 * 	作用：产生随机字符串，不长于32位
 */
function u_createNoncestr( $length = 32 )
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < $length; $i++ )  {
        $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}
function u_xml_to_array($xmlstring) {	
    return ( array ) simplexml_load_string ( $xmlstring, 'SimpleXMLElement', LIBXML_NOCDATA );
}
function u_get_signature($arrdata,$key1,$method = "MD5") {
    // API密钥：9abc1922545f0e27ccc4c7191b96c56f	
	if(!$key1)
		$key1=C('config.UCHANGPAY_PASS');
    if (! function_exists ( $method ))
        return false;
    ksort ( $arrdata );
    $paramstring = "";
    foreach ( $arrdata as $key => $value ) {
        if (strlen ( $paramstring ) == 0)
            $paramstring .= $key . "=" . $value;
        else
            $paramstring .= "&" . $key . "=" . $value;
    }
	$paramstring .= "&key=" . $key1;
	
    $Sign = $method ( $paramstring );
	//dump('sign==='.$Sign);
    $Sign=strtoupper($Sign);
	
	
	
    return $Sign;

}

	/**
	 * 	作用：生成签名
	 */
function u_getSign($Obj)
	{	
	
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		//dump($Parameters);
		$String = u_formatBizQueryParaMap($Parameters, false);
		//dump($String);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".C('config.UCHANGPAY_PASS');
		//dump($String);
		//echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = MD5($String);
				//dump($String);
		//echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		//echo "【result】 ".$result_."</br>";
		return $result_;
	}
	
		/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
function u_formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}

/**
 * 	作用：array转xml
 */
function u_arrayToXml($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key=>$val)
    {
        if (is_numeric($val))
        {
            $xml.="<".$key.">".$val."</".$key.">";

        }
        else
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    }
    $xml.="</xml>";
//         logdebug($xml);
    return $xml;
}
function u_curl_post_ssl($url, $vars, $second = 30, $aHeader = array()) {
    $ch = curl_init ();
    // 超时时间
    curl_setopt ( $ch, CURLOPT_TIMEOUT, $second );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    // 这里设置代理，如果有的话
    // curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
    // curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );



    if (count ( $aHeader ) >= 1) {
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $aHeader );
    }

    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $vars );
    $data = curl_exec ( $ch );
    if ($data) {
        curl_close ( $ch );
        return $data;
    } else {
        $error = curl_errno ( $ch );
        echo "call faild, errorCode:$error\n";
        curl_close ( $ch );
        return false;
    }
}
?>