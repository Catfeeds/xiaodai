<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>添加银行卡</title>
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
			
			.vertical-top{
				margin: 0.12rem 0.12rem 0;
				padding-left:0.12rem ;
				position: relative;
				font-weight: bold;
				color: #999;
				font-size: 0.14rem;
			}
			.vertical-top:before{
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
			
		</style>
	</head>

	<body style="background: #f5f5f5;">
		<div class="vertical-top">
			请填写个人信息
		</div>
		<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<div>
				<span>姓名</span>
				<p class="input-p">
					<input type="text" name="username" id="username" value="" placeholder="请输入真实姓名" />
				</p>
			</div>
			<div>
				<span>银行预留手机号</span>
				<p class="input-p">
					<input type="text" name="telephone" id="telephone" value="" placeholder="请输入电话号码" />
				</p>
			</div>
			<div>
				<span>身份证号</span>
				<p class="input-p">
					<input type="text" name="idcard" id="idcard" value="" placeholder="请输入身份证号" />
				</p>
			</div>
			<div>
				<span>绑定银行卡号</span>
				<p class="input-p">
					<input type="text" name="bankno" id="bankno" value="" placeholder="请输入银行卡号码" />
				</p>
			</div>
			<div>
				<span>所属银行</span>
				<p class="input-p">
					<input type="text" name="name" id="name" value="" placeholder="如：工商银行" />
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
				border: 0;
			}
			.footpadding{
				padding-top:0.5rem ;
			}
		</style>
		<div class="footpadding"></div>
		<div class="bot-btn">
			<button id="ok">
				确认添加
			</button>
		</div>

	</body>
	<script type="text/javascript ">
		$('#ok').click(function(){

			var username=$("#username").val();
			var telephone=$("#telephone").val();
			var idcard=$("#idcard").val();
			var bankno=$("#bankno").val();
			var name=$("#name").val();


			if($.trim(username)==""||$.trim(username)==null){
				yjfunc.mytoast("请输入真实姓名");return;
			}
			if($.trim(telephone)==""||$.trim(telephone)==null){
				yjfunc.mytoast("请输入电话号码");return;
			}
			if($.trim(idcard)==""||$.trim(idcard)==null){
				yjfunc.mytoast("请输入身份证号");return;
			}
			if($.trim(name)==""||$.trim(name)==null){
				yjfunc.mytoast("请输入银行卡号");return;
			}

			if($.trim(bankno)==""||$.trim(bankno)==null){
				yjfunc.mytoast("请输入银行卡号");return;
			}

			$.ajax({
				url:"/Member/subbankinfo.html",
				data:{username:username,idcard:idcard,telephone:telephone,bankno:bankno,name:name},
				type:"POST",
				success:function(data){
					if(data.status==4){
						yjfunc.mytoast(data.info);
						window.location.href="/Index/menu.html";
					}else{
						yjfunc.mytoast(data.info);
					}

				}
			})





		})
	</script>
</html>