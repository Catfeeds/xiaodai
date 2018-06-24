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
        <th>节点名称</th>
        <th>描述</th>
        <th width="80">常用</th>
        <th width="80">排序</th>
        <th width="80">状态</th>
        <th width="140">操作</th>
          </thead>
      <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr >
            <td><?php echo ($vo["id"]); ?></td>
            <td><?php echo ($vo["title"]); ?>【<?php echo ($vo["name"]); ?>】</td>
            <td><?php echo ($vo["remark"]); ?>&nbsp;</td>
             <td> </td>
                
            <td><input class="form-control" name="Item_1" id="Item_1" onchange="setVal('node','sort',<?php echo ($vo["id"]); ?>,$(this).val())"   value="<?php echo ($vo["sort"]); ?>" /></td>
            <td><?php if(($vo["status"] == 1)): ?><a 	href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($vo["id"]); ?>,0,this,'隐藏')" class="btn btn-link"  rel="tooltip" data-original-title="点击隐藏"><i class="fa fa-check-circle"></i> 显示</a>
                <?php else: ?>
                <a href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($vo["id"]); ?>,1,this,'显示')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击显示"><i class="fa fa-times-circle"></i> 隐藏</a><?php endif; ?></td>
            <td><a href="<?php echo U('Rbac/addNode',Array("pid"=>$vo["id"],"level"=>$vo["level"]));?>" class="btn" rel="tooltip" data-original-title="添加"><i class="fa fa-plus"></i></a> <a href="<?php echo U('Rbac/editNode','id='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> <a  href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$vo['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
          </tr>
          <?php if(is_array($vo["child"])): $i = 0; $__LIST__ = $vo["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$action): $mod = ($i % 2 );++$i;?><tr >
              <td><?php echo ($action["id"]); ?></td>
              <td><?php echo(str_repeat("&nbsp;",4));?><i class="<?php echo ((isset($action["icon"]) && ($action["icon"] !== ""))?($action["icon"]):'fa fa-chevron-right'); ?>"></i> <?php echo ($action["title"]); ?>【<?php echo ($action["name"]); ?>】</td>
              <td><?php echo ($action["remark"]); ?>&nbsp;</td>
               <td> </td>
                
              <td><input class="form-control" name="Item_1" id="Item_1" onchange="setVal('node','sort',<?php echo ($action["id"]); ?>,$(this).val())"   value="<?php echo ($action["sort"]); ?>" /></td>
              <td><?php if(($action["status"] == 1)): ?><a 	href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($action["id"]); ?>,0,this,'隐藏')" class="btn btn-link"  rel="tooltip" data-original-title="点击隐藏"><i class="fa fa-check-circle"></i> 显示</a>
                  <?php else: ?>
                  <a href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($action["id"]); ?>,1,this,'显示')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击显示"><i class="fa fa-times-circle"></i> 隐藏</a><?php endif; ?></td>
              <td><a href="<?php echo U('Rbac/addNode',Array("pid"=>$action["id"],"level"=>$action["level"]));?>" class="btn" rel="tooltip" data-original-title="添加"><i class="fa fa-plus"></i></a> <a href="<?php echo U('Rbac/editNode','id='.$action['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> <a  href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$action['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
            </tr>
            <?php if(is_array($action["child"])): $i = 0; $__LIST__ = $action["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$method): $mod = ($i % 2 );++$i;?><tr >
                <td><?php echo ($method["id"]); ?></td>
                <td><?php echo(str_repeat("&nbsp;",8));?><i class="<?php echo ((isset($method["icon"]) && ($method["icon"] !== ""))?($method["icon"]):'fa fa-chevron-right'); ?>"></i> <?php echo ($method["title"]); ?>【<?php echo ($method["name"]); ?>】</td>
                <td><?php echo ($method["remark"]); ?>&nbsp;</td>
                 <td><?php if(($method["isresume"] == 1)): ?><a 	href="javascript:void(0);" 	onclick="setVal('node','isresume',<?php echo ($method["id"]); ?>,0,this,'普通')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击设置普通"><i class="fa fa-arrow-up"></i> 常用</a>
                <?php else: ?>
                <a href="javascript:void(0);" 	onclick="setVal('node','isresume',<?php echo ($method["id"]); ?>,1,this,'常用')" class="btn btn-link "  rel="tooltip" data-original-title="点击设置常用">普通</a><?php endif; ?></td>
                
                <td><input class="form-control" name="Item_1" id="Item_1" onchange="setVal('node','sort',<?php echo ($method["id"]); ?>,$(this).val())"   value="<?php echo ($method["sort"]); ?>" /></td>
                <td><?php if(($method["status"] == 1)): ?><a 	href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($method["id"]); ?>,0,this,'隐藏')" class="btn btn-link"  rel="tooltip" data-original-title="点击隐藏"><i class="fa fa-check-circle"></i> 显示</a>
                    <?php else: ?>
                    <a href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($method["id"]); ?>,1,this,'显示')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击显示"><i class="fa fa-times-circle"></i> 隐藏</a><?php endif; ?></td>
                <td><a href="<?php echo U('Rbac/addNode',Array("pid"=>$method["id"],"level"=>$method["level"]));?>" class="btn" rel="tooltip" data-original-title="添加"><i class="fa fa-plus"></i></a> 
                <a href="<?php echo U('Rbac/editNode','id='.$method['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> <a  href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$method['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
              </tr>
              <?php if(is_array($method["child"])): foreach($method["child"] as $key=>$son): ?><tr >
                <td><?php echo ($son["id"]); ?></td>
                <td><?php echo(str_repeat("&nbsp;",12));?><i class="<?php echo ((isset($son["icon"]) && ($son["icon"] !== ""))?($son["icon"]):'fa fa-chevron-right'); ?>"></i> <?php echo ($son["title"]); ?>【<?php echo ($son["name"]); ?>】</td>
                <td><?php echo ($son["remark"]); ?>&nbsp;</td>
                 <td><?php if(($son["isresume"] == 1)): ?><a 	href="javascript:void(0);" 	onclick="setVal('node','isresume',<?php echo ($son["id"]); ?>,0,this,'普通')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击设置普通"><i class="fa fa-arrow-up"></i> 常用</a>
                <?php else: ?>
                <a href="javascript:void(0);" 	onclick="setVal('node','isresume',<?php echo ($son["id"]); ?>,1,this,'常用')" class="btn btn-link "  rel="tooltip" data-original-title="点击设置常用">普通</a><?php endif; ?></td>
                
                <td><input class="form-control" name="Item_1" id="Item_1" onchange="setVal('node','sort',<?php echo ($son["id"]); ?>,$(this).val())"   value="<?php echo ($son["sort"]); ?>" /></td>
                <td><?php if(($son["status"] == 1)): ?><a 	href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($son["id"]); ?>,0,this,'隐藏')" class="btn btn-link"  rel="tooltip" data-original-title="点击隐藏"><i class="fa fa-check-circle"></i> 显示</a>
                    <?php else: ?>
                    <a href="javascript:void(0);" 	onclick="setVal('node','status',<?php echo ($son["id"]); ?>,1,this,'显示')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击显示"><i class="fa fa-times-circle"></i> 隐藏</a><?php endif; ?></td>
                <td><a href="<?php echo U('Rbac/editNode','id='.$son['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> <a  href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$son['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
              </tr><?php endforeach; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>