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
</head>
<body>
<div class="row">
  <div class="col-md-12 " >
    <h2><?php echo ($title); ?></h2>
  </div>
  <div class="col-md-12 " >
    <form action="" method="post" name="form1" id="form1" class="ajaxformx">
      <input type="hidden" id="id" name="id" value="<?php echo ($db["id"]); ?>" />
      <div class="col-md-6 custom-form">
        <div class="form-group">
          <label class="control-label">上级：</label>
          <div class="controls">
            <select class="form-control" id="pid" name="pid">
          <option value="0">--顶级--</option> 
          <?php echo R('Setting/treeselect', array($list));?> 
        </select>
          </div>
        </div>
        
         <?php if(!empty($db["id"])): ?><div class="form-group">
          <label class="control-label">分类名称：</label>
          <div class="controls">
            <input type="text" class="form-control" name="name" id="name" placeholder="<?php echo ($name); ?>名称" value="<?php echo ($db["name"]); ?>" />
          </div>
        </div>
      <?php else: ?>
      
        <div class="form-group">
          <label class="control-label">分类名称：</label>
          <div class="controls">
            <textarea class="form-control" rows="5" name="name" id="name" placeholder="<?php echo ($name); ?>名称" ><?php echo ($db["name"]); ?></textarea>
            <div class="help-block">允许一次添加多个，一行一个</div>
          </div>
        </div><?php endif; ?>
    
        <div class="form-group">
          <label class="control-label">形象图：</label>
          <div class="controls">
         	 <div class="input-group">
                <input type="text" class="form-control" name="indexpic" id="indexpic" value="<?php echo ($db["indexpic"]); ?>" readonly />
                <span class="input-group-addon">
                   <button type="button" id="btnUpload" class="custom-upload"><i class="fa fa-upload"></i></button>
                </span>
            </div> 
          </div>
        </div> 
        <div class="form-group">
          <label class="control-label">分类描述：</label>
          <div class="controls">
            <input type="text" class="form-control" rows="5" name="remark" id="remark" value="<?php echo ($db["remark"]); ?>" placeholder="<?php echo ($name); ?>描述" />
          </div>
          
        <div class="form-group">
          <label class="control-label">排序：</label>
          <div class="controls">
            <input type="text" class="form-control w80" name="sort" id="sort" value="<?php echo ($db["sort"]); ?>"  /> 
          </div>
        </div>

        <div <?php if($act != 'News'): ?>style="display:none;"<?php endif; ?> class="form-group">
          <label class="control-label">是否内部：</label>
          <div class="controls">
            <select  class="form-control w80 " name="inner" id="inner">
              <option value="0" <?php if($db['inner'] == 0): ?>selected="selected"<?php endif; ?> >非内部</option>
              <option value="1" <?php if($db['inner'] == 1): ?>selected="selected"<?php endif; ?>>内部</option>

            </select>
          </div>
        </div>
		

        <div class="form-group">
          <label class="control-label">状态：</label>
          <div class="controls">
            <select class="form-control w80 " name="status" id="status">
              <?php if(is_array($statuslist)): $i = 0; $__LIST__ = $statuslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(!empty($db["id"])): if(($db["status"]) == $key): ?><option value="<?php echo ($key); ?>" selected="selected"><?php echo ($vo); ?></option>
                    <?php else: ?>
                    <option value="<?php echo ($key); ?>" ><?php echo ($vo); ?></option><?php endif; ?>
                  <?php else: ?>
                  <?php if(($key) == "1"): ?><option value="<?php echo ($key); ?>" selected="selected"><?php echo ($vo); ?></option>
                    <?php else: ?>
                    <option value="<?php echo ($key); ?>" ><?php echo ($vo); ?></option><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </div>
        </div>
        <div class="form-group" >
          <div class="controls">
            <hr />
            <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> 提交</button>
            <button type="button" class="btn btn-default" onClick="history.back();"><i class="fa fa-undo"></i> 返回</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script language="javascript">
var $fields={
	name: {
		validators: {
			notEmpty: {
				message: '<?php echo ($name); ?>名称不能为空'
			}
		}
	} 
	};
</script>
<script type="text/javascript" src="/Public/Admin/lib/bootstrapValidator.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() {
var $form= $('.ajaxformx');
	if(typeof($fields)=="undefined"){
		$.rendAjaxForm($form);
	}else{
		$form.find("input:text").eq(0).focus();
		$form.on('success.form.bv', function(e) {
			$.rendAjaxForm($form);
		}).bootstrapValidator({ 
			message: '输入不合法',
			feedbackIcons: {
				valid: 'fa fa-check',
				invalid: 'fa fa-remove',
				validating: 'fa fa-refresh'
			},
			fields: $fields
		}); 
	}
});
</script> 
</body>
</html>