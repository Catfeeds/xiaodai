<?php
$config = array (
		"LOAD_EXT_FILE" => "app,uchangpay",
		// '配置项'=>'配置值'
		'DB_TYPE' => 'mysql',
		'DB_HOST' => '127.0.0.1',
		'DB_NAME' => 'youyiqianbao',
		'DB_USER' => 'root',
		'DB_PWD' => '',
		'DB_PORT' => '3306',
		'SHOW_ERROR_MSG' => true,
		'DB_PREFIX' => 'my_',
		'COOKIE_PREFIX' => 'my_',


        'LANG_SWITCH_ON' => true,
        'LANG_AUTO_DETECT' => true,
        'LANG_LIST'        => 'ar',
        'VAR_LANGUAGE'     => 'lang',

		// 项目根地址（支付通知）
		// 'BASE_URL'=>'http://psck.cn-unisky.com/tg',
		'BASE_URL' => 'http://daikuan.yzkj888.com/',


		'MODULE_ALLOW_LIST' => array (
				'Home',
				'Api', // 接口
				'Admin' ,
				'Crm',
				'Exam'
		), // 后台
		'DEFAULT_MODULE' => 'Home',

		
		// 分页设置
		'VAR_PAGESIZE' => 10,
		'DEFAULT_AVATAR' => '/Public/Home/images/avatar.jpg',
		'DEFAULT_NOPIC' => '/Public/Home/images/nopic.png',
		
		// 状态设置
		'STATUS' => array (
				0 => '禁用',
				1 => '启用' 
		),
		'ORDERSTATUS' => array (
				0 => '待审核',
				1 => '已审核',
				2 => '已放款',
				3 => '已逾期',
				4 => '已还款',
				5 => '申请延期',
				6 => '确认延期'
		),
		'MEMBERSTATUS' => array (
				0 => '待审核',
				1 => '已提现',
				2 => '提现失败' 
		),
		'BOOKSTATUS' => array(
				0=>'待处理',
				1=>'已处理',
				2=>'无需处理'
		),
		
		'PAYSTATUS' => array (
				0 => '未付款',
				1 => '已付款' 
		),
		'PAYMETHOD' => array (
//				1 => '支付宝',
				2 => '消费积分支付',
//				3 => '积分支付',
				4 => '微信支付'
		),
		
		'PRIZETYPE' => array (
				1 => '实物',
				2 => '手机话费',
				3 => '手机流量',
				4 => '现金红包'
		),
		'PRIZESTATUS'=>array(
			0=>'未领取',
			1=>'已领取',
			2=>'已放弃'
		),
		
		
		// 模型设置
		
		'URL_MODEL' => 2,
		
		/* 文件上传相关配置 */
		'DOWNLOAD_UPLOAD' => array (
				'mimes' => '', // 允许上传的文件MiMe类型
				'maxSize' => 0, // 5 * 1024 * 1024, // 上传的文件大小限制 (0-不做限制)
				'exts' => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,flv,mp4,csv,mp3', // 允许上传的文件后缀
				'autoSub' => true, // 自动子目录保存文件
				'subName' => array (
						'date',
						'Y-m-d' 
				), // 子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
				'rootPath' => './Public/uploadfile/file/', // 保存根路径
				'savePath' => '', // 保存路径
				'saveName' => array (
						'uniqid',
						'' 
				), // 上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
				'saveExt' => '', // 文件保存后缀，空则使用原后缀
				'replace' => false, // 存在同名是否覆盖
				'hash' => true, // 是否生成hash编码
				'callback' => false 
		), // 检测文件是否存在回调函数，如果存在返回文件信息数组
		   
		// RBAC权限配置
		'USER_AUTH_ON' => true, // USER_AUTH_ON 是否需要认证
		'USER_AUTH_TYPE' => 1, // USER_AUTH_TYPE 认证类型
		'USER_AUTH_KEY' => 'authId', // USER_AUTH_KEY 认证识别号
		'REQUIRE_AUTH_MODULE' => '', // REQUIRE_AUTH_MODULE 需要认证模块
		'NOT_AUTH_MODULE' => 'Public', // NOT_AUTH_MODULE 无需认证模块
		'NOT_AUTH_ACTION' => 'sysinfo',
		'USER_AUTH_GATEWAY' => '/Admin/Login', // USER_AUTH_GATEWAY 认证网关
		'USER_AUTH_MODEL' => 'user', // 用户表
		                             // RBAC_DB_DSN 数据库连接DSN
		'RBAC_ROLE_TABLE' => 'my_role', // RBAC_ROLE_TABLE 角色表名称
		'RBAC_USER_TABLE' => 'my_role_user', // RBAC_USER_TABLE 用户表名称
		'RBAC_ACCESS_TABLE' => 'my_access', // RBAC_ACCESS_TABLE 权限表名称
		'RBAC_NODE_TABLE' => 'my_node', // RBAC_NODE_TABLE 节点表名称
		'ADMIN_AUTH_KEY' => 'administrator' ,
        'APP_SUB_DOMAIN_DEPLOY'   =>   1, // 开启子域名配置
        'APP_SUB_DOMAIN_RULES'    =>    array(
            'admin.xiaodai.app'  => 'Admin',  //Admin模块
            'www.xiaodai.app'  => 'Home',  // 主页模块
            'api.xiaodai.app'  => 'Api',  // Api接口模块
        )
);
// 加载自定义参数设置
$GLOBAL_CONFIG = APP_PATH . 'Common/Conf/setting.php';
if (file_exists ( $GLOBAL_CONFIG )) {
	$config1 = require $GLOBAL_CONFIG;
	return array_merge ( $config, $config1 );
} else {
	return $config;
}