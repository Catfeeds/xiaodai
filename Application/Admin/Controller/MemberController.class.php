<?php

namespace Admin\Controller;

class MemberController extends BaseController {
	public function index() {
	}
	
	/**
	 * 订单统计
	 */
	public function statistic($timefrom = '', $timeto = '', $status = '') {
		if (IS_POST) {
			if (! is_date ( $timefrom )) {
				$timefrom = date ( 'Y-01-01', NOW_TIME );
			}
			if (! is_date ( $timeto )) {
				$timeto = date ( 'Y-m-d', NOW_TIME );
			}
			$timeto = get_date_add ( $timeto, 1 );
			
			$status=rtrim($status, ",");
			if($status){
				$status=" and `status` in ($status) ";
			}else{
				$status=" and `status`=100 ";
			}
			
			$sql = "select DATE_FORMAT(`addtime`,'%Y-%m') as month,sum(`num`) as weight from my_order_detail where (`addtime` between '$timefrom' and '$timeto') $status group by month";
			$sql1 = "select DATE_FORMAT(`addtime`,'%Y-%m') as month,sum(`amount`) as amount from my_order where (`addtime` between '$timefrom' and '$timeto') $status group by month";
			$list = M ()->query ( $sql );
			$listamount=M()->query($sql1);
			$data = array ();
			foreach ( $list as $k => $v ) {
				$data [$v ['month']] = $v ['weight'];
			}
			foreach ( $listamount as $k => $v ) {
				$data1 [$v ['month']] = $v ['amount'];
			}
			
			// 循环月区间
			$months = [ ];
			$ToStartMonth = strtotime ( $timefrom ); // 转换一下
			$ToEndMonth = strtotime ( $timeto ); // 一样转换一下
			$i = false; // 开始标示
			while ( $ToStartMonth < $ToEndMonth ) {
				$NewMonth = ! $i ? date ( 'Y-m', strtotime ( '+0 Month', $ToStartMonth ) ) : date ( 'Y-m', strtotime ( '+1 Month', $ToStartMonth ) );
				$ToStartMonth = strtotime ( $NewMonth );
				$months [$NewMonth] = 0;
				$i = true;
			}
			array_pop ( $months );
			
			$arr = array_merge ( $months, $data );
//			$arr=array_merge($arr,$data1);
			$i = 1;
			$allamount=0.00;
			foreach ( $arr as $k => $v ) {
				$result ['months'] [] = [$i,$k];
				$result ['weights'] [] = [$i,$v];
				$result ['amount'] [] = [$i,$data1[$k]?$data1[$k]:0];
				$allamount+=$data1[$k]?$data1[$k]:0;
				$result['allmount']=$allamount*100;
				$i ++;
			}
			$this->success ( $result );
		} else {
			
			$control = 'Member';
			$action = 'Order';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 状态标识
			$this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );
			$this->assign ( 'title', '订单统计' );
			$this->display ();
		}
	}
	
	/**
	 * 客户列表
	 */
	public function member($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {

		$where = array ();
		$map=array();
		if (! isN ( $keyword )) {
			$map ['username'] = array (
					'like',
					'%' . $keyword . '%'
			);
			$map ['userreal'] = array (
					'like',
					'%' . $keyword . '%'
			);
			$map ['telephone'] = array (
					'like',
					'%' . $keyword . '%'
			);
			$map['_logic']='or';
			$where['_complex']=$map;
		}

		
		if (is_numeric ( $pid )) {
			$where ['pid'] = $pid;
		}
		if (is_numeric ( $status )) {
			switch($status){
				case 0:
					$where['_string']="cominfo<1 and contacts<1 and tmobile<1 and qqsync<1 and bank<1";
					break;
				case 1:
					$where['_string']="cominfo=1 or contacts=1 or tmobile=1 or qqsync=1 or bank=1";
					break;
				case 2:
					$exist=M('loan')->where(array('status'=>0))->select();
					$ids=array();
					foreach($exist as $k=>$v){
						$ids[$k]=$v['memberid'];
					}
					if(count($ids)>0){
						$where['id']=array('in',$ids);
					}else{
						$where['id']=array('in','0');
					}
					break;
				case 3:
					$exist=M('loan')->where(array('status'=>array('egt',2)))->select();
					$ids=array();
					foreach($exist as $k=>$v){
						$ids[$k]=$v['memberid'];
					}
					if(count($ids)>0){
						$where['id']=array('in',$ids);
					}else{
						$where['id']=array('in','0');
					}
					break;
				default:
					$where ['status'] = $status;
					break;
			}


		}
		//dump($where);die;
		// 表名
		$tblname = 'member';
		$name = '客户';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();

		foreach($list as $key=>$val){
			//签到次数
			$record=M('content_signup_record')->where(array('memberid'=>$val['id']))->select();
			$times=count($record);
			$list[$key]['signuptimes']=$times;
			
			
			
			//报名活动次数
			$active=M('content_active_member')->where(array('memberid'=>$val['id']))->select();
			$acnum=count($active);
			$list[$key]['activenum']=$acnum;
			
		}
		
		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		
		// 已有分类列表
		$list = get_cache_list ( 'category_member' );
		$list = list_to_tree ( $list );
		$this->assign ( "list", $list );
		
		// 当前表名
		$control = 'Member';
		$action = 'Member';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
		
		// 状态标识
//		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign('statuslist',array('公共客户','意向客户','申请中客户','已成单客户'));
		
		$this->assign ( "title", '客户列表' );
		$this->display ();
	}
	
	/**
	 * 添加
	 */
	public function addMember() {
		$this->editMember ();
	}
	/**
	 * 添加修改客户
	 *
	 * @param number $id
	 */
	public function editMember($id = 0) {
		   $tblname = 'member';
		   $name = '客户';
		   $tplname = 'editMember';


		if (IS_POST) {
			$data = $_POST;
			$data ['username'] = trim ( $data ['username'] );
//			$seq=explode(',',$_POST['seq']);
			$seq=$_POST['seq'];
//			$contrelationship=explode(',',$_POST['relationship']);
			$contrelationship=$_POST['relationship'];
//			$contusername=explode(',',$_POST['contusername']);
			$contusername=$_POST['contusername'];
//			$conttelephone=explode(',',$_POST['conttelephone']);
			$conttelephone=$_POST['conttelephone'];
			unset($data['relationship'],$data['contusername'],$data['conttelephone']);
			$infocont=array();
			foreach($seq as $k=>$v){
				if(!is_mobile($conttelephone[$k])){
					$this->error ("第".($k+1)."个联系人电话号码格式不正确");
				}
				$infocont[$k]['seq']=$v;
				$infocont[$k]['relationship']=$contrelationship[$k];
				$infocont[$k]['username']=$contusername[$k];
				$infocont[$k]['telephone']=$conttelephone[$k];
			}
			$infocont=json_encode($infocont,JSON_UNESCAPED_UNICODE);
			$data['contactinfo']=$infocont;

			$tmobiletelephone=$_POST['tmobiletelephone'];
			$tmobileservicepwd=$_POST['tmobileservicepwd'];
			$tmobileinfo=array();
			$tmobileinfo['telephone']=$tmobiletelephone;
			$tmobileinfo['servicepwd']=$tmobileservicepwd;
			$tmobileinfo=json_encode($tmobileinfo,JSON_UNESCAPED_UNICODE);
			$data['tmobileinfo']=$tmobileinfo;
			unset($data['tmobiletelephone'],$data['tmobileservicepwd']);


			$qqsyncinfotelephone=$_POST['qqsyncinfotelephone'];
			$qqsyncinfopwd=$_POST['qqsyncinfopwd'];
			$qqsyncinfo=array();
			$qqsyncinfo['telephone']=$qqsyncinfotelephone;
			$qqsyncinfo['pwd']=$qqsyncinfopwd;
			$qqsyncinfo=json_encode($qqsyncinfo,JSON_UNESCAPED_UNICODE);
			$data['qqsyncinfo']=$qqsyncinfo;
			unset($data['qqsyncinfotelephone'],$data['qqsyncinfopwd']);


			$bankinfo=array();
			$bankusername=$data['bankusername'];
			$banktelephone=$data['banktelephone'];
			$bankidcard=$data['bankidcard'];
			$bankbankno=$data['bankbankno'];
			$bankbankname=$data['bankbankname'];
			$bankinfo['username']=$bankusername;
			$bankinfo['telephone']=$banktelephone;
			$bankinfo['idcard']=$bankidcard;
			$bankinfo['bankno']=$bankbankno;
			$bankinfo['bankname']=$bankbankname;
			$bankinfo=json_encode($bankinfo,JSON_UNESCAPED_UNICODE);
			$data['bankinfo']=$bankinfo;
			unset($data['bankusername'],$data['banktelephone'],$data['bankidcard'],$data['bankbankno'],$data['bankbankname']);


			$data['work'] = json_encode($_POST['work']);
			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {


				$where = array ();
//				$where ['username'] = $data ['username'];
				$where['telephone']=$data['telephone'];
				$where['idcard']=$data['idcard'];
				$where['_logic']="or";
				$find = M ( $tblname )->where ( $where )->find ();
				if ($find) {
					$this->error ( '对不起，用户已存在【联系电话，身份证号已存在】！' );
				}

				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id
				) )->setField ( 'sort', $id );
				$this->success ( '恭喜，' . $name . '添加成功！' );
			}
			exit ();
		} else {
			if ($id) {


				$where = array ();
				// $where['fpath']=array('like','%,'.$id.',%');
				$where ['fid'] = $id;
//				$field = 'id,fid,invitecode,nickname,username';
				$nodelist = M ( $tblname )->where ( $where )->order ( 'id desc' )->limit(100)->select ();

				foreach ( $nodelist as $k => $v ) {
					$nodelist [$k] ['name'] = $v ['username'];

					$isp=M('member')->where(array('fid'=>$v['id']))->count();
					$nodelist [$k] ['isParent'] = $isp>0 ? true : false;
				}
//				dump($nodelist);die;


				$this->assign ( 'nodelist', $nodelist );


				// 修改
				$db = M ( $tblname )->find ( $id );
				$idcard = $db['idcard'];
                $db['age'] = $this->getAgeByID($idcard);
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}
			// 已有分类列表
			$list = get_cache_list ( 'category_member' );
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
			$control = 'Member';
			$action = 'member';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			//营运商报告
			$rows = M('member_info')->where(['memberid'=>$id])->find()['tmobilereport'];
			
			$allrows = json_decode($rows,true);
                                       
			$userInfo = $allrows['taskResult']['userInfo'];

			$expenses = $allrows['taskResult']['expenses'];
			$recharges = $allrows['taskResult']['recharges'];
			$incomingCallsRanking = $allrows['taskResult']['incomingCallsRanking'];
			$outgoingCallsRanking = $allrows['taskResult']['outgoingCallsRanking'];
			$closelyContacts = $allrows['taskResult']['closelyContacts'];
			$contactsRanking = $allrows['taskResult']['contactsRanking'];
			$callDistributionByTime = $allrows['taskResult']['callDistributionByTime'];
			$taggedCallsAggregation = $allrows['taskResult']['taggedCallsAggregation'];


            $this->assign('userInfo',$userInfo);
            $this->assign('allrows',$allrows);
            $this->assign('expenses',$expenses);
            $this->assign('recharges',$recharges);
            $this->assign('incomingCallsRanking',$incomingCallsRanking);
            $this->assign('outgoingCallsRanking',$outgoingCallsRanking);
            $this->assign('closelyContacts',$closelyContacts);
            $this->assign('contactsRanking',$contactsRanking);
            $this->assign('callDistributionByTime',$callDistributionByTime);
            $this->assign('taggedCallsAggregation',$taggedCallsAggregation);

			//营运商数据
			$rs = M('member_info')->where(['memberid'=>$id])->find()['tmobileinfo'];
                                           
			$result=json_decode($rs,true);
  
			$userInfo1 = $result['taskResult']['userinfo'];


			$billHistory =$result['taskResult']['billHistory'];
			$smsHistory =$result['taskResult']['smsHistory'];
			foreach($smsHistory as $k=>$v){
				$smsHistory[$k]['details']=arraySequence($v['details'],'date','SORT_DESC');
			}
			$smsHistory=arraySequence($smsHistory,'month','SORT_DESC');

			$sdetails = $result['taskResult']['smsHistory']['details'];
			$callHistory =$result['taskResult']['callHistory'];
			foreach($callHistory as $k=>$v){
				$callHistory[$k]['details']=arraySequence($v['details'],'startTime','SORT_DESC');
			}
			$callHistory=arraySequence($callHistory,'month','SORT_DESC');
			$cdetails=array();
			foreach($result['taskResult']['callHistory'] as $kc=>$vc){
				$cdetails[$kc]=$vc['details'];
			}

			$this->assign('userInfo1',$userInfo1);
			$this->assign('billHistory',$billHistory);
			$this->assign('smsHistory',$smsHistory);
			$this->assign('sdetails',$sdetails);
			$this->assign('callHistory',$callHistory);
			$this->assign('cdetails',$cdetails);
			//网贷黑名单
			$blanks = M('member_info')->where(['memberid'=>$id])->find()['blackloan'];
			$blank=json_decode($blanks,true);
			$isBan = $blank['taskResult']['isBan'];
			$createTime =$blank['taskResult']['createTime'];
			$reports = $blank['taskResult']['reports'];
			$this->assign('isBan',$isBan);
			$this->assign('createTime',$createTime);
			$this->assign('reports',$reports);
            //负面信息
			$list = M('member_info')->where(['memberid'=>$id])->find()['blackcrime'];
			$lists=json_decode($list,true);
			$isb = $lists['taskResult']['isBan'];
			$rep = $blank['taskResult']['reports'];
			$this->assign('isb',$isb);
			$this->assign('rep',$rep);

			//客户资料
			$member = M('member')->where(['id'=>$id])->find();
			$this->assign('member',$member);
			//实名
			$real = M('member_info')->where(['memberid'=>$id])->find()['tmobilereal'];
			$realname=json_decode($real,true);

			$this->assign('realname',$realname);


 
		require __DIR__.'/../../../ThinkPHP/Library/ibdaasdemo/zxbg.php';

		// $db['idcard'] = "31010719841107045X"; //身份证号
		// $db['username']="朱殿撰"; //姓名
		// $db['telephone'] ="13917625306";

        $data = array(
            "idCardNo"=>$db['idcard'], //身份证号
            "name"=>$db['username'], //姓名
            "phoneNo"=>$db['telephone'],
            "basicInfo"=>array(
                "idNumber"=>$db['idcard'],
                "phoneNumber"=>$db['telephone'],
                "bankCardNumber"=>$bankinfo['bankno'],
                "userIp"=>"",
                "imei"=>"",
                "idfa"=>""
            ),
            "otherInfo"=>array(
                "name"=>"",
                "emailAddress"=>"",
                "address"=>"",
                "mac"=>"",
                "imsi"=>""
            )
        ); 
		$blackAllArr=getBlackAll($data,$fieldsArr,$Reids);
		// var_dump($blackAllArr);
		// die();

		$this->assign('isBan',getArrVal($blackAllArr['black']['response']['taskResult'],'isBan'));
		$this->assign('isb',getArrVal($blackAllArr['blackcourt']['response']['taskResult'],'isBan'));
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->assign ( "fieldsArr", $fieldsArr );
			$this->assign ( "blackAllArr", $blackAllArr );

			$this->assign ( "reportsArr", $reportsArr );

			
			$this->display ( $tplname );
		}
	}


    public function getAgeByID($id)

    { //过了这年的生日才算多了1周岁

        if (empty($id)) return '';

        $date = strtotime(substr($id, 6, 8)); //获得出生年月日的时间戳

        $today = strtotime('today'); //获得今日的时间戳

        $diff = floor(($today - $date) / 86400 / 365); //得到两个日期相差的大体年数

        //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比

        $age = strtotime(substr($id, 6, 8) . ' +' . $diff . 'years') > $today ? ($diff + 1) : $diff;

        return $age;

    }
	/**
	 * 获取用户基本信息
	 *
	 * @param unknown $id
	 */
	public function getMemberInfo($id) {
		$where = array ();
		$where ['id'] = $id;
		//$field = 'id,nickname,headimgurl,addtime,pid ';
		$db = M ( 'member' )->where ( $where )->find ();
		$db['level']=get_cache_value('category_member',$db['pid'],'name');
		$this->success ( $db );
	}


	/**
	 * 获取下级列表和总数
	 *
	 * @param number $pid
	 */
	public function getChildren($pid = 0) {
		$where = array ();
		$where ['fid'] = $pid;

		$list = M ( 'member' )->where ( $where )->order ( 'id desc' )->limit(100)->select ();

		foreach ( $list as $k => $v ) {

			$list [$k] ['name'] = $v ['username'].'[真实姓名：'.$v ['userreal'].']';

			$isp=M('member')->where(array('fid'=>$v['id']))->count();
			$list [$k] ['isParent'] = $isp>0 ? true : false;
		};
		$list = json_encode ( $list, 256 );
		header ( 'Content-Type:application/json; charset=utf-8' );
		exit ( $list );
	}
	/**
	 * 生成合同
	 *
	 */
	public function getContract($orderno = 0){
		$loan = M('loan')->where(['orderno'=>$orderno])->find();
		$loan['bianhao']=date('YmdHis',time()).mt_rand(1000000,9999999);
		$member = M('member')->where(['id'=>$loan['memberid']])->find();
		//var_dump($member);die;
		$time = substr($loan['addtime'],-19,10);
		$this->assign('time',$time);
		$this->assign('member',$member);
		$this->assign('loan',$loan);
        $this->display();
	}
	public function getpdf($str =""){
		$loan=M('loan')->where(array('orderno'=>$str))->find();
		$loan['bianhao']=date('YmdHis',time()).mt_rand(1000000,9999999);
		$time = substr($loan['addtime'],-19,10);
		$member=M('member')->where(array("id"=>$loan['memberid']))->find();
		//var_dump($member);die;

		$html="<div class=\"center\" style=\"max-width: 800px;margin: 0 auto;box-sizing: border-box;padding: 20px;\">
			<h1 class=\"shengming\" style=\"font-size: 16px;color: #ff5151;\">
				郑重声明: U易平台不从事任何放贷业务，如果有他人以“U易平台”名义进行放贷，均与“U易平台”无关，U易服务平台保留追究其法律责任的权利。
			</h1>
			<h1 class=\"hetongming\" style=\"font-size: 18px;margin: 20px 0;text-align: center;\">
				借款协议
			</h1>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				协议编号:{$loan['bianhao']}
				甲方(出借人):王海雨 (已认证) 甲方身份证号:    甲方手机号:13518143416
甲方电子邮箱:
乙方(借款人):{$member['username']} (已认证)
				乙方身份证号:{$member['idcard']} 乙方手机号:{$member['telephone']}
 甲乙双方因借款事宜，经协商达成如下协议:
				<div style=\"margin-top: 10px;\">本协议内容如下:</div>
			</div>
			<div class=\"setitle\" style=\"font-size: 18px;font-weight: bold;margin: 20px 0;\">
				一、借款主要内容
			</div>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				借款金额:{$loan['damount']}元 借款日期:{$loan['addtime']} 还款日期:{$loan['deadline']} 年化利率:0.24 借款用途:其他 本息共计{$loan['amount']}:元 还款方式:到期还本付息
				注:利息 = 借款金额*年化利率*(借款天数/365),其中\"借款天数\"为\"借款日期(包
				括当天)\"和\"还款日期(不包括当天)\"间的天数，此处的“借款日期” 方实际支付款项且款项已到账的日期。
				借款人可多次还款直至待还本息全部还清，但提前还款并不减少待还本 息。

			</div>
			<div class=\"setitle\" style=\"font-size: 18px;font-weight: bold;margin: 20px 0;\">
				二、打借条流程
			</div>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				1、出借人和借款人在线下完成了借款资金的划转交割后，如认为有需要 过「U易服务」平台完善借款手续的，
				借款人(或出借人)均可在「U易服务」平台起草借条，起草完成后发送给对方，对方确认该借条后即 代表承
				认借条中约定的借款金额、起借日期、还款日期、年化利率等信 息。在出借人(或借款人)确认该借条之前，借款人(
				或出借人)有权利将该借条删除。 2、出借人(或借款人)收到借条后，如果发觉借条信息有误，可以驳回 给借款人(或出借
				人):如果确认无误，点击确认借条按钮即代表认可借条中约定信息，确认后借款手续办理完毕。 3、出借人和借款人可根据需要决
				定是否进行本流程，居间服务商在此仅 提供借条模板，具体借款经过及其他约定以出借人和借款人的约定为准
			</div>
			<div class=\"setitle\" style=\"font-size: 18px;font-weight: bold;margin: 20px 0;\">
				三、借还款方式
			</div>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				1、甲乙双方借还款项方式，由甲乙双方自行约定。甲方将约定款项汇至 乙方指定
				账户(甲乙双方指定账户包括且不限于微信、支付宝、银行转 账，下同),即视为已放款。乙方按时将约定款项汇至甲方指定账户,即 视为已还款。
				2、最终的实际履行，以甲乙双方转账记录为准。
			</div>
			<div class=\"setitle\" style=\"font-size: 18px;font-weight: bold;margin: 20px 0;\">
				四、违约
			</div>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				乙方归还的款项不足以清偿本协议项下应付数额的，
				甲方可以选择先将该款项用于归还本金、利息、违约金、复利或者相关费用。
			</div>
			<div class=\"setitle\" style=\"font-size: 18px;font-weight: bold;margin: 20px 0;\">
				五、违约与还款能力降低
			</div>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				1、乙方行为符合以下情形中任意一种的即视为违约，包括: (1)乙方交易到期未足额偿还借款本金、利息及相应费用的; (2)违反中国的法律法规、将所借资金用于违规或非法用途的; (3)乙方提供虚假资料或隐瞒重要事实的;
				2、乙方行为符合以下情形中之任意一种即视为还款能力降低: (1)在甲方以外任何第三方的其他借款、担保、赔偿、承诺或任何 其他债务出现严重违约情况，影响或可能影响乙方还款能力的; (2)部分或者全部丧失民事行为能力、死亡、被宣告死亡或者被宣 告失踪，影响或可能影响乙方还款能力的 (3)乙方被采取刑事强制措施，影响或可能影响乙方还款能力的; (4)乙方财产被没收、征用、查封、损坏、扣押、冻结的或乙方财 产遭受重大损失，影响或可能影响乙方还款能力的; (5)出现任何其他影响或可能影响乙方还款能力的事件;
				3、若乙方发生违约行为或者还款能力降低事件，或根据甲方/「U易服务」合理判断乙方可能发生本协议第四条第1款和第2款所述事件的，甲方与乙方一致同意甲方有权采取下列任何一项、几项或全部措施予以债权救济:
				(1)立即取消全部或部分借款; (2)宣布借款全部提前到期，乙方应立即偿还所有应付款，包括但 不限于欠款本金、利息、罚息及其他各项相关费用等; (3)解除本协议; (4)对拒不归还欠款的，在「米房服务」平台上向所有用户披露乙 方的违约信息; (5)将乙方违约情况提供给依法成立的个人征信机构;
				(6)采取法律、法规以及本协议约定的其他救济措施
			</div>
			<div class=\"setitle\" style=\"font-size: 18px;font-weight: bold;margin: 20px 0;\">
				六、违约金
			</div>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				从还款日的次日计算违约金，以截至当日未偿还借款本金利息之和为基 数,每日按年化利率24%计收罚息。
			</div>
			<div class=\"setitle\" style=\"font-size: 18px;font-weight: bold;margin: 20px 0;\">
				七、其他
			</div>
			<div class=\"text\" style=\"line-height: 1.5;font-size: 14px;\">
				1、本协议经甲、乙双方通过「U易服务」平台以网络在线点击确认的方式签订，点击确认后本协议生效。
				由于「U易服务」平台的业务流程,一 旦点击确认本协议,本协议同时自动生效。双方委托「U易服务」
				平台保管所有与本协议有关的书面文件或电子信息,且认可「U易服务」平台在 未来发生的相关纠纷中向有关裁判机构提交该等文件信息的权利和证明 争议事实的效力。
				2、双方均确认，本协议的签订、生效和履行以不违反法律为前提。如果 本协议中的任何一条或多条违反适用的法律，则该条将被视为无效,但该
				无效条款并不影响本协议其他条款的效力。 3、本协议中所使用的定义,除非另有规定,否则应适用「U易服务」平台 释义规则，「U易服务」平台对本文定义享有最终解释权。
			</div>

			<div class=\"foot\" style=\"overflow: hidden;margin-top: 20px;float: right;\">
				<p style=\"margin: 10px 0;width: 300px;font-size: 12px;\">甲方(出借人):王海雨</p>
				<p style=\"margin: 10px 0;width: 300px;font-size: 12px;\">乙方(借款人):{$member['username']}</p>
				<p style=\"margin: 10px 0;width: 300px;font-size: 12px;\">丙方(居间服务商): U易网络技术有限公司</p>
				<p style=\"margin: 10px 0;width: 300px;font-size: 12px;\">签订日期:{$time}</p>
			</div>
		</div>
		</div>";
		$path=pdf($html,$str);
		
        if(!$loan['url']){
			$data['url']=$path;
			M('loan')->where(array('orderno'=>$str))->save($data);
		}else{
			E("已经生成过");
		}

	}



	/**
	 * 删除客户
	 *
	 * @param number $id        	
	 */
	public function deleteMember($id = 0) {
		$tblname = 'member';
		$name = '客户';
		$find = M ( $tblname )->find ( $id );
		if ($find) {
			
			//删除二维码
			$openid=$find['openid'];
			$path = './Public/uploadfile/qrcode/qr/';
			$filename = $path . ($openid) .'_'.$find['id'].  '.jpg';
			//dump($filename);
			//dump(file_exists ( $filename ));die;
			if (file_exists ( $filename )){
				//dump(unlink($filename));die;
				unlink($filename);
			}
			
			
			
			M ( $tblname )->where ( array (
					'id' => $id 
			) )->delete ();
			$this->success ( '恭喜，' . $name . '已删除！' );
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}

	/**
	 * 导出游客信息Excel
	 */
	function expmember($timefrom='',$timeto='')
	{//导出Excel
		Vendor("PHPExcel.PHPExcel.Cell.DataType");



		$xlsName = date('Ymd')."-导出客户";

		$xlsCell = array(
				array('sequence', '序号',6),
				array('username', '姓名',12),
				array('nickname', '昵称',12),
				array('telephone', '联系电话',13),
				array('email', '邮箱'),
				array('company', '公司'),
				array('zhiwei', '职位'),
				array('need', '需要什么'),
				array('have', '有什么'),
				array('addtime', '添加时间')
		);

		if(!isN($timefrom)){
			$where['addtime']=array('gt',$timefrom);
		}
		if(!isN($timeto)){
			$timeto = get_date_add ( $timeto, 1 );
			$where['addtime']=array('lt',$timeto);
		}

		if(!isN($timefrom) && !isN($timeto)){
			$where['addtime']=array('between',array($timefrom,$timeto));
		}

//		$where['userreal']=array('neq',null);

		$xlsModel = M('member');

		$xlsData = $xlsModel->where($where)->select();
//		$xlsData = checkIdType($xlsData);
		foreach ($xlsData as $k => $v) {
			$xlsData[$k]['sequence'] = $k +1;
			$xlsData[$k]['username'] = $v['userreal'];
			$xlsData[$k]['nickname'] = $v['nickname'];
			$xlsData[$k]['telephone'] = $v['telephone'];
			$xlsData[$k]['email'] = $v['email'];
			$xlsData[$k]['company'] = $v['company'];
			$xlsData[$k]['zhiwei'] = $v['zhiwei'];
			$xlsData[$k]['need'] = $v['need'];
			$xlsData[$k]['have'] = $v['have'];
			$xlsData[$k]['addtime'] = $v['addtime'];
		}
		$this->exportExcel($xlsName, $xlsCell, $xlsData);
	}


	public function exportExcel($expTitle, $expCellName, $expTableData)
	{
		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
		$fileName = $expTitle;//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		Vendor("PHPExcel.PHPExcel");
		Vendor("PHPExcel.PHPExcel.IOFactory");
		Vendor("PHPExcel.PHPExcel.Style.Border");
		$objPHPExcel = new \PHPExcel();
		$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

		//$objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
		// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
		for ($i = 0; $i < $cellNum; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
		}
		// Miscellaneous glyphs, UTF-8
		for ($i = 0; $i < $dataNum; $i++) {
			for ($j = 0; $j < $cellNum; $j++) {
				if($expCellName[$j][3])
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit($cellName[$j] . ($i + 2), $expTableData[$i][$expCellName[$j][0]],$expCellName[$j][3]);
				else
					$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 2), $expTableData[$i][$expCellName[$j][0]]);
			}
		}

		for ($i = 0; $i < $cellNum; $i++) {
			if($expCellName[$i][2])
				$objPHPExcel->getActiveSheet(0)->getColumnDimension($cellName[$i])->setWidth($expCellName[$i][2]);
		}
		$styleArray = array(
				'borders' => array(
						'allborders' => array(
								'style' => \PHPExcel_Style_Border::BORDER_THIN
						)
				)
		);
		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);


//        $objPHPExcel->getDefaultStyle()
//            ->getBorders()
//            ->getTop()
//            ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
		header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}


	/**
	 * 征信查询接口操作
	 */
	public function getinterfacedata(){
		$type=$_POST['type'];
		$id=$_POST['id'];
		$retry=$_POST['retry'];
		$member=M('member')->where(array('id'=>$id))->find();
		$tmobileinfo = empty($member['tmobileinfo'])?'':json_decode($member['tmobileinfo'],true);
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
		switch($type) {
			case 'element3':
				$tmobileinfo = json_decode($member['tmobileinfo'], true);
				$find = M('member_info')->where(array('memberid' => $id))->find();
//				dump($find);die;
				if ($tmobileinfo) {
					if ($find['tmobilereal'] && $retry == 0) {
						$result = json_decode($find['tmobilereal'], true);
						if ($result['taskStatus'] == "success") {
							if ($result['taskResult']['result'] == "T") {
								$return['status'] = 1;
							} else {
								$return['status'] = 0;
							}
						} else {
							$return['status'] = 0;
							$result['taskResult']['message'] = $result['message'];
						}
					} else {

						$url = $baseurl . $urlpath . "?" . $querystring;
						$body = array();
						$body['callbackurl'] = "";
						$body['data']['sync'] = 1;
						$body['data']['idCardNo'] = $member['idcard'];
						$body['data']['name'] = $member['username'];
						$body['data']['phoneNo'] = $tmobileinfo['telephone'];
						$body = json_encode($body, JSON_UNESCAPED_UNICODE);


						$token = $tokenobj->generateToken($urlpath, $method, $querystring, $body, $currenttime);

						$header[] = "X-IbeeAuth-Token:{$token}";
						$result = get_curl($url, $method, $body, $header);

						$result['gettime'] = date("Y-m-d H:i:s");
						$saveinfo = json_encode($result, JSON_UNESCAPED_UNICODE);

						$find = M('member_info')->where(array('memberid' => $id))->find();
						if ($find) {
							$save = M('member_info')->where(array('memberid' => $id))->setField('tmobilereal', $saveinfo);
						} else {
							$data = array();
							$data['memberid'] = $id;
							$data['tmobilereal'] = $saveinfo;
							$save = M('member_info')->data($data)->add();
						}
						$return['status'] = 1;
						if ($result['taskStatus'] == "success") {
						} else {
							$result['taskResult']['message'] = $result['message'];
						}

					}
				} else {
					$result['taskResult']['message'] = "用户未提交运营商信息";
				}

				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html .= "姓名：" . $member['username'] . " &nbsp;&nbsp;&nbsp;身份证号：" . $member['idcard'] . "&nbsp;&nbsp;&nbsp;电话号码：" . $tmobileinfo['telephone'] . "</p></div>";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>运营商实名结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div> </div>";
				$return['html'] = $html;
				$this->ajaxReturn($return);
				break;
			case 'carrier':
				$tmobileinfo = json_decode($member['tmobileinfo'], true);
				$find = M('member_info')->where(array('memberid' => $id))->find();

				if ($find['tmobileinfo']) {
					$result = json_decode($find['tmobileinfo'], true);
					if ($result['taskStatus'] == "success") {
						$return['status'] = 1;
						$return['info'] = $result;
					} else {
						$return['status'] = 1;
						$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
						$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>运营商数据结果：客户运营商数据授权失败</p></div> </div>";
						$return['html'] = $html;
						$this->ajaxReturn($return);
					}
					$billHistory = $result['taskResult']['billHistory'];
					$callHistory = $result['taskResult']['callHistory'];
					$smsHistory = $result['taskResult']['smsHistory'];
					$userinfo = $result['taskResult']['userinfo'];


					$html = "";
					$html .= " <ul style=\"\">
								<li class=\"active\">用户信息</li>
								<li>账单记录</li>
								<li>通话记录</li>
								<li>短信记录</li>
								</ul>
								<div id=\"info0\">
								<table style=\"width: 100%;\">
									  <tr>
										<td>用户名</td>
										<td>电话号码</td>
										<td>身份证号</td>
										<td>地址</td>
										<td>开户时间</td>
									  </tr> <tr>
										<td>{$userinfo['name']}</td>
										<td>{$userinfo['phoneNo']}</td>
										<td>{$member['idcard']}</td>
										<td>{$userinfo['address']}</td>
										<td>{$userinfo['openDate']}</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info1\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>账单月</td>
										<td>套餐及固定费</td>
										<td>套餐外语音费用</td>
										<td>增值业务费</td>
										<td>本月总费用</td>
									  </tr>	";
					foreach ($billHistory as $k => $val) {
						$html .= "<tr>
											<td>{$val['yearMonth']}</td>
											<td>&yen;" . to_price_cent($val['baseFee']) . "</td>
											<td>&yen;" . to_price_cent($val['voiceFee']) . "</td>
											<td>&yen;" . to_price_cent($val['extraFee']) . "</td>
											<td>&yen;" . to_price_cent($val['totalFee']) . "</td>
										  </tr>";
					}

					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info1nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info1totalpage\">1</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info2\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>年月</td>
										<td>通话时长（单位：秒）</td>
										<td>对方号码</td>
										<td>通话费用（单位：分）</td>
										<td>通话开始时间</td>
										<td>通话区域</td>
										<td>通话类型</td><!--0 - \"主叫\", 1 - \"被叫\"-->
									  </tr>	";
					foreach ($callHistory as $k => $val) {
						foreach ($val['details'] as $kd => $vd) {
							if ($vd['callType'] == 1) {
								$calltype = "被叫";
							} else {
								$calltype = "主叫";
							}
							$html .= "
										<tr>
											<td>{$val['month']}</td>
											<td>{$vd['duration']}</td>
											<td>{$vd['otherPhone']}</td>
											<td>&yen;{$vd['fee']}</td>
											<td>{$vd['startTime']}</td>
											<td>{$vd['callLocation']}</td>
											<td>{$calltype}</td>
										  </tr>";
						}

					}
					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>


										<td colspan=\"5\">当前页：<span id=\"info2nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info2totalpage\">5</span></td>


										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info3\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>年月</td>
										<td>对方号码</td>
										<td>费用（单位：分）</td>
										<td>信息类型</td><!--0 - \"发送\", 1 - \"接收\"-->
										<td>短信发送时间</td>
									  </tr>	";
					foreach ($smsHistory as $k => $val) {
						foreach ($val['details'] as $kd => $vd) {
							if ($vd['smsType'] == 1) {
								$smsType = "接收";
							} else {
								$smsType = "发送";
							}
							$html .= "<tr>
											<td>{$val['month']}</td>
											<td>{$vd['otherPhone']}</td>
											<td>&yen;{$vd['fee']}</td>
											<td>{$smsType}</td>
											<td>{$vd['date']}</td>
										  </tr>";
						}
					}
					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info3nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info3totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>";

//					$html="<div style=\"position:relative;width: 100%;height: 100%;\">";
//					$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>运营商数据结果：客户运营商数据授权失败</p></div> </div>";
					$return['html'] = $html;
					$this->ajaxReturn($return);
				} else {
					$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
					$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>运营商数据结果：客户运营商数据授权失败</p></div> </div>";
					$return['html'] = $html;
					$this->ajaxReturn($return);
				}

				break;
			case "element4":
				$bankinfo = json_decode($member['bankinfo'], true);
				$find = M('member_info')->where(array('memberid' => $id))->find();
//				dump($find);die;
				if ($bankinfo) {
					if ($find['bankreal'] && $retry == 0) {
						$result = json_decode($find['bankreal'], true);
						if ($result['taskStatus'] == "success") {
							if ($result['taskResult']['resultCode'] == "0000") {
								$return['status'] = 1;
							} else {
								$return['status'] = 0;
							}
						} else {
							$return['status'] = 0;
							$result['taskResult']['message'] = $result['message'];
						}
					} else {

						$url = $baseurl . $urlpath . "?" . $querystring;
						$body = array();
						$body['callbackurl'] = "";
						$body['data']['sync'] = 1;
						$body['data']['idCardNo'] = $bankinfo['idcard'];
						$body['data']['cardHolderName'] = $bankinfo['username'];
						$body['data']['phoneNo'] = $bankinfo['telephone'];
						$body['data']['bankCardNo'] = $bankinfo['bankno'];
						$body = json_encode($body, JSON_UNESCAPED_UNICODE);
						$token = $tokenobj->generateToken($urlpath, $method, $querystring, $body, $currenttime);

						$header[] = "X-IbeeAuth-Token:{$token}";
						$result = get_curl($url, $method, $body, $header);
						$result['gettime'] = date("Y-m-d H:i:s");
						$saveinfo = json_encode($result, JSON_UNESCAPED_UNICODE);
						$find = M('member_info')->where(array('memberid' => $id))->find();
						if ($find) {
							$save = M('member_info')->where(array('memberid' => $id))->setField('bankreal', $saveinfo);
						} else {
							$data = array();
							$data['memberid'] = $id;
							$data['bankreal'] = $saveinfo;
							$save = M('member_info')->data($data)->add();
						}
						$return['status'] = 1;
						if ($result['taskStatus'] == "success") {
						} else {
							$result['taskResult']['message'] = $result['message'];
						}

					}
				} else {
					$result['taskResult']['message'] = "用户未提交银行卡信息";
				}

				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html .= "姓名：" . $bankinfo['username'] . " &nbsp;&nbsp;&nbsp;身份证号：" . $bankinfo['idcard'] . "&nbsp;&nbsp;&nbsp;电话号码：" . $bankinfo['telephone'] . "</p>";
				$html .= "<p>银行卡号：" . $bankinfo['bankno'] . "&nbsp;&nbsp;&nbsp;开户行：" . $bankinfo['bankname'] . "</p></div>";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>银行卡实名结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div> </div>";
				$return['html'] = $html;
				$this->ajaxReturn($return);
				break;
			case "idcard_ocr":
				$cominfo = $member['cominfo'];
				$find = M('member_info')->where(array('memberid' => $id))->find();
//				dump($find);die;
				if ($cominfo) {
					if ($find['idcardinfo'] && $retry == 0) {
						$result = json_decode($find['idcardinfo'], true);
						if ($result['taskStatus'] == "success") {
							$return['status'] = 1;
						} else {
							$return['status'] = 0;
							$result['taskResult']['message'] = $result['message'];
						}
					} else {
						$url = $baseurl . $urlpath . "?" . $querystring;
						$body = array();
						$body['callbackurl'] = "";
						$body['data']['sync'] = 1;
						$body['data']['frontPhoto'] = imgTobase64($member['idcardimg1']);
						$body['data']['backPhoto'] = imgTobase64($member['idcardimg2']);
						$body['data']['headOption'] = 1;
//						dump($body);die;
						$body = json_encode($body, JSON_UNESCAPED_UNICODE);
						$token = $tokenobj->generateToken($urlpath, $method, $querystring, $body, $currenttime);

						$header[] = "X-IbeeAuth-Token:{$token}";
						$result = get_curl($url, $method, $body, $header);
						$result['gettime'] = date("Y-m-d H:i:s");
						$saveinfo = json_encode($result, JSON_UNESCAPED_UNICODE);
						$find = M('member_info')->where(array('memberid' => $id))->find();
						if ($find) {
							$save = M('member_info')->where(array('memberid' => $id))->setField('idcardinfo', $saveinfo);
						} else {
							$data = array();
							$data['memberid'] = $id;
							$data['idcardinfo'] = $saveinfo;
							$save = M('member_info')->data($data)->add();
						}
						$return['status'] = 1;
						if ($result['taskStatus'] == "success") {
						} else {
							$result['taskResult']['message'] = $result['message'];
						}

					}
				} else {
					$return['status'] = 1;
					$result['taskStatus'] = "fail";
					$result['taskResult']['message'] = "用户未提交个人身份证信息";
				}

				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html .= "姓名：" . $member['username'] . " &nbsp;&nbsp;&nbsp;身份证号：" . $member['idcard'] . "&nbsp;&nbsp;&nbsp;电话号码：" . $member['telephone'] . "</p></div>";

				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
				if ($result['taskStatus'] == "fail") {
					$html .= "<p>身份证识别结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
				} else {
					$html .= "<p>身份证识别结果：</p><p>姓名：" . $result['taskResult']['name'] . "&nbsp;&nbsp;&nbsp;性别：" . $result['taskResult']['sex'] . "&nbsp;&nbsp;&nbsp;生日：" . $result['taskResult']['birthday'] . "</p>";
					$html .= "<p>身份证号：" . $result['taskResult']['idCardNo'] . "&nbsp;&nbsp;&nbsp;民族：" . $result['taskResult']['fork'] . "</p>";
					$html .= "<p>地址：" . $result['taskResult']['address'] . "&nbsp;&nbsp;&nbsp;发证机关：" . $result['taskResult']['issueAuthority'] . "</p>";
					$html .= "<p>有效期：" . $result['taskResult']['vaildPriod'] . "&nbsp;&nbsp;&nbsp;头像：<img src='" . $result['taskResult']['headPhoto'] . "' /></p></div> ";
				}
				$return['html'] = $html;
				$this->ajaxReturn($return);
				break;
			case 'carrier_report':

				$contactinfo = json_decode($member['contactinfo'], true);
				$newcontactinfo = array();
				foreach ($contactinfo as $kc => $vc) {
					$newcontactinfo[$kc]['name'] = $vc['username'];
					$newcontactinfo[$kc]['phoneNo'] = $vc['telephone'];
					$newcontactinfo[$kc]['relationship'] = $vc['relationship']-0;
				}
				

				$find = M('member_info')->where(array('memberid' => $id))->find();
				$tmobileinfo = json_decode($find['tmobileinfo'], true);


				if ($contactinfo) {
					if ($find['tmobilereport'] && $retry == 0) {
						$result = json_decode($find['tmobilereport'], true);
						if ($result['taskStatus'] == "success") {
							$return['status'] = 1;
						} else {
							$return['status'] = 0;
							$result['taskResult']['message'] = $result['message'];
						}
					} else {
						$url = $baseurl . $urlpath . "?" . $querystring;
						$body = array();
						$body['data']['account'] =$member['telephone'];
						$body['data']['taskNo'] = $tmobileinfo['taskNo'];
						$body['data']['closelyContacts'] = $newcontactinfo;

						$body = json_encode($body, JSON_UNESCAPED_UNICODE);

						$token = $tokenobj->generateToken($urlpath, $method, $querystring, $body, $currenttime);

						$header[] = "X-IbeeAuth-Token:{$token}";
						$result = get_curl($url, $method, $body, $header);
						

						$result['gettime'] = date("Y-m-d H:i:s");
						$saveinfo = json_encode($result, JSON_UNESCAPED_UNICODE);
                        
						$find = M('member_info')->where(array('memberid' => $id))->find();

						if ($find) {
							$save = M('member_info')->where(array('memberid' => $id))->setField('tmobilereport', $saveinfo);
						} else {
							$data = array();
							$data['memberid'] = $id;
							$data['tmobilereport'] = $saveinfo;
							$save = M('member_info')->data($data)->add();
						}
						$return['status'] = 1;
						if ($result['taskStatus'] == "success") {


						} else {
							$result['taskResult']['message'] = $result['message'];
						}
					}
				} else {
					$result['taskResult']['message'] = "用户未提交联系人信息";
				}

				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				if ($result['taskStatus'] == "fail") {
					$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">
              <p>运营商实名结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div> </div>";
				} else {

					$html .= "<p>获取营运商结果：成功</p>";
				}

				$return['html'] = $html;
				$this->ajaxReturn($return);
				break;

			case "black":

             $find=M('member_info')->where(array('memberid'=>$id))->find();
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
			//银行三要素
			case 'anti_fraud':
				$find = M('member_info')->where(array('memberid' => $id))->find();
//				dump($find);die;
				if ($find['squad'] && $retry == 0) {
					$result = json_decode($find['squad'], true);
					if ($result['taskStatus'] == "success") {
						$return['status'] = 1;
					} else {
						$return['status'] = 0;
						$result['taskResult']['message'] = $result['message'];
					}
				} else {
					$url = $baseurl . $urlpath . "?" . $querystring;
					$body = [
							'callbackUrl' => "",
							'data' => [
									'sync' => 1,
									'basicInfo' => [
											'phoneNumber' => $member['telephone'],
											'idNumber'=>$member['idcard']
									],
									'otherInfo' => []
							]
					];
					$body = json_encode($body, JSON_UNESCAPED_UNICODE);
					$token = $tokenobj->generateToken($urlpath, $method, $querystring, $body, $currenttime);

					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime'] = date("Y-m-d H:i:s");
					$saveinfo = json_encode($result, JSON_UNESCAPED_UNICODE);
					$find = M('member_info')->where(array('memberid' => $id))->find();
					if ($find) {
						$save = M('member_info')->where(array('memberid' => $id))->setField('squad', $saveinfo);
					} else {
						$data = array();
						$data['memberid'] = $id;
						$data['squad'] = $saveinfo;
						$save = M('member_info')->data($data)->add();
					}
					$return['status'] = 1;
					if ($result['taskStatus'] == "success") {
					} else {
						$result['taskResult']['message'] = $result['message'];
					}

				}


				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html .= "姓名：" . $member['username'] . " &nbsp;&nbsp;&nbsp;身份证号：" . $member['idcard'] . "&nbsp;&nbsp;&nbsp;电话号码：" . $member['telephone'] . "</p></div>";

				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
				if ($result['taskStatus'] == "fail") {
					$html .= "<p>反欺诈结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
				} else {
					$html .= "<p>反欺诈结果：</p><p>是否有欺诈记录：" . $result['taskResult']['found'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
					$html .= "<p>欺诈id：" . $result['taskResult']['idFound'] . "&nbsp;&nbsp;&nbsp;0-100:欺诈分值：" . $result['taskResult']['riskScore'] . "</p>";
					$html.="<p>风险类型：".$result['taskResult']['riskInfo']."&nbsp;&nbsp;&nbsp;</p>";
//					$html.="<p>有效期：".$result['taskResult']['vaildPriod']."&nbsp;&nbsp;&nbsp;头像：<img src='".$result['taskResult']['headPhoto']."' /></p></div> ";
				}
				$return['html'] = $html;
				$this->ajaxReturn($return);


				break;
			case 'blackcrime':
				$find = M('member_info')->where(array('memberid' => $id))->find();
//				dump($find);die;
				if ($find['blackcrime'] && $retry == 0) {
					$result = json_decode($find['blackcrime'], true);
					if ($result['taskStatus'] == "success") {
						$return['status'] = 1;
					} else {
						$return['status'] = 0;
						$result['taskResult']['message'] = $result['message'];
					}
				} else {
					$url = $baseurl . $urlpath . "?" . $querystring;
					$body = array();
					$body['callbackurl'] = "";
					$body['data']['sync'] = 1;
					$body['data']['idCardNo'] = $member['idcard'];
					$body['data']['name'] = $member['username'];
//						dump($body);die;
					$body = json_encode($body, JSON_UNESCAPED_UNICODE);
					$token = $tokenobj->generateToken($urlpath, $method, $querystring, $body, $currenttime);

					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime'] = date("Y-m-d H:i:s");
					$saveinfo = json_encode($result, JSON_UNESCAPED_UNICODE);
					$find = M('member_info')->where(array('memberid' => $id))->find();
					if ($find) {
						$save = M('member_info')->where(array('memberid' => $id))->setField('blackcrime', $saveinfo);
					} else {
						$data = array();
						$data['memberid'] = $id;
						$data['blackcrime'] = $saveinfo;
						$save = M('member_info')->data($data)->add();
					}
					$return['status'] = 1;
					if ($result['taskStatus'] == "success") {
					} else {
						$result['taskResult']['message'] = $result['message'];
					}

				}


				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html .= "姓名：" . $member['username'] . " &nbsp;&nbsp;&nbsp;身份证号：" . $member['idcard'] . "&nbsp;&nbsp;&nbsp;电话号码：" . $member['telephone'] . "</p></div>";

				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
				if ($result['taskStatus'] == "fail") {
					$html .= "<p>犯罪黑名单结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
				} else {
					$html .= "<p>犯罪黑名单结果：</p><p>是否有犯罪：" . $result['taskResult']['isBan'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
					$html .= "<p>犯罪时间：" . $result['taskResult']['reports']['caseTime'] . "&nbsp;&nbsp;&nbsp;犯罪类型：" . $result['taskResult']['reports']['caseType'] . "</p>";
//					$html.="<p>地址：".$result['taskResult']['address']."&nbsp;&nbsp;&nbsp;发证机关：".$result['taskResult']['issueAuthority']."</p>";
//					$html.="<p>有效期：".$result['taskResult']['vaildPriod']."&nbsp;&nbsp;&nbsp;头像：<img src='".$result['taskResult']['headPhoto']."' /></p></div> ";
				}
				$return['html'] = $html;
				$this->ajaxReturn($return);


				break;
				//反欺诈
			case 'anti_fraud':
				$find = M('member_info')->where(array('memberid' => $id))->find();
//				dump($find);die;
				if ($find['squad'] && $retry == 0) {
					$result = json_decode($find['squad'], true);
					if ($result['taskStatus'] == "success") {
						$return['status'] = 1;
					} else {
						$return['status'] = 0;
						$result['taskResult']['message'] = $result['message'];
					}
				} else {
					$url = $baseurl . $urlpath . "?" . $querystring;
					$body = [
							'callbackUrl' => "",
							'data' => [
									'sync' => 1,
									'basicInfo' => [
											'phoneNumber' => $member['telephone'],
											'idNumber'=>$member['idcard']
									],
									'otherInfo' => []
							]
					];
					$body = json_encode($body, JSON_UNESCAPED_UNICODE);
					$token = $tokenobj->generateToken($urlpath, $method, $querystring, $body, $currenttime);

					$header[] = "X-IbeeAuth-Token:{$token}";
					$result = get_curl($url, $method, $body, $header);
					$result['gettime'] = date("Y-m-d H:i:s");
					$saveinfo = json_encode($result, JSON_UNESCAPED_UNICODE);
					$find = M('member_info')->where(array('memberid' => $id))->find();
					if ($find) {
						$save = M('member_info')->where(array('memberid' => $id))->setField('squad', $saveinfo);
					} else {
						$data = array();
						$data['memberid'] = $id;
						$data['squad'] = $saveinfo;
						$save = M('member_info')->data($data)->add();
					}
					$return['status'] = 1;
					if ($result['taskStatus'] == "success") {
					} else {
						$result['taskResult']['message'] = $result['message'];
					}

				}


				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\"><p>";
				$html .= "姓名：" . $member['username'] . " &nbsp;&nbsp;&nbsp;身份证号：" . $member['idcard'] . "&nbsp;&nbsp;&nbsp;电话号码：" . $member['telephone'] . "</p></div>";

				$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
				if ($result['taskStatus'] == "fail") {
					$html .= "<p>反欺诈结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
				} else {
					$html .= "<p>反欺诈结果：</p><p>是否有欺诈记录：" . $result['taskResult']['found'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
					$html .= "<p>欺诈id：" . $result['taskResult']['idFound'] . "&nbsp;&nbsp;&nbsp;0-100:欺诈分值：" . $result['taskResult']['riskScore'] . "</p>";
					$html.="<p>风险类型：".$result['taskResult']['riskInfo']."&nbsp;&nbsp;&nbsp;</p>";
//					$html.="<p>有效期：".$result['taskResult']['vaildPriod']."&nbsp;&nbsp;&nbsp;头像：<img src='".$result['taskResult']['headPhoto']."' /></p></div> ";
				}
				$return['html'] = $html;
				$this->ajaxReturn($return);


				break;


			case 'accdetect':
			case 'taobao':

				$find = M('member_info')->where(array('memberid' => $id))->find();

				if ($find['taobao'] && $retry == 0) {
					$result = json_decode($find['taobao'], true);

					if ($result['taskStatus'] == "success") {
						$return['status'] = 1;
					} else {
						$return['status'] = 0;
						$result['taskResult']['message'] = $result['message'];
					}
				} else {

					$result['taskResult']['message'] = "用户未认证";

				}


				$html = "<div style=\"position:relative;width: 100%;height: 100%;\">";
				if ($result['taskStatus'] == "fail") {
					$html .= "<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
					$html .= "<p>淘宝查询结果：" . $result['taskResult']['message'] . "&nbsp;&nbsp;&nbsp;查询时间：" . $result['gettime'] . "</p></div>";
				} else {
					$userinfo = $result['taskResult']['account'];//淘宝账号信息
					$address = $result['taskResult']['address'];
					$orders = $result['taskResult']['orders'];
					$shop = $result['taskResult']['shop'];
					$jiebei = $result['taskResult']['jiebei'];
					$bindAlipay = $result['taskResult']['bindAlipay'];
					$aplipay_balance = $result['taskResult']['aplipay_balance'];
					$huabei = $result['taskResult']['huabei'];


					$html = "";
					$html .= "<ul style=\"\">
								<li class=\"active\">淘宝账号信息</li>
								<li>收货地址</li>
								<li>订单详情</li>
								<li>卖家店铺信息</li>
								<li>借呗信息</li>
								<li>支付宝信息</li>
								<li>支付宝余额信息</li>
								<li>花呗信息</li>
								</ul>
								<div id=\"info0\">
								<table style=\"width: 100%;\">
									  <tr>
										<td>淘宝登录账号</td>
									  </tr>
									  <tr>
										<td>{$userinfo['username']}</td>
									  </tr>
									</table>
								  </div>
								<div id=\"info1\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>所在地区（省市区）</td>
										<td>详细地址</td>
										<td>邮政编码</td>
										<td>收货人</td>
										<td>收货人电话号码</td>
									  </tr>	";
					foreach ($address as $k => $val) {
						$html .= "<tr>
											<td>" . $val['area'] . "</td>
											<td>" . $val['address'] . "</td>
											<td>" . $val['zipCode'] . "</td>
											<td>" . $val['receiver'] . "</td>
											<td>" . $val['tel'] . "</td>
										  </tr>";
					}

					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info1nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info1totalpage\">1</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info2\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>订单号</td>
										<td>收货人姓名</td>
										<td>交易状态</td>
										<td>收货人联系电话</td>
										<td>订单实付价格</td>
										<td>商家名称</td>
										<td>订单创建时间</td>
										<td>商家昵称</td>
									  </tr>	";
					foreach ($orders as $k => $val) {
						$html .= "
										<tr>
											<td>" . $val['orderId'] . "</td>
											<td>" . $val['name'] . "</td>
											<td>" . $val['tradestatus'] . "</td>
											<td>" . $val['mobilephone'] . "</td>
											<td>" . $val['actualFee'] . "</td>
											<td>" . $val['shopName'] . "</td>
											<td>" . $val['createTime'] . "</td>
											<td>" . $val['nick'] . "</td>
										  </tr>";
					}


				$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>


										<td colspan=\"5\">当前页：<span id=\"info2nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info2totalpage\">5</span></td>


										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info3\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>卖家信用评分</td>
										<td>店铺名称</td>
										<td>店铺id</td>
										<td>店铺状态0-未开过店；1-店铺被关闭；2-正在运营</td>
										<td>支付宝个人认证时间</td>
									  </tr>	";


					$html .= "<tr>
											<td>{$shop['shop_rank']}</td>
											<td>{$shop['shop_name']}</td>
											<td>{$shop['shop_id']}</td>
											<td>{$shop['shop_state']}</td>
											<td>{$shop['alipay_auth_time']}</td>

									        </tr>";


				$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info3nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info3totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info4\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>0-未开通借呗；1-开通了借呗；-1-获取失败</td>
										<td>借呗额度(-1-获取失败)</td>
										<td>借呗可用额度(-1-获取失败)</td>
									  </tr> ";


					$html .= "<tr>
											<td>{$jiebei['has_jiebei']}</td>
											<td>{$jiebei['credit_amount']}</td>
											<td>{$jiebei['loanable_amount']}</td>

										  </tr>";


				$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info4nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info4totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
				                    <div id=\"info5\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>绑定支付宝的手机号</td>
										<td>实名认证信息</td>
										<td>账户类型</td>
										<td>绑定支付宝的邮箱</td>
									  </tr> ";


					$html .= "<tr>
											<td>{$bindAlipay['phoneNo']}</td>
											<td>{$bindAlipay['verifyInfo']}</td>
											<td>{$bindAlipay['accountType']}</td>
											<td>{$bindAlipay['email']}</td>

										  </tr>";


				   $html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info5nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info5totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
				              <div id=\"info6\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>1：获取余额成功；-1-获取失败</td>
										<td>余额宝余额</td>
										<td>支付宝账号余额</td>
									  </tr> ";

					         $html .= "<tr>
											<td>{$aplipay_balance['get_balance']}</td>
											<td>{$aplipay_balance['total_quotient']}</td>
											<td>{$aplipay_balance['balance']}</td>
							 </tr>";


				           $html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info6nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info6totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
				                    <div id=\"info7\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>花呗额度</td>
										<td>0-未开通花呗；1-开通了花呗</td>
										<td>花呗可用额度</td>
									  </tr> ";



					$html .= "<tr>
											<td>{$huabei['credit_amout']}00</td>
											<td>{$huabei['has_huabei']}</td>
											<td>{$huabei['loanable_amount']}00</td>
										  </tr>";

				
				$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info7nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info7totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>";
		}


				$return['html']=$html;

				$this->ajaxReturn($return);


				break;
			case 'alipay':
				$find=M('member_info')->where(array('memberid'=>$id))->find();
				if($find['alipay']&& $retry==0){
					$result=json_decode($find['alipay'],true);
					//var_dump($result);die;
					if($result['taskStatus']=="success"){
						$return['status']=1;
					}else{
						$return['status']=0;
						$result['taskResult']['message']=$result['message'];
					}
				}else{

					$result['taskResult']['message'] = "用户未认证";

				}
				$html="<div style=\"position:relative;width: 100%;height: 100%;\">";

				if($result['taskStatus']=="fail"){
					$html.="<div style=\"width:80%; background-color: #00b7ee;margin: 1% 10%;color:white;padding: 10px;border-radius: 5px;\">";
					$html.="<p>支付宝结果：".$result['taskResult']['message']."&nbsp;&nbsp;&nbsp;查询时间：".$result['gettime']."</p></div>";

				}else {

					//.$result['taskResult']['info']['alipayAccount']."</p>";
					//$html.="<p>支付宝绑定身份证号码：".$result['taskResult']['info']['idCard']."&nbsp;&nbsp;&nbsp;支付宝用户ID：".$result['taskResult']['info']['alipayUserId']."</p></div> ";
					$userinfo = $result['taskResult']['info'];//用户信息
					$billinfo = $result['taskResult']['bill'];//支付订单信息;
					$bank = $result['taskResult']['bank'];//银行卡信息
					$jiebie = $result['taskResult']['jiebei'];//借呗信息
					$huabei = $result['taskResult']['huabei'];//花呗信息;
					$zhimafen = $result['taskResult']['zhimafen'];//芝麻分信息

					$html = "";
					$html .= " <ul style=\"\">
								<li class=\"active\">用户信息</li>
								<li>支付订单信息</li>
								<li>银行卡信息</li>
								<li>借呗信息</li>
								<li>花呗信息</li>
								</ul>
								<div id=\"info0\">
								<table style=\"width: 100%;\">
									  <tr>
										<td>用户类型</td>
										<td>真实姓名</td>
										<td>认证时间</td>
										<td>是否实名认证</td>
										<td>支付宝账号</td>
										<td>支付宝绑定身份证号码</td>
										<td>支付宝用户ID</td>
										<td>账户总额</td>
										<td>账户余额</td>
									  </tr>
									  <tr>
										<td>{$userinfo['userType']}</td>
										<td>{$userinfo['realName']}</td>
										<td>{$userinfo['authTime']}</td>
										<td>{$userinfo['certified']}</td>
										<td>{$userinfo['alipayAccount']}</td>
										<td>{$userinfo['idCard']}</td>
										<td>{$userinfo['alipayUserId']}</td>
										<td>{$userinfo['amount']}</td>
										<td>{$userinfo['balance']}</td>
									  </tr>
									</table>
								  </div>
								<div id=\"info1\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>交易流水号</td>
										<td>支出/收入</td>
										<td>订单生成时间</td>
										<td>交易名称</td>
										<td>支付状态(缺省 完成)</td>
										<td>交易金额</td>
										<td>付款方式</td>
									  </tr>	";
					foreach ($billinfo as $k => $val) {
						$html .= "<tr>
											<td>" . $val['tradeNo'] . "</td>
											<td>" . $val['outOrIn'] . "</td>
											<td>" . $val['createTime'] . "</td>
											<td>" . $val['tradeName'] . "</td>
											<td>" . $val['paystatus'] . "</td>
											<td>" . $val['paystatus'] . "</td>
											<td>" . $val['payType'] . "</td>
										  </tr>";
					}

					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info1nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info1totalpage\">1</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info2\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>银行卡尾号(后四位)</td>
										<td>银行卡类型</td>
										<td>开户行名称</td>
										<td>银行标识</td>
									  </tr>	";
					foreach ($bank as $k => $val) {
						$html .= "
										<tr>
											<td>" . $val['bankLastNo'] . "</td>
											<td>" . $val['bankType'] . "</td>
											<td>" . $val['bankName'] . "</td>
											<td>" . $val['bankId'] . "</td>
										  </tr>";
					}


					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>


										<td colspan=\"5\">当前页：<span id=\"info2nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info2totalpage\">5</span></td>


										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info3\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>借呗总额度</td>
										<td>借呗剩余额度</td>
									  </tr>	";
					foreach ($jiebie as $k => $val) {

						$html .= "<tr>
											<td>{$val['credit_amount']}00</td>
											<td>{$val['loanable_amount']}00</td>

										  </tr>";

					}
					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info3nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info3totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>
								  <div id=\"info4\" style=\"display: none;\">
									<table style=\"width: 100%;\">
									  <tr>
										<td>花呗总额度</td>
										<td>花呗剩余额度</td>
									  </tr> ";


					foreach ($huabei as $k => $val) {

						$html .= "<tr>
											<td>{$val['credit_amount']}00</td>
											<td>{$val['loanable_amount']}00</td>

										  </tr>";

					}
					$html .= "<tr>
										<td onclick=\"prevpage()\" style=\"cursor: pointer;\">上一页</td>
										<td></td>
										<td>当前页：<span id=\"info4nowpage\">1</span> &nbsp;&nbsp; 总页数：<span  id=\"info4totalpage\">5</span></td>
										<td></td>
										<td onclick=\"nextpage()\" style=\"cursor: pointer;\">下一页</td>
									  </tr>
									</table>
								  </div>";
				}

				$return['html']=$html;
				$this->ajaxReturn($return);




				break;

			default:
				break;
		}
	}



	/**
	 * 订单列表
	 */
	public function loan($searchtype = '',$type='', $keyword = '', $pid = '', $status = '', $p = 1) {
		// we(add_order(1,array('username'=>'张三'),array(1=>2,6=>3,3=>3,4=>5)));
		// we(edit_order('201601251149111019',1,array('username'=>'张四'),array(1=>1,6=>1,3=>1,4=>1)));
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['orderno'] = array (
							'like',
							'%' . $keyword . '%'
					);
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['username'] = array (
							'like',
							'%' . $keyword . '%'
					);
				}
				break;
			case '2' :
				if (! isN ( $keyword )) {
					$where ['telephone'] = array (
							'like',
							'%' . $keyword . '%'
					);
				}
				break;
		}


		if (is_numeric ( $status )) {
			if($status==3){
				$where['status']=array('in','2,3');
				$where['deadline']=array('lt',date("Y-m-d H:i:s"));
			}else{
				$where ['status'] = $status;
			}

		}

		// 表名
		$tblname = 'loan';
		$name = '贷款订单';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();

		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();

		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}

		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		$this->assign('type',$type);

		// 当前表名
		$control = 'Member';
		$action = 'loan';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );


		// 状态标识
		$this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

		$this->assign ( "title", '贷款订单列表' );
		$this->display ();
	}

	/**
	 * 添加
	 */
	public function addloan() {
		$this->editloan ();
	}
	/**
	 * 添加修改订单
	 *
	 * @param number $id
	 */
	public function editloan($id = 0) {
		$tblname = 'loan';
		$name = '贷款订单';
		$tplname = 'editloan';
		if (IS_POST) {
			$data = $_POST;
			if ($id) {
				$save = edit_order ( $data ['orderno'], $data ['status'], $data, $data ['detail'] );
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				$save = add_order ( $data ['memberid'], $data, $data ['detail'], $data ['shipfee'], $data ['discount'] );
				$this->success ( '恭喜，' . $name . '添加成功！' );
			}
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );

				$member=M('member')->where(array('id'=>$db['memberid']))->find();
				$this->assign('member',$member);

				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}

			$control = 'Member';
			$action = 'loan';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );



			// 状态标识
			$this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );
			$this->assign ( "paystatuslist", C ( 'PAYSTATUS' ) );
			$this->assign ( "paymethodlist", C ( 'PAYMETHOD' ) );

			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除订单
	 *
	 * @param number $id
	 */
	public function deleteloan($id = 0) {
		$tblname = 'order';
		$name = '订单';
		$where=array();
		$where['id']=$id;
		$where['status']=array('in',array(4));
		$find = M ( $tblname )->where($where)->find (  );
		if ($find) {
			M ( $tblname )->where ( array (
					'id' => $id
			) )->delete ();
			M ( 'order_detail' )->where ( array (
					'orderno' => $find['orderno']
			) )->delete ();
			$this->success ( '恭喜，' . $name . '已删除！' );
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}



	/**
	 * 订单列表
	 */
	public function order($searchtype = '',$type='', $keyword = '', $pid = '', $status = '', $p = 1) {
		// we(add_order(1,array('username'=>'张三'),array(1=>2,6=>3,3=>3,4=>5)));
		// we(edit_order('201601251149111019',1,array('username'=>'张四'),array(1=>1,6=>1,3=>1,4=>1)));
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['orderno'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['username'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
			case '2' :
				if (! isN ( $keyword )) {
					$where ['telephone'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
		}

		if(!isN($type))
			$where['paymethod']=$type;

		if (is_numeric ( $pid )) {
			$where ['sortpath'] = array (
					'like',
					'%,' . $pid . ',%' 
			);
		}
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
		
		// 表名
		$tblname = 'order';
		$name = '订单';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		
		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		$this->assign('type',$type);
		
		// 当前表名
		$control = 'Member';
		$action = 'Order';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );
		
		$this->assign ( "title", '订单列表' );
		$this->display ();
	}
	
	/**
	 * 添加
	 */
	public function addOrder() {
		$this->editOrder ();
	}
	/**
	 * 添加修改订单
	 *
	 * @param number $id        	
	 */
	public function editOrder($id = 0) {
		$tblname = 'order';
		$name = '订单';
		$tplname = 'editOrder';
		if (IS_POST) {
			$data = $_POST;
			if ($id) {
				$save = edit_order ( $data ['orderno'], $data ['status'], $data, $data ['detail'] );
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				$save = add_order ( $data ['memberid'], $data, $data ['detail'], $data ['shipfee'], $data ['discount'] );
				$this->success ( '恭喜，' . $name . '添加成功！' );
			}
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				
				$detaillist = M ( 'order_detail' )->where ( array (
						'orderno' => $db ['orderno'] 
				) )->order ( 'productid asc' )->select ();
				$this->assign ( 'detaillist', $detaillist );
				
				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}
			
			$control = 'Member';
			$action = 'order';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			
			$expresslist = get_cache_content ( 'express', '', 'title' );
			$this->assign ( 'expresslist', $expresslist );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );
			$this->assign ( "paystatuslist", C ( 'PAYSTATUS' ) );
			$this->assign ( "paymethodlist", C ( 'PAYMETHOD' ) );
			
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除订单
	 *
	 * @param number $id        	
	 */
	public function deleteOrder($id = 0) {
		$tblname = 'order';
		$name = '订单';
		$where=array();
		$where['id']=$id;
		$where['status']=array('in',array(4));
		$find = M ( $tblname )->where($where)->find (  );
		if ($find) {
			M ( $tblname )->where ( array (
					'id' => $id 
			) )->delete ();
			M ( 'order_detail' )->where ( array (
					'orderno' => $find['orderno'] 
			) )->delete ();
			$this->success ( '恭喜，' . $name . '已删除！' );
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}
	
	/**
	 * 申请退款接口
	 *
	 * @param string $orderno        	
	 */
	public function refund($out_trade_no = '') {
		$where = array ();
		$where ['orderno'] = $out_trade_no;
		$where ['paystatus'] = 1; // 已付款才能退
		$where ['status'] = array('in','1,2,3'); // 代发货，待收货，已完成订单都可退款
		$order = M ( 'order' )->where ( $where )->find ();
		if ($order) {
			if ($order ['paymethod'] == 4) {
				load_wxpay ();
				vendor ( "WxPayPubHelper/WxPayPubHelper" );
				$payinfo = unserialize ( json_decode($order ['payinfo'],true) );
//				dump($payinfo);die;
				if ($payinfo) {
					$refund_orderno = get_order_no ();
					$refund = new \Refund_pub ();
					$refund->setParameter ( "transaction_id", $payinfo ['transaction_id'] ); // openid
					$refund->setParameter ( "out_trade_no", $payinfo ['out_trade_no'] ); // 商户订单号
					$refund->setParameter ( "out_refund_no", $refund_orderno ); // 退款订单号
					$refund->setParameter ( "total_fee", $payinfo ['total_fee'] ); // 总金额
					$refund->setParameter ( "refund_fee", $payinfo ['total_fee'] ); // 退款金额
					$refund->setParameter ( "op_user_id", $payinfo ['mch_id'] ); // 交易类型
					$result = $refund->getResult ();
					if ($result) {
						if ($result ['return_code'] == 'SUCCESS') {
							$refundinfo = serialize ( $result );
							$this->doOrderRefund ( $out_trade_no, $refundinfo );
							$this->success ( '恭喜，退款成功！' );
						} else {
							$this->error ( $result ['return_msg'] );
						}
					} else {
						$this->error ( '对不起，操作失败！' );
					}
				} else {
					$this->error ( '对不起，未找到支付信息！' );
				}
			} else {
				// 3:退款
				$add = balance_add ( $order ['memberid'], 3, $order ['amount'], '积分订单申请退款返还积分' );
				if ($add) {
					$result = array ();
					$result ['return_code'] = 'SUCCESS';
					$result ['return_msg'] = '退款成功';
					$refundinfo = serialize ( $result );
					$this->doOrderRefund ( $out_trade_no, $refundinfo );
					$this->success ( '恭喜，退款成功！' );
				} else {
					$this->error ( '对不起，退款失败！' );
				}
			}
		} else {
			$this->error ( '对不起，操作失败！' );
		}
	}
	
	/**
	 * 处理订单退款
	 * 
	 * @param string $orderno        	
	 */
	private function doOrderRefund($orderno = '', $refundinfo = '') {
		$data = array ();
		$data ['refundinfo'] = $refundinfo;
		$where = array ();
		$where ['orderno'] = $orderno;
		$save = M ( 'order' )->where ( $where )->data ( $data )->save ();
		if ($save !== false) {
			// 处理库存
			$edit = switch_order ( $orderno, 5 );
			return $edit;
		} else {
			return false;
		}
	}
	
	/**
	 * 退款单查询
	 *
	 * @param string $orderno        	
	 */
	public function refundQuery($out_trade_no = '', $refund_orderno = '') {
		if ($out_trade_no || $refund_orderno) {
			$where = array ();
			$where ['orderno'] = $out_trade_no;
			$find = M ( 'order' )->where ( $where )->find ();
			if ($find) {
				$refundquery = unserialize ( $find ['refundqueryinfo'] );
				if ($refundquery ['return_code'] == 'SUCCESS') {
					$this->success ( '款项已到达用户账户，退款成功！' );
					exit ();
				}
			}
			if ($find ['paymethod'] == 1) {
				load_wxpay ();
				vendor ( "WxPayPubHelper/WxPayPubHelper" );
				$refund = new \RefundQuery_pub ();
				if ($refund_orderno) {
					$refund->setParameter ( "out_refund_no", $refund_orderno ); // 退款订单号
				} else {
					$refund->setParameter ( "out_trade_no", $out_trade_no ); // 商户订单号
				}
				$result = $refund->getResult ();
				if ($result) {
					if ($result ['return_code'] == 'SUCCESS') {
						$refundinfo = serialize ( $result );
						$data = array ();
						$data ['refundqueryinfo'] = $refundinfo;
						$where = array ();
						$where ['orderno'] = $out_trade_no;
						$db = M ( 'order' )->where ( $where )->data ( $data )->save ();
						$this->success ( '款项已到达用户账户，退款成功！' );
					} else {
						
						$this->error ( '退款失败：' . $result ['return_msg'] );
					}
				} else {
					$this->error ( '对不起，查询失败！' );
				}
			} else {
				$result = unserialize ( $find ['refundinfo'] );
				if ($result ['return_code'] == 'SUCCESS') {
					$this->success ( '款项已到达用户账户，退款成功！' );
				} else {
					$this->error ( '退款失败！' );
				}
			}
		}
	}
	
	/**
	 * 快递列表
	 */
	public function express($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['content'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
			case '2' :
				if (is_number ( $keyword )) {
					$where ['id'] = $keyword;
				}
				break;
			case '3' :
				if (is_number ( $keyword )) {
					$where ['pid'] = $keyword;
				}
				break;
		}
		
		if (is_numeric ( $pid )) {
			$where ['sortpath'] = array (
					'like',
					'%,' . $pid . ',%' 
			);
		}
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
		
		// 表名
		$tblname = 'content_express';
		$name = '快递';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		
		// 当前表名
		$control = 'Member';
		$action = 'Express';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign ( "title", '快递列表' );
		$this->display ();
	}
	
	/**
	 * 添加
	 */
	public function addExpress() {
		$this->editExpress ();
	}
	/**
	 * 添加修改快递
	 *
	 * @param number $id        	
	 */
	public function editExpress($id = 0) {
		$tblname = 'content_express';
		$name = '快递';
		$tplname = 'editExpress';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			if (isN ( $data ['siteurl'] )) {
				$this->error ( '对不起，' . $name . '查询网址不能为空！' );
			}
			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id 
				) )->data ( $data )->save ();
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id 
				) )->setField ( 'sort', $id );
				$this->success ( '恭喜，' . $name . '添加成功！' );
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}
			
			$control = 'Member';
			$action = 'express';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除快递
	 *
	 * @param number $id        	
	 */
	public function deleteExpress($id = 0) {
		$tblname = 'content_express';
		$name = '快递';
		$find = M ( $tblname )->find ( $id );
		if ($find) {
			M ( $tblname )->where ( array (
					'id' => $id 
			) )->delete ();
			$this->success ( '恭喜，' . $name . '已删除！' );
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}
	
	/**
	 * 余额记录
	 */
	public function balance($searchtype = '', $keyword = '', $type = '', $pid = '', $timefrom = '', $timeto = '', $p = 1) {
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['username'] = $keyword;
				}
				break;
			case '1' :
				if (is_number ( $keyword )) {
					$where ['memberid'] = $keyword;
				}
				break;
			case '2' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
		}
		if (is_number ( $type )) {
			$where ['type'] = $type;
		}
		
		if (is_date ( $timefrom )) {
			$this->assign ( "timefrom", $timefrom );
			$where ['addtime'] [] = array (
					'egt',
					$timefrom 
			);
		}
		if (is_date ( $timeto )) {
			$this->assign ( "timeto", $timeto );
			$timeto = get_date_add ( $timeto, 1 );
			$timeto = date2format ( $timeto );
			$where ['addtime'] [] = array (
					'lt',
					$timeto 
			);
		}
		
		// 表名
		$tblname = 'account_log';
		$name = '积分';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}
		
		// 已有分类列表
		$list = get_cache_list ( 'member_balance_type' );
		$list = list_to_tree ( $list );
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "type", $type );
		$this->assign ( "typelist", $list );
		
//		$this->assign ( "status", $status );
		
		// 当前表名
		$control = 'Member';
		$action = 'Balance';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign ( "title", '积分记录' );
		$this->display ();
	}
	
	/**
	 * 余额记录
	 */
	public function cash($searchtype = '', $keyword = '', $status = '', $pid = '', $timefrom = '', $timeto = '', $p = 1) {
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['username'] = $keyword;
				}
				break;
			case '1' :
				if (is_number ( $keyword )) {
					$where ['memberid'] = $keyword;
				}
				break;
			case '2' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
		}
		if (is_number ( $status )) {
			$where ['status'] = $status;
		}
		
		if (is_date ( $timefrom )) {
			$this->assign ( "timefrom", $timefrom );
			$where ['addtime'] [] = array (
					'egt',
					$timefrom 
			);
		}
		if (is_date ( $timeto )) {
			$this->assign ( "timeto", $timeto );
			$timeto = get_date_add ( $timeto, 1 );
			$timeto = date2format ( $timeto );
			$where ['addtime'] [] = array (
					'lt',
					$timeto 
			);
		}
		
		// 表名
		$tblname = 'member_cash';
		$name = '提现';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		
		// 当前表名
		$control = 'Member';
		$action = 'Cash';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'MEMBERSTATUS' ) );
		
		$this->assign ( "title", '提现记录' );
		$this->display ();
	}
	
	/**
	 * 删除提现
	 *
	 * @param number $id        	
	 */
	public function deleteCash($id = 0) {
		$tblname = 'member_cash';
		$name = '提现';
		$find = M ( $tblname )->find ( $id );
		if ($find) {
			M ( $tblname )->where ( array (
					'id' => $id 
			) )->delete ();
			$this->success ( '恭喜，' . $name . '已删除！' );
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}
	public function editCash($id = 0, $status = '') {
		if (IS_POST) {
			$data = $_POST;
			$where = array ();
			$where ['id'] = $id;
			$find = M ( 'member_cash' )->where ( $where )->find ();
			if ($find) {
				if ($find ['status']) {
					$this->error ( '对不起，该提现已处理，请勿重复提交！' );
				} else {
					if ($status == 1) {
//						$balance = get_member_balance ( $find ['memberid'] );
//						if ($find ['amount'] > $balance) {
//							$this->error ( '对不起，用户余额不足！' );
//						}
						// 退款
						$result = $this->wechat_cash ( $find ['memberid'], $find ['amount'] );
						//开通微信企业付款之后吧下面两行代码注释
						$result['status']=1;
						$result['info']='线下打款到账';
						if ($result ['status'] == 1) {
							M ( 'member_cash' )->where ( $where )->setField ( array (
									'status' => 1,
									'remark' => $result ['info'] 
							) );
							$this->success ( '恭喜，提现已成功到账！' );
						} else {
							$this->error ( '接口错误：' . $result ['info'] );
						}
					} else {
						M ( 'member_cash' )->where ( $where )->setField ( 'status', 2 );
						M('member')->where(array('id'=>$find['memberid']))->setField('balance',array('exp','balance + '.$find['amount']));
						$this->success ( '恭喜，提现申请已拒绝！' );
					}
				}
			} else {
				$this->error ( '对不起，提现记录未找到！' );
			}
		} else {
			$this->error ( '对不起，参数错误！' );
		}
	}
	
	/**
	 * 微信发红包
	 * 
	 * @param string $openid        	
	 * @param number $amount        	
	 */
	private function wechat_cash($memberid = 0, $amount = 0) {
		$where = array ();
		$where ['id'] = $memberid;
		$member = M ( 'member' )->where ( $where )->find ();
		if (! $member) {
			return (er ( '用户不存在' ));
		} else {
			$openid = $member ['openid'];
		}
		
		// 微信支付
		load_wxpay ();
		$wxpay = C ( 'WXPAY' );
		
		// 请求接口
		$payurl = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
		$randstr = rand_str ( 32 );
		$orderno = get_order_no ();
		$data = array ();
		$data ['mch_appid'] = $wxpay ['APPID'];
		$data ['mchid'] = $wxpay ['MCHID'];
		$data ['nonce_str'] = $randstr;
		$data ['partner_trade_no'] = $orderno;
		$data ['openid'] = $member ['openid'];
		$data ['check_name'] = 'NO_CHECK'; // NO_CHECK,OPTION_CHECK,FORCE_CHECK
		$data ['re_user_name'] = '小微';
		$data ['amount'] = $amount * 100;
		$data ['desc'] = '账户提现';
		$data ['spbill_create_ip'] = get_client_ip ();
		$key = $wxpay ['KEY'];
		$sign = $this->getSignature ( $data, $key, 'md5' );
		$data ['sign'] = $sign;
		$data = $this->xml_encode ( $data, 'xml' );
		$result = $this->curl_post_ssl ( $payurl, $data );
		$result = $this->xml_to_array ( $result );
//		dump($result);die;
		if ($result ['result_code'] == 'SUCCESS') {
			if($result['return_code']=='SUCCESS'){
				// 付款成功:扣余额
				$json = json_encode ( $result );
				//			$paid = balance_add ( $memberid, 4, floatval ( $amount ), $json );
				//if ($paid) {
				return (ok ( $result ['return_msg'] ));
				//			} else {
				//				return (er ( '微信支付成功，但扣款失败！' ));
				//			}
			}
		} else {
			return (er ( $result ['return_msg'] ));
		}
	}
	private function xml_encode($data, $root = 'think', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8') {
		if (is_array ( $attr )) {
			$_attr = array ();
			foreach ( $attr as $key => $value ) {
				$_attr [] = "{$key}=\"{$value}\"";
			}
			$attr = implode ( ' ', $_attr );
		}
		$attr = trim ( $attr );
		$attr = empty ( $attr ) ? '' : " {$attr}";
		$xml .= "<{$root}{$attr}>";
		$xml .= data_to_xml ( $data, $item, $id );
		$xml .= "</{$root}>";
		return $xml;
	}
	private function curl_post_ssl($url, $vars, $second = 30, $aHeader = array()) {
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
	private function xml_to_array($xmlstring) {
		return ( array ) simplexml_load_string ( $xmlstring, 'SimpleXMLElement', LIBXML_NOCDATA );
	}
	private function getSignature($arrdata, $key1, $method = "sha1") {
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
	public function staff($searchtype = '', $keyword = '', $status = '', $p = 1,$departmentid='') {

		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['username'] = array (
							'like',
							'%' . $keyword . '%'
					);
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%'
					);
				}
				break;
		}

		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		if($departmentid){
			$where['departmentid']=$departmentid;
		}

		// 表名
		$tblname = 'staff';
		$name = '员工';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();


		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();

		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}

		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		$this->assign ( "departmentid", $departmentid );

		// 当前表名
		$control = 'Member';
		$action = 'staff';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );

		//所有员工
		$allstaff=M('staff')->where(array('status'=>1))->select();
		$this->assign('allstaff',$allstaff);

		//部门
		$department=M('department')->where(array('status'=>1))->select();
		$this->assign('department',$department);

		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '员工列表' );
		$this->display ();
	}

	/**
	 * 添加
	 */
	public function addstaff() {
		$this->editstaff ();
	}
	/**
	 * 添加修改员工
	 *
	 * @param number $id
	 */
	public function editstaff($id = 0) {
		$tblname = 'staff';
		$name = '员工';
		$tplname = 'editstaff';
		if (IS_POST) {
			$data = $_POST;


			$data ['username'] = trim ( $data ['username'] );
			$data ['userpwd'] = trim ( $data ['userpwd'] );
			if (isN ( $data ['username'] )) {
				$this->error ( '对不起，' . $name . '用户名不能为空！' );
			}

			$data['departlimit']=json_encode($data['departlimit']);

			if ($id) {
				unset ( $data ['username'] );
				if (! isN ( $data ['userpwd'] )) {
					$data ['userpwd'] = md5 ( $data ['userpwd'] );
				} else {
					unset ( $data ['userpwd'] );
				}

				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {

				if (isN ( $data ['userpwd'] )) {
					$this->error ( '对不起，' . $name . '密码不能为空！' );
				}

				$where = array ();
				$where ['username'] = $data ['username'];
				$find = M ( $tblname )->where ( $where )->find ();
				if ($find) {
					$this->error ( '对不起，登录名【' . $data ['username'] . '】已存在！' );
				}
				$data ['userpwd'] = md5 ( $data ['userpwd'] );
				$data['registercode'] =rand(1000000,99999999);
				
				$id = M ( $tblname )->data ( $data )->add ();

				$this->success ( '恭喜，' . $name . '添加成功！' );
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );

				$limit=json_decode($db['departlimit'],true);
				$this->assign('limit',$limit);


				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}

			//部门列表
			$department=M('department')->where(array('status'=>1))->select();
			$this->assign('department',$department);

			$control = 'Member';
			$action = 'staff';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}

	/**
	 * 删除员工
	 *
	 * @param number $id
	 */
	public function deletestaff($id = 0) {
		$tblname = 'staff';
		$name = '员工';
		$find = M ( $tblname )->find ( $id );
		if ($find) {
			M ( $tblname )->where ( array (
					'id' => $id
			) )->delete ();
			$this->success ( '恭喜，' . $name . '已删除！' );
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}
	
	public function membermy($registercode = 0) {
		
		$contentlist = M ('member')->where(['registercode'=>$registercode])->select();
		if(!$contentlist){
			$this->error ( '该员工目前还没有使用他注册码的客户' );
		}
		$this->assign('contentlist',$contentlist);
		$this->display ();
		
	}
	

}
?>