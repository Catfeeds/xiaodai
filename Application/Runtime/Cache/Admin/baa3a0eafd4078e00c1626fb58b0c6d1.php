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

                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="autotab_1">
                        <div class="col-md-8 custom-form">

                            <div class="form-group">
                                <label class="control-label">标题：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="输入<?php echo ($name); ?>标题" value="<?php echo ($db["title"]); ?>" />
                                </div>
                            </div>



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
                                <label class="control-label">利率：</label>
                                <div class="controls">

                                    <div class="input-group col-md-4">
                                        <input type="text" class="form-control" name="interest" id="interest" placeholder="输入<?php echo ($name); ?>利率" value="<?php echo ($db["interest"]); ?>" />
                                        <span class="input-group-addon">%</span> </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">期限【用“,”隔开多个期限，如：5,7,20,365】：</label>
                                <div class="controls">

                                    <div class="input-group col-md-4">
                                        <input type="text" class="form-control" name="days" id="days" placeholder="输入<?php echo ($name); ?>期限" value="<?php echo ($db["days"]); ?>"  />
                                        <span class="input-group-addon"> 天</span> </div>

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label">起始额度（元）：</label>
                                <div class="controls">

                                    <div class="input-group col-md-4">
                                        <input type="text" class="form-control" name="limitstart" id="limitstart" placeholder="输入<?php echo ($name); ?>起始额度" value="<?php echo ($db["limitstart"]); ?>" />
                                        <span class="input-group-addon"> <i class="fa fa-cny"></i></span> </div>

                                </div>
                                <label class="control-label">最大额度（元）：</label>
                                <div class="controls">

                                    <div class="input-group col-md-4">
                                        <input type="text" class="form-control" name="limitend" id="limitend" placeholder="输入<?php echo ($name); ?>最大额度" value="<?php echo ($db["limitend"]); ?>"  />
                                        <span class="input-group-addon"> <i class="fa fa-cny"></i></span> </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">逾期费（每天费用）：</label>
                                <div class="controls">

                                    <div class="input-group col-md-4">
                                        <input type="text" class="form-control" name="overdue" id="overdue" placeholder="输入<?php echo ($name); ?>逾期费" value="<?php echo ($db["overdue"]); ?>"  />
                                        <span class="input-group-addon"> %</span> </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 custom-form">



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
                        <div class="clearfix"></div>
                    </div>


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
</div>
<script language="JavaScript" type="text/javascript" >
    $.rendUploader("#btnUploadhaibao");
    $(function(){

        $("#addDay").click(function(){
            var num=parseInt($("#daynum").val());
            var html=$.addDay(num+1);
            $("#tempid").append(html);
            if($("#pid").val()==3){
                $(".datatype").val(3).attr("disabled",true).change();
            }
            //$(".editor").xheditor(default_setting);
            $("#daynum").val(num+1);
            $.rendUploader("#btnUpload"+(num+1));
        });

        $("#addattr").click(function(){
            var num=parseInt($("#attrnum").val());
            var html=$.addattr(num+1);
            $("#attrid").append(html);


            $("#attrnum").val(num+1);

        });


    });
    (function(){
        $.addDay=function(n){
            var html="";
            html+=("<table class=\"table vm\">");
            html+=("  <tr class=\"row0\">");
            html+=("    <td width=\"70\"  class=\"tc\" >第<span class=\"day\">"+n+"</span>项 <br /><a href=\"javascript:void(0);\" onclick=\"if(confirm('您确定要删除吗？')){$(this).parent().parent().parent().parent().remove();}\">删除</a></td>");

            html+=("    <td ><div class=\"input-group\"><input type=\"text\" class=\"form-control\" name=\"images[]\"   value=\"\"   /><span class=\"input-group-addon\"><button type=\"button\" id='btnUpload"+n+"' class=\"custom-upload\"><i class=\"fa fa-upload\"></i></button></span></div>  </td>");
            html+=("  </tr>");
            html+=("</table>");
            return html;
        }


        $.addattr=function(n){
            var html="";
            html+=("<table class=\"table vm\">");
            html+=("  <tr class=\"row0\">");
            html+=("    <td width=\"70\"  class=\"tc\" >第<span class=\"day\">"+n+"</span>项 <br /><a href=\"javascript:void(0);\" onclick=\"if(confirm('您确定要删除吗？')){$(this).parent().parent().parent().parent().remove();}\">删除</a></td>");

            html+=("    <td ><div class=\"input-group\"><input type=\"text\" placeholder=\"属性名（如：颜色，尺寸）\" class=\"form-control\" name=\"attr_name[]\"  value=\"\"   /></div>  </td>");
            html+=("    <td ><div class=\"input-group\"><input type=\"text\" placeholder=\"属性值（如：红色，蓝色，白色）\" class=\"form-control\" name=\"attr_value[]\"  value=\"\"   /></div>  </td>");
            html+=("  </tr>");
            html+=("</table>");
            return html;
        }

    })(jQuery);
</script>
<script language="javascript">
    var $fields={
        pid: {
            validators: {
                notEmpty: {
                    message: '必须选择所属分类'
                }
            }
        } ,
        title: {
            validators: {
                notEmpty: {
                    message: '<?php echo ($name); ?>标题不能为空'
                }
            }
        }  ,
        price: {
            validators: {
                notEmpty: {
                    message: '<?php echo ($name); ?>价格不能为空'
                }
            }
        }  ,
        stock: {
            validators: {
                notEmpty: {
                    message: '<?php echo ($name); ?>库存不能为空'
                }
            }
        } ,
        weight: {
            validators: {
                notEmpty: {
                    message: '<?php echo ($name); ?>重量不能为空'
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