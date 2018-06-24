<?php

namespace Admin\Controller;

class SettingController extends BaseController {
	public function index($sys = null) {
	}
	
	/**
	 * 无限级分类
	 *
	 * @param string $tree        	
	 */
	public function treeselect($tree = null) {
		$this->assign ( 'tree', $tree );
		$this->display ( 'Setting/treeselect' );
	}
	public function treelist($tree = null) {
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		$this->assign ( 'tree', $tree );
		$this->display ( 'Setting/treelist' );
	}
	
	/**
	 * 资讯分类列表
	 */
	public function news($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%' 
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%' 
					);
					$isSearch = true;
				}
				break;
		}
		
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
		
		// 表名
		$tplname = 'common';
		$tblname = 'category_news';
		$name = '资讯分类';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		
		// 当前表名
		$control = 'Setting';
		$action = 'News';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name ); 
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign ( "title", '资讯分类列表' );
		$this->display ($tplname);
	}
	
	/**
	 * 添加
	 */
	public function addNews() {
		$this->editNews ();
	}
	/**
	 * 添加修改资讯分类
	 *
	 * @param number $id        	
	 */
	public function editNews($id = 0) {
		$tblname = 'category_news';
		$name = '资讯分类';
		$tplname = 'editCommon';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id 
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}
			
			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
			$act="News";
			$this->assign('act',$act);
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除资讯分类
	 *
	 * @param number $id        	
	 */
	 
	public function deleteNews($id = 0) {
		$tblname = 'category_news';
		$name = '资讯分类';
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
	 * 课程分类列表
	 */
	public function classes($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
		}

		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		// 表名
		$tplname = 'common';
		$tblname = 'category_classes';
		$name = '课程分类';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );

		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );

		// 当前表名
		$control = 'Setting';
		$action = 'classes';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '课程分类列表' );
		$this->display ($tplname);
	}

	/**
	 * 添加
	 */
	public function addclasses() {
		$this->editclasses ();
	}
	/**
	 * 添加修改课程分类
	 *
	 * @param number $id
	 */
	public function editclasses($id = 0) {
		$tblname = 'category_classes';
		$name = '课程分类';
		$tplname = 'editCommon';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}

			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );

			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除资讯分类
	 *
	 * @param number $id
	 */
	public function deleteclasses($id = 0) {
		$tblname = 'category_classes';
		$name = '课程分类';
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
	 * 教师列表
	 */
	public function teacher($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
		}

		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		// 表名
		$tplname = 'common';
		$tblname = 'category_teacher';
		$name = '教师';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );

		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );

		// 当前表名
		$control = 'Setting';
		$action = 'teacher';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '教师列表' );
		$this->display ();
	}

	/**
	 * 添加
	 */
	public function addteacher() {
		$this->editteacher ();
	}
	/**
	 * 添加修改教师
	 *
	 * @param number $id
	 */
	public function editteacher($id = 0) {
		$tblname = 'category_teacher';
		$name = '教师';
		$tplname = 'editteacher';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}

			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );

			$this->assign ( "name", $name );
			$this->display ($tplname);
		}
	}
	/**
	 * 删除教师
	 *
	 * @param number $id
	 */
	public function deleteteacher($id = 0) {
		$tblname = 'category_teacher';
		$name = '教师';
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
	 * 单位列表
	 */
	public function company($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
		}

		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		// 表名
		$tplname = 'common';
		$tblname = 'category_company';
		$name = '单位';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );

		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );

		// 当前表名
		$control = 'Setting';
		$action = 'company';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );

		$this->assign ( "title", '单位列表' );
		$this->display ();
	}

	/**
	 * 添加
	 */
	public function addcompany() {
		$this->editcompany ();
	}
	/**
	 * 添加修改单位
	 *
	 * @param number $id
	 */
	public function editcompany($id = 0) {
		$tblname = 'category_company';
		$name = '单位';
		$tplname = 'editcompany';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}

			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );

			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );

			$this->assign ( "name", $name );
			$this->display ($tplname);
		}
	}
	/**
	 * 删除单位
	 *
	 * @param number $id
	 */
	public function deletecompany($id = 0) {
		$tblname = 'category_company';
		$name = '单位';
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
	 * 优惠券分类列表
	 */
	public function coupon($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
		}
	
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
	
		// 表名
		$tplname = 'common';
		$tblname = 'category_coupon';
		$name = '优惠券分类';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );
	
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
	
		// 当前表名
		$control = 'Setting';
		$action = 'Coupon';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
	
		$this->assign ( "title", '优惠券分类列表' );
		$this->display ($tplname);
	}
	
	/**
	 * 添加
	 */
	public function addCoupon() {
		$this->editCoupon ();
	}
	/**
	 * 添加修改优惠券分类
	 *
	 * @param number $id
	 */
	public function editCoupon($id = 0) {
		$tblname = 'category_coupon';
		$name = '优惠券分类';
		$tplname = 'editCommon';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}
	
			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
	
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
	
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除优惠券分类
	 *
	 * @param number $id
	 */
	public function deleteCoupon($id = 0) {
		$tblname = 'category_coupon';
		$name = '优惠券分类';
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
	 * 会员分类列表
	 */
	public function member($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
		}
	
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
	
		// 表名
		$tplname = 'member';
		$tblname = 'category_member';
		$name = '会员分类';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );
	
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
	
		// 当前表名
		$control = 'Setting';
		$action = 'Member';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
	
		$this->assign ( "title", '会员分类列表' );
		$this->display ($tplname);
	}
	
	/**
	 * 添加
	 */
	public function addMember() {
		$this->editMember ();
	}
	/**
	 * 添加修改会员分类
	 *
	 * @param number $id
	 */
	public function editMember($id = 0) {
		$tblname = 'category_member';
		$name = '会员分类';
		$tplname = 'editmember';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			
			$data['praviteper']=implode(',',$data['video']);
			unset($data['video']);
			
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}
	
			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
	
	
			//视频课程列表
			$video=M('content_classes')->where(array('status'=>1,'type'=>2))->select();
			$this->assign('video',$video);
	
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
	
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除会员分类
	 *
	 * @param number $id
	 */
	public function deleteMember($id = 0) {
		$tblname = 'category_member';
		$name = '会员分类';
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
	 * 产品分类列表
	 */
	public function product($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%'
					);
					$isSearch = true;
				}
				break;
		}
	
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
	
		// 表名
		$tplname = 'common';
		$tblname = 'category_product';
		$name = '产品分类';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );
	
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
	
		// 当前表名
		$control = 'Setting';
		$action = 'Product';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
	
		$this->assign ( "title", '产品分类列表' );
		$this->display ($tplname);
	}
	
	/**
	 * 添加
	 */
	public function addProduct() {
		$this->editProduct ();
	}
	/**
	 * 添加修改产品分类
	 *
	 * @param number $id
	 */
	public function editProduct($id = 0) {
		$tblname = 'category_product';
		$name = '产品分类';
		$tplname = 'editCommon';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}
				
			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
				
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
				
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除产品分类
	 *
	 * @param number $id
	 */
	public function deleteProduct($id = 0) {
		$tblname = 'category_product';
		$name = '产品分类';
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
	 * 单页分类列表
	 */
	public function info($searchtype = '', $keyword = '', $status = '', $p = 1) {
		$where = array ();
		$isSearch = false;
		switch ($searchtype) {
			case '0' :
				if (! isN ( $keyword )) {
					$where ['name'] = array (
							'like',
							'%' . $keyword . '%' 
					);
					$isSearch = true;
				}
				break;
			case '1' :
				if (! isN ( $keyword )) {
					$where ['remark'] = array (
							'like',
							'%' . $keyword . '%' 
					);
					$isSearch = true;
				}
				break;
		}
		
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
		
		// 表名
		$tplname = 'common';
		$tblname = 'category_info';
		$name = '单页分类';
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$list = M ( $tblname )->where ( $where )->order ( 'sort asc' )->select ();
		if (! $isSearch) {
			$list = list_to_tree ( $list );
		}
		$this->assign ( "list", $list );
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		
		// 当前表名
		$control = 'Setting';
		$action = 'Info';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name ); 
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign ( "title", '单页分类列表' );
		$this->display ('common');
	}
	
	/**
	 * 添加
	 */
	public function addInfo() {
		$this->editInfo ();
	}
	/**
	 * 添加修改单页分类
	 *
	 * @param number $id        	
	 */
	public function editInfo($id = 0) {
		$tblname = 'category_info';
		$name = '单页分类';
		$tplname = 'editCommon';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			$names = parse_config ( $data ['name'] );
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
						update_sortpath ( $tblname, $id, $data ['pid'], $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id 
					) )->data ( $data )->save ();
					update_sortpath ( $tblname, $id, $data ['pid'] );
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					update_sortpath ( $tblname, $id, $data ['pid'], $id );
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}
			
			// 已有分类列表
			$where = array ();
			$list = M ( $tblname )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除单页分类
	 *
	 * @param number $id        	
	 */
	public function deleteInfo($id = 0) {
		$tblname = 'category_info';
		$name = '单页分类';
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
	 * 省市县管理
	 *
	 * @param number $proid        	
	 * @param number $cityid        	
	 * @param number $districtid        	
	 */
	public function pcd($proid = 0, $cityid = 0, $districtid = 0, $streetid = 0) {
		$tblname = 'pcd';
		$type = 1;
		$fid = 0;
		$name = '区域';
		
		$where = array ();
		$where ['pid'] = 0;
		if ($districtid) {
			$where ['pid'] = $districtid;
			$fid = $districtid;
			$type = 4;
		} else if ($cityid) {
			$where ['pid'] = $cityid;
			$fid = $cityid;
			$type = 3;
		} else {
			if ($proid) {
				$where ['pid'] = $proid;
				$fid = $proid;
				$type = 2;
			}
		}
		$list = M ( $tblname )->where ( $where )->select ();
		$this->assign ( 'list', $list );
		$this->assign ( 'proid', $proid );
		$this->assign ( 'cityid', $cityid );
		$this->assign ( 'districtid', $districtid );
		$this->assign ( 'streetid', $streetid );
		$this->assign ( 'fid', $fid );
		
		// 取当前省市县名
		if ($proid) {
			$proname = get_area_name ( 'china_province', $proid );
			$this->assign ( 'proname', $proname );
		}
		if ($cityid) {
			$cityname = get_area_name ( 'china_city', $cityid );
			$this->assign ( 'cityname', $cityname );
		}
		if ($districtid) {
			$disname = get_area_name ( 'china_district', $districtid );
			$this->assign ( 'disname', $disname );
		}
		if ($streetid) {
			$streetname = get_area_name ( 'china_district', $streetid );
			$this->assign ( 'streetname', $streetname );
		}
		
		// 当前表名
		$this->assign ( "type", $type );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign ( 'title', '省市区管理' );
		$this->display ();
	}
	
	/**
	 * 添加
	 */
	public function addPcd() {
		$this->editPcd ();
	}
	/**
	 * 编辑省市县
	 *
	 * @param number $id        	
	 */
	public function editPcd($id = 0) {
		$tblname = 'pcd';
		$name = '区域';
		$tplname = 'editPcd';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			$data ['name'] = trim ( $data ['name'] );
			if (strpos ( $data ['name'], ' ' )) {
				$data ['name'] = str_replace ( ' ', "\r\n", $data ['name'] );
			}
			$names = parse_config ( $data ['name'] );
			$level = M ( $tblname )->where ( array (
					'id' => $id 
			) )->getField ( 'level' );
			$data ['level'] = ( int ) $level + 1;
			if (! (strstr ( $data ['code'], $data ['pid'] ) && strlen ( $data ['code'] ) > strlen ( $data ['pid'] ))) {
				$this->error ( '对不起，区域编号必须包含【' . $data ['pid'] . '】，且不能相同!' );
			}
			if (count ( $names ) > 1) {
				// 只可能添加的情况，修改不允许指定多个
				foreach ( $names as $k => $v ) {
					if (! isN ( $v )) {
						$data ['name'] = trim ( $v );
						$id = M ( $tblname )->data ( $data )->add ();
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			} else {
				if ($id) {
					$db = M ( $tblname )->where ( array (
							'id' => $id 
					) )->data ( $data )->save ();
					$this->success ( '恭喜，' . $name . '修改成功！' );
				} else {
					$id = M ( $tblname )->data ( $data )->add ();
					$this->success ( '恭喜，' . $name . '添加成功！' );
				}
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				$fid = I ( 'fid' );
				$db ['pid'] = ( int ) $fid;
				$this->assign ( 'db', $db );
				// 添加
				$this->assign ( 'title', '添加' . $name );
				// 添加的时候传递父ID参数
				$this->assign ( "pid", I ( 'pid' ) );
			}
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	
	/**
	 * 删除单页分类
	 *
	 * @param number $id        	
	 */
	public function deletePcd($id = 0) {
		$tblname = 'pcd';
		$name = '区域';
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