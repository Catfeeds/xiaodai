<?php

namespace Admin\Controller;

class FormController extends BaseController {
	public function index() {
	}
	
	/**
	 * 报名列表
	 */
	public function feedback($searchtype = '',$type='', $keyword = '', $status = '', $p = 1) {
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
					$where ['telephone'] = array (
							'like',
							'%' . $keyword . '%' 
					);
				}
				break;
		}
		
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		if(is_numeric($type)){
			$where['type']=$type;
		}

		// 表名
		$tblname = 'feedback';
		$name = '报名';
		// 分页
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
		
		$this->assign ( "keyword", $keyword );
		$this->assign ( "searchtype", $searchtype );
		$this->assign ( "status", $status );
		$this->assign('type',$type);

		// 当前表名
		$control = 'Form';
		$action = 'feedback';
		$this->assign ( "control", $control );
		$this->assign ( "action", $action );
		// 当前表名
		$this->assign ( "tblname", $tblname );
		$this->assign ( "name", $name );
		// 状态标识
		$this->assign ( "statuslist", C ( 'BOOKSTATUS' ) );
		
		$this->assign ( "title", '报名列表' );
		$this->display ();
	}
	
	/**
	 * 添加
	 */
	public function addFeedback() {
		$this->editFeedback ();
	}
	/**
	 * 添加修改报名
	 *
	 * @param number $id        	
	 */
	public function editFeedback($id = 0) {
		$tblname = 'feedback';
		$name = '报名';
		$tplname = 'editFeedback';
		if (IS_POST) {
			$data = $_POST;
			if (isN ( $data ['username'] )) {
				$this->error ( '对不起，用户姓名不能为空！' );
			}
			if($data['status']==1 && !isN($data['replay']))
				$data['replaytime']=date('Y-m-d H:i:s');
			if ($id) {

				$db = M ( $tblname )->where ( array (
						'id' => $id 
				) )->data ( $data )->save ();
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
				$this->assign ( 'db', $db );
				$this->assign ( 'title', '修改' . $name );
				// 修改的时候取父ID参数
				$this->assign ( "pid", $db ['pid'] );
			} else {
				// 添加
				$this->assign ( 'title', '添加' . $name );
			}

			//专业列表
			$majorlist=M('content_news')->where(array('sortpath'=>array('like','%,315,%')))->select();
			$this->assign('majorlist',$majorlist);


			// 状态标识
			$this->assign ( "statuslist", C ( 'BOOKSTATUS' ) );
			$this->assign ( "name", $name );
			$this->display ( $tplname );
		}
	}
	/**
	 * 删除报名
	 *
	 * @param number $id        	
	 */
	public function deleteFeedback($id = 0) {
		$tblname = 'feedback';
		$name = '报名';
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
	 * 导出预约试听
	 *
	 * @param number $id
	 */
	public function exportfeedback($status = '')
	{
		Vendor("PHPExcel.PHPExcel");
		Vendor("PHPExcel.PHPExcel.IOFactory");
		$objPHPExcel = new \PHPExcel();

		$where = array ();
		$where['type']=1;
		if (is_numeric ( $status )) {
			$where ['status'] = $status;
		}

		$result = M('feedback')->where($where)->select();
		if($result){
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', '序号')
					->setCellValue('B1', '姓名')
					->setCellValue('C1', '联系电话')
					->setCellValue('D1', '预约课程')
					->setCellValue('E1', '预约时间')
					->setCellValue('F1', '提交预约时间')
					->setCellValue('G1', '状态');
			for($j=0;$j<count($result);$j++){
				$status='';

				switch($result[$j]['status']){
					case 0:
						$status='待处理';
						break;
					case 1:
						$status='已处理';
						break;
					case 2:
						$status='无须处理';
						break;
					default:
						exit;
						break;
				};
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . ($j+2), $result[$j]['id'])
						->setCellValue('B' . ($j+2), $result[$j]['username'])
						->setCellValue('C' . ($j+2), $result[$j]['telephone'])
						->setCellValue('D' . ($j+2), $result[$j]['classname'])
						->setCellValue('E' . ($j+2), $result[$j]['time'])
						->setCellValue('F' . ($j+2), $result[$j]['addtime'])
						->setCellValue('G' . ($j+2), $status);

			}

			$objPHPExcel->getActiveSheet()->setTitle('预约试听'.date("Y-m",time()));
			$objPHPExcel->setActiveSheetIndex(0);
			$filename = date('Y-m',time())."预约试听";
			ob_end_clean() ;
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename.'.xlsx');
			header('Cache-Control: max-age=0');
			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
		}else{
			echo '<script type="text/javascript">alert("没有数据导出！");window.history.back();</script>';
		}


	}


}
?>