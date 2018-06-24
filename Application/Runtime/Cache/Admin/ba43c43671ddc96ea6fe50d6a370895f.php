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
                      <option value="0">登录名</option>
                      <?php if(($searchtype) == "1"): ?><option value="1" selected="selected">姓名</option>
                          <?php else: ?>
                          <option value="1">姓名</option><?php endif; ?>
                  </select>
                      <select class="btn" name="departmentid" id="departmentid">
                          <option value="">部门</option>
                            <?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($departmentid == $vo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>


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
            <tr>
                <th></th>
                <th width="80">#</th>
                <th>登录名</th>
                <th>员工姓名</th>
                <th>部门</th>
                <th>联系电话</th>
                <th>职位</th>
                <th>注册码</th>
                <th width="80">状态</th>
                <th width="240">操作</th>
            </thead>
            <tbody>
            <?php if(is_array($contentlist)): $i = 0; $__LIST__ = $contentlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><input type="checkbox" name="selectids" value="<?php echo ($vo["id"]); ?>"></td>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["username"]); ?>
                        <?php if($vo['ismaster'] == 1): ?><span style="color:white;font-size:12px;background-color: #00B83F;border-radius: 3px;padding: 3px;">部门负责人</span><?php endif; ?>
                    </td>
                    <td><?php echo ($vo["name"]); ?></td>
                    <td><?php echo get_cache_value('department',$vo['departmentid'],'name');?></td>
                    <td><?php echo ($vo["telephone"]); ?></td>
                    <td><?php echo ($vo["position"]); ?></td>
                    <td><?php echo ($vo["registercode"]); ?></td>
                    <td>
                        <?php if(($vo["status"]) == "1"): ?><a data-toggle="modal" data-target="#myModal" onclick="showmodal('<?php echo ($vo["id"]); ?>',1);"
                            href="javascript:void(0);" class="btn btn-link"  rel="tooltip" data-original-title="设置为离职"><i class="fa fa-check-circle"></i> 在职</a>
                        <?php else: ?>
                        <a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($vo["id"]); ?>,1,this,'启用')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="设置为在职"><i class="fa fa-times-circle"></i> 已离职</a><?php endif; ?></td>
                    <td>

                        <a data-toggle="modal" data-target="#myModal" onclick="showmodal('<?php echo ($vo["id"]); ?>',2);"
                           href="javascript:void(0);" class="btn"  rel="tooltip" data-original-title="转移客户"><i class="fa fa-arrow-right"></i></a>

                        <a href="<?php echo U($control.'/membermy','staffid='.$vo['registercode']);?>" class="btn" rel="tooltip" data-original-title="查看私有客户"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo U($control.'/edit'.$action,'id='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$vo['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr>
                <td colspan="11"><?php if(!empty($page)): ?><div class="pull-left custom-footer">
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
                                </ul>
                            </div>
                        </div><?php endif; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="motitle">设置离职</h4>
            </div>
            <div class="modal-body">
                <input id="id" type="hidden" value=""/>
                <input type="hidden" value="" id="act">
                <span style="font-size: 12px;color: red;">
                    员工名下客户操作
                </span>
                <br/>
                <input type="radio" name="opra" value="1"/>返回公海&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="opra" value="2"/>转移给员工
                <br/>
                <br/>
                <select class="form-control" name="tostaffid" id="tostaffid">
                    <option value="">选择要转移的员工</option>
                    <?php if(is_array($allstaff)): $i = 0; $__LIST__ = $allstaff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <br/>
                 <span style="font-size: 12px;color: red;" id="motip">
                    设置员工离职后，员工不能登录，且须将员工名下的客户转移到其它员工或者放回公海
                </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="confirmset()">确定</button>
            </div>
        </div>
    </div>
</div>
<script>

    function confirmset(){
        var id=$("#id").val();
        var tostaffid=$("#tostaffid").val();
        var opra=$("input[name='opra']:checked").val();//1-放入公海，2-转移给员工
        var act=$("#act").val();//act==1:设置离职，2-转移员工客户
        if($.trim(opra)==""||$.trim(opra)==null){
            alerterr("请选择要对该员工下的客户执行的操作");
        }

        if(parseInt(opra)==2){
            if($.trim(tostaffid)==""||$.trim(tostaffid)==null){
                alerterr("请选择要转移的目标员工");return;
            }
        }
        $.ajax({
            url:ADMIN_PATH+"/Api/transformmember.html",
            data:{id:id,tostaffid:tostaffid,opra:opra,act:act},
            type:"POST",
            success: function (data) {
                if(data.status==1){
                    alertok(data.info);
                    setTimeout(function () {
                        reloadwin();
                    },500);
                }else{
                    alerterr(data.info);
                }
            }
        })
    }

    function showmodal(id,op){
        $("#id").val(id);
        $("#act").val(op);
        var title="";
        var tip="";
        if(op==1){
            title="设置离职";
            tip="设置员工离职后，员工不能登录，且须将员工名下的客户转移到其它员工或者放回公海"
        }else{
            title="转移客户";
            tip="设置转移客户，可将客户转移给员工，也可将客户转移至公海"
        }
        $("#motitle").html(title);
        $("#motip").html(tip);
    }
</script>
</body>
</html>