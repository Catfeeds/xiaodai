<?php

/**
 * Describe : CURL
 * @param string $url 访问地址
 * @param string $method 请求方式
 * @param string $params 请求参数
 * @param string $header 请求Header头
 * @param array  $auth 认证信息
 */
function get_curl($url,$method,$params=array(),$header='',$auth =array()) {

	$curl = curl_init();//初始化CURL句柄
	$timeout = 15;
	curl_setopt($curl, CURLOPT_URL, $url);//设置请求的URL
	#curl_setopt($curl, CURLOPT_HEADER, false);// 不要http header 加快效率
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

	$header[] = "Content-Type:application/json;charset=utf-8";
	if(!empty($header)){
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
	}

	curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout);//设置连接等待时间
	switch ($method){
		case "GET" :
			curl_setopt($curl, CURLOPT_HTTPGET, true);
			break;
		case "POST":
			if(is_array($params)){
				$params = json_encode($params,320);
			}
			#curl_setopt($curl, CURLOPT_POST,true);
			#curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			//设置提交的信息
			curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
			break;
		case "PUT" :
			curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params,320));
			break;
		case "DELETE":
			curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
			break;
	}

	if (!empty($auth) && isset($auth['username']) && isset($auth['password'])) {
		curl_setopt($curl, CURLOPT_USERPWD, "{$auth['username']}:{$auth['password']}");
	}

	$data = curl_exec($curl);//执行预定义的CURL
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);//获取http返回值
	curl_close($curl);
	$res = json_decode($data,true);
	return $res;

}


function sendmessagenotice($memberid,$tsr){
	$member=M('member')->where(array('id'=>$memberid))->find();
	$wechat=get_wechat_obj();
	$msg = array (
			'touser' => $member['openid'],
			'msgtype' => 'text',
			'text' => array (
					'content' => $tsr
			)
	);
	$wechat->sendCustomMessage ( $msg );
}

/**
 * 生成0~1随机小数
 * @param  Int   $min
 * @param  Int   $max
 * @return Float
 */
function randFloat($min=0, $max=1){
	return $min + mt_rand()/mt_getrandmax() * ($max-$min);
}


//已完成的客户状态值
function getcomplatestatus(){
	return M('member_status')->where(array('complate'=>1,'status'=>1))->getField('id');
}

function getfollowtype($type){
	$act="";
	switch($type){
		case 1:
			$act="跟进";
			break;
		case 2:
			$act="带看";
			break;
		case 3:
			$act="拜访";
			break;
		default:
			break;
	}
	return $act;
}

function getfollowmethod($method){
	$act="";
	switch($type){
		case 1:
			$act="电话";
			break;
		case 2:
			$act="微信";
			break;
		case 3:
			$act="短信";
			break;
		case 4:
			$act="上门拜访";
			break;
		case 5:
			$act="外访约见";
			break;
		case 6:
			$act="其他";
			break;
		default:
			break;
	}
	return $act;
}

function getworktype($type){
	$act="";
	switch($type){
		case 1:
			$act="短期";
			break;
		case 2:
			$act="周";
			break;
		case 3:
			$act="月";
			break;
		default:
			break;
	}
	return $act;
}

function imgTobase64 ($image_file) {
	$base64_image = '';
	$image_info = getimagesize($image_file);
	$image_data = fread(fopen($image_file, 'r'), filesize($image_file));
	$base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
	return $base64_image;
}

function base64Toimg($base64,$upPath="")
{
	//匹配出图片的格式
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {


		if (!$upPath) {
			$upPath = "/Public/uploadfile/file/images/";
		}

		if (!file_exists($upPath)) {
			//检查是否有该文件夹，如果没有就创建，并给予最高权限
			mkdir($upPath, 0700);
		}


		//获取扩展名和文件名
//		$streamFileType = $result[2];  //读取扩展名，如果你的程序仅限于画板上来的，那一定是png，这句可以直接streamFileType 赋值png

		$streamFileRand = rand(1111,9999). get_order_no();    //产生一个随机文件名（因为你base64上来肯定没有文件名，这里你可以自己设置一个也行）

		$streamFilename = '.' . $upPath  .$streamFileRand.'.png';

		//处理base64文本，用正则把第一个base64,之前的部分砍掉
		preg_match('/(?<=base64,)[\S|\s]+/', $base64, $streamForW);

		if (file_put_contents($streamFilename, base64_decode($streamForW[0])) === false) {
			return false;
		}
		else {
			return $upPath  .$streamFileRand.'.png';
		}


	}
}


/**
 * 发送微信通知消息
 */
function sendcrmmsg($staffid,$msg){
	if($staffid){
		$where=array();
		$where['id']=$staffid;
		$find=M('staff')->where($where)->find();
		if($find['openid']){
			$wechat=get_wechat_obj();
			$msg = array (
					'touser' => $find['openid'],
					'msgtype' => 'text',
					'text' => array (
							'content' => $msg
					)
			);
			$wechat->sendCustomMessage ( $msg );
		}
	}
}



function sendworkmsg(){
	$date=date('Y-m-d');
	$list=M('staff')->where(array('workdate'=>array('neq',$date),'noticedate'=>array('neq',$date)))->select();
	
	if(count($list)>0){
		$msgs="今日还没有提交工作计划哦";
		foreach($list as $k=>$v){
			$wechat=get_wechat_obj();
			$msg = array (
					'touser' => $v['openid'],
					'msgtype' => 'text',
					'text' => array (
							'content' => $msgs
					)
			);
			
			M("staff")->where(array('stffid'=>$v['id']))->setField('noticedate',$date);
			
			$wechat->sendCustomMessage ( $msg );
			
		}
	}
}


/**
 * 授权跳转
 */
function get_workauth_openid( $url = '') {
	
	$new_url = C ( 'BASE_URL' ) . 'Crm/Auth/?callback=' . urlencode ( $url );
	
	//dump($new_url);die;
	redirect ( $new_url );
}


function get_order_type($orderno){
	$arr=explode('-',$orderno);
	switch($arr[0]){
		case "C":
			$tblname="order";
			break;
		case "T":
			$tblname="trip_order";
			break;
		case "A":
			$tblname="class_order";
			break;
		case "D":
			$tblname="loan";
			break;
		default:
			$tblname="order";
			break;
	}
	return $tblname;
}

/**
 * 二维数组根据字段进行排序
 * @params array $array 需要排序的数组
 * @params string $field 排序的字段
 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 */
function arraySequence($array, $field, $sort = 'SORT_DESC')
{
	$arrSort = array();
	foreach ($array as $uniqid => $row) {
		foreach ($row as $key => $value) {
			$arrSort[$key][$uniqid] = $value;
		}
	}
	array_multisort($arrSort[$field], constant($sort), $array);
	return $array;
}


/**
 * 计算时间差
 * $day1:小时间；
 * $day2:大时间；
 */
function diffBetweenTwoDays ($day1, $day2)
{
	$second1 = strtotime($day1);
	$second2 = strtotime($day2);

	if ($second1 < $second2) {
		$tmp = $second2;
		$second2 = $second1;
		$second1 = $tmp;
	}
	return ($second1 - $second2) / 86400;
}


/**
 * 计算时间差
 * $day1:小时间；
 * $day2:大时间；
 */
function diffBetweenTwoDaysreal ($day1, $day2)
{
	$second1 = strtotime($day1);
	$second2 = strtotime($day2);

	return ($second2-$second1) / 86400;
}


/**
 * 数组排序
 */
function list_sort_by($list, $field, $sortby = 'asc')
{
	if (is_array($list))
	{
		$refer = $resultSet = array();
		foreach ($list as $i => $data)
		{
			$refer[$i] = &$data[$field];
		}
		switch ($sortby)
		{
			case 'asc': // 正向排序
				asort($refer);
				break;
			case 'desc': // 逆向排序
				arsort($refer);
				break;
			case 'nat': // 自然排序
				natcasesort($refer);
				break;
		}
		foreach ($refer as $key => $val)
		{
			$resultSet[] = &$list[$key];
		}
		return $resultSet;
	}
	return false;
}



function get_address($memberid=0){
	if(!$memberid){
		$memberid=get_memberid();
	}
	$where=array();
	$where['memberid']=$memberid;
	$order='isdefault desc,id desc';
	$list=M('address')->where($where)->order($order)->select();
	return $list;
}

function get_manageid(){
	$manageid=session('manageid');
	return $manageid;
}


function generateLocalPath($sExt) {
	$attachDir = str_replace ( '/file/', '/remote/', C ( 'DOWNLOAD_UPLOAD.rootPath' ) );
	$attachSubDir = date ( 'Y-m-d' );
	$newAttachDir = $attachDir . '/' . $attachSubDir . '/';

	PHP_VERSION < '4.2.0' && mt_srand ( ( double ) microtime () * 1000000 );
	$newFilename = date ( "YmdHis" ) . mt_rand ( 1000, 9999 ) . '.' . $sExt;
	$targetPath = $newAttachDir . '/' . $newFilename;
	return $targetPath;
}



/**
 * 正常返回
 * @param string $str
 */
function ok($str = '') {
	$ret ['status'] = 1;
	$ret ['info'] = $str;
	return $ret;
}

/**
 * 错误返回
 *
 * @param string $str        	
 */
function er($str = '') {
	$ret ['status'] = 0;
	$ret ['info'] = $str;
	return $ret;
}

/**
 * 随机不重复：按时间
 * @param number $len
 */
function rand_code($i='',$len=8){
	return substr( md5($i.'.'.microtime(true).rand(1000,9999)),32-$len,$len);
}

/**
 * 生成优惠券
 * @param string $title
 * @param number $num
 * @param number $cost
 * @param number $amount
 */
function create_coupon($pid='',$title='',$num=0,$cost=0,$amount=0){
	if(!is_number($pid)){
		return er('必须选择分类');
	}
	if(!$title){
		return er ( '优惠券标题不能为空' );
	}
	if($num<1){
		return er ( '生成数量错误' );
	}else if($num>1000){
		return er ('单次生成数量不能超过1000');
	}
	if(!($cost>0)){
		return er ( '消费金额错误' );
	}
	if(!($amount>0)){
		return er ( '抵扣金额错误' );
	}
	$data=array();
	for ($i = 0; $i < $num; $i++) {
		$no=rand_code($i);
		$data[]=array(
				'pid'=>$pid,
				'no'=>$no,
				'title'=>$title,
				'cost'=>$cost,
				'amount'=>$amount
		);
	}
	$addAll=M('coupon')->addAll($data);
	return $addAll;
}

/**
 * 查商品库存
 *
 * @param number $productid        	
 * @return Ambigous <mixed, NULL, unknown, multitype:Ambigous <string, unknown> unknown , object>
 */
function get_product_stock($productid = 0) {
	$where = array ();
	$where ['id'] = $productid;
	$stock = M ( 'content_product' )->where ( $where )->getField ( 'stock' );
	return intval ( $stock );
}

/**
 * 查商品价格
 *
 * @param number $productid        	
 * @return string
 */
function get_product_price($productid = 0) {
	$where = array ();
	$where ['id'] = $productid;
	$price = M ( 'content_product' )->where ( $where )->getField ( 'price' );
	return to_price ( $price );
}

/**
 * 获取商品信息
 *
 * @param number $productid        	
 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, object>
 */
function get_product_info($productid = 0) {
	$where = array ();
	$where ['id'] = $productid;
	$product = M ( 'content_product' )->where ( $where )->find ();
	return $product;
}

/**
 * 获取可用优惠券的金额
 * @param number $memberid
 * @param number $amount
 * @param string $couponno
 */
function get_coupon_amount($memberid=0,$amount=0,$couponno=''){
	$where=array();
	$where['memberid']=$memberid;
	$where['status']=0;
	$where['cost']=array('elt',$amount);
	$where['no']=$couponno;
	$coupon=M('coupon')->where($where)->find();
	return floatval($coupon['amount']);
}

/**
 * 设置券已消费
 * @param string $couponno
 */
function set_coupon_used($couponno='',$orderno=''){
	$data=array();
	$data['status']=1;
	$data['usetime']=time_format();
	$data['remark']=$orderno;
	$where=array();
	$where['no']=$couponno;
	M('coupon')->where($where)->data($data)->save();
}

/**
 * 快捷切换订单状态
 * @param string $orderno
 * @param string $status
 */
function switch_order($orderno='',$status=''){
	//处理库存
	$where=array();
	$where['orderno']=$orderno;
	$order=M('order')->where($where)->find();
	$detail=M('order_detail')->where($where)->getField('productid,num',true);
	$edit=edit_order($orderno,$status,$order,$detail);
	return $edit;
}

function get_w_r_credit($memberid){

	$charge=M('account_log')->where(array('memberid'=>$memberid,'type'=>1,'status'=>1))->getField('sum(amount) as amount');
	$charge=$charge?$charge:0;
	//未满30天白积分
	$wsql='select sum(amount) as amount from `my_account_log` where date_sub(curdate(), INTERVAL 30 DAY) < date(`addtime`) and memberid='.$memberid.' and type=7';
	$wcredit=M()->query($wsql);
	$wcredit[0]['amount']=$wcredit[0]['amount']?$wcredit[0]['amount']:0;



	//已满30天红积分
	$rsql='select sum(amount) as amount from `my_account_log` where date_sub(curdate(), INTERVAL 30 DAY) >= date(`addtime`) and memberid='.$memberid.' and type=7';
	$rcredit=M()->query($rsql);
	$rcredit[0]['amount']=$rcredit[0]['amount']?$rcredit[0]['amount']:0;


	//已使用红积分
	$usesql='select sum(amount) as amount from `my_account_log` where  memberid='.$memberid.' and type=2';
	$usecredit=M()->query($usesql);
	$usecredit[0]['amount']=$usecredit[0]['amount']?$usecredit[0]['amount']:0;


	$result['rcredit']=$charge+$rcredit[0]['amount']-$usecredit[0]['amount'];
	$result['wcredit']=$wcredit[0]['amount'];
	return $result;

}

/**
 * 下订单
 *
 * @param number $memberid        	
 * @param unknown $items：商品列表
 *        	array(id=>num,1=>2,2=>3);
 */
function add_order($memberid = 0, $data = array(), $items = array(), $shipfee = 0, $discount = 0) {
	$ret = array ();
	$where = array ();
	$where ['id'] = $memberid;
	$member = M ( 'member' )->where ( $where )->find ();
	if (! $member) {
		return er ( '用户不存在' );
	} else {
		if (empty ( $items )) {
			return er ( '商品为空' );
		} else {
			ksort ( $items );
			// 生成订单号
			$orderno ='C-'.get_order_no ();
			
			// 检查库存
			$ordername = '';
			$total = 0;
			$num = 0;
			$detail = array ();
			$type=1;
			foreach ( $items as $k => $v ) {
				$product = get_product_info ( $k );
				if($product['type']==2){
					$product['price']=$product['point'];
					$type=2;

					$point=get_w_r_credit($memberid);

					if(($product['point']*intval($v['num']))>$point['rcredit']){
						return er('可使用积分不足，请积攒足够的积分再来哦');
					}
				}

//				dump($type);

				$v['num']=intval($v['num']);
				$stock=intval($product['stock']);
//				dump($v.'---'.$product['stock']);die;
				if ($v['num'] > $stock) {
					return er ( '库存不足【' . $k . '】' );
				} else {
					$detail [] = array (
							'orderno' => $orderno,
							'productid' => $k,
							'specification' => '',
							'title' => $product ['title'],
							'indexpic' => $product ['indexpic'],
							'price' => $product ['price'],
							'weight' => $product ['weight'],
							'num' => $v['num'],
							'attrs'=>$v['attrs'],
							'status' => 0 
					);
					$total += $v['num'] * $product ['price'];
					$num += $v['num'];
					$ordername .= $product ['title'] . ',';
				}
			}
			
			// 生成主订单
			$order = array ();
			$order ['orderno'] = $orderno;
			$order ['type'] = $type;
			$order ['name'] = $ordername;
			$order ['memberid'] = $memberid;
			$order ['nickname'] = $member ['nickname'];
			$order ['headimgurl'] = $member ['headimgurl'];
			$order ['num'] = $num;
			$order ['total'] = $total;
			$order ['shipfee'] = $shipfee;
			$order ['discount'] = $discount;
			$order ['amount'] = $total + $shipfee - $discount;
			if($type==2){
				$order ['status'] = 1;
				$order['paytime']=date('Y-m-d H:i:s');
				$order['paystatus'] = 1;
				$order ['paymethod'] = 3;
			}
			else{
				if(intval($data['paymethod'])==1 || intval($data['paymethod']==4)){
					$order ['status'] = 0;
					$order ['paystatus'] = 0;
				}else{
					$order ['status'] = 1;
					$order['paytime']=date('Y-m-d H:i:s');
					$order ['paystatus'] = 1;
				}
			}

			$order ['paymethod'] = $data['paymethod'];
			$order ['tradeno'] = $data ['tradeno'];
			$order ['username'] = $data ['username'];
			$order ['telephone'] = $data ['telephone'];
			$order ['addressid'] = $data ['addressid'];
			$order ['provinceid'] = $data ['provinceid'];
			$order ['cityid'] = $data ['cityid'];
			$order ['districtid'] = $data ['districtid'];
			$order ['address'] = $data ['address'];
			$order ['expressid'] = $data ['expressid'];
			$order ['expressno'] = $data ['expressno'];
			$order ['remark'] = $data ['remark'];

			$addid = M ( 'order' )->data ( $order )->add ();
			if ($addid) {
				// 写详单
				$addAll = M ( 'order_detail' )->addAll ( $detail );
				if ($addAll) {
					// 减库存，加销量
					foreach ( $detail as $k => $v ) {
						edit_stock_sold ( $v ['productid'], $v ['num'], true );
					}
					//积分订单减积分写积分记录
					if($type==2){
						edit_point($total);
					}

					if(intval($data['paymethod'])==2){
						edit_credit($order ['amount']);
						//return_point($order['memberid'],$order['total']);  //消费积分支付不返积分
						$member=M('member')->where(array('id'=>$order['memberid']))->find();
						$membersort=$member['sortpath'];

						M('order_detail')->where(array('orderno'=>$orderno))->setField('status',1);

						if($member['pid']==1){
							//$refee='FIRST_';
							M('member')->where(array('id'=>$order['memberid']))->setField('pid',2);//第一次购买商品之后自动成为推广会员
						}
						/**
						 * 消费积分支付不给上级返利
						 */
						//else{
						//	$refee='OTHER_';
						//}
						//$refee[0]=C('config.'.$refee.'FIRST_REFEE');
						//$refee[1]=C('config.'.$refee.'SECOND_REFEE');
						//$refee[2]=C('config.'.$refee.'THIRD_REFEE');
						//return_money($membersort,$refee,$order['memberid']);
					}
				}
				return ok ( $orderno );
			} else {
				return er ( '主订单生成失败' );
			}
		}
	}
}

function edit_point($point){
	$member_id = get_memberid ();
	$edit=M('member')->where(array('id'=>$member_id))->setField(array('point'=>array('exp','point - '.$point)));
	write_point_log($member_id,$point,'消费使用积分');
}

function refund_credit($member_id,$point){
	$edit=M('member')->where(array('id'=>$member_id))->setField(array('credit'=>array('exp','credit - '.$point)));
	write_balance_log($member_id,$point,3,'订单退款扣减积分',$member_id);
}


function edit_credit($amount){
	$member_id = get_memberid ();
	$edit=M('member')->where(array('id'=>$member_id))->setField(array('credit'=>array('exp','credit - '.$amount)));
	write_balance_log($member_id,$amount,2,'积分购买商品',$member_id);
}


function write_point_log($member_id,$point,$str){
	$data['memberid']=$member_id;
	$data['point']=$point;
	$data['addtime']=date('Y-m-d H:i:s');
	$data['act']=$str;
	$add=M('point_log')->data($data)->add();
}

function return_point($memberid,$total){
	logdebug('POINTRATE:===='.C('config.POINT_RATE'));
	$point=$total*C('config.POINT_RATE');
	$edit=M('member')->where(array('id'=>$memberid))->setField(array('credit'=>array('exp','credit + '.$point)));
//	write_point_log($memberid,$point,'购买商品返积分');
	write_balance_log($memberid,$point,7,'购买商品返积分',$memberid);
}

function write_balance_log($member_id,$amount,$type,$str,$memberid){
	$data['memberid']=$member_id;
	$data['amount']=$amount;
	$data['addtime']=date('Y-m-d H:i:s');
	$data['act']=$str;
	$data['type']=$type;
	$data['payid']=$memberid;
	$add=M('account_log')->data($data)->add();
	logdebug(M('account_log')->getLastSql());
}

function return_money($sortpath,$fee,$memberid){

	$arr=explode(',',$sortpath);
	$k=0;
	if(count($arr)>4){
		$down=count($arr)-4;
	}else{
		$down=0;
	}
	//返利向上返3级；
	for($i=count($arr)-2;$i>=$down;$i--){
		if(!$arr[$i]){
			continue;
		}

		M('member')->where(array('id'=>$arr[$i]))->setField('balance',array('exp','balance + '.$fee[$k]));
		logdebug(M('member')->getLastSql());

		write_balance_log($arr[$i],$fee[$k],5,'下级消费返利',$memberid);
		//下级购买成功返利发送通知
		$member=M('member')->where(array('id'=>$memberid))->find();
		$msg='恭喜你获得'.$fee[$k].'个佣金，购买者：'.$member['username'].'，购买时间：'.date('Y-m-d H:i:s');
		sendwechatmsg($arr[$i],$msg);
		if($arr[$i]){
			$k++;
		}
	}

}

function get_username($id){
	$username=M('member')->where(array('id'=>$id))->getField('username');
	return $username;
}


/**
 * 修改订单
 * TODO:强制不允许同时改变状态和数量
 *
 * @param string $orderno        	
 * @param unknown $data        	
 * @param unknown $items
 *        	array(id=>num,1=>2,2=>3);
 */
function edit_order($orderno = '', $status = '', $data = array(), $items = array()) {
	if (! is_number ( $status )) {
		return er ( '订单状态不能为空' );
	}
	$ret = array ();
	$where = array ();
	$where ['orderno'] = $orderno;
	$find = M ( 'order' )->where ( $where )->find ();
	if (! $find) {
		return er ( '订单不存在' );
	} else {
		
		// 订单快照
		$history = array ();
		$history ['orderno'] = $orderno;
		$history ['content'] = json_encode ( $find );
		$history ['status'] = $find ['status'];
		$addhistory = M ( 'order_history' )->data ( $history )->add ();
		
		$ordername = $find ['name'];
		$total = $find ['total'];
		$num = $find ['num'];
		
		// 取详单
		$details = M ( 'order_detail' )->where ( $where )->getField ( 'productid,num', true );
		ksort ( $details );
		ksort ( $items );
		
		
		$arrplus = [ 
				0,
				1,
				2,
				3 
		];
		$arrdeduct = [ 
				4,
				5 
		];
		
		if ($find ['status'] != $status) {
			// 详单不变，不检查库存
			if ($items != $details) {
				return er ( '不允许同时改变订单状态和商品数量' );
			}
			// 情况1：加库存
			if (in_array ( $find ['status'], $arrplus ) && in_array ( $status, $arrdeduct )) {
				
				foreach ( $details as $k => $v ) {
					edit_stock_sold ( $k, $v, false );
				}
			}
			
			// 情况2：减库存
			if (in_array ( $find ['status'], $arrdeduct ) && in_array ( $status, $arrplus )) {
				
				foreach ( $details as $k => $v ) {
					edit_stock_sold ( $k, $v, true );
				}
			}
		} else {
			// 详单改变，需要重新检查库存
			if ($items != $details) {
				$arrdiff = array_diff_stock ( $details, $items );
				// if (! empty ( $arrdiff )) {
				foreach ( $arrdiff as $k => $v ) {
					$product = get_product_info ( $k );
					if ($v > $product ['stock']) {
						return er ( '库存不足【' . $k . '】' );
					}
				}
				
				// 改变了商品数量 => 影响库存
				foreach ( $details as $k => $v ) {
					edit_stock_sold ( $k, $v, false );
				}
				foreach ( $items as $k => $v ) {
					edit_stock_sold ( $k, $v, true );
				}
				
				// 统计订单，检查库存
				$ids = array_keys ( $items );
				$where2 = array ();
				$where2 ['orderno'] = $orderno;
				$where2 ['productid'] = array (
						'not in',
						$ids 
				);
				M ( 'order_detail' )->where ( $where2 )->delete ();
				
				$total = 0;
				$num = 0;
				$ordername = '';
				$detail = array ();
				foreach ( $items as $k => $v ) {
					$where1 = array ();
					$where1 ['orderno'] = $orderno;
					$where1 ['productid'] = $k;
					$find1 = M ( 'order_detail' )->where ( $where1 )->find ();
					if ($find1) {
						$detail = array (
								'id' => $find1 ['id'],
								'num' => $v,
								'status' => $status
						);
						M ( 'order_detail' )->data ( $detail )->save ();
						
						$total += $v * $find1 ['price'];
						$num += $v;
						$ordername .= $find1 ['title'] . ',';
					} else {
						$product = get_product_info ( $k );
						
						$detail = array (
								'orderno' => $orderno,
								'productid' => $k,
								'specification' => '',
								'title' => $product ['title'],
								'indexpic' => $product ['indexpic'],
								'price' => $product ['price'],
								'num' => $v,
								'status' => $status
						);
						M ( 'order_detail' )->data ( $detail )->add ();
						$total += $v * $product ['price'];
						$num += $v;
						$ordername .= $product ['title'] . ',';
					}
				}
				// }
			}
		}
		
		// 保存其它库存信息：基本信息和详单
		$shipfee = $data ['shipfee'];
		$discount = $data ['discount'];
		$memberid = $data ['memberid'];
		$member = M ( 'member' )->where ( array (
				'id' => $memberid 
		) )->find ();
		
		$order = array ();
		$order ['orderno'] = $orderno;
		$order ['name'] = $ordername;
		$order ['memberid'] = $memberid;
		$order ['nickname'] = $member ['nickname'];
		$order ['headimgurl'] = $member ['headimgurl'];
		$order ['num'] = $num;
		$order ['total'] = $total;
		$order ['shipfee'] = $shipfee;
		$order ['discount'] = $discount;
		$order ['amount'] = $total + $shipfee - $discount;
		
		$order ['status'] = $status;
		$order ['paystatus'] = $data ['paystatus'];
		$order ['paymethod'] = $data ['paymethod'];
		$order ['tradeno'] = $data ['tradeno'];
		$order ['username'] = $data ['username'];
		$order ['telephone'] = $data ['telephone'];
		$order ['addressid'] = $data ['addressid'];
		$order ['provinceid'] = $data ['provinceid'];
		$order ['cityid'] = $data ['cityid'];
		$order ['districtid'] = $data ['districtid'];
		$order ['address'] = $data ['address'];
		$order ['expressid'] = $data ['expressid'];
		$order ['expressno'] = $data ['expressno'];
		
		$save = M ( 'order' )->where ( $where )->data ( $order )->save ();
		if ($save !== false) {

			if($status==2){
				//发货提醒
				$expressname=M('content_express')->where(array('id'=>$order['expressid']))->getField('title');
				$msg='发货：您的订单【'.$orderno.'】已发货，请注意查收。快递公司：'.$expressname.'，快递单号：'.$data ['expressno'];
				sendwechatmsg($memberid,$msg);
			}
			
			if($status==3){//完成订单时返利
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

			}
			
			M ( 'order_detail' )->where ( $where )->setField ( 'status', $status );
		}
		return $save !== false;
	}
}

/**
 * 订单返利
 * @param number $amount
 * @param number $memberid
 */
function order_return($amount=0,$memberid=0,$orderno=''){
	$ret=false;
	$fid=M('member')->where(array('id'=>$memberid))->getField('fid');
	if($fid){
		$rate=floatval(C('config.ORDER_MONEY_RATE'));
		$money=$amount*$rate;
		$ret=balance_add($fid,5,$money,$orderno);
	}
	return $ret;
}

/**
 * 编辑库存和销量
 *
 * @param number $productid        	
 * @param number $num        	
 * @param string $sale:true卖，false退        	
 */
function edit_stock_sold($productid = 0, $num = 0, $sale = true) {
	$where = array ();
	$where ['id'] = $productid;
	$data = array ();
	if ($sale) {
		$sold = 'sold+' . $num;
		$stock = 'stock-' . $num;
	} else {
		$sold = 'sold-' . $num;
		$stock = 'stock+' . $num;
	}
	$data ['sold'] = array (
			'exp',
			$sold 
	);
	$data ['stock'] = array (
			'exp',
			$stock 
	);
	
	M ( 'content_product' )->where ( $where )->data ( $data )->save ();
}

/**
 * 比较两个数组，返回差值
 *
 * @param unknown $arr1        	
 * @param unknown $arr2        	
 */
function array_diff_stock($arr1 = array(), $arr2 = array()) {
	// $arr1=array(1=>2,6=>3,3=>3,4=>5);
	// $arr2=array(6=>3,2=>5,4=>5);
	// 返回$arr=array(2=>5);
	$arr = array ();
	foreach ( $arr2 as $k => $v ) {
		if (isset ( $arr1 [$k] )) {
			$stock = $v - $arr1 [$k];
			if ($stock > 0) {
				// 查库存
				$arr [$k] = $stock;
			}
		} else {
			$arr [$k] = $v;
		}
	}
	return $arr;
}


/**
 * 获取会员余额
 *
 * @param number $user_id
 */
function get_member_balance($member_id = 0) {
	if ($member_id == 0) {
		$member_id = get_memberid ();
	}
	$db = M ( 'member' )->where ( array (
			'id' => $member_id
	) )->getField ( 'balance' );
	if ($db) {
		return to_price ( $db );
	} else {
		return to_price ( 0 );
	}
}



/**
 * 获取会员支付余额
 *
 * @param number $user_id
 */
function get_member_credit($member_id = 0) {
	if ($member_id == 0) {
		$member_id = get_memberid ();
	}
	$db = M ( 'member' )->where ( array (
			'id' => $member_id
	) )->getField ( 'credit' );
	if ($db) {
		return to_price ( $db );
	} else {
		return to_price ( 0 );
	}
}


/**
 * 会员余额收支
 *
 * @param unknown $memberid
 * @param unknown $balancetypeid
 * @param unknown $amount
 * @param unknown $remark
 */
function balance_add($uid = 0, $typeid = 0, $amount = 0, $remark = '') {

	// 金额必须大于0才写记录
	if (! $amount > 0) {
		return false;
	}

	$data ['memberid'] = $uid;
	$data ['amount'] = $amount;
	$data ['type'] = $typeid;
	$data['act']=$remark;
	$data['addtime']=date('Y-m-d H:i:s');

	$db = M ( "account_log" )->data ( $data )->add ();
	if ($db) {
		// 2. 更新当前余额
		M ( 'member' )->where ( array (
				'id' => $uid
		) )->setField (array('credit'=>array('exp','credit + '.$amount)));
		return $db;
	} else {
		return false;
	}
}

/**
 * *
 * 重组节点
 */
function node_merge($node, $access = null, $pid = 0) {
	$arr = array ();
	foreach ( $node as $v ) {
		if (is_array ( $access )) {
			$v ["access"] = in_array ( $v ["id"], $access ) ? 1 : 0;
		}
		if ($v ['pid'] == $pid) {
			$v ['child'] = node_merge ( $node, $access, $v ['id'] );
			$arr [] = $v;
		}
	}
	
	return $arr;
}
function node_merge1($node, $access = null, $pid = 0) {
	$arr = array ();
	foreach ( $node as $v ) {
		if (is_array ( $access )) {
			$v ["access"] = in_array ( $v ["id"], $access ) ? 1 : 0;
		}
		$arr [] = $v;
	}
	
	return $arr;
}
// 分析枚举类型字段值 格式 a:名称1,b:名称2
// 暂时和 parse_config_attr功能相同
// 但请不要互相使用，后期会调整
function parse_field_attr($string) {
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
 * 更新分类路径
 *
 * @param string $tblname        	
 * @param number $id        	
 */
function update_sortpath($tblname = '', $id = 0, $pid = 0, $sort = 0) {
	$where = array (
			'id' => $pid 
	);
	$sortpath = M ( $tblname )->where ( $where )->getField ( 'sortpath' );
	if (! $sortpath) {
		$sortpath = '0,';
	}
	$sortpath .= $id . ',';
	$where1 = array (
			'id' => $id 
	);
	$presortpath = M ( $tblname )->where ( $where1 )->getField ( 'sortpath' );
	$data = array ();
	$data ['sortpath'] = $sortpath;
	$data ['depth'] = ( int ) count ( str2arr ( $sortpath ) ) - 2;
	if ($sort) {
		$data ['sort'] = $sort;
	}
	M ( $tblname )->where ( $where1 )->setField ( $data );
	update_sub_sortpath ( $tblname, $id, $presortpath, $sortpath );
}

/**
 * 更新分类的子类（移动父级分类的情况）
 *
 * @param string $tblname        	
 * @param number $id        	
 */
function update_sub_sortpath($tblname = '', $id = 0, $presortpath = '', $sortpath = '') {
	$where = array ();
	$where ['id'] = array (
			'neq',
			$id 
	);
	$where ['sortpath'] = array (
			'like',
			'%,' . $id . ',%' 
	);
	$list = M ( $tblname )->where ( $where )->select ();
	foreach ( $list as $k => $v ) {
		$data = array ();
		$data ['sortpath'] = str_replace ( $presortpath, $sortpath, $v ['sortpath'] );
		$data ['depth'] = ( int ) count ( str2arr ( $data ['sortpath'] ) ) - 2;
		M ( $tblname )->where ( array (
				'id' => $v ['id'] 
		) )->data ( $data )->save ();
	}
}

/**
 * 更新资讯
 *
 * @param string $tblname        	
 * @param number $id        	
 * @param number $pid        	
 * @param number $sort        	
 */
function update_sortpath_content($tblnews = '', $tblcategory = '', $id = 0, $pid = 0) {
	$where = array (
			'id' => $pid 
	);
	$db = M ( $tblcategory )->where ( $where )->field ( 'id,sortpath,name' )->find ();
	if ($db ['sortpath']) {
		$where = array (
				'id' => $id 
		);
		$data = array ();
		$data ['sortpath'] = $db ['sortpath'];
		$data ['parentname'] = $db ['name'];
		M ( $tblnews )->where ( $where )->setField ( $data );
	}
}

/**
 * 隐藏字符加*
 *
 * @param string $text        	
 */
function hide_text($text = '', $len = 2) {
	if (strlen ( $text ) < 8) {
		$str = $text . '********';
		$str = substr ( $str, 0, 8 );
	} else {
		$str = substr ( $text, 0, $len ) . '****' . substr ( $text, - 2, $len * 2 );
	}
	return $str;
}
/**
 * 载入微信支付设置
 */
function load_wxpay() {
	$mpinfo = get_mp_info ( get_mp_str () );
	$wxpay = array (
			'APPID' => $mpinfo ['app_id'],
			'APPSECRET' => $mpinfo ['app_secret'],
			'MCHID' => $mpinfo ['app_mchid'],
			'KEY' => $mpinfo ['app_key'],
			'APPAESKEY' => $mpinfo ['app_aeskey'],
			'SSLCERT_PATH' => VENDOR_PATH . 'WxPayPubHelper/cacert/apiclient_cert.pem',
			'SSLKEY_PATH' => VENDOR_PATH . 'WxPayPubHelper/cacert/apiclient_key.pem',
			'CURL_TIMEOUT' => 30 
	);
	C ( array (
			'WXPAY' => $wxpay 
	) );
}
function get_app_url($appcode = '', $appid = '') {
	return '';
}
function get_app_info($appcode = '', $appid = '') {
	return '';
}
function add_message($appcode = '', $appid = '') {
}
/**
 * 返回当前域名
 *
 * @return string
 */
function get_base_domain() {
	return $_SERVER ['SERVER_NAME'];
}
function get_userid() {
	return 0;
}

// 取微信对象
function get_wechat_obj($mpid = 0) {
	if ($mpid == 0) {
		$mpid = get_mp_str ();
	}
	$cache = S ( 'WECHAT_MP_INFO' );
	if (! $cache) {
		$cache = M ( 'wechat_mp' )->find ( 1 );
		S ( 'WECHAT_MP_INFO', $cache, 3600 );
	}
	$options = array (
			'mpid' => $mpid,
			'token' => $cache ['app_token'], // 填写你设定的key
			'encodingaeskey' => $cache ['app_aeskey'],
			'appid' => $cache ['app_id'], // 填写高级调用功能的app id
			'appsecret' => $cache ['app_secret'], // 填写高级调用功能的密钥
			                                      // 'isauth' => $cache['isauth'],
			'app_aeskey' => $cache ['app_aeskey'],
			'app_mchid' => $cache ['app_mchid'], // 财付通商户身份标识
			'app_key' => $cache ['app_key'], // 财付通商户权限密钥Key
			'debug' => true,
			'logcallback' => 'logdebug' 
	);
	$wechat = new \Org\Util\Wechat ( $options );
	return $wechat;
}

/**
 * 是否微信浏览器
 *
 * @return boolean
 */
function is_wechat_browser() {
	$user_agent = $_SERVER ['HTTP_USER_AGENT'];
	if (strpos ( strtolower ( $user_agent ), 'micromessenger' ) === false) {
		return false;
	} else {
		return true;
	}
}

/**
 * 获取当前网址全路径
 */
function get_current_url() {
	$url = array ();
	$url [0] = $_SERVER ['REQUEST_SCHEME'];
	$url [1] = $_SERVER ['SERVER_NAME'];
	$url [2] = $_SERVER ['SERVER_PORT'];
	$url [3] = $_SERVER ['REQUEST_URI'];
	if ($url [0] == '') {
		$url [0] = 'http';
	}
	if ($url [2] == '80') {
		$u = $url [0] . '://' . $url [1] . $url [3];
	} else {
		$u = $url [0] . '://' . $url [1] . ':' . $url [2] . $url [3];
	}
	return $u;
}

/**
 * 授权跳转
 */
function get_auth_openid( $url = '') {
	$new_url = C ( 'BASE_URL' ) . '/Auth/?callback=' . urlencode ( $url );
	redirect ( $new_url );
}

/**
 * 公众号ID
 *
 * @return number
 */
function get_mp_str() {
	return 1;
}

/**
 * 设置openid，暂时只管28位的微信
 */
function openid($open_id = '') {
	$mpid = get_mp_str ();
	if ($open_id === '') {
		return cookie ( '_openid_' . $mpid );
	} else {
		$user = U_Get ( '*', $open_id );
		if ($user) {
			cookie ( '_openid_' . $mpid, $open_id, 3600 );
		} else {
			cookie ( '_openid_' . $mpid, null );
		}
	}
}


/**
 * 发送微信通知消息
 */
function sendwechatmsg($memberid,$msg){
	if($memberid){
		$where=array();
		$where['id']=$memberid;
		$find=M('member')->where($where)->find();
		if($find['openid']){
			$wechat=get_wechat_obj();
			$msg = array (
					'touser' => $find['openid'],
					'msgtype' => 'text',
					'text' => array (
							'content' => $msg
					)
			);
			$wechat->sendCustomMessage ( $msg );
		}
	}
}

/**
 * 发送微信模板消息
 */
function sendtemplatemessage($memberid,$data){
	$send=array(
			"errcode"=>-1,
			"errmsg"=>"系统繁忙"
		);
	if($memberid){
		$where=array();
		$where['id']=$memberid;
		$find=M('member')->where($where)->find();
		if($find['openid']){
			$data['touser']=$find['openid'];
			$wechat=get_wechat_obj();
			$send=$wechat->sendTemplateMessage($data);
		}
	}
	return $send;
}



/**
 * 取用户信息
 *
 * @param string $openid
 */
function U_Get($col = '*', $openid = '')
{
	if (!$openid) {
		$openid = openid();
	}
	if ($openid) {
		$cachename = 'uget_*_' . $openid;
		$cache = S($cachename);
		if (!$cache) {
			U_Subscribe($openid);
			$cachename = 'uget_' . $col . '_' . $openid;
			$where = array();
			$where ['openid'] = $openid;
			$db = M('member')->where($where)->field($col)->cache($cachename)->find();
			return $db;
		} else {
			return $cache;
		}
	}
}

/**
 * 获取会员ID
 *
 * @param string $openid        	
 * @return number Ambigous
 */
function get_memberid($openid = '') {
	return session ( '?memberid'  ) ? session ( 'memberid' ) : 0;
}

/**
 * 获取粉丝信息
 *
 * @param string $openid        	
 */
function U_Subscribe($openid = '', $fid = 0) {
	if (! $openid) {
		$openid = openid ();
	}
	$mpid = get_mp_str ();
	$weChat = get_wechat_obj ( $mpid );
	$user = $weChat->getUserInfo ( $openid );
	if ($user) {
		if ($fid) {
			$user ['fid'] = $fid;
		}
		U_Set ( $user, $openid );
	}
}
/**
 * 取消关注
 *
 * @param string $openid        	
 */
function U_Unsubscribe($openid = '') {
	$user = array ();
	$user ['unsubscribetime'] = NOW_TIME;
	$user ['subscribe'] = 0;
	U_Set ( $user, $openid );
}

/**
 * 设置用户信息
 *
 * @param string $data        	
 * @param string $openid        	
 */
function U_Set($data = null, $openid = '') {
	if (is_array ( $data )) {
		$where = array ();
		if ($openid == '') {
			$openid = openid ();
		}
		
		$where ['openid'] = $openid;
		// 会员id
		$memberid = M ( 'member' )->where ( $where )->getField ( 'id' );
		if ($data ['nickname']) {
			$data ['nickname'] = preg_replace ( '/[\x{10000}-\x{10FFFF}]/u', '', $data ['nickname'] );
		}
		
		logdebug('memberid==='.$memberid);
		logdebug('fid==='.$data['fid']);

		if ($memberid) {

			$username=$data['nickname'];
			$data['username']=$username;

			unset($data['fid']);
			M ( 'member' )->where ( $where )->data ( $data )->save ();
			logdebug(M('member')->getLastSql());
			session ( 'memberid',$memberid );
		} else {
			if ($data ['openid']) {
				
				$id = M ( 'member' )->data ( $data )->add ();
				

				if($id){
					$username=$data['nickname'].'_'.$id;
					if(!$data['fid']){
						$sortpath='0,'.$id;
					}else{
//						关联下级成功发送通知
						$father=M('member')->where(array('id'=>$data['fid']))->find();
						$msg='恭喜您获得一们潜在用户！会员昵称：'.$username.'，加入时间：'.date('Y-m-d H:i:s');
						sendwechatmsg($data['fid'],$msg);
						$fsortpath=M('member')->where(array('id'=>$data['fid']))->getField('sortpath');
						if(!$fsortpath){
							unset($data['fid']);
							$sortpath='0,'.$memberid;
						}else{
							
							//$fsortpath=getsortpath($fsortpath);
							$sortpath=$fsortpath.','.$id;
						}
					}
					logdebug($sortpath);
					M('member')->where(array('id'=>$id))->setField(array('username'=>$username,'sortpath'=>$sortpath));
					logdebug(M('member')->getLastSql());
				}
				session ( 'memberid',$id );
			}
		}
		$cachename = 'uget_*_' . $openid;
		S ( $cachename, null );
	}
}


function getsortpath($fsortpath){
	$arrsort=explode(',',$fsortpath);

	if(count($arrsort)>3){
		for($i=0;$i<count($arrsort)-3;$i++){
			unset($arrsort[$i]);
		}
		$fsortpath=implode(',',$arrsort);
	}

	return $fsortpath;
}

/**
 * 注册送券
 */
function add_coupon($memberid=0,$openid=''){
	$couponid=M('wechat_mp')->where(array('id'=>1))->getField('couponid');
	if($couponid&&$memberid){
		$where=array();
		$where['memberid']=0;
		$where['pid']=$couponid;
		$where['timeto']=array('gt',time_format());
		$find=M('coupon')->where($where)->find();
		if($find){
			$save=M('coupon')->where(array('id'=>$find['id']))->setField('memberid',$memberid);
			if($save&&$openid){
				// 文字
				$wechat=get_wechat_obj();
				$msg = array (
						'touser' => $openid,
						'msgtype' => 'text',
						'text' => array (
								'content' => '恭喜，您获得【满'.$find['cost'].'减'.$find['amount'].'】优惠券一张，券号：'.$find['no'].'！'
						)
				);
				$wechat->sendCustomMessage ( $msg );
				
			}
		}
	}
}

/**
 * 根据关键词取回复ID
 *
 * @param string $keyword        	
 */
function get_reply_by_keyword($keyword = '', $type = '') {
	$where = array ();
	// if ($type != '') {
	// $where ['ismenu'] = 1;
	// } else {
	// $where ['ismenu'] = 0;
	// }
	$where ['title'] = $keyword;
	$keyword_id = M ( 'wechat_keyword' )->where ( $where )->find ();
	$true_id = 0;
	$true_all = 0;
	if ($keyword_id) {
		// 全部匹配
		$true_id = $keyword_id ['id'];
		$true_all = $keyword_id ['isall'];
	} else {
		$where ['title'] = array (
				'like',
				'%' . $keyword . '%' 
		);
		$where ['isfull'] = 0;
		$keyword_id = M ( 'wechat_keyword' )->where ( $where )->find ();
		if ($keyword_id) {
			$true_id = $keyword_id ['id'];
			$true_all = $keyword_id ['isall'];
		} else {
		}
	}
	// 找到真正的keyword id.
	// 再找reply id
	if ($true_id) {
		$where = array ();
		$where ['keyword_ids'] = array (
				'like',
				'%,' . $true_id . ',%' 
		);
		if ($true_all) {
			$reply = M ( 'wechat_keyword_reply' )->where ( $where )->order ( 'id asc' )->select ();
		} else {
			
			$reply = M ( 'wechat_keyword_reply' )->where ( $where )->order ( 'rand()' )->limit ( 1 )->select ();
		}
		return $reply;
	} else {
		// 自动回复数据
		$where = array ();
		$where ['title'] = 'autoreply';
		$db = M ( 'wechat_keyword' )->where ( $where )->find ();
		if ($db) {
			$where = array ();
			$where ['keyword_id'] = $db ['id'];
			$reply = M ( 'wechat_keyword_reply' )->where ( $where )->order ( 'rand()' )->select ();
			return $reply;
		} else {
			return false;
		}
	}
}

/**
 * 获取图文信息页
 *
 * @param unknown $appurl        	
 * @param number $material_detail_id        	
 * @param string $openid        	
 * @return string
 */
function get_app_news_url($app_code, $user_mpid, $material_detail_id = 0, $openid = '') {
	$url = C ( 'BASE_URL' ) . '/News/view/id/' . $material_detail_id . '/_openid/' . $openid . '.html';
	return $url;
}

/**
 * 资源前缀
 *
 * @param string $picurl        	
 * @return string
 */
function get_resource_url($picurl = '') {
	$url = 'http://' . get_base_domain () . '' . $picurl;
	return $url;
}

/**
 * 获取公众号权限
 *
 * @param number $mpid        	
 */
function get_mp_info($mpid = 0) {
	$cache = S ( 'user_mpinfo_' . $mpid );
	if (! $cache) {
		$ret = M ( 'wechat_mp' )->find ( $mpid );
		if ($ret) {
			$cache = $ret;
		} else {
			$cache = false;
		}
		S ( 'user_mpinfo_' . $mpid, $cache );
	}
	return $cache;
}

/**
 * 分享数据
 *
 * @param unknown $mpid        	
 * @return multitype:number unknown string Ambigous <>
 */
function getShareSign($mpid) {
	$weChat = get_wechat_obj ( $mpid );
	$url = get_current_url ();
	$timeStamp = time ();
	$nonceStr = $weChat->generateNonceStr ();
	$usermp = get_mp_info ( $mpid );
	$signature = $weChat->getJsSign ( $url, $timeStamp, $nonceStr, $usermp ['app_id'] );
	$signPackage = array (
			"appId" => $usermp ['app_id'],
			"nonceStr" => $nonceStr,
			"timestamp" => $timeStamp,
			"url" => $url,
			"signature" => $signature 
	);
	return $signPackage;
}

/**
 * 黑词管理
 *
 * @param unknown $words        	
 * @param unknown $blacklist：竖线分割        	
 * @return boolean
 */
function is_black($words, $blacklist) {
	// $blacklist= "你是大神吗|你好|去死";
	if (preg_match ( "/$blacklist/i", $words )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 获取缓存列表
 *
 * @param string $type        	
 * @return Ambigous <mixed, \Think\mixed, object>
 */
function get_cache_list($type = '', $pid = '', $field = 'id,name,pid,depth,indexpic', $limit = 0) {
	$cachename = 'Cache_list_' . $type . '_' . $pid . '_' . $field . '_' . $limit;
	$cache = S ( $cachename );
	if (! $cache) {
		$where = array ();
		$where ['status'] = 1;
		if (is_number ( $pid )) {
			$where ['pid'] = $pid;
		}
		$cache = M ( $type )->where ( $where )->cache ( $cachename )->field ( $field )->order ( 'sort asc' )->select ();
	}
	return $cache;
}

/**
 * 获取缓存资讯
 *
 * @param string $type        	
 */
function get_cache_content($type = 'news', $pid = 0, $limit = 10, $isresume = '') {
	$cachename = 'Content_' . $type . '_' . $pid . '_' . $limit . '_' . $isresume;
	$cache = S ( $cachename );
	if (! $cache) {
		$where = array ();
		$where ['status'] = 1;
		if ($pid) {
			$where ['pid'] = $pid;
		}
		if (is_number ( $isresume )) {
			$where ['isresume'] = $isresume;
		}
		$cache = M ( 'content_' . $type )->where ( $where )->limit ( $limit )->cache ( $cachename )->field ( 'id,title,isresume,indexpic,description' )->order ( 'sort desc' )->select ();
	}
	return $cache;
}

/**
 * 获取缓存内容
 */
function get_cache_value($tblname = '', $id = 0, $field = 'name') {
	$cachename = 'Value_' . $id . '_' . $tblname . '_' . $field;
	$cache = S ( $cachename );
	if (! $cache) {
		$where = array ();
		$where ['id'] = $id;
		$cache = M ( $tblname )->where ( $where )->cache ( $cachename )->getField ( $field );
	}
	return $cache;
}

/**
 * 获取缓存内容
 */
function get_cache_member($memberid = 0, $clear = false) {
	$cachename = 'Member_' . $memberid;
	if ($clear) {
		S ( $cachename, null );
	}
	$cache = S ( $cachename );
	if (! $cache) {
		$where = array ();
		$where ['id'] = $memberid;
		$cache = M ( 'member' )->where ( $where )->cache ( $cachename )->find ();
	}
	return $cache;
}



/**
 * 获取当前域名：包含http://，不包含尾部/
 */
function get_current_domain()
{
	$url = array();
	$url [0] = $_SERVER ['REQUEST_SCHEME'];
	$url [1] = $_SERVER ['SERVER_NAME'];
	$url [2] = $_SERVER ['SERVER_PORT'];
	if ($url [0] == '') {
		$url [0] = 'http';
	}
	if ($url [2] == '80') {
		$u = $url [0] . '://' . $url [1];
	} else {
		$u = $url [0] . '://' . $url [1] . ':' . $url [2];
	}
	return $u;
}

/**
 * 根据概率获取奖品
 */
function getRand($proArr) {
	$data = '';
	$proSum = array_sum($proArr); //概率数组的总概率精度

	foreach ($proArr as $k => $v) { //概率数组循环
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $v) {
			$data = $k;
			break;
		} else {
			$proSum -= $v;
		}
	}
	unset($proArr);

	return $data;
}


/**
 * 处理url
 */
function selecturl($fromurl,$keyword){
	if(strpos(think_decrypt($fromurl),$keyword)){
		$arr=explode('/',think_decrypt($fromurl));
		foreach($arr as $k=>$v){
			if($v==$keyword){
				unset($arr[$k],$arr[$k+1]);
			}
		}
		$fromurl=implode('/',$arr);
	}else{
		$fromurl=think_decrypt($fromurl);
	}
	return $fromurl;
}


/**
 *  @desc 根据两点间的经纬度计算距离
 *  @param float $lat 纬度值
 *  @param float $lng 经度值
 */
function getDistance($lat1, $lng1, $lat2, $lng2)
{
	$earthRadius = 6367000; //approximate radius of earth in meters

	$lat1 = ($lat1 * pi() ) / 180;
	$lng1 = ($lng1 * pi() ) / 180;

	$lat2 = ($lat2 * pi() ) / 180;
	$lng2 = ($lng2 * pi() ) / 180;


	$calcLongitude = $lng2 - $lng1;
	$calcLatitude = $lat2 - $lat1;
	$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
	$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	$calculatedDistance = $earthRadius * $stepTwo;

	return round($calculatedDistance);
}


/**
 * 活动相关开始
 * -----------------------------
 */

/*
 * 经典的概率算法，
 * $proArr是一个预先设置的数组，
 * 假设数组为：array(100,200,300，400)，
 * 开始是从1,1000 这个概率范围内筛选第一个数是否在他的出现概率范围之内，
 * 如果不在，则将概率空间，也就是k的值减去刚刚的那个数字的概率空间，
 * 在本例当中就是减去100，也就是说第二个数是在1，900这个范围内筛选的。
 * 这样 筛选到最终，总会有一个数满足要求。
 * 就相当于去一个箱子里摸东西，
 * 第一个不是，第二个不是，第三个还不是，那最后一个一定是。
 * 这个算法简单，而且效率非常 高，
 * 关键是这个算法已在我们以前的项目中有应用，尤其是大数据量的项目中效率非常棒。
 */
function get_rand($proArr){
	$result = '';
	// 概率数组的总概率精度
	$proSum = array_sum($proArr);
	// 概率数组循环
	foreach ($proArr as $key => $proCur) {
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $proCur) {
			$result = $key;
			break;
		} else {
			$proSum -= $proCur;
		}
	}
	unset($proArr);
	return $result;
}

/**
 * 获取活动信息
 * @param string $type
 * @param string $id
 */
function get_cache_activity($type='',$id='',$clear=false){
	$cachename = $type.'_activity_' . $id;
	if ($clear) {
		S ( $cachename, null );
	} else {
		$cache = S ( $cachename );
	}
	if (! $cache) {
		$where = array ();
		$where ['id'] = $id;
		$cache = M ( $type.'_activity' )->where ( $where )->cache ( $cachename )->find ();
	}
	return $cache;
}

/**
 * 获取活动名称
 * @param string $type
 * @param string $id
 */
function get_activity_name($type='',$id=''){
	$act=get_cache_activity($type,$id);
	if(!$act){
		return '选择活动';
	}else{
		return $act['title'];
	}
}

/**
 * 设置和获取当前活动id
 */
function get_current_activity_id($type='',$id=''){
	$cookiename = $type.'_activity_id' ;
	if(is_numeric($id)){
		//设置
		cookie($cookiename,$id);
		return $id;
	}else{
		$cookie=cookie($cookiename);
		//读取
		return $cookie;
	}
}

/**
 * 用户可用抽奖次数
 * @param string $type
 * @param string $id
 */
function get_activity_play_num($type='',$id='',$memberid='',$iseveryday=''){
	$type=strtolower($type);
	$tblname=$type.'_history';
	$where=array();
	$where['memberid']=$memberid;
	$where['actid']=$id;
	if($iseveryday){
		$where['date']=date2format(NOW_TIME);
	}
	$count=M($tblname)->where($where)->sum('num');
	return intval($count);
}

/**
 * 用户分享获得抽奖次数
 * @param string $type
 * @param string $id
 */
function get_activity_share_num($type='',$id='',$memberid='',$iseveryday=''){
	$type=strtolower($type);
	$tblname=$type.'_share';
	$where=array();
	$where['memberid']=$memberid;
	$where['actid']=$id;
	if($iseveryday){
		$where['date']=date2format(NOW_TIME);
	}
	$count=M($tblname)->where($where)->count();
	return intval($count);
}

/**
 * 用户可中奖次数
 * @param string $type
 * @param string $id
 */
function get_activity_lottery_num($type='',$id='',$memberid='',$iseveryday=''){
	$type=strtolower($type);
	$tblname=$type.'_record';
	$where=array();
	$where['memberid']=$memberid;
	$where['actid']=$id;
	if($iseveryday){
		$where['date']=date2format(NOW_TIME);
	}
	$count=M($tblname)->where($where)->count('id');
	return intval($count);
}

/**
 * 获取活动首页地址
 * @param string $type
 * @param string $id
 * @return string
 */
function get_activity_url($type='',$id=''){
	return get_current_domain().U('/'.$type.'/index','id='.$id);
}

/**
 * 获取活动统计数据：参与人数，中奖人数，分享数
 * @param string $type
 * @param string $id
 */
function get_activity_count($type='',$id='',$tbl=''){
	$type=strtolower($type);
	$count=0;
	$where=array();
	$where['actid']=$id;
	$tblname=$type.'_'.$tbl;
	switch ($tbl){
		//参与人数
		case 'history':
			$count=M($tblname)->where($where)->count('distinct(memberid)');
			break;
		//中奖人数
		case 'record':
			$count=M($tblname)->where($where)->count('distinct(memberid)');
			break;
		//分享次数
		case 'share':
			$count=M($tblname)->where($where)->sum('num');
			break;
	}

	return $count;
}


/**
 * 领取奖品
 * @param string $type
 * @param string $sn
 */
function get_activity_prize($type='',$id='',$sn=''){
	$type=strtolower($type);
	$tblname=$type.'_record';
	$where=array();
	$where['actid']=$id;
	$where['status']=0;
	$where['sn']=$sn;
	$find=M($tblname)->where($where)->find();
	if($find){
		switch ($find['type']){
			case 4:
				//现金红包，立即到账
				$prize=json_decode($find['prize'],true);
				$credit=$prize['amount'];
//				dump($credit);die;
				$result=wechat_redpacket(get_memberid(),$credit,'大转盘活动红包');
				if(strtoupper ( $result ['return_code'] ) == 'FAIL'){
					return er($result['return_msg']);
				}
				if(strtoupper ( $result ['result_code'] ) == 'FAIL'){
					return er($result['err_code_des']);
				}
				break;
		}
		$data1=array();
		$data1['status']=1;
		$data1['gettime']=time_format();
		M($tblname)->where($where)->data($data1)->save();
		return ok('奖品领取成功');
	}else{
		return er('奖品已经领取过了');
	}

}
/**
 * 获取默认活动ID
 * @param string $type
 * @param string $sn
 */
function get_activity_default_id($type=''){
	$type=strtolower($type);
	$tblname=$type.'_activity';
	$where=array();
	$where['status']=1;
	$find=M($tblname)->where($where)->order('isdefault desc,id desc')->find();
	if($find){
		return $find['id'];
	}else{
		return 0;
	}

}

/**
 * 活动相关结束
 * -----------------------------
 */

/**
 * 分享链接
 * @return string
 */
function get_invite_link(){
	$memberid=get_memberid();
	$invitelink=get_current_domain().'/';
	if($memberid){
		if(strpos($invitelink,'?')){
			$invitelink=($invitelink.'&fid='.$memberid);
		}else{
			$invitelink=($invitelink.'?fid='.$memberid);
		}
	}
	return $invitelink;
}
/**
 * 判断是否移动端
 * @return boolean
 */
function is_wap() {

	// returns true if one of the specified mobile browsers is detected
	$regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
	$regex_match .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
	$regex_match .= "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
	$regex_match .= "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
	$regex_match .= "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
	$regex_match .= ")/i";
	return isset ( $_SERVER ['HTTP_X_WAP_PROFILE'] ) or isset ( $_SERVER ['HTTP_PROFILE'] ) or preg_match ( $regex_match, strtolower ( $_SERVER ['HTTP_USER_AGENT'] ) );
}

/**
 * 生成带小数的随机数
 *
 * @param type $min
 *        	最小数
 * @param type $max
 *        	最大数
 * @param type $digit
 *        	保留小数位，默认保留2位小数
 * @return type
 */
function randomFloat($min = 0, $max = 1, $digit = 2) {
	$rand_num = $min + mt_rand () / mt_getrandmax () * ($max - $min);
	$rand_num = sprintf ( "%.{$digit}f", $rand_num );
	return $rand_num;
}

/**
 * 获取红包金额
 */
function get_redpacket_amount() {
	$mp = get_mp_info ();
	if ($mp ['amountmin'] < 1 || $mp ['amountmax'] > 200) {
		return 0;
	} else {
		return randomFloat ( $mp ['amountmin'], $mp ['amountmax'] );
	}
}
/**
 * 检测是否发过红包
 *
 * @param number $memberid
 * @param string $username
 */
function check_redpacket($memberid = 0, $telephone = '') {
	$where = array ();
	$where ['memberid'] = $memberid;
	if ($telephone) {
		$where ['telephone'] = $telephone;
	}
	$find = M ( 'member_redpacket' )->where ( $where )->getField ( 'id' );
	return $find;
}
function get_bill_no($mchid = '') {
	return $mchid . date ( 'Ymd' ) . rand_str ( 10, 1 );
}
/**
 * 微信发红包
 *
 * @param string $openid
 * @param number $amount
 */
function wechat_redpacket($memberid = 0, $amount = 0, $remark = '') {
	$where = array ();
	$where ['id'] = $memberid;
	$member = M ( 'member' )->where ( $where )->find ();
	if (! $member) {
		return (er ( '用户不存在' ));
	}

	// 微信支付
	load_wxpay ();
	$wxpay = C ( 'WXPAY' );

	// 现金红包

	// https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack
	// 裂变红包

	// https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack
	// 企业付款

	// https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers
	// 请求接口
	$payurl = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	$randstr = rand_str ( 32 );
	$orderno = get_bill_no ( $wxpay ['MCHID'] );
	$data = array ();

	// 企业付款
	// $data ['mch_appid'] = $wxpay ['APPID'];
	// $data ['mchid'] = $wxpay ['MCHID'];
	// $data ['nonce_str'] = $randstr;
	// $data ['partner_trade_no'] = $orderno;
	// $data ['openid'] = $member ['openid'];
	// $data ['check_name'] = 'NO_CHECK'; // NO_CHECK,OPTION_CHECK,FORCE_CHECK
	// $data ['re_user_name'] = $wxpay['APPNAME'];//微行;
	// $data ['amount'] = $amount * 100;
	// $data ['desc'] = $remark;
	// $data ['spbill_create_ip'] = get_client_ip ();
	// 现金红包
	$data ['nonce_str'] = $randstr;
	$data ['mch_billno'] = $orderno;
	$data ['mch_id'] = $wxpay ['MCHID'];
	$data ['wxappid'] = $wxpay ['APPID'];
	$data ['send_name'] = '汇智共创'; // '微行';//
	$data ['re_openid'] = $member ['openid'];
	$data ['total_amount'] = $amount * 100;
	$data ['total_num'] = 1;
	$data ['wishing'] = '祝您游戏愉快！';
	$data ['client_ip'] = get_client_ip ();
	$data ['act_name'] = $remark;
	$data ['remark'] = '赶紧邀请朋友来领红包吧，手慢无！';

	$key = $wxpay ['KEY'];
	$sign = get_signature ( $data, $key, 'md5' );
	$data ['sign'] = $sign;
	$data = xml_encode ( $data, 'xml' );
	// logdebug($wxpay);
	logdebug ( $data );
	$result = curl_post_ssl ( $payurl, $data );
	$result = xml_to_array ( $result );
	logdebug ( $result );
	if (strtoupper ( $result ['result_code'] ) == 'SUCCESS') {
		return $result;
//		return (ok ( $result ));
	} else {
		return $result;
//		if (strtoupper ( $result ['err_code'] ) == 'NOTENOUGH') {
//			$errmsg = C ( 'ERRCODE' );
//			$errmsg = $errmsg [2003];
//			return (er ( $errmsg ));
//		} else {
//			return (er ( $result ['return_msg'] ));
//		}
	}
}
function curl_post_ssl($url, $vars, $second = 30, $aHeader = array()) {
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

	// 以下两种方式需选择一种
	// 第一种方法，cert 与 key 分别属于两个.pem文件
	// 默认格式为PEM，可以注释
	curl_setopt ( $ch, CURLOPT_SSLCERTTYPE, 'PEM' );
	curl_setopt ( $ch, CURLOPT_SSLCERT, C ( 'WXPAY.SSLCERT_PATH' ) );
	// 默认格式为PEM，可以注释
	curl_setopt ( $ch, CURLOPT_SSLKEYTYPE, 'PEM' );
	curl_setopt ( $ch, CURLOPT_SSLKEY, C ( 'WXPAY.SSLKEY_PATH' ) );

	// 第二种方式，两个文件合成一个.pem文件
	// curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

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
function xml_to_array($xmlstring) {
	return ( array ) simplexml_load_string ( $xmlstring, 'SimpleXMLElement', LIBXML_NOCDATA );
}
//银行卡掩码
function formatBankCardNo($bankno){
	//截取银行卡号前4位
	$prefix = substr($bankno,0,4);
	//截取银行卡号后4位
	$suffix = substr($bankno,-4,4);
	$maskBankCardNo = $prefix." **** **** **** ".$suffix;
	return $maskBankCardNo;

}
function make_coupon_card() {
	$code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$rand = $code[rand(0,25)]
			.strtoupper(dechex(date('m')))
			.date('d').substr(time(),-5)
			.substr(microtime(),2,5)
			.sprintf('%02d',rand(0,99));
	for(
			$a = md5( $rand, true ),
			$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
			$d = '',
			$f = 0;
			$f < 8;
			$g = ord( $a[ $f ] ),
			$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
			$f++
	);
	return $d;
}
//生成注册码
function createCode($userId)
{
	static $sourceString = [
			0,1,2,3,4,5,6,7,8,9,10,
			'a','b','c','d','e','f',
			'g','h','i','j','k','l',
			'm','n','o','p','q','r',
			's','t','u','v','w','x',
			'y','z'
	];

	$num = $userId;
	$code = '';
	while($num)
	{
		$mod = $num % 36;
		$num = (int)($num / 36);
		$code = "{$sourceString[$mod]}{$code}";
	}

	//判断code的长度
	if( empty($code[4]))
		str_pad($code,5,'0',STR_PAD_LEFT);

	return $code;
}


function get_signature($arrdata, $key1, $method = "sha1") {
	// API密钥：9abc1922545f0e27ccc4c7191b96c56f
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
	return $Sign;
}

    function pdf($html,$filename){
	//引入类库
	Vendor('mpdf.mpdf');
	//设置中文编码
	$mpdf=new \mPDF('zh-cn','A4', 0, '宋体', 0, 0);
	$mpdf->WriteHTML($html);
	 $path="/Public/uploadfile/pdf/".$filename.'.pdf';
	 if(file_exists(".".$path)){
		 return $path;
	 }
	 $mpdf->Output(".".$path,'F');

	   return $path;

	exit;

     }




// -------结束------------------



/**
 * 页面不存在
 */
function go_404() {
	send_http_status ( 404 );
	exit ( 'not found.' );
}

?>