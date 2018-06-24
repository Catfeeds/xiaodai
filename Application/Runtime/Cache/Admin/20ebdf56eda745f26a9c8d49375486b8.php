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
    <script language="javascript">
        (function(){
            //退款
            $.refund=function($orderno){
                var url="<?php echo U('Member/refund');?>";

                bootbox.confirm("您确定给该订单退款吗？款项将原路退回！", function(result) {
                    if (result) {
                        $.ajax({url:url,data:'out_trade_no='+$orderno,success: function(data){
                            if (!(data.toString()).substr(0,1)=="{")
                            {
                                alerterr("对不起，支付证书错误！");
                            }else{
                                if(data.status=="1"){
                                    alertok(data.info);
                                }else{
                                    alerterr(data.info);
                                }
                            }
                        }
                        });
                    }
                });
            };

            //退款查询
            $.refundQuery=function($orderno){
                var url="<?php echo U('Member/refundQuery');?>";
                $.ajax({url:url,data:'out_trade_no='+$orderno,success: function(data){
                    //if (!(data.toString()).match("^\{(.+:.+,*){1,}\}$"))
                    if (!(data.toString()).substr(0,1)=="{")
                    {
                        alerterr("对不起，支付证书错误！");
                    }else{
                        if(data.status=="1"){
                            alertok(data.info);
                        }else{
                            alerterr(data.info);
                        }
                    }
                }
                });
            };

        })(jQuery);
    </script>
</head>
<body>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-12 custom-tool" >
            <h2><?php echo ($title); ?></h2>
            <div style="display: none;" class="pull-right"> <a href="<?php echo U($control.'/statistic');?>" class="btn btn-success" title="订单统计"><i class="fa fa-bar-chart"></i> 订单统计</a> </div>
        </div>
        <form action="" method="get" class="ajaxformx_" name="form1" id="form1">
            <input type="hidden" name="p" id="p" value="1" />
            <input type="hidden" name="status" id="status" value="<?php echo ($status); ?>" />
            <table class="table vm">
                <thead>
                <th colspan="2">筛选</th>
                </thead>
                <tbody>
                <tr style="display: none;">
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
                      <option value="0">订单号</option>
                      <?php if(($searchtype) == "1"): ?><option value="1" selected="selected">姓名</option>
                          <?php else: ?>
                          <option value="1">姓名</option><?php endif; ?>

                      <?php if(($searchtype) == "2"): ?><option value="2" selected="selected">联系电话</option>
                          <?php else: ?>
                          <option value="2">联系电话</option><?php endif; ?>
                      <?php if(($searchtype) == "3"): ?><option value="3" selected="selected">身份证号</option>
                          <?php else: ?>
                          <option value="3">身份证号</option><?php endif; ?>
                  </select>
                  </span> <span class="input-group-btn">
                  <button type="submit" class="btn fl"><i class="fa fa-search"></i> 查询</button>
                  </span> </div>
                    </div></td>
                </tr>

                </tbody>
            </table>
        </form>
        <ol class="breadcrumb">
            <li><a onclick="getmemberbaogao('element3','运营商实名',0);" href="javascript:void(0);">运营商实名</a></li>
            <li><a onclick="getmemberbaogao('carrier','运营商数据',0);" href="javascript:void(0);">运营商数据</a></li>
            <li><a onclick="getmemberbaogao('carrier_report','运营商报告',0);" href="javascript:void(0);">运营商报告</a></li>
            <li><a onclick="getmemberbaogao('element4','银行卡实名',0);" href="javascript:void(0);">银行卡实名</a></li>
            <li><a onclick="getmemberbaogao('idcard_ocr','身份证信息',0);" href="javascript:void(0);">身份证信息</a></li>
            <!--<li><a onclick="getmemberbaogao('photo_compare','身份证两照对比',0);" href="javascript:void(0);">身份证两照对比</a></li>-->
            <!--<li><a onclick="getmemberbaogao('pbc','人行征信报告',0);" href="javascript:void(0);">人行征信报告</a></li>-->
            <li><a onclick="getmemberbaogao('black','网贷黑名单',0);" href="javascript:void(0);">网贷黑名单</a></li>
            <!--<li><a onclick="getmemberbaogao('blackcrime','犯罪黑名单',0);" href="javascript:void(0);">犯罪黑名单</a></li>-->
            <!--<li><a onclick="getmemberbaogao('blackcourt','高法黑名单',0);" href="javascript:void(0);">高法黑名单</a></li>-->
            <!--<li><a href="javascript:void(0);">社保信息</a></li>-->
            <!--<li><a onclick="getmemberbaogao('jd','京东数据',0);" href="javascript:void(0);">京东数据</a></li>-->
            <!--<li><a onclick="getmemberbaogao('taobao','淘宝数据',0);" href="javascript:void(0);">淘宝数据</a></li>-->
            <li><button style="display: none;" id="showmodel" data-toggle="modal" data-target="#myModal1"></button><a class="btn" href="javascript:void(0);" onclick="showxiaoji();" >写小计</a></li>
        </ol>
        <table class="table  table-first-column-number display full table-striped custom-table vm">
            <thead>
            <tr>
                <th>#</th>
                <th width="170">订单号</th>
                <th>用户名</th>
                <th>身份证号</th>
                <th>联系电话</th>
                <th>贷款金额/利息</th>
                <th>利率</th>
                <th>期限</th>
                <th>申请时间</th>
                <th>放款时间</th>
                <th>用户确认</th>
                <th width="80">状态(点击修改)</th>
                <th width="150">操作</th>
            </thead>
            <tbody>
            <?php if(is_array($contentlist)): $i = 0; $__LIST__ = $contentlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><input type="checkbox" name="selectids" value="<?php echo ($vo["memberid"]); ?>"></td>
                    <td><?php echo ($vo["orderno"]); ?></td>
                    <td><a href="<?php echo U('Member/editMember','id='.$vo['memberid']);?>"><?php echo get_cache_value('member',$vo['memberid'],'username');?></a></td>
                    <td><?php echo ($vo["idcard"]); ?></td>
                    <td><?php echo ($vo["telephone"]); ?></td>
                    <td><?php echo ($vo["damount"]); ?>/<?php echo ($vo["interest"]); ?></td>
                    <td><?php echo ($vo["interestrate"]); ?>%</td>
                    <td><?php echo ($vo["days"]); ?>天</td>
                    <td><?php echo ($vo["addtime"]); ?></td>
                    <td><?php echo ($vo["paiedtime"]); ?></td>
                    <td><?php echo ($vo['status1']?'确认':'未确认'); ?></td>
                    <td  data-toggle="modal" data-target="#myModal" onclick="showmodal('<?php echo ($vo["id"]); ?>','<?php echo ($vo["status"]); ?>')">

                        <?php if(($vo['deadline'] < date('Y-m-d H:i:s')) and ($vo['status'] >= 2) and ($vo['status'] < 4)): $noticenum=M('loan_notice')->where(array('orderno'=>$vo['orderno'],'type'=>1))->count(); if($noticenum<=0){ $tip="未提醒"; }else{ $tip="已提醒".$noticenum."次"; } ?>
                            <span style="color: red;">已逾期<span style="color: #00b7ee;">[<?php echo ($tip); ?>]</span></span>
                            <?php else: ?>
                            <?php
 $lefts=diffBetweenTwoDaysreal(date('Y-m-d H:i:s'),$vo['deadline']); $lefts=ceil($lefts); $stan=C('config.LOAN_DAOQI'); if($lefts<=$stan){ $noti=1; }else{ $noti=0; } ?>

                            <?php if(($noti == 1) and ($vo['status'] < 4)): ?><span style="color: red;">还剩<?php echo ($lefts); ?>天到期</span><?php endif; ?>
                            <br/>
                            <?php echo $statuslist[$vo['status']];?>
                            <?php if($vo['status'] == 1): echo ($vo['shenhestatus']?'已通过':'<span style="color: red;">被拒绝</span>'); endif; endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($vo["refundinfo"])): ?><!--<a href="javascript:void(0);" class="btn" rel="tooltip" onClick="$.refundQuery('<?php echo ($vo["orderno"]); ?>')" data-original-title="查询退款"><i class="fa fa-exclamation-circle"></i></a> --><?php endif; ?>
                        <?php if(($vo["paystatus"]) == "1"): if(in_array(($vo["status"]), explode(',',"1,2,3"))): ?><!--<a href="javascript:void(0);" class="btn" onClick="$.refund('<?php echo ($vo["orderno"]); ?>')"  rel="tooltip" data-original-title="立即退款"><i class="fa fa-dollar"></i></a>--><?php endif; endif; ?>
                        <a href="<?php echo U($control.'/edit'.$action,'id='.$vo['id']);?>" class="btn" rel="tooltip" data-original-title="查看"><i class="fa fa-eye"></i></a>
                        <?php if(($vo['deadline'] < date('Y-m-d H:i:s')) and ($vo['status'] >= 2) and ($vo['status'] < 4)): ?><a href="javascript:void(0);" onclick="noticeoverdue('<?php echo ($vo["id"]); ?>')" class="btn" rel="tooltip" data-original-title="逾期提醒"><i class="fa fa-warning"></i></a><?php endif; ?>

                        <?php if(empty($vo["url"])): ?><a href="<?php echo U('Member/getContract','orderno='.$vo['orderno']);?>"  class="btn">生成合同</a>
                         <?php else: ?>
                         <a href="<?php echo ($vo["url"]); ?>"  class="btn">查看合同</a><?php endif; ?>


                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr>
                <td colspan="12"><?php if(!empty($page)): ?><div class="pull-left custom-footer">
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
        <div id="loadingimg" style="display:none;z-index:9999;position:absolute;width: 30%;margin-left: 35%;top: 30px;;"><img src="/Public/Home/images/loading.gif"/></div>

    </div>
</div>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title">审核贷款</h4>
            </div>
            <div class="modal-body">
                <input id="orderid" type="hidden" value=""/>
                <br/>
                <select onchange="checkorderstatus()" name="orderstatus" id="orderstatus" class="form-control">
                    <?php if(is_array($statuslist)): $i = 0; $__LIST__ = $statuslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <hr/>
                <select onchange="checkshenhestatus()" style="display: none;" name="shenhestatus" id="shenhestatus" class="form-control">
                    <option value="">选择是否通过</option>
                    <option value="0">不通过</option>
                    <option value="1">通过</option>
                </select>
                <hr/>
                <textarea style="display: none;" rows="5" placeholder="输入拒绝理由" id="msg" class="form-control"></textarea>
                <input  style="display: none; " type="text" placeholder="输入审批金额" id="money" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="confirmshenhe()">确认</button>
            </div>
        </div>
    </div>
</div>

<div id="myModal1" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title">写小计</h4>
            </div>
            <div class="modal-body">
                <input id="xiaojiid" type="hidden" value=""/>
                <textarea rows="5" placeholder="输入小计" id="content" class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="confirmxiaoji()">确认</button>
            </div>
        </div>
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
        var start=pagesize*(nowpage-1)+1;
        var end=pagesize*nowpage;
        $("#loadingimg").show();
        $("#info"+infoindex+" tr:gt("+start+"):lt("+pagesize+")").show();
        $("#info"+infoindex+" tr:lt("+start+")").hide();
        $("#info"+infoindex+" tr:gt("+end+")").hide();
        $("#loadingimg").hide();
        $("#info"+infoindex+" tr:first").show();
        $("#info"+infoindex+" tr:last").show();
    }




    function showxiaoji(){
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
        $("#xiaojiid").val(id);
        $("#showmodel").click();
    }

    function confirmxiaoji(){
        var id=$("#xiaojiid").val();
        var content=$("#content").val();
        if($.trim(content)==""){
            alerterr("请输入小计内容");return;
        }

        $.ajax({
            url:ADMIN_PATH+"/Api/writexiaoji.html",
            data:{id:id,content:content},
            type:"POST",
            success: function (data) {
                if(data.status==1){
                    alertok(data.info);
                    setTimeout(function () {
                        reloadwin();
                    },1000);
                }else{
                    alerterr(data.info);
                }
            }
        })
    }

    function checkorderstatus(){
        var val=$('#orderstatus').val();
        if(parseInt(val)==1){
            $("#shenhestatus").show();
        }else{
            $("#shenhestatus").hide();
            $("#msg").hide();
            $("#money").hide();
        }
    }
    function checkshenhestatus(){
        var val=$('#shenhestatus').val();
        switch(parseInt(val)){
            case 0:
                $("#msg").show();
                $("#money").hide();
                break;
            case 1:
                $("#msg").hide();
                $("#money").show();
                break;
            default:
                $("#msg").hide();
                $("#money").hide();
                break;
        }
//        if(parseInt(val)==0 || $.trim(val)==""){
//            $("#msg").show();
//            $("#money").hide();
//        }else{
//            $("#msg").hide();
//            $("#money").show();
//        }
    }

    function showmodal(id,status){
        $("#orderid").val(id);
        $("#orderstatus").val(status);
        checkorderstatus();
        checkshenhestatus();
    }

    function confirmshenhe(){
        var orderid=$("#orderid").val();
        var orderstatus=$('#orderstatus').val();
        var shenhestatus=$('#shenhestatus').val();
        var msg=$("#msg").val();
        var money=$("#money").val();
        $.ajax({
            url:ADMIN_PATH+"/Api/confirmshenhe.html",
            data:{id:orderid,status:orderstatus,shenhestatus:shenhestatus,msg:msg,money:money},
            type:"POST",
            success: function (data) {
                if(data.status==1){
                    alertok(data.info);
                    setTimeout(function () {
                        reloadwin();
                    })
                }else{
                    alerterr(data.info);
                }
            }
        })
    }

    function noticeoverdue(id){
        bootbox.confirm("确认要发送贷款逾期短信提醒吗？", function(result) {
            if (result) {
                $.ajax({
                    url:ADMIN_PATH+"/Api/sendnotice.html",
                    data:{id:id},
                    type:"POST",
                    success: function (data) {
                        if(data.status==1){
                            alertok(data.info);
                            setTimeout(function () {
                                reloadwin();
                            })
                        }else{
                            alerterr(data.info);
                        }
                    }
                })
            };
        });
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
        $("#info").html(" <img id=\"loadingimg\" style=\"display:none;position: relative;height: 100%;\" src=\"/Public/Home/images/loading.gif\"/>");
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