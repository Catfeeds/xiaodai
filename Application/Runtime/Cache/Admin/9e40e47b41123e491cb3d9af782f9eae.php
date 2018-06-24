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
          <h2>你好，admin，欢迎使用本系统！ </h2>
          <div class="row">
          <div style="display: none;" class="col-md-6">
              <h2>系统概览</h2>
              <ul class="list-group">
                  <li class="list-group-item"><i class="icon-user"></i> 会员数<span class="badge badge-important"><?php echo ($statistic["member"]); ?></span></li> 
                  <li class="list-group-item"><i class="icon-envelope"></i> 订单数<span class="badge badge-important"><?php echo ($statistic["order"]); ?></span></li>
                  <li class="list-group-item"><i class="icon-comment"></i> 商品数<span class="badge badge-important"><?php echo ($statistic["product"]); ?></span></li> 
                  <!--<li class="list-group-item"><i class="icon-comment"></i> 优惠券<span class="badge badge-important"><?php echo ($statistic["coupon"]); ?></span></li> -->
              </ul>
          </div>
          <div class="col-md-6">
              <h2>登录信息</h2>
              <ul class="list-group">
                  <li class="list-group-item"><i class="icon-bar-chart"></i> 登录次数<span class="badge badge-info"><?php echo ($db["logtimes"]); ?></span></li>
                  <li class="list-group-item"><i class="icon-calendar"></i> 上次登录时间<span class="pull-right"><?php echo ($db["lastlogtime"]); ?></span></li>
                  <li class="list-group-item"><i class="icon-location-arrow"></i> 上次登录IP<span class="pull-right"><?php echo ($db["lastlogip"]); ?></span></li>
                  <li class="list-group-item"> <i class="icon-desktop"></i> 服务器环境
                    <span class="pull-right"><?php echo PHP_OS;?>，<?php echo ($_SERVER['SERVER_SOFTWARE']); ?>，PHP <?php echo phpversion();?></span></li> 
              </ul>
          </div>
          </div>
 
        </div>
    </div>

    <div style="display: none;" class="row">
        <h2 style="padding-left:20px;">你好，<?php echo (session('adminname')); ?>，欢迎使用本系统！ </h2>
        <div id="detail" class="col-md-12">
            <div class="panel panel-default col-md-12" style="padding: 0px;">
            <div class="panel-heading">
                <h3 class="panel-title fl">系统概览（客户情况）</h3>
                <style>
                    .breadcrumb  .active{color:red;}
                    .breadcrumb li a{color: black;}
                </style>
                <ol style="margin: 0px 0px 0px 20px; padding:0px;" class="breadcrumb fl">
                    <style>
                        .breadcrumb .active a{color: red;}
                    </style>
                    <li class="active"><a href="javascript:void(0);">7</a>天</li>
                    <li><a href="javascript:void(0);">14</a>天</li>
                    <li><a href="javascript:void(0);">30</a>天</li>
                </ol>
                &nbsp;&nbsp;
                <select id="staffid" class=" w150" onchange="getanalysis('index')">
                    <option value="">选择员工</option>
                    <?php if(is_array($staff)): $i = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <div class="panel-body">
                <div id="detailchart" style="position:relative;width: 1200px;height: 300px;"></div>
            </div>

        </div>
        </div>
    </div>
    <script src="/Public/Admin/lib/echarts/echarts.js"></script>
    <script type="text/javascript">
        $(".breadcrumb li").click(function () {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            getanalysis('index');
        });

        function getanalysis(type){
            var ddays=$(".breadcrumb li.active a").html();
            type='index';
            var staffid=$("#staffid").val();

            $.ajax({
                url:ADMIN_PATH+"/Api/getcustom.html",
                data:{type:type,ddays:ddays,staffid:staffid},
                type:"POST",
                success: function (data) {
                    switch(type){
                        case 'index':
                            var myChart = echarts.init(document.getElementById("detailchart"));
                            var option = {
                                title : {
                                    text: '',
//                                subtext: '纯属虚构'文章趋势图
                                },
                                tooltip : {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data:['新增客户','跟进客户','成交客户']
                                },
                                //右上角工具条
                                toolbox: {
                                    show : true,
                                    feature : {
                                        mark : {show: true},
                                        dataView : {show: true, readOnly: false},
                                        magicType : {show: true, type: ['line', 'bar']},
                                        restore : {show: true},
                                        saveAsImage : {show: true}
                                    }
                                },
                                calculable : true,
                                xAxis : [
                                    {
                                        type : 'category',
                                        boundaryGap : false,
                                        data : data.dates
                                    }
                                ],
                                yAxis : [
                                    {
                                        type : 'value',
                                        axisLabel : {
                                            formatter: '{value}'
                                        }
                                    }
                                ],
                                series : [
                                    data.news,
                                    data.sees,
                                    data.gens,
                                    data.coms
                                ]
                            };

                            // 为echarts对象加载数据
                            myChart.setOption(option);




                        default:
                            break;
                    }
                }
            });
            //$("#echarts").html(type);
        }
        getanalysis('index');

    </script>
</body>
</html>