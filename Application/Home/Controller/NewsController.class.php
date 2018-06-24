<?php

namespace Home\Controller;

class NewsController extends AuthbaseController{
	public function index() {
	}
	
//	
//	public function _before_nview(){
//
//		$readid=session('readid');
//		$starttime=session('nowtime');
//		$nowtime=date('Y-m-d H:i:s');
//		$readtime=floor((strtotime($nowtime)-strtotime($starttime))%86400/60);//分钟数
//		$readtime=$readtime?$readtime:1;
//		$data=array();
//		$data['endtime']=$nowtime;
//		$data['readtime']=array('exp','readtime + '.$readtime);
//
//		M('article_view')->where(array('id'=>$readid))->data($data)->save();
//		
//	}
	
	/**
	 * 阅读
	 *
	 * @param number $id        	
	 */
	public function view($id = 0) {
		$mpid = get_mp_str();
		$signPackage = getShareSign ( $mpid );
		$this->assign ( 'signPackage', $signPackage );
		
		$db = M ( 'wechat_material_detail' )->find ( $id );
		if ($db) {
			if ($db ['islink'] == 1) {
				if ($db ['url'] != '') {
					header ( "location:" . $db ['url'] );
					exit ();
				}
			}
			// 增加点击数
			$where = array ();
			$where ['id'] = $id;
			M ( 'wechat_material_detail' )->where ( $where )->setInc ( 'hits' );
		} else {
			$this->error ( '对不起，该信息不存在！' );
		}
		
		$mpinfo = get_mp_info ( $mpid );
		$this->assign ( 'mpinfo', $mpinfo );
		
		$this->assign ( 'db', $db );
		$this->assign ( 'title', $db ['title'] );
		$this->display ();
	}

	/**
	 * 列表
	 *
	 * @param number $categoryid
	 * @param number $p
	 */
	public function lists($id = 0) {

		if($id){
			$id=$id?$id:0;
		}
		//左侧分类

		$categorylist=M('category_news')->where(array('inner'=>0))->order('sort asc')->select();


		$this->assign ( 'categorylist', $categorylist );

		$this->assign ( 'categoryid', $id );
		$this->assign ( 'title', '新闻中心' );
		$this->assign ( 'keywords','新闻中心' );
		$this->assign ( 'description', '新闻中心' );

		$this->display ();

	}


	public function getnewsList($id='',$p=1){
		// 分页
		$row = C ( 'VAR_PAGESIZE' );
		$cate=M('category_news')->where(array('inner'=>0,'status'=>1))->select();
        $ids=array();
        foreach($cate as $k=>$v){
            $ids[$k]=$v['id'];
        }

		
		$where = array ();
		$where ['memberid'] = get_memberid ();
		if($id){
			$where['sortpath']=array('like','%,'.$id.',%');
		}else{
			$where['pid']=array('in',$ids);
		}
		

		$list = M ( "content_news" )->where ( $where )->order ( 'id desc' )->page ( $p, $row )->select ();



		$this->assign ( "list", $list );
		$this->assign ( 'p', $p );
		$this->display ();
	}

	/**
	 * 详情
	 *
	 * @param number $id
	 */
	public function nview($id = 0,$fromid='',$from='') {

		

		//检查微信接口信息
		$mpid = get_mp_str();
		$signPackage = getShareSign ( $mpid );
		$this->assign ( 'signPackage', $signPackage );


		$openid = openid();
		if (!$openid) {
			$current_url = get_current_url();
			get_auth_openid($current_url);
		} else {
			$memberid = M("member")->where(array('openid' => $openid))->getField('id');
			if ($memberid) {
				session('memberid', $memberid);
			} else {
				$current_url = get_current_url();
				get_auth_openid($current_url);
			}

		}

		/**
		 * 记录文章浏览记录
		 * 微信群：groupmessage
		 * 好友分享：singlemessage
		 * 公众号：空
		 * 朋友圈：timeline
		 *
		 */

		$dataview=array();
		$dataview['articleid']=$id;
		$dataview['memberid']=get_memberid();
		$source=0;
		switch($from){
			case 'groupmessage':
				$source=2;
				break;
			case 'singlemessage':
				$source=3;
				break;
			case 'timeline':
				$source=1;
				break;
			default:
				$source=0;
				break;
		}
		$dataview['from']=$source;
		$dataview['frommemberid']=$fromid;
		$dataview['addtime']=date('Y-m-d H:i:s');
		$dataview['endtime']=date('Y-m-d H:i:s');
		$where = array ();
		$where ['memberid'] = get_memberid();
		$where ['articleid'] = $id;
		$time = NOW_TIME - 60 * 30;//30分钟内刷新页面或者从新进去该页面不重新算阅读记录。
		$where ['addtime'] = array (
				'gt',
				time_format ( $time )
		);
		
		$viewed=M('article_view')->where($where)->find();
		$nowtime=date('Y-m-d H:i:s');
		if(!$viewed){
			
			if($fromid){
				$point=C('config.SHARE_POINT');
				$setpoint=M('member')->where(array('id'=>$fromid))->setField(array('point' => array('exp', 'point + ' . $point)));
				write_point_log($fromid, intval($point), '分享文章赠送积分', 1);
			}
			
			$readid=M('article_view')->data($dataview)->add();//添加阅读记录
			M ( 'content_news')->where ( array('id'=>$id,'status'=>1) )->setInc ( 'hits' );
		}else{
			$readid=$viewed['id'];

		}
		
		
		
		session('readid',$readid);
		session('nowtime',$nowtime);
		$this->assign('readid',$readid);
		$this->assign('nowtime',$nowtime);


		$tblname = 'news';

		// 取栏目信息
		$where = array ();
		$where ['id'] = $id;
		$where ['status'] = 1;
		$db = M ( 'content_' . $tblname )->where ( $where )->find ();
		if (! $db) {
			go_404 ();
		} else {
			M ( 'content_' . $tblname )->where ( $where )->setInc ( 'hits' );
			$db ['hits'] += 1;
			// 取栏目信息
			$where = array ();
			$where ['id'] = $db ['pid'];
			$channel = M ( 'category_' . $tblname )->where ( $where )->find ();
			$this->assign ( 'channel', $channel );

		}
		$this->assign ( 'db', $db );
		$this->assign ( 'categryid', $db ['pid'] );

		$pid_array =arr2clr(explode(',',$db ['sortpath']));

		$pid = $db["pid"];

		if(count($pid_array) > 1 ) {
			$pid = $pid_array[count($pid_array) - 1 ];

		}


		$categorylist=M('category_news')->where(array('inner'=>0))->order('sort asc')->select();
		$this->assign ( 'categorylist', $categorylist );


		$category_tblname = 'news';
		// 取栏目信息
		$category_where = array ();
		$category_where ['id'] = $pid;
		$category_where ['status'] = 1;
		$category = M ( 'category_' . $category_tblname )->where ( $category_where )->find ();


		//定义分享链接
		$shareurl="http://".get_base_domain().U('news/nview','id='.$id)."?fromid=".get_memberid();
		$shareimg="http://".get_base_domain().$db['indexpic'];
		$this->assign('shareurl',$shareurl);
		$this->assign('shareimg',$shareimg);

		//是否点赞
		$ispraise=M('article_praise')->where(array('articleid'=>$id,'memberid'=>get_memberid()))->find();
		if($ispraise){
			$ispraise=1;
		}else{
			$ispraise=0;
		}
		$this->assign('ispraise',$ispraise);

		
		

		$this->assign ( 'id', $id);
		$this->assign ( 'title', $db ['title'] );
		$this->assign ( 'keywords', $db ['keywords'] );
		$this->assign ( 'description', $db ['description'] );
		$this->assign('category',$category);
		$this->display ();
	}
	
	
	//记录文章阅读时间
	public function unloadpage(){
		
		$readid=$_POST['readid'];
		$starttime=$_POST['nowtime'];
		$nowtime=date('Y-m-d H:i:s');
		$readtime=floor((strtotime($nowtime)-strtotime($starttime))%86400/60);//分钟数
		$readtime=1;//$readtime?$readtime:1;
		$data=array();
		$data['endtime']=$nowtime;
		$data['readtime']=array('exp','readtime + '.$readtime);
		logdebug($data);
		logdebug($readid);
		M('article_view')->where(array('id'=>$readid))->data($data)->save();
	}



	
	
	public function setshare(){
		$articleid=$_POST['articleid'];
		$memberid=get_memberid();
		$result=array();
		if(!$memberid){
			$result['status']=0;
			$result['info']='分享成功，但获取分享人失败，此次分享不计入分享记录中';
			$this->ajaxReturn($result);
		}

		$datashare=array();
		$datashare['articleid']=$articleid;
		$datashare['memberid']=$memberid;
		$datashare['addtime']=date('Y-m-d H:i:s');
		$add=M('article_share')->data($datashare)->add();
		if($add===false){
			$result['status']=0;
			$result['info']='分享成功，但写入分享记录失败';
			$this->ajaxReturn($result);
		}

		M ( 'content_news')->where ( array('id'=>$articleid,'status'=>1) )->setInc ( 'shares' );//增加分享次数
		
		$result['status']=1;
		$result['info']='分享成功,有相关人员阅读后将会为您返积分';
		
		
		$this->ajaxReturn($result);
	}
	
	
	
	public function setpraise(){
		$articleid=$_POST['articleid'];
		$memberid=get_memberid();
		$result=array();
		if(!$memberid){
			$result['status']=0;
			$result['info']='获取信息失败,点赞失败';
			$this->ajaxReturn($result);
		}
			

		$find=M('article_praise')->where(array('articleid'=>$articleid,'memberid'=>$memberid))->find();
		if($find){
			$result['status']=0;
			$result['info']='已经点过赞啦！';
			$this->ajaxReturn($result);
		}


		$datapraise=array();
		$datapraise['articleid']=$articleid;
		$datapraise['memberid']=$memberid;
		$datapraise['addtime']=date('Y-m-d H:i:s');
		$add=M('article_praise')->data($datapraise)->add();
		if($add===false){
			$result['status']=0;
			$result['info']='点赞失败';
			$this->ajaxReturn($result);
		}

		M ( 'content_news')->where ( array('id'=>$articleid,'status'=>1) )->setInc ( 'praises' );//增加分享次数

		$point=C('config.PRAISE_POINT');
		$setpoint=M('member')->where(array('id'=>$memberid))->setField(array('point' => array('exp', 'point + ' . $point)));
		
		
		$result['status']=1;
		$result['info']='点赞成功,获得'.$point.'积分。';
		write_point_log($memberid, intval($point), '点赞文章赠送积分', 1);
		$this->ajaxReturn($result);


	}
	
	

	/**
	 * 单页
	 */
	public function about($id){
		$tblname = 'info';

		// 取栏目信息
		$where = array ();
		$where ['id'] = $id;
		$where ['status'] = 1;
		$db = M ( 'content_' . $tblname )->where ( $where )->find ();
		if (! $db) {
			go_404 ();
		} else {
			M ( 'content_' . $tblname )->where ( $where )->setInc ( 'hits' );
			$db ['hits'] += 1;
			// 取栏目信息
			$where = array ();
			$where ['id'] = $db ['pid'];
			$channel = M ( 'category_' . $tblname )->where ( $where )->find ();
			$this->assign ( 'channel', $channel );

		}
		$this->assign ( 'db', $db );
		$this->assign ( 'categryid', $db ['pid'] );


		$this->assign ( 'id', $id);
		$this->assign ( 'title', $db ['title'] );
		$this->assign ( 'keywords', $db ['keywords'] );
		$this->assign ( 'description', $db ['description'] );
		$this->display ();
	}

	/**
	 * 获取评论
	 *
	 * @param number $id        	
	 * @param number $p        	
	 */
	public function getComment($id = 0, $p = 1) {
		$tblname = "wechat_material_comment";
		$tbl = M ( $tblname );
		$pagesize = 10;
		$where ['material_detail_id'] = $id;
		$where ['pid'] = 0;
		$list = $tbl->where ( $where )->order ( 'id desc' )->page ( $p . ',' . $pagesize )->select ();
		$count = $tbl->where ( $where )->count (); // 查询满足要求的总记录数
		$pages = ceil ( $count / $pagesize ); // 总页数
		$html = '';
		$defaultpic = C ( 'DEFAULT_AVATAR' );
		
		foreach ( $list as $k => $v ) {
			$html .= '<li>';
			$html .= '  <div class="list-tx"><img ';
			$html .= 'src="' . $v ['indexpic'] . '"></div>';
			$html .= '  <div class="msg-desc"><strong>' . $v ['name'] . '</strong>';
			$html .= '    <label class="descbox">' . $v ['info'] . '</label>';
			$html .= '    <label class="descbox"><span>' . get_time_diff ( $v ['addtime'], time_format () ) . '</span><a href="javascript:void(0);" onClick="$.updown(' . $v ['id'] . ',1);"><img src="/Public/tuwen/pictext/zan.svg"  class="plicon"><span id="i_1_' . $v ['id'] . '">' . $v ['up'] . '</span></a>&nbsp;&nbsp; <a href="javascript:void(0);" onClick="$.updown(' . $v ['id'] . ',0);"><img src="/Public/tuwen/pictext/cai.svg" class="plicon"><span id="i_0_' . $v ['id'] . '">' . $v ['down'] . '</span></a> &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="$.reply(' . $v ['id'] . ',' . '\'@' . $v ["name"] . '：\'' . ');" class="f_right">回复</a>';
			
			if ($this->isAdmin ()) {
				$html .= ' <a href="javascript:void(0);" onClick="$.delComment(' . $v ["id"] . ');">删除</a> ';
			}
			$html .= '</label>';
			$html .= '  </div>';
			$html .= '</li>';
			
			// $html .= '<div class="comment"> <img class="comment-avatar" alt="" src="' . $v ['indexpic'] . '">';
			// $html .= ' <div class="comment-body">';
			// $html .= ' <div class="comment-text">';
			// $html .= ' <div class="comment-heading"> <a title="" href="javascript:void(0);">' . $v ['name'] . '</a><span>' . get_time_diff ( $v ['addtime'], time_format () ) . '</span> </div>';
			// $html .= ' ' . $v ['info'] . ' </div>';
			// $html .= ' <div class="comment-footer"> <a href="javascript:void(0);" onClick="$.updown(' . $v ['id'] . ',1);"><img src="/Public/tuwen/pictext/zan.svg" class="plicon"><span id="i_1_' . $v ['id'] . '">' . $v ['up'] . '</span></a>&nbsp;&nbsp; <a href="javascript:void(0);" onClick="$.updown(' . $v ['id'] . ',0);"><img src="/Public/tuwen/pictext/cai.svg" class="plicon"><span id="i_0_' . $v ['id'] . '">' . $v ['down'] . '</span></a> ';
			// $html .= '&nbsp;&nbsp;·&nbsp;&nbsp; <a href="javascript:void(0);" onClick="$.reply(' . $v ['id'] . ',' . '\'@' . $v ["name"] . ':\'' . ');" class="f_right">回复</a> ';
			// if ($this->isAdmin ()) {
			// $html .= ' <a href="javascript:void(0);" onClick="$.delComment(' . $v ["id"] . ');">删除</a> ';
			// }
			// $html .= '</div>';
			// $html .= ' </div>';
			
			$replylist = $tbl->where ( array (
					'pid' => $v ['id'] 
			) )->select ();
			foreach ( $replylist as $k1 => $v1 ) {
				
				$html .= '<li class="level2">';
				$html .= '  <div class="levelbox">';
				$html .= '  <div class="list-tx"><img ';
				$html .= 'src="' . $v1 ['indexpic'] . '"></div>';
				$html .= '  <div class="msg-desc"><strong>' . $v1 ['name'] . '</strong>';
				$html .= '    <label class="descbox">' . $v1 ['info'] . '</label>';
				$html .= '    <label class="descbox"><span>' . get_time_diff ( $v1 ['addtime'], time_format () ) . '</span><a href="javascript:void(0);" onClick="$.updown(' . $v1 ['id'] . ',1);"><img src="/Public/tuwen/pictext/zan.svg" class="plicon"><span id="i_1_' . $v1 ['id'] . '">' . $v1 ['up'] . '</span></a>&nbsp;&nbsp; <a href="javascript:void(0);" onClick="$.updown(' . $v1 ['id'] . ',0);"><img src="/Public/tuwen/pictext/cai.svg" class="plicon"><span id="i_0_' . $v1 ['id'] . '">' . $v1 ['down'] . '</span></a> ';
				
				if ($this->isAdmin ()) {
					$html .= ' <a href="javascript:void(0);" onClick="$.delComment(' . $v1 ["id"] . ');">删除</a> ';
				}
				$html .= '</label>';
				$html .= '  </div>';
				$html .= '  </div>';
				$html .= '</li>';
				
				// $html .= ' <div class="comment"> <img class="comment-avatar" alt="" src="' . $v1 ['indexpic'] . '">';
				// $html .= ' <div class="comment-body">';
				// $html .= ' <div class="comment-text">';
				// $html .= ' <div class="comment-heading"><a title="" href="javascript:void(0);">' . $v1 ['name'] . '</a><span>' . get_time_diff ( $v1 ['addtime'], time_format () ) . '</span> </div>';
				// $html .= ' ' . $v1 ['info'] . ' </div>';
				// $html .= ' <div class="comment-footer"> <a href="javascript:void(0);" onClick="$.updown(' . $v1 ['id'] . ',1);"><i class="fa fa-thumbs-o-up"> <font id="i_1_' . $v1 ['id'] . '">' . $v1 ['up'] . '</font></i></a>&nbsp;&nbsp; <a href="javascript:void(0);" onClick="$.updown(' . $v1 ['id'] . ',0);"><i class="fa fa-thumbs-o-down"> <font id="i_0_' . $v1 ['id'] . '">' . $v1 ['down'] . '</font></i></a> ';
				// if ($this->isAdmin ()) {
				// $html .= ' <a href="javascript:void(0);" onClick="$.delComment(' . $v1 ["id"] . ');">删除</a> ';
				// }
				// $html .= ' </div>';
				// $html .= ' </div></div>';
			}
			$html .= '</div>';
		}
		echo ($html);
	}
	
	/**
	 * 判断是否管理员
	 *
	 * @param number $user_id        	
	 * @return boolean
	 */
	private function isAdmin() {
		return in_array ( $this->tokenid, session ( 'user_mp_ids' ) );
	}
	
	/**
	 * 点赞
	 *
	 * @param number $detailId        	
	 */
	public function praise($detailId = 0) {
		$cookie = cookie ( "news_praise_{$detailId}" );
		if (isset ( $cookie )) {
			exit ( json_encode ( array (
					"status" => 2 
			) ) );
		} else {
			$where ['id'] = $detailId;
			$db = M ( 'wechat_material_detail' )->where ( $where )->setInc ( 'praise' );
			if ($db) {
				cookie ( "news_{$detailId}", $detailId );
				$this->success ( '恭喜，点赞成功！' );
			} else {
				$this->error ( '对不起，点赞失败！' );
			}
		}
	}
	
	/**
	 * 顶
	 *
	 * @param number $detailId        	
	 */
	public function updown($id = 0, $type = 1) {
		if (isset ( $_COOKIE ["news_ud_{$id}"] )) {
			exit ( json_encode ( array (
					"status" => 2 
			) ) );
		} else {
			$where ['id'] = $id;
			if ($type == 1) {
				$db = M ( 'material_comment' )->where ( $where )->setInc ( 'up' );
				if ($db) {
					cookie ( "news_ud_{$id}", $id );
					$this->success ( '恭喜，顶成功！' );
				} else {
					$this->error ( '对不起，顶失败！' );
				}
			} else {
				$db = M ( 'material_comment' )->where ( $where )->setInc ( 'down' );
				if ($db) {
					cookie ( "news_ud_{$id}", $id );
					$this->success ( '恭喜，踩成功！' );
				} else {
					$this->error ( '对不起，踩失败！' );
				}
			}
		}
	}
	
	/**
	 * 评论
	 *
	 * @param number $detailId        	
	 * @param string $name        	
	 * @param string $info        	
	 * @param string $indexpic        	
	 */
	public function comment($pid = 0, $detailId = 0, $name = '', $info = '', $indexpic = '') {
		if (! ($detailId == 0 || $name == '' || $info == '')) {
			if ($pid != 0) {
				$this->reply ( $pid, $detailId, $name, $info, $indexpic );
			}
			$data = array ();
			$data ['name'] = $name;
			$data ['info'] = $info;
			$data ['addip'] = get_client_ip ();
			$data ['material_detail_id'] = $detailId;
			$data ['pid'] = 0;
			$data ['openid'] = cookie ( 'openid' );
			$data ['indexpic'] = $indexpic;
			$db = M ( 'material_comment' )->data ( $data )->add ();
			if ($db) {
				$this->success ( '恭喜，评论成功！' );
			} else {
				$this->error ( '对不起，评论失败！' );
			}
		} else {
			$this->error ( '对不起，评论失败！' );
		}
	}
	
	/**
	 * 回复评论
	 *
	 * @param number $detailId        	
	 * @param string $name        	
	 * @param string $info        	
	 */
	public function reply($commentId = 0, $detailId = 0, $name = '', $info = '', $indexpic = '') {
		$where = array ();
		$where ['id'] = $detailId;
		$where ['user_id'] = array (
				'neq',
				0 
		);
		
		// if ($this->isAdmin($user_id)) {
		$data = array ();
		$data ['name'] = $name;
		$data ['info'] = $info;
		$data ['addip'] = get_client_ip ();
		$data ['material_detail_id'] = $detailId;
		$data ['pid'] = $commentId;
		$data ['indexpic'] = $indexpic;
		$db = M ( 'material_comment' )->add ( $data );
		if ($db) {
			$this->success ( '恭喜，回复成功！' );
		} else {
			$this->error ( '对不起，回复失败！' );
		}
		// } else {
		// $this->error ( '对不起，回复失败2！' );
		// }
	}
	
	/**
	 * 删除评论
	 *
	 * @param number $detailId        	
	 * @param number $commentid        	
	 */
	public function delete($detailId = 0, $commentid = 0) {
		if ($this->isAdmin ()) {
			$where = array ();
			$where ['id'] = $commentid;
			$db = M ( 'material_comment' )->where ( $where )->delete ();
			if ($db) {
				$db = M ( 'material_comment' )->where ( 'pid=' . $commentid )->delete ();
				$this->success ( '恭喜，评论删除成功！' );
			} else {
				$this->error ( '对不起，评论删除失败！' );
			}
		} else {
			$this->error ( '对不起，评论删除失败！' );
		}
	}
}
?>