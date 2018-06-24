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
					<input type="text" name="username" id="username" value="<?php echo ($db["username"]); ?>" placeholder="请输入真实姓名" />
				</p>
			</div>
			<div>
				<span>身份证号</span>
				<p class="input-p">
					<input type="text" name="idcard" id="idcard" value="<?php echo ($db["idcard"]); ?>" placeholder="请输入身份证号" />
				</p>
			</div>
			<div>
				<span>手机号码</span>
				<p class="input-p">
					<input type="text" name="telephone" id="telephone" value="<?php echo ($db["telephone"]); ?>" readonly placeholder="请输入手机号码" />
				</p>
			</div>
		</section>

		<div class="vertical-top">
			请上传身份证照片（正面）
		</div>
		<style type="text/css">
			.zj img{
				width: 100%;
			}
		</style>
		<section class="input-box zj" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<input type="file" onchange="getnewUrl(this.files,'idcardimg1');" style="display: none;" class="uploadpic"  />
			<p onclick="$(this).prev('.uploadpic').click();"  class="idcardimg1" style="height: 2.1rem;position: relative;background: url(/Public/newcss/img/jia.png) no-repeat center / 0.5rem 0.5rem;">
				<?php if(!empty($db["idcardimg1"])): ?><img class="idimg"  src="<?php echo ($db["idcardimg1"]); ?>"><?php endif; ?>
			</p>
			<input type="hidden" name="idcardimg1" value="<?php echo ($db["idcardimg1"]); ?>" />
		</section>



		<div class="vertical-top">
			请上传身份证照片（反面）
		</div>
		<section class="input-box zj" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<input type="file" onchange="getnewUrl(this.files,'idcardimg2');" style="display: none;" class="uploadpic" />
			<p onclick="$(this).prev('.uploadpic').click();" class="idcardimg2" style="height: 2.1rem;position: relative;background: url(/Public/newcss/img/jia.png) no-repeat center / 0.5rem 0.5rem;">
				<?php if(!empty($db["idcardimg1"])): ?><img class="idimg" style="" src="<?php echo ($db["idcardimg2"]); ?>"><?php endif; ?>
			</p>
			<input type="hidden" name="idcardimg2" value="<?php echo ($db["idcardimg2"]); ?>" />
		</section>

		<div class="vertical-top">
			请上传手持身份证照片
		</div>
		<style type="text/css">
			.zj img{
				width: 100%;
			}
		</style>
		<section class="input-box zj" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<input type="file" onchange="getnewUrl(this.files,'idcardimg3');" style="display: none;" class="uploadpic"  />
			<p onclick="$(this).prev('.uploadpic').click();"  class="idcardimg3" style="height: 2.1rem;position: relative;background: url(/Public/newcss/img/jia.png) no-repeat center / 0.5rem 0.5rem;">
				<?php if(!empty($db["idcardimg3"])): ?><img class="idimg"  src="<?php echo ($db["idcardimg3"]); ?>"><?php endif; ?>
			</p>
			<input type="hidden" name="idcardimg3" value="<?php echo ($db["idcardimg3"]); ?>" />
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
			<button onclick="commitinfo()">
				完成
			</button>
		</div>
		<canvas id="myCanvas" style="display: none;"></canvas>
		<img id="agoimg" style="display:none;"/>
	</body>
	<script type="text/javascript ">



				function commitinfo(){
					var username=$("#username").val();
					var idcard=$("#idcard").val();
					var telephone=$("#telephone").val();
					var idcardimg1=$("input[name='idcardimg1']").val();
					var idcardimg2=$("input[name='idcardimg2']").val();
					var idcardimg3=$("input[name='idcardimg3']").val();
					if($.trim(username)==""||$.trim(username)==null){
						yjfunc.mytoast("姓名不能为空");return;
					}
					if($.trim(idcard)==""||$.trim(idcard)==null){
						yjfunc.mytoast("身份证号不能为空");return;
					}
					if($.trim(telephone)==""||$.trim(telephone)==null){
						yjfunc.mytoast("联系电话不能为空");return;
					}
					if($.trim(idcardimg1)==""||$.trim(idcardimg1)==null){
						yjfunc.mytoast("请上传身份证正面照");return;
					}
					if($.trim(idcardimg2)==""||$.trim(idcardimg2)==null){
						yjfunc.mytoast("请上传身份证反面照");return;
					}
					if($.trim(idcardimg3)==""||$.trim(idcardimg3)==null){
						yjfunc.mytoast("请上传手持身份证照片");return;
					}
					$.ajax({
						url:"/Member/subinfo.html",
						data:{username:username,idcard:idcard,telephone:telephone,idcardimg1:idcardimg1,idcardimg2:idcardimg2,idcardimg3:idcardimg3},
						type:"POST",
						success: function (data) {
							if(data.status==1){
								yjfunc.myconfirm(data.info,["确认"], function (e) {
									if(e==0){
										window.location.href="/Index/menu.html";
									}
								})
							}else{
								yjfunc.myconfirm(data.info);
							}
						}
					})

				}

		$(".deleimg").click(function () {
			var obj=$(this);
			yjfunc.myconfirm("确认删除该照片？",['取消','确认'], function (e) {
				if(e==1){
					obj.parent("span").parent("p").empty();
				}
			});
		});


	</script>
	<script>

		function getnewUrl(fil,classes) {
			if(fil.length<=0){
				return;
			}
			var Cnv = document.getElementById('myCanvas');
			var Cntx = Cnv.getContext('2d');//获取2d编辑容器
			var imgss =   new Image();
			var agoimg=document.getElementById("agoimg");
			$("."+classes).html("<img style='width: 100%; height: 100%;' src=\"/Public/Home/images/loading.gif\"/>");
//        alert(fil.length);
			for (var intI = 0; intI < fil.length; intI++) {
				var tmpFile = fil[intI];
				var reader = new FileReader();
				reader.readAsDataURL(tmpFile);
				reader.onload = function (e) {
					url = e.target.result;
					imgss.src = url;
					agoimg.src=url;
				};

				agoimg.onload = function () {
					//等比缩放
					var m = imgss.width / imgss.height;
					Cnv.height =1100;//该值影响缩放后图片的大小
					Cnv.width= 1100*m ;
					//img放入画布中
					//设置起始坐标，结束坐标
					Cntx.drawImage(agoimg, 0, 0,1100*m,1100);
					var Pic = document.getElementById("myCanvas").toDataURL("image/png");

					$.ajax({
						url:"/Member/saveimg.html",
						data:{pic:Pic},
						type:"POST",
						success: function (data) {
							if(data.status==1){
								var html="<img class=\"idimg\" style=\"\" src=\""+data.info+"\"/>" ;
//              + "<span class=\"delespan\">" +
//              "<img class=\"deleimg\" src=\"/Public/newcss/img/delete.png\"/> " +
//              "</span>"
								$("."+classes).html(html);
								$('input[name="'+classes+'"]').val(data.info);
							}else{
								yjfunc.myconfirm(data.info);
							}
						}
					});


				};
			}

		}


	</script>



</html>