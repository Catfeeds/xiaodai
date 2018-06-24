<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>银行卡</title>
		<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--百度禁止转码 -->
<title>登录</title>
<link rel="stylesheet" type="text/css" href="/Public/newcss1/css/base.css" />
<script src="/Public/newcss1/js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newcss1/js/mobileRsize.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newcss1/js/myfunc-1.0.0.js" type="text/javascript" charset="utf-8"></script>
		<style type="text/css">
			.card-list {
				padding: 0 0.12rem;
			}
			
			.card-list li {
				margin: 0.12rem 0;
				background: #fff;
				padding: 0.32rem 0.12rem 0.12rem;
				box-sizing: border-box;
				border-radius: 0.2rem;
				position: relative;
				box-shadow: 0px 0.01rem 0.01rem 0px rgba(0, 0, 0, 0.1);
				overflow: hidden;
			}
			
			.card-list li .topname {
				background: #E6BC85;
				width: 100%;
				height: 0.3rem;
				line-height: 0.3rem;
				padding-left: 0.12rem;
				font-size: 0.14rem;
				color: #fff;
				left: 0;
				top: 0;
				position: absolute;
				box-sizing: border-box;
			}
			
			.card-company {
				float: left;
				font-size: 0.14rem;
				margin-top: 0.06rem;
				color: #999;
			}
			
			.card-company h6 {
				font-weight: normal;
				font-size: 0.14rem;
			}
			.btn-box{
				padding: 0.12rem;
			}
			.addcard {
				box-shadow: 0px 0.01rem 0.01rem 0px rgba(0, 0, 0, 0.1);
				padding: 0.12rem;
				border-radius: 0.2rem;
				background: #FFFFFF;
				text-align: center;
				color: #999;
				width: 100%;
				border: 0;
				box-sizing: border-box;
			}
			
		</style>
	</head>

	<body style="background: #f5f5f5;">
	<?php if(empty($row)): ?><p>暂无银行卡</p>
		<?php else: ?>
		<ul class="card-list">
			<li>
				<div class="topname">
					<?php echo ($row['username']); ?>
				</div>
				<div class="card-company">
					<h6>储蓄卡</h6>
					<h6><?php echo formatBankCardNo($row['bankno']);?></h6>
				</div>
			</li>
		</ul><?php endif; ?>



	</body>

</html>