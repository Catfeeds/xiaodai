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
            <td><div class="col-md-6">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="输入关键词" name="keyword" id="keyword" value="<?php echo ($keyword); ?>">
                  <span class="input-group-btn">
                  <select class="btn" name="searchtype" id="searchtype">
                    <option value="0">标题</option>
                    <?php if(($searchtype) == "1"): ?><option value="1" selected="selected">内容</option>
                      <?php else: ?>
                      <option value="1">内容</option><?php endif; ?>
                  </select>
                  <select class="btn" name="pid" id="pid">
                    <option value="">--选择分类--</option>
                    
                   <?php echo R('Setting/treeselect', array($list));?> 
                
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
      <th></th>
        <th width="80">#</th>
        <th>标题</th>
        <th>分类</th>
        <th width="80">推荐</th>
        <th width="80">排序</th>
        <th width="80">状态</th>
        <th width="140">操作</th>
          </thead>
      <tbody>
        <?php if(is_array($contentlist)): $i = 0; $__LIST__ = $contentlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input type="checkbox" name="selectids" value="<?php echo ($vo["id"]); ?>"></td>
            <td><?php echo ($vo["id"]); ?></td>
            <td><?php echo ($vo["title"]); ?></td>
            <td><?php echo get_cache_value('category_news',$vo['pid'],'name');?></td>
            <td><?php if(($vo["isresume"]) == "1"): ?><a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','isresume',<?php echo ($vo["id"]); ?>,0,this,'默认')" class="btn btn-link fc_blue"  rel="tooltip" data-original-title="点击设置为默认"><i class="fa fa-arrow-up"></i> 推荐</a>
                <?php else: ?>
                <a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','isresume',<?php echo ($vo["id"]); ?>,1,this,'推荐')" class="btn btn-link "  rel="tooltip" data-original-title="点击设置为推荐"><i class="fa"></i> 默认</a><?php endif; ?></td>
             <td><input class="form-control" onchange="setVal('<?php echo ($tblname); ?>','sort',<?php echo ($vo["id"]); ?>,$(this).val())"  value="<?php echo ($vo["sort"]); ?>" /></td>
             <td><?php if(($vo["status"]) == "1"): ?><a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($vo["id"]); ?>,0,this,'禁用')" class="btn btn-link"  rel="tooltip" data-original-title="点击禁用"><i class="fa fa-check-circle"></i> 启用</a>
                <?php else: ?>
                <a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($vo["id"]); ?>,1,this,'启用')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击启用"><i class="fa fa-times-circle"></i> 禁用</a><?php endif; ?></td>
            <td><a href="<?php echo U($control.'/edit'.$action,'id='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> <a href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$vo['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <tr>
          <td colspan="8"><?php if(!empty($page)): ?><div class="pull-left custom-footer">
                <ul class="pagination" style="margin: 0px;">
                  <?php echo ($page); ?>
                </ul>
              </div><?php endif; ?> 
            <?php if(!empty($contentlist)): ?><div class="pull-right">
                <div class="btn-group dropup">
                  <input type="hidden" value="<?php echo ($tblname); ?>" id="ConstTbl" name="ConstTbl" />
                  <button id="AllCheck"  class="btn" title="全选"><i class="fa fa-check-square"></i></button>
                  <button id="ReverseCheck"  class="btn" title="反选"><i class="fa fa-check-square-o"></i></button>
                  <button class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="操作"><i class="fa fa-wrench"></i> <span class="caret"></span></button>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li><a class="AllStatus" data-status="0" href="javascript:void(0);"><i class="fa fa-lock"></i> <?php echo ($statuslist[0]); ?></a></li>
                    <li><a class="AllStatus" data-status="1" href="javascript:void(0);"><i class="fa fa-unlock-alt"></i> <?php echo ($statuslist[1]); ?></a></li>
                    <li class="divider"></li>
                    <li><a id="AllDel" href="javascript:void(0);"><i class="fa fa-trash"></i> 删除</a></li>
                  </ul>
                </div>
              </div><?php endif; ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>