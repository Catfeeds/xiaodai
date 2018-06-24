<?php

namespace Admin\Controller;

class IndexController extends BaseController {
	public function index() {
		$this->getNodes ();
		$this->assign ( "title", C ( 'config.WEB_SITE_TITLE' ) . '-后台管理系统' );
		$this->display ();
	}
	public function sysinfo() {
		
		//统计信息
		$arrnum=array();
		$arrnum['member']=M('member')->count();
		$arrnum['order']=M('order')->count();
		$arrnum['product']=M('content_product')->count();
		$arrnum['coupon']=M('coupon')->count();
		$this->assign('statistic',$arrnum);

		//员工列表
		$staff=M('staff')->where(array('status'=>1))->select();
		$this->assign('staff',$staff);

		
		// 取登录信息
		$where ['id'] = (session ( 'adminid' ));
		$where ['status'] = 1;
		$db = M ( 'user' )->where ( $where )->find ();
		$this->assign ( "db", $db );
		$this->assign ( "title", '登录信息' );
		$this->display ();
	}
	
	/**
	 * 获取权限节点
	 */
	private function getNodes() {
		//输出常用节点
		$where = array ();
		$where ['status'] = 1;
		$where['isresume']=1;
		$nodelist1= M ( "node" )->where ( $where )->order ( 'sort asc' )->select ();
		foreach ($nodelist1 as $k=>$v){
			if(isN($v['url'])){
				$p=get_cache_value('node',$v['pid'],'name');
				$nodelist1[$k]['url']=($p.'/'.$v['name']);
			}
		}
		$this->assign('nodelist1',$nodelist1);
		
		// 输出当前Node列表
		$where = array ();
		$where ['status'] = 1;
		if (empty ( $_SESSION [C ( 'ADMIN_AUTH_KEY' )] )) {
			$where ['super'] = 0;
		}
		$list = M ( "node" )->where ( $where )->order ( 'sort asc' )->select ();
		$access = M ( "access" )->where ( array (
				'role_id' => session ( 'roleid' ) 
		) )->cache ( true )->getField ( "node_id", true );
		$list = node_merge ( $list, $access, 1 );
		$this->assign ( "nodelist", $list );
		$this->assign ( 'supper', session ( C ( 'ADMIN_AUTH_KEY' ) ) );
	}


	public function message(){

		$list=M('message as mc')->join('my_member as mm on mm.id=mc.memberid')->field('mc.*,mm.nickname')->order('mc.isquesnew desc')->select();

		$this->assign('list',$list);

		$this->assign('title','易焯微信客服系统');
		$this->display();
	}


}
?>