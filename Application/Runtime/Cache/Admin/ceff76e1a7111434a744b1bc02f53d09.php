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
</head>
<body>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-12 custom-tool" >
        <h2><?php echo ($title); ?></h2>
        <div class="pull-right"> <a href="<?php echo U($control.'/add'.$action);?>" class="btn btn-success" title="添加"><i class="fa fa-plus"></i> 添加</a> </div>
      </div>
      <table class="table  table-first-column-number display full table-striped custom-table vm">
        <thead> 
            <th width="50">#</th>
            <th>角色名称</th>
            <th>角色描述</th>
            <th width="80">排序</th>
            <th width="80">状态</th>
            <th width="140">操作</th>
        </thead>
        <tbody>
          <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="row<?php echo ($i % 2+1); ?>">
              <td><?php echo ($vo["id"]); ?></td>
              <td><?php echo ($vo["name"]); ?></td>
              <td><?php echo ($vo["remark"]); ?>&nbsp;</td>
              <td><input class="form-control" onchange="setVal('<?php echo ($tblname); ?>','sort',<?php echo ($vo["id"]); ?>,$(this).val())"  value="<?php echo ($vo["sort"]); ?>" /></td>
              <td>
              <?php if(($vo["status"]) == "1"): ?><a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($vo["id"]); ?>,0,this,'禁用')" class="btn btn-link"  rel="tooltip" data-original-title="点击禁用"><i class="fa fa-check-circle"></i> 启用</a>
                  <?php else: ?>
                  <a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($vo["id"]); ?>,1,this,'启用')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击启用"><i class="fa fa-times-circle"></i> 禁用</a><?php endif; ?>
              </td>
              <td>
            
              <a href="<?php echo U($control.'/edit'.$action,'id='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> 
              <a href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$vo['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?> 
          </tbody>
      </table>
    </div>
  </div> 
</body>
</html>