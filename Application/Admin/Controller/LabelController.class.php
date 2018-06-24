<?php

namespace Admin\Controller;

class LabelController extends BaseController {
	public function index() {
	}

	/**
	 * 标签列表，分页
	 */
	public function label($type='') {
		$tblname='label';
		$where = null;
		$searchtype = I ( 'searchtype' );
		$keyword = I ( 'keyword' ); 
		$status = I ( 'status' );
		
		switch ($searchtype) {
			case '0' :
				$name = $keyword;
				break;
			case '1' :
				$info = $keyword;
				break; 
		}

		if (is_numeric ( $type )) {
			$where ['pid'] = $type;
		}
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}
		if (!isN ( $name )) {
			$where ['name'] = array('like','%'.$name.'%');
		}
		if (!isN ( $info )) {
			$where ['info'] = array('like','%'.$info.'%');
		}
 
		// 分页
		$p = intval ( I ( 'p' ) );
		$p = $p ? $p : 1;
		$row = C ( 'VAR_PAGESIZE' );
	
		$rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
		$list = $rs->select ();
	
		$this->assign ( "list", $list );
		$count = $rs->where ( $where )->count ();
	
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( 'page', $page->show () );
		}

		// 已有分类列表
		$where = array ();
		$where ['status'] = 1;
		$typelist = M ( 'category_label' )->where ( $where )->order ( 'sort asc' )->select ();
		$this->assign ( "typelist", $typelist );
		
		$this->assign ( "keyword", $keyword ); 
		$this->assign ( "searchtype", $searchtype ); 
		$this->assign ( "status", $status ); 
		$this->assign ( "type", $type ); 
		

		$control = 'Label';
		$action = 'Label';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );

		// 状态标识
		$this->assign ( "statuslist", C ( 'STATUS' ) );
		
		$this->assign('title','标签列表');
		$this->display ();
	}
	

	/**
	 * 添加
	 */
	public function addLabel() {
		$this->editLabel ();
	}
	/**
	 * 添加修改标签
	 *
	 * @param number $id        	
	 */
	public function editLabel($id = 0) {
		$tblname = 'label';
		$name = '标签';
		$tplname = 'editLabel';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['name'] )) {
				$this->error ( '对不起，' . $name . '标题不能为空！' );
			}
			
			if($data['pid']==2||$data['pid']==3){
			    for($i=0;$i<count($data['day_indexpic']);$i++){
			        $data['ext_detail'][]=array($data['day_indexpic'][$i],$data['day_link'][$i],$data['day_remark'][$i],$data['day_type'][$i]);
			    }
			    unset($data['day_indexpic'],$data['day_link'],$data['day_remark'],$data['day_type']);
			    $data['info']=serialize($data['ext_detail']);
			}
			if ($id) {
				$db = M ( $tblname )->where ( array (
						'id' => $id 
				) )->data ( $data )->save ();
				//清除缓存
				$cachename = 'label_' . $data ['name'];
				S ( $cachename ,null);
				$this->success ( '恭喜，' . $name . '修改成功！' );
			} else {
				unset ( $data ['id'] );
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
				if($db['pid']==2||$db['pid']==3){
				    $db['images']=$db['info'];
				    $db['info']='';
				}
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}
			
			// 已有分类列表
			$where = array ();
			$where ['status'] = 1;
			$list = M ( 'category_label' )->where ( $where )->order ( 'sort asc' )->select ();
			$list = list_to_tree ( $list );
			$this->assign ( "list", $list );
			
			// 状态标识
			$this->assign ( "statuslist", C ( 'STATUS' ) );
			$this->assign ( "name", $name );

			$control = 'Label';
			$action = 'Label';
			$this->assign ( "control", $control );
			$this->assign ( "action", $action );
			$this->assign ( "tblname", $tblname );
			
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除标签
	 *
	 * @param number $id        	
	 */
	public function deleteLabel($id = 0) {
		$tblname = 'label';
		$name = '标签';
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