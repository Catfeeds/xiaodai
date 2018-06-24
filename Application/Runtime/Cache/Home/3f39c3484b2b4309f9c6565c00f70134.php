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


			.input-box {
				margin: 0.12rem;
				border-radius: 0.2rem;
				background: #fff;
				box-sizing: border-box;
			}

			.input-box li {
				/*padding: 0.14rem 0.08rem;*/
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
			.vertical-top {
				margin: 0.12rem 0.12rem 0;
				padding-left: 0.12rem;
				position: relative;
				font-weight: bold;
				color: #999;
				font-size: 0.14rem;
			}

			.vertical-top:before {
				content: "";
				display: block;
				background: #E6BC85;
				width: 0.04rem;
				position: absolute;
				left: 0;
				top: 0;
				height: 100%;
			}
			.repayment-box{
				padding: 0.12rem 0.12rem 0;
				box-sizing: border-box;
			}
			.repayment-box p{
				font-size: 0.12rem;
				padding: 0.1rem 0;
				position: relative;
			}
			.repayment-box p:after {
				left: 0;
				content: "";
				position: absolute;
				bottom: 0;
				border-bottom: 1px solid #CCCCCC;
				width: 100%;
				-webkit-transform: scaley(.3);
				transform: scaley(.3);
			}
			.repayment-box h1{
				font-size: 0.24rem;
				color: #E6BC85;
				margin-bottom: 0.12rem;
			}
			.repayment-box p span{
				margin-left: 0.2rem;
			}
			.repayment-box p span:first-child{
				margin: 0;
			}
			.nametit{
				font-style: normal;
				margin-right: 0.1rem;
				color: #666;

			}
			.repay-others{
				font-size: 0.1rem;
			}
			.repay-others span{
				margin-right: 0.2rem;
			}
			span i{
				font-style: normal;
			}
			.repay-others span .c1 {
				color: #ccc;
			}
			.repay-time{
				margin-top: 0.12rem;
			}
			.repay-time div{
				padding: 0.05rem 0;
				font-size: 0.12rem;
			}
		</style>
	</head>
	<body style="background: #f5f5f5;">
		<div class="name-top">
			<div class="name-box">
				<img src="/Public/newcss1/img/head.jpg" />
				<h1 class="user-name"><?php echo ($member['username']); ?></h1>
			</div>
			<div class="repayment-box">
				<h4 style="color: #E6BC85;margin: 0.04rem 0;font-size: 0.12rem;">还款金额</h4>
				
				<h1 id="money" num="<?php echo ($result['refundamount']); ?>" roder_no="<?php echo ($roderno); ?>"><?php echo ($result['refundamount']); ?></h1>
				<p class="repay-others"><span><i class="nametit">审批金额</i><b class="c2"><?php echo ($result['damount']); ?></b></span>
				<span><i class="nametit">服务费</i><b class="c2"><?php echo ($result['interest']); ?></b></span></span>
				</p>
				<p class="repay-others"><span><i class="nametit">到账金额</i><b class="c2"><?php echo ($result['daozhang']); ?></b>
				</span>
				</p>
				<p class="repay-others"><span><i class="nametit">剩余天数</i><b class="c2"><?php echo ($result['days']); ?>天</b>
				</span><span>
						<i class="nametit">状态</i>
						
						
						<?php switch($result["status"]): case "0": ?><i class="c2">待审核</i><?php break;?>
							<?php case "1": if($result['shenhestatus'] == 0): ?><i class="c2" onclick="yjfunc.myconfirm('<?php echo ($result["refusereason"]); ?>')">查看拒绝理由</i>
								<?php else: ?>
									<?php if($result['status1'] == 1): ?><i class="c2">已确认，等待放款</i>
										<?php else: ?>
										
									<i class="c2">审核通过，待确认</i><?php endif; endif; break;?>
							<?php case "2": ?><i class="c2">已放款</i><?php break;?>
							<?php case "3": ?><i class="c2">已逾期</i><?php break;?>
							<?php case "4": ?><i class="c2">已还款</i><?php break; endswitch;?>
						
						
					</span></p>

				<div class="repay-time">
					<div class="repay-others"><span><i class="nametit">申请日期</i><i class="c1"><?php echo ($result['addtime']); ?></i></span></div>
					<div class="repay-others"><span><i class="nametit">还款日期</i><i class="c1"><?php echo ($result['deadline']); ?></i></span></div>
				</div>
			</div>

		</div>
		<div class="vertical-top">选择优惠券</div>

		<div class="input-box">
				<li>
					<a>
						<div class="input-box">
							<span>选择优惠券</span>
							<p class="input-p">
								<select  onchange="coupon()" id='a' name="" required="" style="color: #999;">
									<option class="tab" value="" style="color: #999 !important;"selected>不使用优惠券</option>
									<?php if(is_array($coupons)): $i = 0; $__LIST__ = $coupons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option  value="<?php echo ($vo['no']); ?>"><?php echo ($vo['title']); ?> 优惠<?php echo ($vo['amount']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</p >
						</div>
					</a>
				</li>
		</div>


		<style type="text/css">
			.bot-btn {
				padding: 0.1rem 0.12rem;
				background: #fff;
				position: fixed;
				bottom: 0;
				left: 0;
				width: 100%;
				box-sizing: border-box;
				border-radius: 0.25rem 0.25rem 0 0;
				box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.1);
			}

			.bot-btn button {
				background: #e6bc85;
				height: 0.4rem;
				border-radius: 0.25rem;
				display: block;
				color: #fff;
				font-size: 0.18rem;
				text-align: center;
				font-weight: bold;
				border: 0;
				width: 48%;
				float: left;
			}
			.bot-btn button:nth-child(2){
				margin-left: 4%;
			}
			.footpadding {
				padding-top: 0.5rem;
			}
		</style>

		<div class="footpadding"></div>
		<?php switch($result['status']): case "0": ?><div class="bot-btn">
				<button class="b-btn4" style="width: 100%">
					<a style="color: #fff; width: 100%;">等待审核</a>
				</button>
				</div><?php break;?>
			<?php case "1": if($result['shenhestatus'] == 0): ?><div onclick="yjfunc.myconfirm('<?php echo ($result["refusereason"]); ?>')" class="bot-btn" >
						<button class="b-btn4" style="width: 100%">
							<a style="color: #fff; width: 100%;">查看拒绝理由</a>
						</button>
					</div>
					<?php else: ?>
					<?php if($result['status1'] == 1): ?><div class="bot-btn" >
							<button class="b-btn3" style="width: 100%">
								<a style="color: #fff; width: 100%;">等待放款</a>
							</button>
						</div>
						<?php else: ?>
						<div class="bot-btn" >
							<button class="b-btn3" style="width: 100%">
								<a style="color: #fff; width: 100%;">确认贷款</a>
							</button>
						</div><?php endif; endif; break;?>
			<?php case "4": ?><div class="bot-btn">
				<button class="b-btn4" style="width: 100%">
					<a style="color: #fff; width: 100%;">已还款</a>
				</button>
				</div><?php break;?>
			<?php default: ?>
			<div class="bot-btn">
				<button class="b-btn1">
					<a style="color: #fff;">申请延期</a>
				</button>
				<button class="b-btn2">
					<a style="color: #fff;">确认还款</a>
				</button>
			</div><?php endswitch;?>

		<style type="text/css">
			.drawerfoot {
				display: none;
			}

			.drawerfoot .mask {
				position: fixed;
				height: 2rem;
				background: #fff;
				width: 100%;
				bottom: 0;
				left: 0;
				padding: 0.12rem;
				box-sizing: border-box;
				z-index: 99;
			}

			.drawerfoot .Drawer-top {
				background: #E6BC85;
				color: #FFFFFF;
				padding: 0.12rem;
				font-size: 0.14rem;
				font-weight: bold;
				position: fixed;
				bottom: 2rem;
				border-radius: 0.2rem 0.2rem 0 0;
				box-sizing: border-box;
				width: 100%;
			}

			.drawerfoot .input-box {
				padding: 0.12rem;
				background: #fafafa;
				margin: 0.12rem -0.12rem;
				border-bottom: 1px solid #e5e5e5;
				border-top: 1px solid #e5e5e5;
			}

			.input-box span {
				display: inline-block;
				text-align: center;
				color: #000;
				float: left;
				width: 0.9rem;
				text-align: left;
			}

			.drawerfoot .input-box .input-p {
				margin-left: 0.9rem;
			}

			 .drawerfoot .input-box input {
				border: 0;
				width: 100%;
				background: #FAFAFA;
			}

			.drawerfoot .Delay-ok {
				background: #e6bc85;
				height: 0.4rem;
				border-radius: 0.25rem;
				width: 100%;
				display: block;
				color: #fff;
				font-size: 0.16rem;
				line-height: 0.4rem;
				text-align: center;
				font-weight: bold;
				border: 0;
				margin-top: 0.3rem;
			}

		</style>
		<section class="drawerfoot" style="display: none;">
			<div class="Drawer-top">
				单号：<?php echo ($roderno); ?>
				<span class="drawer-close" style="float: right;margin-right: 0.12rem;"><img style="width: 0.12rem;" src="/Public/newcss1/img/x.png"></span>
			</div>
			<div class="drawerpadding" style="padding-top: 2.4rem;"></div>
			<div class="mask">
				<h1 style="text-align: center;color: #333;font-size: 0.16rem;">
					申请延期
				</h1>
				<h2 style="text-align: center;color: #999;font-size: 0.12rem;margin-top: 0.06rem;font-weight: normal;">(如果不能在期限时间内还款可以申请延期)</h2>
				<div style="text-align: center">
					延期时间:
					<span id="days">3天</span>
					服务费:
					<span id="service">300元</span>

				</div>
				<button class="Delay-ok">
					确认申请
				</button>
			</div>
		</section>


			<style>
				.input-box span {
					display: inline-block;
					text-align: center;
					color: #000;
					float: left;
					width: 0.9rem;
					text-align: left;
				}

				.input-box .input-p {
					margin-left: 0.9rem;
				}
				.input-box select{
					width: 100%;
					border: 0;
				}
				.centermask-bg {
					display: none;
					background: rgba(0, 0, 0, .3);
					width: 100%;
					z-index: 99;
					position: fixed;
					top: 0;
					padding: 0.12rem;
				}

				.centermask {
					position: fixed;
					height: 1rem;
					background: #fff;
					width: 90%;
					margin:0 5%;
					top: 50%;
					margin-top: -0.5rem;
					left: 0;
					padding: 0.12rem;
					box-sizing: border-box;
					z-index: 99;
					border-radius: 0.2rem;
				}
				.center-btn-box{
					width: 100%;
					height: 0.44rem;
					position: absolute;
					bottom: 0;
					left: 0;
					border-radius: 0 0 0.2rem 0.2rem;
					overflow: hidden;
				}
				.center-btn-box:after{
					left: 0;
					content: "";
					position: absolute;
					top: 0;
					border-bottom: 1px solid #CCCCCC;
					width: 100%;
					-webkit-transform: scaley(.4);
					transform: scaley(.4);
				}
				.center-btn-box button{
					float: left;
					width: 50%;
					border: 0;
					background: none;
					height: 0.44rem;
					font-size: 0.14rem;
					position: relative;
				}
				.center-btn-box button:first-child:after{
					right: 0;
					content: "";
					position: absolute;
					top: 0;
					border-right: 1px solid #CCCCCC;
					height: 100%;
					-webkit-transform: scalex(.4);
					transform: scalex(.4);
				}
			</style>

			<div class="centermask-bg">
				<div class="centermask">
					<h1 style="text-align: center;font-size: 0.16rem;">确定还款？</h1>
					<div class="center-btn-box">
						<button  id='ok' style="color: #FF5151;">确定</button>
						<button class="cancel" style="color: #3F93E1;">取消</button>
					</div>
				</div>
			</div>


	</body>
	<script type="text/javascript">
		$(document).ready(function() {
			var h = $(window).height();
			$(".centermask-bg").css(
					"height", h
			)
		})
		$(function() {

			$(".cancel").click(function() {

				$(".centermask-bg").hide();
			})
		})


		$(function() {
			$(".b-btn1").click(function() {
				$(".drawerfoot").show();
			})
			$(".drawer-close").click(function() {
				$(".drawerfoot").hide();
			})
			$(".b-btn2").click(function() {
				$(".centermask-bg").show();
			})

		})

	</script>
	<script type="text/javascript">
		//优惠卷
		function coupon(){
			var no = $('#a').val();
			var money=$('#money').attr('num');
			$.ajax({
				url:"/Member/deduct.html",
				data:{no:no},
				type:"POST",
				success: function (data) {
					$('#money').html((money-0)-(data-0));
				}
			})
		}

		$('#ok').click(function(){
			var orderno=$("#money").attr('roder_no');
			var no = $('#a').val();
				$.ajax({
					url:"/Member/refundloan.html",
					data:{orderno:orderno,no:no},
					type:"POST",
					success: function (data) {
						if(data.status==1){
							window.location.href="/Pay/order/orderno/"+orderno+".html";
						}else{
							yjfunc.myconfirm(data.info);
						}
					}
				})

		})
		$('.b-btn3').click(function(){

			var orderno=$("#money").attr('roder_no');
			$.ajax({
				url:"/Member/shenqing.html",
				data:{orderno:orderno},
				type:"POST",
				success: function (data) {
					if(data.status==1){
						yjfunc.myconfirm(data.info);
						$('.b-btn3').attr("disabled", true)
						$('.b-btn3').html('等待放款');
					}else{
						yjfunc.myconfirm(data.info);
					}
				}
			})


		});



		$('.Delay-ok').click(function(){

			var orderno=$("#money").attr('roder_no');
			$.ajax({
				url:"/Member/delay.html",
				data:{orderno:orderno},
				type:"POST",
				success: function (data) {
					if(data.status==1){
						window.location.href="/Pay/delay/orderno/"+data.info+".html";
					}else{
						yjfunc.myconfirm(data.info);
					}
				}
			})

		})

	</script>


</html>