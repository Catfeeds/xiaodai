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
        .news-textbox>div {
            width: 90%;
            padding: 0.1rem 0;
            margin: 0 auto;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            line-height: 1;
            font-size: 0.14rem;
        }

        .news-textbox>div .title {
            font-size: 0.16rem;
            font-weight: bold;
            line-height: 1.5;
            margin-bottom: 0.12rem;
        }

        .day {
            color: #999;
            font-size: 0.1rem;
            margin-bottom: 0.12rem;
        }

        .text {
            font-size: 0.14rem;
            color: #666;
            line-height: 1.5;
        }

        .text img {
            max-width: 100%;
            padding: 0.12rem;
            box-sizing: border-box;
        }
    </style>
</head>

<body style="background: #f5f5f5;">

<section class="news-textbox" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
	<div class="">
		<p class="title"><?php echo ($db["title"]); ?></p>

		<div class="text">
			<?php echo ($db["content"]); ?>
		</div>
	</div>

</section>


</body>
</html>