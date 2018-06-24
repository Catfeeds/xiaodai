<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo ($title); ?></title>
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
<style>
body{padding:0px; overflow:hidden;}
#sidebar-nav .nav-list i{width: 15px;}
#sidebar-nav .nav-list li:hover{}
#sidebar-nav .nav-list li>ul{ display:none; padding-left:2em; background: #fff;}
#sidebar-nav .nav-list li>ul li:hover,#sidebar-nav .nav-list li>ul .current{ background: none; }
#sidebar-nav .nav-list li>ul li:hover a,#sidebar-nav .nav-list li>ul .current a{ color: #000; }
</style>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-reorder"></span> </button>
    <a class="navbar-brand" href="<?php echo U('Index/index');?>"><i class="fa fa-television"></i>小贷管理系统</a> </div>
  <div class="hidden-xs">
    <ul class="nav navbar-nav pull-right">
      <!--<li><a href="<?php echo U('Index/message');?>" target="_blank" role="button"><i class="fa fa-file-o"></i> 客服系统</a></li>-->
      <li class="hidden-phone"><a href="javascript:void(0);" id="ClrCache" role="button" rel="tooltip" data-original-title="点击清空缓存" data-placement="bottom" ><i class="fa fa-refresh"></i> 更新缓存</a></li>
      <li><a href="<?php echo U('/');?>" target="_blank" role="button"><i class="fa fa-internet-explorer"></i> 前台</a></li>
      <li id="fat-menu" class="dropdown"> <a href="<?php echo U('System/pwd');?>"  role="button" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-user"></i> <i class="fa fa-user"></i> <?php echo Session('adminname');?> <i class="fa fa-caret-down"></i> </a>
        <ul class="dropdown-menu dropdown-menu-right"> 
          <li><a tabindex="-1" href="javascript:void(0);" id="btnExit">退出</a></li>
        </ul>
      </li>
    </ul>
  </div>
  <!--/.navbar-collapse --> 
</div>
<div class="navbar-collapse collapse">
  <div id="main-menu">
    <ul class="nav nav-tabs hidden-xs">
      <!--<li class="active"><a href="javascript:void(0);" class="level2" data-pid="0" id="parent_0"><i class="fa fa-home"></i> <span>平台首页</span></a></li>-->
      <?php if(is_array($nodelist)): $i = 0; $__LIST__ = $nodelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($supper == true) Or ($vo["access"] == 1)): if(($vo["level"]) == "2"): ?><li><a href="javascript:void(0);"  target="MainFrame" class="level2" data-pid="<?php echo ($vo["id"]); ?>" id="parent_<?php echo ($vo["id"]); ?>"  ><i class="<?php echo ($vo["icon"]); ?>"></i> <span><?php echo ($vo["title"]); ?></span></a></li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
    </ul>
  </div>
</div>
<div id="sidebar-nav" class="hidden-xs">
  <?php if(!empty($nodelist1)): ?><ul id="submenu_0" class="nav nav-list">
      <?php if(is_array($nodelist1)): $i = 0; $__LIST__ = $nodelist1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$son): $mod = ($i % 2 );++$i;?><li ><a href="<?php echo U($son['url']);?>" target="MainFrame" class="level3" data-pid="0" id="son_<?php echo ($son["id"]); ?>" ><i class="<?php echo ((isset($son["icon"]) && ($son["icon"] !== ""))?($son["icon"]):'fa fa-chevron-right'); ?>"></i> <span><?php echo ($son["title"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul><?php endif; ?>
  <?php if(is_array($nodelist)): $i = 0; $__LIST__ = $nodelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["level"]) == "2"): ?><ul id="submenu_<?php echo ($vo["id"]); ?>" class="nav nav-list" style="display:none;">
        <?php if(is_array($vo["child"])): $i = 0; $__LIST__ = $vo["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$son): $mod = ($i % 2 );++$i; if(($supper == true) Or ($son["access"] == 1)): $url=isN($son['url'])?U($vo['name'].'/'.$son['name']):U($son['url']); $child=$son['child']; if($child){ $url='javascript:void(0);'; $css='class="famenu"'; }else{ $css=''; } ?>
            <li <?php echo ($css); ?>><a href="<?php echo ($url); ?>" target="MainFrame" class="level3" data-pid="<?php echo ($vo["id"]); ?>" id="son_<?php echo ($son["id"]); ?>" ><i class="<?php echo ((isset($son["icon"]) && ($son["icon"] !== ""))?($son["icon"]):'fa fa-chevron-right'); ?>"></i> <span><?php echo ($son["title"]); ?></span></a>
              <?php if(!empty($child)): ?><ul id="submenu_<?php echo ($vo["id"]); ?>" class="nav nav-list" >
                  <?php if(is_array($son["child"])): $i = 0; $__LIST__ = $son["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$son1): $mod = ($i % 2 );++$i; if(($supper == true) Or ($son1["access"] == 1)): $url=isN($son1['url'])?U($son['name'].'/'.$son1['name']):U($son1['url']); ?>
                      <li><a href="<?php echo ($url); ?>" target="MainFrame" class="level4" data-pid="<?php echo ($vo["id"]); ?>" id="son1_<?php echo ($son1["id"]); ?>" ><i class="<?php echo ((isset($son1["icon"]) && ($son1["icon"] !== ""))?($son1["icon"]):'fa fa-chevron-right'); ?>"></i> <span><?php echo ($son1["title"]); ?></span></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul><?php endif; ?>
            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
      </ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="content" id="maincontent">
  <iframe id="MainFrame" name="MainFrame" frameborder="0" width="100%" height="100%" src="<?php echo U('Index/sysinfo');?>" ></iframe>
</div>
<script language="javascript">

function resetWin(){
	var WH=($(window).height());
	$("#maincontent").css("min-height",WH-135);	
	$("#MainFrame").css("min-height",WH-131);	
}
$(function(){
	resetWin();
	$(window).resize(function(){resetWin();});
	
	//菜单（一级）
	$("#main-menu .level2").click(function() {
		var pid = $(this).attr("data-pid");
		$("#sidebar-nav ul").hide();
		$("#submenu_" + pid).show();

		$("#main-menu ul li").removeClass("active");
		$(this).parent().addClass("active");
	});

	$("#sidebar-nav .level3").click(function() {
		var pid = $(this).attr("data-pid");

		$("#sidebar-nav ul li").removeClass("active");
		$(this).parent().addClass("active");
	});

	//清除缓存
	$("#ClrCache").click(function() {
		$(this).blur();
		var obj = $(this).find("i");
		obj.addClass("fa-spin fc_green");
		$.ajax({
			url: "<?php echo U('Login/clrcache');?>",
			success: function(msg) {
				msg = eval(msg);
				if (msg.status == "1") {
					obj.removeClass("fa-spin fc_green");
					//setTimeout(function() {
					//	obj.removeClass("fa-spin fc_green");
					//}, 500);
				} else {

				}
			}
		})
	});
	
	//退出登录
	$("#btnExit").click(function() {
		var url="<?php echo U('Login/logout');?>";
		bootbox.confirm("您确定退出登录吗?", function(result) {
				if (result) {
					location=url;
				};
			});
		
	});

   //$(".famenu a:first").click(function(){
	// var $obj=$(this).parent();
	// $obj.addClass("active");
	// var $i=$obj.find("i").eq(0);
	//	if($i.hasClass("fa-chevron-right")){
	//		$i.toggleClass("fa-chevron-down");
	//	};
	// $obj.find("ul").toggle();
   //});
   
    $(".famenu").click(function(){

    //var $obj=$(this).parent();
    //console.log($obj);
    $(this).addClass("active");
    var $i=$(this).find("i").eq(0);
    if($i.hasClass("fa-chevron-right")){
      $i.toggleClass("fa-chevron-down");
    };
    $(this).find("ul").toggle();
  });

   $("#sidebar-nav .nav-list li>ul .current").parent().parent().click();
});
</script>
</body>
</html>