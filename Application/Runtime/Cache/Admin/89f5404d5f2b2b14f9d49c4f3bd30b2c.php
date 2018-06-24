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
                  <input type="text" class="form-control" placeholder="输入关键词，姓名，手机，身份证号" name="keyword" id="keyword" value="<?php echo ($keyword); ?>">
                 <span class="input-group-btn">
                  <button type="submit" class="btn fl"><i class="fa fa-search"></i> 查询</button>
                  </span> </div>
              </div></td>
          </tr>
        </tbody>
      </table>
    </form>
    <ol class="breadcrumb">
      <li><a onclick="getmemberbaogao('element3','运营商实名',0);" href="javascript:void(0);">手机实名</a></li>
      <li><a onclick="getmemberbaogao('carrier','运营商数据',0);" href="javascript:void(0);">运营商数据</a></li>
      <li><a onclick="getmemberbaogao('carrier_report','运营商报告',0);" href="javascript:void(0);">运营商报告</a></li>
      <li><a onclick="getmemberbaogao('element4','银行卡实名',0);" href="javascript:void(0);">银行卡实名</a></li>
      <!--<li><a onclick="getmemberbaogao('idcard_ocr','身份证信息',0);" href="javascript:void(0);">身份证信息</a></li>-->
      <!--<li><a onclick="getmemberbaogao('photo_compare','身份证两照对比',0);" href="javascript:void(0);">身份证两照对比</a></li>-->
      <!--<li><a onclick="getmemberbaogao('pbc','人行征信报告',0);" href="javascript:void(0);">人行征信报告</a></li>-->
      <li><a onclick="getmemberbaogao('black','网贷黑名单',0);" href="javascript:void(0);">网贷黑名单</a></li>
      <li><a onclick="getmemberbaogao('blackcrime','犯罪黑名单',0);" href="javascript:void(0);">犯罪黑名单</a></li>
      <!--<li><a onclick="getmemberbaogao('anti_fraud','反欺诈',0);" href="javascript:void(0);">反欺诈</a></li>-->
      <!--<li><a onclick="getmemberbaogao('blackcourt','高法黑名单',0);" href="javascript:void(0);">高法黑名单</a></li>-->
      <!--<li><a href="javascript:void(0);">社保信息</a></li>-->
      <!--<li><a onclick="getmemberbaogao('jd','京东数据',0);" href="javascript:void(0);">京东数据</a></li>-->
      <li><a onclick="getmemberbaogao('taobao','淘宝数据',0);" href="javascript:void(0);">淘宝数据</a></li>
      <li><a onclick="getmemberbaogao('alipay','支付宝数据',0);" href="javascript:void(0);">支付宝数据</a></li>
    </ol>
    <table class="table  table-first-column-number display full table-striped custom-table vm">
      <thead>
      <tr>
        <th></th>
        <th width="80">#</th>
        <th>用户名</th>
        <th>身份证号</th>
        <th>联系电话</th>
        <th>注册时间</th>

        <th width="80">状态</th>
        <th width="140">操作</th>
          </thead>
      <tbody>
        <?php if(is_array($contentlist)): $i = 0; $__LIST__ = $contentlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input type="checkbox" name="selectids" value="<?php echo ($vo["id"]); ?>"></td>
            <td><?php echo ($vo["id"]); ?></td>
            <td>
              <?php echo ($vo["username"]); ?>
              <?php if($vo['cominfo']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善资料">资</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善资料">资</span><?php endif; ?>
              <?php if($vo['contacts']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善紧急联系人信息">联</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善紧急联系人信息">联</span><?php endif; ?>
              <?php if($vo['tmobile']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善运营商信息">运</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善运营商信息">运</span><?php endif; ?>
              <?php if($vo['bank']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善银行卡信息">银</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善银行卡信息">银</span><?php endif; ?>
              <?php if($vo['zfb']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善支付宝认证">支</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善支付宝认证">支</span><?php endif; ?>
              <?php if($vo['taobao']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善淘宝认证">淘</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善淘宝认证">淘</span><?php endif; ?>
              <?php if($vo['gz']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善淘宝认证">作</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善淘宝认证">作</span><?php endif; ?>
              <?php if($vo['zmf']): ?><span style="background-color: #00b7ee;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="已完善淘宝认证">芝</span>
                <?php else: ?>
                <span style="background-color: gray;padding: 0 5px;color:white;border-radius: 5px;" rel="tooltip" data-original-title="未完善淘宝认证">芝</span><?php endif; ?>
            </td>
            <td><?php echo ($vo["idcard"]); ?></td>
            <td><?php echo ($vo["telephone"]); ?></td>
            <td><?php echo ($vo["addtime"]); ?></td>

             <td><?php if(($vo["status"]) == "1"): ?><a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($vo["id"]); ?>,0,this,'禁用')" class="btn btn-link"  rel="tooltip" data-original-title="点击禁用"><i class="fa fa-check-circle"></i> 启用</a>
                <?php else: ?>
                <a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($vo["id"]); ?>,1,this,'启用')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击启用"><i class="fa fa-times-circle"></i> 禁用</a><?php endif; ?></td>
            <td>
              <!--<a href="<?php echo U($control.'/balance','searchtype=1&keyword='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="余额记录"><i class="fa fa-dollar"></i></a>-->
            <a href="<?php echo U($control.'/edit'.$action,'id='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="查看"><i class="fa fa-edit"></i></a> <a href="javascript:void(0);" onclick="setDelete('<?php echo U($control.'/delete'.$action,"id=".$vo['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a></td>
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
<style>
  #info ul{
    width: 100%;
    height: 30px;
    padding-left: 0px;
    list-style: none;
    border-bottom: 1px dashed lightgray;
  }
  #info ul li{
    float: left;
    width: 25%;
    height: 30px;
    line-height: 30px;
    cursor: pointer;
  }
  #info ul li.active{
    background-color: #00a0e9;
    color:white;
  }
  #info{margin-top: 10px;}
  #info table{
    width:100%;
  }
  #info table tr{
    border-bottom: 1px dashed lightgray;
    margin-top: 5px;
    height: 30px;
  }

  #info1{margin-top: 10px;}
  #info1 table{
    width:100%;
  }
  #info1 table tr{
    border-bottom: 1px dashed lightgray;
    margin-top: 5px;
    height: 30px;
  }
</style>
<div id="mask" style="display:none;position: fixed;top: 0px;left:0px; width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 999;">
  <div id="mainbody" style="position: relative;top: 0px;left:0px;margin: 5% 10%;width: 80%;height: 80%; background-color: white;box-shadow:10px 10px 5px #0000004f;">
    <div id="maintitle" style="position: relative;width: 100%;height:6%;line-height:30px; text-align:center;border-bottom: 1px dashed lightgray;"><span id="masktitle">接口数据</span> <span onclick="closemask(0)" style="margin-right: 10px;cursor: pointer;" class="pull-right"><i class="fa fa-close"></i></span></div>
    <div id="info" style="text-align: center;overflow-y:scroll;height: 90%;width: 100%;">


    </div>
    <div id="loadingimg" style="display:none;position:absolute;width: 30%;margin-left: 35%;top: 30px;;"><img src="/Public/Home/images/loading.gif"/></div>
  </div>
</div>
<script type="text/javascript">
  var pagesize=7;
  var totalpage=1;
  var nowpage=1;
  var infoindex=0;

  $("body").on('click','#info ul li', function () {
    $(this).addClass("active").siblings("li").removeClass("active");
    var index=$(this).index();
    $("#info"+index).show().siblings("div").hide();
    infoindex=index;
	$("#loadingimg").show();
    initpage();
  });


  function initpage(){
    var length=$("#info"+infoindex+" tr").size();
    var totalnum=length-2;
    totalpage=totalnum/pagesize;
    totalpage=Math.ceil(totalpage);
    nowpage=1;
    $("#info"+infoindex+"nowpage").html(nowpage);
    $("#info"+infoindex+"totalpage").html(totalpage);
    showpage();

  }
  function prevpage(){
    if(nowpage<=1){
      return;
    }
    nowpage--;
    $("#info"+infoindex+"nowpage").html(nowpage);
    showpage();
  }
  function nextpage(){
    if(nowpage>=totalpage){
      return;
    }
    nowpage++;
    $("#info"+infoindex+"nowpage").html(nowpage);
    showpage();
  }

  function showpage(){
    var start=pagesize*(nowpage-1);
    var end=pagesize*nowpage-1;
   
    $("#loadingimg").show();
    $("#info"+infoindex+" tr:gt("+start+"):lt("+pagesize+")").show();
    $("#info"+infoindex+" tr:lt("+start+")").hide();
    $("#info"+infoindex+" tr:gt("+end+")").hide();
    $("#loadingimg").hide();
	
    $("#info"+infoindex+" tr:first").show();
    $("#info"+infoindex+" tr:last").show();
  }


  function exportmember(){
    var timefrom=$("#timefrom").val();
    var timeto=$("#timeto").val();
    window.location.href=ADMIN_PATH+"/Member/expmember.html?timefrom="+timefrom+"&timeto="+timeto;
  }

  function getmemberbaogao(type,title,retry){
	var id="";
    var i=0;
    $(".custom-table input:checked[name='selectids']").each(function() {
      id += $(this).val() + ",";
      i++;
    });

    if(i<=0){
      alerterr("请选择要查询的会员信息");return;
    }
    if(i>2){
      alerterr("单次只能查询一个会员的数据");return;
    }
    $("#masktitle").html(title);
    closemask(1);
    $("#info").html(" <img id=\"loadingimg\" style=\"position: relative;height: 100%;\" src=\"/Public/Home/images/loading.gif\"/>");
    $.ajax({
      url:ADMIN_PATH+"/Member/getinterfacedata.html",
      data:{type:type,id:id,retry:retry},
      type:"POST",
      success: function (data) {
        if(data.status==1){
          $("#info").html(data.html);
        }else{
          $("#info").html(data.html);
          if(retry==0){
            setTimeout(function () {
              bootbox.confirm("上次获取接口数据失败，是否重新获取？", function(result) {
                if (result) {
                  getmemberbaogao(type,title,1);
                };
              });
            },1000);
          }

        }

      }
    })


  }

  function closemask(val){
    if(val==1){
      $("#mask").fadeIn();
    }else{
      $("#mask").fadeOut();
    }
  }

</script>
</body>
</html>