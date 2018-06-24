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
<script type="text/javascript" src="/Public/Admin/lib/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootstrap3/dist/js/bootstrap.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootbox/bootbox.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/jquery.custom.js"></script><link rel="stylesheet" type="text/css" href="/Public/Admin/lib/uploadify/uploadify.css" />
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
</script><script type="text/javascript" src="/Public/Admin/lib/ueditor/ueditor.config.js"></script><script type="text/javascript" src="/Public/Admin/lib/ueditor/ueditor.all.min.js"></script>
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
            <div class="fancy-tab-container">
                <ul class="nav nav-tabs fancy">
                    <li class="active"><a href="#autotab_1" data-toggle="tab">基本信息</a></li>
                    <?php if(!empty($db["openid"])): ?><li><a href="#autotab_2" data-toggle="tab">微信资料</a></li><?php endif; ?>
                    <!--<li><a href="#autotab_3" data-toggle="tab">会员关系</a></li>-->
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="autotab_1">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label">登录名：</label>
                                <div class="controls">
                                    <input type="text" class="form-control w150" name="username" id="username" placeholder="输入<?php echo ($name); ?>登录名" value="<?php echo ($db["username"]); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">密码：</label>
                                <div class="controls">
                                    <input type="text" class="form-control w150" name="userpwd" id="userpwd" placeholder="输入<?php echo ($name); ?>密码" value="" />
                                    <?php if(($db["id"]) > "0"): ?><div class="help-block">留空不修改</div>
                                        <?php else: ?>
                                        <div class="help-block">请设置6位以上，数字字母组合</div><?php endif; ?>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="control-label">姓名：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="输入<?php echo ($name); ?>姓名" value="<?php echo ($db["name"]); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">联系电话：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="telephone" id="telephone" placeholder="输入<?php echo ($name); ?>联系电话" value="<?php echo ($db["telephone"]); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">部门：</label>
                                <div class="controls">
                                    <select name="departmentid" class="form-control w80 fl">
                                        <?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($db['departmentid'] == $vo['id']): ?>selected<?php endif; ?> value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="radio" <?php if($db['ismaster'] == 0): ?>checked<?php endif; ?> value="0" name="ismaster" />员工
                                    &nbsp;&nbsp;

                                    <input type="radio" <?php if($db['ismaster'] == 1): ?>checked<?php endif; ?> value="1" name="ismaster" />部门负责人
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">部门权限：</label>
                                <div class="controls">
                                    <?php if(is_array($department)): $k = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><label style="padding-right: 20px;" for="departlimit_<?php echo ($k); ?>">
                                           <?php if(in_array($vo['id'],$limit)): ?><input type="checkbox" checked value="<?php echo ($vo["id"]); ?>" name="departlimit[]" id="departlimit_<?php echo ($k); ?>" />
                                               <?php else: ?>
                                               <input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="departlimit[]" id="departlimit_<?php echo ($k); ?>" /><?php endif; ?>
                                           <?php echo ($vo["name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label">职位：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="position" id="position" placeholder="输入<?php echo ($name); ?>职位" value="<?php echo ($db["position"]); ?>" />
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



                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="clearfix"></div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="padding:20px 0px;">
                        <div class="controls">
                            <hr />
                            <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> 提交</button>
                            <button type="button" class="btn btn-default" onClick="history.back();"><i class="fa fa-undo"></i> 返回</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 " >
        <form action="" method="post" name="form1" id="form1" class="ajaxformx">
            <input type="hidden" id="id" name="id" value="<?php echo ($db["id"]); ?>" />
            <div class="col-md-6 custom-form"> </div>
        </form>
    </div>
</div>
<script language="javascript">
    var $fields={
        pid: {
            validators: {
                notEmpty: {
                    message: '必须选择所属分类'
                }
            }
        } ,
        username: {
            validators: {
                notEmpty: {
                    message: '用户名不能为空'
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