<?php

namespace Crm\Controller;

use Think\Controller;

class BaseController extends Controller {
	public function _initialize() {



		$manageid=get_manageid();

		if(!$manageid){
			redirect('/Crm/Login/login/fromurl/'.think_encrypt(get_current_url()).'.html');
		}else{
			$find=M('staff')->where(array('id'=>$manageid,'status'=>1))->find();
			if(!$find){
				redirect('/Crm/Login/login/fromurl/'.think_encrypt(get_current_url()).'.html');
			}
		}


		//同意申请之后2个月内未下单的客户自动进入公海
		//系统设置天数后未跟进的客户后自动进入公海
		$wherego=array();
		$wherego['m.staffid']=$manageid;
		$wherego['ma.status']=1;
		$wherego['m.memberstatus']=array('lt',getcomplatestatus());

		$membergolist=M('member as m')->join('my_member_apply as ma on ma.memberid=m.id')->where($wherego)->field('m.*,ma.updatetime as maupdatetime')->select();
		foreach($membergolist as $km=>$vm){
			//同意申请之后天数
			$updatedays=diffBetweenTwoDays($vm['maupdatetime'],date('Y-m-d H:i:s'));
			//未跟进天数
			if($vm['lastfollowtime']){
				$days=diffBetweenTwoDays($vm['lastfollowtime'],date('Y-m-d H:i:s'));
			}else{
				$days=0;
			}

			//2个月内未下单客户进入公海
			$noworderdays=C('config.NO_ORDER_DAYS');
				
			if($updatedays>$noworderdays){
				$maxtime=M('order')->where(array('memberid'=>$vm['id']))->getField('max(addtime) as maxtime');
				if($maxtime){
					$diffdays=diffBetweenTwoDays($maxtime,date('Y-m-d H:i:s'));
					if($diffdays>60){
						M('member')->where(array('id'=>$vm['id']))->setField(array('applystatus'=>0,'staffid'=>0));
						M('member_apply')->where(array('memberid'=>$vm['id'],'staffid'=>get_manageid()))->setField('status',3);
					}
				}else{
					M('member')->where(array('id'=>$vm['id']))->setField(array('applystatus'=>0,'staffid'=>0));
					M('member_apply')->where(array('memberid'=>$vm['id'],'staffid'=>get_manageid()))->setField('status',3);
				}
			}

			//系统设置天数后未跟进的客户后自动进入公海
			$nofollowdays=C('config.NO_FOLLOW_DAYS');
			if($days>$nofollowdays){
				M('member')->where(array('id'=>$vm['id']))->setField(array('applystatus'=>0,'staffid'=>0));
				M('member_apply')->where(array('memberid'=>$vm['id'],'staffid'=>get_manageid()))->setField('status',3);
			}


		}





		//获取未读消息数量
		$staffid=get_manageid();
		$record=M('message_record as mr')->join('my_content_news as mc on mc.id=mr.newsid')
				->where(array('mr.staffid'=>$staffid,'mc.status'=>1))->count();

		$cate=M('category_news')->where(array('inner'=>1,'status'=>1))->select();
		$ids=array();
		foreach($cate as $k=>$v){
			$ids[$k]=$v['id'];
		}


		$messagenum=M('content_news')->where(array('pid'=>array('in',$ids),'status'=>1))->count();
		$this->assign('messagenum',$messagenum-$record);


		$staff=M('staff')->where(array('id'=>$manageid))->getField('openid');//检查是否获取了openid
		if(!$staff){
        
        
			$openid = openid();
			if (!$openid) {
				$current_url = get_current_url();
				get_workauth_openid($current_url);
			} else {
				M('staff')->where(array('id'=>$manageid))->setField('openid',$openid);
			}
		}
        
        
		$this->checkwork();


	}

	function _empty(){
		header( " HTTP/1.0  404  Not Found" );

		$this->display("Public:404");
	}

	public function checkwork(){
		$now=date("Y-m-d");
		$list=M("work")->where(array('end'=>array('egt',$now)))->select();
		foreach($list as $k=>$v){
			$left=diffBetweenTwoDays($now,$v['end']);
			if($v['notice']!=1 && $left==0){
				$staff=M('staff')->where(array('id'=>$v['staffid']))->find();
				sendcrmmsg($staff['id'],"您有一项工作计划【".$v['content']."】今日到期，请登陆查看。");
				$setnotice=M('work')->where(array('id'=>$v['id']))->setField('notice',1);
			}
		}
	}



}