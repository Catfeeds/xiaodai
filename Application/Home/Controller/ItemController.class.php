<?php

namespace Home\Controller;

class ItemController extends BaseController {
	public function index() {
	}
	
	/**
	 * 栏目信息
	 * 
	 * @param number $id        	
	 * @param number $p        	
	 */
	public function lists($id = 0, $p = 1,$type=1,$keyword='') {
		$tblname = 'product';
		if($id){
			$where = array ();		
			$where ['id'] = $id;
			$where ['status'] = 1;
			$db = M ( 'category_' . $tblname )->where ( $where )->find ();
//			if (! $db) {
//				go_404 ();
//			}
		}else{
			$db['name']='商品列表';
		}
		
		$categorylist=get_cache_list('category_product',$id);
		$this->assign('categorylist',$categorylist);

		$this->assign ( 'id', $id );
		$this->assign('type',$type);
		$this->assign('keyword',$keyword);
		$this->assign ( 'title', $db ['name'] );
		$this->display ();
	}
	
	/**
	 * 信息列表
	 * 
	 * @param number $id        	
	 * @param number $p        	
	 */
	public function getLists($id = 0, $p = 1,$field='',$sort='',$type=1,$keyword='') {
		$tblname = 'content_product';
		$where = array ();
		if($id){
			$where ['sortpath'] = array('like','%,'.$id.',%');
		}
		$where ['status'] = 1;

		$where['type']=$type;
		if($type==2 && $field=='price')
			$field='point';

		if(!isN($keyword))
			$where['title']=array('like','%'.$keyword.'%');



		$order='sort desc';
		if($sort){
			$order=$field.' '.$sort;
		}




		
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->page ( $p, $row )->order($order)->select ();
		$this->assign ( 'list', $list );
		$this->assign ( 'p', $p );
		$this->display ( 'getLists' );
	}
	
	
	/**
	 * 商品详情
	 *
	 * @param number $id        	
	 */
	public function view($id = 0) {
		$tblname = 'product';

//		dump(session('cart_name'));die;

		$where = array ();
		$where ['status'] = 1;
		$where ['id'] = $id;
		$db = M ( 'content_' . $tblname )->where ( $where )->find ();
		if (! $db) {
			go_404 ();
		} else {
			M ( 'content_' . $tblname )->where ( $where )->setInc ( 'hits' );
			$db ['hits'] += 1;
			if (is_url ( $db ['linkurl'] )) {
				redirect ( $db ['linkurl'] );
			}
			$imglist=json_decode($db['images']);
			$this->assign('imglist',$imglist);

			$attr=json_decode($db['attr'],true);
			$attrs=array();
			foreach($attr as $key=>$val){
				$val=explode(',',$val);
				$attrs[$key]=$val;

			}
			$this->assign('attrs',$attrs);
			
			$this->assign ( 'db', $db );
		}
		
// 		// 上一篇
// 		$where = array ();
// 		$where ['pid'] = $db ['pid'];
// 		$where ['id'] = array (
// 				'neq',
// 				$id 
// 		);
// 		$where ['sort'] = array (
// 				'elt',
// 				$db ['sort'] 
// 		);
// 		$prev = M ( 'content_' . $tblname )->where ( $where )->order ( 'sort desc,id desc' )->find ();
// 		$this->assign ( 'prev', $prev );
		
// 		// 下一篇
// 		$where = array ();
// 		$where ['pid'] = $db ['pid'];
// 		$where ['id'] = array (
// 				'neq',
// 				$id 
// 		);
// 		$where ['sort'] = array (
// 				'egt',
// 				$db ['sort'] 
// 		);
// 		$next = M ( 'content_' . $tblname )->where ( $where )->order ( 'sort asc,id asc' )->find ();
// 		$this->assign ( 'next', $next );
		
		$title = $db ['title'];
		$this->assign ( 'title', $title );
		$this->assign ( 'keywords', $db ['keywords'] );
		$this->assign ( 'description', $db ['description'] );
		$this->display ();
	}


	/**
	 *商品分类
	 *
	 */
	public function category($id=0){
		$first=M('category_product')->where(array('status'=>1,'pid'=>0))->select();
		$this->assign('first',$first);
		if($id)
			$pid=$id;
		else
			$pid=$first[0]['id'];

		$this->assign('pid',$pid);

		$second=M('category_product')->where(array('status'=>1,'pid'=>$pid))->select();
		$this->assign('second',$second);
		$this->assign('title','商品分类');

		$this->display();

	}

	/**
	 *青莲课堂
	 *
	 */
	public function ketang(){

		$ketang=M('content_product')->where(array('pid'=>22,'status'=>1))->order('sort asc ')->select();
		$this->assign('ketang',$ketang);

		$this->display();
	}


}