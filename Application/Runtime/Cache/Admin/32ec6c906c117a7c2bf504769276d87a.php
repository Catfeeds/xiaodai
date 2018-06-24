<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo ($title); ?>-后台管理系统</title> 
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<script>
var ADMIN_PATH="/Admin";
var APP_PATH="";
var CONST_PUBLIC="/Public";
var CONST_UPLOAD="<?php echo U('File/upload');?>";
var CONST_SESSION={}; 
</script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/lib/FontAwesome/css/font-awesome.css" /><link rel="stylesheet" type="text/css" href="/Public/Admin/lib/bootstrap3/dist/css/bootstrap.min.css" /><link rel="stylesheet" type="text/css" href="/Public/Admin/stylesheets/theme.min.css" /><link rel="stylesheet" type="text/css" href="/Public/Admin/stylesheets/custom.css" />
<script type="text/javascript" src="/Public/Admin/lib/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootstrap3/dist/js/bootstrap.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootbox/bootbox.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/jquery.custom.js"></script> 
<script language="javascript">
function isls(){
	if(window.localStorage){
		return true;	
	}else{
		return false;	
	}
}
function store($item){
	if(isls){
		if(arguments[1]!=undefined){
			$value=arguments[1];	
			localStorage.setItem($item,$value);
		}else{
			$value= (localStorage.getItem($item));
			if($value==undefined){
				$value="";	
			}
			return $value;
		}
	}else{
		return false;	
	}
}
$(function(){ 
	$("#theme div").click(function(){
		$("#theme-bg").stop().animate({opacity: '0.5'},0);
		$("#theme div").removeClass("selected");
		$(this).addClass("selected");
		var $img=$(this).attr("data-img");
			store("bg",$img);
		$bg="";
		if($img==""){
			$bg="none";
		}else{
			$bg="/Public/Admin/assets/bg/";
			$bg="url("+($bg+$img)+")"; 
				
		}
		$("#theme-bg").css("background-image",$bg);
		$("#theme-bg").animate({opacity: '1'},1000);
	});
	var	$initimg=store("bg"); 
	if($initimg!=""){
		$("#theme div[data-img='"+$initimg+"']").click();
	}
	
	$("#username").focus();
	if(self.frameElement != null && (self.frameElement.tagName == "IFRAME" || self.frameElement.tagName == "iframe")){
		window.parent.location=ADMIN_PATH;
	} 
	if (window!=top) // 判断当前的window对象是否是top对象
top.location.href =window.location.href; // 如果不是，将top对象的网址自动导向被嵌入网页的网址

	$("#form2").submit(function(){ 
		var u=$.trim($("#username").val());
		var p=$.trim($("#userpwd").val());
		var v=$.trim($("#verify").val());
		if(u==""){
			$("#username").focus();
			return false;
		}
		if(p==""){
			$("#userpwd").focus();
			return false;
		}
		// if(v==""){
		// 	$("#verify").focus();
		// 	return false;
		// }
		$.ajax({
			"type":"POST",
			"url":"<?php echo U('Login/login');?>",
			"data":"username="+u+"&userpwd="+p+"&verify="+v+"",
			"success":function(msg){   
				if(msg.status==0){
					$("#btnLogin").attr("disabled",false);
					$("#verify").val("").focus();
					$("#vimg").click();
					alert(msg.info);
					return false;
				}else{
					
					$("#btnLogin").attr("disabled",true); 
					
					window.location.href='/Admin/Index.html';
				}
			}
		});	
		
		return false;
	});
});
</script>
</head>
<body>
 <div class="login">
    <div class="dialog">
        <p class="brand" href="index.html"><?php echo ($title); ?></p>
        <div class="block">
            <div class="block-header">
                <h2>请您登录</h2>
            </div>
            <form id="form2">
                <div class="form-group">
                    <label>用户名</label>
                    <input type="text" class="form-control" name="username" id="username" maxlength="20" />
                </div>

                <div class="form-group">
                    <label>密码</label> 
                    <input type="password" class="form-control" name="userpwd" id="userpwd"  maxlength="20" />
                </div>
                <div class="form-group">
                    <label>验证码</label> 
                    <input type="text" class="form-control" name="verify" id="verify" maxlength="4"  />
                    <img id="vimg" style="cursor:pointer;margin-left:5px; cursor: hand; width:150px; height:36px;" title="看不清楚?换一张!" alt="" onclick="this.src='<?php echo U('Login/verify');?>?random='+Math.random()" src="<?php echo U('Login/verify');?>" />
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success pull-right">立即登录</button>
                    <!--You can have a remember me or sign up here-->
                    <!--<label class="remember-me"><input type="checkbox"> Remember me</label>-->
                    <!--<a href="sign-up.html" class="sign-up">Sign Up</a>-->
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
function getFile($dir) { if (false != ($handle = opendir ( $dir ))) { $i=0; while ( false !== ($file = readdir ( $handle )) ) { if ($file != "." && $file != ".."&&strpos($file,".")) { $fileArray[$i]=$file; if($i==100){ break; } $i++; } } closedir ( $handle ); } return $fileArray; } $bgs=getFile(('./Public/Admin/assets/bg/')); ?>
<?php if(!empty($bgs)): ?><div id="theme-bg"></div>
<div id="theme">
<div data-img="" class="selected" style="background:#fff;"></div>
<?php if(is_array($bgs)): $i = 0; $__LIST__ = $bgs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div data-img="<?php echo ($vo); ?>" style="background:url(<?php echo get_thumb('/Public/Admin/assets/bg/'.$vo);?>) no-repeat;" /></div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
</body>
</html>