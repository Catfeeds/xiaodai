<?php

/**
 * 调试函数：输出并停止
 * @param string $str
 */
function we($str = '') {
	header("Content-type: text/html; charset=utf-8"); 
	dump ( $str );
	exit ();
}

/**
 * 调试日志
 *
 * @param unknown $text        	
 */
function logdebug($text) {
	if(is_array($text)){
		$text=json_encode($text);
	}
	file_put_contents ( 'log.txt', $text . "\n", FILE_APPEND );
}
/**
 * 清除缓存
 * @return boolean
 */
function clr_cache(){
    if (file_exists ( RUNTIME_PATH)) {
         
        $ctrl=new \Org\Net\File();
        $ctrl->unlinkDir(RUNTIME_PATH);
    }
    return true;
}
/**
 * 清除html
 *
 * @param string $str        	
 * @param number $len        	
 * @return string
 */
function htmlclr($str = '', $len = 0) {
	$str = strip_tags ( $str );
	$str = str_replace ( "\n", "", $str );
	if ($len) {
		$str = cut_str ( $str, 0, $len );
	}
	$str = trim ( $str );
	return $str;
}

/**
 * 判断请求是否为空:特点-0为false
 */
function isN($str = null) {
	if (isset ( $str )) {
		if (strlen ( $str ) > 0)
			return false; // 是否是字符串类型
	}
	if (empty ( $str ))
		return true; // 是否已设定
	if ($str == '')
		return true; // 是否为空
	return true;
}

/**
 * 转换为货币格式:0->0.00
 *
 * @param unknown $num        	
 * @return string
 */
function to_price($num = 0) {
	if (! is_numeric ( $num )) {
		$num = 0;
	}
	$num = sprintf ( "%01.2f", $num );
	return $num;
}

/**
 * 转换为货币格式:0->0.00
 *
 * @param unknown $num
 * @return string
 */
function to_price_cent($num = 0) {
	if (! is_numeric ( $num )) {
		$num = 0;
	}
	$num=$num/100;
	$num = sprintf ( "%01.2f", $num );
	return $num;
}


/**
 * 转换为百分比:0->0.00%
 *
 * @param number $num        	
 * @return string
 */
function to_percent($num = 0) {
	if (! is_numeric ( $num )) {
		$num = 0;
	}
	$num = sprintf ( "%01.2f", $num * 100 ) . '%';
	return $num;
}

/**
 * 字符串截取：默认20个字符
 *
 * @param string $str        	
 * @param number $start        	
 * @param number $len        	
 * @param string $ext        	
 * @return string
 */
function cut_str($str = null, $start = 0, $len = 20, $ext = '...') {
	if (strlen ( $str ) > $len) {
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all ( $pa, $str, $t_string );
		if (count ( $t_string [0] ) - $start >= $len) {
			$str = join ( '', array_slice ( $t_string [0], $start, $len ) );
			$str .= $ext;
		}
	}
	return $str;
}

/**
 * 创建多层目录
 *
 * @param string $dirs        	
 * @param number $mode        	
 * @return boolean
 */
function mkdirs($dirs = '', $mode = 0777) {
	if (! is_dir ( $dirs )) {
		mkdirs ( dirname ( $dirs ), $mode );
		$ret = @mkdir ( $dirs, $mode );
		chmod ( $dirs, $mode );
		return $ret;
	}
	return true;
}

/**
 * 不存在就新建文件
 */
function create_file($l1, $l2 = '') {
	$l1 = str_replace ( '//', '/', $l1 );
	if (! file_exists ( $l1 )) {
		write_file ( $l1, $l2 );
		return true;
	} else {
		return true;
	}
}

/**
 * 生成文件并写入内容，自动创建多层目录
 *
 * @param string $filename
 *        	相对路径: ./1/2/3.html
 * @param string $content        	
 */
function write_file($filename = '', $content = '') {
	$dir = dirname ( $filename );
	if (! is_dir ( $dir )) {
		mkdirs ( $dir );
	}
	$file = @file_put_contents ( $filename, $content );
	chmod ( $filename, 0777 );
	return $file;
}

/**
 * 写入数组到文件
 *
 * @param string $filename        	
 * @param string $arr        	
 */
function arr2file($filename = '', $arr = '') {
	if (is_array ( $arr )) {
		// 数组转字符串
		$con = var_export ( $arr, true );
	} else {
		$con = $arr;
	}
	$con = "<?php\nreturn $con;\n?>";
	write_file ( $filename, $con );
}

/**
 * 数组清洁工作:注意会把0抹掉！
 *
 * @param unknown $arr        	
 * @return multitype:
 */
function arr2clr($arr = null) {
	$arr = array_filter ( $arr );
	$arr = array_unique ( $arr );
	return $arr;
}

/**
 * 字符串转换成数组：默认以逗号分割
 *
 * @param string $str        	
 * @param string $glue        	
 * @return multitype:
 */
function str2arr($str = '', $glue = ',') {
	return explode ( $glue, $str );
}

/**
 * 数组转换成字符串：默认以逗号分割
 *
 * @param string $arr        	
 * @param string $glue        	
 * @return string
 */
function arr2str($arr = null, $glue = ',') {
	return implode ( $glue, $arr );
}

/**
 * 二维数组根据指定键值排序
 *
 * @param string $arr        	
 * @param string $keys        	
 * @param string $type        	
 * @return multitype:string
 */
function array_sort($arr = null, $keys = '', $type = 'asc') {
	$keysvalue = $new_array = array ();
	foreach ( $arr as $k => $v ) {
		$keysvalue [$k] = $v [$keys];
	}
	if ($type == 'asc') {
		asort ( $keysvalue );
	} else {
		arsort ( $keysvalue );
	}
	reset ( $keysvalue );
	foreach ( $keysvalue as $k => $v ) {
		$new_array [$k] = $arr [$k];
	}
	return $new_array;
}

/**
 * 数字时间转换成友好可读形式
 *
 * @param string $time        	
 * @param string $format        	
 * @return string
 */
function time_format($time = NULL, $format = 'Y-m-d H:i:s') {
	$time = $time === NULL ? NOW_TIME : intval ( $time );
	return date ( $format, $time );
}

/**
 * 日期格式转换
 *
 * @param unknown $date        	
 * @param string $format        	
 */
function date2format($date = '', $format = 'Y-m-d') {
	if ($date == '') {
		return '';
	} else {
		if (is_number ( $date )) {
			return date ( $format, $date );
		} else {
			return time_format ( strtotime ( $date ), $format );
		}
	}
}

/**
 * 字节格式化
 *
 * @param number $size        	
 * @param string $delimiter        	
 * @return string
 */
function format_bytes($size = 0, $delimiter = '') {
	$units = array (
			'B',
			'KB',
			'MB',
			'GB',
			'TB',
			'PB' 
	);
	for($i = 0; $size >= 1024 && $i < 5; $i ++)
		$size /= 1024;
	return round ( $size, 2 ) . $delimiter . $units [$i];
}

/**
 * 解析配置:键=>值，如：a:名称1,b:名称2，返回数组形式
 *
 * @param string $string        	
 * @return Ambigous <multitype:, multitype:multitype: >
 */
function parse_config($string = '') {
	if (0 === strpos ( $string, ':' )) {
		// 采用函数定义
		return eval ( substr ( $string, 1 ) . ';' );
	}
	$array = preg_split ( '/[,;\r\n]+/', trim ( $string, ",;\r\n" ) );
	if (strpos ( $string, ':' )) {
		$value = array ();
		foreach ( $array as $val ) {
			list ( $k, $v ) = explode ( ':', $val );
			$value [$k] = $v;
		}
	} else {
		$value = $array;
	}
	return $value;
}

/**
 * 生成随机订单号
 */
function get_order_no() {
	return date('ymd',time()).random_chars(4);
}


function random_chars($length = 4)
{
	$str = strtoupper(substr(uniqid(sha1(time())),0,$length));
	while(strpos($str, 'E') !== false) {
		$str = strtoupper(substr(uniqid(sha1(time())),0,$length));
	}
	return $str;
}

/**
 * 提取编辑器里的图片，返回数组
 *
 * @param string $content        	
 */
function get_imgs($content = '') {
	$pattern = '/<img.*?src=\s*?"?([^"\s]+)(?!\/>)"?\s*?/is';
	preg_match_all ( $pattern, $content, $matches );
	return ($matches [1]);
}

/**
 * 根据url获取图片
 *
 * @param string $url        	
 */
function get_url_img($url = '') {
	if ($url != '') {
		$userid = get_memberid ();
		if (! $userid) {
			$userid = 0;
		}
		$filepath = str_replace ( '/user/', '/panel/', C ( 'DOWNLOAD_UPLOAD.rootPath' ) ) . $userid . '/' . date ( 'Y-m-d' ) . '/';

		write_file ( $filepath . 'lists.html' );
		$fileext = pathinfo ( $url, PATHINFO_EXTENSION );

		if ($fileext == '') {
			$fileext = 'jpg';
		}
		$filename = date ( 'His' ) . rand ( 1000, 2000 );
		$savename = $filepath . $filename . '.' . $fileext;
		
		//$url=str_replace('https://','http://',$url);
		//dump(strpos($url,'wx.qlogo.cn'));
		//if(strpos($url,'wx.qlogo.cn')){
			$url=str_replace('http://','https://',$url);
		//}
		
		//dump($url);
		
		$img = file_get_contents( $url );
		//dump($img);die;
		file_put_contents ( $savename, $img );
		return $savename;
	} else {
		return false;
	}
}

/**
 * 生成缩略图
 *
 * @param string $pic        	
 * @param string $w        	
 * @param string $h        	
 */
// get_thumb('/Public/uploadfile/remote/2014-03-01/201403011449371576.jpg');
function get_thumb($pic = null, $w = 120, $h = 100) {
	// $pic=$_SERVER["DOCUMENT_ROOT"].$pic;

	$pic = str_replace ( './Public/', 'Public/', $pic );

	$returl = $pic;
	$thumburl = str_replace ( 'Public/', 'Public/thumbs/', $pic );

	$thumburl = str_replace ( '.', '_' . $w . 'x' . $h . '.', $thumburl );

	$furl = file_exists ( $thumburl );

	if ($furl) {
		$returl = $thumburl;
	} else {

		if (file_exists ( $pic )) {

			mkdirs ( dirname ( $thumburl ) );

			$ctrl = new \Think\Image ( 1, $pic );
			$img = $ctrl->thumb ( $w, $h, 3 )->save ( $thumburl );

			$returl = $thumburl;
		}
	}
	$returl = str_replace ( 'Public/', '/Public/', $returl );
	return $returl;
}

/**
 * 数组转换成CSV格式
 *
 * @param string $list        	
 * @param string $coding        	
 * @param string $header        	
 * @param string $csvfile        	
 */
function list_to_csv($list = null, $coding = 'gbk', $header = '', $csvfile = '') {
	if ($csvfile == '') {
		$csvfile = get_order_no ();
	}
	
	if ($header == '') {
		if (count ( $list ) > 0) {
			$header [] = array_keys ( $list [0] );
		}
	}
	$list = array_merge ( $header, $list );
	// echo(chr(0xEF).chr(0xBB).chr(0xBF));
	
	$content = generateCsv ( $list );
	if ($coding == 'utf-8') {
		header ( "Content-Type:APPLICATION/OCTET-STREAM" );
	} else {
		$content = iconv ( "UTF-8", "UTF-16LE", $content );
		$content = "\xFF\xFE" . $content; // 添加BOM
		header ( "Content-type: text/csv;charset=UTF-16LE" );
	}
	header ( "Content-Disposition: attachment; filename=$csvfile.csv" );
	echo ($content);
	exit ();
}

/**
 * 生成csv文件
 *
 * @param unknown $data        	
 * @param string $delimiter        	
 * @param string $enclosure        	
 * @return string
 */
function generateCsv($data, $delimiter = "\t", $enclosure = '"') {
	$handle = fopen ( 'php://temp', 'r+' );
	foreach ( $data as $k=>$line1 ) {
		$lines=array();
		$lines[]=array($k);
		$lines[]=array("店名","地址","会员数","分享数");
		$lines=array_merge($lines,$line1);
		foreach ( $lines as $line ) {
		fputcsv ( $handle, $line, $delimiter, $enclosure );
		}
	}
	rewind ( $handle );
	while ( ! feof ( $handle ) ) {
		$contents .= fread ( $handle, 8192 );
	}
	fclose ( $handle );
	return $contents;
}

/**
 * 发送HTTP请求方法，目前只支持CURL发送请求，供微信接口使用
 *
 * @param string $url
 *        	请求URL
 * @param array $params
 *        	请求参数
 * @param string $method
 *        	请求方法GET/POST
 * @return array $data 响应数据
 */
function http($url, $params = array(), $method = 'GET', $header = array(), $multi = false) {
	$opts = array (
			CURLOPT_TIMEOUT => 30,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_HTTPHEADER => $header 
	);
	
	/* 根据请求类型设置特定参数 */
	switch (strtoupper ( $method )) {
		case 'GET' :
			$opts [CURLOPT_URL] = $url . '?' . http_build_query ( $params );
			break;
		case 'POST' :
			
			// 判断是否传输文件
			// $params = $multi ? $params : http_build_query($params);
			$opts [CURLOPT_URL] = $url;
			$opts [CURLOPT_POST] = 1;
			$opts [CURLOPT_POSTFIELDS] = $params;
			
			if (stripos ( $url, "https://" ) !== FALSE) {
				$opts [CURLOPT_SSL_VERIFYPEER] = false;
				$opts [CURLOPT_SSL_VERIFYHOST] = false;
				$opts [CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1;
			}
			
			break;
		default :
			throw new Exception ( '不支持的请求方式！' );
	}
	/* 初始化并执行curl请求 */
	$ch = curl_init ();
	curl_setopt_array ( $ch, $opts );
	$data = curl_exec ( $ch );
	$error = curl_error ( $ch );
	curl_close ( $ch );
	if ($error)
		throw new Exception ( '请求发生错误：' . $error );
	return $data;
}

/**
 * 设置参数值
 *
 * @param string $p
 * @param string $v
 * @return Ambigous <string, mixed>
 */
function set_url($p = '', $v = '') {
	$param = $_GET;
	unset ( $param [C('DOMAIN_PARAM')] );
	$param = array_change_key_case ( $param, CASE_LOWER );
	if (is_array ( $p )) {
		foreach ( $p as $k1 => $v1 ) {
			if ($v1 != '') {
				$v1 = strtolower ( $v1 );
			}
			$param [$k1] = $v1;
		}
	} else {
		$p = strtolower ( $p );
		if ($v != '') {
			$v = strtolower ( $v );
		}
		$param [$p] = $v;
	}
	return U ( CONTROLLER_NAME . '/' . ACTION_NAME, $param );
}


/**
 * 参数转换：防止URL Rewrite模式解析不正常
 *
 * @param string $str        	
 * @param string $de        	
 * @return Ambigous <string, mixed>
 */
function parse_param($str = '', $de = false) {
	if ($de) {
		$str = str_replace ( '_', ' ', $str );
		$str = str_replace ( '@', "'", $str );
	} else {
		$str = str_replace ( ' ', '_', $str );
		$str = str_replace ( "'", '@', $str );
	}
	return $str;
}

/**
 * api接口密码，每天变化
 */
function get_api_code() {
	return '123';
	// return md6(date('Y-m-d'));
}

/**
 * 密码加密方式
 * @param unknown $str
 * @return string
 */
function md5pwd($str){
	$key='yourkey';
	return to_guid_string($str.$key);
}

/**
 * 等长的加解密函数
 *
 * @param string $str        	
 * @param string $de        	
 * @return Ambigous <string, mixed>
 */
function md6($str = '', $de = false) {
	$key = ('yourkey');
	$char = ('MDAwMDAwMDAwM');
	if ($str != '') {
		if ($de) {
			$str = $char . $str;
			$str = think_decrypt ( $str, $key );
		} else {
			$str = think_encrypt ( $str, $key );
			$str = str_replace ( $char, '', $str );
		}
	}
	return $str;
}

/**
 * 系统加密方法
 *
 * @param string $data
 *        	要加密的字符串
 * @param string $key
 *        	加密密钥
 * @param int $expire
 *        	过期时间 单位 秒
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_encrypt($data, $key = '', $expire = 0) {
	$key = md5 ( empty ( $key ) ? C ( 'DATA_AUTH_KEY' ) : $key );
	$data = base64_encode ( $data );
	$x = 0;
	$len = strlen ( $data );
	$l = strlen ( $key );
	$char = '';
	
	for($i = 0; $i < $len; $i ++) {
		if ($x == $l)
			$x = 0;
		$char .= substr ( $key, $x, 1 );
		$x ++;
	}
	
	$str = sprintf ( '%010d', $expire ? $expire + time () : 0 );
	
	for($i = 0; $i < $len; $i ++) {
		$str .= chr ( ord ( substr ( $data, $i, 1 ) ) + (ord ( substr ( $char, $i, 1 ) )) % 256 );
	}
	return str_replace ( array (
			'+',
			'/',
			'=' 
	), array (
			'-',
			'_',
			'' 
	), base64_encode ( $str ) );
}

/**
 * 系统解密方法
 *
 * @param string $data
 *        	要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param string $key
 *        	加密密钥
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_decrypt($data, $key = '') {
	$key = md5 ( empty ( $key ) ? C ( 'DATA_AUTH_KEY' ) : $key );
	$data = str_replace ( array (
			'-',
			'_' 
	), array (
			'+',
			'/' 
	), $data );
	$mod4 = strlen ( $data ) % 4;
	if ($mod4) {
		$data .= substr ( '====', $mod4 );
	}
	$data = base64_decode ( $data );
	$expire = substr ( $data, 0, 10 );
	$data = substr ( $data, 10 );
	
	if ($expire > 0 && $expire < time ()) {
		return '';
	}
	$x = 0;
	$len = strlen ( $data );
	$l = strlen ( $key );
	$char = $str = '';
	
	for($i = 0; $i < $len; $i ++) {
		if ($x == $l)
			$x = 0;
		$char .= substr ( $key, $x, 1 );
		$x ++;
	}
	
	for($i = 0; $i < $len; $i ++) {
		if (ord ( substr ( $data, $i, 1 ) ) < ord ( substr ( $char, $i, 1 ) )) {
			$str .= chr ( (ord ( substr ( $data, $i, 1 ) ) + 256) - ord ( substr ( $char, $i, 1 ) ) );
		} else {
			$str .= chr ( ord ( substr ( $data, $i, 1 ) ) - ord ( substr ( $char, $i, 1 ) ) );
		}
	}
	return base64_decode ( $str );
}

/**
 * 是否是手机号码
 *
 * @param string $phone        	
 * @return boolean
 */
function is_mobile($phone) {
	if (strlen ( $phone ) != 11 || ! preg_match ( '/^1[3|4|5|7|8][0-9]\d{4,8}$/', $phone )) {
		return false;
	} else {
		return true;
	}
}

/**
 * 验证电话号码，支持手机号、固话、400电话号码
 *
 * @param type $tel        	
 * @param type $type        	
 * @return boolean
 */
function isTel($tel, $type = '') {
	$regxArr = array (
			'mobile' => '/^(\+?86-?)?(18|15|13)[0-9]{9}$/',
			'tel' => '/^(010|02\d{1}|0[3-9]\d{2})-\d{7,9}(-\d+)?$/',
			'400' => '/^400(-\d{3,4}){2}$/',
			'other' => '/^\d{3,7}$/' 
	);
	if ($type && isset ( $regxArr [$type] )) {
		return preg_match ( $regxArr [$type], $tel ) ? true : false;
	}
	foreach ( $regxArr as $regx ) {
		if (preg_match ( $regx, $tel )) {
			return true;
		}
	}
	return false;
}

/**
 * 验证字符串是否为数字,字母,中文和下划线构成
 *
 * @param string $username        	
 * @return bool
 */
function is_check_string($str) {
	if (preg_match ( '/^[\x{4e00}-\x{9fa5}\w_]+$/u', $str )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 是否为一个合法的email
 *
 * @param sting $email        	
 * @return boolean
 */
function is_email($email) {
	if (filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 是否为一个合法的url
 *
 * @param string $url        	
 * @return boolean
 */
function is_url($url) {
	if (filter_var ( $url, FILTER_VALIDATE_URL )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 是否为一个合法的ip地址
 *
 * @param string $ip        	
 * @return boolean
 */
function is_ip($ip) {
	if (ip2long ( $ip )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 是否为整数
 *
 * @param int $number        	
 * @return boolean
 */
function is_number($number) {
	if (preg_match ( '/^[-\+]?\d+$/', $number )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 是否为小数
 *
 * @param float $number        	
 * @return boolean
 */
function is_decimal($number) {
	if (preg_match ( '/^[-\+]?\d+(\.\d+)?$/', $number )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 判断是否为图片
 *
 * @param string $file        	
 * @return boolean
 */
function is_image($file) {
	if (file_exists ( $file ) && getimagesize ( $file === false )) {
		return false;
	} else {
		return true;
	}
}

/**
 * 是否为合法的身份证(支持15位和18位)
 *
 * @param string $card        	
 * @return boolean
 */
function is_card($card) {
	
	
	$vCity = array(
			'11','12','13','14','15','21','22',
			'23','31','32','33','34','35','36',
			'37','41','42','43','44','45','46',
			'50','51','52','53','54','61','62',
			'63','64','65','71','81','82','91'
	);
	if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $card)) return false;
	if (!in_array(substr($card, 0, 2), $vCity)) return false;
	$card = preg_replace('/[xX]$/i', 'a', $card);
	$vLength = strlen($card);
	if ($vLength == 18) {
		$vBirthday = substr($card, 6, 4) . '-' . substr($card, 10, 2) . '-' . substr($card, 12, 2);
	} else {
		$vBirthday = '19' . substr($card, 6, 2) . '-' . substr($card, 8, 2) . '-' . substr($card, 10, 2);
	}
	if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
	if ($vLength == 18) {
		$vSum = 0;
		for ($i = 17 ; $i >= 0 ; $i--) {
			$vSubStr = substr($card, 17 - $i, 1);
			$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
		}
		if($vSum % 11 != 1) return false;
	}
	return true;

	
	//if (preg_match ( '/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/', $card ) || preg_match ( '/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/', $card ))
	//	return true;
	//else
	//	return false;
}

/**
 * 验证日期格式是否正确
 *
 * @param string $date        	
 * @param string $format        	
 * @return boolean
 */
function is_date($date, $format = 'Y-m-d') {
	$t = date_parse_from_format ( $format, $date );
	if (empty ( $t ['errors'] )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 发送邮件
 *
 * @param string $to        	
 * @param string $subject        	
 * @param string $body        	
 */
function send_mail($to = null, $subject = null, $body = null) {
	// 需要先开启邮件接口
	if (C ( 'config.WEB_SITE_EMAIL' )) {
		$data ['subject'] = $subject; // 邮件标题
		$data ['body'] = $body; // 邮件正文内容
		                        // $mail->debug=true;
		if (is_array ( $to )) {
			$to = arr2clr ( $to );
			foreach ( $to as $k => $v ) {
				if (! is_email ( $v )) {
					return false;
				}
				$data ['mailto'] = $v; // 收件人
				                       // $sent = $mail->send ( $data );
				$sent = getSendApi ( $data );
			}
			return $sent;
		} else {
			if (! is_email ( $to )) {
				return false;
			}
			$data ['mailto'] = $to; // 收件人
			                        // return $mail->send ( $data );
			return getSendApi ( $data );
		}
	} else {
		return false;
	}
}
function getSendApi($data) {
	$baseurl = get_base_domain ();
	$apiurl = ltrim ( trim ( $baseurl, "/" ), "http://" );
	$param = $data;
	$aPOST = array ();
	foreach ( $param as $key => $val ) {
		$aPOST [] = $key . "=" . ($val);
	}
	$strPOST = join ( "&", $aPOST );
	
	$url = 'http://' . $apiurl . '/Member/mail/';
	sock_post ( $url, $strPOST );
	return true;
}
function sock_post($url, $query) {
	$info = parse_url ( $url );
	$fp = fsockopen ( $info ["host"], 80, $errno, $errstr, 30 );
	stream_set_blocking ( $fp, 0 );
	$head = "POST " . $info ['path'] . "?" . $info ["query"] . " HTTP/1.0\r\n";
	$head .= "Accept: */*\r\n";
	$head .= "Host: " . $info ['host'] . "\r\n";
	$head .= "Referer: http://" . $info ['host'] . $info ['path'] . "\r\n";
	$head .= "Content-type: application/x-www-form-urlencoded\r\n";
	$head .= "Content-Length: " . strlen ( trim ( $query ) ) . "\r\n";
	$head .= "\r\n";
	$head .= trim ( $query );
	
	fwrite ( $fp, $head );
	fclose ( $fp );
	// $write = fputs($fp, $head);
	// while (!feof($fp))
	// {
	// $line = fread($fp,4096);
	// echo $line;
	// }
}

/**
 * 发送手机短信
 *
 * @param string $to        	
 * @param string $content
 *        	:array(1234,1)
 */
function send_sms($to = null, $content = null) {
	// 需要先开启邮件接口
	if (C ( 'config.WEB_SITE_SMS' )) {
		if (! $content) {
			return false;
		}

		if (! is_mobile ( $to )) {
			return false;
		}

		$url = C ( 'config.SMS_API_URL' );
		$username = C ( 'config.SMS_USERNAME' );
		$userpwd = C ( 'config.SMS_USERPWD' );
		$template = C ( 'config.WEB_TPL_CODE' );
		$template = str_replace ( '{$code}', $content, $template );
		$template = mb_convert_encoding ( $template, 'GB2312', 'UTF-8' );
		$apiurl = $url . '?CorpID=' . $username . '&Pwd=' . $userpwd . '&Mobile=' . $to . '&Content=' . ($content);
		$apiurl = $url;
		$param ['CorpID'] = $username;
		$param ['Pwd'] = $userpwd;
		$param ['Mobile'] = $to;
		$param ['Content'] = $template;
		$param ['Cell'] = '';
		$param ['SendTime'] = '';
		$result = http ( $apiurl, $param );
		$result = (json_decode ( $result, true ));

		if ( $result > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


/**
 * 发送手机非验证码短信
 *
 * @param string $to
 * @param string $content
 *        	:array(1234,1)
 */
function send_tip_sms($to = null, $content = null) {
	// 需要先开启邮件接口
	if (C ( 'config.WEB_SITE_SMS' )) {
		if (! $content) {
			return false;
		}

		if (! is_mobile ( $to )) {
			return false;
		}

		$url = C ( 'config.SMS_API_URL' );
		$username = C ( 'config.SMS_USERNAME' );
		$userpwd = C ( 'config.SMS_USERPWD' );
		$template = $content;
		$template = mb_convert_encoding ( $template, 'GB2312', 'UTF-8' );
		$apiurl = $url;
		$param ['CorpID'] = $username;
		$param ['Pwd'] = $userpwd;
		$param ['Mobile'] = $to;
		$param ['Content'] = $template;
		$param ['Cell'] = '';
		$param ['SendTime'] = '';
		$result = http ( $apiurl, $param );
		$result = (json_decode ( $result, true ));

		if ( $result > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


/*--------------------------------2015-12-08 新增 start--------------------------------------*/
/**
 * 发送短信
 *
 * @param number $type:1-验证码，2-订单通知
 * @param string $to
 * @param string $code
 *        	验证码或订单号
 * @param string $text
 *        	订单状态
 */
function api_send_sms($type = 1, $to = '', $code = '', $text = '') {
	// 短信内容
	if ($type == 3) {
		// 退保证金
		$template = C ( 'config.WEB_TPL_BAIL' );
		$template = str_replace ( '{$amount}', $code, $template );
	} else if ($type == 2) {
		// 找回密码
		$template = C ( 'config.WEB_TPL_FINDPWD' );
		$template = str_replace ( '{$code}', $code, $template );
	} else {
		// 验证码
		$template = C ( 'config.WEB_TPL_CODE' );
		$template = str_replace ( '{$code}', $code, $template );
	}

	//$template .= '【' . C ( 'config.WEB_SITE_TITLE' ) . '】';

	$send = api_sms ( $to, $template );

	return $send;
}

/**
 * 短信接口
 *
 * @param string $to
 * @param string $content
 */
function api_sms($to = '', $content = '') {
	if (C ( 'config.WEB_SITE_SMS' )) {
		$url = C ( 'config.SMS_API_URL' );
		$username = C ( 'config.SMS_USERNAME' );
		$userpwd = C ( 'config.SMS_USERPWD' );
		$content = rawurlencode ( mb_convert_encoding ( $content, 'GB2312', 'UTF-8' ) );
		$apiurl = $url . '?CorpID=' . $username . '&Pwd=' . $userpwd . '&Mobile=' . $to . '&Content=' . ($content);
		logdebug($apiurl);
		$result = file_get_contents ( $apiurl );
		return $result;
	}
}



// 以下是Thinkphp专用

/**
 * 自定义标签调用|TP专用
 *
 * @param unknown $name        	
 * @param string $value        	
 * @return Ambigous <mixed, void, boolean>|string
 */
function lbl($name, $value = null) {
	$cachename = 'label_' . $name;
	$cache = S ( $cachename );
	if ($value == null) {
		if (! $cache) {
			$where = array ();
			$where ['status'] = 1;
			$where ['name'] = $name;
			$db = M ( 'label' )->where ( $where )->find ();
			if ($db) {
				$cache = $db ['info'];
				S ( $cachename, $cache );
			}
		}
	} else {
		S ( $cachename, $value );
		$cache = $value;
	}
	
	return $cache;
}

/**
 * 取验证码，返回验证码图片|TP专用
 */
function get_verify() {
	$config = array (
			'codeSet' => '2345678',
			'length' => 4 
	)
    // 'fontSize' => 18,
    // 'imageH' => 40,
    // 'imageW' => 100,
    ;;

	;
	
	ob_clean ();
	$verify = new \Think\Verify ( $config );
	$verify->entry ( 1 );
}

/**
 * 检测验证码|TP专用
 *
 * @param integer $id
 *        	验证码ID
 * @return boolean 检测结果
 */
function check_verify($code, $id = 1) {
	$verify = new \Think\Verify ();
	return $verify->check ( $code, $id );
}

/**
 * 时间差|TP专用
 *
 * @param string $date1        	
 * @param string $date2        	
 * @param string $elaps        	
 */
function get_date_diff($date1 = '', $date2 = '', $elaps = "d") {
	$ctrl = new \Org\Util\Date ( $date1 );
	$df = $ctrl->dateDiff ( $date2, $elaps );
	return $df;
}

/**
 * 时间差：友好显示|TP专用
 *
 * @param string $date1        	
 * @param string $date2        	
 * @return string
 */
function get_time_diff($date1 = '', $date2 = '') {
	$ctrl = new \Org\Util\Date ( $date1 );
	$df = $ctrl->timeDiff ( $date2 );
	return $df;
}

/**
 * 时间增加|TP专用
 *
 * @param string $date1        	
 * @param number $num        	
 * @param string $elaps        	
 * @return string
 */
function get_date_add($date1 = '', $num = 0, $elaps = 'd') {
	$ctrl = new \Org\Util\Date ( $date1 );
	$da = $ctrl->dateAdd ( $num, $elaps )->format ();
	return $da;
}

/**
 * 生成随机字符串/数字|TP专用
 *
 * @param number $len        	
 * @param string $type：0
 *        	字母 1 数字 其它 混合
 * @param string $addChars        	
 * @return string
 */
function rand_str($len = 6, $type = '', $addChars = '') {
	$ctrl = new \Org\Util\String ();
	$str = $ctrl->randString ( $len, $type, $addChars );
	return $str;
}

/**
 * 把返回的数据集转换成Tree
 * access public
 *
 * @param array $list
 *        	要转换的数据集
 * @param string $pid
 *        	parent标记字段
 * @param string $level
 *        	level标记字段
 *        	return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
	// 创建Tree
	$tree = array ();
	if (is_array ( $list )) {
		// 创建基于主键的数组引用
		$refer = array ();
		foreach ( $list as $key => $data ) {
			$refer [$data [$pk]] = & $list [$key];
		}
		foreach ( $list as $key => $data ) {
			// 判断是否存在parent
			$parentId = $data [$pid];
			if ($root == $parentId) {
				$tree [] = & $list [$key];
			} else {
				if (isset ( $refer [$parentId] )) {
					$parent = & $refer [$parentId];
					$parent [$child] [] = & $list [$key];
				}
			}
		}
	}
	return $tree;
}

/**
 * 省市区联动
 *
 * @param string $tbl        	
 * @param string $id        	
 * @param number $cid        	
 * @return string
 */
function get_area($tbl = '', $id = '', $cid = 0) {
	$tblname = 'pcd';
	$where = array ();
	$html = '';
	$html .= '<option value="">--请选择--</option>';
	if (is_number ( $id )) {
		$where ['pid'] = $id;
		
		$db = M ( $tblname )->where ( $where )->cache ( true )->select ();
		if ($db) {
			foreach ( $db as $key => $value ) {
				if ($cid == $value ['code']) {
					$html .= '<option value="' . $value ['code'] . '" selected="selected">' . $value ['name'] . '</option>';
				} else {
					$html .= '<option value="' . $value ['code'] . '">' . $value ['name'] . '</option>';
				}
			}
		} else {
			if ($id) {
				$html = '';
			}
		}
	}
	return $html;
}

/**
 * 省市区联动
 *
 * @param string $tbl        	
 * @param string $id        	
 * @param number $cid        	
 * @return string
 */
function get_area_id($tbl = 'china_province', $name = '') {
	$html = M ( 'pcd' )->where ( array (
			'name' => $name 
	) )->cache ( true )->getField ( 'code' );
	return $html;
}

/**
 * 省市区联动
 *
 * @param string $tbl        	
 * @param string $id        	
 * @param number $cid        	
 * @return string
 */
function get_area_name($id = 0) {
	$html = M ( 'pcd' )->where ( array (
			'code' => $id 
	) )->cache ( true )->getField ( 'name' );
	return $html;
}

/**
 * 获取当前区域名称，上推
 *
 * @param number $id        	
 * @param string $all        	
 * @return Ambigous <\Think\mixed, NULL, mixed, unknown, multitype:Ambigous <unknown, string> unknown , object>
 */
function get_pcds_name($id = 0, $all = false) {
	$tblname = 'pcds';
	if (! $all) {
		$html = M ( $tblname )->where ( array (
				'code' => $id 
		) )->cache ( true )->getField ( 'name' );
	} else {
		// 长度：2，4，6，9
		switch (strlen ( $id )) {
			case 2 :
				$html = M ( $tblname )->where ( array (
						'code' => $id 
				) )->cache ( true )->getField ( 'name' );
				break;
			case 4 :
				// 省
				$html = M ( $tblname )->where ( array (
						'code' => substr ( $id, 0, 2 ) 
				) )->cache ( true )->getField ( 'name' );
				// 市
				$html = $html . ' ' . M ( $tblname )->where ( array (
						'code' => $id 
				) )->cache ( true )->getField ( 'name' );
				break;
			case 6 :
				// 省
				$html = M ( $tblname )->where ( array (
						'code' => substr ( $id, 0, 2 ) 
				) )->cache ( true )->getField ( 'name' );
				// 市
				$html = $html . ' ' . M ( $tblname )->where ( array (
						'code' => substr ( $id, 0, 4 ) 
				) )->cache ( true )->getField ( 'name' );
				// 县
				$html = $html . ' ' . M ( $tblname )->where ( array (
						'code' => $id 
				) )->cache ( true )->getField ( 'name' );
				break;
			case 9 :
				// 省
				$html = M ( $tblname )->where ( array (
						'code' => substr ( $id, 0, 2 ) 
				) )->cache ( true )->getField ( 'name' );
				// 市
				$html = $html . ' ' . M ( $tblname )->where ( array (
						'code' => substr ( $id, 0, 4 ) 
				) )->cache ( true )->getField ( 'name' );
				// 县
				$html = $html . ' ' . M ( $tblname )->where ( array (
						'code' => substr ( $id, 0, 6 ) 
				) )->cache ( true )->getField ( 'name' );
				// 乡
				$html = $html . ' ' . M ( $tblname )->where ( array (
						'code' => $id 
				) )->cache ( true )->getField ( 'name' );
				break;
		}
	}
	return $html;
}

/**
 * 生成二维码
 *
 * @param unknown $content        	
 */
function get_qrcode($content = '', $size = 4, $file = true) {
	vendor ( "phpqrcode" );
	// 纠错级别：L、M、Q、H
	$level = 'M';
	// 点的大小：1到10,用于手机端4就可以了
	// $size = 4;
	// 空白边距
	$margin = 2;
	$QRcode = new \QRcode ();
	if ($file) {
		// 生成的路径和文件名
		$path = './Public/qrcode/';
		$filename = $path . md5 ( $content . '_' . $level . '_' . $size ) . '.png';
		if (! file_exists ( $filename )) {
			mkdirs ( $path );
			$QRcode::png ( $content, $filename, $level, $size, $margin );
		}
		$filename = str_replace ( './Public/', '/Public/', $filename );
		return $filename;
	} else {
		ob_clean ();
		$QRcode::png ( $content, false, $level, $size, $margin );
	}
}
