<?php

namespace Admin\Controller;

class WechatController extends BaseController {
	public function index() {
	}
	
	/**
	 * 公众号设置
	 */
	public function setting($update = 0) {
		if (IS_POST) {
			$data = $_POST;
			if ($data ['username']) {
				if (isN ( $data ['userpwd'] )) {
					$this->error ( '对不起，公众号密码不能为空！' );
				}
				$this->bindAccount ( $data ['username'], $data ['userpwd'] );
				exit ();
			}
			if (isN ( $data ['panel_name'] )) {
				$this->error ( '对不起，公众号名称不能为空！' );
			}
			if (isN ( $data ['panel_username'] )) {
				$this->error ( '对不起，公众号不能为空！' );
			}
			if (isN ( $data ['app_id'] )) {
				$this->error ( '对不起，App Id不能为空！' );
			}
			if (isN ( $data ['app_secret'] )) {
				$this->error ( '对不起，App Secret不能为空！' );
			}
			if (isN ( $data ['app_token'] )) {
				$this->error ( '对不起，token不能为空！' );
			}
			if (isN ( $data ['app_aeskey'] )) {
				$this->error ( '对不起，Aeskey不能为空！' );
			}
			if (isN ( $data ['app_url'] )) {
				$data ['app_url'] = 'http://'.get_base_domain().'/Wechat/';
			}
			$data ['panel_edition'] = 0; // 微信平台
			$data ['is_default'] = 1;
			$data ['status'] = 1;
			
			$find = M ( 'wechat_mp' )->field ( 'id' )->find ();
			if ($find) {
				$where = array ();
				$where ['id'] = $find ['id'];
				$db = M ( 'wechat_mp' )->data ( $data )->where ( $where )->save ();
				S ( 'WECHAT_MP_INFO',null );
			} else {
				$data ['addip'] = get_client_ip ();
				$db = M ( 'wechat_mp' )->data ( $data )->add ();
			}
			if ($db !== false) {
				$this->success ( '恭喜，公众号设置成功！' );
			} else {
				$this->error ( '对不起，公众号设置失败！' );
			}
		} else {
			$db = M ( 'wechat_mp' )->find ();
			if (! $db) {
				$update = 1;
			}
			$this->assign ( 'db', $db );
			$this->assign ( 'update', $update );
			
			$this->assign('name','公众号');
			$this->assign ( "title", '公众号设置' );
			$this->display ();
		}
	}
	
	
	/**
	 * 图文素材
	 */
	public function news() {
		$where = array ();
		$where ['type'] = 2;
		// 分页
		$p = intval ( I ( 'p' ) );
		$p = $p ? $p : 1;
		$row = 8; // C ( 'VAR_PAGESIZE' );
		
		$rs = M ( "wechat_material" )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		foreach ( $list as $k => $v ) {
			$list [$k] ['_child'] = M ( 'wechat_material_detail' )->where ( 'material_id=' . $v ['id'] )->order ( 'id asc' )->select ();
		}
		$this->assign ( "list", $list );
		
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		} else {
			$this->assign ( 'page', null );
		}
		
		$this->assign ( 'title', '图文素材' );
		$this->display ();
	}
	
	/**
	 * 单图文消息管理
	 *
	 * @param number $id        	
	 */
	public function single($id = 0) {
		if ($id == 0) {
			// 添加
			if (IS_POST) {
				$data = $_POST;
				if (isN ( $data ['title'] )) {
					$this->error ( '对不起，标题不能为空！' );
				}
				if (isN ( $data ['indexpic'] )) {
					$this->error ( '对不起，封面不能为空！' );
				}
				$material = array ();
				$material ['type'] = 2;
				// 先插入material主表
				$material_id = M ( 'wechat_material' )->data ( $material )->add ();
				
				$data ['material_id'] = $material_id;
				if (! $data ['iscomment']) {
					$data ['iscomment'] = 0;
				}
				// 取公众号名称
				$db = M ( 'wechat_material_detail' )->data ( $data )->add ();
				if ($db === false) {
					$this->error ( '对不起，保存失败！' );
				} else {
					// 日志记录
					$this->success ( '恭喜，添加成功！' );
				}
			}
			
			$this->assign ( 'title', '新建单图文消息' );
		} else {
			// 修改
			if (IS_POST) {
				$data = $_POST;
				$where = array ();
				$where ['id'] = $id;
				if (isN ( $data ['title'] )) {
					$this->error ( '对不起，标题不能为空！' );
				}
				if (isN ( $data ['indexpic'] )) {
					$this->error ( '对不起，封面不能为空！' );
				}
				if (! ($data ['isshowpic'])) {
					$data ['isshowpic'] = 0;
				}
				if (! ($data ['iscomment'])) {
					$data ['iscomment'] = 0;
				}
				if (! ($data ['url'])) {
					$data ['islink'] = 0;
				} else {
					$data ['islink'] = 1;
				}
				// 取公众号名称
				$db = M ( 'wechat_material_detail' )->where ( $where )->data ( $data )->save ();
				if ($db === false) {
					$this->error ( '对不起，保存失败！' );
				} else {
					// 日志记录
					$this->success ( '恭喜，保存成功！' );
				}
			} else {
				
				$where = array ();
				$where ['material_id'] = $id;
				$db = M ( 'wechat_material_detail' )->where ( $where )->find ();
				if ($db) {
					$this->assign ( 'db', $db );
				}
			}
			$this->assign ( 'title', '修改单图文消息' );
		}
		$this->assign ( 'id', $id );
		$this->display ();
	}
	
	/**
	 * 多图文消息管理
	 *
	 * @param number $id        	
	 */
	public function multi($id = 0) {
		if ($id == 0) {
			// 添加
			if (IS_POST) {
				$data = I ( 'post.data' );
				$data = empty ( $data ) ? $_POST : $data;
				if (count ( $data ) < 2) {
					$this->error ( '对不起，多图文消息至少要有2条！' );
				}
				$material = array ();
				$material ['type'] = 2;
				
				// 有一个标题为空则终止
				foreach ( $data as $k => $v ) {
					if (isN ( $v ['title'] )) {
						$this->error ( '对不起，标题不能为空，请补充！' );
					}
				}
				// 先插入material主表
				$material_id = M ( 'wechat_material' )->data ( $material )->add ();
				
				foreach ( $data as $k => $v ) {
					if (! isN ( $v ['title'] )) {
						unset ( $v ['id'] );
						$v ['material_id'] = $material_id;
						if ($v ['indexpic'] == '') {
							$v ['indexpic'] = C ( 'DEFAULT_NOPIC' );
						}
						if (! ($v ['isshowpic'])) {
							$v ['isshowpic'] = 0;
						}
						if (! ($v ['iscomment'])) {
							$v ['iscomment'] = 0;
						}
						if (! ($v ['url'])) {
							$v ['islink'] = 0;
						} else {
							$v ['islink'] = 1;
						}
						
						$v ['material_id'] = $material_id;

						$db = M ( 'wechat_material_detail' )->data ( $v )->add ();
					}
				}
				if ($db === false) {
					$this->error ( '对不起，保存失败！' );
				} else {
					// 日志记录
					$this->success ( '恭喜，添加成功！' );
				}
			}
			
			$this->assign ( 'title', '新建多图文消息' );
		} else {
			// 修改
			if (IS_POST) {
				$data = I ( 'post.data' );
				$data = empty ( $data ) ? $_POST : $data;
				$where = array ();
				$where ['material_id'] = $id;
				$ids_before = M ( 'wechat_material_detail' )->where ( $where )->getField ( 'id', true );
				
				$ids_after = array ();
				foreach ( $data as $k => $v ) {
					if (! isN ( $v ['title'] )) {
						
						$where = array ();
						$where ['material_id'] = $id;
						$where ['id'] = $v ['id'];
						if ($v ['indexpic'] == '') {
							$v ['indexpic'] = C ( 'DEFAULT_NOPIC' );
						}
						if (! ($v ['isshowpic'])) {
							$v ['isshowpic'] = 0;
						}
						if (! ($v ['iscomment'])) {
							$v ['iscomment'] = 0;
						}
						if (! ($v ['url'])) {
							$v ['islink'] = 0;
						} else {
							$v ['islink'] = 1;
						}
						$db = M ( 'wechat_material_detail' )->where ( $where )->find ();
						if ($db) {
							M ( 'wechat_material_detail' )->where ( $where )->data ( $v )->save ();
							$ids_after [] = $v ['id'];
						} else {
							$v ['material_id'] = $id;
							unset ( $v ['id'] );
							M ( 'wechat_material_detail' )->where ( $where )->data ( $v )->add ();
						}
					}
				}
				$ids_delete = array_diff ( $ids_before, $ids_after );
				if ($ids_delete) {
					$where = array ();
					$where ['material_id'] = $id;
					$where ['id'] = array (
							'in',
							$ids_delete 
					);
					$db = M ( 'wechat_material_detail' )->where ( $where )->delete ();
				}
				if ($db === false) {
					$this->error ( '对不起，保存失败！' );
				} else {
					// 日志记录
					$this->success ( '恭喜，保存成功！' );
				}
			} else {
				
				$where = array ();
				$where ['material_id'] = $id;
				$list = M ( 'wechat_material_detail' )->field ( 'addtime,updatetime', true )->where ( $where )->order ( 'id asc' )->select ();
				if ($list) {
					$newlist = array ();
					foreach ( $list as $key => $val ) {
						$newlist [$key] = $val;
						$newlist [$key] ["info"] = htmlspecialchars_decode ( $val ["info"] );
					}
					$this->assign ( 'list', $newlist );
				}
			}
			$this->assign ( 'title', '修改多图文消息' );
		}
		$this->assign ( 'id', $id );
		$this->display ();
	}
	
	/**
	 * 图片素材
	 */
	public function material() {
		$where = array ();
		
		// 分页
		$p = intval ( I ( 'p' ) );
		$p = $p ? $p : 1;
		$row = 12;
		
		$rs = M ( "file" )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		$this->assign ( "list", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		} else {
			$this->assign ( 'page', null );
		}
		
		$this->assign ( 'title', '图片素材' );
		$this->display ();
	}
	
	/**
	 *
	 * @param number $id:图片ID        	
	 * @param number $type:删除类型：0-图片，1-图文        	
	 */
	public function deleteMaterial($id = 0, $type = 0) {
		switch ($type) {
			case 0 :
				$db = M ( 'file' )->where ( 'id=' . $id )->find ();
				if ($db) {
					if (file_exists ( './' . $db ['fullpath'] )) {
						$ctrl = new \Org\Net\File ();
						$ctrl->unlinkDir ( './' . $db ['fullpath'] );
					}
					M ( 'file' )->where ( 'id=' . $id )->delete ();
					// 日志记录
					$this->success ( '恭喜，图片删除成功！' );
				} else {
					$this->error ( '对不起，图片不存在！' );
				}
				break;
			case 1 :
				$db = M ( 'wechat_material' )->where ( 'id=' . $id )->find ();
				if ($db) {
					
					M ( 'wechat_material' )->where ( 'id=' . $id )->delete ();
					M ( 'wechat_material_detail' )->where ( 'material_id=' . $id )->delete ();
					// 日志记录
					$this->success ( '恭喜，图文素材删除成功！' );
				} else {
					$this->error ( '对不起，图文素材不存在！' );
				}
				break;
		}
	}
	
	/**
	 * 关键词回复
	 */
	public function keyword($id = 0) {
		if (IS_POST) {
			$where = array ();
			$where ['keyword_rule_id'] = $id;
			$info = M ( "wechat_keyword_rule" )->field ( "name" )->where ( "id={$id}" )->find ();
			M ( 'wechat_keyword' )->where ( $where )->delete ();
			M ( 'wechat_keyword_reply' )->where ( $where )->delete ();
			M ( 'wechat_keyword_rule' )->where ( array (
					'id' => $id 
			) )->delete ();
			$this->success ( '规则已删除' );
			exit ();
		}
		$where = array ();
		
		// 分页
		$p = intval ( I ( 'p' ) );
		$p = $p ? $p : 1;
		$row = 12;
		
		$rs = M ( "wechat_keyword_rule" )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		foreach ( $list as $k => $v ) {
			$where1 = array ();
			$where1 ['keyword_rule_id'] = $v ['id'];
			$keywords = M ( 'wechat_keyword' )->where ( $where1 )->order ( 'id asc' )->getField ( 'title', true );
			$list [$k] ['keywords'] = arr2str ( $keywords );
		}
		$this->assign ( "list", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		} else {
			$this->assign ( 'page', null );
		}
		

		$control = 'Wechat';
		$action = 'Keyword';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		
		$this->assign ( 'title', '关键词列表' );
		$this->display ();
	}
	/**
	 * 添加
	 */
	public function addKeyword() {
		$this->editKeyword ();
	}
	/**
	 * 添加修改关键词规则
	 *
	 * @param number $id        	
	 */
	public function editKeyword($id = 0) {
		if (IS_POST) {
			$data = $_POST;
			// 修改
			if (! $data ['name']) {
				$this->error ( '对不起，规则名称不能为空！' );
			}
			$hasKeyword = false;
			foreach ( $data ['keywords'] ['hidden'] as $k => $v ) {
				$hasKeyword = true;
				$v1 = str2arr ( $v, '__' );
				$data ['keywords'] [] = array (
						'title' => $v1 [0],
						'isfull' => $v1 [1] 
				);
			}
			unset ( $data ['keywords'] ['title'], $data ['keywords'] ['hidden'] );
			if (! $hasKeyword) {
				$this->error ( '对不起，至少要一个关键词！' );
			}
			
			$hasReply = false;
			foreach ( $data ['reply_info'] as $k => $v ) {
				if (! isN ( $v )) {
					$hasReply = true;
				}
				$data ['info'] [] = array (
						'type' => $data ['reply_type'] [$k],
						'info' => $data ['reply_info'] [$k] 
				);
			}
			if (count ( $data ['info'] ) > 5) {
				$this->error ( '对不起，最多只能添加5条回复！' );
			}
			if (! $hasReply) {
				$this->error ( '对不起，至少要一个回复！' );
			}
			// 1.更新rule信息
			$rule = array ();
			$rule ['name'] = $data ['name'];
			foreach ( $data ['keywords'] as $k => $v ) {
				$rule ['title'] [] = $v ['title'];
			}
			$rule ['title'] = serialize ( $rule ['title'] );
			
			for($i = 0; $i < 6; $i ++) {
				$rule ['num'] [$i] = ( int ) $this->sum_type ( $i, $data ['info'] );
			}
			$rule ['num'] = serialize ( $rule ['num'] );
			
			if ($data ['id']) {
				$where = array ();
				$where ['id'] = $data ['id'];
				M ( 'wechat_keyword_rule' )->where ( $where )->data ( $rule )->save ();
				$ruleid = $data ['id'];
				$isadd = false;
			} else {
				$ruleid = M ( 'wechat_keyword_rule' )->data ( $rule )->add ();
				$isadd = $ruleid;
			}
			
			// 2.更新keyword和keyword_reply（先删除）
			$where = array ();
			$where ['keyword_rule_id'] = $ruleid;
			M ( 'wechat_keyword' )->where ( $where )->delete ();
			M ( 'wechat_keyword_reply' )->where ( $where )->delete ();
			// 增加
			foreach ( $data ['keywords'] as $k => $v ) {
				$keyword = array ();
				$keyword ['title'] = $v ['title'];
				$keyword ['isfull'] = $v ['isfull'];
				$keyword ['isall'] = $data ['isall'];
				$keyword ['ismenu'] = 0;
				$keyword ['keyword_rule_id'] = $ruleid;
				$keyid = M ( 'wechat_keyword' )->data ( $keyword )->add ();
				if ($keyid) {
					$keyids [] = $keyid;
				}
			}
			if ($keyids) {
				foreach ( $data ['info'] as $k => $v ) {
					$reply = array ();
					$reply ['keyword_rule_id'] = $ruleid;
					$reply ['keyword_id'] = $keyids [0];
					$reply ['type'] = $v ['type'];
					$reply ['info'] = $v ['info'];
					$reply ['status'] = 1;
					$reply ['keyword_ids'] = ',' . arr2str ( $keyids ) . ',';
					$replyid = M ( 'wechat_keyword_reply' )->data ( $reply )->add ();
				}
			}
			if ($isadd) {
				$this->success ( $isadd );
			} else {
				$this->success ( '恭喜，保存成功！' );
			}
		} else {
			// 关键词信息
			$where1 = array ();
			$where1 ['keyword_rule_id'] = $id;
			$keywords = M ( 'wechat_keyword' )->where ( $where1 )->order ( 'id asc' )->select ();
			$this->assign ( 'keywords', $keywords );
			
			// 规则信息
			$where = array ();
			$where ['id'] = $id;
			$rule = M ( 'wechat_keyword_rule' )->where ( $where )->find ();
			if ($keywords) {
				$rule ['keywords'] = arr2str ( $keywords );
			}
			$this->assign ( 'db', $rule );
			
			// 回复信息
			$where2 = array ();
			$where2 ['keyword_rule_id'] = $id;
			$reply = M ( 'wechat_keyword_reply' )->where ( $where2 )->order ( 'id asc' )->select ();
			foreach ( $reply as $k => $v ) {
				if ($v ['type'] == 2) {
					// 图文需要读取详细
					$list = M ( 'wechat_material_detail' )->where ( array (
							'material_id' => $v ['info'] 
					) )->select ();
					$reply [$k] ['detail'] = $list;
				}
			}
			$this->assign ( 'reply', $reply );
			
			if ($id) {
				$this->assign ( 'title', '修改规则' );
			} else {
				$this->assign ( 'title', '添加规则' );
			}

			$control = 'Wechat';
			$action = 'Keyword';
			$name='规则';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			$this->assign ( "name", $name );
			
			$this->display ('editKeyword');
		}
	}


	/**
	 * 删除规则
	 *
	 * @param number $id
	 */
	public function deleteKeyword($id = 0) {
		$tblname = 'wechat_keyword_rule';
		$name = '规则';
		$find = M ( $tblname )->find ( $id );
		if ($find) {


			$title=unserialize($find['title']);

			$keyword=M('wechat_keyword')->where(array('title'=>$title[0]))->find();

			M('wechat_keyword')->where(array('title'=>$title[0]))->delete();

			M('wechat_keyword_reply')->where(array('keyword_id'=>$keyword['id']))->delete();

			M ( $tblname )->where ( array (
					'id' => $id
			) )->delete ();
			$this->success ( '恭喜，' . $name . '已删除！' );
		} else {
			$this->error ( '对不起，' . $name . '不存在！' );
		}
	}


	public function sum_type($type = 0, $arr = null) {
		$num = 0;
		foreach ( $arr as $k => $v ) {
			if ($v ['type'] == $type) {
				$num ++;
			}
		}
		return $num;
	}
	
	/**
	 * 素材选择框
	 *
	 * @param string $type        	
	 * @param string $val        	
	 */
	public function modal($type = 'text', $val = '', $p = 1) {
		switch ($type) {
			case 'text' :
				break;
			case 'image' :
				
				// 图片
				$where = array ();
				
				// 分页
				$row = 12;
				
				$rs = M ( "file" )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
				$list = $rs->select ();
				$this->assign ( "list", $list );
				$count = $rs->where ( $where )->count ();
				
				if ($count > $row) {
					$page = new \Think\AjaxPage ( $count, $row, '$.getMatrialImageList' );
					$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%' );
					$this->assign ( 'page', $page->AjaxShow () );
				} else {
					$this->assign ( 'page', null );
				}
				break;
			case 'news' :
				
				// 图文
				$where = array ();
				$where ['type'] = 2;
				// 分页
				$row = 3; // C ( 'VAR_PAGESIZE' );
				
				$rs = M ( "wechat_material" )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
				$list = $rs->select ();
				foreach ( $list as $k => $v ) {
					$list [$k] ['_child'] = M ( 'wechat_material_detail' )->where ( 'material_id=' . $v ['id'] )->order ( 'id asc' )->select ();
				}
				$this->assign ( "list", $list );
				
				$count = $rs->where ( $where )->count ();
				
				if ($count > $row) {
					$page = new \Think\AjaxPage ( $count, $row, '$.getMatrialNewsList' );
					$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%' );
					$this->assign ( 'page', $page->AjaxShow () );
				} else {
					$this->assign ( 'page', null );
				}
				break;
			case 'voice' :
				break;
			case 'video' :
				break;
		}
		$this->assign ( 'type', $type );
		$this->assign ( 'val', $val );
		$this->display ();
	}
	
	/**
	 * 本地保存菜单
	 */
	public function menu() {
		if (IS_POST) {
			
			$sub1 = array ();
			$sub2 = array ();
			$sub3 = array ();
			
			$data = empty ( $data ) ? $_POST : $data;
			$items = '';
			$items = implode ( $data, ',' );
			$data ['submenu1'] = false;
			$data ['submenu2'] = false;
			$data ['submenu3'] = false;
			// 菜单1
			for($i = 1; $i < 19; $i ++) {
				$data ['menu1'] [] = $data ['item1_' . $i];
			}
			for($i = 3; $i < 19; $i ++) {
				if (isN ( $data ['menu1'] [$i] ) == false) {
					$data ['submenu1'] = true;
					break;
				}
			}
			if ($data ['submenu1']) {
				for($i = 3; $i < 19; $i += 3) {
					if (isN ( $data ['menu1'] [$i + 1] ) && isN ( $data ['menu1'] [$i + 2] )) {
					} else {
						if (isN ( $data ['menu1'] [$i + 1] ) == false) {
							$data ['menu1'] [$i + 2] = '';
							$subs1 [] = array (
									'type' => 'click',
									'name' => $data ['menu1'] [$i],
									'key' => $data ['menu1'] [$i + 1] 
							);
						} else {
							$subs1 [] = array (
									'type' => 'view',
									'name' => $data ['menu1'] [$i],
									'url' => $data ['menu1'] [$i + 2] 
							);
						}
					}
				}
				
				$sub1 = array (
						'name' => $data ['menu1'] [0],
						'sub_button' => $subs1 
				);
			} else {
				if (isN ( $data ['menu1'] [1] ) == false) {
					$sub1 = array (
							'type' => 'click',
							'name' => $data ['menu1'] [0],
							'key' => $data ['menu1'] [1] 
					);
				} else if (isN ( $data ['menu1'] [2] ) == false) {
					
					$sub1 = array (
							'type' => 'view',
							'name' => $data ['menu1'] [0],
							'url' => $data ['menu1'] [2] 
					);
				}
			}
			// 菜单2
			for($i = 1; $i < 19; $i ++) {
				$data ['menu2'] [] = $data ['item2_' . $i];
			}
			for($i = 3; $i < 19; $i ++) {
				if (isN ( $data ['menu2'] [$i] ) == false) {
					$data ['submenu2'] = true;
					break;
				}
			}
			
			if ($data ['submenu2']) {
				for($i = 3; $i < 19; $i += 3) {
					if (isN ( $data ['menu2'] [$i + 1] ) && isN ( $data ['menu2'] [$i + 2] )) {
					} else {
						if (isN ( $data ['menu2'] [$i + 1] ) == false) {
							$data ['menu2'] [$i + 2] = '';
							$subs2 [] = array (
									'type' => 'click',
									'name' => $data ['menu2'] [$i],
									'key' => $data ['menu2'] [$i + 1] 
							);
						} else {
							$subs2 [] = array (
									'type' => 'view',
									'name' => $data ['menu2'] [$i],
									'url' => $data ['menu2'] [$i + 2] 
							);
						}
					}
				}
				
				$sub2 = array (
						'name' => $data ['menu2'] [0],
						'sub_button' => $subs2 
				);
			} else {
				if (isN ( $data ['menu2'] [1] ) == false) {
					$sub2 = array (
							'type' => 'click',
							'name' => $data ['menu2'] [0],
							'key' => $data ['menu2'] [1] 
					);
				} else if (isN ( $data ['menu2'] [2] ) == false) {
					$sub2 = array (
							'type' => 'view',
							'name' => $data ['menu2'] [0],
							'url' => $data ['menu2'] [2] 
					);
				}
			}
			
			// 菜单3
			for($i = 1; $i < 19; $i ++) {
				$data ['menu3'] [] = $data ['item3_' . $i];
			}
			for($i = 3; $i < 19; $i ++) {
				if (isN ( $data ['menu3'] [$i] ) == false) {
					$data ['submenu3'] = true;
					break;
				}
			}
			
			if ($data ['submenu3']) {
				for($i = 3; $i < 19; $i += 3) {
					if (isN ( $data ['menu3'] [$i + 1] ) && isN ( $data ['menu3'] [$i + 2] )) {
					} else {
						if (isN ( $data ['menu3'] [$i + 1] ) == false) {
							$data ['menu3'] [$i + 2] = '';
							$subs3 [] = array (
									'type' => 'click',
									'name' => $data ['menu3'] [$i],
									'key' => $data ['menu3'] [$i + 1] 
							);
						} else {
							$subs3 [] = array (
									'type' => 'view',
									'name' => $data ['menu3'] [$i],
									'url' => $data ['menu3'] [$i + 2] 
							);
						}
					}
				}
				
				$sub3 = array (
						'name' => $data ['menu3'] [0],
						'sub_button' => $subs3 
				);
			} else {
				if (isN ( $data ['menu3'] [1] ) == false) {
					$sub3 = array (
							'type' => 'click',
							'name' => $data ['menu3'] [0],
							'key' => $data ['menu3'] [1] 
					);
				} else if (isN ( $data ['menu3'] [2] ) == false) {
					$sub3 = array (
							'type' => 'view',
							'name' => $data ['menu3'] [0],
							'url' => $data ['menu3'] [2] 
					);
				}
			}
			
			// 生成menu
			$menu = array ();
			if (count ( $sub1 ) > 0) {
				$menu ['button'] [] = $sub1;
			}
			if (count ( $sub2 ) > 0) {
				$menu ['button'] [] = $sub2;
			}
			if (count ( $sub3 ) > 0) {
				$menu ['button'] [] = $sub3;
			}
			
			$menu = json_encode ( $menu, 256 );
			$data = array (
					'menu' => $menu,
					'items' => $items 
			);
			$find = M ( 'wechat_setting' )->getField ( 'id' );
			if ($find) {
				$db = M ( 'wechat_setting' )->data ( $data )->where ( 'id=' . $find )->save ();
			} else {
				
				$db = M ( 'wechat_setting' )->data ( $data )->add ();
			}
			if ($db !== false) {
				$this->success ( '菜单更新成功！' );
			} else {
				$this->error ( '菜单更新失败！' );
			}
		} else {
			$db = M ( 'wechat_setting' )->field ( 'items' )->find ();
			$data = str2arr ( $db ['items'] );
			$this->assign ( 'db', $data );
			$this->assign('title','自定义菜单');
			$this->display ();
		}
	}
	
	// 设置菜单
	public function setMenu($menu = null) {
		$menu = M ( 'wechat_setting' )->getField ( 'menu' );
		$wechat = get_wechat_obj ();
		$menu = (json_decode ( $menu, true ));
		$ret = $wechat->createMenu ( $menu );
		if($ret){
		$this->success ( '菜单提交成功！' );
		}else{
			$this->error('更新失败：'.$wechat->errMsg);
		}
	}
	
	// 获取菜单
	public function getMenu() {
		$wechat = get_wechat_obj ();
		$menu = $wechat->getMenu ();
		if ($menu) {
			$menu1 = $menu ['menu'] ['button'];
			foreach ( $menu1 as $k => $v ) {
				
				if ($menu1 [$k] ['type'] == 'click') {
					$menu1 [$k] ['key'] = $this->getMenuKeyword ( $menu1 [$k] ['key'] );
				}
				if (count ( $v ['sub_button'] )) {
					foreach ( $v ['sub_button'] as $k1 => $v1 ) {
						if ($menu1 [$k] ['sub_button'] [$k1] ['type'] == 'click') {
							$menu1 [$k] ['sub_button'] [$k1] ['key'] = $this->getMenuKeyword ( $menu1 [$k] ['sub_button'] [$k1] ['key'] );
						}
					}
				}
			}
			$menu ['menu'] ['button'] = $menu1;
		}
		if ($menu ['menu'] ['button'] == false) {
			$menu ['menu'] ['button'] = array ();
		}
		$this->success ( $menu );
	}
	public function getMenuKeyword($key) {
		return $key;
		$where = array ();
		$where ['title'] = $key;
		$where ['ismenu'] = 1;
		$id = M ( 'wechat_keyword' )->where ( $where )->getField ( 'id' );
		if ($id) {
			$db = M ( 'wechat_keyword_reply' )->where ( 'keyword_id=' . $id )->find ();
			return $db ['type'] . '_' . $db ['info'];
		} else {
			return false;
		}
	}
}
?>