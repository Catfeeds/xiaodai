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
<link rel="stylesheet" type="text/css" href="/Public/Admin/lib/uploadify/uploadify.css" />
<script type="text/javascript" src="/Public/Admin/lib/uploadify/jquery.uploadify.min.js"></script>

<script>


    function getnewUrl(fil,classes) {
        if(fil.length<=0){
            return;
        }
        var Cnv = document.getElementById('myCanvas');
        var Cntx = Cnv.getContext('2d');//获取2d编辑容器
        var imgss =   new Image();
        var agoimg=document.getElementById("agoimg");
        var newimg=document.getElementById(classes);
        newimg.src="/Public/Home/images/loading.gif";
//        $("."+classes).html("<img style='width: 100%; height: 100%;' src=\"/Public/Home/images/loading.gif\"/>");
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
                Cnv.height =700;//该值影响缩放后图片的大小
                Cnv.width= 700*m ;
                //img放入画布中
                //设置起始坐标，结束坐标
                Cntx.drawImage(agoimg, 0, 0,Cnv.width, Cnv.height);
                var Pic = document.getElementById("myCanvas").toDataURL("image/png");

                $.ajax({
                    url:"/Member/saveimg.html",
                    data:{pic:Pic},
                    type:"POST",
                    success: function (data) {
                        if(data.status==1){
//                            var html="<img class=\"idimg\" style=\"\" src=\""+data.info+"\"/>" ;
                            newimg.src=data.info;
//                            $("."+classes).html(html);
                            $('input[name="'+classes+'"]').val(data.info);
                        }else{
                            alerterr(data.info);
                        }
                    }
                });


            };
        }

    }
</script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/stylesheets/lightbox.css" />
<script type="text/javascript" src="/Public/Admin/lib/jquery.lightbox.min.js"></script>
<script>
$(function(){
	//图片素材上传按钮
	$.rendUploaderMaterial = function(id) {
		$(id).uploadify({
			"swf": CONST_PUBLIC + "/Admin/lib/uploadify/uploadify.swf",
			"fileObjName": "download",
			"fileTypeDesc": "请选择图片文件",
			"fileTypeExts": "*.jpg;*.gif;*.png",
			"buttonText": "",
			"uploader": CONST_UPLOAD,
			'removeTimeout': 1,
			'width': "100%",
			'height': "100%",
			"onUploadSuccess": uploadFilefile_id,
			"onQueueComplete": uploadFileAll
		});

		function uploadFilefile_id(file, data, response) {
			var data = $.parseJSON(data);
			if (data.status) {} else {
				alerterr(data.info);
			}
		};

		function uploadFileAll() {
			alerttip("恭喜，图片上传完成！");
			setTimeout(function() {
				location.reload();
			}, 1000);
		}
	}
	
	$.rendUploaderMaterial("#btnUploadMaterial");
	
	$.deleteImg=function(id){
		bootbox.confirm("您确定删除该图片素材吗?", function(result) {
			if (result) {
				$.ajax({
			url:"<?php echo U('Wechat/deleteMaterial');?>",
			data:"id="+id,
			success: function(msg){
				if(msg.status==1){
					window.location.reload();
				}else{
					alerterr(msg.info);	
				}
			}
			});		
			};
		});
	}
});
</script>
<style>
/*图片素材*/
#btnUploadMaterial{ width:100%; background: url(/Public/Admin/assets/uploadmaterial.gif) no-repeat center;position: relative;min-height: 200px;border: 3px dashed #ddd;border-radius: 3px;vertical-align: middle;cursor: pointer;padding: 0 15px 15px 0;-webkit-transition: all .2s;transition: all .2s;}
.item_image{ position:relative; width:180px; height:200px; margin:0px 10px;  text-align:center; line-height:20px; float:left; padding:9px;}
.item_image a{width:180px; height:180px; float:left; overflow:hidden;}
.item_image img{width:98%; max-height:98%; float:left; border:1px #eee solid; padding:1px;}
.item_image:hover img,.item_image a:hover img{ border:1px #ccc solid;}

.item_image em{ position:absolute; width:50px; cursor:pointer; border-bottom:1px #ccc solid; border-right:1px #ccc solid; height:20px; top:10px; left:10px; font-style:normal; display:none; background:#fff; color:#f00;}
.item_image:hover em{ display:block;color:#000;}
.item_image span{width:100%; float:left; text-align:center; height:20px; overflow:hidden;}
</style>
</head>
<body>
<div class="row">
  <div class="col-md-12"> 
      <h2><?php echo ($title); ?></h2>   
  </div>
  <div class="col-md-12"> 
       <div class="fancy-tab-container"> 
       <ul class="nav nav-tabs  fancy">
          <li class="active"><a href="<?php echo U('Wechat/material');?>"  aria-expanded="true">图片素材</a></li>
          <li class=""><a href="<?php echo U('Wechat/news');?>" aria-expanded="false">图文素材</a></li> 
        </ul> 
       <div style="padding:10px;">
      	 <div class="item_add" id="btnUploadMaterial"></div>
       </div>
       </div>
       <div >
           <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item_image" > 
            <?php if((in_array($vo['ext'],array('jpg','gif','bmp','png')) == true)): ?><a href="<?php echo ($vo["fullpath"]); ?>" rel="tooltip" data-original-title="上传时间：<?php echo (time_format($vo["create_time"])); ?>" target="_blank" data-lightbox="lightbox-set">
            <img src="<?php echo ($vo["fullpath"]); ?>"  alt="" /></a> 
            <?php else: ?>
            <a href="javascript:void(0);" rel="tooltip" data-original-title="上传时间：<?php echo (time_format($vo["create_time"])); ?>"  >
            <img src="<?php echo C('DEFAULT_NOPIC');?>"  alt="" />
            </a><?php endif; ?>
            <span><?php echo ($vo["name"]); ?></span><em onclick="$.deleteImg(<?php echo ($vo["id"]); ?>)">删除</em> </div><?php endforeach; endif; else: echo "" ;endif; ?>
       </div>
       <div class="row">
       <?php echo ($page); ?>
       </div>
  </div>
</div>
</body>
</html>