<?php

namespace Home\Controller;

use Think\Controller;

class WechatController extends Controller {
	protected $wechatObj;
	
	/**
	 * 初始化微信接口类
	 *
	 * @return \Org\Util\Wechat
	 */
	public function _initialize() {
		
		
		$this->tokenid = get_mp_str ();
		$this->wechatObj = get_wechat_obj ( $this->tokenid );
	}
	
	/**
	 * 微信接口主程序
	 */
	public function index() {
		
		$wechat = $this->wechatObj;
		$wechat->valid ();
		
		$type = $wechat->getRev ()->getRevType ();
		
		// 注册新用户
		// $this->register($wechat->getRevFrom());
		
		switch ($type) {
			case $wechat::MSGTYPE_TEXT :
				$this->log ();
				// 文字处理
				$msg = $wechat->getRevContent ();
				logdebug('msg==='.$msg);
				$this->getKeyword ( $wechat, $msg );
				break;
			case $wechat::MSGTYPE_EVENT :
				$this->log ();
				// 事件处理
				$msg = $wechat->getRevEvent ();
				switch (strtolower ( $msg ['event'] )) {
					case 'subscribe' :
						$this->subscribe($wechat->getRevFrom(),$msg ['key']);
						$this->getKeyword ( $wechat, 'subscribe' );
						break;
					case 'unsubscribe' :
//						$this->unsubscribe($wechat->getRevFrom());
						break;
					case 'click' :
						
						// $this->log ( );
						$this->getKeyword ( $wechat, $msg ['key'], 'click' );
						break;
					case 'view' :
						
						// $wechat->text($this->getOpenUrl($msg['EventKey'],$wechat->getRevFrom()))->reply();
						break;
					case 'location' :
						
						// $msg = $wechat->getRevEventGeo ();
						// S ( 'pos_' . $wechat->getRevFrom (), serialize ( $msg ) );
						break;
					case 'scan' :
						$this->scan ( $wechat->getRevFrom (), $msg ['key'] );
						// exit();
						// $wechat->text ( $text )->reply ();
						break;
					case 'poi_check_notify' :
				}
				
				break;
			case $wechat::MSGTYPE_IMAGE :
				$this->log ();
				// 返回收到的图片地址
				// $msg = $wechat->getRevPic ();
				// $wechat->text ( arr2str ( $msg ) )->reply ();
				break;
			case $wechat::MSGTYPE_LOCATION :
				
				// 返回当前地理位置
				$msg = $wechat->getRevGeo ();
				$wechat->text ( arr2str ( $msg ) )->reply ();
				break;
			case $wechat::MSGTYPE_LINK :
				break;
			case $wechat::MSGTYPE_MUSIC :
				break;
			case $wechat::MSGTYPE_NEWS :
				break;
			case $wechat::MSGTYPE_VOICE :
				
				// 语音处理：返回语音识别成文字结果
				// $msg = $wechat->getRevVoice ();
				// $this->getKeyword ( $wechat, $msg ['recognition'] );
				// $this->log ();
				$msg = $wechat->getRevContent ();
				$this->getKeyword ( $wechat, $msg );
				
				break;
			case $wechat::MSGTYPE_VIDEO :
				break;
			
			default :
			// $weixin->text ( "help info" )->reply ();
		}
	}
	/**
	 * 扫描
	 *
	 * @param string $openid
	 */
	private function scan($openid = '', $key = '', $custom = false) {
		if ($key) {
			$key = str_replace ( 'qrscene_', '', $key );

			if($key){
				U_Subscribe($openid,$key);
			}
		}
	}
	/**
	 * OPEN用户注册
	 *
	 * @param unknown $openid        	
	 */
	public function register($openid) {
		if (! S ( $openid )) {
			S ( $openid, 1 );
			// $apiurl = get_app_url ( 'ucenter', $this->tokenid) . 'Front/Member/register';
			$apiurl = get_app_url ( 'ucenter', $this->tokenid ) . 'Front/Member/register';
			$param ['apicode'] = C ( 'API_CODE' );
			$param ['openid'] = $openid;
			$param ['user_mp_id'] = $this->tokenid;
			$result = http ( $apiurl, $param );
		}
	}
	
	/**
	 * 消息记录，api方式
	 */
	private function log() {
		return false;
		$wechat = $this->wechatObj;
		$type = $wechat->getRev ()->getRevType ();
		$logs = array ();
		$logs ['user_mp_id'] = $this->tokenid;
		$logs ['type'] = $type;
		$logs ['info'] = $wechat->getRevData ();
		$content = "";
		switch ($type) {
			case "text" :
				$content = $logs ["info"] ["Content"];
				break;
			case "image" :
				$mpinfo = get_mpid_rights ( $this->tokenid );
				$content = $logs ["info"] ["MediaId"]; // $mpinfo["isauth"] ? downImg(getMediaImg($this->tokenid, $logs["info"]["MediaId"]), $this->tokenid) : $logs["info"]["MediaId"]; // downImg(getMediaImg($this->tokenid, $logs ["info"] ["MediaId"]), $this->tokenid);
				break;
			case "voice" :
				$content = $logs ["info"] ["Recognition"];
				break;
		}
		// 消息中心添加记录
		if ($content) {
			add_message ( $content, $type, $logs ["info"] ["FromUserName"], $logs ["info"] ["ToUserName"], 1, $this->tokenid );
		}
		// 写消息到部库，暂时弃用
		// $apiurl = get_app_url ( 'ucenter', $this->tokenid) . 'Front/Member/logmsg';
		$apiurl = get_app_url ( 'ucenter', $this->tokenid ) . 'Front/Member/logmsg';
		$param ['apicode'] = C ( 'API_CODE' );
		$param ['msg'] = $logs;
		http ( $apiurl, $param );
	}
	
	/**
	 * 关注，设置会员关注时间和状态
	 *
	 * @param string $openid        	
	 */
	private function subscribe($openid = '', $key = '') {
		//U_Subscribe ( $openid );
		if ($key) {
			$key = str_replace ( 'qrscene_', '', $key );

			if($key){
				U_Subscribe($openid,$key);
			}
		}else{
			U_Subscribe ( $openid );
		}


	}
	
	/**
	 * 取消关注，设置会员取消时间和状态
	 *
	 * @param string $openid        	
	 */
	private function unsubscribe($openid = '') {
		U_Unsubscribe ( $openid );
	}
	
	/**
	 * 取关键词表的关键词ID
	 *
	 * @param unknown $obj        	
	 * @param unknown $msg        	
	 */
	private function getKeyword($obj, $msg, $type = '') {
		$db = get_reply_by_keyword ( $msg, $type );
		if ($db) {
			$transfer = md5 ( '[www.abc.com]' );
			$bot = md5 ( '[www.abc.cn]' );
			if ($db [0] ['info'] == $transfer) {
				// 转多客服
				$customer_account = null;
				$obj->transfer_customer_service ( $customer_account )->reply ();
			} else if ($db [0] ['info'] == $bot) {
				// 图灵机器人
				$url = 'http://www.tuling123.com/openapi/api?key=' . C ( 'WEIMI.tuling_key' ) . '&info=' . $msg;
				$result = http ( $url );
				$result = json_decode ( $result, true );
				switch ($result ['code']) {
					case 100000 :
						$info = $result ['text'];
						$ret = array (
								'type' => 0,
								'info' => $info 
						);
						$this->sendReplySingle ( $obj, $ret );
						break;
					case 200000 :
						$info = '<a href="' . $result ['url'] . '">' . $result ['text'] . '</a>';
						$ret = array (
								'type' => 0,
								'info' => $info 
						);
						$this->sendReplySingle ( $obj, $ret );
						break;
				}
			} else {
				if (count ( $db ) == 1) {
					// 单条直接回复
					$this->sendReplySingle ( $obj, $db [0] );
				} else {
					
					// 多条调用客服接口
					foreach ( $db as $k => $v ) {
						$this->sendReplyMulti ( $obj, $v );
					}
				}
			}
		}
	}
	
	/**
	 * 根据关键词ID，取回复表数据：注意多条回复(keyword_reply)
	 *
	 * @param unknown $obj        	
	 * @param string $keyword        	
	 * @param string $isall:0-随机回复，1-全部回复        	
	 */
	public function dealKeyword($obj, $keywordid = '', $isall = 0) {
		$where = array ();
		$where ['keyword_id'] = $keywordid;
		if ($isall) {
			// 多条回复需要调用客服接口
			$db = M ( 'keyword_reply' )->where ( $where )->limit ( 5 )->order ( 'sort asc ,id asc' )->select ();
			foreach ( $db as $k => $v ) {
				$this->sendReplyMulti ( $obj, $v );
			}
		} else {
			// 单回复
			$db = M ( 'keyword_reply' )->where ( $where )->order ( 'rand()' )->find ();
			if ($db) {
				$this->sendReplySingle ( $obj, $db );
			}
		}
	}
	
	/**
	 * 回复多条:需要调用客服接口
	 *
	 * @param unknown $obj        	
	 * @param unknown $db        	
	 */
	protected function sendReplyMulti($obj, $db) {
		if (! $db ['info']) {
			return false;
		}
		switch ($db ['type']) {
			case '0' :
				
				// 文字
				$msg = array (
						'touser' => $obj->getRevFrom (),
						'msgtype' => 'text',
						'text' => array (
								'content' => $this->replaceHref ( $db ['info'], $obj->getRevFrom () ) 
						) 
				);
				$obj->sendCustomMessage ( $msg );
				break;
			case '1' :
				
				// 图片:/Public/uploadfile/file/2014-08-25/53fabf1b81301.jpg
				$img = '@' .  ( $_SERVER ['DOCUMENT_ROOT'] ) . $db ['info'];
				$data = array (
						'media' => $img 
				);
				$ret = $obj->sendMedia ( $data, 'image' );
				logdebug('BBB'.json_encode($ret));
				
				$msg = array (
						'touser' => $obj->getRevFrom (),
						'msgtype' => 'image',
						'image' => array (
								'media_id' => $ret ['media_id'] 
						) 
				);
				$obj->sendCustomMessage ( $msg );
				break;
			
			case '3' :
				
				// 语音
				$img =  '@' .  ( $_SERVER ['DOCUMENT_ROOT'] ) . $db ['info'];
				$data = array (
						'media' => $img 
				);
				$ret = $obj->sendMedia ( $data, 'voice' );
				$msg = array (
						'touser' => $obj->getRevFrom (),
						'msgtype' => 'voice',
						'voice' => array (
								'media_id' => $ret ['media_id'] 
						) 
				);
				$obj->sendCustomMessage ( $msg );
				break;
			case '4' :
				
				// 视频
				$img =  '@' .  ( $_SERVER ['DOCUMENT_ROOT'] ) . $db ['info'];
				$data = array (
						'media' => $img 
				);
				$ret = $obj->sendMedia ( $data, 'video' );
				$msg = array (
						'touser' => $obj->getRevFrom (),
						'msgtype' => 'video',
						'video' => array (
								'media_id' => $ret ['media_id'] 
						) 
				);
				$obj->sendCustomMessage ( $msg );
				break;
			case '2' :
				
				// 图文
				$material_id = $db ['info'];
				$db = M ( 'wechat_material_detail' )->where ( 'material_id=' . $material_id )->order ( 'sort asc,id asc' )->select ();
				foreach ( $db as $k => $v ) {
					if ($v ['islink'] == '1') {
						$url = $v ['url'];
					} else {
						$url = get_app_news_url ( C ( 'APP_CODE' ), $this->tokenid, $v ['id'], $obj->getRevFrom () );
					}
					$ret ['articles'] [] = array (
							'title' => $v ['title'],
							'description' => $v ['remark'],
							'picurl' => get_resource_url ( $v ['indexpic'] ),
							'url' => $url 
					);
				}
				$msg = array (
						'touser' => $obj->getRevFrom (),
						'msgtype' => 'news',
						'news' => $ret 
				);
				$obj->sendCustomMessage ( $msg );
				break;
			case '5' :
				
				// App
				// app_icon,app_title,app_intro,app_cover,app_response
				// 取wechat_setting，没有就取app默认
				$app = get_app_info ( $db ['info'] );
				$app_setting = array (
						'app_code' => $app ['app_code'],
						'app_response' => $app ['app_response'],
						'app_title' => $app ['app_title'],
						'app_intro' => $app ['app_intro'],
						'app_cover' => $app ['app_cover'] 
				);
				
				// 获取应用配置
				$db = M ( $app ['app_code'] . '_setting', 'wm_' )->find ();
				if ($db) {
					if ($db ['app_title']) {
						$app_setting = array (
								'app_response' => $db ['app_response'],
								'app_title' => $db ['app_title'],
								'app_intro' => $db ['app_intro'],
								'app_cover' => $db ['app_cover'],
								'app_code' => $app ['app_code'] 
						);
					}
				}
				$app_url = $this->getOpenUrl ( get_app_url ( $app_setting ['app_code'], $this->tokenid ), $obj->getRevFrom () );
				
				// 响应方式
				if ($app_setting ['app_response'] == 1) {
					// 1-链接
					$ret = "<a href='" . $app_url . "'>" . $app_setting ['app_title'] . "</a>";
					$obj->text ( $ret )->reply ();
				} else {
					// 0-图文（默认）
					$ret ['articles'] [] = array (
							'title' => $app_setting ['app_title'],
							'description' => $app_setting ['app_intro'],
							'picurl' => get_resource_url ( $app_setting ['app_cover'] ),
							'url' => $app_url 
					);
					
					$msg = array (
							'touser' => $obj->getRevFrom (),
							'msgtype' => 'news',
							'news' => $ret 
					);
					$obj->sendCustomMessage ( $msg );
				}
				break;
		}
	}
	
	/**
	 * 单条回复
	 *
	 * @param unknown $obj        	
	 * @param unknown $db
	 *        	$type: 0-文字，1-图片，2-图文，3-语音，4-视频，5-app
	 */
	protected function sendReplySingle($obj, $db) {
		if (! $db ['info']) {
			return false;
		}
		switch ($db ['type']) {
			case '0' :
				
				// 文字
				$obj->text ( $this->replaceHref ( $db ['info'], $obj->getRevFrom () ) )->reply ();
				break;
			case '1' :
				
				// 图片:/Public/uploadfile/file/2014-08-25/53fabf1b81301.jpg
				 logdebug(json_encode($_SERVER));
				//$img =  '@' .  ( $_SERVER ['DOCUMENT_ROOT'] ) . $db ['info'];
				$img = '@' .  ( $_SERVER ['DOCUMENT_ROOT'] ) . $db ['info'];
				logdebug($img);
				$data = array (
						'media' => $img 
				);
				$ret = $obj->sendMedia ( $data, 'image' );
				logdebug('AAA'.json_encode($ret));
				$obj->image ( $ret ['media_id'] )->reply ();
				break;
			case '3' :
				
				// 语音
				$img =  '@' .  ( $_SERVER ['DOCUMENT_ROOT'] ) . $db ['info'];
				$data = array (
						'media' => $img 
				);
				$ret = $obj->sendMedia ( $data, 'voice' );
				$obj->voice ( $ret ['media_id'] )->reply ();
				break;
			case '4' :
				
				// 视频
				$img =  '@' .  ( $_SERVER ['DOCUMENT_ROOT'] ) . $db ['info'];
				$data = array (
						'media' => $img 
				);
				$ret = $obj->sendMedia ( $data, 'video' );
				$obj->video ( $ret ['media_id'] )->reply ();
				break;
			
			case '2' :
				
				// 图文
				$material_id = $db ['info'];
				$db = M ( 'wechat_material_detail' )->where ( 'material_id=' . $material_id )->order ( 'sort asc,id asc' )->select ();
				foreach ( $db as $k => $v ) {
					if ($v ['islink'] == '1') {
						$url = $v ['url'];
					} else {
						$url = get_app_news_url ( C ( 'APP_CODE' ), $this->tokenid, $v ['id'], $obj->getRevFrom () );
					}
					$ret [] = array (
							'Title' => $v ['title'],
							'Description' => $v ['remark'],
							'PicUrl' => get_resource_url ( $v ['indexpic'] ),
							'Url' => $url 
					);
				}
				$obj->news ( $ret )->reply ();
				break;
			case '5' :
				
				// App
				// app_icon,app_title,app_intro,app_cover,app_response
				// 取wechat_setting，没有就取app默认
				$app = get_app_info ( $db ['info'] );
				$app_setting = array (
						'app_code' => $app ['app_code'],
						'app_response' => $app ['app_response'],
						'app_title' => $app ['app_title'],
						'app_intro' => $app ['app_intro'],
						'app_cover' => $app ['app_cover'] 
				);
				// 获取应用配置
				$db = M ( $app ['app_code'] . '_setting', 'wm_' )->find ();
				if ($db) {
					if ($db ['app_title']) {
						$app_setting = array (
								'app_response' => $db ['app_response'],
								'app_title' => $db ['app_title'],
								'app_intro' => $db ['app_intro'],
								'app_cover' => $db ['app_cover'],
								'app_code' => $app ['app_code'] 
						);
					}
				}
				$app_url = $this->getOpenUrl ( get_app_url ( $app_setting ['app_code'], $this->tokenid ), $obj->getRevFrom () );
				
				// 响应方式
				if ($app_setting ['app_response'] == 1) {
					// 1-链接
					$ret = "<a href='" . $app_url . "'>" . $app_setting ['app_title'] . "</a>";
					$obj->text ( $ret )->reply ();
				} else {
					// 0-图文（默认）
					$ret = array (
							array (
									'Title' => $app_setting ['app_title'],
									'Description' => $app_setting ['app_intro'],
									'PicUrl' => get_resource_url ( $app_setting ['app_cover'] ),
									'Url' => $app_url 
							) 
					);
					$obj->news ( $ret )->reply ();
				}
				break;
		}
	}
	
	/**
	 * 给URL加上openid后缀
	 *
	 * @param string $url        	
	 * @param string $openid        	
	 * @return string
	 */
	public function getOpenUrl($url = '', $openid = '') {
		if (strpos ( $url, '?' )) {
			$url .= '&_openid=' . $openid;
		} else {
			$url .= '?_openid=' . $openid;
		}
		return $url;
	}
	
	/**
	 * 提取文本里的a，加上openid
	 *
	 * @param string $content        	
	 */
	public function replaceHref($content = '', $openid = '') {
		// return $content;
		return preg_replace ( '/href=[\'|\"](\S+)[\'|\"]/i', "href='" . $this->getOpenUrl ( '${1}', $openid ) . "'", $content );
	}
}