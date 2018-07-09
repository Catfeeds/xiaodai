<?php
namespace Home\Controller;
Vendor('qiniu.autoload');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class MemberController extends AuthbaseController {//Authbase
	public function index() {
		$memberid = get_memberid ();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign ( 'member', $member );
		$loanlist=M('loan')->where(array('memberid'=>$memberid))->order('addtime desc')->select();
		$this->assign('loanlist',$loanlist);
		$welcome = '';
		$hour = date ( 'H', NOW_TIME );
		if ($hour < 6) {
			$welcome = '这么早~';
		} else if ($hour < 9) {
			$welcome = '早上好~';
		} else if ($hour < 12) {
			$welcome = '上午好~';
		} else if ($hour < 19) {
			$welcome = '下午好~';
		} else if ($hour < 24) {
			$welcome = '晚上好~';
		}
		$this->assign ( 'welcome', $welcome );

		$title = '用户中心';
		$this->assign ( 'title', $title );
		$this->display ();
	}

	public function getorderinfo(){
		$orderno=$_POST['orderno'];
		$memberid=get_memberid();
		$loan=M('loan')->where(array('orderno'=>$orderno))->find();

		$step=json_decode($loan['step'],true);
		$html="";
		foreach($step as $k=>$v){
			$html.="<li><div><div class=\"down-line\"></div><h1>".$v['addtime']."</h1><h2>".$v['act']."</h2></div></li>";
		}
		$refundamount=0;
		$days=0;
		if($loan['status']==2){
			$refundamount+=$loan['damount'];
			$refundamount+=$loan['interest'];
			if(strtotime($loan['deadline'])<strtotime(date("Y-m-d H:i:s"))){
				$overdue=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
				$fee=$overdue*$loan['damount']*$loan['overduefee']/100;
				$refundamount+=$fee;
				$days="已逾期".$overdue."天";
			}else{
				$days=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
			}
		}
		if($loan['status']==4){
			$refundamount=$loan['refundamount'];
		}
		$result=array();
		$result['damount']=$loan['damount'];
		$result['interest']=$loan['interest'];
		$result['refundamount']=$refundamount;
		$result['days']=$days;
		$result['status']=$loan['status'];
		$result['addtime']=$loan['addtime'];
		$result['paiedtime']=$loan['paiedtime']?$loan['paiedtime']:"暂无";
		$result['refundtime']=$loan['refundtime']?$loan['refundtime']:"暂无";
		$result['info']=$html;
		$this->ajaxReturn($result);
	}

	public function refundloan(){
		$orderno=$_POST['orderno'];
		$no = $_POST['no'];
		//var_dump($no);die;
		$discount=0;
		if($no){

			$coupon=M('coupon')->where(array('no'=>$no,'status'=>0,'timeto'=>['gt',date('Y-m-d H:i:s')]))->find();

             //var_dump($coupon);die;

			if(!$coupon){
				$result['status']=0;
				$result['info']="优惠券已使用或已过期";
				$this->ajaxReturn($result);
			}
			$discount=$coupon['amount'];

		}
		$memberid=get_memberid();
		$result=array();

		$loan=M('loan')->where(array('orderno'=>$orderno,'status'=>2))->find();
		if(!$loan){
			$result['status']=0;
			$result['info']="该贷款订单状态异常，请稍后再试";
			$this->ajaxReturn($result);
		}
		$refundamount=0;
		$refundamount+=$loan['damount'];
		$refundamount+=$loan['interest'];
		//应付
		$refundamount-=$discount;


		if(strtotime($loan['deadline'])<strtotime(date("Y-m-d H:i:s"))){
			$overdue=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
			//利息
			$fee=$overdue*$loan['damount']*$loan['overduefee']/100;
			$refundamount+=$fee;
		}


		$set=M('loan')->where(array('orderno'=>$orderno))->setField(array('refundamount'=>$refundamount,'no'=>$no,'discount'=>$discount));
		$result['status']=1;
		$result['info']=$orderno;

		$this->ajaxReturn($result);


	}

	/**
	 * 修改个人资料
	 */
	public function info() {
		$tblname = 'member';
		$memberid=get_memberid();
		$where = array ();
		$where ['id'] = $memberid;
		$db = M ( $tblname )->where ( $where )->find ();
		$this->assign ( 'db', $db );
		$title = '个人资料';
		$this->assign ( 'title', $title );
		$this->display ();
	}


	public function subinfo(){

		$memberid=get_memberid();
		$data1=$_POST;
		$result=array();
		$find=M('member')->where(array('telephone'=>$data1['telephone'],'id'=>array('neq',$memberid)))->find();
		/*if($find){
			$result['status']=0;
			$result['info']="联系电话已存在，请更换联系电话";
			$this->ajaxReturn($result);
		}*/

		if(!is_card($data1['idcard'])){
			$result['status']=0;
			$result['info']="身份证号码格式不正确";
			$this->ajaxReturn($result);
		}

		$data = $data1;
		//var_dump($data);die;
		$data['cominfo']=1;
		$edit = M ( 'member' )->where ( array('id'=>$memberid) )->data ( $data )->save ();
		if($edit===false){
			$result['status']=0;
			$result['info']="提交失败";
			$this->ajaxReturn($result);
		}

		 $result['status']=1;
		 $result['info']="提交成功";
		 $this->ajaxReturn($result);

	}





	/**
	 * 二维码
	 */
	public function qrcode(){
		$memberid = get_memberid ();

		$member = get_cache_member ( $memberid, true );

		$this->assign ( 'member', $member );

		$qrimg = '';

		//$qrimg = $this->get_qrimg ( $memberid, $member ['nickname'], $member ['headimgurl'] );
		$qrimg = $this->get_qrimg ( $member['openid'], $member ['nickname'], $member ['headimgurl'],$memberid );

		$this->assign ( 'qrimg', $qrimg );

		$title = '我的二维码';
		$this->assign ( 'title', $title );
		$this->display ();
	}


	/**
	 * 生成有背景的二维码
	 */
	private function get_qrimg($openid = '', $nickname = '', $headimg = '',$memberid='') {
		$path = './Public/uploadfile/qrcode/qr/';
		$filename = $path . ($openid) .'_'.$memberid. '.jpg';

		if (! file_exists ( $filename )) {
			$pic = './Public/uploadfile/aq.jpg';

			$weChat = get_wechat_obj ();
			$result = $weChat->getQRCode ( $memberid, 1 );

			if ($result ['ticket']) {
				$codeimg = $weChat->getQRUrl ( $result ['ticket'] );
			} else {
				return ('denied');
			}


			$water = get_url_img ( $codeimg );
//			dump($water);
			$water = '.'. get_thumb ( $water, 252, 252 );
//			dump($water);
			// 加二维码
			if (is_file ( $water )) {
				$ctrl = new \Think\Image ( 1, $pic );
				$locate = array (
						236,
						502
				);
				$ctrl->water ( $water, $locate, 100 )->save ( $filename );
			}


			// 加头像
			$water = get_url_img ( $headimg );
			$water =  '.'. get_thumb ( $water, 121, 121 );

			if (is_file ( $water )) {
				$ctrl = new \Think\Image ( 1, $filename );
				$locate = array (
						30,
						1143
				);
				$ctrl->water ( $water, $locate, 100 )->save ( $filename );
			}

			if (is_file ( $filename )) {
				// 加水印
				$ctrl = new \Think\Image ( 1, $filename );
				$locate = array (
						258,
						1165
				);
				$font = './ThinkPHP/Library/Think/Verify/zhttfs/1.ttf';
				$ctrl->text ( $nickname, $font, 24, '#ffff00', $locate )->save ( $filename );
			}
		}
		$filename = str_replace ( './Public/', '/Public/', $filename );
		return $filename;
	}

	/**
	 * 我的活动
	 */
	public function activep() {
		$title = '我的活动';
		$where = array ();
		$where ['memberid'] = get_memberid ();

//		if (is_number ( $status )) {
//			$where ['status'] [] = $status;
//		}

		$list = M ( "content_active_memberp" )->where ( $where )->order ( 'id desc' )->select ();
		$this->assign ( "list", $list );
		$this->assign ( 'barname', '会员中心' );
		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 我的收藏
	 *
	 * @param number $id
	 * @param number $p
	 */
	public function fav() {
		$this->assign ( 'title', '我的收藏' );
		$this->display ();
	}

	/**
	 * 收藏列表
	 *
	 * @param number $id
	 * @param number $p
	 */
	public function getFavLists($p = 1) {
		$guid = get_memberid ();
		$params = array ();
		$params ['guid'] = $guid;
		$result = api ( 'Member/favlist', $params );
		if ($result ['err_code']) {
			$this->error ( $result ['err_msg'] );
		} else {
			$this->assign ( "status1list", C ( 'PRODUCTSTATUS' ) );
			$this->assign ( 'list', $result ['err_msg'] );
			$this->display ( 'getFavLists' );
		}
	}

	/**
	 * 新增/编辑收货地址
	 */
	public function editAddress($id = 0, $fromurl = '') {
		$tblname = 'address';
		$memberid = get_memberid ();
		$where = array ();
		$where ['id'] = $id;
		$where ['memberid'] = $memberid;
		$db = M ( $tblname )->find ( $id );
		$this->assign ( 'db', $db );

		if (IS_POST) {

			$data = $_POST;
			$data ['provinceid'] = $data ['China_Province'];
			$data ['cityid'] = $data ['China_City'];
			$data ['districtid'] = $data ['China_District'];
			$data ['proname'] = get_area_name ( $data ['provinceid'] );
			$data ['cityname'] = get_area_name ( $data ['cityid'] );
			$data ['disname'] = get_area_name ( $data ['districtid'] );
			unset ( $data ['China_Province'], $data ['China_City'], $data ['China_District'] );

			if (isN ( $data ['username'] )) {
				$this->error ( '对不起，姓名不能为空！' );
			}
			if (isN ( $data ['telephone'] )) {
				$this->error ( '对不起，手机号不能为空！' );
			}
			if (! is_mobile ( $data ['telephone'] )) {
				$this->error ( '对不起，手机号不正确！' );
			}

			if (isN ( $data ['districtid'] )) {
				$this->error ( '对不起，必须选择所在地区！' );
			}
			if (isN ( $data ['address'] )) {
				$this->error ( '对不起，详细地址不能为空！' );
			}

			if ($db) {
				M ( $tblname )->where ( $where )->data ( $data )->save ();
				$this->success ( '恭喜，收货地址修改成功！' );
			} else {
				$data ['memberid'] = $memberid;
				$add = M ( $tblname )->data ( $data )->add ();
				$this->success ( '恭喜，收货地址添加成功！',$data['fromurl'] );
			}
		} else {
			$this->assign ( 'barname', '会员中心' );
			if ($id) {
				$title = '编辑收货地址';
			} else {
				$title = '新增收货地址';
			}
			if ($fromurl) {
				$fromurl = think_decrypt ( $fromurl );
			}
			$this->assign ( 'fromurl', $fromurl );
			$this->assign ( 'title', $title );
			$this->display ();
		}
	}

	/**
	 * 我的订单
	 */
	public function order($type='',$status='') {
		$title = '我的订单';
		$this->assign('type',$type);
		$this->assign('status',$status?$status:'10');

		$this->assign ( 'barname', '会员中心' );
		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 订单列表
	 *
	 * @param string $status
	 * @param number $p
	 */
	public function getOrderList($status = '', $p = 1, $type=1) {
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		$where ['memberid'] = get_memberid ();
		$where ['status'] [] = array (
				'notin',
				array (
						4,
						5
				)
		);
		if (is_number ( $status )) {
			$where ['status'] [] = $status;
		}

		$where['type']=$type;
		$list = M ( "order" )->where ( $where )->order ( 'id desc' )->page ( $p, $row )->select ();

		foreach ( $list as $k => $v ) {
			$list [$k] ['details'] = M ( 'order_detail' )->where ( array (
					'orderno' => $v ['orderno']
			) )->select ();

			foreach ( $list [$k]['details'] as $k1 => $v1 ) {
				$list [$k]['details'][$k1]['attrs']=json_decode($list [$k]['details'][$k1]['attrs'],true);
			}



		}

		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}

	/**
	 * 我的活动
	 */
	public function active() {
		$title = '我的活动';
		$where = array ();
		$where ['memberid'] = get_memberid ();

//		if (is_number ( $status )) {
//			$where ['status'] [] = $status;
//		}
		$where['name']=array('neq','');
		$list = M ( "content_active_member" )->where ( $where )->order ( 'id desc' )->select ();
		$this->assign ( "list", $list );
		$this->assign ( 'barname', '会员中心' );
		$this->assign ( 'title', $title );
		$this->display ();
	}




	/**
	 * 我的优惠券
	 */
	public function coupon($amount=0,$fromurl='') {
		$title = '我的优惠券';
		$memberid=get_memberid ();
		if ($fromurl) {
			$fromurl = think_decrypt ( $fromurl );
		}

		$this->assign ( 'fromurl', $fromurl );
		$this->assign ( 'amount', $amount );
        $rows = M('coupon')->where(['memberid'=>$memberid])->select();

		$this->assign ( 'title', $title );
		$this->assign ( 'rows', $rows );
		$this->display ();
	}


	/**
	 * 优惠券列表
	 *
	 * @param string $status
	 * @param number $p
	 */
	public function getCouponList($status = '',$amount=0,$fromurl='', $p = 1) {
		// 分页
		$memberid=get_memberid ();
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		$where ['memberid'] = $memberid;
		$now=time_format();
		if($amount>0){
			$where['cost']=array('elt',$amount);
			$where['status']=0;
			$where ['timefrom'] = array (
					'elt',
					$now
			);
			$where ['timeto'] = array (
					'egt',
					$now
			);
		}
		if (is_number ( $status )) {
			if ($status == 1) {
				//已使用或过期
				$where1['_string'] = "`memberid` = ". $memberid ." AND  (`status` = 1 OR  `timeto` <= '".$now ."') ";
				$where=$where1;
			} else {
				$where ['status'] = 0;
				$where ['timefrom'] = array (
						'elt',
						$now
				);
				$where ['timeto'] = array (
						'egt',
						$now
				);
			}
		}
		$list = M ( "coupon" )->where ( $where )->order ( 'id desc' )->page ( $p, $row )->select ();
		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}
	/**
	 * 我的推荐
	 */
	public function recommend() {
		$title = '我的推荐';

		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 推荐列表
	 *
	 * @param string $status
	 * @param number $p
	 */
	public function getRecommendList($p = 1) {
		// 分页
		$memberid=get_memberid ();
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		$where ['fid'] = $memberid;
		$list = M ( "member" )->where ( $where )->order ( 'id desc' )->page ( $p, $row )->select ();
		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}


	/**
	 * 我的返利
	 */
	public function balance() {
		$title = '我的账户';
		$memberid = get_memberid ();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign('member',$member);

		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 返利列表
	 *
	 * @param string $status
	 * @param number $p
	 */
	public function getBalanceList($type = '', $p = 1) {
		// 分页
		$memberid=get_memberid ();
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		$where ['memberid'] = $memberid;
		if (is_number ( $type )) {
			if($type==1){
				$where ['type'] = array('in','1,7');
			}else{
				$where ['type'] = $type;
			}
		}
		$list = M ( "account_log" )->where ( $where )->order ( 'id desc' )->page ( $p, $row ) ->select ();
		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}
	/**
	 * 申请提现
	 */
	public function cash() {
		$title = '申请提现';

//		$memberid = get_memberid ();
//		$member=M('member')->where(array('id'=>$memberid))->find();
//		$this->assign('member',$member);

		$this->assign('statuslist',C('MEMBERSTATUS'));
		$this->assign('balance',get_member_balance());


		$this->assign ( 'title', $title );
		$this->display ();
	}

	public function cashapply(){
		if(IS_POST){
			$data=$_POST;
			if(!is_numeric($data['amount'])){
				$this->error('对不起，提现金额不正确！');
			}
			$memberid=get_memberid ();
			$balance=get_member_balance();
			if($data['amount']>$balance){
				$this->error('对不起，最多可提现金额'.$balance.'元！');
			}
			$where=array();
			$where['memberid']=$memberid;
			$where['status']=0;
			$find=M('member_cash')->where($where)->find();
			if($find){
				$this->error('对不起，您还有一笔提现未处理，请稍候再申请提现！');
			}else{
				$member=get_cache_member($memberid);
				$data['memberid']=$memberid;
				$data['username']=$member['username'];
				$data['status']=0;
				$add=M('member_cash')->data($data)->add();
				if($add){
					M('member')->where(array('id'=>$memberid))->setField('balance',array('exp','balance - '.$data['amount']));
					$this->success('恭喜，提现申请成功，请等待管理员审核！');
				}else{
					$this->error('对不起，申请失败，请重试！');
				}
			}
		}else{
			$this->error('对不起，请正确提交！');
		}
	}

	/**
	 * 返利列表
	 *
	 * @param string $status
	 * @param number $p
	 */
	public function getCashList($status = '', $p = 1) {
		// 分页
		$memberid=get_memberid ();
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		$where ['memberid'] = $memberid;
		if (is_number ( $status )) {
			$where ['status'] = $status;
		}
		$list = M ( "member_cash" )->where ( $where )->order ( 'id desc' )->page ( $p, $row ) ->select ();
		$this->assign ( "list", $list );

		$this->assign('statuslist',C('MEMBERSTATUS'));
		$this->assign ( 'p', $p );
		$this->display ();
	}

	/**
	 * 我的订单
	 *
	 * @param string $orderno
	 */
	public function orderView($orderno = '') {
		$memberid = get_memberid ();
		$where = array ();
		$where ['memberid'] = $memberid;
		$where ['orderno'] = $orderno;
		$db = M ( 'order' )->where ( $where )->find ();
		if (! $db) {
			go_404 ();
		}
		$this->assign ( 'db', $db );

		$detaillist=M ( 'order_detail' )->where ( array('orderno'=>$orderno) )->select();

		foreach($detaillist as $key=>$val){
			$detaillist[$key]['attrs']=json_decode($detaillist[$key]['attrs'],true);
		}

		$this->assign('detaillist',$detaillist);

		if($db['expressno']){
			$expressname=M('content_express')->where(array('id'=>$db['expressid']))->getField('title');
			$express=M('content_express')->where(array('id'=>$db['expressid']))->getField('siteurl');
			$expressurl=str_replace('POSTID', $db['expressno'],$express);
			$this->assign('expressurl',$expressurl);
			$this->assign('expressname',$expressname);
		}

		// 状态标识
		$this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );
		$this->assign ( "paymethodlist", C ( 'PAYMETHOD' ) );

		$title = '订单详情';
		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 取消订单
	 *
	 * @param number $id
	 */
	public function cancelOrder($orderno = '') {
		$where = array ();
		$memberid = get_memberid ();
		$where ['memberid'] = $memberid;
		$where ['orderno'] = $orderno;
		$where ['status'] = 0;
		$find = M ( 'order' )->where ( $where )->find ();
		if ($find) {
			switch_order($orderno,4);
			$this->success ( '恭喜，订单取消成功！' );
		} else {
			$this->error ( '对不起，取消失败！' );
		}
	}

	/**
	 * 确认订单
	 * @param string $orderno
	 */
	public function confirmOrder($orderno = '') {
		$where = array ();
		$memberid = get_memberid ();
		$where ['memberid'] = $memberid;
		$where ['orderno'] = $orderno;
		$where ['status'] = 2;
		$find = M ( 'order' )->where ( $where )->find ();
		if ($find) {
			switch_order($orderno,3);
			$this->success ( '恭喜，订单确认成功！' );
		} else {
			$this->error ( '对不起，确认失败！' );
		}
	}

	/**
	 * 我的订单
	 */
	public function address() {
		$title = '我的配送地址';
		$this->assign ( 'barname', '会员中心' );
		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 设置默认地址
	 *
	 * @param number $id
	 */
	public function setAddress($id = 0) {
		$where = array ();
		$memberid = get_memberid ();
		$where ['memberid'] = $memberid;
		$where ['id'] = $id;
		$find = M ( 'address' )->where ( $where )->find ();
		if ($find) {
			M ( 'address' )->where ( array (
					'memberid' => $memberid
			) )->setField ( 'isdefault', 0 );
			M ( 'address' )->where ( array (
					'id' => $find ['id']
			) )->setField ( 'isdefault', 1 );
			$this->success ( '恭喜，默认收货地址设置成功！' );
		} else {
			$this->error ( '对不起，设置失败！' );
		}
	}
	/**
	 * 删除收货地址
	 *
	 * @param number $id
	 */
	public function deleteAddress($id = 0) {
		$where = array ();
		$memberid = get_memberid ();
		$where ['memberid'] = $memberid;
		$where ['id'] = $id;
		$find = M ( 'address' )->where ( $where )->find ();
		if ($find) {
			M ( 'address' )->where ( array (
					'id' => $find ['id']
			) )->delete ();
			$this->success ( '恭喜，收货地址删除成功！' );
		} else {
			$this->error ( '对不起，删除失败！' );
		}
	}
	/**
	 * 订单列表
	 *
	 * @param string $status
	 * @param number $p
	 */
	public function getAddressList($p = 1) {
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		$where ['memberid'] = get_memberid ();
		$list = M ( "address" )->where ( $where )->order ( 'isdefault desc,id desc' )->page ( $p, $row )->select ();
		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}


	/**
	 * 积分记录
	 *
	 *
	 */
	public function point(){
		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign('member',$member);
		$pointlist=M('point_log')->where(array('memberid'=>$memberid))->select();
		$this->assign('pointlist',$pointlist);
		$this->display();
	}

	/**
	 * 我的充值
	 */
	public function credit() {
		$title = '我的消费积分';
		$memberid = get_memberid ();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign('member',$member);

		$credit=get_w_r_credit($memberid);
		$this->assign('credit',$credit);

		$last=M('account_log')->where(array('memberid'=>$memberid))->limit(1)->order('addtime desc')->select();

		$date=strtotime(date('y-m-d h:i:s'));
		$date1=strtotime($last[0]['addtime']);
		$cha=ceil(($date-$date1)/86400);
		$iscash=false;
		if($cha>36*30){
			$iscash=true;
		}
		$this->assign('iscash',$iscash);

		$this->assign ( 'title', $title );
		$this->display ();
	}

	/**
	 * 在线充值
	 */
	public function charge(){
		$title = '在线充值';
		$memberid=get_memberid();
		$balance=m('member')->where(array('id'=>$memberid))->getField('balance');
		$this->assign('balance',$balance);
		$this->assign ( 'barname', '会员中心' );
		$this->assign ( 'title', $title );
		$this->display();
	}

	/**
	 * 在线充值付款
	 */
	public function setchargeorder(){

		if($_POST){
			$data=$_POST;
			$data['orderno'] = get_order_no ();
			$data['memberid']=get_memberid();
			$data['type']=1;

			if($data['paymethod']==2){
				$memberid=get_memberid();
				$balance=m('member')->where(array('id'=>$memberid))->getField('balance');
				if($balance<$data['amount']){
					$result['status']=0;
					$result['info']='佣金账户积分不足';
					$this->ajaxReturn($result);
				}

				$data['status']=1;
				$data['paytime']=date('Y-m-d H:i:s');
				$data['payinfo']='佣金账户支付';
			}else{
				$data['status']=0;
			}


			$data['act']='在线充值';
			$data['addtime']=date('Y-m-d H:i:s');
			$add=M('account_log')->data($data)->add();
			if($add!==false){
				if($data['paymethod']==2){
					M('member')->where(array('id'=>$data['memberid']))->setField('credit',array('exp','credit + '.$data['amount']));
					M('member')->where(array('id'=>$data['memberid']))->setField('balance',array('exp','balance - '.$data['amount']));
					//写佣金支出日志
					write_balance_log($data['memberid'],$data['amount'],6,'使用佣金账户充值消费积分',$data['memberid']);

					$result['status']=1;
					$result['info']='充值成功';
					$this->ajaxReturn($result);
				}else{
					$result['status']=1;
					$result['info']=$data['orderno'];
					$this->ajaxReturn($result);
				}

			}else{
				$result['status']=0;
				$result['info']='充值失败！';
			}
		}

	}

	/**
	 * 我的邀请列表
	 */
	public function invite(){

		$memberid = get_memberid ();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign('member',$member);

		$invitelist=M('member')->where(array('fid'=>$memberid))->select();
		for($i=0;$i<count($invitelist);$i++){
			$total=M('order')->where(array('memberid'=>$invitelist[$i]['id'],'paystatus'=>1,'type'=>1))->field('sum(total) as total')->group('memberid')->select();
//			$refee=M('account_log')->where(array('payid'=>$invitelist[$i]['id'],'memberid'=>$memberid))->field('sum(amount) as refee')->group('payid')->select();
			$invitelist[$i]['total']=$total[0]['total']?$total[0]['total']:0;
			//$invitelist[$i]['refee']=$refee[0]['refee']?$refee[0]['refee']:0;
		}
		$this->assign('invitelist',$invitelist);



		$this->display();
	}


	/**
	 * 我的关注
	 */
	public function subscribe(){


		$this->assign('tilte','我的关注');
		$this->assign ( 'keywords','客户列表' );
		$this->assign ( 'description', '客户列表' );
		$this->display();
	}

	public function getsubscribe($p=1,$nickname='',$company=''){
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		if($nickname){
			$where['mm.nickname']=array('like','%'.$nickname.'%');
		}
		if($company){
			$where['mm.company']=array('like','%'.$company.'%');
		}

		$where['mc.memberid']=get_memberid();
		$fields='mm.*';
		$list = M ( "member as mm" )->join('my_member_custom as mc on mc.customid=mm.id')->where ( $where )->field($fields)->order ( 'id desc' )->page ( $p, $row )->select ();


		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}


	public function classes($id='',$teacherid='',$companyid=''){
		if($id){
			$id=$id?$id:0;
		}
		if($teacherid){
			$teacher=M('category_teacher')->where(array('id'=>$teacherid))->find();
		}else{
			$teacherid=$teacherid?$teacherid:0;
		}
		if($companyid){
			$company=M('category_company')->where(array('id'=>$companyid))->find();
		}else{
			$companyid=$companyid?$companyid:0;
		}

		//左侧分类
		$categorylist=M('category_classes')->order('sort asc')->select();

		$this->assign ( 'categorylist', $categorylist );

		$this->assign ( 'categoryid', $id );
		$this->assign ( 'teacherid', $teacherid );
		$this->assign ( 'companyid', $companyid );
		$this->assign('teacher',$teacher['name']?$teacher['name']:'选择老师');
		$this->assign('company',$company['name']?$company['name']:'选择单位');
		$this->assign('fromurl',think_encrypt(get_current_url()));
		$this->assign ( 'title', '我已学课程' );
		$this->assign ( 'keywords','我已学课程' );
		$this->assign ( 'description', '我已学课程' );

		$this->display ();
	}
	public function getclasses($id='',$teacherid='',$companyid='',$p=1){
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$where = array ();
		$where ['mco.memberid'] = get_memberid ();
		if($id){
			$where['cc.sortpath']=array('like','%,'.$id.',%');
		}
		if($teacherid){
			$where['cc.teacherid']=$teacherid;
		}
		if($companyid){
			$where['cc.companyid']=$companyid;
		}
		$fields="cc.*";

		$list = M ( "content_classes as cc" )->join('my_class_order as mco on mco.classid=cc.id')->where ( $where )->field($fields)->order ( 'id desc' )->page ( $p, $row )->select ();
//		dump(M ( "content_classes as cc" )->getLastSql());
		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}

	public function signup(){
		$memberid=get_memberid();
		$where=array();
		$where['mcsr.memberid']=$memberid;
		$fields="mcsr.*,mcs.title as title,mcs.address as signaddress";
		$list=M('content_signup_record as mcsr')
				->join('my_content_signup as mcs on mcs.id=mcsr.signupid')
				->where($where)
				->field($fields)
				->select();
//		dump($list);
		$this->assign('list',$list);
		$this->assign('title','我的签到记录');
		$this->display();
	}


	public function bindtel(){

		$this->assign('title','绑定手机号');
		$this->display();
	}

	public function subbind(){
		if(IS_POST){

			$data=$_POST;
			$result=array();

			if(!is_mobile($data['telephone'])){
				$result['status']=0;
				$result['info']="电话号码格式不正确";
				$this->ajaxReturn($result);
			}

			$find=M('member')->where(array('telephone'=>$data['telephone'],'openid'=>array('neq','')))->find();
			if($find){
				$result['status']=0;
				$result['info']="该电话号码已经绑定，请更换手机号绑定";
				$this->ajaxReturn($result);
			}

			$act="提交绑定";

			$verifysms=$this->verifySms(1,$data['telephone'],$data['smsverify']);

			if($verifysms['status']==0){
				$this->ajaxReturn($verifysms);
			}

			$get=M('member')->where(array('telephone'=>$data['telephone']))->find();
			if($get){
				$data['userreal']=$get['userreal'];
				$data['telephone']=$get['telephone'];
			}

			unset($data['smsverify']);

			$memberid=get_memberid();
//			dump($data);die;
			$set=M('member')->where(array('id'=>$memberid))->data($data)->save();
			if($set===false){
				$result['status']=0;
				$result['info']=$act."失败，请稍后再试";
				$this->ajaxReturn($result);
			}

			$dele=M('member')->where(array('telephone'=>$data['telephone'],'openid'=>array('eq','')))->delete();

			$result['status']=1;
			$result['info']=$act."成功";
			$this->ajaxReturn($result);

		}
	}


	public function getsmsverify( $telephone = '',$type='') {

		if (! is_mobile ( $telephone )) {
			$result['status']=0;
			$result['info']="输入的电话号码格式不正确，请重试！！";
			$this->ajaxReturn($result);
			//err ( 3004 );
		}
		if($type==1){
			$exist=M('member')->where(array('telephone'=>$telephone,'openid'=>array('neq','')))->select();
			if($exist){
				$result['status']=0;
				$result['info']="该手机号码已经绑定，请更换手机号再试";
				$this->ajaxReturn($result);
			}
		}

		$tblname = 'sms';
		$where = array ();
		$where ['telephone'] = $telephone;
		$where ['type'] = $type;
		$where ['status'] = 0;
		$time = NOW_TIME - 60 * 1;
		$where ['addtime'] = array (
				'gt',
				time_format ( $time )
		);
		$find = M ( $tblname )->where ( $where )->order ( 'id desc' )->find ();
		if ($find) {
			$result['status']=0;
			$result['info']="请勿重复发送短信";
			$this->ajaxReturn($result);
			//err ( 3008, '请稍候重试' );
		} else {
			// 随机6位数字码
			$code = rand_str ( 6, 1 );
//			$send = api_send_sms ( $type, $telephone, $code );
			$send=send_sms($telephone,$code);
			if ($send) {
				$data = array ();
				$data ['telephone'] = $telephone;
				$data ['type'] = $type;
				$data ['status'] = 0;
				$data ['code'] = $code;
				$add = M ( $tblname )->data ( $data )->add ();
				if ($add) {
					$result['status']=1;
					$result['info']="验证码已发送，请在10分钟内输入验证码，否则需要重新发送验证码！";
					$this->ajaxReturn($result);
					//ret ( $code );
				} else {
					$result['status']=0;
					$result['info']="发送短信失败，请重试！！";
					$this->ajaxReturn($result);
				}
			} else {
				$result['status']=0;
				$result['info']="发送短信失败，请重试！！";
				$this->ajaxReturn($result);
			}
		}



	}


	/**
	 * 验证短信
	 *
	 * @param string $type
	 * @param string $telephone
	 */
	public function verifySms($type = '', $mobile = '', $code = '') {

		$tblname = 'sms';
		$where = array ();
		$where ['mobile'] = $mobile;
		$where ['type'] = $type;
		$where ['code'] = $code;
		$where ['status'] = 0;
		$time = NOW_TIME - 60 * 10;
		$where ['addtime'] = array (
				'gt',
				time_format ( $time )
		);
		$find = M ( $tblname )->where ( $where )->order ( 'id desc' )->find ();

		if ($find) {
			$data = array ();
			$data ['verifytime'] = time_format ();
			$data ['status'] = 1;
			M ( $tblname )->where ( $where )->data ( $data )->save ();
			$result['status']=1;
			$result['info']="短信验证码正确";
			return $result;
		} else {
			$result['status']=0;
			$result['info']="验证码不正确或已失效请重新获取！";
			return $result;
		}
	}


	/**
	 * 订单提交成功页面
	 */
	public function ordersuccess($orderno,$status){

		$tblname=get_order_type($orderno);
		//var_dump($tblname);die;

		$arr=explode('-',$orderno);
		$this->assign('type',$arr[0]);

		$order=M($tblname)->where(array('orderno'=>$orderno))->find();


		if($status==2){
			$title="提交失败";
		}else{
			$title="提交成功";
		}

		$this->assign('status',$status);
		$this->assign('order',$order);
		$this->assign('title',$title);
		$this->display();
	}

    public function saveqiniuimg(){
        $key=$_POST['key'];
        if(!$key){

            $result['status']=0;
            $result['info']="上传失败";
            $this->ajaxReturn($result);
        }
        $filename=$_POST['filename'];
        $result=array();
        $picurl='https://s3-ap-southeast-1.amazonaws.com/daikuan/'.$key;
        $memberid=get_memberid();

        $set=M('member')->where(array('id'=>$memberid))->setField('headimgurl',$picurl);

        if($set===false){
            $result['status']=0;
            $result['info']="上传失败";
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['info']=$picurl;
        $this->ajaxReturn($result);
    }


	public function saveimg(){
		$pic=$_POST['pic'];
		$result=array();
		$picurl=base64Toimg($pic);
		if(!$picurl){
			$result['status']=0;
			$result['info']="上传失败";
			$this->ajaxReturn($result);
		}
		$memberid=get_memberid();

		$set=M('member')->where(array('id'=>$memberid))->setField('headimgurl',$picurl);

		if($set===false){
			$result['status']=0;
			$result['info']="上传失败";
			$this->ajaxReturn($result);
		}

		$result['status']=1;
		$result['info']=$picurl;
		$this->ajaxReturn($result);
	}


	public function contact(){
		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$contacts=json_decode($member['contactinfo'],true);
		$this->assign('contacts',$contacts);
		$this->assign('member',$member);
		$this->assign('title','紧急联系人');
		$this->display();
	}


	public function subcontact(){
		$seq=explode(',',$_POST['seq']);
		$seq=array_filter($seq);
		$relationship=explode(',',$_POST['relationship']);
		$relationship=array_filter($relationship);
		$username=explode(',',$_POST['username']);
		$username=array_filter($username);
		$telephone=explode(',',$_POST['telephone']);
		$telephone=array_filter($telephone);
		$info=array();
		$result=array();
		foreach($seq as $k=>$v){
			if(!is_mobile($telephone[$k])){
				$result['status']=0;
				$result['info']="第".($k+1)."个联系人电话号码格式不正确";
				$this->ajaxReturn($result);
			}
			$info[$k]['seq']=$v;
			$info[$k]['relationship']=$relationship[$k];
			$info[$k]['username']=$username[$k];
			$info[$k]['telephone']=$telephone[$k];
		}

		$info=json_encode($info,JSON_UNESCAPED_UNICODE);

		$memberid=get_memberid();

		$save=M('member')->where(array('id'=>$memberid))->setField(array('contactinfo'=>$info,'contacts'=>1));
		if($save===false){
			$result['status']=0;
			$result['info']="提交失败，请稍后重试";
			$this->ajaxReturn($result);
		}
		$result['status']=1;
		$result['info']="提交成功";
		$this->ajaxReturn($result);

	}


	public function tmobile(){
		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign('member',$member);
		$this->assign('title','运营商信息授权');
		$this->display();
	}

	public function subtmobile(){
		$memberid=get_memberid();
		$telephone=$_POST['telephone'];
		$servicepwd=$_POST['servicepwd'];
		$step=$_POST['step'];
		$PatchCode=$_POST['PatchCode'];
		$taskNo=$_POST['taskno'];
		$smscode=$_POST['smscode'];
		$PatchCode=str_replace('[','',$PatchCode);
		$PatchCode=str_replace(']','',$PatchCode);
		$idcardno=$_POST['idcard'];
		$username=$_POST['username'];

		$member=M('member')->where(array('id'=>$memberid))->find();

		$baseurl="https://api.ibeesaas.com";
		$appkey="dcxadyh4kp";
		$ak="SHmSJHhvDaq5bY33Bpiz1mDOZHbwQQjp";
		$sk="ciNoa4SPUIMWVvq7k6y19HnbD4AYT9eO";
		$tv="v2";
		vendor("Zhengxin.TokenHelper");
		$tokenobj=new \TokenHelper($ak,$sk,$tv);

		$currenttime=strtotime(date("Y-m-d H:i:s"))+5*60;

		$method="POST";

		$tmobileinfo=json_decode($member['tmobileinfo'],true);
		$find=M('member_info')->where(array('memberid'=>$memberid))->find();
			
		if(!$find){
			$dataf=array();
			$dataf['memberid']=$memberid;
			M('member_info')->data($dataf)->add();
		}

		$result=json_decode($find['tmobileinfo'],true);
		if($result['code']=="general_0" && $result['taskStatus']=="success"){
			$return['status']=2;
			$return['info']="已授权成功";

		}
		else{

			$return=array();
			$taskno="";
			switch ($step){
				case "first":
					$body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/tmobilecallback.html";
					$body['data']['loginType']=0;
					//$body['data']['sync']=1;
					$body['data']['account']=$telephone;
					$body['data']['password']=$servicepwd;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);

					$urlpath="/daas/v1/tasks";
					$querystring="appKey={$appkey}&taskType=carrier";
					$url=$baseurl.$urlpath."?".$querystring;

					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					
					//dump($result);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$result['taskNo'];
					//$taskno="12fe252a49f643c883d430c3bf0dcc731524459231523";
					//$result['taskStatus']="pending";

					break;
				case "carrier_1":
					$body=array();
					$body['patchCode']=intval($PatchCode);
					$body['data']['account']=$telephone;
					$body['data']['password']=$servicepwd;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					//dump($body);die;

					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;

					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$result['taskNo'];
					

					break;
				case "carrier_4":
				case "carrier_5":
				case "carrier_6":
				case "carrier_10":
				case "carrier_11":
					$body=array();
					$body['patchCode']=intval($PatchCode);
					//$body['data']['sync']=1;
					$body['data']=$smscode;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					//dump($body);die;
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$taskNo;
					break;
				case "carrier_16":
				case "carrier_17":
					$body=array();
					$body['patchCode']=intval($PatchCode);
					$body['data']['idCardNo']=$idcardno;
					$body['data']['username']=$username;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$taskNo;
					break;
				default:
					break;
			}

			$datarec=array();
			$datarec['memberid']=$memberid;
			$datarec['taskno']=$taskno;
			$datarec['telephone']=$telephone;
			$datarec['servicepwd']=$servicepwd;
			$exist=M('member_tmobile_record')->where(array('taskno'=>$taskno))->find();
			if(!$exist){
				M('member_tmobile_record')->data($datarec)->add();
			}
			if( $result['taskStatus']=="pending" || $result['taskStatus']=="processing"){
				//$info=array();
				//$info['telephone']=$telephone;
				//$info['servicepwd']=$servicepwd;
				//$info=json_encode($info,JSON_UNESCAPED_UNICODE);
				//$set=M('member')->where(array('id'=>$memberid))->setField(array('tmobileinfo'=>$info,'tmobile'=>1));
				$return['status']=1;
				$return['taskno']=$taskno;
			}else{
				$return['status']=0;
				$return['info']=$result['message'];
				$return['taskno']=$taskno;
			}


		}

		$this->ajaxReturn($return);

	}
	public function zfb(){
		$memberid=get_memberid();
		$rows = M('member')->where(['memberid'=>$memberid])->select();
	    
		$this->assign('title','支付宝授权');
		$this->assign('rows','$rows');
		$this->display();
	}

	//支付宝授权
    public function subzfb(){
		$memberid=get_memberid();
		$taskNo=$_POST['taskno'];
		$step=$_POST['step'];
		$smscode=$_POST['smscode'];
		$PatchCode=$_POST['PatchCode'];
		$PatchCode=str_replace('[','',$PatchCode);
		$PatchCode=str_replace(']','',$PatchCode);
		$member=M('member')->where(array('id'=>$memberid))->find();
		$baseurl="https://api.ibeesaas.com";
		$appkey="dcxadyh4kp";
		$ak="SHmSJHhvDaq5bY33Bpiz1mDOZHbwQQjp";
		$sk="ciNoa4SPUIMWVvq7k6y19HnbD4AYT9eO";
		$tv="v2";
		vendor("Zhengxin.TokenHelper");
		$tokenobj=new \TokenHelper($ak,$sk,$tv);
		
		$method="POST";
		
		$currenttime=strtotime(date("Y-m-d H:i:s"))+5*60;
		$find=M('member_info')->where(array('memberid'=>$memberid))->find();
		
		if(!$find){
			$dataf=array();
			$dataf['memberid']=$memberid;
			M('member_info')->data($dataf)->add();
		}
		$result=json_decode($find['alipay'],true);

		if($result['code']=="general_0" && $result['taskStatus']=="success"){
			$return['status']=2;
			$return['info']="已授权成功";
		}
		else{
		
			$return=array();
			$tasknos="";
			$img = '';
			switch ($step){
				case 'first':
					$body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/zfbcallurl.html";
					$body['data']['loginType']=1;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks";
					$querystring="appKey={$appkey}&taskType=alipay";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);

					$result['gettime']=date("Y-m-d H:i:s");
					$tasknos=$result['taskNo'];
					$img = base64Toimg($result['data']);
					//$taskNo =d7792a543ce74a11ab6e68f6c0fb8edd1526441283297;
					//processing
					break;
				case "alipay_13":
					$body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/zfbcallurl.html";
					$body['patchCode']=intval($PatchCode);
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);

					$result['gettime']=date("Y-m-d H:i:s");
					$tasknos=$taskNo;

					$img = base64Toimg($result['data']);

					//$taskno="12fe252a49f643c883d430c3bf0dcc731524459231523";
					//$result['taskStatus']="pending";
					break;
				case "alipay_5":
				    $body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/zfbcallurl.html";
					$body['patchCode']=intval($PatchCode);

					$body['data']=$smscode;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					//dump($body);die;
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);

					$result['gettime']=date("Y-m-d H:i:s");
					$tasknos=$taskNo;


					break;
				case "alipay_3":
				case "alipay_4":
				case "alipay_9":
				case "alipay_6":
				case "alipay_7":
				case "alipay_8":
				    $body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/zfbcallurl.html";
					$body['patchCode']=intval($PatchCode);
					$body['data']=$smscode;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$tasknos=$taskNo;
					$img = base64Toimg($result['data']);

					break;

				case "alipay_2":
					$body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/zfbcallurl.html";
					$body['patchCode']=intval($PatchCode);
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);

					$result['gettime']=date("Y-m-d H:i:s");
					$tasknos=$taskNo;
					$img = base64Toimg($result['data']);

					//$taskno="12fe252a49f643c883d430c3bf0dcc731524459231523";
					//$result['taskStatus']="pending";
					break;
				default:
					break;
			}

			$datarec=array();
			$datarec['memberid']=$memberid;
			$datarec['taskno']=$tasknos;
			$datarec['addtime'] = date('Y-m-d H:i:s');
			$datarec['img']=$img;

			//M('member_zfb_record')->where(['memberid'=>4])->delete();die;
			$exist=M('member_zfb_record')->where(['taskno'=>$datarec['taskno']])->find();
			if(!$exist){
				 M('member_zfb_record')->data($datarec)->add();
			}

			if( $result['taskStatus']=="pending" || $result['taskStatus']=="processing"){
				$return['status']=1;
				$return['taskno']=$tasknos;
			}else{
				$return['status']=0;
				$return['info']=$result['message'];
				$return['taskno']=$tasknos;
			}

		}


		$this->ajaxReturn($return);
	}
	public function getzfbdata($taskno){

		$memberid=get_memberid();
		$detail=M('member_zfb')->where(array('taskno'=>$taskno,'isget'=>0))->order('addtime desc')->limit(1)->select();
		$detail=$detail[0];
		$return=array();
		if(!$detail){
			$return['status']=0;
			$this->ajaxReturn($return);
		}
		M("member_zfb")->where(array('taskno'=>$taskno,'isget'=>0))->setField('isget',1);//表示该信息已经读取过。
		$result=json_decode($detail['data'],true);
		$code=$result['code'];

		switch($code){
			case "alipay_13":
				$return['status']=1;
				$return['img'] = $result['data'];
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_13';
			break;
				case "alipay_3":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_3';
				break;
			case "alipay_4":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_4';
				break;
			case "alipay_6":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_6';
				break;
			case "alipay_7":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_7';
				break;
			case "alipay_9":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_9';
				break;
			case "alipay_5":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_5';
				break;
			case "alipay_8":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_8';
				break;
			case "alipay_2":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='alipay_2';
				break;
			case "general_0":
				$set=M('member')->where(array('id'=>$memberid))->setField(array('zfb'=>1));
				$return['status']=1;
				$return['info']="授权成功";
				$return['step']='general_0';
				break;
			default:
				$return['status']=0;
				$return['info']=$result['message'];
				$return['step']='first';
				break;
		}
		$this->ajaxReturn($return);

	}


	public function getdatadetail($taskno,$telephone,$servicepwd){

		$memberid=get_memberid();
		$detail=M('member_tmobile_data')->where(array('taskno'=>$taskno,'isget'=>0))->order('addtime desc')->limit(1)->select();
		$detail=$detail[0];
		//dump($detail);die;
		$return=array();
		if(!$detail){
			$return['status']=0;
			$this->ajaxReturn($return);
		}
		M("member_tmobile_data")->where(array('taskno'=>$taskno,'isget'=>0))->setField('isget',1);//表示该信息已经读取过。


		$result=json_decode($detail['datadetail'],true);

//
		$code=$result['code'];
		//dump($code);die;
		switch($code){
			case "carrier_1":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_1';
				break;
			case "carrier_4":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_4';
				break;
			case "carrier_5":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_5';
				break;
			case "carrier_6":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_6';
				break;
			case "carrier_9":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_9';
				break;
			case "carrier_10":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_10';
				break;
			case "carrier_11":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_11';
				break;
			case "carrier_13":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_13';
				break;
			case "carrier_16":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_16';
				break;
			case "carrier_17":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='carrier_17';
				break;
			case "general_0":
				$info=array();
				$info['telephone']=$telephone;
				$info['servicepwd']=$servicepwd;
				$info=json_encode($info,JSON_UNESCAPED_UNICODE);
				$set=M('member')->where(array('id'=>$memberid))->setField(array('tmobileinfo'=>$info,'tmobile'=>1));
				$return['status']=1;
				$return['info']="授权成功";
				$return['step']='general_0';
				break;
			default:
				$return['status']=0;
				$return['info']=$result['message'];
				$return['step']='first';
				break;
		}
		$this->ajaxReturn($return);
	}



	public function qqsync(){
		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign('member',$member);

		$this->assign('title','QQ同步助手授权');
		$this->display();
	}

	public function subqqsync(){
		$memberid=get_memberid();
		$telephone=$_POST['telephone'];
		$pwd=$_POST['pwd'];

		$info=array();
		$result=array();
		$info['telephone']=$telephone;
		$info['pwd']=$pwd;
		$info=json_encode($info,JSON_UNESCAPED_UNICODE);
		$set=M('member')->where(array('id'=>$memberid))->setField(array('qqsyncinfo'=>$info,'qqsync'=>1));
		if($set===false){
			$result['status']=0;
			$result['info']="提交失败，请稍后重试";
			$this->ajaxReturn($result);
		}

		$result['status']=1;
		$result['info']="提交成功";
		$this->ajaxReturn($result);
	}


	public function bank(){
		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$bankinfo=json_decode($member['bankinfo'],true);
		$this->assign('bankinfo',$bankinfo);
		$this->assign('member',$member);
		$this->assign('title','银行卡信息');
		$this->display();
	}

	public function subbankinfo(){
		if(IS_POST) {
			$id = get_memberid();
			$data['username'] = $_POST['username'];
			$data['telephone'] = $_POST['telephone'];
			$data['idcard'] = $_POST['idcard'];
			$data['bankno'] = $_POST['bankno'];
			$data['name'] = $_POST['name'];

			if (!is_card($data['idcard'])) {
				$result['status'] = 0;
				$result['info'] = "身份证格式不正确";
				$this->ajaxReturn($result);
			}
			if (!is_number($data['bankno'])) {
				$result['status'] = 5;
				$result['info'] = "银行卡号格式不正确";
				$this->ajaxReturn($result);
			}
			if (!is_mobile($data['telephone'])) {
				$result['status'] = 1;
				$result['info'] = "电话号码格式不正确";
				$this->ajaxReturn($result);
			}
			$result = array();
             $row['bankinfo'] = json_encode($data,JSON_UNESCAPED_UNICODE);
			$set = M('member')->where(['id'=>$id])->save($row);
			if ($set === false) {
				$result['status'] = 3;
				$result['info'] = "提交失败，请稍后重试";
				$this->ajaxReturn($result);
			}
			$result['status'] = 4;
			$result['info'] = "提交成功";
			M('member')->where(['id'=>$id])->setField('bank',1);
			$this->ajaxReturn($result);
		}
	}

	public function applyloan(){
		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$canapply=0;
		if($member['cominfo']==1&&$member['contacts']==1&&$member['tmobile']==1&&$member['bank']==1&&$member['zmf']==1){
			$canapply=1;
		}

		$this->assign('canapply',$canapply);
		$product=M('loan_product')->where(array('status'=>1))->select();
		$this->assign('product',$product);

		$this->assign('title','申请贷款');
		$this->display();
	}

	public function subapplyloan(){

		$productid=$_POST['productid'];
		//$amount=$_POST['amount'];
		$days=$_POST['days'];

		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();

		$result=array();


		$product=M('loan_product')->where(array('id'=>$productid))->find();
		$result=array();

		/*if($amount<$product['limitstart']){
			$result['status']=0;
			$result['info']="贷款金额不能小于最低限额";
			$this->ajaxReturn($result);
		}

		if($amount>$product['limitend']){
			$result['status']=0;
			$result['info']="贷款金额不能大于最高限额";
			$this->ajaxReturn($result);
		}*/

		$data=array();
		$step=array();
		$step[0]['addtime']=date("Y-m-d H:i:s");
		$step[0]['act']="提交贷款申请";
		$step=json_encode($step,JSON_UNESCAPED_UNICODE);
		$orderno="D-".get_order_no();
		$data['orderno']=$orderno;
		$data['memberid']=$memberid;
		$data['idcard']=$member['idcard'];
		$data['telephone']=$member['telephone'];
		$data['productid']=$productid;
		$data['productinfo']=json_encode($product,JSON_UNESCAPED_UNICODE);
		//$data['damount']=$amount;
		//$data['interestrate']=$product['interest'];
		//$data['interest']=$product['interest']*$amount/100;
		//$data['amount']=$amount+$data['interest'];
		$data['days']=$days;
		$deadline = date('Y-m-d',strtotime('+ '.($days-1).' days')).' 23:59:59';
		$data['deadline']=get_date_add(strtotime($deadline));
		$data['step']=$step;
		$data['overduefee']=$product['overdue'];
		$find=M('loan')->where(array('memberid'=>$memberid,'status'=>array('lt',4)))->find();
		if($find){
			$result['status']=0;
			$result['info']="您还有贷款未还款，请还款后再申请贷款";
			$this->ajaxReturn($result);
		}

		$add=M('loan')->data($data)->add();

		if($add===false){
			$result['status']=0;
			$result['info']="申请失败，请稍后再试";
			$this->ajaxReturn($result);
		}

		$result['status']=1;
		$result['info']="申请成功,请等待审核";
		$this->ajaxReturn($result);


	}



	public function getapplyloan(){
		$id=$_POST['id'];
		$days=M('loan_product')->where(array('id'=>$id))->getField('days');
		$days=explode(',',$days);
		$html="";
		if(count($days)<=0){
			$html="<option value=''>该产品未设置期限，请选择其他产品</option>";
		}else{
			foreach($days as $k=>$v){
				$html.="<option value='".$v."''>".$v."天</option>";
			}
		}

		$this->ajaxReturn($html);
	}

	public function setfeeanddeadline(){
		$productid=$_POST['productid'];
		$day=$_POST['day'];
		//$amount=$_POST['amount'];
		$product=M('loan_product')->where(array('id'=>$productid))->find();
		$result=array();

		/*if($amount<$product['limitstart']){
			$result['status']=0;
			$result['info']="贷款金额不能小于最低限额";
			$this->ajaxReturn($result);
		}

		if($amount>$product['limitend']){
			$result['status']=0;
			$result['info']="贷款金额不能大于最高限额";
			$this->ajaxReturn($result);
		}*/


		/*$result['interestrate']=$product['interest'];
		$result['interest']=$product['interest']*$amount/100;*/
        $deadline = date('Y-m-d',strtotime('+ '.($day-1).' days')).' 23:59:59';
        $result['deadline']=get_date_add(strtotime($deadline));
		//$result['deadline']=get_date_add(strtotime(date("Y-m-d H:i:s")),$day);
		$result['status']=1;
		$this->ajaxReturn($result);


	}

	/**
	 * 征信查询接口操作
	 */
	public function getinterfacedata(){
		$type=$_POST['type'];
		$id=$_POST['id'];
		$retry=$_POST['retry'];
		$member=M('member')->where(array('id'=>$id))->find();

		$baseurl="https://api.ibeesaas.com";
		$appkey="dcxadyh4kp";
		$ak="SHmSJHhvDaq5bY33Bpiz1mDOZHbwQQjp";
		$sk="ciNoa4SPUIMWVvq7k6y19HnbD4AYT9eO";
		$tv="v2";
		vendor("Zhengxin.TokenHelper");
		$tokenobj=new \TokenHelper($ak,$sk,$tv);
		$urlpath="/daas/v1/tasks";

		$querystring="appKey={$appkey}&taskType=$type";
		$currenttime=strtotime(date("Y-m-d H:i:s"))+5*60;
		$method="POST";
		switch($type){
			case 'carrier':
				$tmobileinfo=json_decode($member['tmobileinfo'],true);
				$find=M('member_info')->where(array('memberid'=>$id))->find();
//				dump($find);die;
				if($find['tmobileinfo'] && $retry==0){
					$result=json_decode($find['tmobileinfo'],true);
					if($result['taskStatus']=="success"){
						if($result['taskResult']['result']=="T"){
							$return['status']=1;
						}else{
							$return['status']=0;
						}
					}else{
						$return['status']=0;
						$result['taskResult']['message']=$result['message'];
					}
				}else{

					$url=$baseurl.$urlpath."?".$querystring;
					$body=array();
					$body['callbackurl']="";
					$body['data']['sync']=1;
					$body['data']['idCardNo']=$member['idcard'];
					$body['data']['name']=$member['username'];
					$body['data']['phoneNo']=$tmobileinfo['telephone'];
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);

					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$saveinfo=json_encode($result,JSON_UNESCAPED_UNICODE);
					$find=M('member_info')->where(array('memberid'=>$id))->find();
					if($find){
						$save=M('member_info')->where(array('memberid'=>$id))->setField('tmobilereal',$saveinfo);
					}else{
						$data=array();
						$data['memberid']=$id;
						$data['tmobilereal']=$saveinfo;
						$save=M('member_info')->data($data)->add();
					}
					$return['status']=1;
					if($result['taskStatus']=="success"){
					}else{
						$result['taskResult']['message']=$result['message'];
					}

				}
				$html="<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html.="姓名：".$member['username']." &nbsp;&nbsp;&nbsp;身份证号：".$member['idcard']."&nbsp;&nbsp;&nbsp;电话号码：".$tmobileinfo['telephone']."</p></div>";
				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>运营商实名结果：".$result['taskResult']['message']."&nbsp;&nbsp;&nbsp;查询时间：".$result['gettime']."</p></div> </div>";
				$return['html']=$html;
				$this->ajaxReturn($return);
				break;
			case "element4":
				$bankinfo=json_decode($member['bankinfo'],true);
				$find=M('member_info')->where(array('memberid'=>$id))->find();
//				dump($find);die;
				if($bankinfo){
					if($find['bankreal'] && $retry==0){
						$result=json_decode($find['bankreal'],true);
						if($result['taskStatus']=="success"){
							if($result['taskResult']['resultCode']=="0000"){
								$return['status']=1;
							}else{
								$return['status']=0;
							}
						}else{
							$return['status']=0;
							$result['taskResult']['message']=$result['message'];
						}
					}else{

						$url=$baseurl.$urlpath."?".$querystring;
						$body=array();
						$body['callbackurl']="";
						$body['data']['sync']=1;
						$body['data']['idCardNo']=$bankinfo['idcard'];
						$body['data']['cardHolderName']=$bankinfo['username'];
						$body['data']['phoneNo']=$bankinfo['telephone'];
						$body['data']['bankCardNo']=$bankinfo['bankno'];
						$body=json_encode($body,JSON_UNESCAPED_UNICODE);
						$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);

						$header[] = "X-IbeeAuth-Token:{$token}";
						$result = get_curl($url, $method, $body, $header);
						$result['gettime']=date("Y-m-d H:i:s");
						$saveinfo=json_encode($result,JSON_UNESCAPED_UNICODE);
						$find=M('member_info')->where(array('memberid'=>$id))->find();
						if($find){
							$save=M('member_info')->where(array('memberid'=>$id))->setField('bankreal',$saveinfo);
						}else{
							$data=array();
							$data['memberid']=$id;
							$data['bankreal']=$saveinfo;
							$save=M('member_info')->data($data)->add();
						}
						$return['status']=1;
						if($result['taskStatus']=="success"){
						}else{
							$result['taskResult']['message']=$result['message'];
						}

					}
				}else{
					$result['taskResult']['message']="用户未提交银行卡信息";
				}

				$html="<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html.="姓名：".$bankinfo['username']." &nbsp;&nbsp;&nbsp;身份证号：".$bankinfo['idcard']."&nbsp;&nbsp;&nbsp;电话号码：".$bankinfo['telephone']."</p>";
				$html.="<p>银行卡号：".$bankinfo['bankno']."&nbsp;&nbsp;&nbsp;开户行：".$bankinfo['bankname']."</p></div>";
				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>银行卡实名结果：".$result['taskResult']['message']."&nbsp;&nbsp;&nbsp;查询时间：".$result['gettime']."</p></div> </div>";
				$return['html']=$html;
				$this->ajaxReturn($return);
				break;
			case "idcard_ocr":
				$cominfo=$member['cominfo'];
				$find=M('member_info')->where(array('memberid'=>$id))->find();
//				dump($find);die;
				if($cominfo){
					if($find['idcardinfo'] && $retry==0){
						$result=json_decode($find['idcardinfo'],true);
						if($result['taskStatus']=="success"){
							$return['status']=1;
						}else{
							$return['status']=0;
							$result['taskResult']['message']=$result['message'];
						}
					}else{
						$url=$baseurl.$urlpath."?".$querystring;
						$body=array();
						$body['callbackurl']="";
						$body['data']['sync']=1;
						$body['data']['frontPhoto']=imgTobase64($member['idcardimg1']);
						$body['data']['backPhoto']=imgTobase64($member['idcardimg2']);
						$body['data']['headOption']=1;
						$body=json_encode($body,JSON_UNESCAPED_UNICODE);
						$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);

						$header[] = "X-IbeeAuth-Token:{$token}";
						$result = get_curl($url, $method, $body, $header);
						$result['gettime']=date("Y-m-d H:i:s");
						$saveinfo=json_encode($result,JSON_UNESCAPED_UNICODE);
						$find=M('member_info')->where(array('memberid'=>$id))->find();
						if($find){
							$save=M('member_info')->where(array('memberid'=>$id))->setField('idcardinfo',$saveinfo);
						}else{
							$data=array();
							$data['memberid']=$id;
							$data['idcardinfo']=$saveinfo;
							$save=M('member_info')->data($data)->add();
						}
						$return['status']=1;
						if($result['taskStatus']=="success"){
						}else{
							$result['taskResult']['message']=$result['message'];
						}

					}
				}else{
					$return['status']=1;
					$result['taskStatus']="fail";
					$result['taskResult']['message']="用户未提交个人身份证信息";
				}

				$html="<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html.="姓名：".$member['username']." &nbsp;&nbsp;&nbsp;身份证号：".$member['idcard']."&nbsp;&nbsp;&nbsp;电话号码：".$member['telephone']."</p></div>";

				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
				if($result['taskStatus']=="fail"){
					$html.="<p>身份证识别结果：".$result['taskResult']['message']."&nbsp;&nbsp;&nbsp;查询时间：".$result['gettime']."</p></div>";
				}else{
					$html.="<p>身份证识别结果：</p><p>姓名：".$result['taskResult']['name']."&nbsp;&nbsp;&nbsp;性别：".$result['taskResult']['sex']."&nbsp;&nbsp;&nbsp;生日：".$result['taskResult']['birthday']."</p>";
					$html.="<p>身份证号：".$result['taskResult']['idCardNo']."&nbsp;&nbsp;&nbsp;民族：".$result['taskResult']['fork']."</p>";
					$html.="<p>地址：".$result['taskResult']['address']."&nbsp;&nbsp;&nbsp;发证机关：".$result['taskResult']['issueAuthority']."</p>";
					$html.="<p>有效期：".$result['taskResult']['vaildPriod']."&nbsp;&nbsp;&nbsp;头像：<img src='".$result['taskResult']['headPhoto']."' /></p></div> ";
				}
				$return['html']=$html;
				$this->ajaxReturn($return);
				break;

			case "black":

				$find=M('member_info')->where(array('memberid'=>$id))->find();


				//1:获取反欺诈

				$url=$baseurl.$urlpath."?".$querystring;
				$body = [
					'callbackUrl' => "",
					'data' => [
						'sync' => 1,
						'basicInfo' => [
							'phoneNumber' => ''
						],
						'otherInfo' => []
					]
				];

				$body=json_encode($body,JSON_UNESCAPED_UNICODE);
				$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
				$header[] = "X-IbeeAuth-Token:{$token}";
				$fanqiza = get_curl($url, $method, $body, $header);



				//2:网贷黑名单
				$body = [
						'callbackUrl' => "",
						'data' => [
								'sync' => 1,
								'idCardNo' => ''
						]
				];
				$body=json_encode($body,JSON_UNESCAPED_UNICODE);
				$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
				$header[] = "X-IbeeAuth-Token:{$token}";
				$hemingdan = get_curl($url, $method, $body, $header);



				//3:负面信息记录
				$body = [
						'callbackUrl' => "",
						'data' => [
								'sync' => 1,
								'basicInfo' => [
										'phoneNumber' => ''
								],
								'otherInfo' => []
						]
				];
				$body=json_encode($body,JSON_UNESCAPED_UNICODE);
				$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
				$header[] = "X-IbeeAuth-Token:{$token}";
				$fumian = get_curl($url, $method, $body, $header);

				//4:接待类产品多头
				$body = [
						'callbackUrl' => "",
						'data' => [
								'phoneNo' => 1,
						]
				];
				$body=json_encode($body,JSON_UNESCAPED_UNICODE);
				$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
				$header[] = "X-IbeeAuth-Token:{$token}";
				$duotou = get_curl($url, $method, $body, $header);



				//保存结果
				$find=M('member_info')->where(array('memberid'=>$id))->find();
				if($find){
					$save=M('member_info')
							->where(array('memberid'=>$id))
							->setField('squad',$fanqiza)
							->setField('blackloan',$hemingdan)
							->setField('list',$fumian)
							->setField('bull',$duotou);
				}else{
					$data = [
						'memberid' => $id,
						'squad' => $fanqiza,
						'blackloan' => $hemingdan,
						'list' => $fumian,
						'bull' => $duotou,
					];
					$save=M('member_info')->data($data)->add();
				}
				$return['status']=1;
















//				dump($find);die;

				if($find['blackloan'] && $retry==0){
					$result=json_decode($find['blackloan'],true);
					if($result['taskStatus']=="success"){
						$return['status']=1;
					}else{
						$return['status']=0;
						$result['taskResult']['message']=$result['message'];
					}
				}else{
					$url=$baseurl.$urlpath."?".$querystring;
					$body=array();
					$body['callbackurl']="";
					$body['data']['sync']=1;
					$body['data']['idCardNo']=$member['idcard'];
					$body['data']['name']=$member['username'];
					$body['data']['phoneNo']=$member['telephone'];

//						dump($body);die;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);

					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$saveinfo=json_encode($result,JSON_UNESCAPED_UNICODE);
					$find=M('member_info')->where(array('memberid'=>$id))->find();
					if($find){
						$save=M('member_info')->where(array('memberid'=>$id))->setField('blackloan',$saveinfo);
					}else{
						$data=array();
						$data['memberid']=$id;
						$data['blackloan']=$saveinfo;
						$save=M('member_info')->data($data)->add();
					}
					$return['status']=1;
					if($result['taskStatus']=="success"){
					}else{
						$result['taskResult']['message']=$result['message'];
					}

				}


				$html="<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html.="姓名：".$member['username']." &nbsp;&nbsp;&nbsp;身份证号：".$member['idcard']."&nbsp;&nbsp;&nbsp;电话号码：".$member['telephone']."</p></div>";

				$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
				if($result['taskStatus']=="fail"){
					$html.="<p>网贷黑名单结果：".$result['taskResult']['message']."&nbsp;&nbsp;&nbsp;查询时间：".$result['gettime']."</p></div>";
				}else{
					$html.="<p>网贷黑名单结果：</p><p>被拒或逾期次数：".$result['taskResult']['isBan']."&nbsp;&nbsp;&nbsp;查询时间：".$result['gettime']."</p></div>";
//					$html.="<p>身份证号：".$result['taskResult']['idCardNo']."&nbsp;&nbsp;&nbsp;民族：".$result['taskResult']['fork']."</p>";
//					$html.="<p>地址：".$result['taskResult']['address']."&nbsp;&nbsp;&nbsp;发证机关：".$result['taskResult']['issueAuthority']."</p>";
//					$html.="<p>有效期：".$result['taskResult']['vaildPriod']."&nbsp;&nbsp;&nbsp;头像：<img src='".$result['taskResult']['headPhoto']."' /></p></div> ";
				}
				$return['html']=$html;
				$this->ajaxReturn($return);

				break;

			case 'jd':

				break;
			default:
				break;
		}
	}

	//个人中心
	public function personal(){
		$id = get_memberid();
		$row = M('member')->where(['id'=>$id])->find();
		$rs = M('loan')->where(['memberid'=>$id])->find();
		$title = '用户中心';
		$this->assign ( 'title', $title );
		$this->assign ( 'rs', $rs );
		$this->assign ( 'row', $row );
		$this->display();
	}
	//银行卡
	public function bkcard(){
		$memberid=get_memberid();
		$members=M('member')->where(array('id'=>$memberid))->find();
		$row = json_decode($members['bankinfo'],true);
		$this->assign('row',$row);
		$this->assign('title','银行卡信息');
		$this->display();
	}
	//还款详情
	public function repayment(){
		$roderno = $_GET['roderno'];
		//var_dump($roderno);die;
		$memberid = get_memberid ();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$this->assign ( 'member', $member );
		$this->assign ( 'roderno', $roderno );
		$loan=M('loan')->where(['orderno'=>$roderno])->find();
		$step=json_decode($loan['step'],true);

		$html="";
		foreach($step as $k=>$v){
			$html.="<li><div><div class=\"down-line\"></div><h1>".$v['addtime']."</h1><h2>".$v['act']."</h2></div></li>";
		}
		$refundamount=0;
		$days=0;
		if($loan['status']==2){
			$refundamount+=$loan['damount'];
			if(strtotime($loan['deadline'])<strtotime(date("Y-m-d H:i:s"))){
				$overdue=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
				$fee=$overdue*$loan['damount']*$loan['overduefee']/100;
				$refundamount+=$fee;
				$days="已逾期".$overdue."天";
			}else{
				$days=intval(diffBetweenTwoDays($loan['deadline'],date("Y-m-d H:i:s")));
			}
		}
		if($loan['status']==4){
			$refundamount=$loan['refundamount'];
		}
        $damount = $loan['damount'];
        $delayrate=C('config.DELAY_CONFIG');

        if(!$delayrate)
        {
            $delayrate = 0.3;
        }

        $delay_fee = $damount*$delayrate;
       // $result['delay_fee'] = $delay_fee;

		$result=array();
		$result['damount']=$loan['damount'];
		$result['interest']=$delay_fee;
		$result['refundamount']=$refundamount;
		$result['days']=$days;
		$result['delayconfig'] = $delayconfig;
		$result['status']=$loan['status'];
		$result['status1']=$loan['status1'];
		$result['shenhestatus']=$loan['shenhestatus'];
		$result['refusereason']=$loan['refusereason'];
		$result['addtime']=$loan['addtime'];
		$result['daozhang']=$loan['daozhang'];
		$result['deadline']=$loan['deadline'];
		$result['paiedtime']=$loan['paiedtime']?$loan['paiedtime']:"暂无";
		$result['refundtime']=$loan['refundtime']?$loan['refundtime']:"暂无";
		$result['info']=$html;
		//var_dump($result);die;
		$condition['memberid'] = $memberid;
		$condition['status'] = 0;
		$condition['_logic'] = 'and';
		$coupons = M('coupon')->where($condition)->select();
		$this->assign('coupons',$coupons);
		$this->assign('roderno',$roderno);
		$this->assign('result',$result);
		$this->display();

	}
	//扣除优惠卷
      public function deduct(){
		  $no = $_POST['no'];
		  $amount = M('coupon')->where(['no'=>$no])->find();
		  $a = $amount['amount'];
		  $this->ajaxReturn($a);
	  }
     //延期
	public function delay(){

		$data['orderno'] = $_POST['orderno'];

		$row = M('loan')->where(['orderno'=>$data['orderno']])->find();

		$data['addtime'] = date('Y-m-d H:i:s');
		//未支付
		$data['status'] = 0;
		$data['money'] =  $row['interest'];

		$data['days'] = 3;
		$data['dealno'] ='H-'.get_order_no();
        $rs = M('delay')->add($data);
		$result=[];
		if($rs){
			$result['status']=1;
			$result['info'] = $data['dealno'];
		}else{
			$result['status']=0;
			$result['info'] = '获取订单失败，请稍后在试';
		}
		$this->ajaxReturn($result);

	}
    //淘宝认证
	public function taobao(){
		$memberid=get_memberid();
		$rows = M('member_taobao')->where(['memberid'=>$memberid])->select();
		//var_dump($rows);
		$this->assign('title','淘宝授权');
		$this->display();
	}

	//淘宝授权
	public function subtaobao(){
		$memberid=get_memberid();
		$taskNo=$_POST['taskno'];
		$step=$_POST['step'];
		$smscode=$_POST['smscode'];
		$PatchCode=$_POST['PatchCode'];
		$PatchCode=str_replace('[','',$PatchCode);
		$PatchCode=str_replace(']','',$PatchCode);
		$member=M('member')->where(array('id'=>$memberid))->find();
		$baseurl="https://api.ibeesaas.com";
		$appkey="dcxadyh4kp";
		$ak="SHmSJHhvDaq5bY33Bpiz1mDOZHbwQQjp";
		$sk="ciNoa4SPUIMWVvq7k6y19HnbD4AYT9eO";
		$tv="v2";
		vendor("Zhengxin.TokenHelper");
		$tokenobj=new \TokenHelper($ak,$sk,$tv);

		$method="POST";

		$currenttime=strtotime(date("Y-m-d H:i:s"))+5*60;
		$find=M('member_info')->where(array('memberid'=>$memberid))->find();
		if(!$find){
			$dataf=array();
			$dataf['memberid']=$memberid;
			M('member_info')->data($dataf)->add();
		}
		$result=json_decode($find['taobao'],true);

		if($result['code']=="general_0" && $result['taskStatus']=="success"){
			$return['status']=2;
			$return['info']="已授权成功";
		}
		else{

			$return=array();
			$taskno="";
			$img = '';
			switch ($step){
				case 'first':
					$body=array();

					$body['callbackUrl']="http://www.360rongloan.org/Login/taobaocallurl.html";
					$body['data']['loginType']=1;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks";
					$querystring="appKey={$appkey}&taskType=taobao";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$result['taskNo'];
					$img = base64Toimg($result['data']);
					//$taskNo =d7792a543ce74a11ab6e68f6c0fb8edd1526441283297;
					//processing
					break;
				case "taobao_6":
					$body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/taobaocallurl.html";
					$body['patchCode']=intval($PatchCode);
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					var_dump(6);
					var_dump($result);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$result['taskNo'];
					$img = base64Toimg($result['data']);
					var_dump($img);
					//$taskno="12fe252a49f643c883d430c3bf0dcc731524459231523";
					//$result['taskStatus']="pending";
					break;
				case "taobao_13":
					$body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/taobaocallurl.html";
					$body['patchCode']=intval($PatchCode);

					$body['data']=$smscode;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					//dump($body);die;
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					var_dump(13);
					var_dump($result);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$result['taskNo'];
					$img = base64Toimg($result['data']);
                    var_dump($img);
					break;
				case "taobao_7":
				case "taobao_8":
					$body=array();
					$body['callbackUrl']="http://www.360rongloan.org/Login/taobaocallurl.html";
					$body['patchCode']=intval($PatchCode);
					$body['data']=$smscode;
					$body=json_encode($body,JSON_UNESCAPED_UNICODE);
					$body=str_replace("\\","",$body);
					$urlpath="/daas/v1/tasks/".$taskNo;
					$querystring="appKey={$appkey}";
					$url=$baseurl.$urlpath."?".$querystring;
					$token=$tokenobj->generateToken($urlpath,$method,$querystring,$body,$currenttime);
					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime']=date("Y-m-d H:i:s");
					$taskno=$result['taskNo'];
					$img = base64Toimg($result['data']);
					break;
				default:
					break;
			}

			$datarec=array();
			$datarec['memberid']=$memberid;
			$datarec['taskno']=$taskno;
			$datarec['addtime'] = date('Y-m-d H:i:s');
			$datarec['img']=$img;

			//M('member_zfb_record')->where(['memberid'=>4])->delete();die;
			$exist=M('member_taobao_record')->where(['taskno'=>$datarec['taskno']])->find();
			if(!$exist){
				M('member_taobao_record')->data($datarec)->add();
			}

			if( $result['taskStatus']=="pending" || $result['taskStatus']=="processing"){
				$return['status']=1;
				$return['taskno']=$taskno;
			}else{
				$return['status']=0;
				$return['info']=$result['message'];
				$return['taskno']=$taskno;
			}

			$this->ajaxReturn($return);

		}
	}
	public function gettaobaodata($taskno){
        
		$memberid=get_memberid();
		$detail=M('member_taobao')->where(['taskno'=>$taskno,'isget'=>0])->order('addtime desc')->limit(1)->select();
		

		$detail=$detail[0];
		$return=array();
		if(!$detail){
			$return['status']=0;
			$this->ajaxReturn($return);
		}
		M("member_taobao")->where(array('taskno'=>$taskno,'isget'=>0))->setField('isget',1);//表示该信息已经读取过。
		$result=json_decode($detail['data'],true);
		$code=$result['code'];
       
		switch($code){
			case "taobao_6":
				$return['status']=1;
				$return['img'] = $result['data'];
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='taobao_6';
				break;
			case "taobao_7":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='taobao_7';
				break;
			case "taobao_8":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='taobao_8';
				break;
			case "taobao_13":
				$return['status']=1;
				$return['info']=$result['message'];
				$return['PatchCode']=$result['acceptPatchCode'];
				$return['step']='taobao_13';
				break;
			case "general_0":
				$set=M('member')->where(array('id'=>$memberid))->setField(array('zfb'=>1));
				$return['status']=1;
				$return['info']="授权成功";
				$return['step']='general_0';
				break;
			default:
				$return['status']=0;
				$return['info']=$result['message'];
				$return['step']='first';
				break;
		}
		$this->ajaxReturn($return);

	}
	//工作信息
	public function work(){

		$memberid=get_memberid();
		$rows = M ('member')->where (['id'=>$memberid])->find ();
		$db = json_decode($rows['work'],true);
		$this->assign('db',$db);
        $this->display();
	}

	public function subwork(){

        $memberid=get_memberid();
		$rows=$_POST;
		$result=array();
		$find=M('member')->where(array('id'=>array('neq',$memberid)))->find();

		$data['work']=json_encode($rows,JSON_UNESCAPED_UNICODE);

		$data['gz']=1;
		$edit = M ( 'member' )->where ( array('id'=>$memberid) )->data ( $data )->save ();
		if($edit===false){
			$result['status']=0;
			$result['info']="提交失败";
			$this->ajaxReturn($result);
		}

		$result['status']=1;
		$result['info']="提交成功";
		$this->ajaxReturn($result);

	}

	public function shenqing(){

		$roderno = $_POST['orderno'];
		$a = M('loan')->where(['orderno'=>$roderno])->find()['status1'];
		$result=[];
		if($a==1){
			$result['status']=2;
			$result['info'] = '你已确认过，请等待';
		}else{
			$data['status1'] = 1;
			$rs = M('loan')->where(['orderno'=>$roderno])->save($data);
			if($rs){
				$result['status']=1;
				$result['info'] = '请等待放款';
			}else{
				$result['status']=0;
				$result['info'] = '确认失败';
			}
		}

		$this->ajaxReturn($result);
	}

	public function subzmf(){
		$memberid=get_memberid();
		$data['zmfinfo'] = $_POST['zmf'];
		$data['zmf']=1;
		$edit = M ( 'member' )->where ( array('id'=>$memberid) )->data ( $data )->save ();
		if($edit===false){
			$result['status']=0;
			$result['info']="提交失败";
			$this->ajaxReturn($result);
		}
		$result['status']=1;
		$result['info']="提交成功";
		$this->ajaxReturn($result);


	}
	public function zmf(){

		$memberid=get_memberid();
		$rows = M ('member')->where (['id'=>$memberid])->find ();
		$db = json_decode($rows['zmfinfo'],true);
		$this->assign('db',$db);
		$this->display();
	}


	public function amazontoken(){
        header('Access-Control-Allow-Origin: *');
        $aws_key = "AKIAIPXF7FQFW26HKDYA";
        $aws_secret = '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';
        $expired_reupload = 3600*24*150;
        $bucket = 'daikuan';
        $algorithm = "AWS4-HMAC-SHA256";
        $service = "s3";
        $date = gmdate('Ymd\THis\Z');
        $shortDate = gmdate('Ymd');
        $requestType = "aws4_request";
        $expires = '86400'; // 24 Hours

        $scope = array(
            $aws_key,
            $shortDate,
            "ap-southeast-1",
            $service,
            $requestType
        );
        $credentials = implode('/', $scope);

        $policy = array(
            'expiration' => gmdate('Y-m-d\TG:i:s\Z', strtotime('+6 hours')),
            'conditions' => array(
                array('bucket' => $bucket),
                array('acl' => 'public-read'),
                array('starts-with', '$key', $_POST['key']),
                array('starts-with', '$Content-Type', ''),
                array('content-length-range', 1, 104857600),
                array('x-amz-credential' => $credentials),
                array('x-amz-algorithm' => $algorithm),
                array('x-amz-date' => $date),
                array('x-amz-expires' => $expires),
            )
        );
        $base64Policy = base64_encode(json_encode($policy));

        // Signing Keys
        $dateKey = hash_hmac('sha256', $shortDate, 'AWS4' . $aws_secret, true);
        $dateRegionKey = hash_hmac('sha256', "ap-southeast-1", $dateKey, true);
        $dateRegionServiceKey = hash_hmac('sha256', $service, $dateRegionKey, true);
        $signingKey = hash_hmac('sha256', $requestType, $dateRegionServiceKey, true);

        // Signature
        $signature = hash_hmac('sha256', $base64Policy, $signingKey);

        echo json_encode(array(
            "key" => $_POST['key'],
            "online" => false,
            "policy" => $base64Policy,
            "signature" => $signature,
            "credentials" => $credentials,
            "signature" => $signature,
            "date" => $date,
            "expires" => $expires,
            "status" => $http_status
        ));

        exit();
    }
    /*
     * 生成七牛上传token
     */
    public function uptoken(){

        $accessKey = 'XwR_q3yAUhRxGPQaQRqJGZPP_joMJ40sVf7EgUy1';
        $secretKey = 'YLsDZ4W_YnHMfYiY9Y1L2SyGXZRp5OVc0yYEZOFY';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'imgserver';
// 生成上传Token
        $token = $auth->uploadToken($bucket);
        $res['uptoken'] = $token;
        $res['domain'] =  get_memberid ().time();
        echo json_encode($res);
    }

}
