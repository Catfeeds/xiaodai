<?php

namespace Admin\Controller;

class RbacController extends BaseController {
	public function index() {
	}
	
	/**
	 * 批量操作
	 */
	public function batch($table, $id, $col, $v) {
		$cols = array (
				'sort',
				'status',
				'isresume',
				'__del__',
				'__sta__',
				'tag1',
				'tag2',
				'tag3',
				'tag4',
				'price',
				'chance',
				'sort1' 
		);
		if (in_array ( $col, $cols )) {
			switch ($col) {
				case '__del__' :
					if (substr ( $id, strlen ( $id ) - 1, 1 ) == ',') {
						$id = $id . '0';
					}
					$db = M ()->execute ( 'delete from `' . C ( 'DB_PREFIX' ) . $table . '`   where `id` in (' . $id . ') ' );
					
					break;
				case '__sta__' :
					if (substr ( $id, strlen ( $id ) - 1, 1 ) == ',') {
						$id = $id . '0';
					}
					
					$db = M ()->execute ( 'update `' . C ( 'DB_PREFIX' ) . $table . '`  set `status`=' . $v . '  where `id` in (' . $id . ') ' );
					
					break;
				default :
					
					$db = M ()->execute ( 'update `' . C ( 'DB_PREFIX' ) . $table . '` set `' . $col . '`=' . $v . ' where `id` in (' . $id . ') ' );
					
					break;
			}
			if ($db !== false) {
				$this->ajaxReturn ( Array (
						'status' => 1 
				) );
			} else {
				$this->ajaxReturn ( Array (
						'status' => 0 
				) );
			}
		} else {
			$this->ajaxReturn ( Array (
					'status' => 0 
			) );
		}
	}
	
	// 角色列表
	public function role() {
		$tblname = 'role';
		$list = M ( $tblname )->select ();
		$this->assign ( "list", $list );
		
		$control = 'Rbac';
		$action = 'Role';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		
		$this->assign ( 'title', '角色列表' );
		$this->display ();
	}
	
	/**
	 * 添加角色
	 */
	public function addRole() {
		$this->editRole ();
	}
	/**
	 * 添加修改角色
	 *
	 * @param number $id        	
	 */
	public function editRole($id = 0) {
		$tblname = 'role';
		$name = '角色';
		$tplname = 'editRole';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			if (isN ( $data ['remark'] )) {
				$this->error ( '对不起，' . $name . '描述不能为空！' );
			}
			if ($id) {
				$where = array ();
				$where ['id'] = $id;
				$db = M ( $tblname )->where ( $where )->data ( $data )->save ();
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				$where = array ();
				$where ['name'] = $data ['name'];
				$find = M ( $tblname )->where ( $where )->find ();
				if ($find) {
					$this->error ( '对不起，角色已存在！' );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				if ($id) {
					if (! $data ['sort']) {
						M ( $tblname )->where ( array (
								'id' => $id 
						) )->setField ( 'sort', $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
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
			}
			
			$control = 'Rbac';
			$action = 'Role';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			$this->assign ( "tblname", $tblname );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	
	// 删除角色
	public function deleteRole($id = 0) {
		$tblname = 'role';
		$name = '角色';
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
	
	// 节点处理
	
	// 节点列表
	public function node() {
		$tblname='node';
		$list = M ( $tblname )->order ( "sort asc" )->select ();
		$list = node_merge ( $list );
		$this->assign ( "list", $list );

		$control = 'Rbac';
		$action = 'Node';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		
		$this->assign('title','节点列表');
		$this->display ();
	}
	
	/**
	 * 添加节点
	 */
	public function addNode() {
		$this->editNode ();
	}
	/**
	 * 添加修改节点
	 *
	 * @param number $id
	 */
	public function editNode($id = 0) {
		$tblname = 'node';
		$name = '节点';
		$tplname = 'editNode';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '名不能为空！' );
			}
			if (isN ( $data ['title'] )) {
				$this->error ( '对不起，' . $name . '显示名称不能为空！' );
			}
			if ($id) {
	
				$where = array ();
				$where ['id'] = $id;
				$data['level']=M('node')->where(array('id'=>$data['pid']))->getField('level');
				$data['level']=intval($data['level'])+1;
				$db = M ( $tblname )->where ( $where )->data ( $data )->save ();
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
				$this->assign ( "pid", $db['pid'] );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );
	
			} else {
				$pid=I('pid');
				$level = I ( "level", 0 );
				$this->assign ( "pid", $pid );
				$this->assign ( "level", $level + 1 );
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}

			// 输出当前Node列表
			$list = M ( "node" )->where ( 'level in (1,2,3)' )->order ( "sort asc" )->select ();
			$list = node_merge ( $list );
			$this->assign ( "list", $list );
			
			
			$control = 'Rbac';
			$action = 'Node';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			$this->assign ( "tblname", $tblname );
				
				
			// 状态标识
			$statuslist=array(0=>'隐藏',1=>'显示');
			$this->assign ( "statuslist", $statuslist );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	
	// 删除节点
	public function deleteNode($id = 0) {
		$tblname = 'node';
		$name = '节点';
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
	
	// 用户处理
	
	// 用户列表
	public function user($status = '', $p = 1) {
		$where = array ();
		$where ['username'] = array (
				'neq',
				C ( 'ADMIN_AUTH_KEY' ) 
		);
		if (is_number ( $status )) {
			$where ['status'] = $status;
		}
		// 表名
		$tblname = 'user';
		$name = '用户';
		// 分页
		$row =  C ( 'VAR_PAGESIZE' );
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
		
		$this->assign ( "list", $list );
		$count = $rs->where ( $where )->count ();
		
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}
		
		
		
		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		$this->assign ( 'status', $status );
		
		$control = 'Rbac';
		$action = 'User';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		
		$this->assign ( 'title', '用户列表' );
		$this->display ();
	}
	
	/**
	 * 添加角色
	 */
	public function addUser() {
		$this->editUser ();
	}
	/**
	 * 添加修改角色
	 *
	 * @param number $id        	
	 */
	public function editUser($id = 0) {
		$tblname = 'user';
		$name = '用户';
		$tplname = 'editUser';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['username'] )) {
				$this->error ( '对不起，' . $name . '名称不能为空！' );
			}
			if ($id) {
				if (! isN ( $data ['userpwd'] )) {
					$data ['userpwd'] = md5 ( $data ['userpwd'] );
				}else{
					unset($data['userpwd']);
				}
				
				$where = array ();
				$where ['id'] = $id;
				$db = M ( $tblname )->where ( $where )->data ( $data )->save ();
				if ($db!==false) {
					foreach ( $data ["roleid"] as $v ) {
						$arr [] = array (
								'role_id' => $v,
								"user_id" => $id 
						);
					}
					
					M ( "role_user" )->where ( array (
							"user_id" => $id 
					) )->delete ();
					M ( "role_user" )->addAll ( $arr );
				}
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				if (isN ( $data ['userpwd'] )) {
					$this->error ( '对不起，' . $name . '密码不能为空！' );
				} else {
					$data ['userpwd'] = md5 ( $data ['userpwd'] );
				}
				$where = array ();
				$where ['username'] = $data ['username'];
				$find = M ( $tblname )->where ( $where )->find ();
				if ($find) {
					$this->error ( '对不起，用户已存在！' );
				}
				$id = M ( $tblname )->data ( $data )->add ();
				if ($id) {
					foreach ( $data ["roleid"] as $v ) {
						$arr [] = array (
								'role_id' => $v,
								"user_id" => $id 
						);
					}
					
					M ( "role_user" )->where ( array (
							"user_id" => $id 
					) )->delete ();
					M ( "role_user" )->addAll ( $arr );
					
					//设置排序
					if (! $data ['sort']) {
						M ( $tblname )->where ( array (
								'id' => $id
						) )->setField ( 'sort', $id );
					}
				}
				$this->success ( '恭喜，' . $name . '添加成功！' );
			}
			exit ();
		} else {
			if ($id) {
				// 修改
				$db = M ( $tblname )->find ( $id );
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name.'【'.$id.'】' );

				// 已有角色列表
					
				$rolenow = M ( "role_user" )->where ( array (
						"user_id" => $id
				) )->getField ( "role_id", true );
				// 输出角色列表
				$where1=array();
				$where1['id']=array('neq',8);
				$rolelist = M ( "role" )->where($where1)->select ();
				foreach ( $rolelist as $v ) {
					$selected = false;
					if (in_array ( $v ["id"], $rolenow )) {
						$selected = true;
					}
				
					$arr [] = array (
							"id" => $v ["id"],
							"name" => $v ["name"],
							"selected" => $selected
					);
				}
				$this->assign ( "rolelist", $arr );
			} else {

				// 输出角色列表
				$rolelist = M ( "role" )->select ();
				$this->assign ( "rolelist", $rolelist );
			
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}
			
			$control = 'Rbac';
			$action = 'User';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			$this->assign ( "tblname", $tblname );
			
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	
	// 删除角色
	public function deleteUser($id = 0) {
		$tblname = 'user';
		$name = '用户';
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
	
	// 授权
	public function access($id = 0) {
		if (IS_POST) {
			$db = M ( "access" );
			$db->where ( array (
					"role_id" => $id 
			) )->delete ();
			
			$arr = array ();
			$access = I ( "access" );
			$shop = I ( "shop" );
			$arracc = str2arr ( $access );
			
			$data ['shop'] = $shop;
			$db1 = M ( 'role' )->where ( 'id=' . $id )->save ( $data );
			
			foreach ( $arracc as $v ) {
				$tmp = explode ( "_", $v );
				if ($tmp [0] !== "") {
					$arr [] = array (
							"role_id" => $id,
							"node_id" => $tmp [0],
							"level" => $tmp [1] 
					);
				}
				;
			}
			
			if (false !== $db->addAll ( $arr )) {
				$this->success ( "授权成功！" );
			} else {
				$this->error ( "授权失败" );
			}
		} else {
			// 模块权限
			$fields = array (
					"id",
					"name",
					"title",
					"pid",
					"my_node.level"
			);
			
			$where = array ();
			if (empty ( $_SESSION [C ( 'ADMIN_AUTH_KEY' )] )) {
				$where ['super'] = 0;
			}
			if(session('adminname')!='administrator'){
				$where['my_access.role_id']=session('roleid');
				$nodelist = M ( "node" )->join('my_access on my_access.node_id = my_node.id')->field ( $fields )->where ( $where )->order ( "sort asc" )->select ();
			}else{
				$nodelist = M ( "node" )->field ( $fields )->where($where)->order ( "sort asc" )->select ();
			}
			$access = M ( "access" )->where ( array (
					"role_id" => $id 
			) )->getField ( "node_id", true );
			$nodelist = node_merge1 ( $nodelist, $access );
			$this->assign ( "nodelist", $nodelist );
			
			// 栏目权限
			$fields = array (
					"id",
					"name",
					"pid",
					"depth" 
			);
			
			/*
			 * $where=array();
			 * $channellist = M ( "channel" )->field ( $fields )->where($where)->order ( "sort asc" )->select ();
			 *
			 * $access = M ( "role" )->where ( 'id='.$id)->getField ( "channel" );
			 *
			 * $access=str2arr($access);
			 * $channellist = node_merge1 ( $channellist, $access );
			 *
			 * $this->assign ( "channellist", $channellist );
			 *
			 *
			 * //门店权限
			 * $fields = array (
			 * "id",
			 * "name",
			 * "pid",
			 * "depth"
			 * );
			 *
			 * $where=array();
			 * $shoplist = M ( "shop" )->field ( $fields )->where($where)->order ( "sort asc" )->select ();
			 */
			
			$access = M ( "role" )->where ( 'id=' . $id )->getField ( "shop" );
			
			$access = str2arr ( $access );
			$shoplist = node_merge1 ( $shoplist, $access );
			
			$this->assign ( "shoplist", $shoplist );
			
			$this->assign ( "id", $id );
			$this->assign ( 'title', '角色授权' );
			$this->display ();
		}
	}
	
	
		/**导出xls*/
	public function exportxls($act='',$timefrom='',$timeto='',$status='',$type='',$searchtype='',$keyword='',$pid='',$check=''){

		switch ($act){
			case 'balance':
				//导出Excel
				Vendor("PHPExcel.PHPExcel.Cell.DataType");
				$xlsName = date('Ymd')."-佣金统计";
				$xlsCell = array(
						array('sequence', '序号',6),
						array('username', '会员名',12),
						array('amount', '佣金'),
						array('point', '积分'),
				);
				$where = array ();
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

				// 表名
				$tblname = 'account_log';
				$fields="sum(amount) as amount,memberid";
				$rs = M ( $tblname )->where ( $where )->field($fields)->group('memberid')->order('amount desc');
				$list = $rs->select ();
				foreach($list as $key=>$val){
					$where['memberid']=$val['memberid'];
					$point=M('point_log')->where($where)->getField('sum(point)');
					$list[$key]['point']=$point;
					$username=M('member')->where(array('id'=>$val['memberid']))->getField('username');
					$list[$key]['username']=$username;
				}
//                dump($list);die;
				$xlsData = $list;
				foreach ($xlsData as $k => $v) {
					$xlsData[$k]['sequence'] = $k +1;
					$xlsData[$k]['username'] = $v['username'];
					$xlsData[$k]['amount'] = $v['amount'];
					$xlsData[$k]['point'] = $v['point'];
					unset($xlsData[$k]['memberid']);
				}
				$this->exportExcel($xlsName, $xlsCell, $xlsData);
				break;
			case 'order':
				Vendor("PHPExcel.PHPExcel.Cell.DataType");
				$xlsName = date('Ymd')."-订单统计";
				$xlsCell = array(
						array('sequence', '序号',6),
						array('orderno', '订单编号'),
						array('name', '订单名称'),
						array('amount', '订单金额'),
						array('status', '状态'),
				);
				$where = array ();
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
				if(!isN($status)){
					$where['status']=$status;
					$this->assign('status',$status);
				}
				$shopid=session('shopid');
				if($shopid){
					$where['shopid']=$shopid;
				}


				// 表名
				$tblname = 'order';
				$rs = M ( $tblname )->where ( $where )->order ( 'amount desc, id asc' );
				$list = $rs->select ();

//                dump($list);die;
				$xlsData = $list;
				foreach ($xlsData as $k => $v) {
					$xlsData[$k]['sequence'] = $k +1;
					$xlsData[$k]['orderno'] = $v['orderno'];
					$xlsData[$k]['name'] = $v['name'];
					$xlsData[$k]['amount'] = $v['amount'];
					switch($v['status']){
						case 0:
							$status='待付款';
							break;
						case 1:
							$status='待消费';
							break;
						case 2:
							$status='已消费';
							break;
						case 3:
							$status='待审退款';
							break;
						case 4:
							$status='已审退款';
							break;
						case 5:
							$status='拒绝退款';
							break;
						case 6:
							$status='取消订单';
							break;
						default:
							break;
					}
					$xlsData[$k]['status'] = $status;
				}
				$this->exportExcel($xlsName, $xlsCell, $xlsData);
				break;
				break;
			case 'member':
				//导出Excel
				Vendor("PHPExcel.PHPExcel.Cell.DataType");
				$xlsName = date('Ymd')."-会员统计";
				$xlsCell = array(
						array('sequence', '序号',6),
						array('username', '会员名',12),
						array('num', '购买量'),
						array('amount', '付款金额'),
				);
				$where = array ();


				if(!isN($timefrom)){
					$where['mo.addtime']=array('gt',$timefrom);
				}
				if(!isN($timeto)){
					$timeto = get_date_add ( $timeto, 1 );
					$where['mo.addtime']=array('lt',$timeto);
				}

				if(!isN($timefrom) && !isN($timeto)){
					$where['mo.addtime']=array('between',array($timefrom,$timeto));
				}


				// 表名
				$tblname = 'order as mo';
				$name = '会员';
				// 分页
				$row = C ( 'VAR_PAGESIZE' );
				$fields="sum(mo.amount) as amount,sum(mo.num) as num,mm.username";
				$rs = M ( $tblname )->join('my_member as mm on mm.id=mo.memberid')->where ( $where )->field($fields)->group('mo.memberid')->order('amount desc');
				$list = $rs->select ();

				$xlsData = $list;
				foreach ($xlsData as $k => $v) {
					$xlsData[$k]['sequence'] = $k +1;
					$xlsData[$k]['username'] = $v['username'];
					$xlsData[$k]['num'] = $v['num'];
					$xlsData[$k]['amount'] = $v['amount'];
				}
				$this->exportExcel($xlsName, $xlsCell, $xlsData);
				break;
			case 'product':
				//导出Excel
				Vendor("PHPExcel.PHPExcel.Cell.DataType");
				$xlsName = date('Ymd')."-单品统计";
				$xlsCell = array(
						array('sequence', '序号',6),
						array('title', '产品名称',12),
						array('num', '销售量'),
						array('amount', '销售额'),
				);
				$where = array ();


				if(!isN($timefrom)){
					$where['mo.addtime']=array('gt',$timefrom);
				}
				if(!isN($timeto)){
					$timeto = get_date_add ( $timeto, 1 );
					$where['mo.addtime']=array('lt',$timeto);
				}

				if(!isN($timefrom) && !isN($timeto)){
					$where['mo.addtime']=array('between',array($timefrom,$timeto));
				}
				$tblname = 'order_detail as mo';
				$fields="sum(mo.num) as num,mcp.title,mcp.price";
				$rs = M ( $tblname )->join('my_content_product as mcp on mcp.id=mo.productid')->where ( $where )->field($fields)->group('mo.productid');
				$list = $rs->select ();

				foreach($list as $key=>$val){
					$list[$key]['amount']=$val['num']*$val['price'];
				}

				$list=list_sort_by($list,'amount','desc');

				$xlsData = $list;
				foreach ($xlsData as $k => $v) {
					$xlsData[$k]['sequence'] = $k +1;
					$xlsData[$k]['title'] = $v['title'];
					$xlsData[$k]['num'] = $v['num'];
					$xlsData[$k]['amount'] = $v['amount'];
				}
				$this->exportExcel($xlsName, $xlsCell, $xlsData);
				break;
			case 'memberlist':

				//导出Excel
				Vendor("PHPExcel.PHPExcel.Cell.DataType");
				$xlsName = date('Ymd')."-会员列表";
				$xlsCell = array(
						array('sequence', '序号',6),
						array('invitecode', '邀请码'),
						array('username', '用户名'),
						array('fid', '推荐人'),
						array('balance', '推广佣金'),
						array('point', '当前积分'),
						array('telephone', '联系电话'),
						array('check', '是否审核'),
						array('status', '状态'),
				);
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
							$where ['nickname'] = array (
									'like',
									'%' . $keyword . '%'
							);
						}
						break;
				}

				if (is_numeric ( $pid )) {
					$where ['pid'] = $pid;
				}
				if (is_numeric ( $status )) {
					$where ['status'] = $status;
				}
				if(is_numeric( $check )){
					$where ['check'] = $check;
				}
				$where['reg']=1;
				// 表名
				$tblname = 'member';
				$rs = M ( $tblname )->where ( $where )->order ( 'addtime desc, id desc' );
				$list = $rs->select ();

				$xlsData = $list;
				foreach ($xlsData as $k => $v) {
					$xlsData[$k]['sequence'] = $k +1;
					$xlsData[$k]['invitecode'] = $v['invitecode'];
					$xlsData[$k]['username'] = $v['username']?$v['username']:$v['nickname'];
					$xlsData[$k]['fid'] = get_cache_value('member',$v['fid'],'username');
					$xlsData[$k]['balance'] = $v['balance'];
					$xlsData[$k]['point'] = $v['point'];
					$xlsData[$k]['telephone'] = $v['telephone'];
					$xlsData[$k]['check'] = $v['check']?'已审核':'未审核';
					$xlsData[$k]['status'] = $v['status']?'启用':'禁用';
				}
				$this->exportExcel($xlsName, $xlsCell, $xlsData);
				break;

			case 'orderlist':
				//导出Excel
				Vendor("PHPExcel.PHPExcel.Cell.DataType");
				$xlsName = date('Ymd')."-订单列表";
				$xlsCell = array(
						array('sequence', '序号',6),
						array('orderno', '订单号'),
						array('type', '订单类型'),
						array('nickname', '用户名'),
						array('name', '订单名称'),
						array('addtime', '下单时间'),
						array('username', '收货姓名'),
						array('telephone', '联系电话'),
						array('status', '状态'),
				);
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
					case '3' :
						if (! isN ( $keyword )) {
							$where ['memberid'] = $keyword;
						}
						break;
				}

				if ( $status ) {
					$where ['status'] = $status;
				}

				// 表名
				$tblname = 'order';
				$rs = M ( $tblname )->where ( $where )->order ( 'id desc' );
				$list = $rs->select ();
				

				$xlsData = $list;
				foreach ($xlsData as $k => $v) {
					$xlsData[$k]['sequence'] = $k +1;
					$xlsData[$k]['orderno'] = $v['orderno'];
					$xlsData[$k]['type'] = $v['type']==1?'交易订单':'积分订单';
					$xlsData[$k]['nickname'] = $v['nickname']?$v['nickname']:get_cache_value('member',$v['memberid'],'username');
					$xlsData[$k]['name'] =$v['name'];
					
					$xlsData[$k]['addtime'] = $v['addtime'];
					$xlsData[$k]['username'] = $v['username'];
					$xlsData[$k]['telephone'] = $v['telephone'];
					switch($v['status']){
						case 0:
							$status='待付款';
							break;
						case 1:
							$status='待发货';
							break;
						case 2:
							$status='待收货';
							break;
						case 3:
							$status='已完成';
							break;
						case 4:
							$status='已取消';
							break;
						case 5:
							$status='已退款';
							break;
						default:
							break;
					}
					$xlsData[$k]['status'] = $status;
				}
				$this->exportExcel($xlsName, $xlsCell, $xlsData);
				break;

			default:
				break;
		}


	}

	/**
	 * 输出xls方法
	 */
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



	
}
?>