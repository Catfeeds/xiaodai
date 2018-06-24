<?php

namespace Home\Controller;

//use Think\Controller;

class IndexController extends BaseController {
	public function index(){
		redirect(U('Index/menu'));
	}

	public function menu() {
		$memberid=get_memberid();
		//var_dump($memberid);die;

		$member=M('member')->where(array('id'=>$memberid))->find();

		$canapply=0;
		if($member['cominfo']==1&&$member['contacts']==1&&$member['tmobile']==1&&$member['bank']==1){
			$canapply=1;
		}
		$this->assign('canapply',$canapply);
		$this->assign('memberid',$memberid);
//		dump($member);
		$this->assign('member',$member);
		$title=C('config.WEB_SITE_TITLE');
		$this->assign ( 'title', $title );
		$this->display ();
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

	public function prize(){


		$prize=M('content_turntable')->where(array('num'=>array('gt',0),'status'=>1))->order('id asc')->select();

//		foreach($prize as $key=>$val){
//			$prize[$key]['title']=$val['title'].'|'.$val['remark'];
//		}
		$this->assign('prize',$prize);


		$this->assign('title','大转盘');
		$this->display();
	}


	public function getprize(){
		$prize_arr=M('content_turntable')->where(array('num'=>array('gt',0),'status'=>1))->order('id asc')->select();
		foreach ($prize_arr as $k=>$v) {
			$arr[$v['id']] = $v['chance'];
		}

		$prize_id = getRand($arr);

		foreach($prize_arr as $k=>$v){ //获取前端奖项位置
			if($v['id'] == $prize_id){
				$prize_site = $k;
				break;
			}
		}

		$res = $prize_arr[$prize_site];

		if(!$res){
			$data['status']=0;
			$data['info']='没有奖品了';
			$this->ajaxReturn($data);
		}

		$dataprize=array();
		$memberid=get_memberid();
		$dataprize['memberid']=$memberid;
		$dataprize['prizeid']=$res['id'];
		$dataprize['prizename']=$res['remark'];
		$dataprize['addtime']=date('Y-m-d H:i:s');
		$dataprize['status']=0;
		$pos = strpos($res['remark'], '谢谢');
//		dump($pos);die;
		if($pos>-1){
			$add=1;
		}else{
			$add=M('member_prize')->data($dataprize)->add();
			M('content_turntable')->where(array('id'=>$res['id']))->setDec('num',1);
		}
		if($add===false){
			$data['status']=0;
			$data['info']='人数太多啦,请稍后再抽奖';
			$this->ajaxReturn($data);
		}

		$data['status']=1;
		$data['prize_name'] = $res['remark'];
		$data['prize_site'] = $prize_site+1;//前端奖项从-1开始
		$data['prize_id'] = $prize_id;
		$this->ajaxReturn($data);

	}


	public function prizerec(){
		$memberid=get_memberid();
//		$memberid=31;
		$fields="mp.*,mm.nickname";
		$record=M('member_prize as mp')->join('my_member as mm on mm.id=mp.memberid')->where(array('mp.memberid'=>$memberid))->field($fields)->select();
		$this->assign('record',$record);
		$this->display();
	}

	public function question(){

		$fields='f.*,mm.headimgurl';
		$question=M('feedback as f')->join('my_member as mm on mm.id=f.memberid')->where(array('f.status'=>1,'f.isresume'=>1))->field($fields)->select();
		$this->assign('question',$question);

		$this->assign('title','在线留言');
		$this->assign('keyword','在线留言');
		$this->assign('description','在线留言');
		$this->display();
	}

	public function subquestion(){
		$memberid=get_memberid();
		$member=M('member')->where(array('id'=>$memberid))->find();
		$content=$_POST['content'];
		$data['content']=$content;
		$data['memberid']=$memberid;
		$data['addtime']=date('Y-m-d H:i:s');
		$data['username']=$member['nickname'];
		$data['status']=0;
		$add=M('feedback')->data($data)->add();
		$result=array();
		if($add===false){
			$result['status']=0;
			$result['info']='留言失败';
			$this->ajaxReturn($result);
		}
		$result['status']=1;
		$result['info']='留言成功';
		$this->ajaxReturn($result);
	}


}