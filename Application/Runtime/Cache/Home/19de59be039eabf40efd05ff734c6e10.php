<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
<title>登录</title>
<link rel="stylesheet" type="text/css" href="/Public/newcss1/css/base.css" />
<script src="/Public/newcss1/js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newcss1/js/mobileRsize.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newcss1/js/myfunc-1.0.0.js" type="text/javascript" charset="utf-8"></script>
		<style type="text/css">
			input::-webkit-input-placeholder {
				color: #999;
			}
			
			input::-moz-placeholder {
				/* Mozilla Firefox 19+ */
				color: #999;
			}
			
			input:-moz-placeholder {
				/* Mozilla Firefox 4 to 18 */
				color: #999;
			}
			
			input:-ms-input-placeholder {
				/* Internet Explorer 10-11 */
				color: #999;
			}
			
			.input-box>div {
				padding: 0.14rem 0.08rem;
				width: 94%;
				margin: 0 auto;
				box-sizing: border-box;
				position: relative;
				overflow: hidden;
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
			
			.input-box>div:after {
				left: 0;
				content: "";
				position: absolute;
				bottom: 0;
				border-bottom: 1px solid #CCCCCC;
				width: 100%;
				-webkit-transform: scaley(.3);
				transform: scaley(.3);
			}
			
			.input-box>div:last-child:after {
				border: 0;
			}
			
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
			
			.input-box input {
				border: 0;
				width: 100%;
			}
			
			.price,
			.last-time {
				text-align: right;
				font-weight: bold;
			}
			.top-gold>div{
				width: 50%;
				float: left;
				position: relative;
			}
			.top-gold>div:after{
				right: 0;
				content: "";
				position: absolute;
				top: 0;
				width: 1px;
				background: #FFFFFF;
				height: 100%;
				-webkit-transform: scalex(.4);
				transform: scalex(.4);
			}
			.top-gold>div:last-child:after{
				width: 0px;
			}
			.top-gold h1{
				text-align: center;
				font-size: 0.2rem;
			}
			.top-gold h2{
				font-weight: normal;
				font-size: 0.12rem;
				text-align: center;
				opacity: 0.7;
			}
		</style>
	</head>

	<body style="background: #f5f5f5;">
		<section class="top-gold" style="background: #E6BC85;margin: 0.12rem;padding: 0.12rem;border-radius: 0.2rem;color: #fff;overflow: hidden;">
			<div class="">
				<h1>500-10000元</h1>
				<h2>额度范围</h2>
			</div>
			<div class="">
				<h1>50-60天</h1>
				<h2>借款期限</h2>
			</div>
		</section>
		<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
            <div>
				<span>选择产品</span>
				<p class="input-p">
					<select onchange="getloandays($(this).val())" name="productid" required="" style="color: #999;background: #fff;width: 100%;border: 0;">
						<option class="tab" value="" style="color: #999 !important;" disabled selected>选择贷款的产品</option>
						<?php if(is_array($product)): $i = 0; $__LIST__ = $product;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</p>
			</div>
			<?php if(is_array($product)): $i = 0; $__LIST__ = $product;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="line-height: 30px;display: none;" class="productdetail<?php echo ($vo["id"]); ?> detail">
					贷款额度：&yen;<?php echo ($vo["limitstart"]); ?>-<?php echo ($vo["limitend"]); ?><br/>
					贷款利率：<?php echo ($vo["interest"]); ?>%<br/>
					逾期费：<?php echo ($vo["overdue"]); ?>%/天

				</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<!--<div>
				<span>贷款金额</span>
				<p class="input-p">
					<input  onchange="setfeeanddeadline()" type="number" name="amount" id="amount" value="" placeholder="请输入贷款金额" />
				</p>
			</div>-->
			<div>
				<span>贷款时间</span>
				<p class="input-p">
					<select onchange="setfeeanddeadline()" name="days" required="" style="color: #999;background: #fff;width: 100%;border: 0;">
						<option class="tab" value="" style="color: #999 !important;" disabled selected>选择贷款的期限</option>
					</select>
				</p>
			</div>
		</section>

		<div class="vertical-top">
			贷款费用
		</div>
		<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<!--<div>
				<span>贷款手续费</span>
				<p class="price interestrate">

				</p>
			</div>-->
			<div>
				<span>贷款利息</span>
				<p class="price interest" >

				</p>
			</div>
		</section>
		<section class="input-box"  style="margin: 0.12rem;background: #fff;border-radius:0.25rem ;font-size: 0.14rem;">
			<div>
				<span>最晚还款时间</span>
				<p class="last-time deadline">

				</p>
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
				height: 0.5rem;
				border-radius: 0.25rem;
				width: 100%;
				display: block;
				color: #fff;
				font-size: 0.2rem;
				line-height: 0.5rem;
				text-align: center;
				font-weight: bold;
				border:0;
			}
			
			.footpadding {
				padding-top: 0.5rem;
			}
		</style>
		<div class="footpadding"></div>
		<div class="bot-btn">
			<button onclick="commitapply()">
				确认申请
			</button>
		</div>

	</body>
	<script type="text/javascript ">


		function checkmember(){
			var canapply='<?php echo ($canapply); ?>';
			if(parseInt(canapply)==0){
				yjfunc.myconfirm("认证资料未提交完成，请先提交认证资料",["ok"], function (e) {
					if(e==0){
						window.location.href="/Index/menu.html";
					}else{
						window.location.href="/Index/menu.html";
					}
				})
			}
		}

		checkmember();


		function commitapply(){

			var productid=$("select[name='productid']").val();
			//var amount=$("#amount").val();
			var days=$("select[name='days']").val();



			/*if($.trim(amount)==""||$.trim(amount)==null){
				yjfunc.mytoast("请输入贷款金额");return;
			}*/
			if($.trim(days)==""||$.trim(days)==null){
				yjfunc.mytoast("请输入贷款期限");return;
			}

			$(".bot-btn button").text("提交中。。。");
			$(".bot-btn button").attr("disabled","disabled");

			$.ajax({
				url:"/Member/subapplyloan.html",
				data:{productid:productid,days:days},
				type:"POST",
				success: function (data) {
					if(data.status==1){
						yjfunc.myconfirm(data.info,["ok"], function (e) {
							if(e==0){
								window.location.href="/Index/menu.html";
							}
						})
					}else{
						yjfunc.myconfirm(data.info);
						$(".bot-btn button").text("确认申请");
						$(".bot-btn button").removeAttr("disabled");
					}
				}
			})

		}
		function getloandays(id){
			$.ajax({
				url:"/Member/getapplyloan.html",
				data:{id:id},
				type:"POST",
				success: function (data) {
					$("select[name='days']").append(data);
					$(".productdetail"+id).show().siblings(".detail").hide();
					setfeeanddeadline();
				}
			})
		}

		function setfeeanddeadline(day){
			var productid=$("select[name='productid']").val();
			//var amount=$("input[name='amount']").val();
			var day= $("select[name='days']").val();
			if($.trim(productid)==""||$.trim(productid)==null){
				return;
			}
			/*if($.trim(amount)==""||$.trim(amount)==null){
				return;
			}*/
			if($.trim(day)==""||$.trim(day)==null){
				return;
			}
			$.ajax({
				url:"/Member/setfeeanddeadline.html",
				data:{day:day,productid:productid},
				type:"POST",
				success: function (data) {
					if(data.status==1){
						//$(".interestrate").html('待确定');
						$(".interest").html('待确定');
						$(".deadline").html(data.deadline);
					}else{
						yjfunc.myconfirm(data.info);return;
					}
				}
			})
		}
	</script>
</html>