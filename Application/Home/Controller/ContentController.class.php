<?php

namespace Home\Controller;

class ContentController extends BaseController {
	public function index() {
	}
	
	/**
	 * 栏目信息
	 * 
	 * @param number $id        	
	 * @param number $p        	
	 */
	public function lists($id = 0, $p = 1) {
		$tblname = 'news';
		$where = array ();
		$where ['id'] = $id;
		$where ['status'] = 1;
		$db = M ( 'category_' . $tblname )->where ( $where )->find ();
		if (! $db) {
			go_404 ();
		} else {
			$this->assign ( 'id', $id );
			$this->assign ( 'title', $db ['name'] );
			$this->display ();
		}
	}
	
	/**
	 * 信息列表
	 * 
	 * @param number $id        	
	 * @param number $p        	
	 */
	public function getLists($id = 0, $p = 1) {
		$tblname = 'content_news';
		$where = array ();
		$where ['sortpath'] = array('like','%,'.$id.',%');
		$where ['status'] = 1;
		
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->page ( $p, $row )->order('sort desc')->select ();
		$this->assign ( 'list', $list );
		$this->assign ( 'p', $p );
		$this->display ( 'getLists' );
	}
	
	/**
	 * 详情
	 *
	 * @param number $id        	
	 */
	public function view($id = 0) {
		$tblname = 'info';
		
		// 取内容信息
		$where = array ();
		$where ['id'] = $id;
		$where ['status'] = 1;
		$db = M ( 'content_' . $tblname )->where ( $where )->find ();
		if (! $db) {
			go_404 ();
		} else {
			M ( 'content_' . $tblname )->where ( $where )->setInc ( 'hits' );
			$db ['hits'] += 1;
			if (is_url ( $db ['linkurl'] )) {
				redirect ( $db ['linkurl'] );
			}
			// 取栏目信息
			$where = array ();
			$where ['id'] = $db ['pid'];
			$channel = M ( 'category_' . $tblname )->where ( $where )->find ();
			$this->assign ( 'channel', $channel );
		}
		$this->assign ( 'db', $db );
		$this->assign ( 'categryid', $db ['pid'] );
		

		$this->assign ( 'id', $id );
		$this->assign ( 'title', $db ['title'] );
		$this->assign ( 'keywords', $db ['keywords'] );
		$this->assign ( 'description', $db ['description'] );
		$this->display ();
	}
}