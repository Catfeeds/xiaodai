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
  <form action="" method="get" class="ajaxformx_" name="form1" id="form1">
      <input type="hidden" name="p" id="p" value="1" />
  <input type="hidden" name="status" id="status" value="<?php echo ($status); ?>" />
    <table class="table vm">
      <thead>
      <th colspan="2">筛选</th>
          </thead>
      <tbody>
        <tr>
          <td  class="text-right" width="140">状态：</td>
          <td><div class="col-md-12"> <a href="<?php echo set_url('status','');?>" ><span class="label label-default">全部</span></a>
              <?php if(is_array($statuslist)): $i = 0; $__LIST__ = $statuslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($status) == $key): if(isN($status) == true): ?><a href="<?php echo set_url('status',$key);?>"><span class="label label-default"><?php echo ($statuslist["$key"]); ?></span></a>
                    <?php else: ?>
                    <a href="<?php echo set_url('status',$key);?>" ><span class="label label-info"><?php echo ($statuslist["$key"]); ?></span></a><?php endif; ?>
                  <?php else: ?>
                  <a href="<?php echo set_url('status',$key);?>"  ><span class="label label-default"><?php echo ($statuslist["$key"]); ?></span></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </div></td>
        </tr>
        <tr>
          <td  class="text-right">关键词：</td>
          <td><div class="col-md-4">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="输入关键词" name="keyword" id="keyword" value="<?php echo ($keyword); ?>">
                <span class="input-group-btn">
                <select class="btn" name="searchtype" id="searchtype">
                  <option value="0">名称</option>
                  <?php if(($searchtype) == "1"): ?><option value="1" selected="selected">备注</option>
                    <?php else: ?>
                    <option value="1">备注</option><?php endif; ?>
                </select>
                </span> <span class="input-group-btn">
                <button type="submit" class="btn fl"><i class="fa fa-search"></i> 查询</button>
                </span> </div>
            </div></td>
        </tr>
      </tbody>
    </table>
    </form>
    <table class="table  table-first-column-number display full table-striped custom-table vm">
      <thead>
      <th>#</th>
        <th>分类名称</th>
        <th>分类描述</th>
        <th width="80">排序</th>
        <th width="80">状态</th>
        <th width="140">操作</th>
          </thead>
      <tbody> 
      <?php echo R('Setting/treelist', array($list));?>
        </tbody>
      
    </table>
  </div>
</div>
</body>
</html>