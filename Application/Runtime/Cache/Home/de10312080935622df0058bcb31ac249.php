<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>优惠卷</title>
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
			.coupon-list {
				padding: 0 0.12rem;
			}
			
			.coupon-list li {
				margin: 0.12rem 0;
				background: #fff;
				padding: 0.32rem 0.12rem 0.12rem;
				box-sizing: border-box;
				border-radius: 0.2rem;
				position: relative;
				box-shadow: 0px 0.01rem 0.01rem 0px rgba(0, 0, 0, 0.1);
				overflow: hidden;
			}
			
			.coupon-list li .topname {
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
			
			.coupon-Amount {
				color: #E6BC85;
				font-family: arial;
				font-size: 0.14rem;
				float: left;
			}
			
			.coupon-Amount span:nth-child(2) {
				font-size: 0.4rem;
				margin-left: 0.1rem;
				font-weight: bold;
			}
			
			.coupon-time {
				float: right;
				font-size: 0.14rem;
				margin-top: 0.06rem;
				color: #999;
			}
			
			.coupon-time h6 {
				font-weight: normal;
				font-size: 0.14rem;
				text-align: right;
			}
			
			.over-time .topname {
				background: #dbdbdb !important;
			}
			
			.over-time .topname:after {
				content: "";
				right: 0.12rem;
				top: -0.12rem;
				position: absolute;
				background: url(/Public/newcss1/img/overtime.png) no-repeat center / cover;
				height: 0.6rem;
				width: 0.6rem;
			}
			
			.over-time .coupon-Amount {
				color: #dbdbdb;
			}
			
			.over-time .coupon-time {
				color: #dbdbdb;
			}
		</style>
	</head>

	<body style="background: #f5f5f5;">
	<ul class="coupon-list">
		<?php if(empty($rows)): ?><li style="text-align:center;padding: 0.12rem;">

					目前没有优惠劵

			</li><?php endif; ?>
	<?php if(is_array($rows)): $i = 0; $__LIST__ = $rows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo['status'] == 0)and($vo['timeto'] > date('Y-m-d H:i:s')) ): ?><li>
				<div class="topname">
					<?php echo ($vo['title']); ?>
				</div>
				<div class="coupon-Amount">
					<span>￥</span><span><?php echo ($vo['amount']); ?></span>
				</div>
				<div class="coupon-time">
					<h6>有效期</h6>
					<h6><?php echo substr($vo['timeto'],-19,10);?></h6>
				</div>
			</li>

		<?php else: ?>
			<li class="over-time">
				<div class="topname">
					<?php echo ($vo['title']); ?>
				</div>
				<div class="coupon-Amount">
					<span>￥</span><span><?php echo ($vo['amount']); ?></span>
				</div>
				<div class="coupon-time">
					<h6>有效期</h6>
					<h6><?php echo substr($vo['timeto'],-19,10);?></h6>
				</div>
			</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</ul>

		
	</body>

</html>