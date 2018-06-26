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
			
			.input-box div {
				padding: 0.14rem 0.08rem;
				width: 94%;
				margin: 0 auto;
				box-sizing: border-box;
				position: relative;
				overflow: hidden;
			}
			
			.input-box div:after {
				left: 0;
				content: "";
				position: absolute;
				bottom: 0;
				border-bottom: 1px solid #CCCCCC;
				width: 100%;
				-webkit-transform: scaley(.3);
				transform: scaley(.3);
			}
			
			.input-box>a:last-child div:after {
				border: 0;
			}
			
			.input-box span {
				display: inline-block;
				text-align: center;
				color: #000;
				float: left;
				width: 0.3rem;
				text-align: left;
			}
			
			.input-box span img {
				width: 100%;
			}
			
			.input-box .input-p {
				margin-left: 0.4rem;
				line-height: 0.3rem;
				overflow: hidden;
			}
			
			.input-p .putname {
				font-style: normal;
				float: left;
			}
			
			.input-p .putname,
			.input-p .putset {
				font-style: normal;
				float: left;
			}
			
			.input-p .putset {
				float: right;
				color: #999;
			}
		</style>
	</head>

	<body style="background: #f5f5f5;">
		<div class="vertical-top">
			认证越多，信用额度越高
		</div>
		<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<a href="<?php echo U('Member/info');?>">
				<div>
					<span><img src="/Public/newcss1/img/icon1.png"/></span>
					<p class="input-p">
						<i class="putname">个人信息</i>
						<i class="putset"><?php echo ($member['cominfo']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>
			<a  href="<?php echo U('Member/work');?>">
				<div>
					<span><img src="/Public/newcss1/img/icon4.png"/></span>
					<p class="input-p">
						<i class="putname">工作认证</i>
						<i class="putset"><?php echo ($member['gz']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>
			<a  href="<?php echo U('Member/add-card');?>">
				<div>
					<span><img src="/Public/newcss1/img/Bank card.png"/></span>
					<p class="input-p">
						<i class="putname">银行卡</i>
						<i class="putset"><?php echo ($member['bank']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>
			<a href="<?php echo U('Member/contact');?>">
				<div>
					<span><img src="/Public/newcss1/img/icon2.png"/></span>
					<p class="input-p">
						<i class="putname">紧急联系人</i>
						<i class="putset"><?php echo ($member['contacts']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>
			<a href="<?php echo U('Member/tmobile');?>">
				<div>
					<span><img src="/Public/newcss1/img/icon3.png"/></span>
					<p class="input-p">
						<i class="putname">运营商信息</i>
						<i class="putset"><?php echo ($member['tmobile']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>

			<a  href="<?php echo U('Member/zmf');?>">
				<div>
					<span><img src="/Public/newcss1/img/icon5.png"/></span>
					<p class="input-p">
						<i class="putname">芝麻信用</i>
						<i class="putset"><?php echo ($member['zmf']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>
			<!--<a  href="<?php echo U('Member/zfb');?>">
				<div>
					<span><img src="/Public/newcss1/img/Alipay.png"/></span>
					<p class="input-p">
						<i class="putname">支付宝认证</i>
						<i class="putset"><?php echo ($member['zfb']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>
			<a  href="<?php echo U('Member/taobao');?>">
				<div>
					<span><img src="/Public/newcss1/img/800424775772119799.png"/></span>
					<p class="input-p">
						<i class="putname">淘宝认证（可选）</i>
						<i class="putset"><?php echo ($member['taobao']==0?'未完成':'已完成'); ?></i>
					</p>
				</div>
			</a>-->
		</section>
		
		<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
			<?php if($canapply == 1): ?><a href="<?php echo U('Member/applyloan');?>">
					<div>
						<script>yjfunc.mytoast('您已经上传了所有资料，请提交贷款申请',5000);</script>
						<span><img src="/Public/newcss/img/daikuan.png"/></span>
						<p class="input-p">
							<i class="putname">提交贷款申请</i>
						</p>
					</div>
				</a>
				<?php else: ?>
				<a href="javascript:yjfunc.myconfirm('请先完善认证资料');">
					<div>
						<span><img src="/Public/newcss/img/daikuan.png"/></span>
						<p class="input-p">
							<i class="putname">提交贷款申请</i>
						</p>
					</div>
				</a><?php endif; ?>


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


	</body>
</html>