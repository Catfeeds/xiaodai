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
        .order-item{width:50px; margin:0px; float:left;}
    </style>
    <script language="javascript">
        $(function(){
            InitArea();

            $("#paystatus").change(function(){
                var v=$(this).val();
                if(v==1){
                    $("#paybox").show();
                }else{
                    $("#paybox").hide();
                }
            }).change();

            //减数量
            $(".btn-deduct").click(function(){
                var $input=$(this).next("input");
                var v=$input.val();
                if(isNaN(v)){
                    v=1;
                }
                v--;
                if(v<1){
                    var $obj=$(this);
                    bootbox.confirm("您确定要删除该商品吗?", function(result) {
                        if (result) {
                            $obj.parent().parent().parent().remove();
                            $.caculateTotal();
                        }else{
                            $input.val(v+1);
                        }
                    });
                }else{
                    $input.val(v);
                    $input.change();
                }
            });

            //加数量
            $(".btn-plus").click(function(){
                var $input=$(this).prev("input");
                var v=$input.val();
                if(isNaN(v)){
                    v=1;
                }
                v++;
                $input.val(v);
                $input.change();
            });

            $(".order-item").change(function(){
                var id=$(this).attr("data-id");
                var price=$(this).attr("data-price");
                var num=$(this).val();
                $("#sum_"+id).text((price*num).toFixed(2));
                $.caculateTotal();
            });

            $.caculateTotal=function(){
                var $total=0;
                var $num=0;
                $(".order-item").each(function(index, element) {
                    var num=$(this).val();
                    var price=$(this).attr("data-price");
                    if(isNaN(num)){num=1;}else{
                        num=parseInt(num);
                    }
                    price=parseFloat(price);

                    $num+=num;
                    $total+=parseFloat(num*price);
                });

                $total=$total.toFixed(2);
                $("#label_total").text($total);
                $("#label_num").text($num);
                //其它框
                var $discount=$("#discount").val();
                if(isNaN($discount)){
                    $discount=0;
                    $("#discount").val($discount);
                }
                var $shipfee=$("#shipfee").val();
                if(isNaN($shipfee)){
                    $shipfee=0;
                    $("#shipfee").val($shipfee);
                }
                var $amount=($total-parseFloat($discount)+parseFloat($shipfee)).toFixed(2);
                if($amount<0){
                    alerterr("对不起，应收金额不能小于0！");
                    $("#btnSubmit").attr("disabled",true);
                    return false;
                }else{
                    $("#btnSubmit").attr("disabled",false);
                }
                $("#total").val($total);
                $("#amount").val($amount);
            }

            $("#discount").change(function(){
                $.caculateTotal();
            });
            $("#shipfee").change(function(){
                $.caculateTotal();
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
                    <li><a href="#autotab_2" data-toggle="tab">贷款过程</a></li>
                    <li><a href="#autotab_4" data-toggle="tab">小计</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="autotab_1">
                        <div class="col-md-12 custom-form">
                            <div class="form-group col-md-4">
                                <label class="control-label">订单编号：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="orderno" id="orderno"  value="<?php echo ($db["orderno"]); ?>" readonly  />
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">贷款用户：</label>
                                <div class="controls">

                                    <input type="hidden" name="memberid" id="memberid" value="<?php echo ($db["memberid"]); ?>" />
                                    <input type="text" class="form-control" name="membername" id="membername" value="<?php echo get_cache_value('member',$db['memberid'],'username');?>" readonly />

                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">联系电话：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="telephone" id="telephone"  value="<?php echo ($db["telephone"]); ?>"  readonly/>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="control-label">身份证号：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="idcard" id="idcard"  value="<?php echo ($db["idcard"]); ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">贷款金额：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="damount" id="damount"  value="<?php echo ($db["damount"]); ?>" readonly />
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="control-label">利息：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="interest" id="interest"  value="<?php echo ($db["interest"]); ?>" readonly />
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="control-label">利率：</label>
                                <div class="controls">
                                    <input type="text" class="form-control w150 fl" name="interestrate" id="interestrate"  value="<?php echo ($db["interestrate"]); ?>" readonly />
                                   <span style="line-height: 34px;">&nbsp; %</span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">期限：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="days" id="days"  value="<?php echo ($db["days"]); ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">申请时间：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="addtime" id="addtime"  value="<?php echo ($db["addtime"]); ?>" readonly />
                                </div>
                            </div>

                            <?php if($db['status'] == 1): ?><div class="form-group col-md-4">
                                    <label class="control-label">审核结果：</label>
                                    <div class="controls">
                                        <?php echo ($db['shenhestatus']?'已通过':'<span style="color: red;">被拒绝</span>'); ?>
                                    </div>
                                </div>
                                <?php if($db['shenhestatus'] == 0): ?><div class="form-group col-md-4">
                                        <label class="control-label">拒绝原因：</label>
                                        <div class="controls">
                                            <?php echo ($db["refusereason"]); ?>
                                        </div>
                                    </div><?php endif; endif; ?>



                            <?php if($db['status'] >= 2): ?><div class="form-group col-md-4">
                                    <label class="control-label">最晚还款日期：</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="deadline" id="deadline"  value="<?php echo ($db["deadline"]); ?>" readonly />
                                    </div>
                                </div><?php endif; ?>

                            <?php if(($db['deadline'] < date('Y-m-d H:i:s')) and ($db['status'] >= 2) and ($db['status'] < 4)): ?><div class="form-group col-md-4">
                                    <label class="control-label">是否逾期：</label>
                                    <div class="controls">
                                       已逾期<?php
 $overdue=intval(diffBetweenTwoDays(date('Y-m-d H:i:s'),$db['deadline'])); $overduefee=$overdue*$db['damount']*$db['overduefee']/100; echo ($overdue); ?>天
                                        ，逾期费：&yen;<?php echo ($overduefee); ?>
                                    </div>
                                </div><?php endif; ?>

                            <div class="form-group">
                                <label class="control-label">订单状态：</label>
                                <div class="controls">
                                    <select class="form-control w80 " name="status" id="status" readonly>
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
                    <div class="tab-pane" id="autotab_2">
                        <div class="col-md-6 custom-form">
                            <?php
 $step=json_decode($db['step'],true); ?>

                            <div class="form-group">
                                <?php if(is_array($step)): foreach($step as $key=>$vo): echo ($key+1); ?>、时间：<?php echo ($vo["addtime"]); ?>，事件：<?php echo ($vo["act"]); ?><br/><?php endforeach; endif; ?>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="tab-pane" id="autotab_4">
                        <div class="col-md-6 custom-form">
                            <?php
 $xiaoji=M('loan_notice')->where(array('orderno'=>$db['orderno']))->order('addtime desc')->select() ?>

                            <div class="form-group">
                                <?php if(is_array($xiaoji)): foreach($xiaoji as $key=>$vo): echo ($key+1); ?>、时间：<?php echo ($vo["addtime"]); ?>，事件：<?php echo ($vo["act"]); ?><br/><?php endforeach; endif; ?>
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
                            <?php if(in_array(($db["status"]), explode(',',"3,4,5"))): ?><button type="button" disabled class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> 提交</button>
                                <?php else: ?>
                                <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> 提交</button><?php endif; ?>
                            <button type="button" class="btn btn-default" onClick="history.back();"><i class="fa fa-undo"></i> 返回</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
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