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

    <link rel="stylesheet" type="text/css" href="/Public/Admin/lib/datepicker/css/datepicker.css" />
<script type="text/javascript" src="/Public/Admin/lib/datepicker/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
$(function(){
  $('.datepicker').datepicker();
});
</script>

</head>
<body>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-12 custom-tool" >
            <h2><?php echo ($title); ?></h2>

        </div>
        <form action="" method="get" class="ajaxformx_" name="form1" id="form1">
            <input type="hidden" name="p" id="p" value="1" />
            <input type="hidden" name="status" id="status" value="<?php echo ($status); ?>" />
            <table class="table vm">
                <thead>
                <th colspan="2">筛选</th>
                </thead>
                <tbody>
                <tr>
                    <td class="text-right" width="140">
                        日期：
                    </td>
                    <td>
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input class="form-control datepicker" size="16" type="text" value="<?php echo ($timefrom); ?>" id="timefrom" name="timefrom"  placeholder="开始日期" >
                                <span class="input-group-addon">-</span>
                                <input class="form-control datepicker" size="16" type="text" value="<?php echo ($timeto); ?>"  id="timeto" name="timeto"  placeholder="结束日期" >
                                <span class="input-group-btn">
                                  <button type="submit" class="btn fl"><i class="fa fa-search"></i> 查询</button>
                                </span>
                                <span class="input-group-btn">
                                  <span  onclick="cleartime()" class="btn fl">清除时间</span>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </form>
        <table class="table  table-first-column-number display full table-striped custom-table vm">
            <thead>
            <tr>
                <th></th>
                <th width="80">#</th>
                <th width="180">贷款订单编号</th>
                <th width="180">收款金额</th>
                <th width="180">姓名</th>
                <th width="80">联系电话</th>
                <th width="80">产生时间</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($contentlist)): $i = 0; $__LIST__ = $contentlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td></td>
                    <td><?php echo ($key+1); ?></td>
                    <td><a href="<?php echo U('Member/editloan','id='.$vo['mlid']);?>"><?php echo ($vo["orderno"]); ?></a></td>
                    <td><?php echo (to_price($vo["amount"])); ?></td>
                    <td><?php echo ($vo["username"]); ?></td>
                    <td>
                        <?php echo ($vo["telephone"]); ?>
                    </td>
                    <td>
                        <?php echo ($vo["addtime"]); ?>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr>
                <td colspan="2"></td>
                <td colspan="2" class="text-right">总合计：<?php echo (to_price($total)); ?></td>
                <td colspan="2">当页合计：<?php echo (to_price($pagetotal)); ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="7"><?php if(!empty($page)): ?><div class="pull-left custom-footer">
                        <ul class="pagination" style="margin: 0px;">
                            <?php echo ($page); ?>
                        </ul>
                    </div><?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    function exportxls(){
        var timefrom=$("#timefrom").val();
        var timeto=$("#timeto").val();
        var status=$("#sstatus").val();
        var act='order';
        window.location.href=ADMIN_PATH+"/Rbac/exportxls.html?timefrom="+timefrom+"&timeto="+timeto+"&act="+act+"&status="+status;
    }


    function cleartime(){
        $('#timefrom').val('');
        $('#timeto').val('');
    }
</script>
</body>
</html>