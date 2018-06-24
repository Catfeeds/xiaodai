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
<script> 
(function(){
	$.deleteKeyword=function(id){
		var url='<?php echo U("Wechat/keyword");?>';
		
		bootbox.confirm("您确定删除该记录吗?", function(result) {
			if (result) { 
			$.ajax({url:url,data:'id='+id,type:'POST',success:function(msg){
				if(msg.status==1){
					location.reload();	
				}else{
					alerterr(msg.info);	
				}
			}
			});
		}else{
			return false;	
		}
	});
	}
})(jQuery);
</script>
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
        <th>规则名</th>
        <th>关键词</th>
        <th>回复</th>
        <th width="140">操作</th>
          </thead>
      <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr >
            <td><?php echo ($vo["id"]); ?></td>
            <td><?php echo ($vo["name"]); ?></td>
            <td><?php echo ($vo["keywords"]); ?></td>
            <td><?php $arr=unserialize($vo['num']); ?>
              <?php echo (int)array_sum($arr);?>条（<?php echo (int)$arr[0];?>条文字， <?php echo (int)$arr[1];?>条图片， <?php echo (int)$arr[2];?>条图文）</td>
            <td><a href="<?php echo U($control.'/edit'.$action,'id='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> <a href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$vo['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <tr>
          <td colspan="5"><?php if(!empty($page)): ?><div class="pull-left custom-footer">
                <ul class="pagination" style="margin: 0px;">
                  <?php echo ($page); ?>
                </ul>
              </div><?php endif; ?>
            <?php if(!empty($list)): ?><div class="pull-right">
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