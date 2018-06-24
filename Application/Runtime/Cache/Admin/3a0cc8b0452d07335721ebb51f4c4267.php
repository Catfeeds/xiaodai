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
<script type="text/javascript" src="/Public/Admin/lib/ueditor/ueditor.config.js"></script><script type="text/javascript" src="/Public/Admin/lib/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".myueditor").each(function(index, element) {
        var id=$(element).attr("id");
		var config=$(element).attr("data-config");
		config=((config==""||config==undefined)?$ueconfig:config);
		UE.getEditor(id,config);
    }); 
});
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
          <label class="control-label">公众号名称：</label>
          <div class="controls">
            <input type="text" class="form-control" name="panel_name" id="panel_name" placeholder="<?php echo ($name); ?>名称" value="<?php echo ($db["panel_name"]); ?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">登录名：</label>
          <div class="controls">
            <input type="text" class="form-control" name="panel_username" id="panel_username" value="<?php echo ($db["panel_username"]); ?>" placeholder="登录微信公众平台的账号" />
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">原始ID：</label>
          <div class="controls">
            <input type="text" class="form-control" name="panel_originid" id="panel_originid" value="<?php echo ($db["panel_originid"]); ?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">公众号类型：</label>
          <div class="controls">
           <?php if(($db["panel_type"]) == "0"): ?><label>
            <input type="radio"  id="panel_type" name="panel_type"   value="1" >
            <span class="lbl">服务号</span> </label>
          <label>
            <input   type="radio"  id="panel_type" name="panel_type"  checked  value="0" >
            <span class="lbl">订阅号</span> </label>
          <?php else: ?>
          <label>
            <input   type="radio"  id="panel_type" name="panel_type"  checked value="1" >
            <span class="lbl">服务号</span> </label>
          <label>
            <input  type="radio"  id="panel_type" name="panel_type"    value="0" >
            <span class="lbl">订阅号</span> </label><?php endif; ?>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">认证类型：</label>
          <div class="controls">
           <?php if(($db["panel_cert"]) != "0"): ?><label>
            <input  type="radio"  id="panel_cert" name="panel_cert" checked value="1" >
            <span class="lbl">已认证</span> </label>
          <label>
            <input  type="radio"  id="panel_cert" name="panel_cert"   value="0" >
            <span class="lbl">未认证</span> </label>
          <?php else: ?>
          <label>
            <input  type="radio"  id="panel_cert" name="panel_cert"  value="1" >
            <span class="lbl">已认证</span> </label>
          <label>
            <input type="radio"  id="panel_cert" name="panel_cert" checked  value="0" >
            <span class="lbl">未认证</span> </label><?php endif; ?>
          </div>
        </div>

        <h2>开发者配置<hr /></h2>
        <div class="form-group">
          <label class="control-label">URL(服务器地址)：</label>
          <div class="controls">
            <input type="text" class="form-control" name="app_url" id="app_url" value="<?php echo ($db["app_url"]); ?>" placeholder="服务器URL" />
            <div class="help-block">微信公众平台填写的地址，复制此地址到微信后台相应处</div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label">AppId：</label>
          <div class="controls">
            <input type="text" class="form-control" name="app_id" id="app_id" value="<?php echo ($db["app_id"]); ?>" placeholder="<?php echo ($name); ?>AppId" />
            <div class="help-block">审核后在公众平台开启开发模式后可查看</div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label">AppSecret：</label>
          <div class="controls">
            <input type="text" class="form-control" name="app_secret" id="app_secret" value="<?php echo ($db["app_secret"]); ?>" placeholder="<?php echo ($name); ?>AppId" />
            <div class="help-block">审核后在公众平台开启开发模式后可查看</div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">Token：</label>
          <div class="controls">
            <input type="text" class="form-control" name="app_token" id="app_token" value="<?php echo ($db["app_token"]); ?>" placeholder="<?php echo ($name); ?>AppId" />
            <div class="help-block">接口token</div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">aeskey：</label>
          <div class="controls">
            <input type="text" class="form-control" name="app_aeskey" id="app_aeskey" value="<?php echo ($db["app_aeskey"]); ?>" placeholder="<?php echo ($name); ?>EncodingAESKey" />
            <div class="help-block">消息加解密密钥</div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label">mchid：</label>
          <div class="controls">
            <input type="text" class="form-control" name="app_mchid" id="app_mchid" value="<?php echo ($db["app_mchid"]); ?>" placeholder="<?php echo ($name); ?>AppId" />
            <div class="help-block">受理商ID，身份标识。微信支付审核通过后，在微信发送的邮件中查看</div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">key：</label>
          <div class="controls">
            <input type="text" class="form-control" name="app_key" id="app_key" value="<?php echo ($db["app_key"]); ?>" placeholder="<?php echo ($name); ?>AppId" />
            <div class="help-block">商户支付密钥Key。微信支付审核通过后，在微信发送的邮件中查看</div>
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
	panel_name: {
		validators: {
			notEmpty: {
				message: '<?php echo ($name); ?>名称不能为空'
			} 
		}
	} ,
	panel_username: {
		validators: {
			notEmpty: {
				message: '登录名不能为空'
			} 
		}
	} ,
	app_id: {
		validators: {
			notEmpty: {
				message: '<?php echo ($name); ?>app_id不能为空'
			} 
		}
	} ,
	app_secret: {
		validators: {
			notEmpty: {
				message: '<?php echo ($name); ?>app secret不能为空'
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