<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo ($title); ?></title>
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

<link rel="stylesheet" type="text/css" href="/Public/newcss/css/base.css" />
<script src="/Public/newcss/js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newcss/js/mobileRsize.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css">
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

		.list-box {
			margin: 0.12rem 0.12rem 0;
		}

		.list-box>div {
			padding: 0.14rem 0.08rem;
			width: 94%;
			margin: 0 auto;
			box-sizing: border-box;
			position: relative;
			overflow: hidden;
		}

		.list-box>div:after {
			left: 0;
			content: "";
			position: absolute;
			bottom: 0;
			border-bottom: 1px solid #CCCCCC;
			width: 100%;
			-webkit-transform: scaley(.3);
			transform: scaley(.3);
		}

		.list-box>div:last-child:after {
			border: 0;
		}

		.list-top,
		.list-use {
			position: relative;
			overflow: hidden;
		}

		.list-top:after,
		.list-use:after {
			left: 0;
			content: "";
			position: absolute;
			bottom: 0;
			border-bottom: 1px solid #CCCCCC;
			width: 100%;
			-webkit-transform: scaley(.3);
			transform: scaley(.3);
		}

		.list-top span {
			float: right;
			padding: 0.01rem 0.1rem;
			border-radius: 1rem;
		}

		.list-top span.c0 {
			color: #ff5151;
			background: rgba(255, 81, 81, 0.3);
		}
		.list-top span.c1 {
			color: #ff5151;
			background: rgba(255, 81, 81, 0.3);
		}
		.list-top span.c2 {
			color: #ff5151;
			background: rgba(252, 165, 51, 0.3);
		}

		.list-top span.c3 {
			color: #fca533;
			background: rgba(252, 165, 51, 0.3);
		}

		.list-top span.c4 {
			color: #78cb5f;
			background: rgba(120, 203, 95, 0.3);
		}

		.list-use>div {
			float: left;
			width: 50%;
		}

		.list-last>div {
			float: left;
			width: 33.33%;
		}

		.list-use>div:first-child {
			text-align: left;
		}

		.list-use>div:last-child {
			text-align: center;
		}

		.list-use h1,
		.list-last h1 {
			font-size: 0.22rem;
			color: #333;
		}

		.list-use>div:first-child h1 {
			color: #E6BC85;
		}

		.list-use h4,
		.list-last h4 {
			font-weight: normal;
			color: #999;
		}
	</style>
</head>
<body style="background: #f5f5f5;">
<div class="vertical-top">
	我的贷款
</div>
<?php if(empty($loanlist)): ?><div style="position: absolute;width: 80%;height: 70%;margin-left: 10%; margin-top: 15%;text-align: center;font-size: 20px;">
		没有贷款订单<br/>
		<a style="position:relative;top:30px; font-size:14px;background-color:lightblue; padding: 1px 3px;border-radius: 3px;" href=<?php echo U('Index/menu');?>>申请贷款</a>
	</div>
	<?php else: ?>
	<?php if(is_array($loanlist)): $i = 0; $__LIST__ = $loanlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><section data-field="<?php echo ($vo["orderno"]); ?>" data-status="<?php echo ($vo["status"]); ?>" class="list-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.12rem;">
			<div class="list-top">
				单号：<?php echo ($vo["orderno"]); ?>
				<a href="<?php echo U('Member/repayment','roderno='.$vo['orderno']);?>" style="color: red">[点击查看]</a>
			<span class="c<?php echo ($vo['status']); ?>">
				<?php switch($vo["status"]): case "0": ?>待审核<?php break;?>
					<?php case "1": if($vo['shenhestatus'] == 0): ?><span onclick="yjfunc.myconfirm('<?php echo ($vo["refusereason"]); ?>')">查看拒绝理由</span>
							<?php else: ?>
							<?php if($vo['status1'] == 1): ?>等待放款
								<?php else: ?>
								审核通过，待确认<?php endif; endif; break;?>
					<?php case "2": ?>已放款<?php break;?>
					<?php case "3": ?>已逾期<?php break;?>
					<?php case "4": ?>已还款<?php break; endswitch;?>
			</span>


			</div>
			<div class="list-use">
				<div>
					<h1><?php echo (to_price($vo["damount"])); ?></h1>
					<h4>贷款金额(元)</h4>
				</div>
				<div>
					<h1><?php echo (to_price($vo["interest"])); ?></h1>
					<h4>服务费</h4>
				</div>
			</div>
			<div class="list-last">
				<div>
					<h1 style="font-size: 0.14rem;"><?php echo ($vo["deadline"]); ?></h1>
					<h4>到期时间</h4>
				</div>
				<div>
					<h1 style="font-size: 0.14rem;">

						<?php switch($vo["status"]): case "0": ?>待审核<?php break;?>
							<?php case "1": ?>待放款<?php break;?>
							<?php case "2": $overdue=intval(diffBetweenTwoDays(date('Y-m-d H:i:s'),$vo['deadline'])); ?>
								<?php if(($vo['deadline'] < date('Y-m-d H:i:s')) and ($vo['status'] >= 2) and ($vo['status'] < 4)): ?>已逾期<?php endif; ?>
								<?php echo ($overdue); ?>天<?php break;?>
							<?php case "4": if($vo['refundtime'] < $vo['deadline']): ?>[正常还款]
									<?php else: ?>
									[逾期还款]<?php endif; break; endswitch;?>

					</h1>
					<h4>剩余还款日(天)</h4>
				</div>
				<div>
					<h1 style="font-size: 0.14rem;color: #FF5151;">本金的<?php echo ($vo["overduefee"]); ?>%</h1>
					<h4>逾期利息</h4>
				</div>
			</div>
		</section><?php endforeach; endif; else: echo "" ;endif; endif; ?>

<style type="text/css">
	.drawerfoot{
		display: none;
	}

	.list-line {
		position: fixed;
		height: 4rem;
		background: #fff;
		width: 100%;
		bottom: 0;
		left: 0;
		padding: 0.12rem;
		box-sizing: border-box;
		overflow-y: scroll;
		z-index: 99;
	}

	.Drawer-top {
		background: #E6BC85;
		color: #FFFFFF;
		padding: 0.12rem;
		font-size: 0.14rem;
		font-weight: bold;
		position: fixed;
		bottom: 4rem;
		border-radius: 0.2rem 0.2rem 0 0;
		box-sizing: border-box;
		width: 100%;
	}

	.down-line {
		height: 100%;
		width: 1px;
		background: #d6cec5;
		position: absolute;
		left: 0;
		top: 0;
	}

	.list-line li {
		position: relative;
		padding: 0 0.12rem;
	}

	.list-line li>div {
		position: relative;
		padding: 0.12rem;
		padding-right: 0;
		overflow: hidden;
	}

	.list-line li:before {
		content: "";
		height: 10px;
		width: 10px;
		border-radius: 50%;
		background: #f4c89d;
		position: absolute;
		left: 0.12rem;
		margin-left: -5px;
		z-index: 99;
		margin-top: -5px;
		top: 50%;
	}

	.list-line li h1,
	.list-line li h2 {
		font-size: 0.12rem;
		font-weight: normal;
		color: #999;
	}

	.list-line li h1 {
		float: left;
	}

	.list-line li h2 {
		float: right;
		text-align: right;
	}
</style>
<section class="drawerfoot">
	<div class="Drawer-top">
		单号：<span id="orderno"></span>
		<input onclick="refundnow();" style="display:none;background-color: #00a0e9;border: none;color:white;padding: 1px 5px;border-radius: 3px;" id="refund" type="button" value="立即还款"/>
		<span class="drawer-close" style="float: right;margin-right: 0.12rem;"><img style="width: 0.12rem;" src="/Public/newcss/img/x.png"/></span>
	</div>
	<div class="drawerpadding" style="padding-top: 3.4rem;"></div>
	<div class="list-line" style="text-align: center;">
		<img id="loading" style="display: none;" src="/Public/Home/images/loading.gif"/>
		<div style="display: none;" id="info">
			<div style="margin-bottom: 10px;">
				<div style="width: 100%;position: relative; border-bottom: 1px dashed lightgray; min-height: 25px;line-height: 25px;">
					<div style="position: relative;width: 50%;float: left;">贷款金额：<span id="damunt">1000</span></div>
					<div style="position: relative;width: 50%;float: left;">服务费：<span id="interest">2000</span></div>

				</div>
				<div style="width: 100%;position: relative; border-bottom: 1px dashed lightgray; min-height: 25px;line-height: 25px;">
					<div style="position: relative;width: 50%;float: left;">还款金额：<span id="refundamount">2000</span></div>
					<div style="position: relative;width: 50%;float: left;">剩余天数：<span id="days">1000</span></div>
				</div>
				<div style="width: 100%;position: relative; border-bottom: 1px dashed lightgray; min-height: 25px;line-height: 25px;">

					<div style="position: relative;width: 50%;float: left;">状态：<span id="status">2000</span></div>
				</div>
				<div style="width: 100%;position: relative; border-bottom: 1px dashed lightgray; min-height: 25px;line-height: 25px;">
					<div style="position: relative;width: 100%;float: left;">申请日期：<span id="addtime">2018-01-01 12:12:12</span></div>
				</div>
				<div style="width: 100%;position: relative; border-bottom: 1px dashed lightgray; min-height: 25px;line-height: 25px;">
					<div style="position: relative;width: 100%;float: left;">放款日期：<span id="paiedtime">2018-01-01 12:12:12</span></div>
				</div>
				<div style="width: 100%;position: relative; border-bottom: 1px dashed lightgray; min-height: 25px;line-height: 25px;">
					<div style="position: relative;width: 100%;float: left;">还款日期：<span id="refundtime">2018-01-01 12:12:12</span></div>
				</div>
			</div>

			<div style="margin-top: 10px;">
				<div style="width: 100%;position: relative; border-bottom: 1px dashed lightgray; min-height: 25px;line-height: 25px;">
					<div style="position: relative;width: 100%;float: left;font-size: 16px;font-weight: 600;">贷款历程</span></div>
				</div>
			</div>
			<ul id="step">

			</ul>
		</div>
	</div>
</section>
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
<div class="bot-btn">
    <button>
        <a style="color: #fff;" href="<?php echo U('Index/menu');?>">贷款申请</a>
    </button>
    <button>
        <a style="color: #fff;" href="<?php echo U('Member/personal');?>">我的贷款</a>
    </button>
</div>


<script src="/Public/newcss/js/myfunc-1.0.0.js"></script>
<script type="text/javascript">

	/*function refundnow(){
		yjfunc.myconfirm('确认还款吗？',['不还','立即还'], function (e) {
			if(e==1){

				var orderno=$("#orderno").html();
				if($.trim("orderno")==""){
					yjfunc.mytoast("获取订单信息失败，请稍后再试");return;
				}

				$.ajax({
					url:"/Member/refundloan.html",
					data:{orderno:orderno},
					type:"POST",
					success: function (data) {
						if(data.status==1){
							window.location.href="/Pay/order/orderno/"+orderno+".html";
						}else{
							yjfunc.myconfirm(data.info);
						}
					}
				})
			}
		});
	}*/

	/*$(function() {
		$(".list-box").click(function() {
			$(".drawerfoot").show();
			var orderno=$(this).attr('data-field');
			var status=$(this).attr('data-status');
			$("#loading").show();
			$("#info").hide();
			$.ajax({
				url:"/Member/getorderinfo.html",
				data:{orderno:orderno},
				type:"POST",
				success: function (data) {
					var statusname="";
					switch(parseInt(data.status)){
						case 0:
							statusname='待审核';
							break;
						case 1:
							statusname='待放款';
							break;
						case 2:
							statusname='已放款';
							break;
						case 3:
							statusname='已逾期';
							break;
						case 4:
							statusname='已还款';
							break;
						default:
							break;
					}
					$("#orderno").html(orderno);
					$("#status").html(statusname);
					$("#damunt").html(data.damount);
					$("#interest").html(data.interest);
					$("#refundamount").html(data.refundamount);
					$("#days").html(data.days);
					$("#addtime").html(data.addtime);
					$("#paiedtime").html(data.paiedtime);
					$("#refundtime").html(data.refundtime);
					$("#step").html(data.info);
					if(parseInt(status)==2){
						$("#refund").show();
					}
					$("#loading").hide();
					$("#info").show();

				}
			})
		});
		$(".drawer-close").click(function() {
			$(".drawerfoot").hide();
		})
	})*/
</script>
</body>
</html>