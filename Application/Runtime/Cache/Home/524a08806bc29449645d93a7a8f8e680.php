<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
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
				width: 0.18rem;
				text-align: left;
			}
			
			.input-box span img {
				width: 100%;
			}
			
			.input-box .input-p {
				margin-left: 0.28rem;
			}
			
			.input-box input,
			.input-box select {
				border: 0;
				width: 100%;
			}
		</style>
	</head>

	<body style="background: #f5f5f5;">
		<div style="text-align: center;margin: 0.4rem 0 0.3rem 0;">
			<img style="width: 0.8rem;" src="/Public/newcss1/img/logo.png" />
		</div>
		<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<div>
				<span><img src="/Public/newcss1/img/phone.png"/></span>
				<p class="input-p">
					<input type="text" name="" id="telephone" value="" placeholder="请输入手机号码" />
				</p>
			</div>

			<div>
				<span><img src="/Public/newcss1/img/Verification.png"/></span>
				<p class="input-p">
					<input style="width: 66%;float: left;border-right: 1px solid #e5e5e5;box-sizing: border-box;" type="text" name="" id="varf" value="" placeholder="验证码" />
					<input type="button" id="smsbotton" value="获取验证码" style="border: 0; float: left;width: 34%;color: #E6BC85;background: #fff;padding-left: 0.1rem;" onclick="getsmsverify()" />
				</p>
			</div>
			<div>
				<span><img src="/Public/newcss1/img/password.png"/></span>
				<p class="input-p">
					<input type="password" name="" id="userpwd" value="" placeholder="请输入密码" />
				</p>
			</div>
			<div>
				<span><img src="/Public/newcss1/img/password.png"/></span>
				<p class="input-p">
					<input type="password" name="" id="repassword" value="" placeholder="请确认密码" />
				</p>
			</div>
			<div>
				<span><img src="/Public/newcss1/img/password.png"/></span>
				<p class="input-p">
					<input type="text" name="" id="registercode" value="" placeholder="如无可不填写邀请码" />
				</p>
			</div>

		</section>
		<div style="padding: 0.12rem;margin: 0.12rem;overflow: hidden;font-size: 0.14rem;">
			<a style="text-decoration: underline;color: #E6BC85;float: left;" href="<?php echo U('login/index');?>">返回登录</a>
		</div>
		<label style="text-align: center;position: absolute;bottom: 0.8rem;left: 0.24rem;color: #999;font-size: 0.12rem;">
			<input type="checkbox" style="vertical-align: middle;" checked="checked" name="" id="pro" value="" />
			<span style="vertical-align: middle;">同意<a href="">《用户协议》</a></span>
		</label>
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
				border: 0;
			}
		</style>
		<div class="bot-btn">
			<button onclick="subreg()">
				注册
			</button>
		</div>
	</body>
	<script type="text/javascript ">
		var countdown=60;
		var t;
		function getsmsverify(){
			var telephone=$("#telephone").val();
			if($.trim(telephone)==""|| $.trim(telephone)==null){
				yjfunc.mytoast("请输入有效电话");return;
			}
			if(countdown==60){
				$.ajax({
					url:"/Login/getsmsverify.html",
					data:{telephone:telephone,type:1},
					type:"POST",
					success:function(data){
						yjfunc.mytoast(data.info);
						if(data.status==0){
							clearTimeout(t);
							$('#smsbotton').removeAttr("disabled");
							$('#smsbotton').val("获取验证码");
							countdown = 60;
							return;
						}
					}
				});
				countdown--;
			}
			else{
				if (countdown == 0) {
					$('#smsbotton').removeAttr("disabled");
					$('#smsbotton').val("获取验证码");

					countdown = 60;
					return;
				} else {
					$('#smsbotton').attr("disabled", true);
					$('#smsbotton').val("重新发送(" + countdown + ")");

					countdown--;
				}
			}

				t=setTimeout(function() {getsmsverify()},1000);


		}


		function subreg(){
			//电话
			var telephone =$("#telephone").val();
			//验证码
			var varf=$("#varf").val();
			//密码
			var userpwd=$("#userpwd").val();
			//确认密码
			var repassword=$("#repassword").val();
			//邀请码
			var registercode=$("#registercode").val();


			if($.trim(telephone)==""|| $.trim(telephone)==null){
				yjfunc.mytoast("请输入有效电话");return;
			}
			if($.trim(varf)==""|| $.trim(varf)==null){
				yjfunc.mytoast("请输入验证码");return;
			}
			if($.trim(userpwd)==""|| $.trim(userpwd)==null){
				yjfunc.mytoast("请输入密码");return;
			}
			if($.trim(repassword)==""|| $.trim(repassword)==null){
				yjfunc.mytoast("请确认密码");return;
			}

			if($('#pro').is(':checked')) {
				$.ajax({
					url:"/Login/ajaxregister.html",
					data:{telephone:telephone,varf:varf,userpwd:userpwd,repassword:repassword,registercode:registercode},
					type:"POST",
					success:function(data){
						if(data.status==4){
							yjfunc.mytoast(data.info);
							setTimeout(function () {
								window.location.href="/login/index.html";
							},1000);
						}else{

							yjfunc.mytoast(data.info)
						}

					}
				})
			}else{
				yjfunc.mytoast("请先阅读并勾选注册协议");return;
			}




		}




	</script>

</html>