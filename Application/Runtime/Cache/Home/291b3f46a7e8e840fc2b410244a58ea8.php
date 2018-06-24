<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>个人中心</title>
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
			.name-top {
				margin: 0.12rem;
				border-radius: 0.2rem;
				padding: 0.12rem;
				background: #fff;
				box-sizing: border-box;
			}
			
			.name-box {
				padding: 0.12rem;
				border-radius: 0.04rem;
				text-align: center;
			}
			
			.name-box img {
				border-radius: 50%;
				width: 0.4rem;
				margin-bottom: 0.12rem;
			}
			
			.user-name {
				font-size: 0.14rem;
			}
			
			.num-width ul {
				overflow: hidden;
				padding: 0.12rem;
				box-sizing: border-box;
			}
			
			.num-width ul li {
				width: 50%;
				float: left;
				text-align: center;
				position: relative;
				font-size: 0.12rem;
			}
			
			.num-width ul li img {
				width: 0.4rem;
			}
			
			.num-width ul li p:first-child {
				/*margin-bottom: 0.06rem;*/
			}
			
			.num-width ul li:first-child:after {
				right: 0;
				content: "";
				position: absolute;
				top: 0;
				border-right: 1px solid #CCCCCC;
				height: 100%;
				-webkit-transform: scalex(.3);
				transform: scalex(.3);
			}
			
			.input-box {
				margin: 0.12rem;
				border-radius: 0.2rem;
				background: #fff;
				box-sizing: border-box;
			}
			
			.input-box li {
				padding: 0.14rem 0.08rem;
				width: 94%;
				margin: 0 auto;
				box-sizing: border-box;
				position: relative;
				overflow: hidden;
			}
			
			.input-box li:after {
				left: 0;
				content: "";
				position: absolute;
				bottom: 0;
				border-bottom: 1px solid #CCCCCC;
				width: 100%;
				-webkit-transform: scaley(.3);
				transform: scaley(.3);
			}
			
			.input-box li:last-child:after {
				border: 0;
			}
			
			.input-box span {
				display: inline-block;
				text-align: center;
				color: #000;
				float: left;
				width: 0.18rem;
				text-align: left;
			}
			
			.input-box span img {
				width: 100%;
				vertical-align: top;
			}
			
			.input-box .input-p {
				margin-left: 0.28rem;
				line-height: 0.18rem;
				overflow: hidden;
				font-size: 0.12rem;
			}
			
			.input-p .putname {
				font-style: normal;
				float: left;
			}
			
		</style>
	</head>

	<body style="background: #f5f5f5;">
		<div class="name-top">
			<div class="name-box">
				<img src="/Public/newcss1/img/head.jpg"/>
				<h1 class="user-name"><?php echo ($row['username']); ?></h1>
			</div>
			<div class="num-width">
				<ul>
					<li>
						<a href="<?php echo U('Member/bkcard');?>">
							<p>
								<img src="/Public/newcss1/img/Bank card.png" />
							</p>
							<p>
								银行卡
							</p>
						</a>
					</li>
					<li>
						<a href="<?php echo U('Member/coupon');?>">
							<p>
								<img src="/Public/newcss1/img/Coupons.png" />
							</p>
							<p>
								优惠券
							</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="input-box">
			<ul>
				<li>
					<a href="<?php echo U('Content/view','id=5');?>">
						<div>
							<span><img src="/Public/newcss1/img/About us.png"/></span>
							<p class="input-p">
								<i class="putname">关于我们</i>
							</p>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo U('Member/index');?>">
						<div>
							<span><img src="/Public/newcss1/img/Order.png"/></span>
							<p class="input-p">
								<i class="putname">我的订单</i>
							</p>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo ($rs['url']); ?>">
						<div>
							<span><img src="/Public/newcss1/img/contract.png"/></span>
							<p class="input-p">
								<i class="putname">我的合同</i>
							</p>
						</div>
					</a>
				</li>
			</ul>
		</div>
	</body>

</html>