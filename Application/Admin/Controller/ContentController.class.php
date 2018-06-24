<?php

namespace Admin\Controller;

class ContentController extends BaseController {
	public function index() {
	}
	/**
	 * 选择
	 *
	 * @param number $id        	
	 */
	public function modal($type = '', $val = '', $id = '', $p = 1, $searchtype = '', $keyword = '') {
		switch ($type) {
			case 'member' :
				// 表名
				$tblname = 'member';
				$name = '会员';
				
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
						if (is_number ( $keyword )) {
							$where ['id'] = $keyword;
						}
						break;
				}
				
				break;
		}
		// 分页
		$row = 5;
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		
		$this->assign ( "list", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "val", $val );
		$this->assign ( "type", $type );
		$this->assign ( "id", $id );
		$this->assign ( "name", $name );
		
		$this->display ();
	}
	/**
	 * 资讯列表
	 */
	public function news($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
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
		$tblname = 'content_news';
		$name = '资讯';
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
		
		// 已有分类列表
		$list=get_cache_list('category_news');
		$list = list_to_tree ( $list );
		$this->assign ( "list", $list );
		
		// 当前表名
		$control = 'Content';
		$action = 'News';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign ( "title", '资讯列表' );
		$this->display ();
	}
	
	/**
	 * 添加
	 */
	public function addNews() {
		$this->editNews ();
	}
	/**
	 * 添加修改资讯
	 *
	 * @param number $id        	
	 */
	public function editNews($id = 0) {
		$tblname = 'content_news';
		$name = '资讯';
		$tplname = 'editNews';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			if (isN ( $data ['content'] )) {
				$this->error ( '对不起，' . $name . '内容不能为空！' );
			}
			$data ['images'] = json_encode ( $data ['images'] );
			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id 
				) )->data ( $data )->save ();
				update_sortpath_content ( $tblname, 'category_news', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				if (isN ( $data ['keywords'] )) {
					$data ['keywords'] = $data ['title'];
				}
				if (isN ( $data ['description'] )) {
					$data ['description'] = cut_str ( htmlclr ( $data ['content'] ), 0, 200 );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id 
				) )->setField ( 'sort', $id );
				update_sortpath_content ( $tblname, 'category_news', $id, $data ['pid'] );
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
			
			// 已有分类列表
			$list=get_cache_list('category_news');
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
			$control = 'Content';
			$action = 'news';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除资讯
	 *
	 * @param number $id        	
	 */
	public function deleteNews($id = 0) {
		$tblname = 'content_news';
		$name = '资讯';
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
	 * 单页列表
	 */
	public function info($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
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
		$tblname = 'content_info';
		$name = '单页';
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
		
		// 已有分类列表
		$list=get_cache_list('category_info');
		$list = list_to_tree ( $list );
		$this->assign ( "list", $list );
		
		// 当前表名
		$control = 'Content';
		$action = 'Info';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign ( "title", '单页列表' );
		$this->display ();
	}
	
	/**
	 * 添加
	 */
	public function addInfo() {
		$this->editInfo ();
	}
	/**
	 * 添加修改单页
	 *
	 * @param number $id        	
	 */
	public function editInfo($id = 0) {
		$tblname = 'content_info';
		$name = '单页';
		$tplname = 'editInfo';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			if (isN ( $data ['content'] )) {
				$this->error ( '对不起，' . $name . '内容不能为空！' );
			}
			$data ['images'] = json_encode ( $data ['images'] );
			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id 
				) )->data ( $data )->save ();
				update_sortpath_content ( $tblname, 'category_info', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				if (isN ( $data ['keywords'] )) {
					$data ['keywords'] = $data ['title'];
				}
				if (isN ( $data ['description'] )) {
					$data ['description'] = cut_str ( htmlclr ( $data ['content'] ), 0, 200 );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id 
				) )->setField ( 'sort', $id );
				update_sortpath_content ( $tblname, 'category_info', $id, $data ['pid'] );
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
			
			// 已有分类列表
			$list=get_cache_list('category_info');
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
			
			$control = 'Content';
			$action = 'info';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除单页
	 *
	 * @param number $id        	
	 */
	public function deleteInfo($id = 0) {
		$tblname = 'content_info';
		$name = '单页';
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
	 * 产品列表
	 */
	public function product($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
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
		$where['type']=1;//商品产品
		// 表名
		$tblname = 'content_product';
		$name = '产品';
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
	
		// 已有分类列表
		$list=get_cache_list('category_product');
		$list = list_to_tree ( $list );
		$this->assign ( "list", $list );
	
		// 当前表名
		$control = 'Content';
		$action = 'product';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );
	
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
	
		$this->assign ( "title", '产品列表' );
		$this->display ();
	}

//积分产品列表
	public function pointproduct($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1){
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

		$where['type']=2;//积分产品
		// 表名
		$tblname = 'content_product';
		$name = '积分产品';
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
		$control = 'Content';
		$action = 'pointproduct';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		//$this->assign ( "pid", $pid );

		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '积分产品列表' );
		$this->display ();
	}

	
	/**
	 * 添加
	 */
	public function addProduct() {
		$this->editProduct ();
	}

	/**
	 * 添加
	 */
	public function addpointProduct() {
		$this->editpointProduct ();
	}

	/**
	 * 添加修改产品
	 *
	 * @param number $id
	 */
	public function editProduct($id = 0) {
		$tblname = 'content_product';
		$name = '产品';
		$tplname = 'editProduct';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['pid'] )) {
				$this->error ( '对不起，请选择' . $name . '分类！' );
			}
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			if (isN ( $data ['content'] )) {
				$this->error ( '对不起，' . $name . '内容不能为空！' );
			}
			$attr=array();
			foreach($data['attr_name'] as $key=>$val){
				$data['attr_value'][$key]=str_replace('，',',',$data['attr_value'][$key]);
				$attr[$val]=$data['attr_value'][$key];
			}
			$attr=json_encode($attr,JSON_UNESCAPED_UNICODE);
			$data['attr']=$attr;

			$data ['images'] = json_encode ( $data ['images'] );

			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();
				update_sortpath_content ( $tblname, 'category_product', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '修改成功！',U('Content/product') );
			} else {
				if (isN ( $data ['keywords'] )) {
					$data ['keywords'] = $data ['title'];
				}
				if (isN ( $data ['description'] )) {
					$data ['description'] = cut_str ( htmlclr ( $data ['content'] ), 0, 200 );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id
				) )->setField ( 'sort', $id );
				update_sortpath_content ( $tblname, 'category_product', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '添加成功！',U('Content/product')  );
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
				
			// 已有分类列表
			$list=get_cache_list('category_product');
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
				
			$control = 'Content';
			$action = 'product';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
				
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}


	/**
	 * 添加修改产品
	 *
	 * @param number $id
	 */
	public function editpointProduct($id = 0) {
		$tblname = 'content_product';
		$name = '产品';
		$tplname = 'editpointProduct';
		if (IS_POST) {
			$data = $_POST;

			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			if (isN ( $data ['content'] )) {
				$this->error ( '对不起，' . $name . '内容不能为空！' );
			}
			$attr=array();
			foreach($data['attr_name'] as $key=>$val){
				$data['attr_value'][$key]=str_replace('，',',',$data['attr_value'][$key]);
				$attr[$val]=$data['attr_value'][$key];
			}
			$attr=json_encode($attr,JSON_UNESCAPED_UNICODE);
			$data['attr']=$attr;

			$data ['images'] = json_encode ( $data ['images'] );

			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();
				//update_sortpath_content ( $tblname, 'category_product', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '修改成功！',U('Content/pointproduct')  );
			} else {
				if (isN ( $data ['keywords'] )) {
					$data ['keywords'] = $data ['title'];
				}
				if (isN ( $data ['description'] )) {
					$data ['description'] = cut_str ( htmlclr ( $data ['content'] ), 0, 200 );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id
				) )->setField ( 'sort', $id );
				//update_sortpath_content ( $tblname, 'category_product', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '添加成功！',U('Content/pointproduct')  );
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}

			$control = 'Content';
			$action = 'pointproduct';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}



	/**
	 * 删除产品
	 *
	 * @param number $id
	 */
	public function deleteProduct($id = 0) {
		$tblname = 'content_product';
		$name = '产品';
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
	 * 课程列表
	 */
	public function classes($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
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
		$tblname = 'content_classes';
		$name = '课程';
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

		// 已有分类列表
		$list=get_cache_list('category_classes');
		$list = list_to_tree ( $list );
		$this->assign ( "list", $list );

		// 当前表名
		$control = 'Content';
		$action = 'classes';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );

		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '课程列表' );
		$this->display ();
	}

	/**
	 * 添加
	 */
	public function addclasses() {
		$this->editclasses ();
	}
	/**
	 * 添加修改课程
	 *
	 * @param number $id
	 */
	public function editclasses($id = 0) {
		$tblname = 'content_classes';
		$name = '课程';
		$tplname = 'editclasses';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['pid'] )) {
				$this->error ( '对不起，请选择' . $name . '分类！' );
			}
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			if (isN ( $data ['content'] )) {
				$this->error ( '对不起，' . $name . '内容不能为空！' );
			}
			$attr=array();
			foreach($data['attr_name'] as $key=>$val){
				$data['attr_value'][$key]=str_replace('，',',',$data['attr_value'][$key]);
				$attr[$val]=$data['attr_value'][$key];
			}
			$attr=json_encode($attr,JSON_UNESCAPED_UNICODE);
			$data['attr']=$attr;

			$data ['images'] = json_encode ( $data ['images'] );
//			dump($data);die;
			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();
				update_sortpath_content ( $tblname, 'category_classes', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '修改成功！',U('Content/classes') );
			} else {
				if (isN ( $data ['keywords'] )) {
					$data ['keywords'] = $data ['title'];
				}
				if (isN ( $data ['description'] )) {
					$data ['description'] = cut_str ( htmlclr ( $data ['content'] ), 0, 200 );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id
				) )->setField ( 'sort', $id );
				update_sortpath_content ( $tblname, 'category_classes', $id, $data ['pid'] );
				$this->success ( '恭喜，' . $name . '添加成功！',U('Content/classes')  );
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

			// 已有分类列表
			$list=get_cache_list('category_classes');
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );

			//教师列表
			$teacher=get_cache_list('category_teacher');
			$this->assign ( "teacher", $teacher );

			//单位列表
			$company=get_cache_list('category_company');
			$this->assign ( "company", $company );


			$control = 'Content';
			$action = 'classes';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除课程
	 *
	 * @param number $id
	 */
	public function deleteclasses($id = 0) {
		$tblname = 'content_classes';
		$name = '课程';
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
	 * 签到课程列表
	 */
	public function signup($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['title'] = array (
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
		}


		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}


		// 表名
		$tblname = 'content_signup';
		$name = '签到课程';
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
		$control = 'Content';
		$action = 'signup';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign ( "pid", $pid );

		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '课程列表' );
		$this->display ();
	}

	/**
	 * 添加签到
	 */
	public function addsignup() {
		$this->editsignup ();
	}
	/**
	 * 添加修改课程签到
	 *
	 * @param number $id
	 */
	public function editsignup($id = 0) {
		$tblname = 'content_signup';
		$name = '签到课程';
		$tplname = 'editsignup';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}

			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();
				$this->success ( '恭喜，' . $name . '修改成功！',U('Content/signup') );
			} else {
				if (isN ( $data ['keywords'] )) {
					$data ['keywords'] = $data ['title'];
				}
				if (isN ( $data ['description'] )) {
					$data ['description'] = cut_str ( htmlclr ( $data ['content'] ), 0, 200 );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id
				) )->setField ( 'sort', $id );
				$this->success ( '恭喜，' . $name . '添加成功！',U('Content/signup')  );
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


			$control = 'Content';
			$action = 'signup';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除课程签到
	 *
	 * @param number $id
	 */
	public function deletesignup($id = 0) {
		$tblname = 'content_signup';
		$name = '签到课程';
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
	 * 获取签到课程二维码
	 */
	public function getqrcode(){

		$id=$_POST['id'];
		$result=array();
		$iimg='./Public/uploadfile/classcode/class_id_'.$id.'.jpg';
		if(file_exists($iimg)){
			$result['status']=0;
			$result['info']='该课程已生成二维码';
			$this->ajaxReturn($result);
		}


		Vendor('phpqrcode');
		//生成二维码图片
		$object = new \QRcode();
		$qrcode_path='';
		$file_tmp_name='';
		$errors=array();
		if(!empty($_POST)){
			$url="http://".get_base_domain()."/Classes/signup/id/".$id.".html";
			$qrcode_bas_path='Public/uploadfile/classcode/';
			if(!is_dir($qrcode_bas_path)){
				mkdir($qrcode_bas_path, 0777, true);
			}

			$uniqid_rand="class_id_";
			$qrcode_path_new=$qrcode_bas_path.$uniqid_rand.$id.'.jpg';//二维码图片路径

			$errorCorrectionLevel = 'M';//容错级别
			$matrixPointSize = 10;//生成图片大小
			$matrixMarginSize = 2;//边距大小

			//生成二维码图片
			$object::png($url,$qrcode_path_new,$errorCorrectionLevel,$matrixPointSize,$matrixMarginSize);

			$QR = $qrcode_path_new;//已经生成的原始二维码图
			$logo = $qrcode_path;//准备好的logo图片
			if (file_exists($logo)) {
				$QR = imagecreatefromstring(file_get_contents($QR));
				$logo = imagecreatefromstring(file_get_contents($logo));
				$QR_width = imagesx($QR);//二维码图片宽度
				$QR_height = imagesy($QR);//二维码图片高度
				$logo_width = imagesx($logo);//logo图片宽度
				$logo_height = imagesy($logo);//logo图片高度
				$logo_qr_width = $QR_width / 5;
				$scale = $logo_width/$logo_qr_width;
				$logo_qr_height = $logo_height/$scale;
				$from_width = ($QR_width - $logo_qr_width) / 2;
				//重新组合图片并调整大小
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
						$logo_qr_height, $logo_width, $logo_height);
				//输出图片
				//header("Content-type: image/png");
				imagepng($QR,$qrcode_path);
				imagedestroy($QR);
			}else{
				$qrcode_path=$qrcode_path_new;
			}

		}

		if($qrcode_path){
			$set=M('content_signup')->where(array('id'=>$id))->setField('ewm','/'.$qrcode_path);
		}else{
			$set=false;
		}
		if($set===false){
			$result['status']=0;
			$result['info']="生成二维码失败，请稍后再试。";
			$this->ajaxReturn($result);
		}

		$result['status']=1;
		$result['info']="生成二维码成功。";
		$this->ajaxReturn($result);

	}

	/**
	 * 签到记录
	 */
	public function signuprecord($searchtype = '', $keyword = '', $id = '', $p = 1) {
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['mm.userreal'] = array (
							'like',
							'%' . $keyword . '%'
					);
				}
				break;
		}

		$where['signupid']=$id;

		// 表名
		$tblname = 'content_signup_record as mcsr';
		$name = '课程签到记录';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$fields="mcsr.*,mcs.title as title,mcs.address as signaddress,mm.userreal as username,mm.nickname as nickname";
		$rs = M ( $tblname )
				->join('my_content_signup as mcs on mcsr.signupid=mcs.id')
				->join('my_member as mm on mcsr.memberid=mm.id')
				->field($fields)
				->where ( $where )->order ( 'id desc' )->page ( $p, $row );
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


		// 当前表名
		$control = 'Content';
		$action = 'signup';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		$this->assign('id',$id);

		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '课程签到记录' );
		$this->display ();
	}

	public function expsignuprecord($id){
		Vendor("PHPExcel.PHPExcel.Cell.DataType");

		$class=M('content_signup')->where(array('id'=>$id))->find();

		$xlsName = date('Ymd')."-导出".$class['title']."签到记录";

		$xlsCell = array(
				array('sequence', '序号',6),
				array('title', '课程'),
				array('signaddress', '签到地点'),
				array('nickname', '姓名'),
				array('addtime', '签到时间')
		);
		$where['signupid']=$id;
		// 表名
		$tblname = 'content_signup_record as mcsr';
		$fields="mcsr.*,mcs.title as title,mcs.address as signaddress,mm.userreal as username,mm.nickname as nickname";
		$rs = M ( $tblname )
				->join('my_content_signup as mcs on mcsr.signupid=mcs.id')
				->join('my_member as mm on mcsr.memberid=mm.id')
				->field($fields)
				->where ( $where )->order ( 'id desc' );
		$xlsData = $rs->select ();

		foreach ($xlsData as $k => $v) {
			$xlsData[$k]['sequence'] = $k +1;
			$xlsData[$k]['title'] = $v['title'];
			$xlsData[$k]['signaddress'] = $v['signaddress'];
			$xlsData[$k]['username'] = $v['nickname'];
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
	 * 优惠券列表
	 */
	public function coupon($searchtype = '', $keyword = '',$pid = '', $status = '', $timefrom = '', $timeto = '', $p = 1) {
		$where = array ();
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['no'] =   $keyword;
				}
				break;
			case '1' :
				if (is_number ( $keyword )) {
					$where ['memberid'] = $keyword;
				}
				break;
			case '2' :
				if (!isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%'
					);
				}
				break;
		}
	
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
		if (is_numeric ( $pid )) {
			$where ['pid'] = $pid;
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
		$tblname = 'coupon';
		$name = '优惠券';
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
		$control = 'Content';
		$action = 'Coupon';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );

		// 已有分类列表
		$list=get_cache_list('category_coupon');
		$list = list_to_tree ( $list );
		$this->assign ( "list", $list );
		$this->assign ( "pid", $pid );
		
		// 状态标识
		$this->assign ( "statuslist", array(0=>'未使用',1=>'已使用') );
	
		$this->assign ( "title", '优惠券列表' );
		$this->display ();
	}
	

	/**
	 * 添加
	 */
	public function addCoupon() {
		$this->editCoupon ();
	}
	
	/**
	 * 添加修改优惠券
	 *
	 * @param number $id
	 */
	public function editCoupon($id = 0) {
		$tblname = 'coupon';
		$name = '优惠券';
		$tplname = 'editCoupon';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			if (!is_number ( $data ['num'] )) {
				$this->error ( '对不起，请正确输入要生成的' . $name . '数量！' );
			}
			if (!is_numeric ( $data ['cost'] )) {
				$this->error ( '对不起，请正确输入消费金额！' );
			}
			if (!is_numeric ( $data ['amount'] )) {
				$this->error ( '对不起，请正确输入抵扣金额！' );
			}
			if ($id) {
				$this->error('对不起，不允许修改！');
// 				$db = M ( $tblname )->where ( array (
// 						'id' => $id
// 				) )->data ( $data )->save ();
// 				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				$id = create_coupon($data['pid'],$data['title'],$data['num'],$data['cost'],$data['amount']);
				if($id){
				$this->success ( '恭喜，' . $name . '生成成功！' );
				}else{
				$this->error('对不起，不允许修改！');
					
				}
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
				$this->assign ( 'title', '生成' . $name );
			}

			$control = 'Content';
			$action = 'coupon';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 已有分类列表
			$list=get_cache_list('category_coupon');
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	
	
	
	/**
	 * 删除优惠券
	 *
	 * @param number $id
	 */
	public function deleteCoupon($id = 0) {
		$tblname = 'coupon';
		$name = '优惠券';
		$find = M ( $tblname )->find ( $id );
		if ($find) {
			$delete=M ( $tblname )->where ( array (
					'id' => $id,
					'status'=>0
			) )->delete ();
			if($delete){
			$this->success ( '恭喜，' . $name . '已删除！' );
			}else{
				$this->error('对不起，无法删除！');
			}
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}



	/**
	 * 活动列表
	 */
	public function active($searchtype = '', $keyword = '', $status = '', $p = 1) {
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


		}

		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		// 表名
		$tblname = 'content_active';
		$name = '活动';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
	foreach($list as $key=>$val){
			if($val['type']==1){
				$nums=M('content_active_member')->where(array('activeid'=>$val['id'],'status'=>1))->count();
			}else{
				$nums=M('content_active_memberp')->where(array('activeid'=>$val['id'],'status'=>1))->count();
			}
			$list[$key]['nums']=$nums;
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


		// 当前表名
		$control = 'Content';
		$action = 'active';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );


		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '活动列表' );
		$this->display ();
	}

	/**
	 * 添加
	 */
	public function addactive() {
		$this->editactive ();
	}
	/**
	 * 添加修改单页
	 *
	 * @param number $id
	 */
	public function editactive($id = 0) {
		$tblname = 'content_active';
		$name = '活动';
		$tplname = 'editactive';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			if (isN ( $data ['activetime'] )) {
				$this->error ( '对不起，' . $name . '活动时间不能为空！' );
			}
			
			//if (isN ( $data ['content'] )) {
			//	$this->error ( '对不起，' . $name . '内容不能为空！' );
			//}
			$data ['images'] = json_encode ( $data ['images'] );
			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();

				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				if (isN ( $data ['keywords'] )) {
					$data ['keywords'] = $data ['title'];
				}
				if (isN ( $data ['description'] )) {
					$data ['description'] = cut_str ( htmlclr ( $data ['content'] ), 0, 200 );
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
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}



			$control = 'Content';
			$action = 'active';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除活动
	 *
	 * @param number $id
	 */
	public function deleteactive($id = 0) {
		$tblname = 'content_active';
		$name = '活动';
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
	 * 参赛选手列表
	 */
	public function activemember($id='',$searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
//		switch ($searchtype) {
//			case '0' :
//				if (! isN ( $keyword )) {
//					$where ['name'] = array (
//							'like',
//							'%' . $keyword . '%'
//					);
//				}
//				break;
//			case '1' :
//				if (! isN ( $keyword )) {
//					$where ['content'] = array (
//							'like',
//							'%' . $keyword . '%'
//					);
//				}
//				break;
//
//
//		}

//		if (is_numeric ( $status )) {
//			$where ['cam.status'] = $status;
//		}
		$where ['cam.status'] = 1;
		$where['activeid']=$id;
		$where['cam.name']=array('neq','');
		// 表名
		$tblname = 'content_active_member';
		$name = '活动报名';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$field='cam.*,ca.title';
		$rs = M ( $tblname.' as cam' )->join('my_content_active as ca on ca.id=cam.activeid')->where ( $where )->field($field)->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();


		$this->assign ( "contentlist", $list );
		$count = $rs->where ( $where )->count ();

		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}

//		$this->assign ( "keyword", $keyword );
//		$this->assign ( "searchtype", $searchtype );
//		$this->assign ( "status", $status );


		// 当前表名
		$control = 'Content';
		$action = 'activemember';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );


		// 状态标识
		$this->assign ( "statuslist", array(0=>'未支付',1=>'已支付') );

		$this->assign ( "title", '活动报名列表' );
		$this->display ();
	}


	public function deleteactivemember($id){
		$tblname = 'content_active_member';
		$name = '活动';
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
	
	
	public function videoques(){

		if(IS_POST){

			$data=$_POST;
			$question=array();
			foreach($data['attr_name'] as $k=>$v){
				$kk=$k+1;
				$question[$k]['ques']=$v;
				$question[$k]['answ']=$data['attr_value'.$kk];
			}

			$text=json_encode($question,JSON_UNESCAPED_UNICODE);

			$save=M('wechat_mp')->where(array('id'=>1))->setField('quesjson',$text);
			if($save===false){
				$this->error("保存失败");
			}
			$this->success('保存成功');
		}else{

			$json=M('wechat_mp')->where(array('id'=>1))->getField('quesjson');
			$this->assign('json',$json);

			$this->display();
		}


	}

	/**
	 * 亲子投票活动报名列表
	 */
	public function activememberp($id='',$searchtype = '', $keyword = '', $status = '', $p = 1) {
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


		}

		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		$where['activeid']=$id;

		// 表名
		$tblname = 'content_active_memberp';
		$name = '参赛选手';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$field='cam.*,ca.title';
		$rs = M ( $tblname.' as cam' )->join('my_content_active as ca on ca.id=cam.activeid')->where ( $where )->field($field)->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();

		for($i=0;$i<count($list);$i++){
			$list[$i]['photo']=explode(',',$list[$i]['photo']);
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


		// 当前表名
		$control = 'Content';
		$action = 'activememberp';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );


		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '活动报名列表' );
		$this->display ();
	}


	public function deleteactivememberp($id){
		$tblname = 'content_active_memberp';
		$name = '活动报名';
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
	 * 产品列表
	 */
	public function loan( $keyword = '', $status = '', $p = 1) {
		$where = array ();

		if (! isN ( $keyword )) {
			$where ['title'] = array (
					'like',
					'%' . $keyword . '%'
			);
		}

		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
		// 表名
		$tblname = 'loan_product';
		$name = '贷款产品';
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
		$this->assign ( "status", $status );

		// 当前表名
		$control = 'Content';
		$action = 'loan';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );

		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '贷款产品列表' );
		$this->display ();
	}

	/**
	 * 添加
	 */
	public function addloan() {
		$this->editloan ();
	}

	/**
	 * 添加修改产品
	 *
	 * @param number $id
	 */
	public function editloan($id = 0) {
		$tblname = 'loan_product';
		$name = '贷款产品';
		$tplname = 'editloan';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			if (isN ( $data ['interest'] )) {
				$this->error ( '对不起，' . $name . '利率不能为空！' );
			}
			if (isN ( $data ['days'] )) {
				$this->error ( '对不起，' . $name . '期限不能为空！' );
			}
			if (isN ( $data ['limitstart'] )) {
				$this->error ( '对不起，' . $name . '起始限额不能为空！' );
			}
			if (isN ( $data ['limitend'] )) {
				$this->error ( '对不起，' . $name . '最高限额不能为空！' );
			}


			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id
				) )->data ( $data )->save ();
				$this->success ( '恭喜，' . $name . '修改成功！',U('Content/loan') );
			} else {

				$id = M ( $tblname )->data ( $data )->add ();
				M ( $tblname )->where ( array (
						'id' => $id
				) )->setField ( 'sort', $id );
				$this->success ( '恭喜，' . $name . '添加成功！',U('Content/loan')  );
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}


			$control = 'Content';
			$action = 'loan';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}

	/**
	 * 删除产品
	 *
	 * @param number $id
	 */
	public function deleteloan($id = 0) {
		$tblname = 'content_product';
		$name = '产品';
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


}
?>